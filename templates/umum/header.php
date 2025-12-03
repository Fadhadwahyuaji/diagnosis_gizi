<?php session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$isUserLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['user_role']);

// Ambil data user jika sudah login
$user_name = $_SESSION['user_username'] ?? 'User';
$user_role = $_SESSION['user_role'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pakar Status Gizi Ideal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">


    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="bi bi-heart-pulse me-2"></i>
                Gizi Ideal
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="bi bi-house me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="diagnosis.php">
                            <i class="bi bi-clipboard-pulse me-1"></i>Mulai Diagnosis
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link " href="informasi.php" role="button" aria-expanded="false">
                            <i class="bi bi-info-circle me-1"></i>
                            Informasi
                        </a>
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-info-circle me-1"></i>Informasi
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="informasi.php">
                                    <i class="bi bi-file-text me-2"></i>Umum
                                </a>
                            </li>
                        </ul>
                    </li> -->
                </ul>

                <div class="d-flex align-items-center">
                    <?php if ($isUserLoggedIn): ?>
                    <!-- Dropdown untuk user yang sudah login -->
                    <div class="dropdown">
                        <button class="btn btn-dashboard dropdown-toggle" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-speedometer2 me-1"></i>Dashboard
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="dashboard.php">
                                    <i class="bi bi-grid me-2"></i>Dashboard
                                </a>
                            </li>
                            <!-- <li>
                                <a class="dropdown-item" href="admin/users.php">
                                    <i class="bi bi-people me-2"></i>Kelola User
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="admin/settings.php">
                                    <i class="bi bi-gear me-2"></i>Pengaturan
                                </a>
                            </li> -->
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="auth/logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php else: ?>
                    <!-- Button login untuk admin -->
                    <button class="btn btn-login" type="button" data-bs-toggle="modal" data-bs-target="#loginModal">
                        <i class="bi bi-person-lock me-1"></i>Login
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Modal Login user -->
    <?php if (!$isUserLoggedIn): ?>
    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header text-white">
                    <h1 class="modal-title fs-5" id="loginModalLabel">
                        <i class="bi bi-shield-lock me-2"></i>Login
                    </h1>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Alert untuk pesan error/sukses -->
                    <div id="loginAlert" class="alert alert-danger d-none" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <span id="loginAlertMessage"></span>
                    </div>

                    <form id="userLoginForm" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="ajax_login" value="1">

                        <div class="mb-3">
                            <label for="userUsername" class="form-label fw-semibold">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="userUsername" name="username" required
                                    placeholder="Masukkan username user">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="userPassword" class="form-label fw-semibold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control" id="userPassword" name="password" required
                                    placeholder="Masukkan password">
                                <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" id="loginBtn">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <main class="container flex-grow-1 mt-4 mb-4">