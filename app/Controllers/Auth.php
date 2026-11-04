<?php
/**
 * Class Auth
 * Controller untuk autentikasi (Login, Register, Logout) dengan proteksi CSRF
 */
class Auth extends Controller
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('UserModel');
    }

    /**
     * Halaman & Proses Login
     */
    public function login()
    {
        // Jika sudah login, langsung alihkan ke dashboard masing-masing
        if (is_logged_in()) {
            $this->redirectByRole($_SESSION['role']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                set_flash('danger', 'Token keamanan (CSRF) tidak valid.');
                $this->redirect('auth/login');
            }

            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $redirectUrl = $_POST['redirect'] ?? '';

            if (empty($email) || empty($password)) {
                set_flash('danger', 'Email dan password wajib diisi.');
                $this->view('auth/login', ['title' => 'Login - KosClean', 'email' => $email]);
                return;
            }

            $user = $this->userModel->getByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Regenerate session id untuk mencegah session fixation
                session_regenerate_id(true);

                $_SESSION['user_id']      = $user['id_user'];
                $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
                $_SESSION['email']        = $user['email'];
                $_SESSION['role']         = $user['role'];
                $_SESSION['no_telp']      = $user['no_telp'];
                $_SESSION['alamat']       = $user['alamat'];

                set_flash('success', 'Selamat datang kembali, ' . $user['nama_lengkap'] . '!');

                // Redirect sesuai target awal jika ada
                if (!empty($redirectUrl) && strpos($redirectUrl, 'auth') === false) {
                    $this->redirect($redirectUrl);
                }

                $this->redirectByRole($user['role']);
            } else {
                set_flash('danger', 'Kombinasi email atau password salah.');
                $this->view('auth/login', ['title' => 'Login - KosClean', 'email' => $email]);
                return;
            }
        }

        $redirectParam = $_GET['redirect'] ?? '';
        $this->view('auth/login', [
            'title'    => 'Login - KosClean',
            'redirect' => $redirectParam
        ]);
    }

    /**
     * Halaman & Proses Registrasi Pelanggan
     */
    public function register()
    {
        if (is_logged_in()) {
            $this->redirectByRole($_SESSION['role']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                set_flash('danger', 'Token keamanan (CSRF) tidak valid.');
                $this->redirect('auth/register');
            }

            $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
            $no_telp      = trim($_POST['no_telp'] ?? '');
            $email        = trim($_POST['email'] ?? '');
            $password     = $_POST['password'] ?? '';
            $alamat       = trim($_POST['alamat'] ?? '');

            // Validasi input
            if (empty($nama_lengkap) || empty($no_telp) || empty($email) || empty($password)) {
                set_flash('danger', 'Semua kolom bertanda bintang (*) wajib diisi.');
                $this->view('auth/register', ['title' => 'Daftar Akun Pelanggan', 'data' => $_POST]);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                set_flash('danger', 'Format alamat email tidak valid.');
                $this->view('auth/register', ['title' => 'Daftar Akun Pelanggan', 'data' => $_POST]);
                return;
            }

            // Cek apakah email sudah terdaftar
            if ($this->userModel->getByEmail($email)) {
                set_flash('danger', 'Email sudah terdaftar. Silakan login atau gunakan email lain.');
                $this->view('auth/register', ['title' => 'Daftar Akun Pelanggan', 'data' => $_POST]);
                return;
            }

            $newUserId = $this->userModel->create([
                'nama_lengkap' => $nama_lengkap,
                'no_telp'      => $no_telp,
                'email'        => $email,
                'password'     => $password,
                'alamat'       => $alamat,
                'role'         => 'pelanggan'
            ]);

            if ($newUserId) {
                // Auto login setelah registrasi berhasil
                $_SESSION['user_id']      = $newUserId;
                $_SESSION['nama_lengkap'] = $nama_lengkap;
                $_SESSION['email']        = $email;
                $_SESSION['role']         = 'pelanggan';
                $_SESSION['no_telp']      = $no_telp;
                $_SESSION['alamat']       = $alamat;

                set_flash('success', 'Akun berhasil dibuat! Silakan mulai memesan layanan.');
                $this->redirect('booking');
            } else {
                set_flash('danger', 'Terjadi kesalahan sistem saat mendaftar. Silakan coba lagi.');
                $this->view('auth/register', ['title' => 'Daftar Akun Pelanggan', 'data' => $_POST]);
            }
            return;
        }

        $this->view('auth/register', ['title' => 'Daftar Akun Pelanggan - KosClean']);
    }

    /**
     * Logout pengguna
     */
    public function logout()
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: ' . BASE_URL . '/auth/login');
        exit;
    }

    /**
     * Helper redirect berdasarkan role
     */
    private function redirectByRole(string $role): void
    {
        switch ($role) {
            case 'admin':
                $this->redirect('admin');
                break;
            case 'karyawan':
                $this->redirect('karyawan');
                break;
            case 'pelanggan':
            default:
                $this->redirect('pelanggan/dashboard');
                break;
        }
    }
}
