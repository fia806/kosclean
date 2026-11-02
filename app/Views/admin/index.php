<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container py-4 my-3">
    <!-- Header Admin -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge badge-mist mb-1">
                <i class="bi bi-shield-lock-fill me-1"></i> Admin Portal
            </span>
            <h2 class="display-6 font-heading mb-0" style="color: var(--color-heading);">Dashboard Manajemen & Akuntansi</h2>
            <p class="font-body small mb-0" style="color: var(--color-body-text);">Ringkasan operasional jasa bersih kos dan performa keuangan platform.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-brand-primary btn-sm shadow">
                <i class="bi bi-card-checklist me-1"></i>Kelola Pesanan
            </a>
            <a href="<?= BASE_URL ?>/admin/jurnal" class="btn btn-brand-outline btn-sm">
                <i class="bi bi-journal-text me-1"></i>Jurnal Akuntansi
            </a>
            <a href="<?= BASE_URL ?>/admin/labaRugi" class="btn btn-brand-outline btn-sm">
                <i class="bi bi-graph-up-arrow me-1"></i>Laba/Rugi
            </a>
        </div>
    </div>

    <!-- 1. METRIC SUMMARY CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card card-custom p-3 border-start border-4 shadow-sm" style="border-left-color: var(--color-dark) !important;">
                <span class="font-body small" style="color: var(--color-muted);">Total Seluruh Pesanan</span>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h3 class="fw-bold font-heading mb-0" style="color: var(--color-heading);"><?= $totalOrders ?></h3>
                    <span class="p-2 rounded-circle" style="background-color: var(--color-cream); color: var(--color-dark);"><i class="bi bi-cart-check-fill fs-4"></i></span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card card-custom p-3 border-start border-4 shadow-sm" style="border-left-color: #D97706 !important;">
                <span class="font-body small" style="color: var(--color-muted);">Menunggu Konfirmasi / Jadwal</span>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h3 class="fw-bold font-heading mb-0" style="color: #D97706;"><?= $pendingOrders ?></h3>
                    <span class="p-2 rounded-circle" style="background-color: #FEF3C7; color: #D97706;"><i class="bi bi-hourglass-top fs-4"></i></span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card card-custom p-3 border-start border-4 shadow-sm" style="border-left-color: var(--color-slate-blue) !important;">
                <span class="font-body small" style="color: var(--color-muted);">Sedang Dikerjakan Cleaner</span>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h3 class="fw-bold font-heading mb-0" style="color: var(--color-dark);"><?= $inProgressOrders ?></h3>
                    <span class="p-2 rounded-circle" style="background-color: var(--color-cream); color: var(--color-dark);"><i class="bi bi-brush-fill fs-4"></i></span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card card-custom p-3 border-start border-4 shadow-sm" style="border-left-color: #2E7D32 !important;">
                <span class="font-body small" style="color: var(--color-muted);">Pesanan Selesai (Lunas)</span>
                <div class="d-flex justify-content-between align-items-center mt-2">
                    <h3 class="fw-bold font-heading mb-0" style="color: #2E7D32;"><?= $completedOrders ?></h3>
                    <span class="p-2 rounded-circle" style="background-color: #E8F5E9; color: #2E7D32;"><i class="bi bi-check-circle-fill fs-4"></i></span>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. GRAFIK OMZET BULAN INI (CHART.JS) DARI TABEL JURNAL_DETAIL -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card card-custom p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="fw-bold font-heading mb-0" style="color: var(--color-heading);"><i class="bi bi-bar-chart-line-fill me-2" style="color: var(--color-dark);"></i>Tren Omzet Pendapatan Bersih</h5>
                        <small class="font-body" style="color: var(--color-muted);">Data ditarik otomatis dari buku besar akun Pendapatan (jurnal_detail)</small>
                    </div>
                    <span class="badge badge-palette-cream font-heading">Real-time Accounting</span>
                </div>
                <div style="min-height: 280px; position: relative;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-custom p-4 h-100">
                <h5 class="fw-bold font-heading mb-3" style="color: var(--color-heading);"><i class="bi bi-lightning-charge-fill me-2" style="color: #D97706;"></i>Aksi Cepat</h5>
                <div class="d-grid gap-2">
                    <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-outline-dark text-start p-3 rounded-4" style="border-color: var(--color-sand);">
                        <i class="bi bi-check2-square fs-5 me-2" style="color: var(--color-dark);"></i>
                        <strong class="font-heading" style="color: var(--color-heading);">Verifikasi QC Pesanan</strong>
                        <div class="small font-body" style="color: var(--color-muted);">Periksa foto sebelum & sesudah dari cleaner</div>
                    </a>

                    <a href="<?= BASE_URL ?>/admin/layanan" class="btn btn-outline-dark text-start p-3 rounded-4" style="border-color: var(--color-sand);">
                        <i class="bi bi-grid-fill fs-5 me-2" style="color: var(--color-slate-blue);"></i>
                        <strong class="font-heading" style="color: var(--color-heading);">Kelola Paket Layanan</strong>
                        <div class="small font-body" style="color: var(--color-muted);">Ubah tarif dasar & upload ikon layanan</div>
                    </a>

                    <a href="<?= BASE_URL ?>/admin/jurnal" class="btn btn-outline-dark text-start p-3 rounded-4" style="border-color: var(--color-sand);">
                        <i class="bi bi-cash-coin fs-5 me-2" style="color: #D97706;"></i>
                        <strong class="font-heading" style="color: var(--color-heading);">Input Pengeluaran Kas</strong>
                        <div class="small font-body" style="color: var(--color-muted);">Catat gaji karyawan atau beban perlengkapan</div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TABEL PESANAN TERBARU -->
    <div class="card card-custom p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold font-heading mb-0" style="color: var(--color-heading);">Pesanan Terbaru Masuk</h5>
            <a href="<?= BASE_URL ?>/admin/orders" class="btn btn-sm btn-link font-heading text-decoration-none" style="color: var(--color-dark);">Lihat Semua Pesanan &raquo;</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 font-body">
                <thead class="small" style="background-color: var(--color-cream); color: var(--color-heading);">
                    <tr>
                        <th class="py-3">ID Pesanan</th>
                        <th class="py-3">Pelanggan</th>
                        <th class="py-3">Layanan</th>
                        <th class="py-3">Jadwal</th>
                        <th class="py-3">Total</th>
                        <th class="py-3">Metode</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="small">
                    <?php if (empty($recentOrders)): ?>
                        <tr><td colspan="8" class="text-center font-body py-4" style="color: var(--color-muted);">Belum ada pesanan masuk.</td></tr>
                    <?php else: ?>
                        <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td><strong class="font-heading" style="color: var(--color-heading);"><?= e($order['id_pesanan']) ?></strong></td>
                                <td>
                                    <div class="fw-bold" style="color: var(--color-heading);"><?= e($order['nama_pelanggan']) ?></div>
                                    <small style="color: var(--color-muted);"><?= e($order['telp_pelanggan']) ?></small>
                                </td>
                                <td style="color: var(--color-body-text);"><?= e($order['nama_layanan']) ?></td>
                                <td style="color: var(--color-body-text);"><?= date('d/m/Y', strtotime($order['tanggal_jadwal'])) ?> (<?= substr($order['waktu_jadwal'], 0, 5) ?>)</td>
                                <td class="fw-bold font-heading" style="color: var(--color-dark);"><?= format_rupiah($order['total_harga']) ?></td>
                                <td><span class="badge badge-palette-cream"><?= strtoupper(e($order['metode_pembayaran'])) ?></span></td>
                                <td>
                                    <span class="badge rounded-pill" style="background-color: var(--color-sand); color: var(--color-dark);"><?= ucfirst(str_replace('_', ' ', e($order['status_pesanan']))) ?></span>
                                </td>
                                <td>
                                    <a href="<?= BASE_URL ?>/admin/orderDetail/<?= e($order['id_pesanan']) ?>" class="btn btn-sm btn-brand-outline py-1 px-3">
                                        Detail / QC
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

<!-- Script Inisialisasi Chart.js Omzet Bulanan -->
<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const chartLabels = <?= json_encode($chartData['labels']) ?>;
    const chartDataValues = <?= json_encode($chartData['data']) ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Omzet Pendapatan Jasa (Rp)',
                data: chartDataValues,
                backgroundColor: 'rgba(72, 75, 106, 0.85)',
                borderColor: '#484B6A',
                borderWidth: 2,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Pendapatan: Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
