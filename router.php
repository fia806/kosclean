<?php
/**
 * Router untuk PHP Built-in Web Server
 * Memungkinkan URL rewriting berjalan mulus tanpa Apache .htaccess.
 *
 * Cara menjalankan:
 * php -S localhost:8000 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$publicDir = __DIR__ . '/public';
$filePath = $publicDir . $uri;

// 1. Jika mengakses file fisik yang ada di public/ (css, js, gambar, dsb.)
if ($uri !== '/' && file_exists($filePath) && !is_dir($filePath)) {
    return false; // Server bawaan PHP akan langsung menyajikan file statis tersebut
}

// 2. Arahkan seluruh rute URL lainnya ke Front Controller
$_GET['url'] = ltrim($uri, '/');
require_once $publicDir . '/index.php';
