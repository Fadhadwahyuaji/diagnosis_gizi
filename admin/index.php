<?php
session_start();

// Cek apakah user sudah login
if (isset($_SESSION['user_id'])) {
    // Jika sudah login, redirect ke dashboard
    header("Location: ../dashboard.php");
} else {
    // Jika belum login, redirect ke halaman login
    header("Location: ../index.php");
}
exit();
