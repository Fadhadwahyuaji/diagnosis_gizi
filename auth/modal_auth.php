<?php
header('Content-Type: application/json');
session_start();
require_once __DIR__ . '/../config/databases.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

if (!isset($_POST['ajax_login'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit();
}

try {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'Token keamanan tidak valid!']);
        exit();
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username dan password tidak boleh kosong!']);
        exit();
    }

    $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_role'] = $user['role']; // Tambahkan role
        $_SESSION['username'] = $user['username']; // Untuk kompatibilitas
        $_SESSION['role'] = $user['role'];

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        echo json_encode([
            'success' => true,
            'message' => 'Login berhasil!',
            'redirect' => 'dashboard.php'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Username atau password salah!'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'
    ]);
    error_log("Database error: " . $e->getMessage());
}
