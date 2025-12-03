<?php
// File untuk mengecek apakah user sudah login atau belum
session_start();
require_once __DIR__ . '/../config/app.php';

// Jika session user_id tidak ada, berarti belum login
if (!isset($_SESSION['user_id'])) {
    // Simpan pesan error
    $_SESSION['error_message'] = "Anda harus login terlebih dahulu untuk mengakses halaman ini.";

    // Redirect ke halaman login
    header("Location: index.php");
    exit();
}

$user_role = $_SESSION['user_role'] ?? 'admin';
$user_name = $_SESSION['user_username'] ?? 'Admin';

// Optional: Cek apakah user masih ada di database (untuk keamanan ekstra)
try {
    require_once BASE_PATH . '/config/databases.php';
    $stmt = $pdo->prepare("SELECT id FROM user WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);

    if (!$stmt->fetch()) {
        // user tidak ditemukan di database, hapus session
        session_destroy();
        session_start();
        $_SESSION['error_message'] = "Session tidak valid, silakan login kembali.";
        header("Location: index.php");
        exit();
    }
    // Set session admin_logged_in agar middleware bisa mendeteksi login
    $_SESSION['admin_logged_in'] = true;
} catch (PDOException $e) {
    // Jika ada error database, abaikan saja (tidak perlu logout paksa)
    error_log("Database error: " . $e->getMessage());
}

// Jika sampai di sini, berarti user sudah login dan bisa melanjutkan