<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- HEADER HERO SYARAT & KETENTUAN (PALET SWATCH #484B6A & #FAF1DC) -->
<div class="hero-banner py-5 text-white mb-5" style="background: linear-gradient(140deg, #484B6A 0%, #35384F 55%, #424663 100%) !important; border-radius: 0 0 36px 36px; box-shadow: 0 14px 35px -10px rgba(47, 50, 73, 0.4);">
    <div class="container py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none" style="color: var(--color-cream); opacity: 0.85;">Beranda</a></li>
                <li class="breadcrumb-item active" style="color: #FFFFFF;" aria-current="page">Syarat & Ketentuan</li>
            </ol>
        </nav>
        <h1 class="display-5 fw-bold text-white mb-2 font-heading">Syarat & Ketentuan Layanan</h1>
        <p class="lead mb-0" style="color: #FAF1DC !important; font-size: 1.1rem;">
            Ketentuan resmi pemesanan dan garansi kebersihan kamar kos di KosClean Semarang.
        </p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <!-- BOKS PERINGATAN KEAMANAN SANTAI -->
            <div class="alert-security-warning mb-4 shadow-sm d-flex gap-3 align-items-start">
                <i class="bi bi-shield-fill-exclamation fs-2 flex-shrink-0 mt-1" style="color: #D97706;"></i>
                <div>
                    <h5 class="fw-bold mb-1 font-heading" style="color: var(--color-heading);">Peringatan Keamanan Transaksi!</h5>
                    <p class="mb-0 font-body small" style="color: var(--color-body-text); line-height: 1.68;">
                        <strong style="color: #B45309;">DILARANG KERAS</strong> melakukan kesepakatan harga di bawah tangan atau transaksi pembayaran langsung secara privat dengan cleaner di luar sistem resmi KosClean. Segala bentuk komplain, kehilangan barang, maupun klaim garansi 1x24 jam <strong>hanya berlaku untuk pesanan yang terdaftar di platform resmi KosClean</strong>.
                    </p>
                </div>
            </div>

            <!-- KONTEN SYARAT & KETENTUAN (KARTU PUTIH BERSIH DENGAN TIPOGRAFI TAJAM) -->
            <div class="card card-custom p-4 p-md-5">
                <h4 class="fw-bold mb-3 font-heading" style="color: var(--color-heading);">
                    <i class="bi bi-card-checklist me-2" style="color: var(--color-dark);"></i> 1. Ketentuan Pemesanan
                </h4>
                <p class="font-body small" style="color: var(--color-body-text); line-height: 1.75;">
                    Pemesanan jasa bersih kos dapat dilakukan secara online melalui website resmi KosClean dengan memilih jenis paket layanan resmi (General Kost Cleaning, Deep Cleaning Kost, Bathroom Cleaning, Move-Out Cleaning, atau Move-In Cleaning), memilih jadwal (tanggal & jam kedatangan), serta menyertakan alamat kos yang jelas beserta patokan lokasi di area Kota Semarang.
                </p>

                <hr class="my-4" style="border-color: var(--color-sand) !important;">

                <h4 class="fw-bold mb-3 font-heading" style="color: var(--color-heading);">
                    <i class="bi bi-wallet2 me-2" style="color: var(--color-slate-blue);"></i> 2. Metode Pembayaran
                </h4>
                <p class="font-body small mb-2" style="color: var(--color-body-text);">
                    Kami mendukung dua metode pembayaran yang aman, nyaman, dan fleksibel:
                </p>
                <ul class="font-body small d-flex flex-column gap-2 mb-3" style="color: var(--color-body-text); line-height: 1.7;">
                    <li><strong style="color: var(--color-heading);">Cash (Tunai di Lokasi):</strong> Dibayarkan langsung kepada cleaner setelah proses pembersihan selesai dan Anda memeriksa hasilnya.</li>
                    <li><strong style="color: var(--color-heading);">Midtrans (Pembayaran Digital):</strong> Pembayaran instan melalui QRIS (GoPay, OVO, ShopeePay) atau Transfer Virtual Account Bank.</li>
                </ul>

                <hr class="my-4" style="border-color: var(--color-sand) !important;">

                <h4 class="fw-bold mb-3 font-heading" style="color: var(--color-heading);">
                    <i class="bi bi-patch-check-fill me-2" style="color: var(--color-dark);"></i> 3. Garansi Sentuh Ulang (Touch-up 1x24 Jam)
                </h4>
                <p class="font-body small" style="color: var(--color-body-text); line-height: 1.75;">
                    Jika ada bagian yang terlewat atau belum bersih sesuai checklist paket layanan yang Anda pesan, Anda berhak mengajukan permintaan touch-up ulang gratis dalam waktu maksimal 1x24 jam setelah pesanan berstatus selesai.
                </p>

                <hr class="my-4" style="border-color: var(--color-sand) !important;">

                <h4 class="fw-bold mb-3 font-heading" style="color: var(--color-heading);">
                    <i class="bi bi-lock-fill me-2" style="color: #D97706;"></i> 4. Keamanan Barang Pribadi
                </h4>
                <p class="font-body small mb-0" style="color: var(--color-body-text); line-height: 1.75;">
                    Demi kenyamanan bersama, pelanggan disarankan menyimpan barang berharga (uang tunai, laptop, perhiasan, dokumen penting) di tempat aman sebelum cleaner memulai tugasnya di dalam kamar kos.
                </p>
            </div>

            <div class="text-center mt-4">
                <a href="<?= BASE_URL ?>/booking" class="btn btn-brand-primary px-5 py-3 shadow">
                    <i class="bi bi-calendar2-check-fill me-2"></i>Lanjutkan Pemesanan
                </a>
            </div>

        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
