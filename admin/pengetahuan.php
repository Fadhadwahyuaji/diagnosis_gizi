<?php
require_once __DIR__ . '/../auth/auth_check.php';
require_once __DIR__ . '/../config/databases.php';
require_once __DIR__ . '/../middleware/role_guard.php';
requireAdmin();

// Logika untuk menangani Aksi (Create, Update, Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah'])) {
        $stmt = $pdo->prepare("INSERT INTO pengetahuan (gejala_id, status_gizi_id, cf_pakar) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['gejala_id'], $_POST['status_gizi_id'], $_POST['cf_pakar']]);
        header('Location: pengetahuan.php');
        exit;
    }
    if (isset($_POST['edit'])) {
        $stmt = $pdo->prepare("UPDATE pengetahuan SET gejala_id = ?, status_gizi_id = ?, cf_pakar = ? WHERE id = ?");
        $stmt->execute([$_POST['gejala_id'], $_POST['status_gizi_id'], $_POST['cf_pakar'], $_POST['id']]);
        header('Location: pengetahuan.php');
        exit;
    }
    if (isset($_POST['hapus'])) {
        $stmt = $pdo->prepare("DELETE FROM pengetahuan WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        header('Location: pengetahuan.php');
        exit;
    }
}

$all_gejala = $pdo->query("SELECT id, kode_gejala, nama_gejala FROM gejala ORDER BY kode_gejala ASC")->fetchAll();
$all_status = $pdo->query("SELECT id, nama_status FROM status_gizi ORDER BY kode_status ASC")->fetchAll();

$stmt = $pdo->query("
    SELECT p.id, g.kode_gejala, g.nama_gejala, s.nama_status, p.cf_pakar, p.gejala_id, p.status_gizi_id
    FROM pengetahuan p
    JOIN gejala g ON p.gejala_id = g.id
    JOIN status_gizi s ON p.status_gizi_id = s.id
    ORDER BY g.kode_gejala ASC
");
$rules_data = $stmt->fetchAll();

require_once __DIR__ . '/../templates/admin/header.php';
?>

<!-- Content wrapper with proper spacing -->
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark">Manajemen Pengetahuan (Rules)</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Pengetahuan
        </button>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">No</th>
                            <th style="width: 120px;">Kode Gejala</th>
                            <th>Nama Gejala</th>
                            <th style="width: 200px;">Target Status Gizi</th>
                            <th class="text-center" style="width: 100px;">Nilai CF</th>
                            <th class="text-center" style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rules_data)) : ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                    <p class="mt-2">Tidak ada data</p>
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($rules_data as $index => $rule) : ?>
                                <tr>
                                    <td class="text-center"><?php echo $index + 1; ?></td>
                                    <td><span
                                            class="badge bg-secondary"><?php echo htmlspecialchars($rule['kode_gejala']); ?></span>
                                    </td>
                                    <td><?php echo htmlspecialchars($rule['nama_gejala']); ?></td>
                                    <td><?php echo htmlspecialchars($rule['nama_status']); ?></td>
                                    <td class="text-center"><span
                                            class="badge bg-success"><?php echo htmlspecialchars($rule['cf_pakar']); ?></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit" data-id="<?php echo $rule['id']; ?>"
                                                data-gejala="<?php echo $rule['gejala_id']; ?>"
                                                data-status="<?php echo $rule['status_gizi_id']; ?>"
                                                data-cf="<?php echo $rule['cf_pakar']; ?>">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="if(confirm('Yakin hapus aturan ini?')) { document.getElementById('delete-form-<?php echo $rule['id']; ?>').submit(); }">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </div>
                                        <form id="delete-form-<?php echo $rule['id']; ?>" action="pengetahuan.php" method="POST"
                                            style="display: none;">
                                            <input type="hidden" name="id" value="<?php echo $rule['id']; ?>">
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

<!-- Modal Tambah Pengetahuan -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Aturan (Rule)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="pengetahuan.php" method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="gejala_id_tambah" class="form-label">JIKA Gejala <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="gejala_id_tambah" name="gejala_id" required>
                            <option value="">-- Pilih Gejala --</option>
                            <?php foreach ($all_gejala as $gejala) : ?>
                                <option value="<?php echo $gejala['id']; ?>">
                                    <?php echo htmlspecialchars($gejala['kode_gejala'] . ' - ' . $gejala['nama_gejala']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status_gizi_id_tambah" class="form-label">MAKA Status Gizi <span
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
                        <label for="cf_pakar_tambah" class="form-label">Dengan Nilai CF Pakar <span
                                class="text-danger">*</span></label>
                        <input type="number" step="0.01" max="1.0" min="-1.0" class="form-control" id="cf_pakar_tambah"
                            name="cf_pakar" value="0.0" required>
                        <small class="text-muted">Nilai antara -1.0 sampai 1.0</small>
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

<!-- Modal Edit Pengetahuan -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="modalEditLabel">Edit Aturan (Rule)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="pengetahuan.php" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="gejala_id_edit" class="form-label">JIKA Gejala <span
                                class="text-danger">*</span></label>
                        <select class="form-select" id="gejala_id_edit" name="gejala_id" required>
                            <option value="">-- Pilih Gejala --</option>
                            <?php foreach ($all_gejala as $gejala) : ?>
                                <option value="<?php echo $gejala['id']; ?>">
                                    <?php echo htmlspecialchars($gejala['kode_gejala'] . ' - ' . $gejala['nama_gejala']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status_gizi_id_edit" class="form-label">MAKA Status Gizi <span
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
                        <label for="cf_pakar_edit" class="form-label">Dengan Nilai CF Pakar <span
                                class="text-danger">*</span></label>
                        <input type="number" step="0.01" max="1.0" min="-1.0" class="form-control" id="cf_pakar_edit"
                            name="cf_pakar" required>
                        <small class="text-muted">Nilai antara -1.0 sampai 1.0</small>
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
        document.getElementById('gejala_id_edit').value = button.getAttribute('data-gejala');
        document.getElementById('status_gizi_id_edit').value = button.getAttribute('data-status');
        document.getElementById('cf_pakar_edit').value = button.getAttribute('data-cf');
    });
</script>

<?php
require_once __DIR__ . '/../templates/admin/footer.php';
?>