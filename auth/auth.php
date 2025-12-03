<?php
session_start();
require_once __DIR__ . '/../config/databases.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error_message'] = "Token keamanan tidak valid!";
        header("Location: login.php");
        exit();
    }

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $_SESSION['error_message'] = "Username dan password tidak boleh kosong!";
        header("Location: login.php");
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_username'] = $user['username'];
            $_SESSION['user_role'] = $user['role']; // Tambahkan role ke session

            header("Location: ../dashboard.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Username atau password salah!";
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        die("Error saat query: " . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit();
}
