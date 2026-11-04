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
                <i class="bi bi-bank me-2" style="color: #6A1B9A;"></i>Neraca Keuangan (Balance Sheet)
            </h2>
            <small class="font-body" style="color: var(--color-muted);">
                Per tanggal: <strong><?= date('d F Y', strtotime($report['endDate'])) ?></strong>
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
            <div class="col-md-5">
                <label class="form-label fw-bold font-heading small" style="color: var(--color-heading);">
                    Tampilkan Neraca Per Tanggal
                </label>
                <input type="date" name="end" class="form-control" value="<?= e($report['endDate']) ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-brand-primary w-100">
                    <i class="bi bi-search me-1"></i>Tampilkan
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
            <strong class="font-heading">Neraca Seimbang!</strong>
            <div class="font-body small">Total Aktiva (Harta) = Total Pasiva (Kewajiban + Modal).</div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert rounded-4 mb-4 d-flex align-items-center gap-3"
         style="background-color: #FFEBEE; border: 2px solid #F44336; color: #B71C1C;">
        <i class="bi bi-exclamation-triangle-fill fs-3"></i>
        <div>
            <strong class="font-heading">Neraca Tidak Seimbang!</strong>
            <div class="font-body small">
                Selisih: <?= format_rupiah(abs($report['totalHarta'] - $report['totalPassiva'])) ?>.
                Periksa entri jurnal atau akun Modal.
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row g-4">

        <!-- AKTIVA (HARTA) -->
        <div class="col-md-6">
            <div class="card card-custom p-4 h-100">
                <h5 class="fw-bold font-heading mb-3 pb-2 border-bottom"
                    style="color: #1565C0; border-color: var(--color-sand) !important;">
                    <i class="bi bi-safe2 me-2"></i>AKTIVA (HARTA)
                </h5>
                <table class="table table-sm font-body small mb-0">
                    <tbody>
                        <?php foreach ($report['harta'] as $h): ?>
                        <tr>
                            <td class="py-2 ps-0" style="color: var(--color-body-text);">
                                <?= e($h['kode_akun']) ?> - <?= e($h['nama_akun']) ?>
                            </td>
                            <td class="py-2 pe-0 text-end fw-semibold" style="color: #1565C0;">
                                <?= format_rupiah($h['saldo']) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if (empty($report['harta'])): ?>
                        <tr>
                            <td colspan="2" class="text-center py-3" style="color: var(--color-muted);">
                                Belum ada akun harta tercatat.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <tfoot>
                        <tr style="background-color: #EBF5FB; border-top: 2px solid #BBDEFB;">
                            <td class="py-2 ps-0 fw-bold font-heading" style="color: #0D47A1;">TOTAL AKTIVA</td>
                            <td class="py-2 pe-0 text-end fw-bold font-heading" style="color: #0D47A1; font-size: 1.05rem;">
                                <?= format_rupiah($report['totalHarta']) ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- PASIVA (KEWAJIBAN + MODAL) -->
        <div class="col-md-6">
            <div class="card card-custom p-4 h-100">
                <h5 class="fw-bold font-heading mb-3 pb-2 border-bottom"
                    style="color: #6A1B9A; border-color: var(--color-sand) !important;">
                    <i class="bi bi-people-fill me-2"></i>PASIVA (KEWAJIBAN &amp; MODAL)
                </h5>

                <!-- KEWAJIBAN -->
                <?php if (!empty($report['kewajiban'])): ?>
                <div class="mb-3">
                    <div class="fw-bold font-heading small mb-2" style="color: #C62828; text-transform: uppercase; letter-spacing: 0.05em;">
                        Kewajiban
                    </div>
                    <table class="table table-sm font-body small mb-0">
                        <tbody>
                            <?php foreach ($report['kewajiban'] as $k): ?>
                            <tr>
                                <td class="py-2 ps-0" style="color: var(--color-body-text);">
                                    <?= e($k['kode_akun']) ?> - <?= e($k['nama_akun']) ?>
                                </td>
                                <td class="py-2 pe-0 text-end fw-semibold" style="color: #C62828;">
                                    <?= format_rupiah($k['saldo']) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr style="border-top: 1px solid var(--color-sand);">
                                <td class="py-1 ps-0 fw-semibold font-heading" style="color: var(--color-heading);">Subtotal Kewajiban</td>
                                <td class="py-1 pe-0 text-end fw-semibold" style="color: #C62828;">
                                    <?= format_rupiah($report['totalKewajiban']) ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php endif; ?>

                <!-- MODAL -->
                <div class="mb-0">
                    <div class="fw-bold font-heading small mb-2" style="color: #2E7D32; text-transform: uppercase; letter-spacing: 0.05em;">
                        Modal
                    </div>
                    <table class="table table-sm font-body small mb-0">
                        <tbody>
                            <?php foreach ($report['modal'] as $m): ?>
                            <tr>
                                <td class="py-2 ps-0" style="color: var(--color-body-text);">
                                    <?= e($m['kode_akun']) ?> - <?= e($m['nama_akun']) ?>
                                </td>
                                <td class="py-2 pe-0 text-end fw-semibold" style="color: #2E7D32;">
                                    <?= format_rupiah($m['saldo']) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <!-- Laba Berjalan -->
                            <tr>
                                <td class="py-2 ps-0" style="color: var(--color-body-text);">
                                    Laba / (Rugi) Berjalan Tahun <?= date('Y', strtotime($report['endDate'])) ?>
                                </td>
                                <td class="py-2 pe-0 text-end fw-semibold"
                                    style="color: <?= $report['labaBerjalan'] >= 0 ? '#2E7D32' : '#C62828' ?>;">
                                    <?= ($report['labaBerjalan'] < 0 ? '(' : '') . format_rupiah(abs($report['labaBerjalan'])) . ($report['labaBerjalan'] < 0 ? ')' : '') ?>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr style="border-top: 1px solid var(--color-sand);">
                                <td class="py-1 ps-0 fw-semibold font-heading" style="color: var(--color-heading);">Subtotal Modal</td>
                                <td class="py-1 pe-0 text-end fw-semibold" style="color: #2E7D32;">
                                    <?= format_rupiah($report['totalModal']) ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- TOTAL PASIVA -->
                <div class="mt-3 pt-2 border-top" style="border-color: var(--color-sand) !important;">
                    <div class="d-flex justify-content-between align-items-center"
                         style="background-color: #F3E5F5; border-radius: 8px; padding: 10px 14px;">
                        <span class="fw-bold font-heading" style="color: #4A148C;">TOTAL PASIVA</span>
                        <span class="fw-bold font-heading" style="color: #6A1B9A; font-size: 1.05rem;">
                            <?= format_rupiah($report['totalPassiva']) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

