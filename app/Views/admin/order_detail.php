<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4 my-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/admin/orders" class="text-decoration-none" style="color: var(--color-dark);">Pesanan</a></li>
                    <li class="breadcrumb-item active" style="color: var(--color-heading);"><?= e($pesanan['id_pesanan']) ?></li>
                </ol>
            </nav>
            <h2 class="display-6 font-heading mb-0" style="color: var(--color-heading);">Detail Pesanan & Validasi QC</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-brand-outline btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Kolom Kiri: Informasi Pesanan & Pelanggan -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 h-100">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2" style="color: var(--color-heading); border-color: var(--color-sand) !important;">
                    <i class="bi bi-info-circle-fill me-2" style="color: var(--color-dark);"></i>Rincian Pemesanan
                </h5>

                <div class="row g-3 small font-body">
                    <div class="col-4" style="color: var(--color-muted);">ID Pesanan:</div>
                    <div class="col-8 fw-bold font-heading" style="color: var(--color-heading);"><?= e($pesanan['id_pesanan']) ?></div>

                    <div class="col-4" style="color: var(--color-muted);">Nama Pelanggan:</div>
                    <div class="col-8 fw-bold font-heading" style="color: var(--color-heading);"><?= e($pesanan['nama_pelanggan']) ?></div>

                    <div class="col-4" style="color: var(--color-muted);">Nomor WhatsApp:</div>
                    <div class="col-8">
                        <a href="https://wa.me/<?= preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $pesanan['telp_pelanggan'])) ?>" target="_blank" class="text-success text-decoration-none fw-bold">
                            <i class="bi bi-whatsapp"></i> <?= e($pesanan['telp_pelanggan']) ?>
                        </a>
                    </div>

                    <div class="col-4" style="color: var(--color-muted);">Layanan:</div>
                    <div class="col-8 fw-semibold" style="color: var(--color-heading);"><?= e($pesanan['nama_layanan']) ?></div>

                    <div class="col-4" style="color: var(--color-muted);">Jadwal:</div>
                    <div class="col-8" style="color: var(--color-body-text);"><?= date('d F Y', strtotime($pesanan['tanggal_jadwal'])) ?> (<?= substr($pesanan['waktu_jadwal'], 0, 5) ?> WIB)</div>

                    <div class="col-4" style="color: var(--color-muted);">Alamat Pembersihan:</div>
                    <div class="col-8" style="color: var(--color-body-text);"><?= e($pesanan['alamat_pembersihan']) ?></div>

                    <div class="col-4" style="color: var(--color-muted);">Patokan Lokasi:</div>
                    <div class="col-8" style="color: var(--color-body-text);"><?= e($pesanan['patokan_lokasi']) ?></div>

                    <div class="col-4" style="color: var(--color-muted);">Total Tarif:</div>
                    <div class="col-8 fw-extrabold font-heading fs-5" style="color: var(--color-dark);"><?= format_rupiah($pesanan['total_harga']) ?></div>

                    <div class="col-4" style="color: var(--color-muted);">Metode Pembayaran:</div>
                    <div class="col-8">
                        <span class="badge badge-palette-cream"><?= strtoupper(e($pesanan['metode_pembayaran'])) ?></span>
                        <span class="badge rounded-pill ms-1" style="background-color: var(--color-sand); color: var(--color-dark);"><?= ucfirst(str_replace('_', ' ', e($pesanan['status_pembayaran']))) ?></span>
                    </div>

                    <div class="col-4" style="color: var(--color-muted);">Status Pesanan:</div>
                    <div class="col-8">
                        <span class="badge badge-palette-slate px-3 py-1"><?= ucfirst(str_replace('_', ' ', e($pesanan['status_pesanan']))) ?></span>
                    </div>
                </div>

                <!-- Penugasan Cleaner -->
                <div class="mt-4 pt-3 border-top" style="border-color: var(--color-sand) !important;">
                    <h6 class="fw-bold font-heading mb-2" style="color: var(--color-heading);"><i class="bi bi-person-badge-fill me-1" style="color: var(--color-dark);"></i>Penugasan Cleaner Lapangan</h6>
                    
                    <?php if ($tugas): ?>
                        <div class="p-3 rounded-4 d-flex justify-content-between align-items-center mb-3" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                            <div>
                                <strong class="d-block font-heading" style="color: var(--color-heading);"><?= e($tugas['nama_cleaner']) ?></strong>
                                <small class="font-body" style="color: var(--color-muted);"><i class="bi bi-telephone"></i> <?= e($tugas['telp_cleaner']) ?></small>
                            </div>
                            <span class="badge badge-palette-slate"><?= ucfirst(str_replace('_', ' ', e($tugas['status_tugas']))) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($pesanan['status_pesanan'] !== 'selesai'): ?>
                        <form action="<?= BASE_URL ?>/admin/assignCleaner" method="POST" class="d-flex gap-2">
                            <?= csrf_field() ?>
                            <input type="hidden" name="id_pesanan" value="<?= e($pesanan['id_pesanan']) ?>">
                            <select name="id_karyawan" class="form-select form-select-sm" required>
                                <option value="">-- <?= $tugas ? 'Ganti Cleaner' : 'Pilih Cleaner Lapangan' ?> --</option>
                                <?php foreach ($cleaners as $c): ?>
                                    <option value="<?= $c['id_user'] ?>" <?= ($tugas && $tugas['id_karyawan'] == $c['id_user']) ? 'selected' : '' ?>>
                                        <?= e($c['nama_lengkap']) ?> (<?= e($c['no_telp']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-sm btn-brand-primary text-nowrap">
                                <i class="bi bi-check2"></i> Tugaskan
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Foto QC & Eksekusi "Selesaikan Pesanan" -->
        <div class="col-lg-6">
            <div class="card card-custom p-4 h-100 d-flex flex-column">
                <h5 class="fw-bold font-heading mb-3 border-bottom pb-2" style="color: var(--color-heading); border-color: var(--color-sand) !important;">
                    <i class="bi bi-camera-fill me-2" style="color: #D97706;"></i>Laporan Foto QC (Quality Control)
                </h5>

                <?php if (!$tugas || (empty($tugas['foto_sebelum']) && empty($tugas['foto_sesudah']))): ?>
                    <div class="p-4 rounded-4 text-center my-auto" style="background-color: var(--color-cream); border: 1.5px dashed var(--color-sand);">
                        <i class="bi bi-images fs-1 d-block mb-2" style="color: var(--color-dark);"></i>
                        <h6 class="font-heading fw-bold" style="color: var(--color-heading);">Belum Ada Dokumentasi Foto</h6>
                        <p class="font-body small mb-0" style="color: var(--color-body-text);">Cleaner belum mengunggah foto SEBELUM atau SESUDAH pengerjaan.</p>
                    </div>
                <?php else: ?>
                    <div class="row g-3 mb-4">
                        <!-- Foto SEBELUM -->
                        <div class="col-sm-6">
                            <div class="p-2 border rounded-4 text-center" style="background-color: var(--color-cream); border-color: var(--color-sand) !important;">
                                <strong class="d-block small font-heading mb-2" style="color: var(--color-heading);">Foto SEBELUM Pengerjaan</strong>
                                <?php if (!empty($tugas['foto_sebelum'])): ?>
                                    <a href="<?= BASE_URL ?>/uploads/tasks/<?= e($tugas['foto_sebelum']) ?>" target="_blank">
                                        <img src="<?= BASE_URL ?>/uploads/tasks/<?= e($tugas['foto_sebelum']) ?>" class="img-fluid rounded border shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover;" alt="Foto Sebelum">
                                    </a>
                                <?php else: ?>
                                    <div class="py-5 font-body small" style="color: var(--color-muted);">Belum diunggah</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Foto SESUDAH -->
                        <div class="col-sm-6">
                            <div class="p-2 border rounded-4 text-center" style="background-color: var(--color-cream); border-color: var(--color-sand) !important;">
                                <strong class="d-block small font-heading mb-2" style="color: var(--color-heading);">Foto SESUDAH (Hasil Bersih)</strong>
                                <?php if (!empty($tugas['foto_sesudah'])): ?>
                                    <a href="<?= BASE_URL ?>/uploads/tasks/<?= e($tugas['foto_sesudah']) ?>" target="_blank">
                                        <img src="<?= BASE_URL ?>/uploads/tasks/<?= e($tugas['foto_sesudah']) ?>" class="img-fluid rounded border shadow-sm" style="max-height: 200px; width: 100%; object-fit: cover;" alt="Foto Sesudah">
                                    </a>
                                <?php else: ?>
                                    <div class="py-5 font-body small" style="color: var(--color-muted);">Belum diunggah</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- ACTION: SELESAIKAN PESANAN (TRIGGER JURNAL AKUNTANSI OTOMATIS) -->
                <div class="mt-auto pt-3 border-top" style="border-color: var(--color-sand) !important;">
                    <?php if ($pesanan['status_pesanan'] === 'selesai'): ?>
                        <div class="alert alert-success d-flex align-items-center gap-2 mb-0 rounded-4">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                            <div>
                                <strong class="font-heading">Pesanan Telah Selesai & Lunas!</strong>
                                <div class="small font-body">Pencatatan pendapatan telah dibukukan secara otomatis ke Jurnal Umum (Debet: Kas, Kredit: Pendapatan).</div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="p-3 rounded-4 mb-3" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                            <h6 class="fw-bold font-heading mb-1" style="color: var(--color-heading);"><i class="bi bi-journal-check me-1" style="color: var(--color-dark);"></i>Verifikasi QC & Pembukuan Otomatis:</h6>
                            <p class="small font-body mb-0" style="color: var(--color-body-text);">
                                Mengklik tombol di bawah akan mengubah status menjadi <strong>'selesai'</strong> dan <strong>'lunas'</strong>, serta secara otomatis memicu penjurnalan akuntansi ganda ke kas & pendapatan.
                            </p>
                        </div>

                        <form action="<?= BASE_URL ?>/admin/completeOrder/<?= e($pesanan['id_pesanan']) ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin foto hasil pengerjaan sudah valid dan ingin menyelesaikan pesanan ini?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-success btn-lg w-100 py-3 fw-bold font-heading shadow rounded-pill">
                                <i class="bi bi-check2-circle me-1"></i> Selesaikan Pesanan & Catat ke Jurnal
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
