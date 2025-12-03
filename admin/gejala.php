<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/databases.php';
require_once __DIR__ . '/../middleware/role_guard.php';
requireAdmin();

// Logika untuk menangani Aksi (Create, Update, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Aksi Tambah Gejala
    if (isset($_POST['tambah'])) {
        $stmt = $pdo->prepare("INSERT INTO gejala (kode_gejala, nama_gejala) VALUES (?, ?)");
        $stmt->execute([$_POST['kode_gejala'], $_POST['nama_gejala']]);
        header('Location: gejala.php');
        exit;
    }
    // Aksi Update Gejala
    if (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE gejala SET kode_gejala = ?, nama_gejala = ? WHERE id = ?");
        $stmt->execute([$_POST['kode_gejala'], $_POST['nama_gejala'], $_POST['id']]);
        header('Location: gejala.php');
        exit;
    }
    // Aksi Hapus Gejala
    if (isset($_POST['hapus'])) {
        $stmt = $pdo->prepare("DELETE FROM gejala WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        header('Location: gejala.php');
        exit;
    }
}

$stmt = $pdo->query("SELECT * FROM gejala ORDER BY kode_gejala ASC");
$gejala_data = $stmt->fetchAll();

require_once __DIR__ . '/../templates/admin/header.php';
?>

<!-- Content wrapper with proper spacing -->
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Manajemen Data Gejala</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Gejala
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
                            <th>Nama Gejala/Parameter</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($gejala_data)) : ?>
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2">Tidak ada data</p>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($gejala_data as $index => $gejala) : ?>
                                <tr>
                                    <td class="text-center"><?php echo $index + 1; ?></td>
                                    <td><span
                                            class="badge bg-secondary"><?php echo htmlspecialchars($gejala['kode_gejala']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($gejala['nama_gejala']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit" data-id="<?php echo $gejala['id']; ?>"
                                                data-kode="<?php echo htmlspecialchars($gejala['kode_gejala']); ?>"
                                                data-nama="<?php echo htmlspecialchars($gejala['nama_gejala']); ?>">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Apakah Anda yakin ingin menghapus data ini?')) { document.getElementById('delete-form-<?php echo $gejala['id']; ?>').submit(); }">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                        <form id="delete-form-<?php echo $gejala['id']; ?>" action="gejala.php" method="POST"
                                            style="display: none;">
                                            <input type="hidden" name="id" value="<?php echo $gejala['id']; ?>">
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

<!-- Modal Tambah Gejala -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Gejala</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="gejala.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_gejala_tambah" class="form-label">Kode Gejala <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_gejala_tambah" name="kode_gejala" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_gejala_tambah" class="form-label">Nama Gejala <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_gejala_tambah" name="nama_gejala" required>
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

<!-- Modal Edit Gejala -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEditLabel">Edit Gejala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="gejala.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_gejala_edit" class="form-label">Kode Gejala <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="kode_gejala_edit" name="kode_gejala" required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_gejala_edit" class="form-label">Nama Gejala <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_gejala_edit" name="nama_gejala" required>
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
    // Script untuk mengisi data ke modal edit
    const modalEdit = document.getElementById('modalEdit');
    modalEdit.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const kode = button.getAttribute('data-kode');
        const nama = button.getAttribute('data-nama');

        document.getElementById('edit_id').value = id;
        document.getElementById('kode_gejala_edit').value = kode;
        document.getElementById('nama_gejala_edit').value = nama;
    });
</script>

<?php
require_once __DIR__ . '/../templates/admin/footer.php';
?>