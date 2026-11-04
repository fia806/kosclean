<?php
/**
 * Class TugasKaryawanModel
 * Model untuk mengelola tabel 'tugas_karyawan'
 */
class TugasKaryawanModel
{
    private string $table = 'tugas_karyawan';
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Mengambil daftar tugas berdasarkan id_karyawan
     */
    public function getByKaryawanId(int $id_karyawan): array
    {
        $sql = "SELECT tk.*, 
                       p.tanggal_jadwal, p.waktu_jadwal, p.alamat_pembersihan, p.patokan_lokasi, p.total_harga, p.status_pesanan,
                       l.nama_layanan, l.estimasi_waktu,
                       u.nama_lengkap AS nama_pelanggan, u.no_telp AS telp_pelanggan
                FROM {$this->table} tk
                JOIN pesanan p ON tk.id_pesanan = p.id_pesanan
                LEFT JOIN pesanan_detail pd ON p.id_pesanan = pd.id_pesanan
                LEFT JOIN layanan l ON pd.id_layanan = l.id_layanan
                JOIN users u ON p.id_pelanggan = u.id_user
                WHERE tk.id_karyawan = :id_karyawan
                ORDER BY FIELD(tk.status_tugas, 'ditugaskan', 'proses', 'menunggu_qc', 'selesai'), p.tanggal_jadwal ASC";

        $this->db->query($sql);
        $this->db->bind(':id_karyawan', $id_karyawan);
        return $this->db->resultSet();
    }

    /**
     * Mengambil data satu tugas
     */
    public function getById(int $id_tugas)
    {
        $sql = "SELECT tk.*, 
                       p.tanggal_jadwal, p.waktu_jadwal, p.alamat_pembersihan, p.patokan_lokasi, p.total_harga, p.status_pesanan,
                       l.nama_layanan,
                       u.nama_lengkap AS nama_pelanggan, u.no_telp AS telp_pelanggan
                FROM {$this->table} tk
                JOIN pesanan p ON tk.id_pesanan = p.id_pesanan
                LEFT JOIN pesanan_detail pd ON p.id_pesanan = pd.id_pesanan
                LEFT JOIN layanan l ON pd.id_layanan = l.id_layanan
                JOIN users u ON p.id_pelanggan = u.id_user
                WHERE tk.id_tugas = :id_tugas
                LIMIT 1";

        $this->db->query($sql);
        $this->db->bind(':id_tugas', $id_tugas);
        return $this->db->single();
    }

    /**
     * Mengambil tugas berdasarkan id_pesanan (untuk Admin melihat cleaner & foto QC)
     */
    public function getByPesananId(string $id_pesanan)
    {
        $sql = "SELECT tk.*, u.nama_lengkap AS nama_cleaner, u.no_telp AS telp_cleaner
                FROM {$this->table} tk
                JOIN users u ON tk.id_karyawan = u.id_user
                WHERE tk.id_pesanan = :id_pesanan
                LIMIT 1";

        $this->db->query($sql);
        $this->db->bind(':id_pesanan', $id_pesanan);
        return $this->db->single();
    }

    /**
     * Menugaskan cleaner ke suatu pesanan
     */
    public function assign(string $id_pesanan, int $id_karyawan)
    {
        // Cek jika sudah pernah ditugaskan
        $existing = $this->getByPesananId($id_pesanan);
        if ($existing) {
            $sql = "UPDATE {$this->table} SET id_karyawan = :id_karyawan, status_tugas = 'ditugaskan' WHERE id_tugas = :id_tugas";
            $this->db->query($sql);
            $this->db->bind(':id_karyawan', $id_karyawan);
            $this->db->bind(':id_tugas', $existing['id_tugas']);
            $this->db->execute();
            return $existing['id_tugas'];
        }

        $sql = "INSERT INTO {$this->table} (id_pesanan, id_karyawan, status_tugas) 
                VALUES (:id_pesanan, :id_karyawan, 'ditugaskan')";
        $this->db->query($sql);
        $this->db->bind(':id_pesanan', $id_pesanan);
        $this->db->bind(':id_karyawan', $id_karyawan);

        if ($this->db->execute()) {
            return (int) $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Cleaner mulai tugas: upload foto_sebelum dan set status ke 'proses'
     */
    public function startTask(int $id_tugas, string $foto_sebelum): bool
    {
        $sql = "UPDATE {$this->table} 
                SET foto_sebelum = :foto_sebelum, status_tugas = 'proses' 
                WHERE id_tugas = :id_tugas";

        $this->db->query($sql);
        $this->db->bind(':foto_sebelum', $foto_sebelum);
        $this->db->bind(':id_tugas', $id_tugas);
        return $this->db->execute();
    }

    /**
     * Cleaner selesai tugas: upload foto_sesudah dan set status ke 'menunggu_qc'
     */
    public function finishTask(int $id_tugas, string $foto_sesudah): bool
    {
        $sql = "UPDATE {$this->table} 
                SET foto_sesudah = :foto_sesudah, 
                    status_tugas = 'menunggu_qc', 
                    waktu_selesai = NOW() 
                WHERE id_tugas = :id_tugas";

        $this->db->query($sql);
        $this->db->bind(':foto_sesudah', $foto_sesudah);
        $this->db->bind(':id_tugas', $id_tugas);
        return $this->db->execute();
    }

    /**
     * Update status tugas menjadi 'selesai'
     */
    public function completeTaskByPesananId(string $id_pesanan): bool
    {
        $sql = "UPDATE {$this->table} SET status_tugas = 'selesai' WHERE id_pesanan = :id_pesanan";
        $this->db->query($sql);
        $this->db->bind(':id_pesanan', $id_pesanan);
        return $this->db->execute();
    }
}
