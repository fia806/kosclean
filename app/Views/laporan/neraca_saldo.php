<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    @media print {
        .no-print, nav, .navbar, footer, form { display: none !important; }
        body { font-size: 11pt; background: white !important; color: #000; }
        .card-custom { border: 1px solid #ccc !important; box-shadow: none !important; border-radius: 0 !important; }
        .container { max-width: 100% !important; }
        .alert { border: 1px solid #999 !important; background: #f9f9f9 !important; color: #000 !important; }
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
                <i class="bi bi-table me-2" style="color: #2E7D32;"></i>Neraca Saldo (Trial Balance)
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

    <!-- Status Keseimbangan -->
    <?php if ($report['is_balanced']): ?>
    <div class="alert rounded-4 mb-4 d-flex align-items-center gap-3"
         style="background-color: #E8F5E9; border: 2px solid #4CAF50; color: #1B5E20;">
        <i class="bi bi-check-circle-fill fs-3"></i>
        <div>
            <strong class="font-heading">Buku Seimbang!</strong>
            <div class="font-body small">Total Debet = Total Kredit = <?= format_rupiah($report['grand_debet']) ?>. Pembukuan telah benar.</div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert rounded-4 mb-4 d-flex align-items-center gap-3"
         style="background-color: #FFEBEE; border: 2px solid #F44336; color: #B71C1C;">
        <i class="bi bi-exclamation-triangle-fill fs-3"></i>
        <div>
            <strong class="font-heading">Buku Tidak Seimbang!</strong>
            <div class="font-body small">
                Selisih: <?= format_rupiah(abs($report['grand_debet'] - $report['grand_kredit'])) ?>.
                Periksa entri jurnal yang mungkin tidak terbukukan dengan benar.
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Tabel Neraca Saldo -->
    <div class="card card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 font-body small">
                <thead style="background-color: var(--color-cream); color: var(--color-heading);">
                    <tr>
                        <th class="py-3">Kode Akun</th>
                        <th class="py-3">Nama Akun</th>
                        <th class="py-3">Kategori</th>
                        <th class="py-3">Saldo Normal</th>
                        <th class="py-3 text-end" style="color: #1565C0;">Total Debet</th>
                        <th class="py-3 text-end" style="color: #C62828;">Total Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report['rows'] as $row): ?>
                    <tr>
                        <td>
                            <strong class="font-heading" style="color: var(--color-heading);">
                                <?= e($row['kode_akun']) ?>
                            </strong>
                        </td>
                        <td style="color: var(--color-body-text);"><?= e($row['nama_akun']) ?></td>
                        <td>
                            <span class="badge rounded-pill"
                                  style="background-color: var(--color-cream); color: var(--color-dark); border: 1px solid var(--color-sand); font-size: 0.7rem;">
                                <?= e($row['kategori']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge rounded-pill" style="font-size: 0.7rem;
                                background-color: <?= $row['saldo_normal'] === 'Debet' ? '#EBF5FB' : '#FDF2F8' ?>;
                                color: <?= $row['saldo_normal'] === 'Debet' ? '#1565C0' : '#C62828' ?>;
                                border: 1px solid <?= $row['saldo_normal'] === 'Debet' ? '#BBDEFB' : '#F8BBD0' ?>;">
                                <?= e($row['saldo_normal']) ?>
                            </span>
                        </td>
                        <td class="text-end" style="color: #1565C0; font-weight: 600;">
                            <?= (float)$row['total_debet'] > 0 ? format_rupiah($row['total_debet']) : '<span style="color:var(--color-muted)">-</span>' ?>
                        </td>
                        <td class="text-end" style="color: #C62828; font-weight: 600;">
                            <?= (float)$row['total_kredit'] > 0 ? format_rupiah($row['total_kredit']) : '<span style="color:var(--color-muted)">-</span>' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: var(--color-cream); border-top: 2px solid var(--color-sand);">
                        <td colspan="4" class="fw-bold font-heading text-end py-3" style="color: var(--color-heading);">
                            GRAND TOTAL:
                        </td>
                        <td class="text-end fw-bold font-heading py-3 fs-6" style="color: #1565C0;">
                            <?= format_rupiah($report['grand_debet']) ?>
                        </td>
                        <td class="text-end fw-bold font-heading py-3 fs-6" style="color: #C62828;">
                            <?= format_rupiah($report['grand_kredit']) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

