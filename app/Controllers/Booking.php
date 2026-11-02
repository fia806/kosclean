<?php
/**
 * Class Booking
 * Controller untuk alur pemesanan jasa kebersihan oleh Pelanggan & Integrasi Midtrans Pull API
 */
class Booking extends Controller
{
    private PesananModel $pesananModel;
    private LayananModel $layananModel;
    private AccountingModel $accountingModel;

    public function __construct()
    {
        $this->pesananModel = $this->model('PesananModel');
        $this->layananModel = $this->model('LayananModel');
        $this->accountingModel = $this->model('AccountingModel');
    }

    /**
     * Halaman Formulir Pemesanan
     */
    public function index()
    {
        if (!is_logged_in()) {
            set_flash('info', 'Silakan login terlebih dahulu untuk melanjutkan pemesanan jasa bersih kos.');
            $this->redirect('auth/login?redirect=booking');
        }

        $layananList = $this->layananModel->getActive();
        $selectedLayananId = isset($_GET['layanan']) ? (int)$_GET['layanan'] : 0;

        $data = [
            'title'             => 'Pesan Jasa Bersih Kos Semarang',
            'layananList'       => $layananList,
            'selectedLayananId' => $selectedLayananId,
            'currentUser'       => current_user()
        ];

        $this->view('booking/index', $data);
    }

    /**
     * FASE 1: Proses Simpan Pesanan — HANYA via Midtrans (Tidak ada opsi Cash)
     * Status awal pesanan: 'belum_bayar'
     */
    public function store()
    {
        require_role(['pelanggan', 'admin'], 'auth/login?redirect=booking');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            set_flash('danger', 'Token keamanan CSRF tidak valid atau sesi kadaluarsa.');
            $this->redirect('booking');
        }

        $id_pelanggan       = $_SESSION['user_id'];
        $id_layanan         = (int)($_POST['id_layanan'] ?? 0);
        $tanggal_jadwal     = trim($_POST['tanggal_jadwal'] ?? '');
        $waktu_jadwal       = trim($_POST['waktu_jadwal'] ?? '');
        $alamat_pembersihan = trim($_POST['alamat_pembersihan'] ?? '');
        $patokan_lokasi     = trim($_POST['patokan_lokasi'] ?? '');

        // Metode pembayaran selalu midtrans — tidak ada opsi lain
        $metode_pembayaran = 'midtrans';

        if (empty($id_layanan) || empty($tanggal_jadwal) || empty($waktu_jadwal) || empty($alamat_pembersihan)) {
            set_flash('danger', 'Harap lengkapi semua kolom pemesanan dan alamat secara rinci.');
            $this->redirect('booking');
        }

        $layanan = $this->layananModel->getById($id_layanan);
        if (!$layanan || $layanan['status_layanan'] !== 'aktif') {
            set_flash('danger', 'Layanan yang dipilih tidak tersedia atau tidak aktif.');
            $this->redirect('booking');
        }

        $total_harga = (float)$layanan['harga_dasar'];
        $id_pesanan  = 'KBK-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

        // Dapatkan Snap Token dari Midtrans API
        require_once __DIR__ . '/../../config/midtrans.php';

        $user = current_user();
        $params = [
            'transaction_details' => [
                'order_id'     => $id_pesanan,
                'gross_amount' => (int)$total_harga,
            ],
            'customer_details' => [
                'first_name' => $user['nama_lengkap'],
                'email'      => $user['email'],
                'phone'      => $user['no_telp'] ?? '08123456789',
                'billing_address' => [
                    'address' => $alamat_pembersihan
                ]
            ],
            'item_details' => [
                [
                    'id'       => (string)$id_layanan,
                    'price'    => (int)$total_harga,
                    'quantity' => 1,
                    'name'     => substr($layanan['nama_layanan'], 0, 50)
                ]
            ]
        ];

        $snapToken = null;
        $snapError = null;

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);
        } catch (Exception $e) {
            // Catat error asli — JANGAN buat token dummy palsu
            $snapError = $e->getMessage();
        }

        // Jika token gagal didapatkan, batalkan proses dan tampilkan error
        if (empty($snapToken)) {
            $errMsg = 'Gagal menghubungi server Midtrans untuk membuat sesi pembayaran.';
            if (!empty($snapError)) {
                $errMsg .= ' Detail: ' . $snapError;
            }
            set_flash('danger', $errMsg . ' Pastikan Server Key di .env sudah benar dan koneksi internet aktif.');
            $this->redirect('booking');
        }

        $orderData = [
            'id_pesanan'          => $id_pesanan,
            'id_pelanggan'        => $id_pelanggan,
            'id_layanan'          => $id_layanan,
            'tanggal_jadwal'      => $tanggal_jadwal,
            'waktu_jadwal'        => $waktu_jadwal,
            'alamat_pembersihan'  => $alamat_pembersihan,
            'patokan_lokasi'      => $patokan_lokasi,
            'total_harga'         => $total_harga,
            'metode_pembayaran'   => $metode_pembayaran,
            'status_pembayaran'   => 'belum_bayar',
            'status_pesanan'      => 'menunggu_konfirmasi',
            'midtrans_snap_token' => $snapToken
        ];

        $createdId = $this->pesananModel->create($orderData);
        if ($createdId) {
            set_flash('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran via Midtrans.');
            $this->redirect('booking/pay/' . $id_pesanan);
        }

        set_flash('danger', 'Gagal menyimpan pesanan ke database. Silakan coba kembali.');
        $this->redirect('booking');
    }

    /**
     * Halaman Pembayaran Midtrans Snap
     */
    public function pay($id_pesanan = null)
    {
        if (empty($id_pesanan)) {
            $this->redirect('pelanggan/dashboard');
        }

        $pesanan = $this->pesananModel->getByIdWithDetails($id_pesanan);
        if (!$pesanan) {
            die("Pesanan tidak ditemukan.");
        }

        $data = [
            'title'   => 'Pembayaran Midtrans - KosClean',
            'pesanan' => $pesanan
        ];

        $this->view('booking/pay', $data);
    }

    /**
     * FASE 1: Cek Status Pembayaran Manual (Pull API Midtrans - Tanpa Webhook)
     * TRIGGER AKUNTANSI 1: Saat lunas, Debet: Kas Bank (112) vs Kredit: Pendapatan Diterima di Muka (212)
     */
    public function checkPaymentStatus($id_pesanan = null)
    {
        $id_pesanan = $id_pesanan ?? ($_GET['order_id'] ?? '');

        if (empty($id_pesanan)) {
            $this->json(['success' => false, 'message' => 'ID Pesanan wajib diisi.'], 400);
        }

        $pesanan = $this->pesananModel->getByIdWithDetails($id_pesanan);
        if (!$pesanan) {
            $this->json(['success' => false, 'message' => 'Pesanan tidak ditemukan di database.'], 404);
        }

        if ($pesanan['status_pembayaran'] === 'lunas') {
            $this->json([
                'success'           => true, 
                'status_pembayaran' => 'lunas',
                'status_pesanan'    => $pesanan['status_pesanan'],
                'message'           => 'Pesanan ini sudah berstatus LUNAS sebelumnya.'
            ]);
        }

        require_once __DIR__ . '/../../config/midtrans.php';

        try {
            // PULL status manual langsung dari API Midtrans
            $statusObj = \Midtrans\Transaction::status($id_pesanan);
            $transaction = is_object($statusObj) ? $statusObj->transaction_status : ($statusObj['transaction_status'] ?? '');
            $fraud = is_object($statusObj) ? ($statusObj->fraud_status ?? '') : ($statusObj['fraud_status'] ?? '');

            $isSettled = false;

            if ($transaction == 'capture') {
                if ($fraud == 'accept') {
                    $isSettled = true;
                }
            } else if ($transaction == 'settlement') {
                $isSettled = true;
            } else if (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                $this->pesananModel->updateStatusPesanan($id_pesanan, 'dibatalkan');
            }

            if ($isSettled) {
                // 1. Ubah status pesanan menjadi lunas & menunggu_jadwal
                $this->pesananModel->updateStatusPembayaranDanPesanan($id_pesanan, 'lunas', 'menunggu_jadwal');

                // 2. TRIGGER AKUNTANSI 1:
                // Debet: Kas Bank (112) | Kredit: Pendapatan Diterima di Muka (212)
                $this->accountingModel->recordUnearnedRevenue(
                    $id_pesanan,
                    (float)$pesanan['total_harga'],
                    'midtrans',
                    $_SESSION['user_id'] ?? null
                );

                $this->json([
                    'success'           => true,
                    'status_pembayaran' => 'lunas',
                    'status_pesanan'    => 'menunggu_jadwal',
                    'message'           => 'Pembayaran LUNAS terverifikasi! Sistem otomatis mencatat uang masuk ke Pendapatan Diterima di Muka (Jurnal Umum).'
                ]);
            }

            $this->json([
                'success'           => false,
                'status_pembayaran' => $pesanan['status_pembayaran'],
                'midtrans_status'   => $transaction,
                'message'           => 'Status terkini di Midtrans: ' . $transaction
            ]);

        } catch (Exception $e) {
            $this->json([
                'success' => false, 
                'message' => 'Gagal memverifikasi ke server Midtrans: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman Sukses Pemesanan & Konfirmasi WhatsApp
     */
    public function success($id_pesanan = null)
    {
        if (empty($id_pesanan)) {
            $this->redirect('pelanggan/dashboard');
        }

        $pesanan = $this->pesananModel->getByIdWithDetails($id_pesanan);
        if (!$pesanan) {
            die("Pesanan tidak ditemukan.");
        }

        $noWaAdmin = '6281234567890';
        $pesanWa = "Halo Admin KosClean Semarang,\n\n"
                 . "Saya ingin konfirmasi pesanan jasa bersih kos:\n"
                 . "• ID Pesanan: {$pesanan['id_pesanan']}\n"
                 . "• Nama: {$pesanan['nama_pelanggan']}\n"
                 . "• Layanan: {$pesanan['nama_layanan']}\n"
                 . "• Jadwal: {$pesanan['tanggal_jadwal']} ({$pesanan['waktu_jadwal']} WIB)\n"
                 . "• Alamat: {$pesanan['alamat_pembersihan']}\n"
                 . "• Status Bayar: " . strtoupper(str_replace('_', ' ', $pesanan['status_pembayaran'])) . "\n"
                 . "• Total: " . format_rupiah($pesanan['total_harga']) . "\n\n"
                 . "Mohon dikonfirmasi jadwal cleaner untuk lokasi saya. Terima kasih!";

        $linkWa = "https://wa.me/{$noWaAdmin}?text=" . rawurlencode($pesanWa);

        $data = [
            'title'   => 'Pesanan Berhasil - KosClean',
            'pesanan' => $pesanan,
            'linkWa'  => $linkWa
        ];

        $this->view('booking/success', $data);
    }

    /**
     * Simulasi Pembayaran Berhasil Midtrans (Untuk testing lokal cepat)
     */
    public function simulateMidtransSuccess($id_pesanan = null)
    {
        if (empty($id_pesanan)) {
            $this->redirect('pelanggan/dashboard');
        }

        $pesanan = $this->pesananModel->getByIdWithDetails($id_pesanan);
        if ($pesanan) {
            $this->pesananModel->updateStatusPembayaranDanPesanan($id_pesanan, 'lunas', 'menunggu_jadwal');
            
            // TRIGGER AKUNTANSI 1
            $this->accountingModel->recordUnearnedRevenue(
                $id_pesanan,
                (float)$pesanan['total_harga'],
                'midtrans',
                $_SESSION['user_id'] ?? null
            );
        }

        set_flash('success', 'Simulasi pembayaran via Midtrans berhasil diverifikasi & dibukukan!');
        $this->redirect('booking/success/' . $id_pesanan);
    }
}
