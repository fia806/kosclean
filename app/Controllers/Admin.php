<?php
/**
 * Class Admin
 * Controller Back-Office untuk Administrator & Akuntansi (Order QC, Layanan CRUD, Jurnal Umum, Laba/Rugi)
 */
class Admin extends Controller
{
    private PesananModel $pesananModel;
    private LayananModel $layananModel;
    private TugasKaryawanModel $tugasModel;
    private AccountingModel $accountingModel;
    private UserModel $userModel;
    private AbsensiModel $absensiModel;

    public function __construct()
    {
        require_role(['admin'], 'auth/login');
        $this->pesananModel    = $this->model('PesananModel');
        $this->layananModel    = $this->model('LayananModel');
        $this->tugasModel      = $this->model('TugasKaryawanModel');
        $this->accountingModel = $this->model('AccountingModel');
        $this->userModel       = $this->model('UserModel');
        $this->absensiModel    = $this->model('AbsensiModel');
    }

    /**
     * Dashboard Utama Admin
     */
    public function index()
    {
        // 1. Data Ringkasan Metrik
        $allOrders = $this->pesananModel->getAllWithDetails();
        $totalOrders = count($allOrders);
        $pendingOrders = count(array_filter($allOrders, fn($o) => in_array($o['status_pesanan'], ['menunggu_konfirmasi', 'menunggu_jadwal'])));
        $inProgressOrders = count(array_filter($allOrders, fn($o) => $o['status_pesanan'] === 'sedang_dikerjakan'));
        $completedOrders = count(array_filter($allOrders, fn($o) => $o['status_pesanan'] === 'selesai'));

        // 2. Data Grafik Omzet Bulanan dari Jurnal Detail (Akun Pendapatan)
        $chartData = $this->accountingModel->getMonthlyRevenueChartData();

        // 3. Pesanan Terbaru
        $recentOrders = array_slice($allOrders, 0, 5);

        $data = [
            'title'            => 'Admin Dashboard - KosClean',
            'totalOrders'      => $totalOrders,
            'pendingOrders'    => $pendingOrders,
            'inProgressOrders' => $inProgressOrders,
            'completedOrders'  => $completedOrders,
            'chartData'        => $chartData,
            'recentOrders'     => $recentOrders
        ];

        $this->view('admin/index', $data);
    }

    /**
     * Manajemen Pesanan (Order Management)
     */
    public function orders()
    {
        $statusFilter = $_GET['status'] ?? '';
        if (!empty($statusFilter)) {
            $orders = $this->pesananModel->getByStatusPesanan($statusFilter);
        } else {
            $orders = $this->pesananModel->getAllWithDetails();
        }

        // Ambil daftar cleaner untuk modal penugasan
        $cleaners = $this->userModel->getByRole('karyawan');

        $data = [
            'title'        => 'Manajemen Pesanan - KosClean',
            'orders'       => $orders,
            'cleaners'     => $cleaners,
            'statusFilter' => $statusFilter
        ];

        $this->view('admin/orders', $data);
    }

    /**
     * Detail Pesanan & Peninjauan Foto QC (Sebelum / Sesudah)
     */
    public function orderDetail($id_pesanan = null)
    {
        if (empty($id_pesanan)) {
            $this->redirect('admin/orders');
        }

        $pesanan = $this->pesananModel->getByIdWithDetails($id_pesanan);
        if (!$pesanan) {
            set_flash('danger', 'Pesanan tidak ditemukan.');
            $this->redirect('admin/orders');
        }

        // Ambil tugas cleaner dan foto QC
        $tugas = $this->tugasModel->getByPesananId($id_pesanan);
        $cleaners = $this->userModel->getByRole('karyawan');

        $data = [
            'title'    => 'Detail Pesanan #' . $id_pesanan,
            'pesanan'  => $pesanan,
            'tugas'    => $tugas,
            'cleaners' => $cleaners
        ];

        $this->view('admin/order_detail', $data);
    }

    /**
     * Tugaskan Cleaner ke Pesanan
     */
    public function assignCleaner()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            $this->redirect('admin/orders');
        }

        $id_pesanan = trim($_POST['id_pesanan'] ?? '');
        $id_karyawan = (int)($_POST['id_karyawan'] ?? 0);

        if (!empty($id_pesanan) && !empty($id_karyawan)) {
            $this->tugasModel->assign($id_pesanan, $id_karyawan);
            // Ubah status pesanan ke 'menunggu_jadwal' jika sebelumnya masih 'menunggu_konfirmasi'
            $pesanan = $this->pesananModel->getByIdWithDetails($id_pesanan);
            if ($pesanan && $pesanan['status_pesanan'] === 'menunggu_konfirmasi') {
                $this->pesananModel->updateStatusPesanan($id_pesanan, 'menunggu_jadwal');
            }
            set_flash('success', 'Cleaner berhasil ditugaskan untuk pesanan #' . $id_pesanan);
        } else {
            set_flash('danger', 'Pilih cleaner yang valid.');
        }

        $this->redirect('admin/orderDetail/' . $id_pesanan);
    }

    /**
     * ACTION: "Selesaikan Pesanan"
     * 1. Ubah status pesanan -> 'selesai', status pembayaran -> 'lunas'
     * 2. Trigger insert otomatis ke tabel jurnal_umum dan jurnal_detail (Debet: Kas, Kredit: Pendapatan)
     */
    public function completeOrder($id_pesanan = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf() || empty($id_pesanan)) {
            $this->redirect('admin/orders');
        }

        $pesanan = $this->pesananModel->getByIdWithDetails($id_pesanan);
        if (!$pesanan) {
            set_flash('danger', 'Pesanan tidak ditemukan.');
            $this->redirect('admin/orders');
        }

        if ($pesanan['status_pesanan'] === 'selesai') {
            set_flash('info', 'Pesanan ini sudah berstatus selesai sebelumnya.');
            $this->redirect('admin/orderDetail/' . $id_pesanan);
        }

        // 1. Update status pesanan & pembayaran
        $this->pesananModel->updateStatusPesanan($id_pesanan, 'selesai');
        $this->tugasModel->completeTaskByPesananId($id_pesanan);

        // Jika pesanan belum lunas (misal metode tunai di lokasi), catat penerimaan kas terlebih dahulu
        if ($pesanan['status_pembayaran'] !== 'lunas') {
            $this->pesananModel->updateStatusPembayaran($id_pesanan, 'lunas');
            $this->accountingModel->recordUnearnedRevenue(
                $id_pesanan,
                (float)$pesanan['total_harga'],
                $pesanan['metode_pembayaran'],
                $_SESSION['user_id']
            );
        }

        // 2. TRIGGER AKUNTANSI 2: Pengakuan Pendapatan Jasa
        // Debet: Pendapatan Diterima di Muka (212) | Kredit: Pendapatan Jasa Kebersihan (411)
        $this->accountingModel->recordEarnedRevenue(
            $id_pesanan,
            (float)$pesanan['total_harga'],
            $_SESSION['user_id']
        );

        set_flash('success', "Pesanan #{$id_pesanan} berhasil diselesaikan! Pendapatan telah diakui dan dibukukan ke akun Pendapatan Jasa Kebersihan.");
        $this->redirect('admin/orderDetail/' . $id_pesanan);
    }

    /**
     * CRUD Layanan: Daftar Layanan
     */
    public function layanan()
    {
        $layananList = $this->layananModel->getAll();
        $data = [
            'title'       => 'Manajemen Layanan - KosClean',
            'layananList' => $layananList
        ];
        $this->view('admin/layanan', $data);
    }

    /**
     * Simpan / Tambah Layanan Baru
     */
    public function layananStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            $this->redirect('admin/layanan');
        }

        $nama_layanan  = trim($_POST['nama_layanan'] ?? '');
        $deskripsi     = trim($_POST['deskripsi'] ?? '');
        $harga_dasar   = (float)($_POST['harga_dasar'] ?? 0);
        $estimasi_waktu= trim($_POST['estimasi_waktu'] ?? '');
        $status_layanan= trim($_POST['status_layanan'] ?? 'aktif');

        $gambar_ikon = null;
        if (!empty($_FILES['gambar_ikon']['name'])) {
            $upload = upload_file($_FILES['gambar_ikon'], __DIR__ . '/../../public/uploads/layanan', ['jpg', 'jpeg', 'png'], 2097152);
            if ($upload['success']) {
                $gambar_ikon = $upload['fileName'];
            }
        }

        $this->layananModel->create([
            'nama_layanan'  => $nama_layanan,
            'deskripsi'     => $deskripsi,
            'harga_dasar'   => $harga_dasar,
            'estimasi_waktu'=> $estimasi_waktu,
            'gambar_ikon'   => $gambar_ikon,
            'status_layanan'=> $status_layanan
        ]);

        set_flash('success', 'Layanan baru berhasil ditambahkan.');
        $this->redirect('admin/layanan');
    }

    /**
     * Update Layanan
     */
    public function layananUpdate($id_layanan = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf() || empty($id_layanan)) {
            $this->redirect('admin/layanan');
        }

        $nama_layanan  = trim($_POST['nama_layanan'] ?? '');
        $deskripsi     = trim($_POST['deskripsi'] ?? '');
        $harga_dasar   = (float)($_POST['harga_dasar'] ?? 0);
        $estimasi_waktu= trim($_POST['estimasi_waktu'] ?? '');
        $status_layanan= trim($_POST['status_layanan'] ?? 'aktif');

        $updateData = [
            'nama_layanan'  => $nama_layanan,
            'deskripsi'     => $deskripsi,
            'harga_dasar'   => $harga_dasar,
            'estimasi_waktu'=> $estimasi_waktu,
            'status_layanan'=> $status_layanan
        ];

        if (!empty($_FILES['gambar_ikon']['name'])) {
            $upload = upload_file($_FILES['gambar_ikon'], __DIR__ . '/../../public/uploads/layanan', ['jpg', 'jpeg', 'png'], 2097152);
            if ($upload['success']) {
                $updateData['gambar_ikon'] = $upload['fileName'];
            }
        }

        $this->layananModel->update((int)$id_layanan, $updateData);
        set_flash('success', 'Data layanan berhasil diperbarui.');
        $this->redirect('admin/layanan');
    }

    /**
     * Hapus Layanan
     */
    public function layananDelete($id_layanan = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf() || empty($id_layanan)) {
            $this->redirect('admin/layanan');
        }

        $this->layananModel->delete((int)$id_layanan);
        set_flash('success', 'Layanan berhasil dihapus.');
        $this->redirect('admin/layanan');
    }

    /**
     * Accounting: Halaman Jurnal Umum & Form Input Pengeluaran
     */
    public function jurnal()
    {
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate   = $_GET['end'] ?? date('Y-m-d');

        $jurnals   = $this->accountingModel->getJurnalUmumWithDetails($startDate, $endDate);
        $bebanCoa  = $this->accountingModel->getCoaByKategori('Beban');
        $kasCoa    = $this->accountingModel->getCoaByKategori('Harta');

        $data = [
            'title'     => 'Jurnal Umum (Double-Entry) - KosClean',
            'jurnals'   => $jurnals,
            'bebanCoa'  => $bebanCoa,
            'kasCoa'    => $kasCoa,
            'startDate' => $startDate,
            'endDate'   => $endDate
        ];

        $this->view('admin/jurnal', $data);
    }

    /**
     * Accounting: Simpan Transaksi Pengeluaran Manual (Gaji / Beban Operasional)
     * Controller insert ke jurnal_umum, lalu 2 baris ke jurnal_detail (Debet: Beban, Kredit: Kas)
     */
    public function jurnalStore()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            $this->redirect('admin/jurnal');
        }

        $tanggal    = trim($_POST['tanggal_transaksi'] ?? date('Y-m-d'));
        $deskripsi  = trim($_POST['deskripsi'] ?? '');
        $referensi  = trim($_POST['referensi'] ?? 'MANUAL');
        $kodeBeban  = trim($_POST['kode_beban'] ?? '');
        $kodeKas    = trim($_POST['kode_kas'] ?? '111');
        $nominal    = (float)($_POST['nominal'] ?? 0);

        if (empty($deskripsi) || empty($kodeBeban) || empty($kodeKas) || $nominal <= 0) {
            set_flash('danger', 'Harap lengkapi semua field transaksi pengeluaran dengan benar.');
            $this->redirect('admin/jurnal');
        }

        $details = [
            [
                'kode_akun' => $kodeBeban,
                'posisi'    => 'Debet',
                'nominal'   => $nominal
            ],
            [
                'kode_akun' => $kodeKas,
                'posisi'    => 'Kredit',
                'nominal'   => $nominal
            ]
        ];

        $res = $this->accountingModel->createJurnalEntry(
            $tanggal,
            $deskripsi,
            $referensi,
            $_SESSION['user_id'],
            $details
        );

        if ($res) {
            set_flash('success', 'Transaksi pengeluaran berhasil dibukukan secara seimbang ke Jurnal Umum.');
        } else {
            set_flash('danger', 'Gagal membukukan transaksi ke Jurnal Umum.');
        }

        $this->redirect('admin/jurnal');
    }

    /**
     * Accounting: Laporan Laba / Rugi
     */
    public function labaRugi()
    {
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate   = $_GET['end'] ?? date('Y-m-t');

        $report = $this->accountingModel->getLabaRugiReport($startDate, $endDate);

        $data = [
            'title'  => 'Laporan Laba / Rugi - KosClean',
            'report' => $report
        ];

        $this->view('admin/laba_rugi', $data);
    }

    /**
     * Rekapitulasi Presensi Cleaner Harian (Jam Masuk, Jam Pulang, Foto Selfie Kantor)
     */
    public function absensi()
    {
        $selectedDate = $_GET['tanggal'] ?? date('Y-m-d');
        $attendanceList = $this->absensiModel->getAllWithKaryawan($selectedDate);
        $summary = $this->absensiModel->getDailySummary($selectedDate);

        $data = [
            'title'          => 'Rekapitulasi Presensi Cleaner - KosClean',
            'selectedDate'   => $selectedDate,
            'attendanceList' => $attendanceList,
            'summary'        => $summary
        ];

        $this->view('admin/absensi', $data);
    }

    /**
     * Modul Manajemen Aset & Peralatan (Depresiasi)
     * Menampilkan tabel semua aset beserta kalkulasi penyusutan bulanan
     */
    public function aset()
    {
        $asetModel = $this->model('AsetModel');
        $asets     = $asetModel->getAll();

        $data = [
            'title' => 'Manajemen Aset & Depresiasi - KosClean',
            'asets' => $asets
        ];

        $this->view('admin/aset', $data);
    }

    /**
     * Proses Hitung Penyusutan Bulanan (POST)
     * TRIGGER AKUNTANSI 4: Dr. Beban Penyusutan (513) | Cr. Akumulasi Penyusutan (122)
     */
    public function asetHitungPenyusutan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            $this->redirect('admin/aset');
        }

        $periodeBulan = trim($_POST['periode_bulan'] ?? date('Y-m'));

        // Validasi format YYYY-MM
        if (!preg_match('/^\d{4}-\d{2}$/', $periodeBulan)) {
            set_flash('danger', 'Format periode tidak valid. Gunakan format YYYY-MM.');
            $this->redirect('admin/aset');
        }

        $asetModel = $this->model('AsetModel');
        $result    = $asetModel->processMonthlyDepreciation($periodeBulan, (int)$_SESSION['user_id']);

        if ($result['total_aset'] > 0) {
            set_flash('success',
                "Penyusutan bulan {$periodeBulan} berhasil dibukukan! " .
                "{$result['total_aset']} aset diproses. Total beban penyusutan: " .
                format_rupiah($result['total_nominal']) . ". " .
                count($result['jurnal_ids']) . " entri jurnal telah dibuat."
            );
        } else {
            set_flash('info',
                "Tidak ada aset baru yang diproses untuk periode {$periodeBulan}. " .
                "Kemungkinan sudah pernah dihitung bulan ini, atau belum ada aset " .
                "berkategori 'alat_kebersihan' dengan harga beli & umur ekonomis."
            );
        }

        $this->redirect('admin/aset');
    }
}
