<?php
/**
 * Class AbsensiModel
 * Model untuk mengelola tabel 'absensi'
 */
class AbsensiModel
{
    private string $table = 'absensi';
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Cek absensi karyawan pada hari ini
     */
    public function getTodayAttendance(int $id_karyawan)
    {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM {$this->table} WHERE id_karyawan = :id_karyawan AND tanggal = :tanggal LIMIT 1";
        $this->db->query($sql);
        $this->db->bind(':id_karyawan', $id_karyawan);
        $this->db->bind(':tanggal', $today);
        return $this->db->single();
    }

    /**
     * Clock-In karyawan dengan foto selfie kedatangan di kantor
     */
    public function clockIn(int $id_karyawan, ?string $fotoMasuk = null)
    {
        // Cek jika sudah clock in hari ini
        $existing = $this->getTodayAttendance($id_karyawan);
        if ($existing) {
            return false;
        }

        $sql = "INSERT INTO {$this->table} (id_karyawan, tanggal, jam_masuk, foto_masuk, status_kehadiran, status_presensi)
                VALUES (:id_karyawan, CURDATE(), CURTIME(), :foto_masuk, 'hadir', 'aktif_bekerja')";

        $this->db->query($sql);
        $this->db->bind(':id_karyawan', $id_karyawan);
        $this->db->bind(':foto_masuk', $fotoMasuk);

        if ($this->db->execute()) {
            return (int) $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Clock-Out karyawan dengan foto selfie kepulangan di kantor
     */
    public function clockOut(int $id_karyawan, ?string $fotoKeluar = null): bool
    {
        $today = date('Y-m-d');
        $sql = "UPDATE {$this->table} 
                SET jam_keluar = CURTIME(), 
                    foto_keluar = :foto_keluar, 
                    status_presensi = 'selesai_shift'
                WHERE id_karyawan = :id_karyawan AND tanggal = :tanggal AND jam_keluar IS NULL";

        $this->db->query($sql);
        $this->db->bind(':id_karyawan', $id_karyawan);
        $this->db->bind(':tanggal', $today);
        $this->db->bind(':foto_keluar', $fotoKeluar);
        return $this->db->execute() && $this->db->rowCount() > 0;
    }

    /**
     * Riwayat absensi karyawan tertentu
     */
    public function getHistory(int $id_karyawan, int $limit = 30): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_karyawan = :id_karyawan ORDER BY tanggal DESC, jam_masuk DESC LIMIT :limit";
        $this->db->query($sql);
        $this->db->bind(':id_karyawan', $id_karyawan);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    /**
     * Mengambil seluruh data absensi lengkap dengan data karyawan (Untuk Admin Rekapitulasi)
     */
    public function getAllWithKaryawan(?string $tanggal = null): array
    {
        if (!empty($tanggal)) {
            $sql = "SELECT a.*, u.nama_lengkap AS nama_karyawan, u.no_telp AS telp_karyawan, u.email AS email_karyawan
                    FROM {$this->table} a
                    JOIN users u ON a.id_karyawan = u.id_user
                    WHERE a.tanggal = :tanggal
                    ORDER BY a.jam_masuk DESC";
            $this->db->query($sql);
            $this->db->bind(':tanggal', $tanggal);
        } else {
            $sql = "SELECT a.*, u.nama_lengkap AS nama_karyawan, u.no_telp AS telp_karyawan, u.email AS email_karyawan
                    FROM {$this->table} a
                    JOIN users u ON a.id_karyawan = u.id_user
                    ORDER BY a.tanggal DESC, a.jam_masuk DESC";
            $this->db->query($sql);
        }

        return $this->db->resultSet();
    }

    /**
     * Statistik kehadiran harian untuk Admin
     */
    public function getDailySummary(string $tanggal): array
    {
        $sql = "SELECT 
                    COUNT(*) AS total_hadir,
                    SUM(CASE WHEN status_presensi = 'aktif_bekerja' THEN 1 ELSE 0 END) AS total_aktif,
                    SUM(CASE WHEN status_presensi = 'selesai_shift' THEN 1 ELSE 0 END) AS total_selesai
                FROM {$this->table}
                WHERE tanggal = :tanggal";
        $this->db->query($sql);
        $this->db->bind(':tanggal', $tanggal);
        return $this->db->single() ?: ['total_hadir' => 0, 'total_aktif' => 0, 'total_selesai' => 0];
    }
}
