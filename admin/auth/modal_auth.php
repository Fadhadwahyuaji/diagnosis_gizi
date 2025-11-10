<?php
// File khusus untuk handle AJAX login dari modal
header('Content-Type: application/json');

// Selalu mulai session di awal
session_start();

// Panggil file koneksi database
require_once '../../config/databases.php';

// Hanya terima POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

// Cek apakah ini AJAX request
if (!isset($_POST['ajax_login'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

try {
    // Validasi CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid!']);
        exit();
    }

    // Validasi input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Cek apakah username dan password tidak kosong
    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username dan password tidak boleh kosong!']);
        exit();
    }

    // Cari user di database berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Verifikasi user dan password
    if ($user && password_verify($password, $user['password'])) {
        // Login berhasil - simpan ke session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_username'] = $user['username'];

        // Generate new CSRF token untuk security
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        echo json_encode([
            'success' => true,
            'message' => 'Login berhasil!',
            'redirect' => 'admin/dashboard.php'
        ]);
    } else {
        // Login gagal
        echo json_encode([
            'success' => false,
            'message' => 'Username atau password salah!'
        ]);
    }
} catch (PDOException $e) {
    // Error database
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
    ]);
    error_log("Database error: " . $e->getMessage());
} catch (Exception $e) {
    // Error umum lainnya
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan tak terduga.'
    ]);
    error_log("General error: " . $e->getMessage());
}
