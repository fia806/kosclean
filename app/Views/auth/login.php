<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-5 my-auto">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card card-custom p-4 p-md-5">
                <div class="text-center mb-4">
                    <span class="p-3 rounded-circle d-inline-flex mb-3" style="background-color: var(--color-cream); color: var(--color-dark); width: 64px; height: 64px; align-items: center; justify-content: center; border: 2px solid var(--color-sand);">
                        <i class="bi bi-box-arrow-in-right fs-2"></i>
                    </span>
                    <h3 class="font-heading mb-1" style="color: var(--color-heading);">Masuk ke Akun</h3>
                    <p class="font-body small mb-0" style="color: var(--color-body-text);">Kelola pesanan bersih kos atau pantau tugas cleaner</p>
                </div>

                <form action="<?= BASE_URL ?>/auth/login" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="redirect" value="<?= e($redirect ?? '') ?>">

                    <div class="mb-3">
                        <label for="email" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">Alamat Email</label>
                        <div class="input-group">
                            <span class="input-group-text border-end-0 rounded-start-pill ps-3" style="background-color: var(--color-cream); border-color: var(--color-sand); color: var(--color-dark);"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control border-start-0 rounded-end-pill pe-3" id="email" name="email" value="<?= e($email ?? '') ?>" placeholder="nama@email.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold font-heading" style="color: var(--color-heading);">Kata Sandi (Password)</label>
                        <div class="input-group">
                            <span class="input-group-text border-end-0 rounded-start-pill ps-3" style="background-color: var(--color-cream); border-color: var(--color-sand); color: var(--color-dark);"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control border-start-0 rounded-end-pill pe-3" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-brand-primary w-100 py-3 mt-3 shadow">
                        Masuk Sekarang <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>

                <hr class="my-4" style="border-color: var(--color-sand) !important;">

                <!-- Akun Demo untuk Kemudahan Evaluasi -->
                <div class="p-3 rounded-4 small" style="background-color: var(--color-cream); border: 1.5px dashed var(--color-sand);">
                    <strong class="d-block mb-1 font-heading" style="color: var(--color-heading);"><i class="bi bi-info-circle-fill me-1" style="color: var(--color-dark);"></i> Akun Demo Tersedia:</strong>
                    <div class="font-body" style="font-size: 0.82rem; line-height: 1.6; color: var(--color-body-text);">
                        <div>&bull; <strong>Admin:</strong> admin@kosclean.com / admin123</div>
                        <div>&bull; <strong>Cleaner:</strong> cleaner@kosclean.com / cleaner123</div>
                        <div>&bull; <strong>Pelanggan:</strong> pelanggan@gmail.com / pelanggan123</div>
                    </div>
                </div>

                <div class="text-center mt-4 small font-body" style="color: var(--color-body-text);">
                    Belum punya akun? <a href="<?= BASE_URL ?>/auth/register" class="text-decoration-none fw-bold font-heading" style="color: var(--color-dark);">Daftar sebagai Pelanggan</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
