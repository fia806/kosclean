<?php
/**
 * Class PenggajianModel
 * Model untuk mengelola kalkulasi Payroll Karyawan berdasarkan pesanan selesai & integrasi Jurnal Penggajian
 */
class PenggajianModel
{
    private string $table = 'penggajian';
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Menghitung total pesanan dengan status 'selesai' dari tabel tugas_karyawan untuk karyawan tertentu pada periode tertentu
     *
     * @param int $id_karyawan
     * @param string $periodeBulan Format: 'YYYY-MM'
     * @return int
     */
    public function getCompletedTasksCount(int $id_karyawan, string $periodeBulan): int
    {
        $sql = "SELECT COUNT(tk.id_tugas) AS total_selesai
                FROM tugas_karyawan tk
                JOIN pesanan p ON tk.id_pesanan = p.id_pesanan
                WHERE tk.id_karyawan = :id_karyawan 
                  AND tk.status_tugas = 'selesai'
                  AND DATE_FORMAT(p.tanggal_jadwal, '%Y-%m') = :periode";

        $this->db->query($sql);
        $this->db->bind(':id_karyawan', $id_karyawan);
        $this->db->bind(':periode', $periodeBulan);
        $row = $this->db->single();
        return (int)($row['total_selesai'] ?? 0);
    }

    /**
     * Mengambil daftar rekapitulasi tugas selesai seluruh karyawan pada suatu bulan untuk form penggajian
     */
    public function getPayrollSummaryByPeriod(string $periodeBulan): array
    {
        $sql = "SELECT u.id_user AS id_karyawan, u.nama_lengkap, u.no_telp,
                       COUNT(CASE WHEN tk.status_tugas = 'selesai' AND DATE_FORMAT(p.tanggal_jadwal, '%Y-%m') = :periode THEN tk.id_tugas END) AS total_pesanan_selesai,
                       pg.id_penggajian, pg.total_gaji, pg.tanggal_bayar, pg.id_jurnal
                FROM users u
                LEFT JOIN tugas_karyawan tk ON u.id_user = tk.id_karyawan
                LEFT JOIN pesanan p ON tk.id_pesanan = p.id_pesanan
                LEFT JOIN {$this->table} pg ON u.id_user = pg.id_karyawan AND pg.periode_bulan = :periode2
                WHERE u.role = 'karyawan'
                GROUP BY u.id_user
                ORDER BY u.nama_lengkap ASC";

        $this->db->query($sql);
        $this->db->bind(':periode', $periodeBulan);
        $this->db->bind(':periode2', $periodeBulan);
        return $this->db->resultSet();
    }

    /**
     * Memproses Penggajian & Menginjeksi Jurnal Otomatis (TRIGGER AKUNTANSI 3)
     * Debet: Beban Gaji Karyawan (511) | Kredit: Kas Bank (112) atau Kas Tunai (111)
     */
    public function createPayrollEntry(int $id_karyawan, string $periodeBulan, float $nominalPerPesanan, string $kodeKas = '112', ?int $adminId = null): ?int
    {
        // Cek jika sudah pernah digaji pada periode ini
        $this->db->query("SELECT id_penggajian FROM {$this->table} WHERE id_karyawan = :id_karyawan AND periode_bulan = :periode LIMIT 1");
        $this->db->bind(':id_karyawan', $id_karyawan);
        $this->db->bind(':periode', $periodeBulan);
        if ($this->db->single()) {
            return null; // Sudah digaji
        }

        $jumlahPesanan = $this->getCompletedTasksCount($id_karyawan, $periodeBulan);
        $totalGaji = $jumlahPesanan * $nominalPerPesanan;

        if ($totalGaji <= 0) {
            return null; // Tidak ada nominal gaji yang dibayarkan
        }

        // Ambil nama karyawan untuk deskripsi jurnal
        $this->db->query("SELECT nama_lengkap FROM users WHERE id_user = :id_user LIMIT 1");
        $this->db->bind(':id_user', $id_karyawan);
        $user = $this->db->single();
        $namaKaryawan = $user['nama_lengkap'] ?? ('ID #' . $id_karyawan);

        $accountingModel = new AccountingModel();
        $details = [
            [
                'kode_akun' => '511', // Beban Gaji Karyawan
                'posisi'    => 'Debet',
                'nominal'   => $totalGaji
            ],
            [
                'kode_akun' => $kodeKas, // Kas Bank (112) atau Kas Tunai (111)
                'posisi'    => 'Kredit',
                'nominal'   => $totalGaji
            ]
        ];

        $deskripsi = "Pembayaran Gaji Cleaner {$namaKaryawan} ({$jumlahPesanan} Pesanan @ " . number_format($nominalPerPesanan, 0, ',', '.') . ") Periode {$periodeBulan}";
        $referensi = "GAJI-{$periodeBulan}-{$id_karyawan}";

        $id_jurnal = $accountingModel->createJurnalEntry(date('Y-m-d'), $deskripsi, $referensi, $adminId, $details);

        if (!$id_jurnal) {
            return null;
        }

        // Simpan ke tabel penggajian
        $sql = "INSERT INTO {$this->table} (id_karyawan, periode_bulan, jumlah_pesanan, nominal_per_pesanan, total_gaji, id_jurnal, tanggal_bayar)
                VALUES (:id_karyawan, :periode_bulan, :jumlah_pesanan, :nominal_per_pesanan, :total_gaji, :id_jurnal, :tanggal_bayar)";

        $this->db->query($sql);
        $this->db->bind(':id_karyawan', $id_karyawan);
        $this->db->bind(':periode_bulan', $periodeBulan);
        $this->db->bind(':jumlah_pesanan', $jumlahPesanan);
        $this->db->bind(':nominal_per_pesanan', $nominalPerPesanan);
        $this->db->bind(':total_gaji', $totalGaji);
        $this->db->bind(':id_jurnal', $id_jurnal);
        $this->db->bind(':tanggal_bayar', date('Y-m-d'));

        if ($this->db->execute()) {
            return (int) $this->db->lastInsertId();
        }

        return null;
    }

    /**
     * Mengambil riwayat penggajian lengkap dengan relasi ke users dan jurnal_umum
     */
    public function getAllWithDetails(?string $periodeBulan = null): array
    {
        $sql = "SELECT pg.*, u.nama_lengkap, u.no_telp, j.deskripsi AS deskripsi_jurnal
                FROM {$this->table} pg
                JOIN users u ON pg.id_karyawan = u.id_user
                LEFT JOIN jurnal_umum j ON pg.id_jurnal = j.id_jurnal";

        if (!empty($periodeBulan)) {
            $sql .= " WHERE pg.periode_bulan = :periode";
        }

        $sql .= " ORDER BY pg.tanggal_bayar DESC, pg.id_penggajian DESC";

        $this->db->query($sql);
        if (!empty($periodeBulan)) {
            $this->db->bind(':periode', $periodeBulan);
        }

        return $this->db->resultSet();
    }
}

