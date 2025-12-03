<?php
session_start();

// Hapus semua session
$_SESSION = array();

// Hapus cookie session
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy session
session_destroy();

// Redirect ke halaman utama
header("Location: ../index.php");
exit();
