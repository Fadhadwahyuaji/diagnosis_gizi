<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/databases.php';
require_once __DIR__ . '/../middleware/role_guard.php';
requireAdmin();

// Logika untuk Aksi (Create, Update, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah'])) {
        $stmt = $pdo->prepare("INSERT INTO halaman_informasi (judul, konten, urutan) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['judul'], $_POST['konten'], $_POST['urutan']]);
        header('Location: informasi.php');
        exit;
    }
    if (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE halaman_informasi SET judul = ?, konten = ?, urutan = ? WHERE id = ?");
        $stmt->execute([$_POST['judul'], $_POST['konten'], $_POST['urutan'], $_POST['id']]);
        header('Location: informasi.php');
        exit;
    }
    if (isset($_POST['hapus'])) {
        $stmt = $pdo->prepare("DELETE FROM halaman_informasi WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        header('Location: informasi.php');
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM halaman_informasi ORDER BY urutan ASC");
$info_data = $stmt->fetchAll();

require_once __DIR__ . '/../templates/admin/header.php';
?>

<!-- Content wrapper with proper spacing -->
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Manajemen Halaman Informasi</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Konten
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 100px;">Urutan</th>
                            <th>Judul</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($info_data)) : ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2">Tidak ada data</p>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($info_data as $info) : ?>
                                <tr>
                                    <td class="text-center"><span
                                            class="badge bg-secondary"><?php echo htmlspecialchars($info['urutan']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($info['judul']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit" data-id="<?php echo $info['id']; ?>"
                                                data-judul="<?php echo htmlspecialchars($info['judul']); ?>"
                                                data-konten="<?php echo htmlspecialchars($info['konten']); ?>"
                                                data-urutan="<?php echo $info['urutan']; ?>">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Yakin hapus konten ini?')) { document.getElementById('delete-form-<?php echo $info['id']; ?>').submit(); }">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                        <form id="delete-form-<?php echo $info['id']; ?>" action="informasi.php" method="POST"
                                            style="display: none;">
                                            <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
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

<!-- Modal Tambah Informasi -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Konten Informasi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="informasi.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul_tambah" class="form-label">Judul Bagian <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="judul_tambah" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="konten_tambah" class="form-label">Isi Konten (Bisa menggunakan HTML) <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="konten_tambah" name="konten" rows="10" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="urutan_tambah" class="form-label">Urutan Tampil <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="urutan_tambah" name="urutan" value="0" required>
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

<!-- Modal Edit Informasi -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEditLabel">Edit Konten Informasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="informasi.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="judul_edit" class="form-label">Judul Bagian <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="judul_edit" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="konten_edit" class="form-label">Isi Konten (Bisa menggunakan HTML) <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="konten_edit" name="konten" rows="10" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="urutan_edit" class="form-label">Urutan Tampil <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="urutan_edit" name="urutan" required>
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
        document.getElementById('judul_edit').value = button.getAttribute('data-judul');
        document.getElementById('konten_edit').value = button.getAttribute('data-konten');
        document.getElementById('urutan_edit').value = button.getAttribute('data-urutan');
    });
</script>

<?php require_once __DIR__ . '/../templates/admin/footer.php'; ?>