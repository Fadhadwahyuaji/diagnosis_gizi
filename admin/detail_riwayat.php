<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/databases.php';
require_once __DIR__ . '/../middleware/role_guard.php';
requireAdmin();

// Ambil ID dari parameter GET
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: riwayat.php');
    exit;
}

// Ambil data riwayat
$stmt = $pdo->prepare("
    SELECT r.*, s.nama_status
    FROM riwayat_diagnosa r
    JOIN status_gizi s ON r.hasil_status_gizi_id = s.id
    WHERE r.id = ?
");
$stmt->execute([$id]);
$riwayat = $stmt->fetch();

if (!$riwayat) {
    header('Location: riwayat.php');
    exit;
}

// Parse data JSON jika ada
$gejala_list = [];
if (!empty($riwayat['data_gejala'])) {
    $gejala_list = json_decode($riwayat['data_gejala'], true) ?? [];
}

$bb_ideal = 0;
if (!empty($riwayat['tinggi_badan'])) {
    $bb_ideal = ($riwayat['tinggi_badan'] - 100) * 0.9;
}

require_once __DIR__ . '/../templates/admin/header.php';
?>

<!-- Content wrapper with proper spacing -->
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">
            <i class="bi bi-clipboard-data"></i> Detail Riwayat Diagnosa
        </h2>
        <a href="riwayat.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">
                <i class="bi bi-person-circle"></i>
                Diagnosa: <?php echo htmlspecialchars($riwayat['nama_lengkap']); ?>
            </h4>
            <small>Waktu Diagnosa: <?php echo date('d F Y, H:i', strtotime($riwayat['waktu'])); ?> WIB</small>
        </div>
        <div class="card-body">
            <!-- Info Pasien -->
            <div class="alert alert-light border mb-4">
                <h5 class="mb-3"><i class="bi bi-person-badge"></i> Informasi Pasien</h5>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong><i class="bi bi-person"></i> Nama Lengkap:</strong>
                                <?php echo htmlspecialchars($riwayat['nama_lengkap']); ?>
                            </li>
                            <li class="mb-2">
                                <strong><i class="bi bi-arrows-vertical"></i> Tinggi Badan:</strong>
                                <?php echo !empty($riwayat['tinggi_badan']) ? number_format($riwayat['tinggi_badan'], 1) . ' cm' : '-'; ?>
                            </li>
                            <li class="mb-2">
                                <strong><i class="bi bi-speedometer2"></i> Berat Badan:</strong>
                                <?php echo !empty($riwayat['berat_badan']) ? number_format($riwayat['berat_badan'], 1) . ' kg' : '-'; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <strong><i class="bi bi-calculator"></i> IMT:</strong>
                                <?php echo !empty($riwayat['imt']) ? number_format($riwayat['imt'], 2) . ' kg/m²' : '-'; ?>
                            </li>
                            <li class="mb-2">
                                <strong><i class="bi bi-heart-pulse"></i> Berat Badan Ideal:</strong>
                                <?php echo $bb_ideal > 0 ? number_format($bb_ideal, 1) . ' kg' : '-'; ?>
                            </li>
                            <li class="mb-2">
                                <strong><i class="bi bi-calendar-event"></i> Waktu Diagnosa:</strong>
                                <?php echo date('d F Y, H:i', strtotime($riwayat['waktu'])); ?> WIB
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <!-- Hasil Diagnosa -->
            <h5 class="mb-3"><i class="bi bi-graph-up"></i> Hasil Diagnosa</h5>
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Status Gizi</h6>
                            <h3 class="mb-0">
                                <span class="badge bg-primary fs-5">
                                    <?php echo htmlspecialchars($riwayat['nama_status']); ?>
                                </span>
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h6 class="text-muted mb-2">Tingkat Keyakinan (CF)</h6>
                            <h3 class="mb-0">
                                <span class="badge bg-success fs-5">
                                    <?php echo number_format($riwayat['hasil_cf'] * 100, 2); ?>%
                                </span>
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (!empty($gejala_list)) : ?>
                <hr class="my-4">

                <!-- Gejala yang Terdeteksi -->
                <h5 class="mb-3"><i class="bi bi-clipboard-pulse"></i> Gejala yang Terdeteksi</h5>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;" class="text-center">No</th>
                                <th>Nama Gejala</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($gejala_list as $index => $gejala) : ?>
                                <tr>
                                    <td class="text-center"><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($gejala); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <hr class="my-4">

            <!-- Rekomendasi -->
            <h5 class="mb-3"><i class="bi bi-heart-pulse"></i> Rekomendasi yang Diberikan</h5>
            <div class="alert alert-info">
                <div style="white-space: pre-line;"><?php echo htmlspecialchars($riwayat['rekomendasi_diberikan']); ?>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="text-center mt-4">
                <a href="riwayat.php" class="btn btn-secondary btn-lg me-2">
                    <i class="bi bi-arrow-left-circle"></i> Kembali ke Daftar
                </a>
                <button onclick="window.print()" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-printer"></i> Cetak Detail
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Style untuk print -->
<style>
    @media print {

        .btn,
        .sidebar,
        .navbar {
            display: none !important;
        }

        .container-fluid {
            padding: 0 !important;
        }
    }
</style>

<?php
require_once __DIR__ . '/../templates/admin/footer.php';
?>