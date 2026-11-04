<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    @media print {
        .no-print, nav, .navbar, footer, form, .btn { display: none !important; }
        body { font-size: 11pt; background: white !important; }
        .card-custom { border: 1px solid #ccc !important; box-shadow: none !important; border-radius: 4px !important; }
        .container { max-width: 100% !important; }
    }
</style>

<div class="container py-4 my-3">

    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item">
                        <a href="<?= BASE_URL ?>/admin" class="text-decoration-none" style="color: var(--color-dark);">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active" style="color: var(--color-heading);">Aset & Depresiasi</li>
                </ol>
            </nav>
            <h2 class="display-6 font-heading mb-0" style="color: var(--color-heading);">
                <i class="bi bi-tools me-2" style="color: var(--color-dark);"></i>Manajemen Aset & Depresiasi
            </h2>
            <p class="font-body small mb-0 mt-1" style="color: var(--color-muted);">
                Perhitungan penyusutan metode <strong>Garis Lurus (Straight-Line)</strong> per bulan sesuai SAK EMKM.
            </p>
        </div>
        <div class="no-print d-flex gap-2">
            <button onclick="window.print()" class="btn btn-brand-outline btn-sm">
                <i class="bi bi-printer me-1"></i>Cetak
            </button>
            <a href="<?= BASE_URL ?>/admin/jurnal" class="btn btn-brand-outline btn-sm">
                <i class="bi bi-journal-text me-1"></i>Lihat Jurnal
            </a>
        </div>
    </div>

    <!-- Kotak Rumus -->
    <div class="alert rounded-4 mb-4" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand); color: var(--color-heading);">
        <div class="d-flex align-items-start gap-3">
            <i class="bi bi-info-circle-fill fs-4 flex-shrink-0" style="color: var(--color-dark);"></i>
            <div>
                <h6 class="fw-bold font-heading mb-1">Formula Penyusutan Garis Lurus (SAK EMKM)</h6>
                <p class="font-body small mb-1">
                    <strong>Penyusutan Bulanan = (Harga Beli &minus; Nilai Sisa) &divide; Umur Ekonomis (Bulan)</strong>
                </p>
                <p class="font-body small mb-0" style="color: var(--color-muted);">
                    Pencatatan jurnal otomatis: &nbsp;
                    <span class="badge" style="background:#1565C0;color:#fff;">Debet: 513 Beban Penyusutan Peralatan</span>
                    &nbsp;&rarr;&nbsp;
                    <span class="badge" style="background:#C62828;color:#fff;">Kredit: 122 Akumulasi Penyusutan Peralatan</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Form Hitung Penyusutan -->
    <div class="card card-custom p-4 mb-4 no-print">
        <h5 class="fw-bold font-heading mb-3" style="color: var(--color-heading);">
            <i class="bi bi-calculator-fill me-2" style="color: var(--color-dark);"></i>Hitung & Bukukan Penyusutan Bulanan
        </h5>
        <p class="small font-body mb-3" style="color: var(--color-body-text);">
            Sistem secara otomatis menghitung penyusutan semua aset kategori <strong>alat_kebersihan</strong> dan
            menginjeksikan ke Jurnal Umum. Mekanisme <strong>idempotensi</strong> mencegah jurnal ganda pada periode yang sama.
        </p>
        <form action="<?= BASE_URL ?>/admin/asetHitungPenyusutan" method="POST" class="row g-3 align-items-end">
            <?= csrf_field() ?>
            <div class="col-md-4">
                <label class="form-label fw-bold font-heading small" style="color: var(--color-heading);">
                    Periode Penyusutan <span class="text-danger">*</span>
                </label>
                <input type="month" name="periode_bulan" class="form-control"
                       value="<?= date('Y-m') ?>" required
                       style="border-color: var(--color-sand);">
                <div class="form-text font-body" style="font-size:0.72rem; color: var(--color-muted);">
                    Pilih bulan yang ingin dihitung penyusutannya.
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-brand-primary w-100 fw-bold"
                        onclick="return confirm('Hitung & bukukan penyusutan semua aset peralatan untuk periode ini?\nProses ini tidak dapat dibatalkan.');">
                    <i class="bi bi-lightning-charge-fill me-1"></i>Hitung & Catat ke Jurnal
                </button>
            </div>
        </form>
    </div>

    <!-- Tabel Aset -->
    <div class="card card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold font-heading mb-0" style="color: var(--color-heading);">
                <i class="bi bi-list-ul me-2"></i>Daftar Aset & Peralatan
                <span class="badge rounded-pill ms-2" style="background-color: var(--color-slate-blue); color: #fff; font-size: 0.75rem;">
                    <?= count($asets) ?> item
                </span>
            </h5>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 font-body small">
                <thead style="background-color: var(--color-cream); color: var(--color-heading);">
                    <tr>
                        <th class="py-3">Nama Aset</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Tgl Beli</th>
                        <th class="py-3 text-end">Harga Beli</th>
                        <th class="py-3 text-end">Nilai Sisa</th>
                        <th class="py-3 text-center">Umur Ekonomis</th>
                        <th class="py-3 text-end" style="color: #D97706;">Penyusutan/Bulan</th>
                        <th class="py-3 text-center">Kondisi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($asets)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5" style="color: var(--color-muted);">
                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                Belum ada data aset. Tambahkan melalui manajemen database.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $grandTotalDepresiasi = 0;
                        foreach ($asets as $aset):
                            $hargaBeli    = (float)($aset['harga_beli'] ?? 0);
                            $nilaiSisa    = (float)($aset['nilai_sisa'] ?? 0);
                            $umurEkonomis = (int)($aset['umur_ekonomis'] ?? 0);
                            $depresiasi   = ($umurEkonomis > 0 && $hargaBeli > $nilaiSisa)
                                            ? ($hargaBeli - $nilaiSisa) / $umurEkonomis
                                            : 0;
                            if ($aset['kategori'] === 'alat_kebersihan') {
                                $grandTotalDepresiasi += $depresiasi;
                            }
                        ?>
                        <tr>
                            <td>
                                <strong class="font-heading d-block" style="color: var(--color-heading);">
                                    <?= e($aset['nama_barang']) ?>
                                </strong>
                                <small style="color: var(--color-muted);">ID: <?= (int)$aset['id_aset'] ?></small>
                            </td>
                            <td>
                                <span class="badge rounded-pill"
                                      style="background-color: var(--color-cream); color: var(--color-dark); border: 1px solid var(--color-sand); font-size: 0.72rem;">
                                    <?= e($aset['kategori']) ?>
                                </span>
                            </td>
                            <td style="color: var(--color-body-text);">
                                <?= !empty($aset['tanggal_pembelian'])
                                    ? date('d/m/Y', strtotime($aset['tanggal_pembelian']))
                                    : '<span style="color:var(--color-muted)">-</span>' ?>
                            </td>
                            <td class="text-end fw-bold font-heading" style="color: var(--color-heading);">
                                <?= $hargaBeli > 0 ? format_rupiah($hargaBeli) : '<span style="color:var(--color-muted)">-</span>' ?>
                            </td>
                            <td class="text-end" style="color: var(--color-body-text);">
                                <?= format_rupiah($nilaiSisa) ?>
                            </td>
                            <td class="text-center" style="color: var(--color-body-text);">
                                <?= $umurEkonomis > 0
                                    ? "<strong>{$umurEkonomis}</strong> bln"
                                    : '<span style="color:var(--color-muted)">-</span>' ?>
                            </td>
                            <td class="text-end fw-bold"
                                style="color: <?= ($depresiasi > 0 && $aset['kategori'] === 'alat_kebersihan') ? '#D97706' : 'var(--color-muted)' ?>;">
                                <?php if ($depresiasi > 0 && $aset['kategori'] === 'alat_kebersihan'): ?>
                                    <?= format_rupiah($depresiasi) ?>
                                <?php elseif ($aset['kategori'] !== 'alat_kebersihan'): ?>
                                    <span style="color: var(--color-muted); font-size: 0.72rem;">Bukan alat kebersihan</span>
                                <?php else: ?>
                                    <span style="color: var(--color-muted);">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    $kondisi = $aset['kondisi'] ?? '';
                                    $kondisiStyle = match($kondisi) {
                                        'baik'          => 'background:#2E7D32;color:#fff',
                                        'rusak'         => 'background:#C62828;color:#fff',
                                        'perlu_service' => 'background:#D97706;color:#fff',
                                        default         => 'background:var(--color-sand);color:var(--color-dark)'
                                    };
                                ?>
                                <span class="badge rounded-pill" style="<?= $kondisiStyle ?>">
                                    <?= ucfirst(str_replace('_', ' ', e($kondisi))) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <?php if (!empty($asets)): ?>
                <tfoot>
                    <tr style="background-color: var(--color-cream); border-top: 2px solid var(--color-sand);">
                        <td colspan="6" class="fw-bold font-heading py-3 text-end" style="color: var(--color-heading);">
                            <i class="bi bi-sigma me-1"></i>Total Beban Penyusutan Bulanan (Alat Kebersihan):
                        </td>
                        <td class="fw-bold font-heading text-end py-3" style="color: var(--color-dark); font-size: 1.05rem;">
                            <?= format_rupiah($grandTotalDepresiasi) ?>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

