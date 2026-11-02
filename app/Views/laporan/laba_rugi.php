<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    @media print {
        .no-print, nav, .navbar, footer, form { display: none !important; }
        body { font-size: 11pt; background: white !important; color: #000; }
        .card-custom { border: 1px solid #ccc !important; box-shadow: none !important; border-radius: 0 !important; }
        .container { max-width: 100% !important; }
        .laba-box { border: 2px solid #999 !important; background: #f9f9f9 !important; }
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
                <i class="bi bi-graph-up-arrow me-2" style="color: #D97706;"></i>Laporan Laba / Rugi
            </h2>
            <small class="font-body" style="color: var(--color-muted);">
                Periode: <?= date('d/m/Y', strtotime($report['startDate'])) ?>
                &ndash; <?= date('d/m/Y', strtotime($report['endDate'])) ?>
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

    <div class="row g-4">

        <!-- PENDAPATAN -->
        <div class="col-md-6">
            <div class="card card-custom p-4 h-100">
                <h5 class="fw-bold font-heading mb-3 pb-2 border-bottom"
                    style="color: #2E7D32; border-color: var(--color-sand) !important;">
                    <i class="bi bi-plus-circle-fill me-2"></i>Pendapatan
                </h5>
                <table class="table table-sm font-body small mb-0">
                    <thead>
                        <tr style="color: var(--color-muted);">
                            <th class="py-1 ps-0 border-0">Kode &amp; Nama Akun</th>
                            <th class="py-1 pe-0 border-0 text-end">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['pendapatanItems'] as $p): ?>
                        <tr>
                            <td class="py-2 ps-0" style="color: var(--color-body-text);">
                                <?= e($p['kode_akun']) ?> - <?= e($p['nama_akun']) ?>
                            </td>
                            <td class="py-2 pe-0 text-end fw-semibold" style="color: #2E7D32;">
                                <?= format_rupiah($p['saldo']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($report['pendapatanItems'])): ?>
                        <tr>
                            <td colspan="2" class="text-center py-3" style="color: var(--color-muted);">
                                Belum ada pendapatan tercatat.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #E8F5E9; border-top: 2px solid #A5D6A7;">
                            <td class="py-2 ps-0 fw-bold font-heading" style="color: #1B5E20;">Total Pendapatan</td>
                            <td class="py-2 pe-0 text-end fw-bold font-heading" style="color: #1B5E20; font-size: 1rem;">
                                <?= format_rupiah($report['totalPendapatan']) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- BEBAN -->
        <div class="col-md-6">
            <div class="card card-custom p-4 h-100">
                <h5 class="fw-bold font-heading mb-3 pb-2 border-bottom"
                    style="color: #C62828; border-color: var(--color-sand) !important;">
                    <i class="bi bi-dash-circle-fill me-2"></i>Beban Operasional
                </h5>
                <table class="table table-sm font-body small mb-0">
                    <thead>
                        <tr style="color: var(--color-muted);">
                            <th class="py-1 ps-0 border-0">Kode &amp; Nama Akun</th>
                            <th class="py-1 pe-0 border-0 text-end">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($report['bebanItems'] as $b): ?>
                        <tr>
                            <td class="py-2 ps-0" style="color: var(--color-body-text);">
                                <?= e($b['kode_akun']) ?> - <?= e($b['nama_akun']) ?>
                            </td>
                            <td class="py-2 pe-0 text-end fw-semibold" style="color: #C62828;">
                                <?= format_rupiah($b['saldo']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($report['bebanItems'])): ?>
                        <tr>
                            <td colspan="2" class="text-center py-3" style="color: var(--color-muted);">
                                Belum ada beban tercatat.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #FFEBEE; border-top: 2px solid #FFCDD2;">
                            <td class="py-2 ps-0 fw-bold font-heading" style="color: #B71C1C;">Total Beban</td>
                            <td class="py-2 pe-0 text-end fw-bold font-heading" style="color: #B71C1C; font-size: 1rem;">
                                <?= format_rupiah($report['totalBeban']) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- LABA / RUGI BERSIH -->
        <div class="col-12">
            <?php $isLaba = $report['labaBersih'] >= 0; ?>
            <div class="laba-box rounded-4 p-4 text-center"
                 style="background: <?= $isLaba ? 'linear-gradient(135deg,#E8F5E9,#C8E6C9)' : 'linear-gradient(135deg,#FFEBEE,#FFCDD2)' ?>;
                        border: 2px solid <?= $isLaba ? '#4CAF50' : '#F44336' ?>;">
                <div class="font-body mb-1" style="color: var(--color-muted); font-size: 0.85rem;">
                    Pendapatan <?= format_rupiah($report['totalPendapatan']) ?>
                    &minus; Beban <?= format_rupiah($report['totalBeban']) ?>
                </div>
                <div class="fw-bold font-heading" style="font-size: 0.95rem; color: <?= $isLaba ? '#1B5E20' : '#B71C1C' ?>; text-transform: uppercase;">
                    <?= $isLaba ? 'Laba Bersih Periode Ini' : 'Rugi Bersih Periode Ini' ?>
                </div>
                <div class="fw-bold font-heading" style="font-size: 2.5rem; color: <?= $isLaba ? '#2E7D32' : '#C62828' ?>; line-height: 1.2;">
                    <?= format_rupiah(abs($report['labaBersih'])) ?>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

