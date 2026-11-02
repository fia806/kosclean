<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
// Ambil snap token - pastikan sudah di-load midtrans config untuk $_ENV
if (!isset($_ENV['MIDTRANS_CLIENT_KEY'])) {
    require_once __DIR__ . '/../../../config/midtrans.php';
}
$snapToken  = $pesanan['midtrans_snap_token'] ?? '';
$clientKey  = $_ENV['MIDTRANS_CLIENT_KEY'] ?? 'SB-Mid-client-oWStWLPcxOJ05HPI';
$hasToken   = !empty($snapToken) && !str_starts_with($snapToken, 'SNAP-abc');
?>

<?php if ($hasToken): ?>
<!-- Script Midtrans Snap JS — Sandbox Mode -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="<?= e($clientKey) ?>"></script>
<?php endif; ?>

<div class="container py-5 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card card-custom p-4 p-md-5 text-center">

                <div class="rounded-circle p-3 mx-auto mb-3 d-flex align-items-center justify-content-center"
                     style="background-color: var(--color-cream); color: var(--color-dark); width: 72px; height: 72px; border: 2px solid var(--color-sand);">
                    <i class="bi bi-credit-card-2-front-fill fs-2" style="color: var(--color-dark);"></i>
                </div>

                <h3 class="font-heading mb-1" style="color: var(--color-heading);">Pembayaran via Midtrans</h3>
                <p class="font-body small mb-4" style="color: var(--color-body-text);">
                    Selesaikan pembayaran untuk mengonfirmasi pesanan jasa bersih kos Anda.
                </p>

                <!-- Ringkasan Pesanan -->
                <div class="p-4 rounded-4 text-start mb-4" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                    <div class="d-flex justify-content-between mb-2 small font-body">
                        <span style="color: var(--color-body-text);">ID Pesanan:</span>
                        <strong class="font-heading" style="color: var(--color-heading);"><?= e($pesanan['id_pesanan']) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small font-body">
                        <span style="color: var(--color-body-text);">Paket Layanan:</span>
                        <strong style="color: var(--color-heading);"><?= e($pesanan['nama_layanan']) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small font-body">
                        <span style="color: var(--color-body-text);">Jadwal:</span>
                        <span style="color: var(--color-heading);">
                            <?= date('d M Y', strtotime($pesanan['tanggal_jadwal'])) ?>
                            (<?= substr($pesanan['waktu_jadwal'], 0, 5) ?> WIB)
                        </span>
                    </div>
                    <hr style="border-color: var(--color-sand) !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold font-heading" style="color: var(--color-heading);">Total Tagihan:</span>
                        <span class="h4 fw-extrabold font-heading mb-0 text-success"><?= format_rupiah($pesanan['total_harga']) ?></span>
                    </div>
                </div>

                <?php if (!$hasToken): ?>
                <!-- ERROR: Token tidak valid atau gagal dibuat -->
                <div class="alert rounded-4 text-start mb-4 d-flex gap-3 align-items-start"
                     style="background-color: #FFEBEE; border: 2px solid #EF9A9A; color: #B71C1C;">
                    <i class="bi bi-exclamation-triangle-fill fs-4 flex-shrink-0 mt-1"></i>
                    <div>
                        <strong class="font-heading d-block mb-1">Sesi Pembayaran Tidak Valid</strong>
                        <p class="font-body small mb-2">
                            Token Midtrans gagal dibuat. Kemungkinan penyebab:
                        </p>
                        <ul class="font-body small mb-2" style="padding-left: 1.2rem;">
                            <li>Server Key di file <code>.env</code> belum benar atau belum diperbarui</li>
                            <li>Tidak ada koneksi internet ke server Midtrans saat checkout</li>
                            <li>Pastikan Server Key sandbox dimulai dengan <code>SB-Mid-server-</code></li>
                        </ul>
                        <p class="font-body small mb-0">
                            Ambil key yang benar di:
                            <a href="https://dashboard.sandbox.midtrans.com/settings/config_info"
                               target="_blank" class="fw-bold" style="color: #1565C0;">
                                dashboard.sandbox.midtrans.com
                            </a>
                        </p>
                    </div>
                </div>
                <a href="<?= BASE_URL ?>/booking" class="btn btn-brand-primary w-100 mb-2">
                    <i class="bi bi-arrow-left me-1"></i>Buat Pesanan Baru
                </a>

                <?php else: ?>
                <!-- Tombol Bayar via Snap -->
                <button type="button" id="pay-button" class="btn btn-brand-primary btn-lg w-100 py-3 shadow mb-3"
                        style="font-size: 1.05rem;">
                    <i class="bi bi-wallet2 me-2"></i>Bayar Sekarang
                </button>

                <!-- Metode yang tersedia -->
                <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                    <?php
                    $methods = ['QRIS', 'GoPay', 'OVO', 'ShopeePay', 'Virtual Account', 'Kartu Kredit'];
                    foreach ($methods as $m): ?>
                    <span class="badge rounded-pill font-body"
                          style="background-color: var(--color-cream); color: var(--color-dark); border: 1px solid var(--color-sand); font-size: 0.72rem;">
                        <?= $m ?>
                    </span>
                    <?php endforeach; ?>
                </div>

                <hr style="border-color: var(--color-sand);">

                <!-- Tombol Cek Status Manual (Pull API) -->
                <button type="button" id="btn-pull-status" class="btn btn-brand-outline w-100 py-2 mb-2">
                    <i class="bi bi-arrow-repeat me-1"></i>Cek Status Pembayaran Manual (Pull API)
                </button>

                <div id="pull-status-result" class="mt-2 text-start"></div>

                <?php endif; ?>

            </div>

            <!-- Info keamanan -->
            <div class="text-center mt-3">
                <small class="font-body" style="color: var(--color-muted);">
                    <i class="bi bi-shield-lock-fill me-1"></i>
                    Transaksi diamankan oleh Midtrans &mdash; Payment Gateway berlisensi Bank Indonesia
                </small>
            </div>
        </div>
    </div>
</div>

<?php if ($hasToken): ?>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        const snapToken = '<?= e($snapToken) ?>';
        window.snap.pay(snapToken, {
            onSuccess: function (result) {
                console.log('Snap Success:', result);
                // Trigger akuntansi via pull setelah sukses
                fetch('<?= BASE_URL ?>/booking/checkPaymentStatus/<?= e($pesanan['id_pesanan']) ?>')
                    .then(() => {
                        window.location.href = '<?= BASE_URL ?>/booking/success/' + result.order_id;
                    })
                    .catch(() => {
                        window.location.href = '<?= BASE_URL ?>/booking/success/' + result.order_id;
                    });
            },
            onPending: function (result) {
                console.log('Snap Pending:', result);
                showStatus('warning', '<i class="bi bi-hourglass-split me-1"></i>Pembayaran dalam proses. Cek email atau aplikasi bank Anda untuk menyelesaikan transaksi.');
                setTimeout(() => {
                    window.location.href = '<?= BASE_URL ?>/pelanggan/dashboard';
                }, 3000);
            },
            onError: function (result) {
                console.error('Snap Error:', result);
                showStatus('danger', '<i class="bi bi-x-circle me-1"></i>Pembayaran gagal diproses. Silakan coba kembali atau pilih metode lain.');
            },
            onClose: function () {
                console.log('Snap closed without payment');
                showStatus('warning', '<i class="bi bi-info-circle me-1"></i>Jendela pembayaran ditutup. Klik "Bayar Sekarang" untuk mencoba kembali, atau gunakan tombol cek status di bawah.');
            }
        });
    };

    document.getElementById('btn-pull-status').onclick = function () {
        showStatus('info', '<i class="bi bi-hourglass-split me-1"></i>Menghubungi server Midtrans untuk memverifikasi status transaksi...');

        fetch('<?= BASE_URL ?>/booking/checkPaymentStatus/<?= e($pesanan['id_pesanan']) ?>')
            .then(res => res.json())
            .then(data => {
                if (data.success && data.status_pembayaran === 'lunas') {
                    showStatus('success', '<i class="bi bi-check-circle-fill me-1"></i>' + data.message);
                    setTimeout(() => {
                        window.location.href = '<?= BASE_URL ?>/booking/success/<?= e($pesanan['id_pesanan']) ?>';
                    }, 1500);
                } else {
                    showStatus('warning', '<i class="bi bi-exclamation-triangle me-1"></i>' + data.message);
                }
            })
            .catch(err => {
                showStatus('danger', '<i class="bi bi-x-circle me-1"></i>Gagal cek status: ' + err.message);
            });
    };

    function showStatus(type, html) {
        const box = document.getElementById('pull-status-result');
        if (box) {
            box.innerHTML = '<div class="alert alert-' + type + ' py-2 small mb-0 rounded-3">' + html + '</div>';
        }
    }
</script>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
