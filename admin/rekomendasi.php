<?php
require_once 'auth/auth_check.php';
require_once '../config/databases.php';

// Logika untuk menangani Aksi (Create, Update, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah'])) {
        $stmt = $pdo->prepare("INSERT INTO rekomendasi (status_gizi_id, kondisi_tambahan_id, saran) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['status_gizi_id'], $_POST['kondisi_tambahan_id'] ?: null, $_POST['saran']]);
        header('Location: rekomendasi.php');
        exit;
    }
    if (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE rekomendasi SET status_gizi_id = ?, kondisi_tambahan_id = ?, saran = ? WHERE id = ?");
        $stmt->execute([$_POST['status_gizi_id'], $_POST['kondisi_tambahan_id'] ?: null, $_POST['saran'], $_POST['id']]);
        header('Location: rekomendasi.php');
        exit;
    }
    if (isset($_POST['hapus'])) {
        $stmt = $pdo->prepare("DELETE FROM rekomendasi WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        header('Location: rekomendasi.php');
        exit;
    }
}

$all_status = $pdo->query("SELECT id, nama_status FROM status_gizi ORDER BY kode_status ASC")->fetchAll();
$all_kondisi = $pdo->query("SELECT id, nama_kondisi FROM kondisi_tambahan ORDER BY id ASC")->fetchAll();

$stmt = $pdo->query("
    SELECT r.id, s.nama_status, r.saran, r.status_gizi_id, r.kondisi_tambahan_id, k.nama_kondisi
    FROM rekomendasi r
    JOIN status_gizi s ON r.status_gizi_id = s.id
    LEFT JOIN kondisi_tambahan k ON r.kondisi_tambahan_id = k.id
    ORDER BY s.kode_status ASC, k.nama_kondisi ASC
");
$rekomendasi_data = $stmt->fetchAll();

require_once 'templates/header.php';
?>

<div class="container-fluid bg-white p-4" style="min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Manajemen Rekomendasi</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Rekomendasi
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th style="width: 200px;">Status Gizi</th>
                            <th style="width: 180px;">Kondisi Tambahan</th>
                            <th>Saran/Rekomendasi</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rekomendasi_data)) : ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2">Tidak ada data</p>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($rekomendasi_data as $index => $rekomendasi) : ?>
                                <tr>
                                    <td class="text-center"><?php echo $index + 1; ?></td>
                                    <td><span
                                            class="badge bg-info"><?php echo htmlspecialchars($rekomendasi['nama_status']); ?></span>
                                    </td>
                                    <td>
                                        <?php if ($rekomendasi['nama_kondisi']) : ?>
                                            <span
                                                class="badge bg-warning text-dark"><?php echo htmlspecialchars($rekomendasi['nama_kondisi']); ?></span>
                                        <?php else : ?>
                                            <span class="text-muted fst-italic">Umum</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo nl2br(htmlspecialchars($rekomendasi['saran'])); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit" data-id="<?php echo $rekomendasi['id']; ?>"
                                                data-status="<?php echo $rekomendasi['status_gizi_id']; ?>"
                                                data-kondisi="<?php echo $rekomendasi['kondisi_tambahan_id'] ?? ''; ?>"
                                                data-saran="<?php echo htmlspecialchars($rekomendasi['saran']); ?>">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Yakin hapus rekomendasi ini?')) { document.getElementById('delete-form-<?php echo $rekomendasi['id']; ?>').submit(); }">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                        <form id="delete-form-<?php echo $rekomendasi['id']; ?>" action="rekomendasi.php"
                                            method="POST" style="display: none;">
                                            <input type="hidden" name="id" value="<?php echo $rekomendasi['id']; ?>">
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

<!-- Modal Tambah Rekomendasi -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Rekomendasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="rekomendasi.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status_gizi_id_tambah" class="form-label">Untuk Status Gizi <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="status_gizi_id_tambah" name="status_gizi_id" required>
                            <option value="">-- Pilih Status Gizi --</option>
                            <?php foreach ($all_status as $status) : ?>
                                <option value="<?php echo $status['id']; ?>">
                                    <?php echo htmlspecialchars($status['nama_status']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kondisi_tambahan_id_tambah" class="form-label">Kondisi Tambahan <small
                                class="text-muted">(Opsional)</small></label>
                        <select class="form-select" id="kondisi_tambahan_id_tambah" name="kondisi_tambahan_id">
                            <option value="">-- Rekomendasi Umum --</option>
                            <?php foreach ($all_kondisi as $kondisi) : ?>
                                <option value="<?php echo $kondisi['id']; ?>">
                                    <?php echo htmlspecialchars($kondisi['nama_kondisi']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="saran_tambah" class="form-label">Saran/Rekomendasi <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="saran_tambah" name="saran" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Rekomendasi -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEditLabel">Edit Rekomendasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="rekomendasi.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status_gizi_id_edit" class="form-label">Untuk Status Gizi <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="status_gizi_id_edit" name="status_gizi_id" required>
                            <option value="">-- Pilih Status Gizi --</option>
                            <?php foreach ($all_status as $status) : ?>
                                <option value="<?php echo $status['id']; ?>">
                                    <?php echo htmlspecialchars($status['nama_status']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kondisi_tambahan_id_edit" class="form-label">Kondisi Tambahan <small
                                class="text-muted">(Opsional)</small></label>
                        <select class="form-select" id="kondisi_tambahan_id_edit" name="kondisi_tambahan_id">
                            <option value="">-- Rekomendasi Umum --</option>
                            <?php foreach ($all_kondisi as $kondisi) : ?>
                                <option value="<?php echo $kondisi['id']; ?>">
                                    <?php echo htmlspecialchars($kondisi['nama_kondisi']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="saran_edit" class="form-label">Saran/Rekomendasi <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="saran_edit" name="saran" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const modalEdit = document.getElementById('modalEdit');
    modalEdit.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        document.getElementById('edit_id').value = button.getAttribute('data-id');
        document.getElementById('status_gizi_id_edit').value = button.getAttribute('data-status');
        document.getElementById('kondisi_tambahan_id_edit').value = button.getAttribute('data-kondisi');
        document.getElementById('saran_edit').value = button.getAttribute('data-saran');
    });
</script>

<?php
require_once 'templates/footer.php';
?>