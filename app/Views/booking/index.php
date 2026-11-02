<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
// Checklist detail resmi untuk setiap layanan
$serviceChecklists = [
    1 => [
        'Menyapu dan mengepel lantai kamar',
        'Membersihkan debu meja & permukaan furnitur',
        'Merapikan area kamar kos',
        'Membuang sampah ke tempat luar'
    ],
    2 => [
        'Membersihkan sudut dan kolong furnitur',
        'Menghilangkan debu membandel & sarang laba-laba',
        'Membersihkan noda pada permukaan',
        'Membersihkan area yang sulit dijangkau'
    ],
    3 => [
        'Menyikat lantai & dinding kamar mandi',
        'Membersihkan toilet dan wastafel',
        'Membersihkan kerak ringan membandel',
        'Membersihkan saluran & shower terjangkau'
    ],
    4 => [
        'Membersihkan lantai dan debu menyeluruh',
        'Membersihkan lemari & meja yang dikosongkan',
        'Membersihkan bekas noda ringan',
        'Mengumpulkan & membuang sampah tertinggal'
    ],
    5 => [
        'Membersihkan lantai & permukaan furnitur',
        'Membersihkan lemari dan meja kosong',
        'Membersihkan debu & sarang laba-laba',
        'Membersihkan kamar mandi siap huni'
    ]
];
?>

<div class="container py-4 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <div class="card card-custom p-4 p-md-5">
                
                <div class="mb-4">
                    <span class="badge badge-mist mb-2"><i class="bi bi-calendar2-check-fill me-1"></i> Formulir Pemesanan Resmi</span>
                    <h2 class="display-6 font-heading mb-1" style="color: var(--color-heading);">Pesan Layanan Bersih Kos</h2>
                    <p class="font-body small mb-0" style="color: var(--color-body-text);">Pilih paket layanan spesialis kami dan tentukan jadwal kedatangan cleaner ke lokasi kos Anda di Semarang.</p>
                </div>

                <!-- BOKS ALERT PERINGATAN KEAMANAN SANTAI -->
                <div class="alert-security-warning mb-4 shadow-sm d-flex gap-3 align-items-start">
                    <i class="bi bi-shield-exclamation fs-3 flex-shrink-0 mt-1" style="color: #D97706;"></i>
                    <div>
                        <strong class="d-block font-heading mb-1" style="color: var(--color-heading);">Peringatan Keamanan Transaksi:</strong>
                        <span class="font-body small" style="color: var(--color-body-text); line-height: 1.65;">
                            Dilarang keras bertransaksi atau melakukan pembayaran langsung secara privat dengan cleaner di luar sistem resmi KosClean. Segala komplain atau kehilangan tanpa nomor order resmi <strong>tidak dilindungi oleh garansi platform</strong>.
                        </span>
                    </div>
                </div>

                <form action="<?= BASE_URL ?>/booking/store" method="POST" id="formBooking">
                    <?= csrf_field() ?>

                    <!-- 1. Pilihan Layanan -->
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2" style="border-color: var(--color-sand) !important;">
                        <h5 class="fw-bold font-heading mb-0" style="color: var(--color-heading);">
                            <i class="bi bi-stars me-2" style="color: var(--color-dark);"></i>1. Pilih Paket Layanan (5 Pilihan Resmi)
                        </h5>
                        <span class="badge badge-palette-cream small">Pilih salah satu</span>
                    </div>

                    <div class="row g-3 mb-4">
                        <?php foreach ($layananList as $l): ?>
                            <?php 
                                $isDefaultChecked = ($selectedLayananId == $l['id_layanan'] || (empty($selectedLayananId) && $l['id_layanan'] == 1));
                                $checkItems = $serviceChecklists[$l['id_layanan']] ?? [];
                            ?>
                            <div class="col-md-6">
                                <label class="card p-3 p-md-4 h-100 rounded-4 cursor-pointer service-option-card <?= $isDefaultChecked ? 'active-service' : '' ?>" style="cursor: pointer; border: 2px solid var(--color-sand); background: #FFFFFF; transition: all 0.25s ease;">
                                    <div class="d-flex align-items-start justify-content-between mb-2 gap-2">
                                        <div class="form-check ps-4">
                                            <input class="form-check-input service-radio" type="radio" name="id_layanan" id="layanan_<?= $l['id_layanan'] ?>" value="<?= $l['id_layanan'] ?>" data-harga="<?= $l['harga_dasar'] ?>" <?= $isDefaultChecked ? 'checked' : '' ?> required style="cursor: pointer; margin-left: -1.4rem;">
                                            <span class="fw-bold font-heading fs-6" style="color: var(--color-heading);"><?= e($l['nama_layanan']) ?></span>
                                        </div>
                                        <span class="badge badge-palette-cream small text-nowrap"><?= e($l['estimasi_waktu']) ?></span>
                                    </div>

                                    <p class="font-body small mb-3" style="color: var(--color-body-text); font-size: 0.88rem; line-height: 1.55;">
                                        <?= e($l['deskripsi']) ?>
                                    </p>

                                    <!-- Checklist Rincian Pengerjaan -->
                                    <?php if (!empty($checkItems)): ?>
                                        <div class="p-2 rounded-3 mb-3" style="background-color: rgba(216, 206, 190, 0.2);">
                                            <span class="d-block fw-bold font-heading small mb-1" style="font-size: 0.78rem; color: var(--color-heading);">
                                                <i class="bi bi-check2-all me-1" style="color: var(--color-dark);"></i>Cakupan Layanan:
                                            </span>
                                            <ul class="list-unstyled mb-0 d-flex flex-column gap-1" style="font-size: 0.82rem; color: var(--color-body-text);">
                                                <?php foreach ($checkItems as $item): ?>
                                                    <li class="d-flex align-items-start gap-1">
                                                        <i class="bi bi-check2 text-success flex-shrink-0 mt-1"></i>
                                                        <span><?= e($item) ?></span>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>

                                    <div class="mt-auto pt-2 d-flex justify-content-between align-items-baseline border-top" style="border-color: rgba(216, 206, 190, 0.4) !important;">
                                        <span class="small font-body" style="color: var(--color-muted);">Tarif Resmi:</span>
                                        <div class="fw-extrabold font-heading fs-5" style="color: var(--color-dark);">
                                            <?= format_rupiah($l['harga_dasar']) ?> <span class="small fw-normal font-body" style="font-size: 0.85rem; color: var(--color-muted);">/ sesi</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- 2. Jadwal Pembersihan -->
                    <h5 class="fw-bold font-heading mb-3 border-bottom pb-2" style="color: var(--color-heading); border-color: var(--color-sand) !important;">
                        <i class="bi bi-calendar3 me-2" style="color: var(--color-dark);"></i>2. Jadwal Pembersihan
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label for="tanggal_jadwal" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">Tanggal Pembersihan *</label>
                            <input type="date" class="form-control" id="tanggal_jadwal" name="tanggal_jadwal" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="waktu_jadwal" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">Jam Kedatangan Cleaner *</label>
                            <select class="form-select" id="waktu_jadwal" name="waktu_jadwal" required>
                                <option value="08:00:00">08:00 WIB (Pagi)</option>
                                <option value="10:00:00">10:00 WIB (Pagi)</option>
                                <option value="13:00:00">13:00 WIB (Siang)</option>
                                <option value="15:30:00">15:30 WIB (Sore)</option>
                            </select>
                        </div>
                    </div>

                    <!-- 3. Alamat & Patokan Lokasi Semarang -->
                    <h5 class="fw-bold font-heading mb-3 border-bottom pb-2" style="color: var(--color-heading); border-color: var(--color-sand) !important;">
                        <i class="bi bi-geo-alt-fill me-2" style="color: var(--color-dark);"></i>3. Lokasi Kos di Semarang
                    </h5>
                    <div class="row g-3 mb-4">
                        <div class="col-12">
                            <label for="alamat_pembersihan" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">Alamat Lengkap Kamar Kos *</label>
                            <textarea class="form-control" id="alamat_pembersihan" name="alamat_pembersihan" rows="3" placeholder="Contoh: Kos Putri Melati, Kamar No. 204 Lt 2, Jl. Tirto Agung No. 15, Tembalang, Kota Semarang" required><?= e($currentUser['alamat'] ?? '') ?></textarea>
                            <div class="form-text small" style="color: var(--color-muted);">Sertakan nama kos, nomor kamar, lantai, dan nama jalan.</div>
                        </div>

                        <div class="col-12">
                            <label for="patokan_lokasi" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">Patokan Lokasi Terdekat *</label>
                            <input type="text" class="form-control" id="patokan_lokasi" name="patokan_lokasi" placeholder="Contoh: Gang seberang SPBU Undip Tembalang / Cat pagar hitam depan Indomaret" required>
                        </div>
                    </div>

                    <!-- 4. Metode Pembayaran (Hanya Midtrans) -->
                    <h5 class="fw-bold font-heading mb-3 border-bottom pb-2" style="color: var(--color-heading); border-color: var(--color-sand) !important;">
                        <i class="bi bi-wallet2 me-2" style="color: var(--color-dark);"></i>4. Metode Pembayaran
                    </h5>

                    <!-- Hidden input: metode selalu midtrans -->
                    <input type="hidden" name="metode_pembayaran" value="midtrans">

                    <div class="mb-4">
                        <div class="card p-4 rounded-4" style="border: 2px solid var(--color-dark); background: linear-gradient(135deg, #FAF1DC 0%, #F4EFE6 100%);">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                     style="width:48px;height:48px;background-color:var(--color-dark);color:#fff;">
                                    <i class="bi bi-shield-check fs-5"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold font-heading mb-1" style="color: var(--color-heading); font-size: 1rem;">
                                        <i class="bi bi-check-circle-fill me-1 text-success"></i>
                                        Pembayaran via Midtrans (Payment Gateway Resmi)
                                    </div>
                                    <p class="font-body small mb-3" style="color: var(--color-body-text); line-height: 1.6;">
                                        Semua transaksi KosClean diproses secara aman melalui <strong>Midtrans</strong> — payment gateway terpercaya berlisensi Bank Indonesia.
                                        Tidak ada pembayaran tunai atau transfer langsung di luar sistem.
                                    </p>
                                    <!-- Metode pembayaran yang tersedia via Midtrans -->
                                    <div class="d-flex flex-wrap gap-2">
                                        <?php
                                        $paymentMethods = [
                                            ['icon' => 'bi-qr-code-scan',      'label' => 'QRIS'],
                                            ['icon' => 'bi-bank',              'label' => 'Virtual Account'],
                                            ['icon' => 'bi-credit-card-fill',  'label' => 'Kartu Kredit'],
                                            ['icon' => 'bi-phone-fill',        'label' => 'GoPay / OVO'],
                                            ['icon' => 'bi-bag-fill',          'label' => 'ShopeePay'],
                                            ['icon' => 'bi-cash-coin',         'label' => 'Alfamart/Indomaret'],
                                        ];
                                        foreach ($paymentMethods as $pm): ?>
                                        <span class="badge px-3 py-2 rounded-pill font-body"
                                              style="background-color: var(--color-dark); color: #fff; font-size: 0.75rem; font-weight: 600;">
                                            <i class="bi <?= $pm['icon'] ?> me-1"></i><?= $pm['label'] ?>
                                        </span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan & Submit (Palet Cream & Deep Slate) -->
                    <div class="p-4 rounded-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                        <div>
                            <span class="small font-body d-block" style="color: var(--color-body-text);">Estimasi Total Biaya:</span>
                            <span class="h3 fw-extrabold font-heading mb-0" id="displayTotal" style="color: var(--color-dark);">Rp 35.000</span>
                        </div>
                        <button type="submit" class="btn btn-brand-primary btn-lg px-5 py-3 shadow">
                            Konfirmasi Booking <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .service-option-card.active-service {
        border-color: var(--color-dark) !important;
        background-color: var(--color-cream) !important;
        box-shadow: 0 8px 24px rgba(72, 75, 106, 0.15);
    }
</style>

<script>
    // Update display total harga secara dinamis berdasarkan radio terpilih
    document.querySelectorAll('.service-radio').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const harga = parseInt(this.getAttribute('data-harga')) || 0;
            document.getElementById('displayTotal').innerText = 'Rp ' + harga.toLocaleString('id-ID');
            
            document.querySelectorAll('.service-option-card').forEach(c => {
                c.classList.remove('active-service');
            });
            this.closest('.service-option-card').classList.add('active-service');
        });
        if (radio.checked) {
            const harga = parseInt(radio.getAttribute('data-harga')) || 0;
            document.getElementById('displayTotal').innerText = 'Rp ' + harga.toLocaleString('id-ID');
            radio.closest('.service-option-card').classList.add('active-service');
        }
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
