<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'KosClean - Jasa Bersih Kos Semarang') ?></title>
    
    <!-- Google Fonts: Outfit (Judul Catchy, Bold & Santai) + Plus Jakarta Sans (Penjelasan Elegan & Nyaman Dibaca) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Tema Warna Eksklusif Sesuai Palet Gambar 4 Warna -->
    <style>
        :root {
            /* 4 WARNA UTAMA SESUAI GAMBAR PALET SWATCH */
            --color-dark: #484B6A;          /* Band 1: Deep Slate Indigo / Twilight Navy */
            --color-slate-blue: #8F99B5;    /* Band 2: Dusty Periwinkle / Slate Blue */
            --color-sand: #D8CEBE;          /* Band 3: Warm Oatmeal Sand / Soft Taupe */
            --color-cream: #FAF1DC;         /* Band 4: Warm Vanilla Cream / Pale Ivory */
            
            /* Warna Pendukung Kontras Tinggi & Keterbacaan Optimal */
            --color-dark-deep: #2F3249;     /* Deep Twilight (Footer, Hero dark gradient) */
            --color-heading: #2D3047;       /* Judul Utama (Sangat tegas, tajam, mudah dibaca) */
            --color-body-text: #41455C;     /* Penjelasan / Body (Kontras tinggi > 8:1, nyaman di mata) */
            --color-muted: #64687F;         /* Teks sekunder yang tetap terbaca jelas */
            --color-card-bg: #FFFFFF;       /* Background kartu putih bersih */
            --color-page-bg: #FAF6ED;       /* Canvas latar belakang: warm cream lembut */
            
            /* Gradasi Harmonis & Modern */
            --gradient-primary: linear-gradient(135deg, #484B6A 0%, #383A52 60%, #545B7A 100%);
            --gradient-hero: linear-gradient(140deg, #484B6A 0%, #35384F 55%, #424663 100%);
            --primary-gradient: linear-gradient(135deg, #484B6A 0%, #383A52 60%, #545B7A 100%); /* Alias backward compatibility */
            --gradient-cream-sand: linear-gradient(135deg, #FAF1DC 0%, #D8CEBE 100%);
            
            /* Alias kompatibilitas tema */
            --color-deep-blue: #484B6A;
            --color-deep-dark: #2F3249;
            --color-marine: #8F99B5;
            --color-mist: #FAF1DC;
            --color-mist-light: #F4EFE6;
            
            /* Shadow Halus, Santai & Modern */
            --shadow-card: 0 10px 30px -8px rgba(72, 75, 106, 0.12);
            --shadow-hover: 0 18px 42px -10px rgba(72, 75, 106, 0.22);
        }

        /* TIPOGRAFI: PEMBEDAAN JELAS, TAJAM & SANGAT MUDAH DIBACA */
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            font-size: 0.98rem;
            font-weight: 400;
            line-height: 1.7;
            background-color: var(--color-page-bg);
            color: var(--color-body-text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        /* JUDUL: FONT OUTFIT (BOLD, CATCHY, TEGAS & MENARIK) */
        h1, h2, h3, h4, h5, h6, 
        .h1, .h2, .h3, .h4, .h5, .h6, 
        .navbar-brand, .font-heading {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            color: var(--color-heading);
            letter-spacing: -0.025em;
            line-height: 1.28;
        }

        /* PENJELASAN: FONT PLUS JAKARTA SANS (LEMBUT, SANTAI & SANGAT MUDAH DIBACA) */
        p, .font-body, .text-explanation {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--color-body-text);
            font-size: 0.96rem;
            line-height: 1.7;
            margin-bottom: 1rem;
        }

        .lead {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.12rem;
            font-weight: 400;
            line-height: 1.72;
            color: var(--color-body-text);
        }

        .main-content {
            flex: 1 0 auto;
        }

        /* HERO SECTION KHUSUS (PASTI KONTRAS TINGGI, TEKS PUTIH & CREAM NYAMAN) */
        .hero-banner {
            background: var(--gradient-hero) !important;
            color: #FFFFFF !important;
            border-radius: 0 0 44px 44px;
            box-shadow: 0 16px 40px -12px rgba(47, 50, 73, 0.4);
            position: relative;
        }

        .hero-banner h1, .hero-banner h2, .hero-banner h3, .hero-banner .font-heading {
            color: #FFFFFF !important;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
        }

        .hero-banner p, .hero-banner .lead {
            color: #FAF1DC !important; /* Warm vanilla cream yang sangat kontras dan ramah dibaca di latar gelap */
            font-weight: 400;
        }

        .hero-badge {
            background-color: rgba(143, 153, 181, 0.25);
            border: 1px solid rgba(250, 241, 220, 0.35);
            color: var(--color-cream) !important;
            backdrop-filter: blur(8px);
        }

        /* NAVBAR ELEGAN DENGAN DEEP SLATE INDIGO */
        .navbar-custom {
            background-color: var(--color-dark) !important;
            box-shadow: 0 4px 20px rgba(47, 50, 73, 0.22);
            border-bottom: 2px solid rgba(143, 153, 181, 0.25);
        }

        .navbar-brand-logo {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 1.45rem;
            letter-spacing: -0.03em;
            color: #FFFFFF !important;
        }

        .nav-link {
            font-family: 'Outfit', sans-serif;
            font-weight: 600;
            font-size: 0.95rem;
            color: rgba(250, 241, 220, 0.88) !important;
            transition: all 0.25s ease;
            padding: 8px 16px !important;
            border-radius: 50px;
        }

        .nav-link:hover, .nav-link.active {
            color: #FFFFFF !important;
            background-color: rgba(143, 153, 181, 0.25);
        }

        /* TOMBOL (PILL SHAPE, TACTILE, SANTAI & RAMAH PENGGUNA) */
        .btn-brand-primary {
            background: var(--gradient-primary);
            color: #FFFFFF !important;
            border: none;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 12px 28px;
            border-radius: 50px;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 6px 20px rgba(72, 75, 106, 0.3);
        }

        .btn-brand-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(72, 75, 106, 0.4);
            filter: brightness(1.1);
        }

        /* Tombol CTA Warm Vanilla Cream */
        .btn-brand-cta {
            background-color: var(--color-cream) !important;
            color: var(--color-dark) !important;
            border: 2px solid var(--color-sand) !important;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 11px 26px;
            border-radius: 50px;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        }

        .btn-brand-cta:hover {
            background-color: #FFFFFF !important;
            border-color: var(--color-slate-blue) !important;
            color: var(--color-dark) !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(72, 75, 106, 0.22);
        }

        .btn-brand-outline {
            border: 2px solid var(--color-dark);
            color: var(--color-dark) !important;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 10px 24px;
            border-radius: 50px;
            background: transparent;
            transition: all 0.25s ease;
        }

        .btn-brand-outline:hover {
            background-color: var(--color-dark);
            color: #FFFFFF !important;
            transform: translateY(-2px);
        }

        /* CARD SANTAI & AESTHETIC (LATAR PUTIH BERSIH AGAR TULISAN SUPER JELAS) */
        .card-custom {
            border: 1.5px solid var(--color-sand) !important;
            border-radius: 24px !important;
            background: var(--color-card-bg) !important;
            box-shadow: var(--shadow-card);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
            border-color: var(--color-slate-blue) !important;
        }

        /* BADGES LEMBUT & KONTRAS */
        .badge-palette-cream, .badge-mist {
            background-color: var(--color-cream) !important;
            color: var(--color-dark) !important;
            border: 1px solid var(--color-sand);
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 7px 16px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-palette-slate, .badge-marine {
            background-color: var(--color-slate-blue) !important;
            color: #FFFFFF !important;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 7px 16px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-palette-dark, .badge-dark {
            background-color: var(--color-dark) !important;
            color: #FFFFFF !important;
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            padding: 7px 16px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* BOKS PERINGATAN KEAMANAN SANTAI */
        .alert-security-warning {
            background-color: #FFFDF5;
            border: 2px dashed #D8CEBE;
            color: var(--color-heading);
            border-radius: 20px;
            padding: 20px 24px;
        }

        /* FORM INPUT DENGAN SUDUT SANTAI & TEKS TEGAS */
        .form-control, .form-select {
            border-radius: 16px;
            border: 1.5px solid var(--color-sand);
            padding: 12px 18px;
            font-size: 0.95rem;
            color: var(--color-heading);
            transition: all 0.25s ease;
            background-color: #FFFFFF;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--color-dark);
            box-shadow: 0 0 0 4px rgba(72, 75, 106, 0.18);
        }

        /* DETAIL TEKS KONTRAS */
        .text-dark-custom {
            color: var(--color-heading) !important;
        }

        .text-muted-custom {
            color: var(--color-body-text) !important;
        }
    </style>
</head>
<body>

<!-- Navigation Bar (Menggunakan Deep Slate Indigo #484B6A) -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-3">
    <div class="container">
        <a class="navbar-brand navbar-brand-logo d-flex align-items-center gap-2 text-white" href="<?= BASE_URL ?>">
            <span class="p-2 rounded-circle d-inline-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 40px; height: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.15);">
                <i class="bi bi-stars fs-5"></i>
            </span>
            <span>Kos<span style="color: var(--color-cream);">Clean</span></span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4 gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link active" href="<?= BASE_URL ?>">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>#layanan">Katalog Paket</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>#area">Area Semarang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>/home/terms">Syarat & Ketentuan</a>
                </li>
            </ul>

            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                <?php if (is_logged_in()): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle rounded-pill px-3 py-2 d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" style="border-color: rgba(250, 241, 220, 0.4);">
                            <i class="bi bi-person-circle"></i>
                            <span class="font-heading"><?= e($_SESSION['nama_lengkap'] ?? 'Akun Saya') ?></span>
                            <span class="badge rounded-pill ms-1" style="background-color: var(--color-cream); color: var(--color-dark);">
                                <?= ucfirst(e($_SESSION['role'] ?? '')) ?>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-2 p-2">
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <li><a class="dropdown-item rounded-3 py-2" href="<?= BASE_URL ?>/admin"><i class="bi bi-speedometer2 me-2 text-primary"></i>Admin Dashboard</a></li>
                                <li><a class="dropdown-item rounded-3 py-2" href="<?= BASE_URL ?>/admin/orders"><i class="bi bi-card-checklist me-2 text-primary"></i>Manajemen Pesanan</a></li>
                                <li><a class="dropdown-item rounded-3 py-2" href="<?= BASE_URL ?>/admin/layanan"><i class="bi bi-grid me-2 text-primary"></i>Kelola Layanan</a></li>
                                <li><a class="dropdown-item rounded-3 py-2" href="<?= BASE_URL ?>/admin/jurnal"><i class="bi bi-journal-text me-2 text-primary"></i>Jurnal Akuntansi</a></li>
                                <li><a class="dropdown-item rounded-3 py-2" href="<?= BASE_URL ?>/admin/labaRugi"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Laporan Laba/Rugi</a></li>
                                <li><a class="dropdown-item rounded-3 py-2" href="<?= BASE_URL ?>/admin/aset"><i class="bi bi-tools me-2 text-primary"></i>Aset &amp; Depresiasi</a></li>
                                <li><hr class="dropdown-divider" style="border-color: var(--color-sand);"></li>
                                <li><a class="dropdown-item rounded-3 py-2 fw-bold" href="<?= BASE_URL ?>/laporan" style="color: var(--color-dark);"><i class="bi bi-bar-chart-line-fill me-2"></i>Pusat Laporan SAK</a></li>
                            <?php elseif ($_SESSION['role'] === 'karyawan'): ?>
                                <li><a class="dropdown-item rounded-3 py-2" href="<?= BASE_URL ?>/karyawan"><i class="bi bi-person-workspace me-2 text-success"></i>Dashboard Cleaner</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item rounded-3 py-2" href="<?= BASE_URL ?>/pelanggan/dashboard"><i class="bi bi-clock-history me-2 text-primary"></i>Pesanan Saya</a></li>
                                <li><a class="dropdown-item rounded-3 py-2" href="<?= BASE_URL ?>/booking"><i class="bi bi-calendar-plus me-2 text-success"></i>Booking Baru</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded-3 py-2 text-danger" href="<?= BASE_URL ?>/auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/auth/login" class="btn btn-outline-light rounded-pill px-4" style="border-color: rgba(250,241,220,0.5); color: var(--color-cream);">Masuk</a>
                    <a href="<?= BASE_URL ?>/auth/register" class="btn btn-brand-cta">Daftar</a>
                <?php endif; ?>

                <a href="<?= BASE_URL ?>/booking" class="btn btn-brand-cta d-none d-md-inline-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-stars"></i> Pesan di Semarang
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="main-content">
    <div class="container">
        <?= display_flash() ?>
    </div>
