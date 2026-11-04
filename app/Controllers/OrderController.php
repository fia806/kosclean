<?php
/**
 * Class OrderController
 * Controller untuk menangani pemrosesan checkout dan integrasi Midtrans Snap API (Production Mode)
 */
class OrderController extends Controller
{
    private PesananModel $pesananModel;
    private LayananModel $layananModel;

    public function __construct()
    {
        $this->pesananModel = $this->model('PesananModel');
        $this->layananModel = $this->model('LayananModel');
    }

    /**
     * Memproses pesanan dan melakukan request Snap Token ke Midtrans Production API
     */
    public function processCheckout()
    {
        // 1. Panggil file konfigurasi midtrans (Kredensial otomatis ditarik dari .env)
        require_once __DIR__ . '/../../config/midtrans.php';

        // 2. Ambil data dari formulir pesanan user
        $idLayanan = (int)($_POST['id_layanan'] ?? 1);
        $layanan   = $this->layananModel->getById($idLayanan);

        $namaLayanan = $layanan['nama_layanan'] ?? 'Jasa Pembersihan Kos';
        $grossAmount = (int)($layanan['harga_dasar'] ?? ($_POST['total_harga'] ?? 50000));

        // Buat Order ID unik (Contoh: CLEAN-20261030-ABCDE)
        $orderId = 'CLEAN-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));

        $namaPelanggan     = trim($_POST['nama'] ?? ($_SESSION['nama_lengkap'] ?? 'Pelanggan KosClean'));
        $emailPelanggan    = trim($_POST['email'] ?? ($_SESSION['email'] ?? 'pelanggan@kosclean.com'));
        $noTelpPelanggan   = trim($_POST['no_telp'] ?? ($_SESSION['no_telp'] ?? '081234567890'));
        $alamatPembersihan = trim($_POST['alamat_pembersihan'] ?? 'Semarang, Jawa Tengah');

        // 3. Siapkan parameter payload yang dikirim ke Midtrans API
        // PENTING: JANGAN sertakan parameter enabled_payments di dalam payload agar Midtrans otomatis memuat semua opsi pembayaran
        $transaction = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => [
                [
                    'id'       => (string)$idLayanan,
                    'price'    => $grossAmount,
                    'quantity' => 1,
                    'name'     => substr($namaLayanan, 0, 50),
                ]
            ],
            'customer_details' => [
                'first_name' => $namaPelanggan,
                'email'      => $emailPelanggan,
                'phone'      => $noTelpPelanggan,
                'billing_address' => [
                    'first_name' => $namaPelanggan,
                    'address'    => $alamatPembersihan
                ]
            ],
        ];

        try {
            // 4. Dapatkan Snap Token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($transaction);

            // 5. Simpan pesanan ke database (Status awal: 'belum_bayar')
            $idUser = $_SESSION['user_id'] ?? 1;
            $orderData = [
                'id_pesanan'          => $orderId,
                'id_pelanggan'        => $idUser,
                'id_layanan'          => $idLayanan,
                'tanggal_jadwal'      => $_POST['tanggal_jadwal'] ?? date('Y-m-d', strtotime('+1 day')),
                'waktu_jadwal'        => $_POST['waktu_jadwal'] ?? '09:00:00',
                'alamat_pembersihan'  => $alamatPembersihan,
                'patokan_lokasi'      => $_POST['patokan_lokasi'] ?? null,
                'total_harga'         => $grossAmount,
                'metode_pembayaran'   => 'midtrans',
                'status_pembayaran'   => 'belum_bayar',
                'status_pesanan'      => 'menunggu_konfirmasi',
                'midtrans_snap_token' => $snapToken
            ];

            $this->pesananModel->create($orderData);

            // 6. Jika pemanggilan dilakukan via AJAX, kembalikan JSON
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                $this->json([
                    'status'    => 'success',
                    'snapToken' => $snapToken,
                    'clientKey' => \Midtrans\Config::$clientKey,
                    'orderId'   => $orderId
                ]);
            }

            // 7. Render halaman View checkout
            $data = [
                'title'       => 'Selesaikan Pembayaran - KosClean',
                'snapToken'   => $snapToken,
                'clientKey'   => \Midtrans\Config::$clientKey,
                'orderId'     => $orderId,
                'grossAmount' => $grossAmount,
                'layanan'     => $namaLayanan,
                'pelanggan'   => [
                    'nama'    => $namaPelanggan,
                    'email'   => $emailPelanggan,
                    'no_telp' => $noTelpPelanggan
                ]
            ];

            $this->view('pesanan/checkout', $data);

        } catch (Exception $e) {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                $this->json([
                    'status'  => 'error',
                    'message' => 'Gagal mendapatkan Snap Token: ' . $e->getMessage()
                ], 500);
            }

            die('Gagal mendapatkan token Midtrans: ' . htmlspecialchars($e->getMessage()));
        }
    }

    /**
     * Halaman Sukses Pembayaran
     */
    public function success()
    {
        $orderId = $_GET['order_id'] ?? '';
        $pesanan = !empty($orderId) ? $this->pesananModel->getByIdWithDetails($orderId) : null;

        $this->view('booking/success', [
            'title'   => 'Pembayaran Berhasil - KosClean',
            'pesanan' => $pesanan,
            'linkWa'  => 'https://wa.me/6281234567890?text=' . rawurlencode("Halo Admin KosClean, saya sudah menyelesaikan pembayaran untuk Order #" . $orderId)
        ]);
    }
}
