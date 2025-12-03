<?php
// Middleware untuk memastikan hanya role tertentu yang bisa akses halaman

function requireRole($allowedRoles)
{
    // Pastikan session sudah dimulai
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Cek apakah user sudah login
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: /dashboard.php');
        exit;
    }

    $userRole = $_SESSION['user_role'] ?? 'admin';

    // Konversi ke array jika string
    if (!is_array($allowedRoles)) {
        $allowedRoles = [$allowedRoles];
    }

    // Cek apakah role user diizinkan
    if (!in_array($userRole, $allowedRoles)) {
        // Redirect ke dashboard dengan pesan error
        $_SESSION['error_message'] = 'Anda tidak memiliki akses ke halaman tersebut!';
        header('Location: /dashboard.php');
        exit;
    }
}

function requireAdmin()
{
    requireRole('admin'); // Hanya admin
}

function requireSuperAdmin()
{
    requireRole('superadmin'); // Hanya superadmin
}
