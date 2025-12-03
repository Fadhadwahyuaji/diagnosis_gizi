<?php

define('BASE_PATH', __DIR__ . '/..');

// Auto-detect BASE_URL berdasarkan folder proyek
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

// Dapatkan script name dan cari folder root proyek
$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);

// Ekstrak base folder - asumsi proyek ada di /diagnosis_gizi atau root
// Cari semua segment sebelum /admin, /superadmin, /auth, dll
if (preg_match('#^(.*?)/(admin|superadmin|auth|templates|config|middleware)/#', $scriptName, $matches)) {
    $baseFolder = $matches[1];
} elseif (preg_match('#^(/[^/]+)/#', $scriptName, $matches)) {
    // Jika tidak match, ambil folder pertama setelah domain
    $baseFolder = $matches[1];
} else {
    $baseFolder = '';
}

$baseUrl = rtrim($protocol . '://' . $host . $baseFolder, '/');

define('BASE_URL', $baseUrl);
