<?php
// Langkah 1: Panggil file pengecekan login
require_once __DIR__ . '/auth/auth_check.php';

// Langkah 2: Panggil file header
require_once __DIR__ . '/templates/admin/header.php';
?>

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Selamat Datang di Admin Panel!</h1>
        <p class="col-md-8 fs-4">
            <?php if ($user_role === 'superadmin'): ?>
            Anda login sebagai Super Admin. Anda memiliki akses penuh untuk mengelola seluruh sistem.
            <?php else: ?>
            Anda telah berhasil login. Dari sini, Anda dapat mengelola data gejala, status gizi,
            aturan sistem pakar, dan melihat riwayat diagnosis.
            <?php endif; ?>
        </p>
    </div>
</div>

<div class="row">
    <?php if ($user_role === 'superadmin'): ?>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-person display-4 text-info"></i>
                <h5 class="card-title mt-2">Kelola User</h5>
                <p class="card-text">Lihat daftar user</p>
                <a href="superadmin/kelola_admin.php" class="btn btn-info">Lihat</a>
            </div>
        </div>
    </div>

    <?php else: ?>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body"> <i class="bi bi-list-check display-4 text-primary"></i>
                <h5 class="card-title mt-2">Gejala</h5>
                <p class="card-text">Kelola data gejala</p>
                <a href="admin/gejala.php" class="btn btn-primary">Kelola</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body"> <i class="bi bi-clipboard-data display-4 text-success"></i>
                <h5 class="card-title mt-2">Status Gizi</h5>
                <p class="card-text">Kelola status gizi</p>
                <a href="admin/status_gizi.php" class="btn btn-success">Kelola</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body"> <i class="bi bi-gear display-4 text-warning"></i>
                <h5 class="card-title mt-2">Aturan</h5>
                <p class="card-text">Kelola aturan sistem</p>
                <a href="admin/pengetahuan.php" class="btn btn-warning">Kelola</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body"> <i class="bi bi-clock-history display-4 text-info"></i>
                <h5 class="card-title mt-2">Riwayat</h5>
                <p class="card-text">Lihat riwayat diagnosis</p>
                <a href="admin/riwayat.php" class="btn btn-info">Lihat</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php
// Langkah 3: Panggil file footer
require_once BASE_PATH . '/templates/admin/footer.php';
?>