<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- Script Midtrans Snap API (Wajib Sandbox) -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= $_ENV['MIDTRANS_CLIENT_KEY'] ?? ($clientKey ?? '') ?>"></script>

<div class="container py-5 my-4">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card card-custom p-4 p-md-5 text-center shadow">
                
                <div class="rounded-circle p-3 mx-auto mb-3 d-flex align-items-center justify-content-center" 
                     style="background-color: var(--color-cream); color: var(--color-dark); width: 72px; height: 72px; border: 2px solid var(--color-sand);">
                    <i class="bi bi-shield-check fs-2" style="color: var(--color-dark);"></i>
                </div>

                <span class="badge badge-palette-cream mx-auto mb-2 px-3 py-2 font-heading">
                    Order #<?= htmlspecialchars($orderId ?? '') ?>
                </span>
                <h3 class="font-heading mb-1" style="color: var(--color-heading);">Konfirmasi Pembayaran</h3>
                <p class="font-body small text-muted mb-4">Selesaikan transaksi Anda melalui gerbang pembayaran resmi Midtrans Sandbox.</p>

                <!-- Ringkasan Rincian Pesanan -->
                <div class="p-4 rounded-4 text-start mb-4" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                    <div class="d-flex justify-content-between mb-2 small font-body">
                        <span style="color: var(--color-body-text);">Paket Layanan:</span>
                        <strong class="font-heading" style="color: var(--color-heading);"><?= htmlspecialchars($layanan ?? 'Jasa Bersih Kos') ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small font-body">
                        <span style="color: var(--color-body-text);">Nama Pelanggan:</span>
                        <span style="color: var(--color-heading);"><?= htmlspecialchars($pelanggan['nama'] ?? '') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small font-body">
                        <span style="color: var(--color-body-text);">No. WhatsApp:</span>
                        <span style="color: var(--color-heading);"><?= htmlspecialchars($pelanggan['no_telp'] ?? '') ?></span>
                    </div>
                    <hr style="border-color: var(--color-sand) !important;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold font-heading" style="color: var(--color-heading);">Total Pembayaran:</span>
                        <span class="h4 fw-extrabold font-heading text-success mb-0">
                            <?= function_exists('format_rupiah') ? format_rupiah($grossAmount ?? 0) : 'Rp ' . number_format($grossAmount ?? 0, 0, ',', '.') ?>
                        </span>
                    </div>
                </div>

                <!-- Tombol Eksekusi Snap Popup -->
                <button type="button" id="pay-button" class="btn btn-brand-primary btn-lg w-100 py-3 shadow font-heading fw-bold">
                    <i class="bi bi-wallet2 me-2"></i>Bayar Sekarang (Midtrans)
                </button>

                <div id="payment-status-box" class="mt-3"></div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
document.getElementById('pay-button').onclick = function () {
    // Eksekusi pembayaran Snap API secara murni tanpa ada alert() yang menahan UI
    window.snap.pay('<?= $snapToken ?>', {
        onSuccess: function (result) {
            console.log("Payment Success:", result);
            // Redirect otomatis ke halaman booking/success/ disertai ID Pesanan
            window.location.href = "<?= BASE_URL ?>/booking/success/" + result.order_id;
        },
        onPending: function (result) {
            console.log("Payment Pending:", result);
            // Arahkan pelanggan ke halaman riwayat pesanan (dashboard)
            window.location.href = "<?= BASE_URL ?>/pelanggan/dashboard";
        },
        onError: function (result) {
            console.error("Payment Error:", result);
            var statusBox = document.getElementById('payment-status-box');
            if (statusBox) {
                statusBox.innerHTML = '<div class="alert alert-danger py-2 small mb-0">Pembayaran gagal diproses atau dibatalkan.</div>';
            }
        },
        onClose: function () {
            console.log("Customer closed the popup without finishing the payment");
            var statusBox = document.getElementById('payment-status-box');
            if (statusBox) {
                statusBox.innerHTML = '<div class="alert alert-warning py-2 small mb-0">Jendela pembayaran ditutup sebelum transaksi selesai.</div>';
            }
        }
    });
};
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
