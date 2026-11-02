<?php
require 'vendor/autoload.php';
$d = Dotenv\Dotenv::createImmutable('.');
$d->safeLoad();
echo 'ServerKey: ' . substr($_ENV['MIDTRANS_SERVER_KEY'] ?? 'KOSONG', 0, 25) . '...' . PHP_EOL;
echo 'ClientKey: ' . substr($_ENV['MIDTRANS_CLIENT_KEY'] ?? 'KOSONG', 0, 25) . '...' . PHP_EOL;
echo 'isProduction: ' . ($_ENV['MIDTRANS_IS_PRODUCTION'] ?? 'KOSONG') . PHP_EOL;

// Cek format Server Key
$sk = $_ENV['MIDTRANS_SERVER_KEY'] ?? '';
if (str_starts_with($sk, 'SB-Mid-server-')) {
    echo 'FORMAT SERVER KEY: OK (Sandbox)' . PHP_EOL;
} elseif (str_starts_with($sk, 'Mid-server-')) {
    echo 'FORMAT SERVER KEY: OK (Production)' . PHP_EOL;
} else {
    echo 'FORMAT SERVER KEY: !! TIDAK VALID - Harus dimulai dengan SB-Mid-server- atau Mid-server-' . PHP_EOL;
}

