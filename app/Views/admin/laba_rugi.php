<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4 my-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge badge-mist mb-1">
                <i class="bi bi-file-earmark-spreadsheet me-1"></i> Laporan Keuangan
            </span>
            <h2 class="display-6 font-heading mb-0" style="color: var(--color-heading);">Laporan Laba / Rugi</h2>
            <p class="font-body small mb-0" style="color: var(--color-body-text);">Ringkasan total pendapatan jasa dikurangi total beban operasional dalam periode tertentu.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-brand-outline btn-sm">
                <i class="bi bi-printer me-1"></i>Cetak Laporan
            </button>
            <a href="<?= BASE_URL ?>/admin/jurnal" class="btn btn-brand-primary btn-sm shadow">
                <i class="bi bi-journal-text me-1"></i>Lihat Jurnal Umum
            </a>
        </div>
    </div>

    <!-- Filter Rentang Tanggal -->
    <div class="card card-custom p-3 mb-4">
        <form action="<?= BASE_URL ?>/admin/labaRugi" method="GET" class="row g-2 align-items-center font-body">
            <div class="col-auto small fw-bold" style="color: var(--color-heading);">Periode Laporan:</div>
            <div class="col-auto">
                <input type="date" name="start" class="form-control form-control-sm" value="<?= e($report['startDate']) ?>">
            </div>
            <div class="col-auto small" style="color: var(--color-muted);">s/d</div>
            <div class="col-auto">
                <input type="date" name="end" class="form-control form-control-sm" value="<?= e($report['endDate']) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-brand-primary">Tampilkan</button>
            </div>
        </form>
    </div>

    <!-- Konten Laporan Laba / Rugi Formal -->
    <div class="card card-custom p-4 p-md-5 bg-white">
        <!-- Kop Laporan -->
        <div class="text-center pb-4 mb-4 border-bottom" style="border-color: var(--color-sand) !important;">
            <h3 class="fw-extrabold font-heading mb-1" style="color: var(--color-heading);">KosClean Semarang</h3>
            <h5 class="fw-bold font-heading mb-1" style="color: var(--color-dark);">LAPORAN LABA / RUGI</h5>
            <small class="font-body" style="color: var(--color-muted);">Periode: <?= date('d F Y', strtotime($report['startDate'])) ?> s/d <?= date('d F Y', strtotime($report['endDate'])) ?></small>
        </div>

        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0 font-body">
                <tbody>
                    <!-- 1. PENDAPATAN -->
                    <tr style="background-color: var(--color-cream);">
                        <td colspan="2" class="fw-bold font-heading fs-6 py-2" style="color: var(--color-heading);">
                            <i class="bi bi-arrow-down-circle text-success me-1"></i> I. PENDAPATAN OPERASIONAL
                        </td>
                        <td class="text-end fw-bold font-heading fs-6 py-2"></td>
                    </tr>
                    <?php if (empty($report['pendapatanItems'])): ?>
                        <tr><td colspan="3" class="ps-4 small font-body" style="color: var(--color-muted);">Tidak ada akun pendapatan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($report['pendapatanItems'] as $p): ?>
                            <tr class="small border-bottom" style="border-color: var(--color-sand) !important;">
                                <td style="width: 120px; color: var(--color-muted);" class="ps-4"><?= e($p['kode_akun']) ?></td>
                                <td style="color: var(--color-body-text);"><?= e($p['nama_akun']) ?></td>
                                <td class="text-end fw-semibold font-heading" style="width: 200px; color: var(--color-heading);">
                                    <?= number_format((float)$p['saldo'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <tr class="fw-bold border-top" style="background-color: #E8F5E9; border-color: var(--color-sand) !important;">
                        <td colspan="2" class="ps-4 text-success font-heading">TOTAL PENDAPATAN (A)</td>
                        <td class="text-end text-success fs-6 font-heading"><?= format_rupiah($report['totalPendapatan']) ?></td>
                    </tr>

                    <tr><td colspan="3" class="py-3"></td></tr>

                    <!-- 2. BEBAN-BEBAN -->
                    <tr style="background-color: var(--color-cream);">
                        <td colspan="2" class="fw-bold font-heading fs-6 py-2" style="color: var(--color-heading);">
                            <i class="bi bi-arrow-up-circle text-danger me-1"></i> II. BEBAN OPERASIONAL
                        </td>
                        <td class="text-end fw-bold font-heading fs-6 py-2"></td>
                    </tr>
                    <?php if (empty($report['bebanItems'])): ?>
                        <tr><td colspan="3" class="ps-4 small font-body" style="color: var(--color-muted);">Tidak ada akun beban.</td></tr>
                    <?php else: ?>
                        <?php foreach ($report['bebanItems'] as $b): ?>
                            <tr class="small border-bottom" style="border-color: var(--color-sand) !important;">
                                <td style="width: 120px; color: var(--color-muted);" class="ps-4"><?= e($b['kode_akun']) ?></td>
                                <td style="color: var(--color-body-text);"><?= e($b['nama_akun']) ?></td>
                                <td class="text-end fw-semibold font-heading text-danger" style="width: 200px;">
                                    <?= number_format((float)$b['saldo'], 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <tr class="fw-bold border-top" style="background-color: #FFEBEE; border-color: var(--color-sand) !important;">
                        <td colspan="2" class="ps-4 text-danger font-heading">TOTAL BEBAN OPERASIONAL (B)</td>
                        <td class="text-end text-danger fs-6 font-heading"><?= format_rupiah($report['totalBeban']) ?></td>
                    </tr>

                    <tr><td colspan="3" class="py-3"></td></tr>

                    <!-- 3. LABA / RUGI BERSIH -->
                    <?php $isLaba = ($report['labaBersih'] >= 0); ?>
                    <tr class="fw-extrabold border" style="background-color: <?= $isLaba ? 'var(--color-cream)' : '#FFEBEE' ?>; border-color: var(--color-sand) !important;">
                        <td colspan="2" class="ps-4 py-3 font-heading fs-5" style="color: <?= $isLaba ? 'var(--color-dark)' : '#C62828' ?>;">
                            LABA BERSIH (A - B)
                        </td>
                        <td class="text-end py-3 font-heading fs-4" style="color: <?= $isLaba ? 'var(--color-dark)' : '#C62828' ?>;">
                            <?= format_rupiah($report['labaBersih']) ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
