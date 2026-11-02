<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4 my-3">
    <div class="mb-4">
        <h2 class="display-6 font-heading mb-1" style="color: var(--color-heading);">
            <i class="bi bi-bar-chart-line-fill me-2" style="color: var(--color-dark);"></i>Pusat Laporan Keuangan SAK
        </h2>
        <p class="font-body mb-0" style="color: var(--color-muted);">
            Laporan keuangan standar SAK EMKM untuk Jasa Bersih KosClean. Semua laporan dapat dicetak.
        </p>
    </div>

    <div class="row g-4">
        <?php
        $menus = [
            [
                'url'   => BASE_URL . '/laporan/mutasiKeuangan',
                'icon'  => 'bi-arrow-left-right',
                'title' => 'Mutasi Kas & Bank',
                'desc'  => 'Pergerakan saldo masuk & keluar pada akun Kas Tunai (111) dan Kas Bank (112) beserta saldo awal & akhir.',
                'color' => '#1565C0',
                'badge' => 'Akun 111 & 112'
            ],
            [
                'url'   => BASE_URL . '/laporan/neracaSaldo',
                'icon'  => 'bi-table',
                'title' => 'Neraca Saldo',
                'desc'  => 'Trial Balance: ringkasan total Debet & Kredit semua akun COA. Grand total Debet harus = Kredit.',
                'color' => '#2E7D32',
                'badge' => 'Trial Balance'
            ],
            [
                'url'   => BASE_URL . '/laporan/labaRugi',
                'icon'  => 'bi-graph-up-arrow',
                'title' => 'Laporan Laba / Rugi',
                'desc'  => 'Income Statement: Total Pendapatan (kat. 4) dikurangi Total Beban operasional (kat. 5) periode berjalan.',
                'color' => '#D97706',
                'badge' => 'Income Statement'
            ],
            [
                'url'   => BASE_URL . '/laporan/neraca',
                'icon'  => 'bi-bank',
                'title' => 'Neraca Keuangan',
                'desc'  => 'Balance Sheet: Aktiva (Harta) = Pasiva (Kewajiban + Modal + Laba Berjalan). Nilai buku aset bersih.',
                'color' => '#6A1B9A',
                'badge' => 'Balance Sheet'
            ],
            [
                'url'   => BASE_URL . '/laporan/arusKas',
                'icon'  => 'bi-cash-coin',
                'title' => 'Laporan Arus Kas',
                'desc'  => 'Cash Flow Statement: pergerakan kas masuk & keluar yang melibatkan akun Kas/Bank (111 & 112).',
                'color' => '#00695C',
                'badge' => 'Cash Flow'
            ],
        ];
        foreach ($menus as $m): ?>
        <div class="col-md-6 col-lg-4">
            <a href="<?= $m['url'] ?>" class="text-decoration-none h-100 d-block">
                <div class="card card-custom p-4 h-100">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:52px;height:52px;background-color:var(--color-cream);color:<?= $m['color'] ?>;border:1.5px solid var(--color-sand);">
                            <i class="bi <?= $m['icon'] ?> fs-4"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold font-heading mb-0" style="color: var(--color-heading);"><?= $m['title'] ?></h6>
                                <span class="badge rounded-pill ms-2 flex-shrink-0" style="background-color:var(--color-cream);color:var(--color-dark);border:1px solid var(--color-sand);font-size:0.65rem;">
                                    <?= $m['badge'] ?>
                                </span>
                            </div>
                            <p class="font-body mb-0" style="color: var(--color-muted); font-size:0.8rem; line-height:1.5;">
                                <?= $m['desc'] ?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 pt-2 border-top d-flex align-items-center justify-content-end" style="border-color: var(--color-sand) !important;">
                        <small class="font-heading fw-bold" style="color: <?= $m['color'] ?>;">
                            Buka Laporan <i class="bi bi-arrow-right-short"></i>
                        </small>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>

        <!-- Link Cepat ke Laporan Admin Lainnya -->
        <div class="col-12">
            <div class="card card-custom p-4" style="background-color: var(--color-cream) !important;">
                <h6 class="fw-bold font-heading mb-3" style="color: var(--color-heading);">
                    <i class="bi bi-lightning-charge-fill me-2" style="color: var(--color-dark);"></i>Akses Cepat Modul Terkait
                </h6>
                <div class="d-flex flex-wrap gap-2">
                    <a href="<?= BASE_URL ?>/admin/jurnal" class="btn btn-brand-outline btn-sm">
                        <i class="bi bi-journal-text me-1"></i>Jurnal Umum
                    </a>
                    <a href="<?= BASE_URL ?>/admin/aset" class="btn btn-brand-outline btn-sm">
                        <i class="bi bi-tools me-1"></i>Aset & Depresiasi
                    </a>
                    <a href="<?= BASE_URL ?>/admin/labaRugi" class="btn btn-brand-outline btn-sm">
                        <i class="bi bi-graph-up me-1"></i>Laba/Rugi (Admin)
                    </a>
                    <a href="<?= BASE_URL ?>/admin" class="btn btn-brand-outline btn-sm">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

