<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4 my-3">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom p-4 p-md-5">
                
                <div class="text-center mb-4">
                    <span class="p-3 rounded-circle d-inline-flex mb-3" style="background-color: var(--color-cream); color: #D97706; width: 68px; height: 68px; align-items: center; justify-content: center; border: 2px solid var(--color-sand);">
                        <i class="bi bi-star-fill fs-2"></i>
                    </span>
                    <h3 class="font-heading mb-1" style="color: var(--color-heading);">Beri Ulasan & Foto Before-After</h3>
                    <p class="font-body small mb-0" style="color: var(--color-body-text);">
                        Bagikan pengalaman nyata kepuasan Anda setelah pembersihan kamar kos selesai dikerjakan oleh tim KosClean.
                    </p>
                </div>

                <!-- Ringkasan Singkat Pesanan Selesai -->
                <div class="p-4 rounded-4 mb-4 small font-body" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                    <div class="row g-2">
                        <div class="col-sm-6">
                            <span style="color: var(--color-muted);">ID Pesanan:</span>
                            <strong class="d-block font-heading" style="color: var(--color-heading);"><?= e($pesanan['id_pesanan']) ?></strong>
                        </div>
                        <div class="col-sm-6">
                            <span style="color: var(--color-muted);">Paket Layanan:</span>
                            <strong class="d-block font-heading" style="color: var(--color-heading);"><?= e($pesanan['nama_layanan']) ?></strong>
                        </div>
                        <div class="col-sm-6">
                            <span style="color: var(--color-muted);">Tanggal Selesai:</span>
                            <span class="d-block fw-semibold" style="color: var(--color-heading);"><?= date('d M Y', strtotime($pesanan['tanggal_jadwal'])) ?></span>
                        </div>
                        <div class="col-sm-6">
                            <span style="color: var(--color-muted);">Status Pengerjaan:</span>
                            <span class="badge" style="background-color: #2E7D32; color: #FFFFFF;"><i class="bi bi-check-circle-fill me-1"></i>Selesai & Lunas</span>
                        </div>
                    </div>
                </div>

                <form action="<?= BASE_URL ?>/pelanggan/testimoni/<?= e($pesanan['id_pesanan']) ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <!-- Input 1: Rating (1-5 Bintang) -->
                    <div class="mb-4 text-center">
                        <label class="form-label fw-bold small d-block font-heading mb-2" style="color: var(--color-heading);">
                            Tingkat Kepuasan (Rating Bintang 1 - 5) *
                        </label>
                        <div class="d-inline-flex flex-wrap justify-content-center gap-2 p-2 rounded-pill shadow-sm" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                            <?php 
                            $starLabels = [
                                5 => '5 ★ Sangat Puas',
                                4 => '4 ★ Puas',
                                3 => '3 ★ Cukup',
                                2 => '2 ★ Kurang',
                                1 => '1 ★ Kecewa'
                            ];
                            foreach ([1, 2, 3, 4, 5] as $val): 
                            ?>
                                <input type="radio" class="btn-check" name="rating" id="rating_<?= $val ?>" value="<?= $val ?>" <?= $val === 5 ? 'checked' : '' ?> required>
                                <label class="btn btn-outline-warning rounded-pill px-3 py-2 fw-bold font-heading small star-option" for="rating_<?= $val ?>" style="border-color: transparent;">
                                    <?= $val ?> ★
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <div class="font-body small mt-2" id="rating-text" style="color: #B45309; font-weight: 600;">
                            5 Bintang - Sangat Puas & Bersih Maksimal!
                        </div>
                    </div>

                    <!-- Input 2: Ulasan Teks -->
                    <div class="mb-4">
                        <label for="ulasan" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">
                            Ulasan & Kesan Anda *
                        </label>
                        <textarea class="form-control" id="ulasan" name="ulasan" rows="4" placeholder="Ceritakan bagaimana hasil kebersihan kamar kos, ketepatan waktu cleaner, keramahan, dan bagian mana yang paling memuaskan..." required style="border-radius: 14px; border: 1.5px solid var(--color-sand);"></textarea>
                        <div class="form-text small font-body" style="color: var(--color-muted);">
                            Ulasan Anda akan membantu teman-teman mahasiswa & perantau lain dalam memilih jasa bersih kos terbaik di Semarang.
                        </div>
                    </div>

                    <!-- Input 3 & 4: Upload Foto Before & After (Opsional) -->
                    <div class="mb-4">
                        <label class="form-label small fw-bold font-heading d-block mb-2" style="color: var(--color-heading);">
                            Unggah Foto Hasil Pembersihan (Before & After) <span class="badge badge-palette-cream ms-1">Opsional</span>
                        </label>

                        <div class="row g-3">
                            <!-- Foto Sebelum (Before Photo) -->
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 h-100" style="background-color: var(--color-cream); border: 1.5px dashed var(--color-sand);">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge px-3 py-1 rounded-pill font-heading" style="background-color: #64748B; color: #FFFFFF;">
                                            <i class="bi bi-camera me-1"></i>Foto Sebelum (Before)
                                        </span>
                                        <small class="font-body" style="color: var(--color-muted);">Opsional</small>
                                    </div>
                                    <p class="font-body small mb-3" style="color: var(--color-body-text); font-size: 0.82rem;">
                                        Kondisi kamar/kamar mandi saat berdebu atau kotor sebelum dibersihkan.
                                    </p>
                                    
                                    <input type="file" class="form-control form-control-sm mb-2" id="foto_before" name="foto_before" accept=".jpg,.jpeg,.png,.webp" onchange="previewImage(this, 'preview_before', 'box_preview_before')">
                                    
                                    <!-- Preview Box -->
                                    <div id="box_preview_before" class="d-none text-center mt-2">
                                        <img id="preview_before" src="#" alt="Preview Before" class="img-fluid rounded-3 border shadow-sm" style="max-height: 140px; object-fit: cover;">
                                        <div class="small text-muted mt-1 font-body" style="font-size: 0.75rem;">Preview Foto Sebelum</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Foto Sesudah (After Photo) -->
                            <div class="col-md-6">
                                <div class="p-3 rounded-4 h-100" style="background-color: var(--color-cream); border: 1.5px dashed var(--color-sand);">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <span class="badge px-3 py-1 rounded-pill font-heading" style="background-color: #0F766E; color: #FFFFFF;">
                                            <i class="bi bi-stars me-1"></i>Foto Sesudah (After)
                                        </span>
                                        <small class="font-body" style="color: var(--color-muted);">Opsional</small>
                                    </div>
                                    <p class="font-body small mb-3" style="color: var(--color-body-text); font-size: 0.82rem;">
                                        Kondisi kamar yang sudah kinclong, rapi, dan bersih sesudah pengerjaan.
                                    </p>
                                    
                                    <input type="file" class="form-control form-control-sm mb-2" id="foto_after" name="foto_after" accept=".jpg,.jpeg,.png,.webp" onchange="previewImage(this, 'preview_after', 'box_preview_after')">
                                    
                                    <!-- Preview Box -->
                                    <div id="box_preview_after" class="d-none text-center mt-2">
                                        <img id="preview_after" src="#" alt="Preview After" class="img-fluid rounded-3 border shadow-sm" style="max-height: 140px; object-fit: cover;">
                                        <div class="small text-muted mt-1 font-body" style="font-size: 0.75rem;">Preview Foto Sesudah</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-text small font-body mt-2" style="color: var(--color-muted);">
                            <i class="bi bi-info-circle me-1"></i>Format yang didukung: <strong>JPG, JPEG, PNG, WEBP</strong>. Maksimal <strong>2MB</strong> per foto.
                        </div>
                    </div>

                    <div class="d-grid gap-2 pt-2">
                        <button type="submit" class="btn btn-brand-primary btn-lg py-3 shadow font-heading fw-bold">
                            Kirim Ulasan & Publikasikan <i class="bi bi-send-fill ms-1"></i>
                        </button>
                        <a href="<?= BASE_URL ?>/pelanggan/dashboard" class="btn btn-brand-outline py-2 font-heading">
                            Kembali ke Dashboard Saya
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input, previewImgId, boxPreviewId) {
    const box = document.getElementById(boxPreviewId);
    const img = document.getElementById(previewImgId);
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            img.src = e.target.result;
            box.classList.remove('d-none');
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        box.classList.add('d-none');
    }
}

// Live text rating explanation
const ratingTexts = {
    '1': '1 Bintang - Sangat Kecewa dengan hasil pengerjaan',
    '2': '2 Bintang - Kurang Memuaskan, masih ada bagian terlewat',
    '3': '3 Bintang - Cukup Rapi, standar pembersihan',
    '4': '4 Bintang - Puas, kamar bersih dan cleaner tepat waktu',
    '5': '5 Bintang - Sangat Puas & Bersih Maksimal! Sangat direkomendasikan.'
};

document.querySelectorAll('input[name="rating"]').forEach(function(radio) {
    radio.addEventListener('change', function() {
        const descEl = document.getElementById('rating-text');
        if (descEl && ratingTexts[this.value]) {
            descEl.textContent = ratingTexts[this.value];
        }
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
