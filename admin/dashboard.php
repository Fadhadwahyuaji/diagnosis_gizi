<?php
// Langkah 1: Panggil file pengecekan login
require_once 'auth/auth_check.php';

// Langkah 2: Panggil file header
require_once 'templates/header.php';
?>

<div class="p-5 mb-4 bg-light rounded-3">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Selamat Datang di Admin Panel!</h1>
        <p class="col-md-8 fs-4">
            Anda telah berhasil login. Dari sini, Anda dapat mengelola data gejala, status gizi,
            aturan sistem pakar, dan melihat riwayat diagnosis.
        </p>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-list-check display-4 text-primary"></i>
                <h5 class="card-title mt-2">Gejala</h5>
                <p class="card-text">Kelola data gejala</p>
                <a href="gejala.php" class="btn btn-primary">Kelola</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-clipboard-data display-4 text-success"></i>
                <h5 class="card-title mt-2">Status Gizi</h5>
                <p class="card-text">Kelola status gizi</p>
                <a href="status_gizi.php" class="btn btn-success">Kelola</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-gear display-4 text-warning"></i>
                <h5 class="card-title mt-2">Aturan</h5>
                <p class="card-text">Kelola aturan sistem</p>
                <a href="aturan.php" class="btn btn-warning">Kelola</a>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-clock-history display-4 text-info"></i>
                <h5 class="card-title mt-2">Riwayat</h5>
                <p class="card-text">Lihat riwayat diagnosis</p>
                <a href="riwayat.php" class="btn btn-info">Lihat</a>
            </div>
        </div>
    </div>
</div>

<?php
// Langkah 3: Panggil file footer
require_once 'templates/footer.php';
?>