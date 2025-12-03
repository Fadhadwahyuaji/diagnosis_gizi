<?php
require_once __DIR__ . '/templates/umum/header.php';
require_once __DIR__ . '/config/databases.php';

// Ambil semua konten informasi dari database, diurutkan berdasarkan kolom 'urutan'
$stmt = $pdo->query("SELECT * FROM halaman_informasi ORDER BY urutan ASC");
$informasi = $stmt->fetchAll();
?>

<?php if (empty($informasi)) : ?>
    <div class="alert alert-warning">Konten informasi belum tersedia.</div>
<?php else : ?>
    <?php foreach ($informasi as $item) : ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo htmlspecialchars($item['judul']); ?></h5>
            </div>
            <div class="card-body">
                <?php
                // Tampilkan konten langsung. Ini memungkinkan admin menyimpan HTML di database.
                // Pastikan hanya admin yang bisa dipercaya yang mengisi konten ini untuk menghindari risiko keamanan.
                echo $item['konten'];
                ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php require_once 'templates/umum/footer.php'; ?>