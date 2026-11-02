<?php
/**
 * Class AccountingModel
 * Model untuk mengelola COA, Jurnal Umum, Jurnal Detail, Laba/Rugi, Arus Kas, dan Siklus Akuntansi SAK
 */
class AccountingModel
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Mengambil seluruh Chart of Accounts (COA)
     */
    public function getCoa(): array
    {
        $this->db->query("SELECT * FROM coa ORDER BY kode_akun ASC");
        return $this->db->resultSet();
    }

    /**
     * Mengambil akun berdasarkan kategori (misal: 'Beban', 'Harta', 'Kewajiban', 'Pendapatan')
     */
    public function getCoaByKategori(string $kategori): array
    {
        $this->db->query("SELECT * FROM coa WHERE kategori = :kategori ORDER BY kode_akun ASC");
        $this->db->bind(':kategori', $kategori);
        return $this->db->resultSet();
    }

    /**
     * Mengambil daftar jurnal umum beserta rincian detailnya
     */
    public function getJurnalUmumWithDetails(?string $startDate = null, ?string $endDate = null): array
    {
        $sql = "SELECT j.*, u.nama_lengkap AS pembuat
                FROM jurnal_umum j
                LEFT JOIN users u ON j.created_by = u.id_user";

        $conditions = [];
        if (!empty($startDate)) {
            $conditions[] = "j.tanggal_transaksi >= :startDate";
        }
        if (!empty($endDate)) {
            $conditions[] = "j.tanggal_transaksi <= :endDate";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        $sql .= " ORDER BY j.tanggal_transaksi DESC, j.id_jurnal DESC";

        $this->db->query($sql);
        if (!empty($startDate)) $this->db->bind(':startDate', $startDate);
        if (!empty($endDate)) $this->db->bind(':endDate', $endDate);

        $jurnals = $this->db->resultSet();

        // Ambil detail debet & kredit untuk setiap jurnal
        foreach ($jurnals as &$jurnal) {
            $sqlDetail = "SELECT jd.*, c.nama_akun, c.kategori
                          FROM jurnal_detail jd
                          JOIN coa c ON jd.kode_akun = c.kode_akun
                          WHERE jd.id_jurnal = :id_jurnal
                          ORDER BY jd.posisi ASC, jd.id_detail ASC";
            $this->db->query($sqlDetail);
            $this->db->bind(':id_jurnal', $jurnal['id_jurnal']);
            $jurnal['details'] = $this->db->resultSet();
        }

        return $jurnals;
    }

    /**
     * Mencatat transaksi ganda (Double-Entry Journal) secara ACID
     *
     * @param string $tanggal
     * @param string $deskripsi
     * @param string|null $referensi
     * @param int|null $createdBy
     * @param array $details Array of ['kode_akun' => string, 'posisi' => 'Debet'|'Kredit', 'nominal' => float]
     * @return int|false ID Jurnal baru atau false jika gagal
     */
    public function createJurnalEntry(string $tanggal, string $deskripsi, ?string $referensi, ?int $createdBy, array $details)
    {
        // Validasi keseimbangan Debet & Kredit
        $totalDebet = 0;
        $totalKredit = 0;
        foreach ($details as $d) {
            if ($d['posisi'] === 'Debet') $totalDebet += (float)$d['nominal'];
            if ($d['posisi'] === 'Kredit') $totalKredit += (float)$d['nominal'];
        }

        if (abs($totalDebet - $totalKredit) > 0.01) {
            return false; // Jurnal tidak seimbang!
        }

        try {
            $this->db->beginTransaction();

            // 1. Insert ke jurnal_umum
            $sqlJurnal = "INSERT INTO jurnal_umum (tanggal_transaksi, deskripsi, referensi, created_by)
                          VALUES (:tanggal, :deskripsi, :referensi, :created_by)";
            $this->db->query($sqlJurnal);
            $this->db->bind(':tanggal', $tanggal);
            $this->db->bind(':deskripsi', $deskripsi);
            $this->db->bind(':referensi', $referensi);
            $this->db->bind(':created_by', $createdBy);
            $this->db->execute();

            $id_jurnal = (int) $this->db->lastInsertId();

            // 2. Insert ke jurnal_detail
            $sqlDetail = "INSERT INTO jurnal_detail (id_jurnal, kode_akun, posisi, nominal)
                          VALUES (:id_jurnal, :kode_akun, :posisi, :nominal)";

            foreach ($details as $d) {
                $this->db->query($sqlDetail);
                $this->db->bind(':id_jurnal', $id_jurnal);
                $this->db->bind(':kode_akun', $d['kode_akun']);
                $this->db->bind(':posisi', $d['posisi']);
                $this->db->bind(':nominal', $d['nominal']);
                $this->db->execute();
            }

            $this->db->commit();
            return $id_jurnal;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * TRIGGER AKUNTANSI 1:
     * Saat Pesanan Lunas dibayar (Uang Masuk), dicatat sebagai Pendapatan Diterima di Muka
     * Debet: Kas/Bank (112 atau 111) | Kredit: Pendapatan Diterima di Muka (212)
     */
    public function recordUnearnedRevenue(string $id_pesanan, float $totalHarga, string $metodePembayaran, ?int $userId = null): bool
    {
        // Cek idempotensi: Jangan jurnal ulang jika pesanan ini sudah pernah dicatat uang mukanya
        $this->db->query("SELECT id_jurnal FROM jurnal_umum WHERE referensi = :ref AND deskripsi LIKE 'Penerimaan Pembayaran%' LIMIT 1");
        $this->db->bind(':ref', $id_pesanan);
        if ($this->db->single()) {
            return true; // Sudah pernah dicatat sebelumnya
        }

        $kodeKas = ($metodePembayaran === 'midtrans') ? '112' : '111'; // 112 Kas Bank, 111 Kas Tunai
        $kodeUangMuka = '212'; // Pendapatan Diterima di Muka (Kewajiban)

        $details = [
            [
                'kode_akun' => $kodeKas,
                'posisi'    => 'Debet',
                'nominal'   => $totalHarga
            ],
            [
                'kode_akun' => $kodeUangMuka,
                'posisi'    => 'Kredit',
                'nominal'   => $totalHarga
            ]
        ];

        $deskripsi = "Penerimaan Pembayaran Pesanan #" . $id_pesanan . " (Uang Muka Jasa)";
        return (bool) $this->createJurnalEntry(date('Y-m-d'), $deskripsi, $id_pesanan, $userId, $details);
    }

    /**
     * TRIGGER AKUNTANSI 2:
     * Saat Pengerjaan Jasa Selesai & QC Disetujui (Kewajiban Gugur -> Pendapatan Diakui)
     * Debet: Pendapatan Diterima di Muka (212) | Kredit: Pendapatan Jasa Kebersihan (411)
     */
    public function recordEarnedRevenue(string $id_pesanan, float $totalHarga, ?int $userId = null): bool
    {
        // Cek idempotensi
        $this->db->query("SELECT id_jurnal FROM jurnal_umum WHERE referensi = :ref AND deskripsi LIKE 'Pengakuan Pendapatan%' LIMIT 1");
        $this->db->bind(':ref', $id_pesanan);
        if ($this->db->single()) {
            return true;
        }

        $kodeUangMuka   = '212'; // Pendapatan Diterima di Muka
        $kodePendapatan = '411'; // Pendapatan Jasa Kebersihan

        $details = [
            [
                'kode_akun' => $kodeUangMuka,
                'posisi'    => 'Debet',
                'nominal'   => $totalHarga
            ],
            [
                'kode_akun' => $kodePendapatan,
                'posisi'    => 'Kredit',
                'nominal'   => $totalHarga
            ]
        ];

        $deskripsi = "Pengakuan Pendapatan Jasa Selesai Pesanan #" . $id_pesanan;
        return (bool) $this->createJurnalEntry(date('Y-m-d'), $deskripsi, $id_pesanan, $userId, $details);
    }

    /**
     * Fallback / Backward Compatibility:
     * Pencatatan langsung (jika sistem menggunakan metode kas murni)
     */
    public function recordOrderRevenue(string $id_pesanan, float $totalHarga, string $metodePembayaran, ?int $adminId): bool
    {
        return $this->recordEarnedRevenue($id_pesanan, $totalHarga, $adminId);
    }

    /**
     * Mengambil data omzet bulanan untuk grafik Chart.js
     * Ditarik dari akun Pendapatan (kategori Pendapatan / kode 411) posisi Kredit pada jurnal_detail
     */
    public function getMonthlyRevenueChartData(): array
    {
        $sql = "SELECT DATE_FORMAT(j.tanggal_transaksi, '%Y-%m') AS bulan, 
                       DATE_FORMAT(j.tanggal_transaksi, '%b %Y') AS label_bulan,
                       SUM(jd.nominal) AS total_omzet
                FROM jurnal_detail jd
                JOIN jurnal_umum j ON jd.id_jurnal = j.id_jurnal
                JOIN coa c ON jd.kode_akun = c.kode_akun
                WHERE c.kategori = 'Pendapatan' AND jd.posisi = 'Kredit'
                GROUP BY bulan
                ORDER BY bulan ASC
                LIMIT 12";

        $this->db->query($sql);
        $rows = $this->db->resultSet();

        $labels = [];
        $data = [];

        foreach ($rows as $row) {
            $labels[] = $row['label_bulan'];
            $data[] = (float)$row['total_omzet'];
        }

        if (empty($labels)) {
            $labels = [date('M Y')];
            $data = [0];
        }

        return [
            'labels' => $labels,
            'data'   => $data
        ];
    }

    /**
     * Menghitung Laporan Laba / Rugi (Pendapatan - Beban) berdasarkan filter rentang tanggal
     */
    public function getLabaRugiReport(?string $startDate = null, ?string $endDate = null): array
    {
        $startDate = $startDate ?: date('Y-m-01');
        $endDate   = $endDate ?: date('Y-m-t');

        // 1. Akun-akun Pendapatan (Kredit menambah pendapatan)
        $sqlPendapatan = "SELECT c.kode_akun, c.nama_akun, 
                                 COALESCE(SUM(CASE WHEN jd.posisi = 'Kredit' THEN jd.nominal ELSE -jd.nominal END), 0) AS saldo
                          FROM coa c
                          LEFT JOIN jurnal_detail jd ON c.kode_akun = jd.kode_akun
                          LEFT JOIN jurnal_umum j ON jd.id_jurnal = j.id_jurnal AND j.tanggal_transaksi BETWEEN :startDate AND :endDate
                          WHERE c.kategori = 'Pendapatan'
                          GROUP BY c.kode_akun, c.nama_akun";

        $this->db->query($sqlPendapatan);
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        $pendapatanItems = $this->db->resultSet();

        $totalPendapatan = 0;
        foreach ($pendapatanItems as $p) {
            $totalPendapatan += (float)$p['saldo'];
        }

        // 2. Akun-akun Beban (Debet menambah beban)
        $sqlBeban = "SELECT c.kode_akun, c.nama_akun, 
                            COALESCE(SUM(CASE WHEN jd.posisi = 'Debet' THEN jd.nominal ELSE -jd.nominal END), 0) AS saldo
                     FROM coa c
                     LEFT JOIN jurnal_detail jd ON c.kode_akun = jd.kode_akun
                     LEFT JOIN jurnal_umum j ON jd.id_jurnal = j.id_jurnal AND j.tanggal_transaksi BETWEEN :startDate AND :endDate
                     WHERE c.kategori = 'Beban'
                     GROUP BY c.kode_akun, c.nama_akun";

        $this->db->query($sqlBeban);
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        $bebanItems = $this->db->resultSet();

        $totalBeban = 0;
        foreach ($bebanItems as $b) {
            $totalBeban += (float)$b['saldo'];
        }

        $labaBersih = $totalPendapatan - $totalBeban;

        return [
            'startDate'       => $startDate,
            'endDate'         => $endDate,
            'pendapatanItems' => $pendapatanItems,
            'totalPendapatan' => $totalPendapatan,
            'bebanItems'      => $bebanItems,
            'totalBeban'      => $totalBeban,
            'labaBersih'      => $labaBersih
        ];
    }

    /**
     * Laporan Arus Kas (Cash Flow Statement - Direct Method)
     * Mengambil seluruh pergerakan mutasi kas & bank (111 dan 112)
     */
    public function getCashFlowReport(string $startDate, string $endDate): array
    {
        $sql = "SELECT j.tanggal_transaksi, j.deskripsi, j.referensi, jd.kode_akun, jd.posisi, jd.nominal, c.nama_akun
                FROM jurnal_detail jd
                JOIN jurnal_umum j ON jd.id_jurnal = j.id_jurnal
                JOIN coa c ON jd.kode_akun = c.kode_akun
                WHERE jd.kode_akun IN ('111', '112') 
                  AND j.tanggal_transaksi BETWEEN :startDate AND :endDate
                ORDER BY j.tanggal_transaksi ASC, j.id_jurnal ASC";

        $this->db->query($sql);
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        $mutasi = $this->db->resultSet();

        $arusKasMasuk = 0;
        $arusKasKeluar = 0;

        foreach ($mutasi as $m) {
            if ($m['posisi'] === 'Debet') {
                $arusKasMasuk += (float)$m['nominal'];
            } else {
                $arusKasKeluar += (float)$m['nominal'];
            }
        }

        return [
            'startDate'     => $startDate,
            'endDate'       => $endDate,
            'mutasi'        => $mutasi,
            'total_masuk'   => $arusKasMasuk,
            'total_keluar'  => $arusKasKeluar,
            'kenaikan_kas'  => $arusKasMasuk - $arusKasKeluar
        ];
    }

    /**
     * Neraca Saldo (Trial Balance)
     * GROUP BY kode_akun, SUM Debet & Kredit untuk semua akun COA dalam rentang tanggal
     */
    public function getNeracaSaldo(string $startDate, string $endDate): array
    {
        $sql = "SELECT c.kode_akun, c.nama_akun, c.kategori, c.saldo_normal,
                       COALESCE(SUM(CASE WHEN jd.posisi = 'Debet'  THEN jd.nominal ELSE 0 END), 0) AS total_debet,
                       COALESCE(SUM(CASE WHEN jd.posisi = 'Kredit' THEN jd.nominal ELSE 0 END), 0) AS total_kredit
                FROM coa c
                LEFT JOIN jurnal_detail jd ON c.kode_akun = jd.kode_akun
                LEFT JOIN jurnal_umum   ju ON jd.id_jurnal = ju.id_jurnal
                    AND ju.tanggal_transaksi BETWEEN :startDate AND :endDate
                GROUP BY c.kode_akun, c.nama_akun, c.kategori, c.saldo_normal
                ORDER BY c.kode_akun ASC";

        $this->db->query($sql);
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        $rows = $this->db->resultSet();

        $grandDebet  = 0;
        $grandKredit = 0;
        foreach ($rows as $row) {
            $grandDebet  += (float)$row['total_debet'];
            $grandKredit += (float)$row['total_kredit'];
        }

        return [
            'rows'         => $rows,
            'grand_debet'  => $grandDebet,
            'grand_kredit' => $grandKredit,
            'is_balanced'  => abs($grandDebet - $grandKredit) < 0.01,
            'startDate'    => $startDate,
            'endDate'      => $endDate
        ];
    }

    /**
     * Neraca Keuangan (Balance Sheet) per tanggal tertentu
     * Aktiva (Harta) = Pasiva (Kewajiban + Modal)
     * Laba berjalan otomatis dimasukkan ke komponen Modal
     */
    public function getNeracaKeuangan(string $endDate): array
    {
        // Query saldo semua akun Harta, Kewajiban, Modal s.d endDate
        $sql = "SELECT c.kode_akun, c.nama_akun, c.kategori, c.saldo_normal,
                       COALESCE(SUM(CASE WHEN jd.posisi = 'Debet'  THEN jd.nominal ELSE 0 END), 0) AS total_debet,
                       COALESCE(SUM(CASE WHEN jd.posisi = 'Kredit' THEN jd.nominal ELSE 0 END), 0) AS total_kredit
                FROM coa c
                LEFT JOIN jurnal_detail jd ON c.kode_akun = jd.kode_akun
                LEFT JOIN jurnal_umum   ju ON jd.id_jurnal = ju.id_jurnal
                    AND ju.tanggal_transaksi <= :endDate
                WHERE c.kategori IN ('Harta', 'Kewajiban', 'Modal')
                GROUP BY c.kode_akun, c.nama_akun, c.kategori, c.saldo_normal
                ORDER BY c.kode_akun ASC";

        $this->db->query($sql);
        $this->db->bind(':endDate', $endDate);
        $rows = $this->db->resultSet();

        $harta      = [];
        $kewajiban  = [];
        $modal      = [];
        $totalHarta = $totalKewajiban = $totalModal = 0;

        foreach ($rows as $row) {
            // Saldo bersih: ikuti saldo normal akun
            $saldo = ($row['saldo_normal'] === 'Debet')
                ? (float)$row['total_debet'] - (float)$row['total_kredit']
                : (float)$row['total_kredit'] - (float)$row['total_debet'];

            $item = array_merge($row, ['saldo' => $saldo]);

            switch ($row['kategori']) {
                case 'Harta':
                    $harta[]     = $item;
                    $totalHarta += $saldo;
                    break;
                case 'Kewajiban':
                    $kewajiban[]     = $item;
                    $totalKewajiban += $saldo;
                    break;
                case 'Modal':
                    $modal[]     = $item;
                    $totalModal += $saldo;
                    break;
            }
        }

        // Laba berjalan (dari L/R s.d. endDate) dimasukkan ke komponen Modal
        $lr           = $this->getLabaRugiReport(null, $endDate);
        $labaBerjalan = (float)$lr['labaBersih'];
        $totalModal  += $labaBerjalan;
        $totalPassiva = $totalKewajiban + $totalModal;

        return [
            'endDate'        => $endDate,
            'harta'          => $harta,
            'totalHarta'     => $totalHarta,
            'kewajiban'      => $kewajiban,
            'totalKewajiban' => $totalKewajiban,
            'modal'          => $modal,
            'labaBerjalan'   => $labaBerjalan,
            'totalModal'     => $totalModal,
            'totalPassiva'   => $totalPassiva,
            'is_balanced'    => abs($totalHarta - $totalPassiva) < 1
        ];
    }

    /**
     * Mutasi Kas & Bank — saldo awal + pergerakan periode
     * Akun 111 (Kas Tunai) & 112 (Kas Bank)
     */
    public function getMutasiKeuangan(string $startDate, string $endDate): array
    {
        // Saldo awal: semua transaksi sebelum startDate untuk akun kas & bank
        $sqlSaldoAwal = "SELECT COALESCE(
                             SUM(CASE WHEN jd.posisi = 'Debet'  THEN jd.nominal ELSE 0 END) -
                             SUM(CASE WHEN jd.posisi = 'Kredit' THEN jd.nominal ELSE 0 END),
                         0) AS saldo_awal
                         FROM jurnal_detail jd
                         JOIN jurnal_umum ju ON jd.id_jurnal = ju.id_jurnal
                         WHERE jd.kode_akun IN ('111', '112')
                           AND ju.tanggal_transaksi < :startDate";

        $this->db->query($sqlSaldoAwal);
        $this->db->bind(':startDate', $startDate);
        $saldoAwalRow = $this->db->single();
        $saldoAwal    = (float)($saldoAwalRow['saldo_awal'] ?? 0);

        // Mutasi dalam periode
        $sqlMutasi = "SELECT ju.tanggal_transaksi, ju.deskripsi, ju.referensi,
                             jd.kode_akun, c.nama_akun, jd.posisi, jd.nominal
                      FROM jurnal_detail jd
                      JOIN jurnal_umum ju ON jd.id_jurnal = ju.id_jurnal
                      JOIN coa c ON jd.kode_akun = c.kode_akun
                      WHERE jd.kode_akun IN ('111', '112')
                        AND ju.tanggal_transaksi BETWEEN :startDate AND :endDate
                      ORDER BY ju.tanggal_transaksi ASC, ju.id_jurnal ASC";

        $this->db->query($sqlMutasi);
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        $mutasi = $this->db->resultSet();

        $totalMasuk  = 0;
        $totalKeluar = 0;
        foreach ($mutasi as $m) {
            if ($m['posisi'] === 'Debet') {
                $totalMasuk += (float)$m['nominal'];
            } else {
                $totalKeluar += (float)$m['nominal'];
            }
        }

        return [
            'startDate'    => $startDate,
            'endDate'      => $endDate,
            'saldo_awal'   => $saldoAwal,
            'mutasi'       => $mutasi,
            'total_masuk'  => $totalMasuk,
            'total_keluar' => $totalKeluar,
            'saldo_akhir'  => $saldoAwal + $totalMasuk - $totalKeluar
        ];
    }
}
