<?php
/**
 * Class Pelanggan
 * Controller untuk dashboard pelanggan, riwayat pesanan, dan form review/testimoni
 */
class Pelanggan extends Controller
{
    private PesananModel $pesananModel;
    private TestimoniModel $testimoniModel;

    public function __construct()
    {
        require_role(['pelanggan', 'admin'], 'auth/login');
        $this->pesananModel = $this->model('PesananModel');
        $this->testimoniModel = $this->model('TestimoniModel');
    }

    /**
     * Dashboard Pelanggan: Menampilkan riwayat pesanan & status aktif
     */
    public function dashboard()
    {
        $id_pelanggan = $_SESSION['user_id'];
        $pesananList = $this->pesananModel->getByPelangganId($id_pelanggan);

        // Cek pesanan mana saja yang sudah memiliki ulasan
        $reviewedOrders = [];
        $myReviews = $this->testimoniModel->getByPelangganId($id_pelanggan);
        foreach ($myReviews as $rev) {
            $reviewedOrders[$rev['id_pesanan']] = $rev;
        }

        $data = [
            'title'          => 'Dashboard Saya - KosClean',
            'pesananList'    => $pesananList,
            'reviewedOrders' => $reviewedOrders
        ];

        $this->view('pelanggan/dashboard', $data);
    }

    /**
     * Beri Ulasan / Testimoni untuk pesanan yang sudah 'selesai'
     */
    public function testimoni($id_pesanan = null)
    {
        if (empty($id_pesanan)) {
            $this->redirect('pelanggan/dashboard');
        }

        $pesanan = $this->pesananModel->getByIdWithDetails($id_pesanan);

        if (!$pesanan || $pesanan['id_pelanggan'] != $_SESSION['user_id']) {
            set_flash('danger', 'Data pesanan tidak ditemukan atau bukan milik Anda.');
            $this->redirect('pelanggan/dashboard');
        }

        // Testimoni HANYA boleh jika status_pesanan = 'selesai' dan pembayaran bukan 'belum_bayar'
        if ($pesanan['status_pesanan'] !== 'selesai' || $pesanan['status_pembayaran'] === 'belum_bayar') {
            set_flash('warning', 'Ulasan hanya dapat diberikan setelah pembersihan selesai dilakukan oleh tim dan pembayaran telah lunas.');
            $this->redirect('pelanggan/dashboard');
        }

        // Cek jika sudah pernah diulas
        $existing = $this->testimoniModel->getByPesananId($id_pesanan);
        if ($existing) {
            set_flash('info', 'Anda sudah memberikan ulasan untuk pesanan ini.');
            $this->redirect('pelanggan/dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                set_flash('danger', 'Token CSRF tidak valid. Silakan coba kembali.');
                $this->redirect('pelanggan/testimoni/' . $id_pesanan);
            }

            $rating = (int)($_POST['rating'] ?? 5);
            $ulasan = trim($_POST['ulasan'] ?? '');

            if ($rating < 1 || $rating > 5) {
                $rating = 5;
            }

            if (empty($ulasan)) {
                set_flash('danger', 'Harap isi teks ulasan atau kesan pembersihan Anda.');
                $this->view('pelanggan/testimoni', ['title' => 'Beri Ulasan Pesanan #' . $id_pesanan, 'pesanan' => $pesanan]);
                return;
            }

            $uploadDir = __DIR__ . '/../../public/uploads/testimoni';
            $allowedExts = ['jpg', 'jpeg', 'png', 'webp'];
            $maxBytes = 2097152; // 2MB

            $fotoBefore = null;
            // 1. Upload Foto Sebelum (Before Photo) - Opsional
            if (!empty($_FILES['foto_before']['name'])) {
                $uploadBefore = upload_file($_FILES['foto_before'], $uploadDir, $allowedExts, $maxBytes);
                if (!$uploadBefore['success']) {
                    set_flash('danger', 'Gagal upload Foto Sebelum (Before): ' . $uploadBefore['error']);
                    $this->view('pelanggan/testimoni', ['title' => 'Beri Ulasan Pesanan #' . $id_pesanan, 'pesanan' => $pesanan]);
                    return;
                }
                $fotoBefore = $uploadBefore['fileName'];
            }

            $fotoAfter = null;
            // 2. Upload Foto Sesudah (After Photo) - Opsional
            if (!empty($_FILES['foto_after']['name'])) {
                $uploadAfter = upload_file($_FILES['foto_after'], $uploadDir, $allowedExts, $maxBytes);
                if (!$uploadAfter['success']) {
                    set_flash('danger', 'Gagal upload Foto Sesudah (After): ' . $uploadAfter['error']);
                    $this->view('pelanggan/testimoni', ['title' => 'Beri Ulasan Pesanan #' . $id_pesanan, 'pesanan' => $pesanan]);
                    return;
                }
                $fotoAfter = $uploadAfter['fileName'];
            }

            // Fallback backward compatibility jika input foto_testimoni dikirim
            if (empty($fotoAfter) && !empty($_FILES['foto_testimoni']['name'])) {
                $uploadLegacy = upload_file($_FILES['foto_testimoni'], $uploadDir, $allowedExts, $maxBytes);
                if ($uploadLegacy['success']) {
                    $fotoAfter = $uploadLegacy['fileName'];
                }
            }

            $save = $this->testimoniModel->create([
                'id_pesanan'       => $id_pesanan,
                'id_pelanggan'     => $_SESSION['user_id'],
                'rating'           => $rating,
                'ulasan'           => $ulasan,
                'foto_before'      => $fotoBefore,
                'foto_after'       => $fotoAfter,
                'foto_testimoni'   => $fotoAfter ?? $fotoBefore,
                'status_publikasi' => 'tampilkan'
            ]);

            if ($save) {
                set_flash('success', 'Terima kasih! Ulasan dan foto Before-After Anda berhasil disimpan dan ditayangkan.');
                $this->redirect('pelanggan/dashboard');
            } else {
                set_flash('danger', 'Terjadi kesalahan sistem saat menyimpan ulasan Anda.');
            }
        }

        $data = [
            'title'   => 'Beri Ulasan Pesanan #' . $id_pesanan,
            'pesanan' => $pesanan
        ];

        $this->view('pelanggan/testimoni', $data);
    }
}
