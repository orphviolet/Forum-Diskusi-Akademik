<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | EduConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin_dashboard.css'); ?>">
</head>
<body>
    <div class="wrapper">
        <!-- ===================== NAVBAR ===================== -->
        <nav class="topbar">
            <div class="logo-section">
                <button type="button" class="menu-toggle" id="menuToggle" aria-label="Buka menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>
                <img src="<?= base_url('assets/uploads/ikon.png'); ?>" alt="Logo">
                <div>
                    <h4>EduConnect</h4>
                    <span>Forum Diskusi Akademik</span>
                </div>
            </div>
            <div class="admin-profile">
                <i class="bi bi-person-circle"></i>
            </div>
        </nav>

        <!-- ===================== CONTENT ===================== -->
        <div class="main-container">

            <!-- Overlay, muncul di belakang sidebar saat mode mobile terbuka -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            <!-- ===================== SIDEBAR ===================== -->
            <aside class="sidebar" id="sidebar">
                <a href="<?= base_url('admin'); ?>" class="sidebar-item <?= ($this->router->fetch_method()=='index') ? 'active' : ''; ?>">
                    <div class="icon-box">
                        <i class="bi bi-grid-fill"></i>
                    </div>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('admin/students'); ?>" class="sidebar-item <?= ($this->router->fetch_method()=='students') ? 'active' : ''; ?>">
                    <div class="icon-box">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <span>Growth Pengguna</span>
                </a>
                <a href="<?= base_url('admin/category'); ?>"
   class="sidebar-item <?= ($this->router->fetch_method()=='category') ? 'active' : ''; ?>">
                    <div class="icon-box">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <span>Kelola Kategori</span>
                </a>
                <a href="<?= base_url('admin/reward'); ?>"
   class="sidebar-item <?= ($this->router->fetch_method()=='reward') ? 'active' : ''; ?>">
                    <div class="icon-box">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <span>Kelola Reward</span>
                </a>
                <a href="<?= base_url('admin/verification'); ?>"
   class="sidebar-item <?= ($this->router->fetch_method()=='verification') ? 'active' : ''; ?>">
                    <div class="icon-box">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <span>Verifikasi Jawaban</span>
                </a>
                <div class="sidebar-bottom">
                    <a href="<?= base_url('auth/logout'); ?>" class="sidebar-item logout">
                        <div class="icon-box logout-icon">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>

            <!-- ===================== MAIN ===================== -->
            <main class="content-area">
                <!-- ===================== WELCOME ===================== -->
                <div class="welcome-card">
                    <div class="welcome-avatar">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="welcome-text">
                        <h2>Halo, <?= htmlspecialchars($admin['nama']); ?> 👋</h2>
                        <p>Anda berhasil login sebagai administrator EduConnect.</p>
                    </div>
                </div>

                <!-- ===================== STATISTIK ===================== -->
                <div class="row g-4 mt-2">
                    <div class="col-6 col-md-6">
                        <div class="stat-card">
                            <div class="stat-top"></div>
                            <div class="stat-body">
                                <div>
                                    <h6>Pengguna</h6>
                                    <h2><?= $total_siswa; ?></h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="stat-card">
                            <div class="stat-top"></div>
                            <div class="stat-body">
                                <div>
                                    <h6>Kategori Mapel</h6>
                                    <h2><?= $total_kategori; ?></h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-book-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="stat-card">
                            <div class="stat-top"></div>
                            <div class="stat-body">
                                <div>
                                    <h6>Diskusi</h6>
                                    <h2><?= $total_diskusi; ?></h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-chat-left-text-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-6">
                        <div class="stat-card">
                            <div class="stat-top"></div>
                            <div class="stat-body">
                                <div>
                                    <h6>Reward</h6>
                                    <h2><?= $total_reward; ?></h2>
                                </div>
                                <div class="stat-icon">
                                    <i class="bi bi-award-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.btn-edit').forEach(function(button) {
            button.addEventListener('click', function() {
                let id = this.dataset.id;
                let nama = this.dataset.kategori;
                document.getElementById('editKategori').value = nama;
                document.getElementById('formEditKategori').action = "<?= base_url('admin/edit_category/'); ?>" + id;
            });
        });

        // ===================== MOBILE SIDEBAR TOGGLE =====================
        (function () {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                sidebar.classList.add('is-open');
                overlay.classList.add('is-open');
                menuToggle.setAttribute('aria-expanded', 'true');
            }

            function closeSidebar() {
                sidebar.classList.remove('is-open');
                overlay.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
            }

            menuToggle.addEventListener('click', function () {
                sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar();
            });

            overlay.addEventListener('click', closeSidebar);

            // Tutup sidebar otomatis saat salah satu menu diklik (mode mobile)
            sidebar.querySelectorAll('.sidebar-item').forEach(function (item) {
                item.addEventListener('click', function () {
                    if (window.innerWidth <= 992) {
                        closeSidebar();
                    }
                });
            });

            // Tutup sidebar saat layar di-resize kembali ke ukuran desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth > 992) {
                    closeSidebar();
                }
            });
        })();
    </script>
</body>
</html>