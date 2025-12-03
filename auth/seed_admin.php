<?php
// Gunakan path absolut berbasis direktori file ini
require_once __DIR__ . '/../config/databases.php';

// Data users yang akan di-seed
$users = [
    [
        'username' => 'superadmin',
        'password' => 'super123',
        'role' => 'superadmin'
    ],
    [
        'username' => 'admin5',
        'password' => 'admin123',
        'role' => 'admin'
    ]
];

foreach ($users as $user) {
    // Cek duplikasi
    $check = $pdo->prepare("SELECT id FROM user WHERE username = ?");
    $check->execute([$user['username']]);

    if ($check->fetch()) {
        echo "Username sudah ada: {$user['username']}\n";
        continue;
    }

    // Hash password
    $hash = password_hash($user['password'], PASSWORD_DEFAULT);

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO user (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute([$user['username'], $hash, $user['role']]);

    echo "User dibuat: {$user['username']} / {$user['password']} (role: {$user['role']})\n";
}

echo "\nSeeding selesai!";
