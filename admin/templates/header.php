<?php
// Ambil username dari session untuk ditampilkan
$username = $_SESSION['admin_username'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
    .sidebar {
        min-height: 100vh;
        background-color: #343a40;
    }

    .sidebar .nav-link {
        color: #adb5bd;
        padding: 0.75rem 1rem;
        border-radius: 0.25rem;
        margin: 0.25rem 0;
    }

    .sidebar .nav-link:hover {
        color: #fff;
        background-color: #495057;
    }

    .sidebar .nav-link.active {
        color: #fff;
        background-color: #28a745;
    }

    .content-area {
        margin-left: 0;
        transition: margin-left 0.25s ease;
    }

    @media (min-width: 768px) {
        .content-area {
            margin-left: 250px;
        }
    }

    .sidebar {
        position: fixed;
        top: 0;
        left: -250px;
        width: 250px;
        z-index: 1000;
        transition: left 0.25s ease;
    }

    @media (min-width: 768px) {
        .sidebar {
            left: 0;
        }
    }

    .sidebar.show {
        left: 0;
    }
    </style>
</head>

<body>
    <?php
    // Include sidebar
    require_once 'templates/sidebar.php';
    ?>

    <!-- Content Area -->
    <div class="content-area">
        <!-- Top Navigation (Mobile) -->
        <nav class="navbar navbar-dark bg-dark d-md-none">
            <div class="container-fluid">
                <button class="navbar-toggler border-0" type="button" onclick="toggleSidebar()">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <span class="navbar-brand mb-0 h1">Admin Panel</span>
            </div>
        </nav>

        <main class="container-fluid p-4">