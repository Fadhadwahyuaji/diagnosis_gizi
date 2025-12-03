<?php
require_once __DIR__ . '/config/app.php';

echo "<h3>Debug Informasi URL</h3>";
echo "<p><strong>BASE_PATH:</strong> " . BASE_PATH . "</p>";
echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<hr>";
echo "<p><strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>HTTP_HOST:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
echo "<hr>";
echo "<h4>Contoh URL yang Dihasilkan:</h4>";
echo "<ul>";
echo "<li>Dashboard: <a href='" . BASE_URL . "/dashboard.php'>" . BASE_URL . "/dashboard.php</a></li>";
echo "<li>Gejala: <a href='" . BASE_URL . "/admin/gejala.php'>" . BASE_URL . "/admin/gejala.php</a></li>";
echo "<li>Status Gizi: <a href='" . BASE_URL . "/admin/status_gizi.php'>" . BASE_URL . "/admin/status_gizi.php</a></li>";
echo "</ul>";
