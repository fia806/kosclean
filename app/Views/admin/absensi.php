<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4 my-3">
    <!-- Header Page -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge badge-mist mb-1">
                <i class="bi bi-shield-check me-1"></i> Admin Monitoring
            </span>
            <h2 class="display-6 font-heading mb-0" style="color: var(--color-heading);">Rekapitulasi Presensi Cleaner</h2>
            <p class="font-body small mb-0" style="color: var(--color-body-text);">
                Pantau jam kedatangan, jam kepulangan, serta bukti foto selfie kehadiran cleaner di kantor/basecamp.
            </p>
        </div>

        <!-- Filter Tanggal -->
        <div>
            <form action="<?= BASE_URL ?>/admin/absensi" method="GET" class="d-flex align-items-center gap-2">
                <label for="tanggal" class="small fw-bold font-heading mb-0 text-nowrap" style="color: var(--color-heading);">
                    <i class="bi bi-calendar3 me-1"></i>Pilih Tanggal:
                </label>
                <input type="date" name="tanggal" id="tanggal" class="form-control form-control-sm rounded-pill font-body" value="<?= e($selectedDate) ?>" onchange="this.form.submit()">
                <button type="submit" class="btn btn-brand-primary btn-sm rounded-pill px-3 font-heading">
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Ringkasan Statistik Harian -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-custom p-3 d-flex flex-row align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 56px; height: 56px; border: 1.5px solid var(--color-sand);">
                    <i class="bi bi-people-fill fs-3"></i>
                </div>
                <div>
                    <span class="font-body small d-block" style="color: var(--color-muted);">Total Cleaner Hadir</span>
                    <h3 class="font-heading mb-0 fw-bold" style="color: var(--color-heading);"><?= (int)($summary['total_hadir'] ?? 0) ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom p-3 d-flex flex-row align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: #E8F5E9; color: #2E7D32; width: 56px; height: 56px; border: 1.5px solid #C8E6C9;">
                    <i class="bi bi-person-walking fs-3"></i>
                </div>
                <div>
                    <span class="font-body small d-block" style="color: var(--color-muted);">Aktif / Bekerja (Shift)</span>
                    <h3 class="font-heading mb-0 fw-bold" style="color: #2E7D32;"><?= (int)($summary['total_aktif'] ?? 0) ?></h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom p-3 d-flex flex-row align-items-center gap-3">
                <div class="rounded-circle p-3 d-flex align-items-center justify-content-center" style="background-color: #EDE7F6; color: #484B6A; width: 56px; height: 56px; border: 1.5px solid var(--color-sand);">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
                <div>
                    <span class="font-body small d-block" style="color: var(--color-muted);">Selesai Shift (Pulang)</span>
                    <h3 class="font-heading mb-0 fw-bold" style="color: var(--color-dark);"><?= (int)($summary['total_selesai'] ?? 0) ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Rekapitulasi Presensi -->
    <div class="card card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="font-heading mb-0 fw-bold" style="color: var(--color-heading);">
                Daftar Presensi Cleaner Tanggal: <?= date('d M Y', strtotime($selectedDate)) ?>
            </h5>
            <span class="badge badge-palette-cream font-heading"><?= count($attendanceList) ?> Cleaner Tercatat</span>
        </div>

        <?php if (empty($attendanceList)): ?>
            <div class="text-center py-5">
                <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 68px; height: 68px; border: 2px solid var(--color-sand);">
                    <i class="bi bi-calendar-x fs-2"></i>
                </div>
                <h5 class="font-heading" style="color: var(--color-heading);">Tidak Ada Catatan Presensi</h5>
                <p class="font-body small mb-0" style="color: var(--color-body-text);">Belum ada cleaner yang melakukan presensi masuk pada tanggal terpilih.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 font-body">
                    <thead class="small" style="background-color: var(--color-cream); color: var(--color-heading);">
                        <tr>
                            <th class="py-3">No</th>
                            <th class="py-3">Nama Cleaner</th>
                            <th class="py-3">Status Shift</th>
                            <th class="py-3">Presensi Masuk (Check-In)</th>
                            <th class="py-3">Foto Masuk (Kantor)</th>
                            <th class="py-3">Presensi Keluar (Check-Out)</th>
                            <th class="py-3">Foto Keluar (Kantor)</th>
                        </tr>
                    </thead>
                    <tbody class="small">
                        <?php $no = 1; foreach ($attendanceList as $att): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td>
                                    <strong class="d-block font-heading" style="color: var(--color-heading);"><?= e($att['nama_karyawan']) ?></strong>
                                    <small class="font-body" style="color: var(--color-muted);"><i class="bi bi-telephone me-1"></i><?= e($att['telp_karyawan'] ?? '-') ?></small>
                                </td>
                                <td>
                                    <?php if ($att['status_presensi'] === 'aktif_bekerja'): ?>
                                        <span class="badge px-3 py-2 rounded-pill font-heading" style="background-color: #2E7D32; color: #FFFFFF;">
                                            <i class="bi bi-lightning-charge-fill me-1"></i>Aktif / Bekerja
                                        </span>
                                    <?php else: ?>
                                        <span class="badge px-3 py-2 rounded-pill font-heading" style="background-color: var(--color-slate-blue); color: #FFFFFF;">
                                            <i class="bi bi-check2-all me-1"></i>Selesai Shift
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong class="font-heading" style="color: var(--color-heading);"><i class="bi bi-clock me-1 text-primary"></i><?= e($att['jam_masuk']) ?> WIB</strong>
                                </td>
                                <td>
                                    <?php if (!empty($att['foto_masuk'])): ?>
                                        <a href="<?= BASE_URL ?>/uploads/absensi/<?= e($att['foto_masuk']) ?>" target="_blank" class="d-inline-block position-relative rounded-3 overflow-hidden border shadow-sm" style="width: 52px; height: 52px; border-color: var(--color-sand) !important;" title="Klik untuk perbesar">
                                            <img src="<?= BASE_URL ?>/uploads/absensi/<?= e($att['foto_masuk']) ?>" class="w-100 h-100" style="object-fit: cover;" alt="Foto Masuk">
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Tanpa Foto</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($att['jam_keluar'])): ?>
                                        <strong class="font-heading" style="color: var(--color-heading);"><i class="bi bi-clock-history me-1 text-danger"></i><?= e($att['jam_keluar']) ?> WIB</strong>
                                    <?php else: ?>
                                        <span class="badge rounded-pill" style="background-color: var(--color-cream); color: var(--color-dark); border: 1px solid var(--color-sand);">
                                            Sedang Bertugas
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($att['foto_keluar'])): ?>
                                        <a href="<?= BASE_URL ?>/uploads/absensi/<?= e($att['foto_keluar']) ?>" target="_blank" class="d-inline-block position-relative rounded-3 overflow-hidden border shadow-sm" style="width: 52px; height: 52px; border-color: var(--color-sand) !important;" title="Klik untuk perbesar">
                                            <img src="<?= BASE_URL ?>/uploads/absensi/<?= e($att['foto_keluar']) ?>" class="w-100 h-100" style="object-fit: cover;" alt="Foto Keluar">
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
