<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    @media print {
        .no-print, nav, .navbar, footer, form { display: none !important; }
        body { font-size: 11pt; background: white !important; color: #000; }
        .card-custom { border: 1px solid #ccc !important; box-shadow: none !important; border-radius: 0 !important; }
        .container { max-width: 100% !important; }
        h2 { font-size: 18pt !important; }
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
                <i class="bi bi-arrow-left-right me-2" style="color: #1565C0;"></i>Mutasi Kas & Bank
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

    <!-- Filter Tanggal -->
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

    <!-- Ringkasan 4 Kartu -->
    <div class="row g-3 mb-4">
        <?php
        $summaryCards = [
            ['label' => 'Saldo Awal',   'val' => $report['saldo_awal'],   'icon' => 'bi-wallet2',          'color' => 'var(--color-dark)'],
            ['label' => 'Total Masuk',  'val' => $report['total_masuk'],  'icon' => 'bi-arrow-down-circle', 'color' => '#2E7D32'],
            ['label' => 'Total Keluar', 'val' => $report['total_keluar'], 'icon' => 'bi-arrow-up-circle',   'color' => '#C62828'],
            ['label' => 'Saldo Akhir',  'val' => $report['saldo_akhir'],  'icon' => 'bi-bank2',             'color' => '#1565C0'],
        ];
        foreach ($summaryCards as $c): ?>
        <div class="col-6 col-md-3">
            <div class="card card-custom p-3 text-center h-100">
                <i class="bi <?= $c['icon'] ?> fs-2 mb-1" style="color: <?= $c['color'] ?>;"></i>
                <div class="fw-bold font-heading" style="color: var(--color-heading); font-size: 0.95rem;">
                    <?= format_rupiah($c['val']) ?>
                </div>
                <div class="font-body" style="color: var(--color-muted); font-size: 0.78rem;"><?= $c['label'] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Tabel Mutasi -->
    <div class="card card-custom p-4">
        <h5 class="fw-bold font-heading mb-3" style="color: var(--color-heading);">
            Rincian Pergerakan Kas & Bank
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
                            Tidak ada transaksi kas/bank pada periode ini.
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
                            <?= $m['posisi'] === 'Debet' ? format_rupiah($m['nominal']) : '<span style="color:var(--color-muted)">-</span>' ?>
                        </td>
                        <td class="text-end fw-bold" style="color: #C62828;">
                            <?= $m['posisi'] === 'Kredit' ? format_rupiah($m['nominal']) : '<span style="color:var(--color-muted)">-</span>' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr style="background-color: var(--color-cream); border-top: 2px solid var(--color-sand);">
                        <td colspan="4" class="fw-bold font-heading text-end py-2" style="color: var(--color-heading);">
                            TOTAL PERIODE:
                        </td>
                        <td class="text-end fw-bold py-2" style="color: #2E7D32;"><?= format_rupiah($report['total_masuk']) ?></td>
                        <td class="text-end fw-bold py-2" style="color: #C62828;"><?= format_rupiah($report['total_keluar']) ?></td>
                    </tr>
                    <tr style="background-color: #EEF2F7;">
                        <td colspan="5" class="fw-bold font-heading text-end py-2" style="color: var(--color-heading);">
                            SALDO AKHIR (<?= date('d/m/Y', strtotime($report['endDate'])) ?>):
                        </td>
                        <td class="text-end fw-bold font-heading py-2 fs-6" style="color: #1565C0;">
                            <?= format_rupiah($report['saldo_akhir']) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

