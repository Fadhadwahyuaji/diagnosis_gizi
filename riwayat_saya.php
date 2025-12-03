<?php
// Halaman riwayat diagnosa untuk pengguna umum (tanpa login)
require_once 'templates/umum/header.php';
require_once 'config/databases.php';

// Cek apakah ada riwayat di session
$riwayat_ids = $_SESSION['riwayat_pengguna'] ?? [];

$riwayat_data = [];

if (!empty($riwayat_ids)) {
    try {
        // Ambil data riwayat dari database
        $placeholders = implode(',', array_fill(0, count($riwayat_ids), '?'));
        $stmt = $pdo->prepare("
            SELECT r.id, r.waktu, r.nama_lengkap, s.nama_status, r.hasil_cf, r.imt, r.berat_badan, r.tinggi_badan
            FROM riwayat_diagnosa r
            JOIN status_gizi s ON r.hasil_status_gizi_id = s.id
            WHERE r.id IN ($placeholders)
            ORDER BY r.waktu DESC
        ");
        $stmt->execute($riwayat_ids);
        $riwayat_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error: " . $e->getMessage());
    }
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="text-dark">
                        <i class="bi bi-clock-history"></i> Riwayat Diagnosa Saya
                    </h2>
                    <p class="text-muted">Lihat hasil diagnosa yang pernah Anda lakukan</p>
                </div>
                <a href="diagnosis.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Diagnosa Baru
                </a>
            </div>

            <?php if (empty($riwayat_data)): ?>
            <!-- Jika belum ada riwayat -->
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 4rem; color: #ccc;"></i>
                    <h4 class="mt-3 text-muted">Belum Ada Riwayat Diagnosa</h4>
                    <p class="text-muted">Anda belum pernah melakukan diagnosa. Mulai diagnosa pertama Anda sekarang!</p>
                    <a href="diagnosis.php" class="btn btn-primary mt-3">
                        <i class="bi bi-clipboard-pulse"></i> Mulai Diagnosa
                    </a>
                </div>
            </div>
            <?php else: ?>
            <!-- Tabel Riwayat -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>Catatan:</strong> Riwayat diagnosa akan tersimpan selama sesi browser Anda aktif. 
                        Jika Anda menutup browser, riwayat akan hilang.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 60px;">No</th>
                                    <th style="width: 180px;">Waktu Diagnosa</th>
                                    <th style="width: 200px;">Nama Lengkap</th>
                                    <th class="text-center" style="width: 120px;">BB / TB</th>
                                    <th class="text-center" style="width: 100px;">IMT</th>
                                    <th style="width: 180px;">Status Gizi</th>
                                    <th class="text-center" style="width: 120px;">CF</th>
                                    <th class="text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($riwayat_data as $index => $riwayat): ?>
                                <tr>
                                    <td class="text-center"><?php echo $index + 1; ?></td>
                                    <td>
                                        <small>
                                            <i class="bi bi-calendar3"></i>
                                            <?php echo date('d M Y', strtotime($riwayat['waktu'])); ?>
                                            <br>
                                            <i class="bi bi-clock"></i>
                                            <?php echo date('H:i', strtotime($riwayat['waktu'])); ?> WIB
                                        </small>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($riwayat['nama_lengkap']); ?></strong>
                                    </td>
                                    <td class="text-center">
                                        <small>
                                            <?php echo number_format($riwayat['berat_badan'], 1); ?> kg /
                                            <?php echo number_format($riwayat['tinggi_badan'], 0); ?> cm
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">
                                            <?php echo number_format($riwayat['imt'], 2); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            <?php echo htmlspecialchars($riwayat['nama_status']); ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            <?php echo number_format($riwayat['hasil_cf'] * 100, 2); ?>%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="hasil_diagnosis.php?id=<?php echo $riwayat['id']; ?>" 
                                           class="btn btn-sm btn-primary"
                                           title="Lihat Detail">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 text-end">
                        <small class="text-muted">
                            Total: <?php echo count($riwayat_data); ?> riwayat diagnosa
                        </small>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
require_once 'templates/umum/footer.php';
?>
