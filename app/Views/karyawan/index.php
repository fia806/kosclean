<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container py-4 my-3">
    <!-- Header Dashboard Cleaner -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <span class="badge badge-mist mb-1">
                <i class="bi bi-person-workspace me-1"></i> Cleaner Portal
            </span>
            <h2 class="display-6 font-heading mb-0" style="color: var(--color-heading);">Dashboard Lapangan Cleaner</h2>
            <p class="font-body small mb-0" style="color: var(--color-body-text);">Kelola presensi harian dan dokumentasi foto pengerjaan kebersihan kos.</p>
        </div>
        <div class="text-md-end">
            <span class="small font-body d-block" style="color: var(--color-muted);"><?= date('l, d F Y') ?></span>
            <strong class="font-heading" style="color: var(--color-dark);"><i class="bi bi-person-circle me-1"></i> <?= e($_SESSION['nama_lengkap']) ?></strong>
        </div>
    </div>

    <!-- 1. WIDGET ABSENSI (CLOCK-IN & CLOCK-OUT) -->
    <div class="card card-custom p-4 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="fw-bold font-heading mb-1" style="color: var(--color-heading);"><i class="bi bi-clock-history me-2" style="color: var(--color-dark);"></i>Presensi Harian Cleaner</h5>
                <?php if ($todayAttendance): ?>
                    <p class="font-body small mb-0" style="color: var(--color-body-text);">
                        Status: <span class="badge badge-palette-slate">Hadir</span> &bull;
                        Jam Masuk: <strong style="color: var(--color-heading);"><?= e($todayAttendance['jam_masuk'] ?? '-') ?> WIB</strong> &bull;
                        Jam Keluar: <strong style="color: var(--color-heading);"><?= !empty($todayAttendance['jam_keluar']) ? e($todayAttendance['jam_keluar']) . ' WIB' : 'Belum Clock-Out' ?></strong>
                    </p>
                <?php else: ?>
                    <p class="font-body small mb-0" style="color: var(--color-body-text);">Anda belum melakukan presensi Clock-In untuk hari ini.</p>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2">
                <!-- Tombol Clock-In -->
                <?php if (!$todayAttendance): ?>
                    <button type="button" class="btn btn-brand-primary fw-bold px-4 py-2 shadow" data-bs-toggle="modal" data-bs-target="#modalScanWajahMasuk">
                        <i class="bi bi-camera-fill me-1"></i> Clock-In (Scan Wajah)
                    </button>
                <?php endif; ?>

                <!-- Tombol Clock-Out -->
                <?php if ($todayAttendance && empty($todayAttendance['jam_keluar'])): ?>
                    <button type="button" class="btn btn-outline-danger rounded-pill fw-bold px-4 py-2 shadow-sm font-heading" data-bs-toggle="modal" data-bs-target="#modalScanWajahKeluar">
                        <i class="bi bi-box-arrow-right me-1"></i> Clock-Out (Pulang)
                    </button>
                <?php elseif ($todayAttendance && !empty($todayAttendance['jam_keluar'])): ?>
                    <button class="btn btn-secondary rounded-pill px-4 py-2 font-heading" disabled>
                        <i class="bi bi-check-all me-1"></i> Presensi Selesai
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- 2. DAFTAR TUGAS LAPANGAN CLEANER -->
    <h4 class="font-heading mb-3" style="color: var(--color-heading);">
        <i class="bi bi-clipboard2-check-fill me-2" style="color: var(--color-dark);"></i>Daftar Tugas Kebersihan Anda
    </h4>

    <?php if (empty($myTasks)): ?>
        <div class="card card-custom p-5 text-center">
            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="background-color: var(--color-cream); color: var(--color-dark); width: 72px; height: 72px; border: 2px solid var(--color-sand);">
                <i class="bi bi-calendar-check fs-2"></i>
            </div>
            <h5 class="font-heading" style="color: var(--color-heading);">Belum Ada Tugas Ditugaskan</h5>
            <p class="font-body small mb-0" style="color: var(--color-body-text);">Admin belum menetapkan tugas pembersihan untuk akun Anda saat ini.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($myTasks as $task): ?>
                <div class="col-lg-6">
                    <div class="card card-custom h-100 p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-3" style="border-color: var(--color-sand) !important;">
                            <div>
                                <span class="badge badge-palette-cream small mb-1">Order #<?= e($task['id_pesanan']) ?></span>
                                <h5 class="font-heading mb-0" style="color: var(--color-heading);"><?= e($task['nama_layanan']) ?></h5>
                                <small class="font-body" style="color: var(--color-muted);"><i class="bi bi-person-fill"></i> Pelanggan: <?= e($task['nama_pelanggan']) ?> (<?= e($task['telp_pelanggan']) ?>)</small>
                            </div>

                            <!-- Badge Status Tugas -->
                            <?php
                                $badgeStyle = 'background-color: var(--color-sand); color: var(--color-dark);';
                                switch($task['status_tugas']) {
                                    case 'ditugaskan':   $badgeStyle = 'background-color: var(--color-cream); color: var(--color-dark); border: 1px solid var(--color-sand);'; break;
                                    case 'proses':       $badgeStyle = 'background-color: var(--color-slate-blue); color: #FFFFFF;'; break;
                                    case 'menunggu_qc':  $badgeStyle = 'background-color: #D97706; color: #FFFFFF;'; break;
                                    case 'selesai':      $badgeStyle = 'background-color: #2E7D32; color: #FFFFFF;'; break;
                                }
                            ?>
                            <span class="badge px-3 py-2 rounded-pill font-heading" style="<?= $badgeStyle ?>">
                                <?= ucfirst(str_replace('_', ' ', e($task['status_tugas']))) ?>
                            </span>
                        </div>

                        <!-- Info Lokasi & Jadwal -->
                        <div class="row g-2 small mb-3 font-body">
                            <div class="col-4" style="color: var(--color-muted);">Jadwal Tugas:</div>
                            <div class="col-8 fw-semibold" style="color: var(--color-heading);"><?= date('d M Y', strtotime($task['tanggal_jadwal'])) ?> - <?= substr($task['waktu_jadwal'], 0, 5) ?> WIB</div>

                            <div class="col-4" style="color: var(--color-muted);">Alamat Kos:</div>
                            <div class="col-8" style="color: var(--color-body-text);"><?= e($task['alamat_pembersihan']) ?></div>

                            <div class="col-4" style="color: var(--color-muted);">Patokan:</div>
                            <div class="col-8" style="color: var(--color-body-text);"><?= e($task['patokan_lokasi']) ?></div>
                        </div>

                        <!-- Galeri Foto QC yang sudah ada -->
                        <?php if (!empty($task['foto_sebelum']) || !empty($task['foto_sesudah'])): ?>
                            <div class="row g-2 mb-3 p-2 rounded-3" style="background-color: var(--color-cream); border: 1.5px solid var(--color-sand);">
                                <?php if (!empty($task['foto_sebelum'])): ?>
                                    <div class="col-6">
                                        <small class="d-block font-heading mb-1" style="color: var(--color-heading);">Foto SEBELUM:</small>
                                        <a href="<?= BASE_URL ?>/uploads/tasks/<?= e($task['foto_sebelum']) ?>" target="_blank">
                                            <img src="<?= BASE_URL ?>/uploads/tasks/<?= e($task['foto_sebelum']) ?>" class="img-fluid rounded-3 border" style="max-height: 120px; width: 100%; object-fit: cover;" alt="Foto Sebelum">
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($task['foto_sesudah'])): ?>
                                    <div class="col-6">
                                        <small class="d-block font-heading mb-1" style="color: var(--color-heading);">Foto SESUDAH:</small>
                                        <a href="<?= BASE_URL ?>/uploads/tasks/<?= e($task['foto_sesudah']) ?>" target="_blank">
                                            <img src="<?= BASE_URL ?>/uploads/tasks/<?= e($task['foto_sesudah']) ?>" class="img-fluid rounded-3 border" style="max-height: 120px; width: 100%; object-fit: cover;" alt="Foto Sesudah">
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- FLOW 1: TUGAS STATUS 'DITUGASKAN' -> WAJIB UPLOAD FOTO SEBELUM -->
                        <?php if ($task['status_tugas'] === 'ditugaskan'): ?>
                            <div class="mt-auto pt-3 border-top" style="border-color: var(--color-sand) !important;">
                                <form action="<?= BASE_URL ?>/karyawan/startTask/<?= $task['id_tugas'] ?>" method="POST" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <label class="form-label small fw-bold font-heading mb-1" style="color: var(--color-heading);">
                                        <i class="bi bi-camera-fill me-1" style="color: var(--color-dark);"></i>Mulai Tugas (Upload Foto SEBELUM) *
                                    </label>
                                    <div class="input-group mb-2">
                                        <input type="file" name="foto_sebelum" class="form-control form-control-sm" accept=".jpg,.jpeg,.png" required>
                                        <button type="submit" class="btn btn-brand-primary btn-sm px-3">
                                            Mulai
                                        </button>
                                    </div>
                                    <div class="font-body" style="font-size: 0.72rem; color: var(--color-muted);">Format: JPG, JPEG, PNG (Maks 2MB)</div>
                                </form>
                            </div>

                        <!-- FLOW 2: TUGAS STATUS 'PROSES' -> WAJIB UPLOAD FOTO SESUDAH (MENUNGGU QC) -->
                        <?php elseif ($task['status_tugas'] === 'proses'): ?>
                            <div class="mt-auto pt-3 border-top" style="border-color: var(--color-sand) !important;">
                                <form action="<?= BASE_URL ?>/karyawan/finishTask/<?= $task['id_tugas'] ?>" method="POST" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <label class="form-label small fw-bold font-heading mb-1" style="color: var(--color-dark);">
                                        <i class="bi bi-check2-circle me-1" style="color: var(--color-slate-blue);"></i>Selesaikan Tugas (Upload Foto SESUDAH) *
                                    </label>
                                    <div class="input-group mb-2">
                                        <input type="file" name="foto_sesudah" class="form-control form-control-sm" accept=".jpg,.jpeg,.png" required>
                                        <button type="submit" class="btn btn-brand-primary btn-sm px-3">
                                            Ajukan ke QC
                                        </button>
                                    </div>
                                    <div class="font-body" style="font-size: 0.72rem; color: var(--color-muted);">Format: JPG, JPEG, PNG (Maks 2MB)</div>
                                </form>
                            </div>

                        <!-- STATUS 'MENUNGGU_QC' -->
                        <?php elseif ($task['status_tugas'] === 'menunggu_qc'): ?>
                            <div class="mt-auto pt-3 border-top text-center p-2 rounded-3" style="background-color: var(--color-cream); border-color: var(--color-sand) !important;">
                                <span class="badge badge-palette-slate">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu Verifikasi QC Admin
                                </span>
                                <small class="d-block font-body mt-1" style="font-size: 0.75rem; color: var(--color-muted);">Foto hasil pengerjaan telah dikirim ke admin untuk validasi.</small>
                            </div>

                        <!-- STATUS 'SELESAI' -->
                        <?php else: ?>
                            <div class="mt-auto pt-3 border-top text-center p-2 rounded-3" style="background-color: var(--color-cream); border-color: var(--color-sand) !important;">
                                <span class="badge rounded-pill px-3 py-2 font-heading" style="background-color: #2E7D32; color: #FFFFFF;">
                                    <i class="bi bi-check-all me-1"></i> Tugas Selesai & Disetujui QC
                                </span>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Scan Wajah Masuk (Clock-In) -->
<div class="modal fade" id="modalScanWajahMasuk" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title font-heading fw-bold">
                    <i class="bi bi-camera-fill text-primary me-2"></i>Absensi Scan Wajah (Masuk)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="stopCamera('webcam-in')"></button>
            </div>
            <div class="modal-body text-center">
                <div class="position-relative d-inline-block rounded-4 overflow-hidden border shadow-sm mb-3" style="width: 320px; height: 240px; background: #000;">
                    <video id="webcam-in" width="320" height="240" autoplay playsinline style="object-fit: cover;"></video>
                    <canvas id="canvas-in" width="320" height="240" style="display:none;"></canvas>
                </div>
                <p class="font-body small text-muted mb-0">Posisikan wajah Anda tepat di depan kamera untuk verifikasi kehadiran.</p>
            </div>
            <div class="modal-footer border-top justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="stopCamera('webcam-in')">Batal</button>
                <form action="<?= BASE_URL ?>/karyawan/clockIn" method="POST" id="form-clock-in">
                    <?= csrf_field() ?>
                    <input type="hidden" name="foto_masuk_base64" id="foto_masuk_base64">
                    <button type="button" class="btn btn-brand-primary btn-sm fw-bold px-3" onclick="captureAndSubmit('webcam-in', 'canvas-in', 'foto_masuk_base64', 'form-clock-in')">
                        <i class="bi bi-camera me-1"></i> Jepret & Clock-In
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Scan Wajah Pulang (Clock-Out) -->
<div class="modal fade" id="modalScanWajahKeluar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-bottom">
                <h5 class="modal-title font-heading fw-bold">
                    <i class="bi bi-camera-fill text-danger me-2"></i>Absensi Scan Wajah (Pulang)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="stopCamera('webcam-out')"></button>
            </div>
            <div class="modal-body text-center">
                <div class="position-relative d-inline-block rounded-4 overflow-hidden border shadow-sm mb-3" style="width: 320px; height: 240px; background: #000;">
                    <video id="webcam-out" width="320" height="240" autoplay playsinline style="object-fit: cover;"></video>
                    <canvas id="canvas-out" width="320" height="240" style="display:none;"></canvas>
                </div>
                <p class="font-body small text-muted mb-0">Posisikan wajah Anda di depan kamera sebelum menyelesaikan shift.</p>
            </div>
            <div class="modal-footer border-top justify-content-between">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" onclick="stopCamera('webcam-out')">Batal</button>
                <form action="<?= BASE_URL ?>/karyawan/clockOut" method="POST" id="form-clock-out">
                    <?= csrf_field() ?>
                    <input type="hidden" name="foto_keluar_base64" id="foto_keluar_base64">
                    <button type="button" class="btn btn-outline-danger btn-sm fw-bold px-3" onclick="captureAndSubmit('webcam-out', 'canvas-out', 'foto_keluar_base64', 'form-clock-out')">
                        <i class="bi bi-camera me-1"></i> Jepret & Selesai Shift
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let currentStream = null;

function startCamera(videoElementId) {
    const video = document.getElementById(videoElementId);
    if (!video) return;

    navigator.mediaDevices.getUserMedia({ video: { width: 320, height: 240 }, audio: false })
        .then(stream => {
            currentStream = stream;
            video.srcObject = stream;
        })
        .catch(err => {
            alert('Akses kamera gagal: ' + err.message + '\nPastikan browser diizinkan mengakses webcam.');
        });
}

function stopCamera(videoElementId) {
    if (currentStream) {
        currentStream.getTracks().forEach(track => track.stop());
        currentStream = null;
    }
}

function captureAndSubmit(videoElementId, canvasElementId, inputHiddenId, formId) {
    const video = document.getElementById(videoElementId);
    const canvas = document.getElementById(canvasElementId);
    const context = canvas.getContext('2d');

    context.drawImage(video, 0, 0, 320, 240);
    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);

    document.getElementById(inputHiddenId).value = dataUrl;
    stopCamera(videoElementId);
    document.getElementById(formId).submit();
}

const modalIn = document.getElementById('modalScanWajahMasuk');
if (modalIn) {
    modalIn.addEventListener('shown.bs.modal', () => startCamera('webcam-in'));
    modalIn.addEventListener('hidden.bs.modal', () => stopCamera('webcam-in'));
}

const modalOut = document.getElementById('modalScanWajahKeluar');
if (modalOut) {
    modalOut.addEventListener('shown.bs.modal', () => startCamera('webcam-out'));
    modalOut.addEventListener('hidden.bs.modal', () => stopCamera('webcam-out'));
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
