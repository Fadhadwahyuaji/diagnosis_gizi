    </main>

    <footer class="footer mt-auto py-4" style="background: #ffffff; border-top: 1px solid #e9ecef; color: #6c757d;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-md-start mb-2 mb-md-0">
                        <i class="bi bi-heart-pulse text-success me-2"></i>
                        <span class="fw-semibold">Gizi Ideal</span>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <!-- <div class="d-flex justify-content-center justify-content-md-end align-items-center flex-wrap">
                        <a href="informasi.php" class="me-3 mb-1"
                            style="color: #28a745; text-decoration: none; transition: color 0.3s ease;">
                            <small><i class="bi bi-info-circle me-1"></i>Informasi</small>
                        </a>
                        <a href="panduan.php" class="me-3 mb-1"
                            style="color: #28a745; text-decoration: none; transition: color 0.3s ease;">
                            <small><i class="bi bi-book me-1"></i>Panduan</small>
                        </a>
                        <a href="#" class="mb-1"
                            style="color: #28a745; text-decoration: none; transition: color 0.3s ease;">
                            <small><i class="bi bi-envelope me-1"></i>Kontak</small>
                        </a>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            Dibuat dengan <i class="bi bi-heart-fill text-danger"></i> untuk kesehatan Anda
                        </small>
                    </div> -->
                    <small class="text-muted">
                        &copy; <?php echo date('Y'); ?> Sistem Pakar Status Gizi Ideal.
                        <br class="d-md-none">
                        Semua hak dilindungi.
                    </small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
// Toggle password visibility
document.getElementById('togglePassword')?.addEventListener('click', function() {
    const password = document.getElementById('userPassword');
    const icon = this.querySelector('i');

    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
});

// Handle AJAX login form
document.getElementById('userLoginForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const loginBtn = document.getElementById('loginBtn');
    const loginAlert = document.getElementById('loginAlert');
    const loginAlertMessage = document.getElementById('loginAlertMessage');

    // Disable button dan ubah text
    loginBtn.disabled = true;
    loginBtn.innerHTML = '<i class="spinner-border spinner-border-sm me-2"></i>Loading...';

    // Hide alert
    loginAlert.classList.add('d-none');

    fetch('admin/auth/modal_auth.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Login berhasil
                loginAlertMessage.textContent = 'Login berhasil! Redirecting...';
                loginAlert.classList.remove('alert-danger');
                loginAlert.classList.add('alert-success');
                loginAlert.classList.remove('d-none');

                // Redirect setelah 1 detik
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1000);
            } else {
                // Login gagal
                loginAlertMessage.textContent = data.message || 'Username atau password salah!';
                loginAlert.classList.remove('alert-success');
                loginAlert.classList.add('alert-danger');
                loginAlert.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            loginAlertMessage.textContent = 'Terjadi kesalahan sistem!';
            loginAlert.classList.remove('alert-success');
            loginAlert.classList.add('alert-danger');
            loginAlert.classList.remove('d-none');
        })
        .finally(() => {
            // Re-enable button
            loginBtn.disabled = false;
            loginBtn.innerHTML = '<i class="bi bi-box-arrow-in-right me-2"></i>Login';
        });
});

// Auto close navbar on mobile when clicking a link
document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 992) {
            const navbarCollapse = document.getElementById('navbarNav');
            const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                toggle: false
            });
            bsCollapse.hide();
        }
    });
});

// Add active class to current page nav link
document.addEventListener('DOMContentLoaded', function() {
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.php')) {
            link.classList.add('active');
        }
    });
});

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Footer link hover effects
document.querySelectorAll('footer a').forEach(link => {
    link.addEventListener('mouseenter', function() {
        this.style.color = '#20c997';
    });

    link.addEventListener('mouseleave', function() {
        this.style.color = '#28a745';
    });
});
    </script>
    </body>

    </html>