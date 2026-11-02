<?php
/**
 * Class LayananModel
 * Model untuk mengelola tabel 'layanan'
 */
class LayananModel
{
    private string $table = 'layanan';
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Mengambil semua layanan
     */
    public function getAll(): array
    {
        $this->db->query("SELECT * FROM {$this->table} ORDER BY id_layanan ASC");
        return $this->db->resultSet();
    }

    /**
     * Mengambil hanya layanan yang berstatus aktif (untuk katalog/booking pelanggan)
     */
    public function getActive(): array
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE status_layanan = 'aktif' ORDER BY id_layanan ASC");
        return $this->db->resultSet();
    }

    /**
     * Mengambil satu layanan berdasarkan id_layanan
     */
    public function getById(int $id_layanan)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE id_layanan = :id_layanan LIMIT 1");
        $this->db->bind(':id_layanan', $id_layanan);
        return $this->db->single();
    }

    /**
     * Tambah data layanan baru
     */
    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} (nama_layanan, deskripsi, harga_dasar, estimasi_waktu, gambar_ikon, status_layanan) 
                VALUES (:nama_layanan, :deskripsi, :harga_dasar, :estimasi_waktu, :gambar_ikon, :status_layanan)";
        
        $this->db->query($sql);
        $this->db->bind(':nama_layanan', $data['nama_layanan']);
        $this->db->bind(':deskripsi', $data['deskripsi']);
        $this->db->bind(':harga_dasar', $data['harga_dasar']);
        $this->db->bind(':estimasi_waktu', $data['estimasi_waktu']);
        $this->db->bind(':gambar_ikon', $data['gambar_ikon'] ?? null);
        $this->db->bind(':status_layanan', $data['status_layanan'] ?? 'aktif');

        if ($this->db->execute()) {
            return (int) $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Perbarui data layanan
     */
    public function update(int $id_layanan, array $data): bool
    {
        $sql = "UPDATE {$this->table} 
                SET nama_layanan = :nama_layanan, 
                    deskripsi = :deskripsi, 
                    harga_dasar = :harga_dasar, 
                    estimasi_waktu = :estimasi_waktu, 
                    status_layanan = :status_layanan";
        
        if (!empty($data['gambar_ikon'])) {
            $sql .= ", gambar_ikon = :gambar_ikon";
        }
        $sql .= " WHERE id_layanan = :id_layanan";

        $this->db->query($sql);
        $this->db->bind(':id_layanan', $id_layanan);
        $this->db->bind(':nama_layanan', $data['nama_layanan']);
        $this->db->bind(':deskripsi', $data['deskripsi']);
        $this->db->bind(':harga_dasar', $data['harga_dasar']);
        $this->db->bind(':estimasi_waktu', $data['estimasi_waktu']);
        $this->db->bind(':status_layanan', $data['status_layanan']);
        if (!empty($data['gambar_ikon'])) {
            $this->db->bind(':gambar_ikon', $data['gambar_ikon']);
        }

        return $this->db->execute();
    }

    /**
     * Hapus layanan
     */
    public function delete(int $id_layanan): bool
    {
        $this->db->query("DELETE FROM {$this->table} WHERE id_layanan = :id_layanan");
        $this->db->bind(':id_layanan', $id_layanan);
        return $this->db->execute();
    }
}
