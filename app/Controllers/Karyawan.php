<?php
/**
 * Class Karyawan
 * Controller Back-Office untuk Cleaner / Karyawan (Absensi & Manajemen Tugas Lapangan)
 */
class Karyawan extends Controller
{
    private AbsensiModel $absensiModel;
    private TugasKaryawanModel $tugasModel;
    private PesananModel $pesananModel;

    public function __construct()
    {
        require_role(['karyawan', 'admin'], 'auth/login');
        $this->absensiModel = $this->model('AbsensiModel');
        $this->tugasModel = $this->model('TugasKaryawanModel');
        $this->pesananModel = $this->model('PesananModel');
    }

    /**
     * Dashboard Karyawan / Cleaner
     */
    public function index()
    {
        $id_karyawan = $_SESSION['user_id'];

        // Cek absensi hari ini
        $todayAttendance = $this->absensiModel->getTodayAttendance($id_karyawan);

        // Ambil daftar tugas yang ditugaskan ke cleaner ini
        $myTasks = $this->tugasModel->getByKaryawanId($id_karyawan);

        $data = [
            'title'           => 'Dashboard Cleaner - KosClean',
            'todayAttendance' => $todayAttendance,
            'myTasks'         => $myTasks
        ];

        $this->view('karyawan/index', $data);
    }

    /**
     * Memproses upload foto selfie (bisa via file input kamera atau base64 snapshot webcam)
     */
    private function processSelfieUpload(string $fileKey, string $base64Key): ?string
    {
        $targetDir = __DIR__ . '/../../public/uploads/absensi';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // 1. Cek unggahan file / native camera capture
        if (!empty($_FILES[$fileKey]['name'])) {
            $upload = upload_file($_FILES[$fileKey], $targetDir, ['jpg', 'jpeg', 'png', 'webp'], 4194304);
            if ($upload['success']) {
                return $upload['fileName'];
            }
        }

        // 2. Cek base64 snapshot dari WebRTC webcam
        if (!empty($_POST[$base64Key])) {
            $base64Data = $_POST[$base64Key];
            if (preg_match('/^data:image\/(png|jpeg|jpg|webp);base64,/', $base64Data, $matches)) {
                $ext = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                $data = substr($base64Data, strpos($base64Data, ',') + 1);
                $decoded = base64_decode($data);
                if ($decoded !== false) {
                    $filename = 'selfie_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                    if (file_put_contents($targetDir . '/' . $filename, $decoded)) {
                        return $filename;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Aksi Presensi Masuk (Check-In Masuk Shift) dengan Foto Selfie di Kantor
     */
    public function clockIn()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            set_flash('danger', 'Permintaan presensi tidak valid.');
            $this->redirect('karyawan');
        }

        $id_karyawan = $_SESSION['user_id'];

        // Cek jika sudah presensi hari ini
        $existing = $this->absensiModel->getTodayAttendance($id_karyawan);
        if ($existing) {
            set_flash('warning', 'Anda sudah melakukan Presensi Masuk (Check-In) hari ini.');
            $this->redirect('karyawan');
        }

        $fotoMasuk = $this->processSelfieUpload('foto_masuk', 'foto_masuk_base64');
        if (!$fotoMasuk) {
            set_flash('danger', 'Foto selfie presensi di kantor wajib diambil/dilampirkan sebelum Check-In.');
            $this->redirect('karyawan');
        }

        $res = $this->absensiModel->clockIn($id_karyawan, $fotoMasuk);

        if ($res) {
            set_flash('success', 'Presensi Masuk Berhasil! Waktu kedatangan dan foto kantor telah dicatat. Status: Aktif / Bekerja.');
        } else {
            set_flash('warning', 'Gagal memproses presensi masuk.');
        }

        $this->redirect('karyawan');
    }

    /**
     * Aksi Presensi Keluar (Check-Out Selesai Shift) dengan Foto Selfie Kepulangan di Kantor
     */
    public function clockOut()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf()) {
            set_flash('danger', 'Permintaan presensi tidak valid.');
            $this->redirect('karyawan');
        }

        $id_karyawan = $_SESSION['user_id'];
        $existing = $this->absensiModel->getTodayAttendance($id_karyawan);

        if (!$existing) {
            set_flash('danger', 'Anda belum melakukan Presensi Masuk hari ini.');
            $this->redirect('karyawan');
        }

        if (!empty($existing['jam_keluar'])) {
            set_flash('info', 'Anda sudah melakukan Presensi Pulang (Selesai Shift) hari ini.');
            $this->redirect('karyawan');
        }

        $fotoKeluar = $this->processSelfieUpload('foto_keluar', 'foto_keluar_base64');
        if (!$fotoKeluar) {
            set_flash('danger', 'Foto selfie presensi kepulangan di kantor wajib diambil/dilampirkan sebelum Check-Out.');
            $this->redirect('karyawan');
        }

        $res = $this->absensiModel->clockOut($id_karyawan, $fotoKeluar);

        if ($res) {
            set_flash('success', 'Presensi Pulang Berhasil! Waktu kepulangan dan foto telah dicatat. Status: Selesai Shift. Terima kasih atas kerja keras Anda!');
        } else {
            set_flash('warning', 'Gagal memproses presensi pulang.');
        }

        $this->redirect('karyawan');
    }

    /**
     * Mulai Tugas: Wajib Presensi Masuk & upload foto_sebelum
     */
    public function startTask($id_tugas = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf() || empty($id_tugas)) {
            $this->redirect('karyawan');
        }

        $id_karyawan = $_SESSION['user_id'];

        // Sebelum mulai bertugas atau mengambil jadwal pembersihan, Cleaner WAJIB melakukan presensi datang di kantor
        $todayAttendance = $this->absensiModel->getTodayAttendance($id_karyawan);
        if (!$todayAttendance) {
            set_flash('warning', 'Sebelum mulai bertugas atau mengambil jadwal pembersihan, Cleaner wajib melakukan presensi datang di kantor terlebih dahulu.');
            $this->redirect('karyawan');
        }

        $task = $this->tugasModel->getById((int)$id_tugas);

        if (!$task || $task['id_karyawan'] != $id_karyawan) {
            set_flash('danger', 'Tugas tidak ditemukan.');
            $this->redirect('karyawan');
        }

        if (empty($_FILES['foto_sebelum']['name'])) {
            set_flash('danger', 'Wajib melampirkan foto kondisi ruangan sebelum dibersihkan (foto_sebelum).');
            $this->redirect('karyawan');
        }

        $upload = upload_file($_FILES['foto_sebelum'], __DIR__ . '/../../public/uploads/tasks', ['jpg', 'jpeg', 'png'], 2097152);

        if (!$upload['success']) {
            set_flash('danger', 'Upload foto sebelum gagal: ' . $upload['error']);
            $this->redirect('karyawan');
        }

        // Update tugas ke 'proses'
        $this->tugasModel->startTask((int)$id_tugas, $upload['fileName']);

        // Update status pesanan ke 'sedang_dikerjakan'
        $this->pesananModel->updateStatusPesanan($task['id_pesanan'], 'sedang_dikerjakan');

        set_flash('success', 'Tugas berhasil dimulai. Foto SEBELUM pengerjaan telah tersimpan!');
        $this->redirect('karyawan');
    }

    /**
     * Selesai Tugas: Wajib upload foto_sesudah -> set status ke 'menunggu_qc'
     */
    public function finishTask($id_tugas = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !verify_csrf() || empty($id_tugas)) {
            $this->redirect('karyawan');
        }

        $id_karyawan = $_SESSION['user_id'];
        $task = $this->tugasModel->getById((int)$id_tugas);

        if (!$task || $task['id_karyawan'] != $id_karyawan) {
            set_flash('danger', 'Tugas tidak ditemukan.');
            $this->redirect('karyawan');
        }

        if (empty($_FILES['foto_sesudah']['name'])) {
            set_flash('danger', 'Wajib melampirkan foto hasil pembersihan ruangan (foto_sesudah).');
            $this->redirect('karyawan');
        }

        $upload = upload_file($_FILES['foto_sesudah'], __DIR__ . '/../../public/uploads/tasks', ['jpg', 'jpeg', 'png'], 2097152);

        if (!$upload['success']) {
            set_flash('danger', 'Upload foto sesudah gagal: ' . $upload['error']);
            $this->redirect('karyawan');
        }

        // Update status_tugas ke 'menunggu_qc'
        $this->tugasModel->finishTask((int)$id_tugas, $upload['fileName']);

        set_flash('success', 'Pekerjaan selesai! Foto SESUDAH berhasil diunggah dan kini menunggu Quality Control (QC) dari Admin.');
        $this->redirect('karyawan');
    }
}
