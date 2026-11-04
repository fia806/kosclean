<?php
/**
 * Midtrans HTTP Notification Webhook Handler
 * Menangani notifikasi webhook otomatis ketika website di-deploy ke server online.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/midtrans.php';
require_once __DIR__ . '/../app/Core/Helper.php';
require_once __DIR__ . '/../app/Core/Database.php';
require_once __DIR__ . '/../app/Models/PesananModel.php';
require_once __DIR__ . '/../app/Models/AccountingModel.php';

try {
    $notif = new \Midtrans\Notification();

    $transaction = $notif->transaction_status;
    $type        = $notif->payment_type;
    $orderId     = $notif->order_id;
    $fraud       = $notif->fraud_status;

    $pesananModel    = new PesananModel();
    $accountingModel = new AccountingModel();

    $pesanan = $pesananModel->getByIdWithDetails($orderId);
    if (!$pesanan) {
        http_response_code(404);
        exit('Order tidak ditemukan');
    }

    $isSettled = false;

    if ($transaction == 'capture') {
        if ($fraud == 'accept') {
            $isSettled = true;
        }
    } else if ($transaction == 'settlement') {
        $isSettled = true;
    } else if (in_array($transaction, ['deny', 'expire', 'cancel'])) {
        $pesananModel->updateStatusPesanan($orderId, 'dibatalkan');
    }

    if ($isSettled) {
        // 1. Ubah status pesanan menjadi lunas
        $pesananModel->updateStatusPembayaranDanPesanan($orderId, 'lunas', 'menunggu_jadwal');

        // 2. Injeksi Jurnal SAK: Debet Kas Bank (112) vs Kredit Pendapatan Diterima di Muka (212)
        $accountingModel->recordUnearnedRevenue(
            $orderId,
            (float)$pesanan['total_harga'],
            'midtrans',
            null
        );
    }

    http_response_code(200);
    echo "Notification processed successfully";
} catch (Exception $e) {
    http_response_code(500);
    echo "Notification handling failed: " . $e->getMessage();
}
