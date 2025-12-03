<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/databases.php';
require_once __DIR__ . '/../middleware/role_guard.php';
requireAdmin();

// Logika untuk menangani Aksi (Hanya Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hapus'])) {
        $stmt = $pdo->prepare("DELETE FROM riwayat_diagnosa WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        header('Location: riwayat.php');
        exit;
    }
}

$stmt = $pdo->query("
    SELECT r.id, r.waktu, r.nama_lengkap, s.nama_status, r.hasil_cf, r.rekomendasi_diberikan
    FROM riwayat_diagnosa r
    JOIN status_gizi s ON r.hasil_status_gizi_id = s.id
    ORDER BY r.waktu DESC
");
$riwayat_data = $stmt->fetchAll();

require_once __DIR__ . '/../templates/admin/header.php';
?>

<!-- Content wrapper with proper spacing -->
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Riwayat Diagnosa Pengguna</h2>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th style="width: 150px;">Waktu Diagnosa</th>
                            <th style="width: 150px;">Nama Lengkap</th>
                            <th style="width: 150px;">Hasil Status Gizi</th>
                            <th class="text-center" style="width: 120px;">Nilai Keyakinan (CF)</th>
                            <th>Rekomendasi Diberikan</th>
                            <th class="text-center" style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($riwayat_data)) : ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2">Belum ada riwayat diagnosa</p>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($riwayat_data as $index => $riwayat) : ?>
                                <tr>
                                    <td class="text-center"><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars(date('d M Y, H:i', strtotime($riwayat['waktu']))); ?></td>
                                    <td><span class=""><?php echo htmlspecialchars($riwayat['nama_lengkap']); ?></span>
                                    </td>
                                    <td><span
                                            class="badge bg-info"><?php echo htmlspecialchars($riwayat['nama_status']); ?></span>
                                    </td>
                                    <td class="text-center"><span
                                            class="badge bg-success"><?php echo htmlspecialchars(number_format($riwayat['hasil_cf'] * 100, 2)) . '%'; ?></span>
                                    </td>
                                    <td><?php echo nl2br(htmlspecialchars($riwayat['rekomendasi_diberikan'])); ?></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="if(confirm('Yakin hapus riwayat ini?')) { document.getElementById('delete-form-<?php echo $riwayat['id']; ?>').submit(); }">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                        <form id="delete-form-<?php echo $riwayat['id']; ?>" action="riwayat.php" method="POST"
                                            style="display: none;">
                                            <input type="hidden" name="id" value="<?php echo $riwayat['id']; ?>">
                                            <input type="hidden" name="hapus" value="1">
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End content wrapper -->

<?php
require_once __DIR__ . '/../templates/admin/footer.php';
?>