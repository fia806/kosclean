<?php
/**
 * Helper Functions Global
 * Berisi utilitas CSRF, sanitasi, autentikasi, format rupiah, dan upload file aman.
 */

// Inisialisasi token CSRF jika belum ada
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Menghasilkan input hidden CSRF untuk form HTML
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
}

// Memvalidasi token CSRF pada request POST
function verify_csrf(?string $token = null): bool
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return true;
    }
    $postToken = $token ?? ($_POST['csrf_token'] ?? '');
    return !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $postToken);
}

// Helper sanitasi teks untuk mencegah XSS
function e(?string $string): string
{
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

// Format mata uang Rupiah
function format_rupiah($angka): string
{
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

// Menyimpan pesan flash session
function set_flash(string $type, string $message): void
{
    $_SESSION['flash_message'] = [
        'type' => $type, // 'success', 'danger', 'warning', 'info'
        'message' => $message
    ];
}

// Menampilkan pesan flash alert Bootstrap
function display_flash(): string
{
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        $type = e($flash['type']);
        $msg = e($flash['message']);
        return "<div class=\"alert alert-{$type} alert-dismissible fade show my-3 shadow-sm\" role=\"alert\">
                    {$msg}
                    <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
                </div>";
    }
    return '';
}

// Cek apakah user sedang login
function is_logged_in(): bool
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Ambil info user yang sedang login
function current_user(): ?array
{
    if (!is_logged_in()) {
        return null;
    }
    return [
        'id_user'      => $_SESSION['user_id'],
        'nama_lengkap' => $_SESSION['nama_lengkap'] ?? '',
        'email'        => $_SESSION['email'] ?? '',
        'role'         => $_SESSION['role'] ?? '',
        'no_telp'      => $_SESSION['no_telp'] ?? '',
        'alamat'       => $_SESSION['alamat'] ?? ''
    ];
}

// Proteksi akses berdasarkan role
function require_role($roles, string $redirect = 'auth/login'): void
{
    if (!is_array($roles)) {
        $roles = [$roles];
    }
    if (!is_logged_in()) {
        set_flash('warning', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        header('Location: ' . BASE_URL . '/' . ltrim($redirect, '/'));
        exit;
    }
    if (!in_array($_SESSION['role'], $roles, true)) {
        set_flash('danger', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
        header('Location: ' . BASE_URL);
        exit;
    }
}

/**
 * Upload file dengan validasi ekstensi dan batas ukuran (Maksimal 2MB)
 *
 * @param array $file Array $_FILES['input_name']
 * @param string $destinationDir Folder tujuan (relatif dari public/)
 * @param array $allowedExtensions Ekstensi yang diizinkan (default: jpg, jpeg, png)
 * @param int $maxSizeBytes Batas ukuran dalam bytes (default: 2MB = 2 * 1024 * 1024)
 * @return array ['success' => bool, 'fileName' => string|null, 'error' => string|null]
 */
function upload_file(array $file, string $destinationDir, array $allowedExtensions = ['jpg', 'jpeg', 'png'], int $maxSizeBytes = 2097152): array
{
    if (!isset($file['error']) || is_array($file['error'])) {
        return ['success' => false, 'fileName' => null, 'error' => 'Parameter file tidak valid.'];
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors = [
            UPLOAD_ERR_INI_SIZE   => 'Ukuran file melebihi batas upload server.',
            UPLOAD_ERR_FORM_SIZE  => 'Ukuran file melebihi batas form.',
            UPLOAD_ERR_PARTIAL    => 'File hanya terunggah sebagian.',
            UPLOAD_ERR_NO_FILE    => 'Tidak ada file yang diunggah.',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary server tidak ditemukan.',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk.',
            UPLOAD_ERR_EXTENSION  => 'Ekstensi file dihentikan oleh konfigurasi PHP.',
        ];
        return ['success' => false, 'fileName' => null, 'error' => $errors[$file['error']] ?? 'Terjadi kesalahan saat upload.'];
    }

    // Validasi ukuran (Max 2MB)
    if ($file['size'] > $maxSizeBytes) {
        return ['success' => false, 'fileName' => null, 'error' => 'Ukuran file maksimal 2MB.'];
    }

    // Validasi ekstensi
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedExtensions, true)) {
        return ['success' => false, 'fileName' => null, 'error' => 'Hanya format ' . implode(', ', $allowedExtensions) . ' yang diperbolehkan.'];
    }

    // Validasi MIME Type asli gambar
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $allowedMimes = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png'
    ];
    if (!in_array($mime, $allowedMimes, true)) {
        return ['success' => false, 'fileName' => null, 'error' => 'Tipe konten file bukan gambar yang valid.'];
    }

    // Buat nama unik acak
    $newFileName = uniqid('img_', true) . '.' . $ext;
    $targetPath = rtrim($destinationDir, '/') . '/' . $newFileName;

    // Pastikan folder tujuan ada
    if (!is_dir($destinationDir)) {
        mkdir($destinationDir, 0755, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => false, 'fileName' => null, 'error' => 'Gagal menyimpan file ke direktori tujuan.'];
    }

    return ['success' => true, 'fileName' => $newFileName, 'error' => null];
}
