<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card card-custom p-4 p-md-5 text-center">
                
                <div class="rounded-circle p-3 mx-auto mb-3 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 84px; height: 84px; border: 2px solid var(--color-sand);">
                    <i class="bi bi-check-circle-fill display-4" style="color: var(--color-dark);"></i>
                </div>

                <h2 class="font-heading mb-1" style="color: var(--color-heading);">Pesanan Berhasil Dibuat!</h2>
                <p class="font-body small mb-4" style="color: var(--color-body-text);">
                    Nomor Pesanan: <strong class="px-3 py-1 rounded-pill font-heading" style="background-color: var(--color-cream); border: 1px solid var(--color-sand); color: var(--color-dark);"><?= e($pesanan['id_pesanan']) ?></strong>
                </p>

                <!-- Rincian Pesanan (Palet Warm Cream & Deep Slate Navy) -->
                <div class="p-4 rounded-4 text-start mb-4" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                    <h6 class="font-heading mb-3 border-bottom pb-2" style="color: var(--color-heading); border-color: var(--color-sand) !important;">Rincian Pemesanan:</h6>
                    <div class="row g-2 small font-body">
                        <div class="col-5" style="color: var(--color-muted);">Paket Layanan:</div>
                        <div class="col-7 fw-bold font-heading" style="color: var(--color-heading);"><?= e($pesanan['nama_layanan']) ?></div>

                        <div class="col-5" style="color: var(--color-muted);">Jadwal Kedatangan:</div>
                        <div class="col-7 fw-semibold" style="color: var(--color-heading);"><?= date('d M Y', strtotime($pesanan['tanggal_jadwal'])) ?> (<?= substr($pesanan['waktu_jadwal'], 0, 5) ?> WIB)</div>

                        <div class="col-5" style="color: var(--color-muted);">Alamat Kos:</div>
                        <div class="col-7" style="color: var(--color-body-text);"><?= e($pesanan['alamat_pembersihan']) ?></div>

                        <div class="col-5" style="color: var(--color-muted);">Patokan:</div>
                        <div class="col-7" style="color: var(--color-body-text);"><?= e($pesanan['patokan_lokasi']) ?></div>

                        <div class="col-5" style="color: var(--color-muted);">Total Pembayaran:</div>
                        <div class="col-7 fw-extrabold font-heading fs-5" style="color: var(--color-dark);"><?= format_rupiah($pesanan['total_harga']) ?></div>

                        <div class="col-5" style="color: var(--color-muted);">Metode Pembayaran:</div>
                        <div class="col-7"><span class="badge badge-marine"><?= strtoupper(e($pesanan['metode_pembayaran'])) ?></span> (<?= ucfirst(str_replace('_', ' ', e($pesanan['status_pembayaran']))) ?>)</div>

                        <div class="col-5" style="color: var(--color-muted);">Status Pesanan:</div>
                        <div class="col-7"><span class="badge badge-mist"><?= ucfirst(str_replace('_', ' ', e($pesanan['status_pesanan']))) ?></span></div>
                    </div>
                </div>

                <div class="p-3 rounded-3 text-start small mb-4" style="background-color: #FFFFFF; border: 1.5px solid var(--color-sand); color: var(--color-body-text);">
                    <i class="bi bi-info-circle-fill me-1" style="color: var(--color-dark);"></i>
                    <strong style="color: var(--color-heading);">Langkah Santai Selanjutnya:</strong> Agar cleaner segera kami jadwalkan ke lokasi kos Anda, yuk kirim pesan konfirmasi instan ke WhatsApp Admin.
                </div>

                <!-- TOMBOL KONFIRMASI KE WA ADMIN -->
                <div class="d-grid gap-2">
                    <a href="<?= $linkWa ?>" target="_blank" class="btn btn-lg fw-bold py-3 shadow d-flex align-items-center justify-content-center gap-2 rounded-pill text-white" style="background-color: #25D366; border: none; font-family: 'Outfit', sans-serif;">
                        <i class="bi bi-whatsapp fs-5"></i> Konfirmasi ke WA Admin (Semarang)
                    </a>
                    
                    <a href="<?= BASE_URL ?>/pelanggan/dashboard" class="btn btn-brand-outline py-2 mt-2">
                        Lihat Riwayat Pesanan Saya
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
