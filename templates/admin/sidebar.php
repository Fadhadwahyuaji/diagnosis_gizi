<?php
require_once __DIR__ . '/../../config/app.php';
// Ambil username dari session untuk ditampilkan
$username = $_SESSION['admin_username'] ?? 'Admin';
$role = $_SESSION['user_role'] ?? 'admin';
$isSuperAdmin = ($role === 'superadmin');
?>

<!-- Sidebar -->
<div class="sidebar bg-white shadow-sm border-end" id="sidebar">
    <div class="p-3 border-bottom">
        <div class="d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center me-2"
                style="width: 40px; height: 40px;">
                <i class="bi bi-heart-pulse me-2"></i>
            </div>
            <div>
                <h6 class="mb-0 text-dark fw-bold">
                    <?php echo $isSuperAdmin ? 'Super Admin' : 'Admin Panel'; ?>
                </h6>
                <small class="text-muted">Sistem Diagnosis Gizi</small>
            </div>
        </div>
    </div> <!-- Navigation Menu -->
    <nav class="nav flex-column p-3">
        <a class="nav-link d-flex align-items-center py-2 px-3 mb-1 rounded text-decoration-none text-dark sidebar-nav-link"
            href="/dashboard.php">
            <i class="bi bi-house-door me-3"></i>
            <span>Dashboard</span>
        </a>


        <?php if ($isSuperAdmin): ?>
            <!-- Menu khusus superadmin - HANYA KELOLA ADMIN -->
            <a class="nav-link d-flex align-items-center py-2 px-3 mb-1 rounded text-decoration-none text-dark sidebar-nav-link"
                href="/superadmin/kelola_admin.php">
                <i class="bi bi-people me-3"></i>
                <span>Kelola Admin</span>
            </a>
        <?php else: ?>
            <!-- Menu khusus admin biasa -->
            <a class="nav-link d-flex align-items-center py-2 px-3 mb-1 rounded text-decoration-none text-dark sidebar-nav-link"
                href="/admin/gejala.php">
                <i class="bi bi-list-check me-3"></i>
                <span>Kelola Gejala</span>
            </a>
            <a class="nav-link d-flex align-items-center py-2 px-3 mb-1 rounded text-decoration-none text-dark sidebar-nav-link"
                href="/admin/status_gizi.php"> <i class="bi bi-clipboard-data me-3"></i>
                <span>Status Gizi</span>
            </a>
            <a class="nav-link d-flex align-items-center py-2 px-3 mb-1 rounded text-decoration-none text-dark sidebar-nav-link"
                href="/admin/pengetahuan.php">
                <i class="bi bi-journal me-3"></i>
                <span>Pengetahuan</span>
            </a>
            <a class="nav-link d-flex align-items-center py-2 px-3 mb-1 rounded text-decoration-none text-dark sidebar-nav-link"
                href="/admin/rekomendasi.php">
                <i class="bi bi-file-earmark-text me-3"></i>
                <span>Rekomendasi</span>
            </a>
            <a class="nav-link d-flex align-items-center py-2 px-3 mb-1 rounded text-decoration-none text-dark sidebar-nav-link"
                href="/admin/riwayat.php">
                <i class="bi bi-clock-history me-3"></i>
                <span>Riwayat Diagnosis</span>
            </a>
            <a class="nav-link d-flex align-items-center py-2 px-3 mb-1 rounded text-decoration-none text-dark sidebar-nav-link"
                href="/admin/informasi.php">
                <i class="bi bi-info-circle me-3"></i>
                <span>Kelola Informasi</span>
            </a>
        <?php endif; ?>
    </nav>
</div>

<!-- Header/Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm fixed-top"
    style="margin-left: 250px; z-index: 1000;">
    <div class="container-fluid">
        <!-- Mobile menu toggle -->
        <button class="btn btn-outline-secondary d-lg-none me-2" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#sidebar">
            <i class="bi bi-list"></i>
        </button>

        <div class="navbar-brand d-lg-none">
            <span class="fw-bold text-primary">Diagnosis Gizi</span>
        </div>

        <!-- User info and logout (right side) -->
        <div class="ms-auto d-flex align-items-center">
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="d-flex align-items-center justify-content-center me-2"
                        style="width: 32px; height: 32px;">
                        <i class="bi bi-person text-black"></i>
                    </div>
                    <span class="me-1"><?php echo htmlspecialchars($username); ?></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <h6 class="dropdown-header">Login sebagai:</h6>
                    </li>
                    <li><span class="dropdown-item-text fw-bold">
                            <!-- <?php echo htmlspecialchars($username); ?> -->
                            <?php echo htmlspecialchars(ucfirst($role)); ?>
                        </span></li>                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger" href="<?php echo BASE_URL; ?>/auth/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 250px;
        z-index: 1000;
        overflow-y: auto;
    }

    .sidebar-nav-link {
        transition: all 0.3s ease;
    }

    .sidebar-nav-link:hover {
        background-color: #f8f9fa !important;
        color: #28a745 !important;
        transform: translateX(5px);
    }

    .sidebar-nav-link.active {
        background-color: #28a745 !important;
        color: white !important;
    }

    .bg-primary {
        background-color: #28a745 !important;
    }

    /* Main content adjustment */
    .main-content {
        margin-left: 250px;
        margin-top: 80px;
        /* Height of fixed navbar + spacing */
        padding: 30px;
        min-height: calc(100vh - 80px);
    }

    /* Content area wrapper */
    .content-area {
        margin-left: 0;
        transition: margin-left 0.3s ease;
    }

    /* Mobile responsive */
    @media (max-width: 991.98px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .main-content {
            margin-left: 0;
            margin-top: 80px;
            padding: 20px 15px;
        }

        .navbar {
            margin-left: 0 !important;
            width: 100% !important;
        }
    }

    /* Desktop - ensure content doesn't overlap */
    @media (min-width: 992px) {
        .navbar {
            margin-left: 250px;
            width: calc(100% - 250px);
        }
    }

    /* Scrollbar styling for sidebar */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>

<script>
    // JavaScript untuk mobile responsiveness
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 991.98) {
                if (!sidebar.contains(event.target) && !event.target.closest(
                        '[data-bs-target="#sidebar"]')) {
                    sidebar.classList.remove('show');
                }
            }
        });

        // Toggle sidebar on mobile
        document.querySelector('[data-bs-toggle="offcanvas"]')?.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });

        // Add active class to current page
        const currentPage = window.location.pathname.split('/').pop();
        const navLinks = document.querySelectorAll('.sidebar-nav-link');

        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentPage) {
                link.classList.add('active');
            }
        });
    });
</script>