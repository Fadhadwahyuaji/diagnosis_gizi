<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/databases.php';
require_once __DIR__ . '/../middleware/role_guard.php';
requireAdmin();

// Logika untuk menangani Aksi (Create, Update, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aksi Tambah
    if (isset($_POST['tambah'])) {
        $stmt = $pdo->prepare("INSERT INTO status_gizi (kode_status, nama_status, deskripsi) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['kode_status'], $_POST['nama_status'], $_POST['deskripsi']]);
        header('Location: status_gizi.php');
        exit;
    }
    // Aksi Update
    if (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE status_gizi SET kode_status = ?, nama_status = ?, deskripsi = ? WHERE id = ?");
        $stmt->execute([$_POST['kode_status'], $_POST['nama_status'], $_POST['deskripsi'], $_POST['id']]);
        header('Location: status_gizi.php');
        exit;
    }
    // Aksi Hapus
    if (isset($_POST['hapus'])) {
        $stmt = $pdo->prepare("DELETE FROM status_gizi WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        header('Location: status_gizi.php');
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM status_gizi ORDER BY kode_status ASC");
$status_data = $stmt->fetchAll();

require_once __DIR__ . '/../templates/admin/header.php';
?>

<!-- Content wrapper with proper spacing -->
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Manajemen Data Status Gizi</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Status
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th style="width: 120px;">Kode</th>
                            <th style="width: 250px;">Nama Status</th>
                            <th>Deskripsi</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($status_data)) : ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2">Tidak ada data</p>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($status_data as $index => $status) : ?>
                                <tr>
                                    <td class="text-center"><?php echo $index + 1; ?></td>
                                    <td><span
                                            class="badge bg-info"><?php echo htmlspecialchars($status['kode_status']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($status['nama_status']); ?></td>
                                    <td><?php echo htmlspecialchars($status['deskripsi']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit" data-id="<?php echo $status['id']; ?>"
                                                data-kode="<?php echo htmlspecialchars($status['kode_status']); ?>"
                                                data-nama="<?php echo htmlspecialchars($status['nama_status']); ?>"
                                                data-deskripsi="<?php echo htmlspecialchars($status['deskripsi']); ?>">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Apakah Anda yakin ingin menghapus data ini?')) { document.getElementById('delete-form-<?php echo $status['id']; ?>').submit(); }">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                        <form id="delete-form-<?php echo $status['id']; ?>" action="status_gizi.php"
                                            method="POST" style="display: none;">
                                            <input type="hidden" name="id" value="<?php echo $status['id']; ?>">
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

<!-- Modal Tambah Status -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Status Gizi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="status_gizi.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_status_tambah" class="form-label">Kode Status <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_status_tambah" name="kode_status" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_status_tambah" class="form-label">Nama Status <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_status_tambah" name="nama_status" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_tambah" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi_tambah" name="deskripsi" rows="3"></textarea>
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

<!-- Modal Edit Status -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEditLabel">Edit Status Gizi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="status_gizi.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_status_edit" class="form-label">Kode Status <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_status_edit" name="kode_status" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_status_edit" class="form-label">Nama Status <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_status_edit" name="nama_status" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_edit" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi_edit" name="deskripsi" rows="3"></textarea>
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
        document.getElementById('kode_status_edit').value = button.getAttribute('data-kode');
        document.getElementById('nama_status_edit').value = button.getAttribute('data-nama');
        document.getElementById('deskripsi_edit').value = button.getAttribute('data-deskripsi');
    });
</script>

<?php
require_once __DIR__ . '/../templates/admin/footer.php';
?>