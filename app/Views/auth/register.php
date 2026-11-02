<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card card-custom p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="p-3 rounded-circle d-inline-flex mb-3" style="background-color: var(--color-cream); color: var(--color-dark); width: 64px; height: 64px; align-items: center; justify-content: center; border: 2px solid var(--color-sand);">
                        <i class="bi bi-person-plus-fill fs-2"></i>
                    </span>
                    <h3 class="font-heading mb-1" style="color: var(--color-heading);">Daftar Akun Pelanggan</h3>
                    <p class="font-body small mb-0" style="color: var(--color-body-text);">Pesan jasa pembersihan kos di Semarang dengan mudah & santai</p>
                </div>

                <form action="<?= BASE_URL ?>/auth/register" method="POST">
                    <?= csrf_field() ?>

                    <div class="row g-3">
                        <div class="col-12">
                            <label for="nama_lengkap" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">Nama Lengkap *</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= e($data['nama_lengkap'] ?? '') ?>" placeholder="Contoh: Dimas Anggara" required>
                        </div>

                        <div class="col-md-6">
                            <label for="no_telp" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">Nomor WhatsApp *</label>
                            <input type="tel" class="form-control" id="no_telp" name="no_telp" value="<?= e($data['no_telp'] ?? '') ?>" placeholder="08xxxxxxxxxx" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">Alamat Email *</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= e($data['email'] ?? '') ?>" placeholder="nama@email.com" required>
                        </div>

                        <div class="col-12">
                            <label for="password" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">Kata Sandi (Password) *</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 6 karakter" required>
                        </div>

                        <div class="col-12">
                            <label for="alamat" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">Alamat Kos Utama (Opsional)</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="2" placeholder="Nama kos, nomor kamar, nama jalan, kecamatan di Semarang"><?= e($data['alamat'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-brand-primary w-100 py-3 mt-4 shadow">
                        Daftar & Mulai Pesan <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>

                <div class="text-center mt-4 small font-body" style="color: var(--color-body-text);">
                    Sudah memiliki akun? <a href="<?= BASE_URL ?>/auth/login" class="text-decoration-none fw-bold font-heading" style="color: var(--color-dark);">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
