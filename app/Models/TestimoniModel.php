<?php
/**
 * Class TestimoniModel
 * Model untuk mengelola tabel 'testimoni'
 */
class TestimoniModel
{
    private string $table = 'testimoni';
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Mengambil testimoni yang berstatus 'tampilkan' untuk landing page
     */
    public function getPublished(int $limit = 6): array
    {
        $sql = "SELECT t.*, u.nama_lengkap AS nama_pelanggan, l.nama_layanan
                FROM {$this->table} t
                JOIN users u ON t.id_pelanggan = u.id_user
                JOIN pesanan p ON t.id_pesanan = p.id_pesanan
                LEFT JOIN pesanan_detail pd ON p.id_pesanan = pd.id_pesanan
                LEFT JOIN layanan l ON pd.id_layanan = l.id_layanan
                WHERE t.status_publikasi = 'tampilkan'
                ORDER BY t.id_testimoni DESC
                LIMIT :limit";

        $this->db->query($sql);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Cek apakah pesanan tertentu sudah memiliki testimoni
     */
    public function getByPesananId(string $id_pesanan)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_pesanan = :id_pesanan LIMIT 1";
        $this->db->query($sql);
        $this->db->bind(':id_pesanan', $id_pesanan);
        return $this->db->single();
    }

    /**
     * Mengambil semua testimoni milik pelanggan tertentu
     */
    public function getByPelangganId(int $id_pelanggan): array
    {
        $sql = "SELECT t.*, p.tanggal_jadwal, l.nama_layanan 
                FROM {$this->table} t
                LEFT JOIN pesanan p ON t.id_pesanan = p.id_pesanan
                LEFT JOIN pesanan_detail pd ON p.id_pesanan = pd.id_pesanan
                LEFT JOIN layanan l ON pd.id_layanan = l.id_layanan
                WHERE t.id_pelanggan = :id_pelanggan 
                ORDER BY t.id_testimoni DESC";
        $this->db->query($sql);
        $this->db->bind(':id_pelanggan', $id_pelanggan);
        return $this->db->resultSet();
    }

    /**
     * Tambah testimoni baru oleh pelanggan (Hanya kolom foto_testimoni sesuai revisi database terbaru)
     */
    public function create(array $data)
    {
        $fotoTestimoni = $data['foto_testimoni'] ?? ($data['foto_after'] ?? ($data['foto_before'] ?? null));

        $sql = "INSERT INTO {$this->table} (id_pesanan, id_pelanggan, rating, ulasan, foto_testimoni, status_publikasi)
                VALUES (:id_pesanan, :id_pelanggan, :rating, :ulasan, :foto_testimoni, :status_publikasi)";

        $this->db->query($sql);
        $this->db->bind(':id_pesanan', $data['id_pesanan']);
        $this->db->bind(':id_pelanggan', $data['id_pelanggan']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':ulasan', $data['ulasan']);
        $this->db->bind(':foto_testimoni', $fotoTestimoni);
        $this->db->bind(':status_publikasi', $data['status_publikasi'] ?? 'tampilkan');

        if ($this->db->execute()) {
            return (int) $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Ubah status publikasi oleh Admin
     */
    public function updatePublikasi(int $id_testimoni, string $status): bool
    {
        $sql = "UPDATE {$this->table} SET status_publikasi = :status WHERE id_testimoni = :id_testimoni";
        $this->db->query($sql);
        $this->db->bind(':status', $status);
        $this->db->bind(':id_testimoni', $id_testimoni);
        return $this->db->execute();
    }
}
