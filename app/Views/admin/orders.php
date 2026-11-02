<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4 my-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h2 class="display-6 font-heading mb-0" style="color: var(--color-heading);">Manajemen Pesanan Masuk</h2>
            <p class="font-body small mb-0" style="color: var(--color-body-text);">Kelola status pemesanan, penugasan cleaner lapangan, dan verifikasi pengerjaan.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>/admin" class="btn btn-brand-outline btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
            </a>
        </div>
    </div>

    <!-- Filter Tab Status -->
    <div class="card card-custom p-3 mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-sm rounded-pill font-heading <?= empty($statusFilter) ? 'btn-brand-primary' : 'btn-outline-dark' ?>" style="<?= empty($statusFilter) ? '' : 'border-color: var(--color-sand);' ?>">Semua</a>
            <a href="<?= BASE_URL ?>/admin/orders?status=menunggu_konfirmasi" class="btn btn-sm rounded-pill font-heading <?= $statusFilter === 'menunggu_konfirmasi' ? 'btn-warning text-dark fw-bold' : 'btn-outline-dark' ?>" style="<?= $statusFilter === 'menunggu_konfirmasi' ? '' : 'border-color: var(--color-sand);' ?>">Menunggu Konfirmasi</a>
            <a href="<?= BASE_URL ?>/admin/orders?status=menunggu_jadwal" class="btn btn-sm rounded-pill font-heading <?= $statusFilter === 'menunggu_jadwal' ? 'btn-brand-primary' : 'btn-outline-dark' ?>" style="<?= $statusFilter === 'menunggu_jadwal' ? '' : 'border-color: var(--color-sand);' ?>">Menunggu Jadwal</a>
            <a href="<?= BASE_URL ?>/admin/orders?status=sedang_dikerjakan" class="btn btn-sm rounded-pill font-heading <?= $statusFilter === 'sedang_dikerjakan' ? 'btn-brand-primary' : 'btn-outline-dark' ?>" style="<?= $statusFilter === 'sedang_dikerjakan' ? '' : 'border-color: var(--color-sand);' ?>">Sedang Dikerjakan</a>
            <a href="<?= BASE_URL ?>/admin/orders?status=selesai" class="btn btn-sm rounded-pill font-heading <?= $statusFilter === 'selesai' ? 'btn-success text-white' : 'btn-outline-dark' ?>" style="<?= $statusFilter === 'selesai' ? '' : 'border-color: var(--color-sand);' ?>">Selesai</a>
        </div>
    </div>

    <!-- Tabel Daftar Pesanan -->
    <div class="card card-custom p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 font-body">
                <thead class="small" style="background-color: var(--color-cream); color: var(--color-heading);">
                    <tr>
                        <th class="py-3">ID Pesanan</th>
                        <th class="py-3">Pelanggan</th>
                        <th class="py-3">Layanan</th>
                        <th class="py-3">Jadwal & Jam</th>
                        <th class="py-3">Alamat Kos</th>
                        <th class="py-3">Total Biaya</th>
                        <th class="py-3">Status Order</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php if (empty($orders)): ?>
                        <tr><td colspan="8" class="text-center font-body py-5" style="color: var(--color-muted);">Tidak ada pesanan ditemukan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($orders as $o): ?>
                            <tr>
                                <td>
                                    <span class="badge badge-palette-cream"><?= e($o['id_pesanan']) ?></span>
                                </td>
                                <td>
                                    <strong class="font-heading" style="color: var(--color-heading);"><?= e($o['nama_pelanggan']) ?></strong>
                                    <div style="color: var(--color-muted); font-size: 0.78rem;"><?= e($o['telp_pelanggan']) ?></div>
                                </td>
                                <td style="color: var(--color-body-text);"><?= e($o['nama_layanan']) ?></td>
                                <td>
                                    <div class="fw-semibold" style="color: var(--color-heading);"><?= date('d M Y', strtotime($o['tanggal_jadwal'])) ?></div>
                                    <small style="color: var(--color-muted);"><?= substr($o['waktu_jadwal'], 0, 5) ?> WIB</small>
                                </td>
                                <td style="max-width: 200px;">
                                    <span class="d-inline-block text-truncate" style="max-width: 190px; color: var(--color-body-text);" title="<?= e($o['alamat_pembersihan']) ?>">
                                        <?= e($o['alamat_pembersihan']) ?>
                                    </span>
                                </td>
                                <td class="fw-bold font-heading" style="color: var(--color-dark);"><?= format_rupiah($o['total_harga']) ?></td>
                                <td>
                                    <?php
                                        $badgeColor = 'background-color: var(--color-sand); color: var(--color-dark);';
                                        switch($o['status_pesanan']) {
                                            case 'menunggu_konfirmasi': $badgeColor = 'background-color: var(--color-cream); color: var(--color-dark); border: 1px solid var(--color-sand);'; break;
                                            case 'menunggu_jadwal':     $badgeColor = 'background-color: var(--color-slate-blue); color: #FFFFFF;'; break;
                                            case 'sedang_dikerjakan':   $badgeColor = 'background-color: var(--color-dark); color: #FFFFFF;'; break;
                                            case 'selesai':             $badgeColor = 'background-color: #2E7D32; color: #FFFFFF;'; break;
                                            case 'dibatalkan':          $badgeColor = 'background-color: #C62828; color: #FFFFFF;'; break;
                                        }
                                    ?>
                                    <span class="badge px-3 py-2 rounded-pill font-heading" style="<?= $badgeColor ?>">
                                        <?= ucfirst(str_replace('_', ' ', e($o['status_pesanan']))) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/admin/orderDetail/<?= e($o['id_pesanan']) ?>" class="btn btn-sm btn-brand-primary py-1 px-3">
                                        <i class="bi bi-eye-fill me-1"></i>QC & Kelola
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
