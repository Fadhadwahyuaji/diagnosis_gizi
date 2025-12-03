<?php
// Selalu mulai session di awal untuk mengakses data hasil
// session_start();

// Panggil header
require_once 'templates/umum/header.php';
require_once 'config/databases.php';

// Cek apakah ada hasil diagnosa di session atau parameter ID
$hasil = null;

if (isset($_SESSION['hasil_diagnosa'])) {
    // Ambil dari session jika ada
    $hasil = $_SESSION['hasil_diagnosa'];
} elseif (isset($_GET['id'])) {
    // Jika tidak ada di session, coba ambil dari database berdasarkan ID
    $id = (int)$_GET['id'];

    // Verifikasi bahwa ID ini ada di riwayat pengguna
    if (isset($_SESSION['riwayat_pengguna']) && in_array($id, $_SESSION['riwayat_pengguna'])) {
        try {
            $stmt = $pdo->prepare("
                SELECT r.*, s.nama_status
                FROM riwayat_diagnosa r
                JOIN status_gizi s ON r.hasil_status_gizi_id = s.id
                WHERE r.id = ?
            ");
            $stmt->execute([$id]);
            $riwayat = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($riwayat) {
                // Rekonstruksi array hasil dari database
                $gejala_list = json_decode($riwayat['data_gejala'], true) ?? [];

                // Parse rekomendasi dari text
                $rekomendasi_list = [];
                $rekomendasi_parts = explode("\n\n", $riwayat['rekomendasi_diberikan']);
                foreach ($rekomendasi_parts as $part) {
                    if (strpos($part, '===') !== false) {
                        $lines = explode("\n", $part);
                        $judul = trim(str_replace('===', '', $lines[0]));
                        $isi = implode("\n", array_slice($lines, 1));
                        $rekomendasi_list[] = [
                            'judul' => $judul,
                            'isi' => trim($isi),
                            'icon' => 'clipboard-heart'
                        ];
                    }
                }

                $hasil = [
                    'id' => $riwayat['id'],
                    'nama_lengkap' => $riwayat['nama_lengkap'],
                    'berat_badan' => $riwayat['berat_badan'],
                    'tinggi_badan' => $riwayat['tinggi_badan'],
                    'imt' => $riwayat['imt'],
                    'status' => $riwayat['nama_status'],
                    'cf' => $riwayat['hasil_cf'],
                    'gejala_terdeteksi' => $gejala_list,
                    'rekomendasi_list' => $rekomendasi_list,
                ];
            }
        } catch (PDOException $e) {
            error_log("Error: " . $e->getMessage());
        }
    }
}

// Jika tidak ada hasil, redirect ke diagnosis
if (!$hasil) {
    header('Location: diagnosis.php');
    exit();
}

$bb_ideal = 0;
if (isset($hasil['tinggi_badan'])) {
    $bb_ideal = ($hasil['tinggi_badan'] - 100) * 0.9;
}

?>

<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0"><i class="bi bi-clipboard-check"></i> Hasil Diagnosa Status Gizi</h3>
        <p class="mb-0 mt-2">Atas Nama: <strong><?php echo htmlspecialchars($hasil['nama_lengkap']); ?></strong></p>
    </div>
    <div class="card-body">
        <!-- Info Pasien -->
        <div class="alert alert-light border">
            <h5 class="mb-3"><i class="bi bi-person-circle"></i> Informasi Pasien</h5>
            <div class="row">
                <div class="col-md-6">
                    <ul class="list-unstyled mb-0">
                        <li><strong>Nama Lengkap:</strong> <?php echo htmlspecialchars($hasil['nama_lengkap']); ?></li>
                        <li><strong>Berat Badan:</strong> <?php echo number_format($hasil['berat_badan'], 1); ?> kg</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <ul class="list-unstyled mb-0">
                        <li><strong>Tinggi Badan:</strong> <?php echo number_format($hasil['tinggi_badan'], 1); ?> cm
                        </li>
                        <li><strong>Berat Badan Ideal:</strong> <?php echo number_format($bb_ideal, 1); ?> kg</li>
                    </ul>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <!-- Hasil Diagnosa -->
        <h4 class="card-title"><i class="bi bi-graph-up"></i> Hasil Diagnosa</h4>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <strong>IMT (Indeks Massa Tubuh):</strong>
                <span class="badge bg-info text-dark fs-6"><?php echo number_format($hasil['imt'], 2); ?> kg/m²</span>
            </li>
            <li class="list-group-item">
                <strong>Kategori Status Gizi:</strong>
                <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($hasil['status']); ?></span>
            </li>
            <li class="list-group-item">
                <strong>Berat Badan Ideal:</strong>
                <span class="fw-bold"><?php echo number_format($bb_ideal, 1); ?> kg</span>
            </li>
            <li class="list-group-item">
                <strong>Tingkat Keyakinan Sistem (CF):</strong>
                <span class="badge bg-success fs-6"><?php echo number_format($hasil['cf'] * 100, 2); ?>%</span>
            </li>
        </ul>

        <hr class="my-4">

        <!-- Tabel Klasifikasi IMT -->
        <h4 class="card-title"><i class="bi bi-table"></i> Tabel Klasifikasi Massa Tubuh (IMT)</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th>Rentang IMT (kg/m²)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr <?php echo ($hasil['imt'] < 18.5) ? 'class="table-warning"' : ''; ?>>
                        <td>Gizi Kurang (Underweight)</td>
                        <td>&lt; 18,5</td>
                    </tr>
                    <tr <?php echo ($hasil['imt'] >= 18.5 && $hasil['imt'] <= 24.9) ? 'class="table-success"' : ''; ?>>
                        <td>Normal</td>
                        <td>18,5 – 24,9</td>
                    </tr>
                    <tr <?php echo ($hasil['imt'] >= 25.0 && $hasil['imt'] <= 29.9) ? 'class="table-warning"' : ''; ?>>
                        <td>Gizi Lebih (Overweight)</td>
                        <td>25,0 – 29,9</td>
                    </tr>
                    <tr <?php echo ($hasil['imt'] >= 30.0) ? 'class="table-danger"' : ''; ?>>
                        <td>Obesitas</td>
                        <td>≥ 30,0</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <hr class="my-4">

        <!-- Kesimpulan -->
        <div class="alert alert-info">
            <h4 class="alert-heading"><i class="bi bi-info-circle"></i> Kesimpulan</h4>
            <p class="mb-0">
                Berdasarkan perhitungan menggunakan metode <strong>Certainty Factor (CF)</strong>,
                status gizi Anda termasuk dalam kategori
                <strong class="text-primary"><?php echo htmlspecialchars($hasil['status']); ?></strong>
                dengan tingkat keyakinan sistem sebesar
                <strong class="text-success"><?php echo number_format($hasil['cf'] * 100, 2); ?>%</strong>.
            </p>
        </div>

        <hr class="my-4">

        <!-- Rekomendasi Nutrisi -->
        <h4 class="card-title"><i class="bi bi-heart-pulse"></i> Rekomendasi Nutrisi & Gaya Hidup</h4>

        <?php if (isset($hasil['rekomendasi_list']) && !empty($hasil['rekomendasi_list'])): ?>
            <?php foreach ($hasil['rekomendasi_list'] as $index => $rekomendasi): ?>
                <div class="card mb-3 border-start border-primary border-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <?php if ($index === 0): ?>
                                <i class="bi bi-clipboard-heart text-primary"></i>
                            <?php else: ?>
                                <i class="bi bi-exclamation-triangle text-warning"></i>
                            <?php endif; ?>
                            <?php echo htmlspecialchars($rekomendasi['judul']); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0" style="white-space: pre-line;"><?php echo htmlspecialchars($rekomendasi['isi']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> Tidak ada rekomendasi yang tersedia untuk kondisi Anda.
            </div>
        <?php endif; ?>

        <hr class="my-4"> <!-- Tombol Aksi -->
        <div class="text-center">
            <a href="diagnosis.php" class="btn btn-primary btn-lg me-2">
                <i class="bi bi-arrow-left-circle"></i> Diagnosis Ulang
            </a>
            <a href="riwayat_saya.php" class="btn btn-outline-info btn-lg me-2">
                <i class="bi bi-clock-history"></i> Lihat Riwayat
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-printer"></i> Cetak Hasil
            </button>
        </div>
    </div>

    <div class="card-footer text-muted">
        <i class="bi bi-info-circle"></i>
        <strong>Catatan Penting:</strong>
        Hasil diagnosis ini bersifat edukatif dan tidak menggantikan konsultasi dengan tenaga kesehatan profesional.
        Untuk penanganan lebih lanjut, silakan berkonsultasi dengan dokter atau ahli gizi.
    </div>
</div>

<!-- Style untuk print -->
<style>
    @media print {

        .btn,
        .card-footer {
            display: none !important;
        }
    }
</style>

<?php
// Panggil footer
require_once 'templates/umum/footer.php';
?>