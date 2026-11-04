<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4 my-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge badge-mist mb-1"><i class="bi bi-person-circle me-1"></i> Area Pelanggan</span>
            <h2 class="display-6 font-heading mb-0" style="color: var(--color-heading);">Pesanan Saya</h2>
            <p class="font-body small mb-0" style="color: var(--color-body-text);">Pantau status pengerjaan pembersihan kamar kos dan riwayat transaksi Anda.</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/booking" class="btn btn-brand-primary shadow">
                <i class="bi bi-plus-circle me-1"></i>Pesan Layanan Baru
            </a>
        </div>
    </div>

    <?php if (empty($pesananList)): ?>
        <div class="card card-custom p-5 text-center">
            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 84px; height: 84px; border: 2px solid var(--color-sand);">
                <i class="bi bi-inbox fs-1"></i>
            </div>
            <h4 class="font-heading" style="color: var(--color-heading);">Belum Ada Pesanan</h4>
            <p class="font-body small mb-4" style="color: var(--color-body-text);">Kamar kos lagi kotor atau berantakan? Pesan cleaner sekarang, tinggal terima beres!</p>
            <div>
                <a href="<?= BASE_URL ?>/booking" class="btn btn-brand-primary px-4 py-3 shadow">
                    Pesan di Semarang Sekarang
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($pesananList as $p): ?>
                <div class="col-lg-6">
                    <div class="card card-custom h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-3" style="border-color: var(--color-sand) !important;">
                            <div>
                                <span class="badge badge-palette-cream small mb-1"><?= e($p['id_pesanan']) ?></span>
                                <h5 class="font-heading mb-0" style="color: var(--color-heading);"><?= e($p['nama_layanan']) ?></h5>
                            </div>

                            <!-- Badge Status Pesanan -->
                            <?php
                                $badgeStyle = 'background-color: var(--color-sand); color: var(--color-dark);';
                                switch($p['status_pesanan']) {
                                    case 'menunggu_konfirmasi': $badgeStyle = 'background-color: var(--color-cream); color: var(--color-dark); border: 1px solid var(--color-sand);'; break;
                                    case 'menunggu_jadwal':     $badgeStyle = 'background-color: var(--color-slate-blue); color: #FFFFFF;'; break;
                                    case 'sedang_dikerjakan':   $badgeStyle = 'background-color: var(--color-dark); color: #FFFFFF;'; break;
                                    case 'selesai':             $badgeStyle = 'background-color: #2E7D32; color: #FFFFFF;'; break;
                                    case 'dibatalkan':          $badgeStyle = 'background-color: #C62828; color: #FFFFFF;'; break;
                                }
                            ?>
                            <span class="badge px-3 py-2 rounded-pill font-heading" style="<?= $badgeStyle ?>">
                                <?= ucfirst(str_replace('_', ' ', e($p['status_pesanan']))) ?>
                            </span>
                        </div>

                        <div class="row g-2 small mb-3 font-body">
                            <div class="col-4" style="color: var(--color-muted);">Jadwal:</div>
                            <div class="col-8 fw-semibold" style="color: var(--color-heading);">
                                <i class="bi bi-calendar-event me-1" style="color: var(--color-dark);"></i>
                                <?= date('d M Y', strtotime($p['tanggal_jadwal'])) ?> (<?= substr($p['waktu_jadwal'], 0, 5) ?> WIB)
                            </div>

                            <div class="col-4" style="color: var(--color-muted);">Alamat:</div>
                            <div class="col-8" style="color: var(--color-body-text);"><?= e($p['alamat_pembersihan']) ?></div>

                            <div class="col-4" style="color: var(--color-muted);">Patokan:</div>
                            <div class="col-8" style="color: var(--color-body-text);"><?= e($p['patokan_lokasi']) ?></div>

                            <div class="col-4" style="color: var(--color-muted);">Total:</div>
                            <div class="col-8 fw-extrabold font-heading fs-6" style="color: var(--color-dark);"><?= format_rupiah($p['total_harga']) ?></div>

                            <div class="col-4" style="color: var(--color-muted);">Pembayaran:</div>
                            <div class="col-8">
                                <span class="badge badge-palette-cream"><?= strtoupper(e($p['metode_pembayaran'])) ?></span>
                                <span class="badge rounded-pill ms-1" style="background-color: var(--color-sand); color: var(--color-dark);"><?= ucfirst(str_replace('_', ' ', e($p['status_pembayaran']))) ?></span>
                            </div>
                        </div>

                        <div class="mt-auto pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2" style="border-color: var(--color-sand) !important;">
                            <!-- IF status selesai: Tombol Beri Ulasan / Testimoni -->
                            <?php if ($p['status_pesanan'] === 'selesai'): ?>
                                <?php if (isset($reviewedOrders[$p['id_pesanan']])): ?>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge badge-palette-cream py-2 px-3 font-heading">
                                            <i class="bi bi-star-fill me-1" style="color: #D97706;"></i> Sudah Diulas (<?= $reviewedOrders[$p['id_pesanan']]['rating'] ?>/5)
                                        </span>
                                        <?php if (!empty($reviewedOrders[$p['id_pesanan']]['foto_before']) || !empty($reviewedOrders[$p['id_pesanan']]['foto_after'])): ?>
                                            <span class="badge rounded-pill py-2 px-2" style="background-color: var(--color-sand); color: var(--color-dark);" title="Foto Before-After terlampir">
                                                <i class="bi bi-camera-fill me-1"></i>Before-After
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>/pelanggan/testimoni/<?= e($p['id_pesanan']) ?>" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold font-heading shadow-sm d-inline-flex align-items-center gap-1" style="color: #4A3B00;">
                                        <i class="bi bi-star-fill"></i> Beri Ulasan & Foto Before-After
                                    </a>
                                <?php endif; ?>
                            <?php elseif ($p['status_pesanan'] === 'sedang_dikerjakan'): ?>
                                <span class="small fw-semibold font-body" style="color: var(--color-dark);">
                                    <i class="bi bi-gear-wide-connected me-1"></i> Pembersihan sedang berlangsung
                                </span>
                            <?php elseif ($p['status_pesanan'] === 'dibatalkan'): ?>
                                <span class="small font-body text-danger">
                                    <i class="bi bi-x-circle me-1"></i> Pesanan dibatalkan
                                </span>
                            <?php else: ?>
                                <span class="small font-body" style="color: var(--color-muted);">
                                    <i class="bi bi-lock me-1"></i> Ulasan aktif setelah pembersihan selesai
                                </span>
                            <?php endif; ?>

                            <a href="<?= BASE_URL ?>/booking/success/<?= e($p['id_pesanan']) ?>" class="btn btn-brand-outline btn-sm px-3">
                                Detail Order
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
