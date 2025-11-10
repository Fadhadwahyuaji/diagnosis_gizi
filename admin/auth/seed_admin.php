<?php
// Gunakan path absolut berbasis direktori file ini
require_once __DIR__ . '/../../config/databases.php';

$username = 'admin5';
$plain = 'admin123';
$hash = password_hash($plain, PASSWORD_DEFAULT);

// Cek duplikasi
$check = $pdo->prepare("SELECT id FROM admin WHERE username = ?");
$check->execute([$username]);
if ($check->fetch()) {
    exit("Username sudah ada: $username");
}

$stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
$stmt->execute([$username, $hash]);

echo "Admin dibuat: $username / $plain";
