<?php
/**
 * Front Controller (Entry Point Utama)
 * Semua request HTTP bermuara di file ini melalui URL Rewriting.
 */

// Normalisasi environment jika dijalankan melalui CLI / test runner
if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}

// Aktifkan session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Muat file konfigurasi
require_once __DIR__ . '/../config/config.php';

// 2. Muat kelas Core aplikasi
require_once __DIR__ . '/../app/Core/Helper.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Core/Controller.php';
require_once __DIR__ . '/../app/Core/App.php';

// 3. Autoload otomatis untuk Controller dan Model jika belum di-require manual
spl_autoload_register(function ($className) {
    $paths = [
        __DIR__ . '/../app/Controllers/' . $className . '.php',
        __DIR__ . '/../app/Models/' . $className . '.php',
        __DIR__ . '/../app/Core/' . $className . '.php'
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// 4. Inisialisasi router aplikasi
$app = new App();
