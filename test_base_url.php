<?php
require_once __DIR__ . '/config/app.php';

echo "<h3>Debug BASE_URL</h3>";
echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<p><strong>BASE_PATH:</strong> " . BASE_PATH . "</p>";
echo "<hr>";
echo "<p><strong>HTTP_HOST:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<hr>";
echo "<p><strong>Expected logout URL:</strong> " . BASE_URL . "/auth/logout.php</p>";
