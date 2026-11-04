<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4 my-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge badge-mist mb-1">
                <i class="bi bi-book-half me-1"></i> Double-Entry Bookkeeping
            </span>
            <h2 class="display-6 font-heading mb-0" style="color: var(--color-heading);">Buku Jurnal Umum</h2>
            <p class="font-body small mb-0" style="color: var(--color-body-text);">Catatan seluruh transaksi masuk (pendapatan pesanan) dan keluar (beban operasional/gaji).</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-brand-primary btn-sm shadow" data-bs-toggle="modal" data-bs-target="#modalInputPengeluaran">
                <i class="bi bi-dash-circle-fill me-1"></i>Input Transaksi Pengeluaran
            </button>
            <a href="<?= BASE_URL ?>/admin/labaRugi" class="btn btn-brand-outline btn-sm">
                <i class="bi bi-graph-up me-1"></i>Laba/Rugi
            </a>
        </div>
    </div>

    <!-- Filter Rentang Tanggal -->
    <div class="card card-custom p-3 mb-4">
        <form action="<?= BASE_URL ?>/admin/jurnal" method="GET" class="row g-2 align-items-center font-body">
            <div class="col-auto small fw-bold" style="color: var(--color-heading);">Filter Tanggal:</div>
            <div class="col-auto">
                <input type="date" name="start" class="form-control form-control-sm" value="<?= e($startDate) ?>">
            </div>
            <div class="col-auto small" style="color: var(--color-muted);">s/d</div>
            <div class="col-auto">
                <input type="date" name="end" class="form-control form-control-sm" value="<?= e($endDate) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-brand-primary">Terapkan Filter</button>
            </div>
        </form>
    </div>

    <!-- Tabel Jurnal Umum Berpasangan (Debet / Kredit) -->
    <div class="card card-custom p-4">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0 font-body" style="border-color: var(--color-sand) !important;">
                <thead class="small" style="background-color: var(--color-cream); color: var(--color-heading);">
                    <tr>
                        <th style="width: 110px;" class="py-3">Tanggal</th>
                        <th class="py-3">Keterangan / Deskripsi Transaksi</th>
                        <th style="width: 90px;" class="py-3">Ref</th>
                        <th class="py-3">Akun (COA)</th>
                        <th class="text-end py-3" style="width: 150px;">Debet (Rp)</th>
                        <th class="text-end py-3" style="width: 150px;">Kredit (Rp)</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php if (empty($jurnals)): ?>
                        <tr><td colspan="6" class="text-center py-5 font-body" style="color: var(--color-muted);">Belum ada transaksi jurnal pada rentang tanggal ini.</td></tr>
                    <?php else: ?>
                        <?php foreach ($jurnals as $j): ?>
                            <!-- Baris Header Transaksi -->
                            <tr style="background-color: var(--color-cream);">
                                <td class="fw-bold font-heading" style="color: var(--color-heading);"><?= date('d/m/Y', strtotime($j['tanggal_transaksi'])) ?></td>
                                <td colspan="5" class="fw-bold font-heading" style="color: var(--color-heading);">
                                    <?= e($j['deskripsi']) ?>
                                    <span class="badge badge-palette-slate ms-2" style="font-size: 0.7rem;">Oleh: <?= e($j['pembuat'] ?? 'Sistem') ?></span>
                                </td>
                            </tr>

                            <!-- Baris Detail Akun Debet & Kredit -->
                            <?php foreach ($j['details'] as $d): ?>
                                <tr>
                                    <td></td>
                                    <td class="<?= $d['posisi'] === 'Kredit' ? 'ps-4' : 'fw-semibold' ?>" style="color: <?= $d['posisi'] === 'Kredit' ? 'var(--color-muted)' : 'var(--color-heading)' ?>;">
                                        <?= $d['posisi'] === 'Kredit' ? '&nbsp;&nbsp;&nbsp;&nbsp;' : '' ?>
                                        <?= e($d['nama_akun']) ?> (<?= e($d['kode_akun']) ?>)
                                    </td>
                                    <td><span class="badge badge-palette-cream"><?= e($j['referensi'] ?? '-') ?></span></td>
                                    <td><small class="badge rounded-pill" style="background-color: var(--color-sand); color: var(--color-dark);"><?= e($d['kategori']) ?></small></td>
                                    <td class="text-end fw-semibold font-heading" style="color: var(--color-dark);">
                                        <?= $d['posisi'] === 'Debet' ? number_format((float)$d['nominal'], 0, ',', '.') : '-' ?>
                                    </td>
                                    <td class="text-end fw-semibold font-heading" style="color: #2E7D32;">
                                        <?= $d['posisi'] === 'Kredit' ? number_format((float)$d['nominal'], 0, ',', '.') : '-' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Input Pengeluaran Kas Manual (Debet: Beban, Kredit: Kas) -->
<div class="modal fade" id="modalInputPengeluaran" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="<?= BASE_URL ?>/admin/jurnalStore" method="POST">
                <?= csrf_field() ?>
                <div class="modal-header border-bottom" style="background-color: var(--color-cream); border-color: var(--color-sand) !important;">
                    <h5 class="modal-title font-heading fw-bold" style="color: var(--color-heading);"><i class="bi bi-dash-circle-fill text-danger me-2"></i>Input Pengeluaran Kas (Beban)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body small font-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Tanggal Transaksi *</label>
                        <input type="date" name="tanggal_transaksi" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Keterangan Pengeluaran *</label>
                        <input type="text" name="deskripsi" class="form-control" placeholder="Contoh: Pembayaran Gaji Cleaner Budi Santoso" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Nomor Referensi (Opsional)</label>
                        <input type="text" name="referensi" class="form-control" placeholder="Contoh: BKK-001 / GAJI-OKT">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Akun Beban / Pengeluaran (Posisi Debet) *</label>
                        <select name="kode_beban" class="form-select" required>
                            <option value="">-- Pilih Akun Beban --</option>
                            <?php foreach ($bebanCoa as $b): ?>
                                <option value="<?= $b['kode_akun'] ?>"><?= e($b['kode_akun']) ?> - <?= e($b['nama_akun']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="font-body" style="font-size: 0.72rem; color: var(--color-muted);">Beban bertambah di sisi Debet.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Sumber Kas Pembayar (Posisi Kredit) *</label>
                        <select name="kode_kas" class="form-select" required>
                            <?php foreach ($kasCoa as $k): ?>
                                <option value="<?= $k['kode_akun'] ?>"><?= e($k['kode_akun']) ?> - <?= e($k['nama_akun']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="font-body" style="font-size: 0.72rem; color: var(--color-muted);">Kas berkurang di sisi Kredit.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Nominal Pengeluaran (Rp) *</label>
                        <input type="number" name="nominal" class="form-control form-control-lg fw-bold font-heading text-danger" placeholder="500000" min="1" required>
                    </div>
                </div>
                <div class="modal-footer border-top" style="border-color: var(--color-sand) !important;">
                    <button type="button" class="btn btn-brand-outline btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brand-primary btn-sm fw-bold">Catat Pengeluaran (Posting)</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
