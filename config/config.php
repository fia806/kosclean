<?php
/**
 * Konfigurasi Aplikasi & Database Jasa Bersih Kos
 */

// Deteksi otomatis BASE_URL agar fleksibel di CLI server (php -S) maupun Apache/XAMPP
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';

    if (isset($_SERVER['SERVER_SOFTWARE']) && stripos($_SERVER['SERVER_SOFTWARE'], 'Development Server') !== false) {
        // Jika dijalankan dengan: php -S localhost:8000 router.php
        define('BASE_URL', rtrim($protocol . $host, '/'));
    } else {
        // Jika dijalankan di Apache / XAMPP / Laragon (contoh: http://localhost/kosclean/public)
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $subfolder = ($scriptDir === '/' || $scriptDir === '.') ? '' : $scriptDir;
        define('BASE_URL', rtrim($protocol . $host . $subfolder, '/'));
    }
}

// Konfigurasi Database PDO (Gunakan 127.0.0.1 untuk mencegah isu resolusi IPv6 pada Windows)
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'db_jasabersih');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');
