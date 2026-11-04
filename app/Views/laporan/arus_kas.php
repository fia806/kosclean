<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    @media print {
        .no-print, nav, .navbar, footer, form { display: none !important; }
        body { font-size: 11pt; background: white !important; color: #000; }
        .card-custom { border: 1px solid #ccc !important; box-shadow: none !important; border-radius: 0 !important; }
        .container { max-width: 100% !important; }
    }
</style>

<div class="container py-4 my-3">

    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <a href="<?= BASE_URL ?>/laporan" class="text-decoration-none small no-print" style="color: var(--color-dark);">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Menu Laporan
            </a>
            <h2 class="display-6 font-heading mb-0 mt-1" style="color: var(--color-heading);">
                <i class="bi bi-cash-coin me-2" style="color: #00695C;"></i>Laporan Arus Kas
            </h2>
            <small class="font-body" style="color: var(--color-muted);">
                Periode: <?= date('d/m/Y', strtotime($report['startDate'])) ?>
                &ndash; <?= date('d/m/Y', strtotime($report['endDate'])) ?>
                &nbsp;|&nbsp; Akun Kas Tunai (111) &amp; Kas Bank (112)
            </small>
        </div>
        <div class="no-print">
            <button onclick="window.print()" class="btn btn-brand-outline btn-sm">
                <i class="bi bi-printer me-1"></i>Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Filter -->
    <div class="card card-custom p-3 mb-4 no-print">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-bold font-heading small" style="color: var(--color-heading);">Dari Tanggal</label>
                <input type="date" name="start" class="form-control" value="<?= e($report['startDate']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold font-heading small" style="color: var(--color-heading);">Sampai Tanggal</label>
                <input type="date" name="end" class="form-control" value="<?= e($report['endDate']) ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-brand-primary w-100">
                    <i class="bi bi-search me-1"></i>Filter Periode
                </button>
            </div>
        </form>
    </div>

    <!-- Ringkasan 3 Kartu -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-custom p-3 text-center">
                <i class="bi bi-arrow-down-circle fs-2 mb-1" style="color: #2E7D32;"></i>
                <div class="fw-bold font-heading" style="color: #2E7D32; font-size: 1rem;">
                    <?= format_rupiah($report['total_masuk']) ?>
                </div>
                <div class="font-body small" style="color: var(--color-muted);">Total Kas Masuk</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-custom p-3 text-center">
                <i class="bi bi-arrow-up-circle fs-2 mb-1" style="color: #C62828;"></i>
                <div class="fw-bold font-heading" style="color: #C62828; font-size: 1rem;">
                    <?= format_rupiah($report['total_keluar']) ?>
                </div>
                <div class="font-body small" style="color: var(--color-muted);">Total Kas Keluar</div>
            </div>
        </div>
        <div class="col-md-4">
            <?php $naik = $report['kenaikan_kas'] >= 0; ?>
            <div class="card card-custom p-3 text-center" style="background-color: <?= $naik ? '#E8F5E9' : '#FFEBEE' ?> !important;">
                <i class="bi bi-cash-stack fs-2 mb-1" style="color: <?= $naik ? '#00695C' : '#C62828' ?>;"></i>
                <div class="fw-bold font-heading" style="color: <?= $naik ? '#00695C' : '#C62828' ?>; font-size: 1rem;">
                    <?= ($naik ? '' : '(') . format_rupiah(abs($report['kenaikan_kas'])) . ($naik ? '' : ')') ?>
                </div>
                <div class="font-body small" style="color: var(--color-muted);">
                    <?= $naik ? 'Kenaikan' : 'Penurunan' ?> Kas Bersih
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rincian Arus Kas -->
    <div class="card card-custom p-4">
        <h5 class="fw-bold font-heading mb-3" style="color: var(--color-heading);">
            Rincian Pergerakan Kas Operasional
        </h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 font-body small">
                <thead style="background-color: var(--color-cream); color: var(--color-heading);">
                    <tr>
                        <th class="py-3">Tanggal</th>
                        <th class="py-3">Keterangan</th>
                        <th class="py-3">Referensi</th>
                        <th class="py-3">Akun</th>
                        <th class="py-3 text-end" style="color: #2E7D32;">Kas Masuk</th>
                        <th class="py-3 text-end" style="color: #C62828;">Kas Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($report['mutasi'])): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5" style="color: var(--color-muted);">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Tidak ada pergerakan kas/bank pada periode ini.
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($report['mutasi'] as $m): ?>
                    <tr>
                        <td style="color: var(--color-body-text); white-space: nowrap;">
                            <?= date('d/m/Y', strtotime($m['tanggal_transaksi'])) ?>
                        </td>
                        <td style="color: var(--color-body-text);"><?= e($m['deskripsi']) ?></td>
                        <td>
                            <span class="badge rounded-pill"
                                  style="background-color: var(--color-cream); color: var(--color-dark); border: 1px solid var(--color-sand); font-size: 0.7rem;">
                                <?= e($m['referensi'] ?? '-') ?>
                            </span>
                        </td>
                        <td>
                            <small style="color: var(--color-muted);">
                                <?= e($m['kode_akun']) ?> - <?= e($m['nama_akun']) ?>
                            </small>
                        </td>
                        <td class="text-end fw-bold" style="color: #2E7D32;">
                            <?= $m['posisi'] === 'Debet'
                                ? format_rupiah($m['nominal'])
                                : '<span style="color:var(--color-muted)">-</span>' ?>
                        </td>
                        <td class="text-end fw-bold" style="color: #C62828;">
                            <?= $m['posisi'] === 'Kredit'
                                ? format_rupiah($m['nominal'])
                                : '<span style="color:var(--color-muted)">-</span>' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: var(--color-cream); border-top: 2px solid var(--color-sand);">
                        <td colspan="4" class="fw-bold font-heading text-end py-2" style="color: var(--color-heading);">TOTAL:</td>
                        <td class="text-end fw-bold py-2" style="color: #2E7D32;"><?= format_rupiah($report['total_masuk']) ?></td>
                        <td class="text-end fw-bold py-2" style="color: #C62828;"><?= format_rupiah($report['total_keluar']) ?></td>
                    </tr>
                    <tr style="background-color: <?= $naik ? '#E8F5E9' : '#FFEBEE' ?>;">
                        <td colspan="5" class="fw-bold font-heading text-end py-2" style="color: var(--color-heading);">
                            KENAIKAN / (PENURUNAN) KAS BERSIH:
                        </td>
                        <td class="text-end fw-bold font-heading py-2 fs-6"
                            style="color: <?= $naik ? '#00695C' : '#C62828' ?>;">
                            <?= ($naik ? '' : '(') . format_rupiah(abs($report['kenaikan_kas'])) . ($naik ? '' : ')') ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

