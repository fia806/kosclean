<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- HERO SECTION: KONTRAST TINGGI & PALET SWATCH (#484B6A & #FAF1DC) -->
<section class="hero-banner py-5 position-relative overflow-hidden" style="background: linear-gradient(140deg, #484B6A 0%, #35384F 55%, #424663 100%) !important; border-radius: 0 0 44px 44px; box-shadow: 0 16px 40px -12px rgba(47, 50, 73, 0.45);">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill mb-3 hero-badge">
                    <i class="bi bi-geo-alt-fill" style="color: var(--color-cream);"></i>
                    <span class="small fw-bold" style="color: var(--color-cream);">Layanan Kebersihan Khusus Kota Semarang</span>
                </div>
                
                <h1 class="display-4 fw-extrabold mb-3 text-white lh-sm font-heading">
                    Datang ke Lokasi, <br class="d-none d-md-inline">Hasil Bersih Rapi.
                </h1>
                
                <p class="lead mb-4" style="color: #FAF1DC !important; font-size: 1.15rem; line-height: 1.75;">
                    Bikin kamar kos dan kamar mandi kamu kinclong kembali tanpa perlu capek. Dari pembersihan rutin, deep cleaning, kamar mandi, hingga persiapan pindahan kos, cleaner profesional kami siap meluncur tepat waktu!
                </p>
                
                <div class="d-flex flex-wrap gap-3 align-items-center">
                    <a href="<?= BASE_URL ?>/booking" class="btn btn-brand-cta btn-lg px-4 py-3 shadow d-inline-flex align-items-center gap-2">
                        <i class="bi bi-calendar2-check-fill"></i> Pesan di Semarang
                    </a>
                    <a href="#layanan" class="btn btn-outline-light rounded-pill btn-lg px-4 py-3" style="border: 2px solid var(--color-sand); color: var(--color-cream);">
                        <i class="bi bi-list-check me-1"></i> Lihat 5 Paket & Tarif
                    </a>
                </div>
                
                <div class="mt-4 pt-2 d-flex flex-wrap align-items-center gap-4 small" style="color: #FAF1DC;">
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill" style="color: var(--color-sand);"></i> Tarif Mulai Rp 35.000 / Sesi
                    </span>
                    <span class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill" style="color: var(--color-sand);"></i> Bayar Cash di Lokasi / Midtrans
                    </span>
                </div>
            </div>

            <!-- Ilustrasi / Box Fitur Santai di Sebelah Kanan (Kontras Tinggi) -->
            <div class="col-lg-5 d-none d-lg-block">
                <div class="p-4 rounded-4 text-start" style="background: rgba(35, 38, 54, 0.65); border: 1.5px solid rgba(216, 206, 190, 0.35); backdrop-filter: blur(12px); box-shadow: 0 20px 40px rgba(0,0,0,0.25);">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 54px; height: 54px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            <i class="bi bi-stars fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 text-white font-heading">5 Pilihan Layanan Spesialis</h5>
                            <small style="color: var(--color-cream); opacity: 0.9;">Standar Kebersihan Kos Modern</small>
                        </div>
                    </div>
                    
                    <ul class="list-unstyled d-flex flex-column gap-2 mb-0 small" style="color: #FAF1DC;">
                        <li class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255,255,255,0.08); color: #FAF1DC;">
                            <i class="bi bi-check2-circle fs-6" style="color: var(--color-sand);"></i> <strong>General Kost:</strong> Rp35.000 (Sapu, pel & lap rapi)
                        </li>
                        <li class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255,255,255,0.08); color: #FAF1DC;">
                            <i class="bi bi-check2-circle fs-6" style="color: var(--color-sand);"></i> <strong>Deep Cleaning:</strong> Rp100.000 (Menyeluruh & sudut sulit)
                        </li>
                        <li class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255,255,255,0.08); color: #FAF1DC;">
                            <i class="bi bi-check2-circle fs-6" style="color: var(--color-sand);"></i> <strong>Bathroom Cleaning:</strong> Rp45.000 (Kamar mandi higienis)
                        </li>
                        <li class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255,255,255,0.08); color: #FAF1DC;">
                            <i class="bi bi-check2-circle fs-6" style="color: var(--color-sand);"></i> <strong>Move-In & Move-Out:</strong> Rp90.000 (Spesialis pindahan)
                        </li>
                        <li class="d-flex align-items-center gap-2 p-2 rounded-3" style="background: rgba(255,255,255,0.08); color: #FAF1DC;">
                            <i class="bi bi-camera-fill fs-6" style="color: var(--color-sand);"></i> Laporan Foto Sebelum & Sesudah Transparan
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TRUST BADGES (4 IKON KEUNGGULAN - KARTU PUTIH BERSIH & TEKS SANGAT MUDAH DIBACA) -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-sm-6">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 68px; height: 68px; border: 2px solid var(--color-sand);">
                        <i class="bi bi-person-check-fill fs-2"></i>
                    </div>
                    <h5 class="font-heading mb-2" style="color: var(--color-heading);">Cleaner Terverifikasi</h5>
                    <p class="font-body mb-0" style="color: var(--color-body-text); font-size: 0.92rem; line-height: 1.6;">
                        Tenaga pembersih ramah, sopan, dan telah melalui verifikasi identitas ketat.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 68px; height: 68px; border: 2px solid var(--color-sand);">
                        <i class="bi bi-images fs-2"></i>
                    </div>
                    <h5 class="font-heading mb-2" style="color: var(--color-heading);">Laporan Foto</h5>
                    <p class="font-body mb-0" style="color: var(--color-body-text); font-size: 0.92rem; line-height: 1.6;">
                        Transparan! Foto SEBELUM dan SESUDAH pengerjaan langsung dapat Anda cek di sistem.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 68px; height: 68px; border: 2px solid var(--color-sand);">
                        <i class="bi bi-patch-check-fill fs-2"></i>
                    </div>
                    <h5 class="font-heading mb-2" style="color: var(--color-heading);">Garansi 1x24 Jam</h5>
                    <p class="font-body mb-0" style="color: var(--color-body-text); font-size: 0.92rem; line-height: 1.6;">
                        Kurang puas dengan hasilnya? Tim kami siap melakukan touch-up ulang gratis.
                    </p>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 68px; height: 68px; border: 2px solid var(--color-sand);">
                        <i class="bi bi-bucket-fill fs-2"></i>
                    </div>
                    <h5 class="font-heading mb-2" style="color: var(--color-heading);">Alat Sudah Termasuk</h5>
                    <p class="font-body mb-0" style="color: var(--color-body-text); font-size: 0.92rem; line-height: 1.6;">
                        Anda tinggal terima beres, cleaner membawa alat pel, sikat, dan cairan lengkap.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PRICING CARDS: 5 LAYANAN LENGKAP KOSCLEAN -->
<section class="py-5" id="layanan">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 720px;">
            <span class="badge badge-mist mb-2"><i class="bi bi-tag-fill me-1"></i> Paket Lengkap & Tarif Resmi</span>
            <h2 class="display-6 font-heading mb-2" style="color: var(--color-heading);">Katalog Layanan Bersih Kos</h2>
            <p class="font-body mb-0" style="color: var(--color-body-text); font-size: 1.05rem;">
                Daftar lengkap layanan kebersihan kos di Semarang dengan estimasi waktu dan cakupan kerja transparan.
            </p>
        </div>

        <!-- BARIS 1: 3 LAYANAN UTAMA (General, Deep Clean, Bathroom) -->
        <div class="row g-4 justify-content-center mb-4">
            
            <!-- 1. General Kost Cleaning -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-custom h-100 p-4 position-relative d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-marine">Perawatan Rutin</span>
                        <span class="small font-body" style="color: var(--color-muted);"><i class="bi bi-clock me-1"></i> 1 - 1.5 Jam</span>
                    </div>
                    
                    <h4 class="font-heading mb-1" style="color: var(--color-heading); font-size: 1.25rem;">General Kost Cleaning</h4>
                    <p class="font-body small mb-3" style="color: var(--color-body-text); min-height: 44px;">
                        Pembersihan rutin untuk menjaga kamar tetap bersih dan rapi.
                    </p>
                    
                    <div class="mb-4 p-3 rounded-4" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                        <span class="h2 fw-extrabold font-heading mb-0" style="color: var(--color-dark);">Rp 35.000</span>
                        <span class="font-body small" style="color: var(--color-body-text);">/ sesi</span>
                    </div>

                    <ul class="list-unstyled d-flex flex-column gap-2 mb-4 small">
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Menyapu dan mengepel lantai</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Membersihkan debu meja & furnitur</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Merapikan area kamar</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Membuang sampah</span>
                        </li>
                    </ul>

                    <div class="mt-auto pt-2">
                        <a href="<?= BASE_URL ?>/booking?layanan=1" class="btn btn-brand-outline w-100 py-2 fw-bold">
                            Pesan General Cleaning
                        </a>
                    </div>
                </div>
            </div>

            <!-- 2. Deep Cleaning Kost (Featured) -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-custom h-100 p-4 position-relative shadow d-flex flex-column" style="border: 2px solid var(--color-dark) !important; background: #FFFFFF;">
                    <div class="position-absolute top-0 end-0 px-3 py-1 fw-bold font-heading small" style="background-color: var(--color-dark); color: var(--color-cream); border-radius: 0 22px 0 16px;">
                        <i class="bi bi-award-fill me-1"></i> PALING FAVORIT
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-dark">Total Menyeluruh</span>
                        <span class="small font-body" style="color: var(--color-muted);"><i class="bi bi-clock me-1"></i> 2.5 - 3 Jam</span>
                    </div>
                    
                    <h4 class="font-heading mb-1" style="color: var(--color-heading); font-size: 1.25rem;">Deep Cleaning Kost</h4>
                    <p class="font-body small mb-3" style="color: var(--color-body-text); min-height: 44px;">
                        Pembersihan menyeluruh hingga area yang sulit dijangkau dan jarang dibersihkan.
                    </p>
                    
                    <div class="mb-4 p-3 rounded-4" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                        <span class="h2 fw-extrabold font-heading mb-0" style="color: var(--color-dark);">Rp 100.000</span>
                        <span class="font-body small" style="color: var(--color-body-text);">/ sesi</span>
                    </div>

                    <ul class="list-unstyled d-flex flex-column gap-2 mb-4 small">
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-dark);"></i>
                            <span style="color: var(--color-heading); font-weight: 600;">Membersihkan sudut & kolong furnitur</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-dark);"></i>
                            <span style="color: var(--color-heading); font-weight: 600;">Menghilangkan debu membandel</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-dark);"></i>
                            <span style="color: var(--color-heading); font-weight: 600;">Membersihkan noda pada permukaan</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-dark);"></i>
                            <span style="color: var(--color-heading); font-weight: 600;">Pembersihan area sulit dijangkau</span>
                        </li>
                    </ul>

                    <div class="mt-auto pt-2">
                        <a href="<?= BASE_URL ?>/booking?layanan=2" class="btn btn-brand-primary w-100 py-2 fw-bold">
                            Pesan Deep Cleaning Sekarang
                        </a>
                    </div>
                </div>
            </div>

            <!-- 3. Bathroom Cleaning -->
            <div class="col-lg-4 col-md-6">
                <div class="card card-custom h-100 p-4 position-relative d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-palette-cream">Kamar Mandi Kinclong</span>
                        <span class="small font-body" style="color: var(--color-muted);"><i class="bi bi-clock me-1"></i> 1 Jam</span>
                    </div>
                    
                    <h4 class="font-heading mb-1" style="color: var(--color-heading); font-size: 1.25rem;">Bathroom Cleaning</h4>
                    <p class="font-body small mb-3" style="color: var(--color-body-text); min-height: 44px;">
                        Layanan khusus untuk menjaga kamar mandi tetap bersih, higienis, dan nyaman.
                    </p>
                    
                    <div class="mb-4 p-3 rounded-4" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                        <span class="h2 fw-extrabold font-heading mb-0" style="color: var(--color-dark);">Rp 45.000</span>
                        <span class="font-body small" style="color: var(--color-body-text);">/ sesi</span>
                    </div>

                    <ul class="list-unstyled d-flex flex-column gap-2 mb-4 small">
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Menyikat lantai & dinding kamar mandi</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Membersihkan toilet dan wastafel</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Membersihkan kerak ringan</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Saluran & area shower terjangkau</span>
                        </li>
                    </ul>

                    <div class="mt-auto pt-2">
                        <a href="<?= BASE_URL ?>/booking?layanan=3" class="btn btn-brand-outline w-100 py-2 fw-bold">
                            Pesan Bathroom Cleaning
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <!-- BARIS 2: 2 LAYANAN PINDAHAN (Move-Out & Move-In) -->
        <div class="row g-4 justify-content-center">
            
            <!-- 4. Move-Out Cleaning -->
            <div class="col-lg-6 col-md-6">
                <div class="card card-custom h-100 p-4 p-lg-5 position-relative d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-marine">Setelah Pindahan</span>
                        <span class="small font-body" style="color: var(--color-muted);"><i class="bi bi-clock me-1"></i> 2 Jam / Sesi</span>
                    </div>
                    
                    <h4 class="font-heading mb-1" style="color: var(--color-heading);">Move-Out Cleaning</h4>
                    <p class="font-body small mb-3" style="color: var(--color-body-text);">
                        Pembersihan kamar setelah penghuni memindahkan barang-barangnya agar kamar siap diserahkan kepada pemilik kost.
                    </p>
                    
                    <div class="mb-4 p-3 rounded-4" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                        <span class="h2 fw-extrabold font-heading mb-0" style="color: var(--color-dark);">Rp 90.000</span>
                        <span class="font-body small" style="color: var(--color-body-text);">/ sesi</span>
                    </div>

                    <ul class="list-unstyled d-flex flex-column gap-2 mb-4 small">
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Membersihkan lantai dan debu menyeluruh</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Membersihkan lemari & meja yang sudah dikosongkan</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Membersihkan bekas noda ringan</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Mengumpulkan dan membuang sampah yang tertinggal</span>
                        </li>
                    </ul>

                    <div class="mt-auto pt-2">
                        <a href="<?= BASE_URL ?>/booking?layanan=4" class="btn btn-brand-outline w-100 py-3 fw-bold">
                            Pilih Move-Out Cleaning
                        </a>
                    </div>
                </div>
            </div>

            <!-- 5. Move-In Cleaning -->
            <div class="col-lg-6 col-md-6">
                <div class="card card-custom h-100 p-4 p-lg-5 position-relative d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge badge-palette-cream">Sebelum Ditempati</span>
                        <span class="small font-body" style="color: var(--color-muted);"><i class="bi bi-clock me-1"></i> 2 Jam / Sesi</span>
                    </div>
                    
                    <h4 class="font-heading mb-1" style="color: var(--color-heading);">Move-In Cleaning</h4>
                    <p class="font-body small mb-3" style="color: var(--color-body-text);">
                        Pembersihan kamar sebelum penghuni baru masuk agar ruangan terasa bersih, wangi, dan nyaman sejak hari pertama.
                    </p>
                    
                    <div class="mb-4 p-3 rounded-4" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                        <span class="h2 fw-extrabold font-heading mb-0" style="color: var(--color-dark);">Rp 90.000</span>
                        <span class="font-body small" style="color: var(--color-body-text);">/ sesi</span>
                    </div>

                    <ul class="list-unstyled d-flex flex-column gap-2 mb-4 small">
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Membersihkan lantai dan permukaan furnitur</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Membersihkan lemari & meja kosong</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Membersihkan debu & sarang laba-laba</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color: var(--color-slate-blue);"></i>
                            <span style="color: var(--color-heading); font-weight: 500;">Pembersihan kamar mandi siap pakai</span>
                        </li>
                    </ul>

                    <div class="mt-auto pt-2">
                        <a href="<?= BASE_URL ?>/booking?layanan=5" class="btn btn-brand-outline w-100 py-3 fw-bold">
                            Pilih Move-In Cleaning
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- AREA COVERAGE: KHUSUS KECAMATAN DI SEMARANG -->
<section class="py-5" id="area">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 650px;">
            <span class="badge badge-mist mb-2"><i class="bi bi-geo-alt-fill me-1"></i> Jangkauan Kami</span>
            <h2 class="display-6 font-heading mb-2" style="color: var(--color-heading);">Area Layanan di Kota Semarang</h2>
            <p class="font-body mb-0" style="color: var(--color-body-text); font-size: 1.05rem;">
                Cleaner kami siap meluncur ke kos kamu di seluruh kecamatan utama Semarang.
            </p>
        </div>

        <div class="row g-4">
            <!-- 1. Tembalang & Banyumanik (Digabung) -->
            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 48px; height: 48px; border: 1.5px solid var(--color-sand);">
                            <i class="bi bi-mortarboard-fill fs-5"></i>
                        </div>
                        <h5 class="font-heading mb-0" style="color: var(--color-heading);">Tembalang & Banyumanik</h5>
                    </div>
                    <p class="font-body small mb-0" style="color: var(--color-body-text); line-height: 1.6;">
                        Area kampus UNDIP, POLINES, Poltekkes, Jl. Banjarsari, Sirojudin, Tirto Agung, Srondol Kulon/Wetan, Ngesrep, Jl. Setiabudi, hingga Pudakpayung.
                    </p>
                </div>
            </div>

            <!-- 2. Pedurungan (Dipisah) -->
            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 48px; height: 48px; border: 1.5px solid var(--color-sand);">
                            <i class="bi bi-pin-map-fill fs-5"></i>
                        </div>
                        <h5 class="font-heading mb-0" style="color: var(--color-heading);">Pedurungan</h5>
                    </div>
                    <p class="font-body small mb-0" style="color: var(--color-body-text); line-height: 1.6;">
                        Kawasan kos sekitar kampus USM, Tlogosari Kulon & Wetan, Pedurungan Tengah, Muktiharjo Kidul, Palebon, dan sepanjang koridor Jl. Majapahit.
                    </p>
                </div>
            </div>

            <!-- 3. Ngaliyan (Dipisah) -->
            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 48px; height: 48px; border: 1.5px solid var(--color-sand);">
                            <i class="bi bi-compass-fill fs-5"></i>
                        </div>
                        <h5 class="font-heading mb-0" style="color: var(--color-heading);">Ngaliyan</h5>
                    </div>
                    <p class="font-body small mb-0" style="color: var(--color-body-text); line-height: 1.6;">
                        Kawasan kos mahasiswa kampus UIN Walisongo (Kampus 1, 2, 3), Tambakaji, Purwoyoso, Bringin, Ngaliyan Square, hingga koridor BSB City.
                    </p>
                </div>
            </div>

            <!-- 4. Gunungpati -->
            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 48px; height: 48px; border: 1.5px solid var(--color-sand);">
                            <i class="bi bi-geo-alt-fill fs-5"></i>
                        </div>
                        <h5 class="font-heading mb-0" style="color: var(--color-heading);">Gunungpati</h5>
                    </div>
                    <p class="font-body small mb-0" style="color: var(--color-body-text); line-height: 1.6;">
                        Kawasan kos mahasiswa UNNES Sekaran, Banaran, Patemon, Kalisegoro, Mangunsari, dan sekitarnya.
                    </p>
                </div>
            </div>

            <!-- 5. Semarang Kota & Barat -->
            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 48px; height: 48px; border: 1.5px solid var(--color-sand);">
                            <i class="bi bi-buildings-fill fs-5"></i>
                        </div>
                        <h5 class="font-heading mb-0" style="color: var(--color-heading);">Semarang Kota & Barat</h5>
                    </div>
                    <p class="font-body small mb-0" style="color: var(--color-body-text); line-height: 1.6;">
                        Pleburan (Undip Bawah), Bulustalan, Mugassari, Simpang Lima, Krobokan, Bojongsalaman, dan Pamularsih.
                    </p>
                </div>
            </div>

            <!-- 6. Gajahmungkur & Candisari -->
            <div class="col-md-4 col-sm-6">
                <div class="card card-custom p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle p-2 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 48px; height: 48px; border: 1.5px solid var(--color-sand);">
                            <i class="bi bi-geo-fill fs-5"></i>
                        </div>
                        <h5 class="font-heading mb-0" style="color: var(--color-heading);">Gajahmungkur & Candisari</h5>
                    </div>
                    <p class="font-body small mb-0" style="color: var(--color-body-text); line-height: 1.6;">
                        Kawasan kos UNIKA Soegijapranata, Sampangan, Bendan Duwur, Wonotingal, Candi Baru, dan Karanganyar.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- TESTIMONI SECTION (KARTU PUTIH BERSIH & REVIEW SANTAI) -->
<section class="py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5" style="max-width: 650px;">
            <span class="badge badge-mist mb-2"><i class="bi bi-chat-heart-fill me-1"></i> Suara Pelanggan</span>
            <h2 class="display-6 font-heading mb-2" style="color: var(--color-heading);">Apa Kata Mahasiswa & Perantau?</h2>
            <p class="font-body mb-0" style="color: var(--color-body-text); font-size: 1.05rem;">
                Pengalaman nyata teman-teman kos yang sudah merasakan hasil bersih rapi KosClean.
            </p>
        </div>

        <div class="row g-4">
            <?php if (!empty($testimoni)): ?>
                <?php foreach ($testimoni as $t): ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="card card-custom h-100 p-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div style="color: #EAB308;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="bi <?= $i <= $t['rating'] ? 'bi-star-fill' : 'bi-star' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="badge badge-palette-cream small"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>
                            </div>

                            <!-- Showcase Foto Before-After Jika Ada -->
                            <?php if (!empty($t['foto_before']) || !empty($t['foto_after'])): ?>
                                <div class="row g-2 mb-3">
                                    <?php if (!empty($t['foto_before'])): ?>
                                        <div class="<?= !empty($t['foto_after']) ? 'col-6' : 'col-12' ?>">
                                            <div class="position-relative rounded-3 overflow-hidden border shadow-sm" style="border-color: var(--color-sand) !important;">
                                                <span class="position-absolute top-0 start-0 m-1 badge" style="background-color: rgba(100, 116, 139, 0.9); font-size: 0.65rem;">Sebelum</span>
                                                <img src="<?= BASE_URL ?>/uploads/testimoni/<?= e($t['foto_before']) ?>" class="w-100" style="height: 110px; object-fit: cover;" alt="Foto Sebelum">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($t['foto_after'])): ?>
                                        <div class="<?= !empty($t['foto_before']) ? 'col-6' : 'col-12' ?>">
                                            <div class="position-relative rounded-3 overflow-hidden border shadow-sm" style="border-color: var(--color-sand) !important;">
                                                <span class="position-absolute top-0 start-0 m-1 badge" style="background-color: rgba(15, 118, 110, 0.9); font-size: 0.65rem;">Sesudah</span>
                                                <img src="<?= BASE_URL ?>/uploads/testimoni/<?= e($t['foto_after']) ?>" class="w-100" style="height: 110px; object-fit: cover;" alt="Foto Sesudah">
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <p class="font-body small mb-4 fst-italic" style="color: var(--color-body-text); line-height: 1.65;">
                                "<?= e($t['ulasan']) ?>"
                            </p>
                            <div class="mt-auto d-flex align-items-center justify-content-between pt-3 border-top" style="border-color: var(--color-sand) !important;">
                                <div>
                                    <strong class="d-block font-heading small" style="color: var(--color-heading);"><?= e($t['nama_pelanggan']) ?></strong>
                                    <span style="color: var(--color-muted); font-size: 0.78rem;"><?= e($t['nama_layanan']) ?></span>
                                </div>
                                <span class="badge rounded-pill" style="background-color: var(--color-cream); color: var(--color-dark); border: 1px solid var(--color-sand); font-size: 0.72rem;">Selesai Dikerjakan</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card card-custom h-100 p-4">
                        <div class="mb-3" style="color: #EAB308;">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="font-body small mb-4 fst-italic" style="color: var(--color-body-text); line-height: 1.65;">
                            "Kamar kos di Tembalang ditinggal libur semester berdebu parah. Pesan Deep Cleaning Kost, hasilnya kinclong banget dan wangi! Kerak kamar mandi hilang tuntas."
                        </p>
                        <div class="mt-auto d-flex align-items-center justify-content-between pt-3 border-top" style="border-color: var(--color-sand) !important;">
                            <div>
                                <strong class="d-block font-heading small" style="color: var(--color-heading);">Rian Pratama</strong>
                                <span style="color: var(--color-muted); font-size: 0.78rem;">Mahasiswa Undip Tembalang</span>
                            </div>
                            <span class="badge badge-palette-cream small"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="card card-custom h-100 p-4">
                        <div class="mb-3" style="color: #EAB308;">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="font-body small mb-4 fst-italic" style="color: var(--color-body-text); line-height: 1.65;">
                            "Waktu pindahan kos kemarin pesan Move-Out Cleaning. Kamar jadi bersih rapi kembali dan uang jaminan kos kembali utuh dari ibu kos. Sangat recommended!"
                        </p>
                        <div class="mt-auto d-flex align-items-center justify-content-between pt-3 border-top" style="border-color: var(--color-sand) !important;">
                            <div>
                                <strong class="d-block font-heading small" style="color: var(--color-heading);">Annisa Fitriani</strong>
                                <span style="color: var(--color-muted); font-size: 0.78rem;">Karyawan Swasta Banyumanik</span>
                            </div>
                            <span class="badge badge-palette-cream small"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-6">
                    <div class="card card-custom h-100 p-4">
                        <div class="mb-3" style="color: #EAB308;">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                        </div>
                        <p class="font-body small mb-4 fst-italic" style="color: var(--color-body-text); line-height: 1.65;">
                            "General Kost Cleaning harganya cuma 35rb per sesi, sangat bersahabat buat mahasiswa Unnes. Kamar wangi dan bersih tanpa harus capek beres-beres sendiri."
                        </p>
                        <div class="mt-auto d-flex align-items-center justify-content-between pt-3 border-top" style="border-color: var(--color-sand) !important;">
                            <div>
                                <strong class="d-block font-heading small" style="color: var(--color-heading);">Bagas Setiawan</strong>
                                <span style="color: var(--color-muted); font-size: 0.78rem;">Mahasiswa Unnes Sekaran</span>
                            </div>
                            <span class="badge badge-palette-cream small"><i class="bi bi-patch-check-fill"></i> Terverifikasi</span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CALL TO ACTION BANNER (SANTAI, HANGAT & ELEGAN) -->
<section class="py-5 text-white text-center position-relative mx-3 mx-lg-5 mb-5" style="background: linear-gradient(135deg, #484B6A 0%, #35384F 100%) !important; border-radius: 36px; box-shadow: 0 16px 40px -12px rgba(47, 50, 73, 0.4);">
    <div class="container py-4">
        <h2 class="display-6 font-heading mb-3 text-white">Istirahat Tenang di Kamar Kos yang Bersih</h2>
        <p class="lead mb-4 mx-auto" style="max-width: 650px; color: #FAF1DC !important; font-size: 1.15rem; line-height: 1.75;">
            Nggak perlu pusing lagi mikirin debu atau lantai kotor. Atur jadwal sekarang, cleaner kami yang selesaikan semuanya!
        </p>
        <a href="<?= BASE_URL ?>/booking" class="btn btn-brand-cta btn-lg px-5 py-3 shadow">
            <i class="bi bi-stars me-2"></i>Pesan Sekarang di Semarang
        </a>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
