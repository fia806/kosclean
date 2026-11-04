<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4 my-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="display-6 font-heading mb-0" style="color: var(--color-heading);">Manajemen Paket Layanan</h2>
            <p class="font-body small mb-0" style="color: var(--color-body-text);">Kelola katalog layanan, tarif dasar, estimasi waktu pengerjaan, dan ikon gambar.</p>
        </div>
        <div>
            <button type="button" class="btn btn-brand-primary" data-bs-toggle="modal" data-bs-target="#modalTambahLayanan">
                <i class="bi bi-plus-circle me-1"></i>Tambah Layanan Baru
            </button>
        </div>
    </div>

    <!-- Tabel Layanan -->
    <div class="card card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 font-body">
                <thead class="small" style="background-color: var(--color-cream); color: var(--color-heading);">
                    <tr>
                        <th class="py-3">ID</th>
                        <th class="py-3">Ikon</th>
                        <th class="py-3">Nama Layanan</th>
                        <th class="py-3">Deskripsi</th>
                        <th class="py-3">Harga Dasar</th>
                        <th class="py-3">Estimasi Waktu</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php foreach ($layananList as $item): ?>
                        <tr>
                            <td><strong class="font-heading" style="color: var(--color-heading);"><?= $item['id_layanan'] ?></strong></td>
                            <td>
                                <?php if (!empty($item['gambar_ikon'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/layanan/<?= e($item['gambar_ikon']) ?>" class="rounded-3 border" style="width: 44px; height: 44px; object-fit: cover;" alt="Ikon">
                                <?php else: ?>
                                    <div class="rounded-3 border d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background-color: var(--color-cream); color: var(--color-dark); border-color: var(--color-sand) !important;">
                                        <i class="bi bi-stars"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><strong class="font-heading" style="color: var(--color-heading);"><?= e($item['nama_layanan']) ?></strong></td>
                            <td style="max-width: 250px;">
                                <span class="d-inline-block text-truncate" style="max-width: 240px; color: var(--color-body-text);" title="<?= e($item['deskripsi']) ?>">
                                    <?= e($item['deskripsi']) ?>
                                </span>
                            </td>
                            <td class="fw-bold font-heading" style="color: var(--color-dark);"><?= format_rupiah($item['harga_dasar']) ?></td>
                            <td style="color: var(--color-body-text);"><?= e($item['estimasi_waktu']) ?></td>
                            <td>
                                <span class="badge px-3 py-1 rounded-pill font-heading" style="<?= $item['status_layanan'] === 'aktif' ? 'background-color: #2E7D32; color: #FFFFFF;' : 'background-color: var(--color-sand); color: var(--color-dark);' ?>">
                                    <?= ucfirst(e($item['status_layanan'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-brand-outline py-1 px-2" data-bs-toggle="modal" data-bs-target="#modalEdit_<?= $item['id_layanan'] ?>">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <form action="<?= BASE_URL ?>/admin/layananDelete/<?= $item['id_layanan'] ?>" method="POST" onsubmit="return confirm('Hapus layanan ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2 rounded-pill">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Edit Layanan -->
                        <div class="modal fade" id="modalEdit_<?= $item['id_layanan'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content rounded-4 border-0 shadow">
                                    <form action="<?= BASE_URL ?>/admin/layananUpdate/<?= $item['id_layanan'] ?>" method="POST" enctype="multipart/form-data">
                                        <?= csrf_field() ?>
                                        <div class="modal-header border-bottom" style="border-color: var(--color-sand) !important;">
                                            <h5 class="modal-title font-heading fw-bold" style="color: var(--color-heading);">Edit Layanan #<?= $item['id_layanan'] ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body small font-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Nama Layanan *</label>
                                                <input type="text" name="nama_layanan" class="form-control" value="<?= e($item['nama_layanan']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Deskripsi *</label>
                                                <textarea name="deskripsi" class="form-control" rows="3" required><?= e($item['deskripsi']) ?></textarea>
                                            </div>
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Harga Dasar (Rp) *</label>
                                                    <input type="number" name="harga_dasar" class="form-control" value="<?= (int)$item['harga_dasar'] ?>" required>
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Estimasi Waktu *</label>
                                                    <input type="text" name="estimasi_waktu" class="form-control" value="<?= e($item['estimasi_waktu']) ?>" placeholder="Misal: 1 - 1.5 Jam" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Ganti Ikon Gambar (Opsional)</label>
                                                <input type="file" name="gambar_ikon" class="form-control" accept=".jpg,.jpeg,.png">
                                                <div class="font-body" style="font-size: 0.72rem; color: var(--color-muted);">Maks 2MB, format JPG, PNG.</div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Status Layanan *</label>
                                                <select name="status_layanan" class="form-select">
                                                    <option value="aktif" <?= $item['status_layanan'] === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                                    <option value="nonaktif" <?= $item['status_layanan'] === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-top" style="border-color: var(--color-sand) !important;">
                                            <button type="button" class="btn btn-brand-outline btn-sm" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-brand-primary btn-sm fw-bold">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Layanan Baru -->
<div class="modal fade" id="modalTambahLayanan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="<?= BASE_URL ?>/admin/layananStore" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header border-bottom" style="border-color: var(--color-sand) !important;">
                    <h5 class="modal-title font-heading fw-bold" style="color: var(--color-heading);">Tambah Layanan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body small font-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Nama Layanan *</label>
                        <input type="text" name="nama_layanan" class="form-control" placeholder="Contoh: Pembersihan Kasur Tungau" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Deskripsi Rinci *</label>
                        <textarea name="deskripsi" class="form-control" rows="3" placeholder="Rincian bagian yang dibersihkan..." required></textarea>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Harga Dasar (Rp) *</label>
                            <input type="number" name="harga_dasar" class="form-control" placeholder="50000" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Estimasi Waktu *</label>
                            <input type="text" name="estimasi_waktu" class="form-control" placeholder="Contoh: 1.5 Jam" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Ikon Gambar (Opsional)</label>
                        <input type="file" name="gambar_ikon" class="form-control" accept=".jpg,.jpeg,.png">
                        <div class="font-body" style="font-size: 0.72rem; color: var(--color-muted);">Maks 2MB, format JPG, PNG.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold font-heading" style="color: var(--color-heading);">Status Layanan *</label>
                        <select name="status_layanan" class="form-select">
                            <option value="aktif" selected>Aktif (Tampil di Katalog)</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-top" style="border-color: var(--color-sand) !important;">
                    <button type="button" class="btn btn-brand-outline btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-brand-primary btn-sm fw-bold">Simpan Layanan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
