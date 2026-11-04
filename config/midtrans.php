<?php
/**
 * Konfigurasi Midtrans Payment Gateway
 * Memuat kredensial aman dari file .env menggunakan Dotenv
 */

// 1. Panggil autoloader Composer
require_once __DIR__ . '/../vendor/autoload.php';

// 2. Inisialisasi dan load variabel environment dari .env
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->safeLoad();
}

// 3. Set konfigurasi Midtrans SDK menggunakan $_ENV
\Midtrans\Config::$serverKey    = $_ENV['MIDTRANS_SERVER_KEY'] ?? getenv('MIDTRANS_SERVER_KEY') ?? '';
\Midtrans\Config::$clientKey    = $_ENV['MIDTRANS_CLIENT_KEY'] ?? getenv('MIDTRANS_CLIENT_KEY') ?? '';

// Konversi nilai string boolean 'true'/'false' ke boolean PHP
$isProductionVal               = $_ENV['MIDTRANS_IS_PRODUCTION'] ?? getenv('MIDTRANS_IS_PRODUCTION') ?? 'false';
\Midtrans\Config::$isProduction = filter_var($isProductionVal, FILTER_VALIDATE_BOOLEAN);

// Sanitasi parameter request & aktifkan 3D Secure untuk kartu kredit
\Midtrans\Config::$isSanitized  = true;
\Midtrans\Config::$is3ds        = true;
