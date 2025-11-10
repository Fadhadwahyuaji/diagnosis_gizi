<?php
// Selalu mulai session di awal
session_start();

// Panggil file koneksi database
require_once '../../config/databases.php';

// Cek apakah data form sudah dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error_message'] = "Token keamanan tidak valid!";
        header("Location: login.php");
        exit();
    }

    // Validasi input
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Cek apakah username dan password tidak kosong
    if (empty($username) || empty($password)) {
        $_SESSION['error_message'] = "Username dan password tidak boleh kosong!";
        header("Location: login.php");
        exit();
    }

    // 1. Cari user di database berdasarkan username
    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // 2. Verifikasi user dan password
        // Cek apakah user ditemukan DAN password cocok dengan hash di database
        if ($user && password_verify($password, $user['password'])) {

            // Jika berhasil login:
            // a. Simpan informasi user ke session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username'];

            // b. Redirect ke halaman dashboard
            header("Location: ../dashboard.php");
            exit();
        } else {
            // Jika username atau password salah:
            // a. Buat pesan error
            $_SESSION['error_message'] = "Username atau password salah!";

            // b. Redirect kembali ke halaman login
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        // Tangani error database jika terjadi
        die("Error saat query: " . $e->getMessage());
    }
} else {
    // Jika file diakses langsung tanpa metode POST, redirect ke halaman login
    header("Location: login.php");
    exit();
}
