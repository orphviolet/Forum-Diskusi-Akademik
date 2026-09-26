<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | EduConnect</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css'); ?>">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid px-3 px-md-4">

            <!-- Tombol hamburger untuk buka sidebar dropdown di HP -->
            <button type="button" class="sidebar-toggle-btn d-lg-none" id="sidebarToggleBtn" aria-label="Buka menu">
                <i class="bi bi-list"></i>
            </button>

            <a href="<?= base_url('dashboard'); ?>" class="navbar-brand d-flex align-items-center text-decoration-none">
                <img src="<?= base_url('assets/uploads/ikon.png'); ?>" class="logo-image" alt="EduConnect">
                <div class="ms-2 brand-text-wrap">
                    <div class="brand-title">EduConnect</div>
                    <div class="brand-subtitle">Forum Diskusi Akademik</div>
                </div>
            </a>

            <div class="ms-auto d-flex align-items-center navbar-actions">
                <a href="<?= base_url('notification'); ?>" class="notification-link position-relative">
                    <i class="bi bi-bell-fill"></i>
                    <?php if (isset($unread_notifications) && $unread_notifications > 0): ?>
                        <span class="notification-badge"><?= $unread_notifications; ?></span>
                    <?php endif; ?>
                </a>

                <form action="<?= base_url('dashboard'); ?>" method="GET" class="search-form">
                    <input type="text" name="search" class="form-control search-input" placeholder="Cari diskusi..." value="<?= isset($search_keyword) ? htmlspecialchars($search_keyword, ENT_QUOTES, 'UTF-8') : ''; ?>">
                    <button class="search-button">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <!-- Ikon search khusus mobile, buka form pencarian sebagai overlay -->
                <button type="button" class="search-icon-mobile d-lg-none" id="searchToggleBtn" aria-label="Cari">
                    <i class="bi bi-search"></i>
                </button>

                <a href="<?= base_url('user/profile'); ?>" class="profile-link">
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= base_url('assets/uploads/avatars/' . $user['avatar']); ?>" class="avatar-circle">
                    <?php else: ?>
                        <i class="bi bi-person-circle avatar-placeholder-icon"></i>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <!-- Search form versi mobile (muncul sebagai dropdown penuh saat ikon search ditekan) -->
        <form action="<?= base_url('dashboard'); ?>" method="GET" class="search-form-mobile d-lg-none" id="searchFormMobile">
            <input type="text" name="search" class="form-control search-input-mobile" placeholder="Cari diskusi..." value="<?= isset($search_keyword) ? htmlspecialchars($search_keyword, ENT_QUOTES, 'UTF-8') : ''; ?>">
            <button class="search-button-mobile">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </nav>

    <!-- Overlay gelap saat sidebar dropdown terbuka di HP -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="container-fluid">
        <div class="row">

            <aside class="col-lg-2 col-md-3 sidebar-container" id="sidebarContainer">
                <div class="sidebar-menu">
                    <?php
                    $dashboard = ($this->uri->segment(1) == 'dashboard' && $this->uri->segment(2) == '') ? 'active' : '';
                    ?>
                    <a href="<?= base_url('dashboard'); ?>" class="nav-link-custom <?= $dashboard; ?>">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?= base_url('dashboard/categories_list'); ?>" class="nav-link-custom">
                        <i class="bi bi-book-half"></i>
                        <span>Kategori Mapel</span>
                    </a>
                    <a href="<?= base_url('dashboard/history'); ?>" class="nav-link-custom">
                        <i class="bi bi-clock-history"></i>
                        <span>Riwayat Saya</span>
                    </a>
                    <a href="<?= base_url('dashboard/achievement'); ?>" class="nav-link-custom">
                        <i class="bi bi-award-fill"></i>
                        <span>Pencapaian</span>
                    </a>
                </div>

                <div class="sidebar-bottom">
                    <a href="<?= base_url('auth/logout'); ?>" class="nav-link-custom logout-link">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>

            <main class="col-lg-10 col-md-9 main-content-area">

                <div class="welcome-card mb-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <div class="d-flex align-items-center">
                                <?php if (!empty($user['avatar'])): ?>
                                    <img src="<?= base_url('assets/uploads/avatars/' . $user['avatar']); ?>" class="profile-avatar" alt="Avatar">
                                <?php else: ?>
                                    <i class="bi bi-person-circle profile-avatar-placeholder"></i>
                                <?php endif; ?>
                                <div class="ms-4">
                                    <h3 class="welcome-title">
                                        Halo, <?= explode(' ', $user['nama'])[0]; ?> 👋
                                    </h3>
                                    <p class="welcome-text">
                                        Selamat datang kembali di <strong>EduConnect</strong>. Teruslah berdiskusi, berbagi pengetahuan, dan kumpulkan poin untuk mendapatkan berbagai reward menarik.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 text-end d-none d-lg-block">
                            <i class="bi bi-chat-heart-fill welcome-icon"></i>
                        </div>
                    </div>
                </div>

                <div class="row g-2 g-md-4 mb-4">
                    <div class="col-4">
                        <div class="stat-card">
                            <div class="stat-top bg-primary-gradient"></div>
                            <div class="stat-body">
                                <div>
                                    <small>Pengguna</small>
                                    <h2><?= $counts['total_users']; ?></h2>
                                </div>
                                <div class="stat-icon icon-blue">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="stat-card">
                            <div class="stat-top bg-info-gradient"></div>
                            <div class="stat-body">
                                <div>
                                    <small>Diskusi</small>
                                    <h2><?= $counts['total_threads']; ?></h2>
                                </div>
                                <div class="stat-icon icon-sky">
                                    <i class="bi bi-chat-left-text-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="stat-card">
                            <div class="stat-top bg-soft-gradient"></div>
                            <div class="stat-body">
                                <div>
                                    <small>Kategori Mapel</small>
                                    <h2><?= $counts['total_categories']; ?></h2>
                                </div>
                                <div class="stat-icon icon-light">
                                    <i class="bi bi-book-fill"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if (!empty($recommended_threads)): ?>
                <div class="thread-container rounded-4 shadow-sm mb-4">
                    <div class="thread-header-bar">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h6 class="m-0 fw-bold">
                                <i class="bi bi-stars text-warning"></i>
                                <?php if ($recommendation_type == 'category'): ?>
                                    Rekomendasi Untukmu
                                    <span class="text-muted fw-normal" style="font-size:0.85rem;">
                                        — berdasarkan kategori <?= htmlspecialchars($recommended_category_name); ?>
                                    </span>
                                <?php else: ?>
                                    Diskusi Populer
                                <?php endif; ?>
                            </h6>
                        </div>
                    </div>

                    <?php foreach ($recommended_threads as $t): ?>
                        <div class="thread-item">
                            <div class="thread-user">
                                <?php if (!empty($t['avatar'])): ?>
                                    <img src="<?= base_url('assets/uploads/avatars/' . $t['avatar']); ?>" class="avatar-circle" alt="Avatar">
                                <?php else: ?>
                                    <i class="bi bi-person-circle avatar-placeholder-icon"></i>
                                <?php endif; ?>
                            </div>
                            <div class="thread-content">
                                <div class="thread-badge">
                                    <span class="badge-mapel"><?= $t['nama_kategori']; ?></span>
                                    <?php if (isset($t['is_verified']) && $t['is_verified'] == 1): ?>
                                        <span class="badge-terjawab">Terjawab</span>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= base_url('thread/detail/' . $t['slug']); ?>" class="thread-title"><?= $t['title']; ?></a>
                                <div class="thread-meta">
                                    <span><i class="bi bi-person"></i> @<?= htmlspecialchars($t['username']); ?></span>
                                    <span><i class="bi bi-chat"></i> <?= $t['total_balasan']; ?> Balasan</span>
                                    <?php if (isset($t['total_views'])): ?>
                                        <span><i class="bi bi-eye"></i> <?= $t['total_views']; ?> Dilihat</span>
                                    <?php endif; ?>
                                    <span><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($t['created_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="thread-container rounded-4 shadow-sm">
                    <div class="thread-header-bar">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h6 class="m-0 fw-bold">Diskusi Terbaru</h6>
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('dashboard?type=all'); ?>" class="btn btn-filter <?= ($current_filter == 'all') ? 'active' : ''; ?>">Semua</a>
                                <a href="<?= base_url('dashboard?type=unanswered'); ?>" class="btn btn-filter <?= ($current_filter == 'unanswered') ? 'active' : ''; ?>">Belum Terjawab</a>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($threads)): ?>
                        <?php foreach ($threads as $t): ?>
                            <div class="thread-item">
                                <div class="thread-user">
                                    <?php if (!empty($t['avatar'])): ?>
                                        <img src="<?= base_url('assets/uploads/avatars/' . $t['avatar']); ?>" class="avatar-circle" alt="Avatar">
                                    <?php else: ?>
                                        <i class="bi bi-person-circle avatar-placeholder-icon"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="thread-content">
                                    <div class="thread-badge">
                                        <span class="badge-mapel"><?= $t['nama_kategori']; ?></span>
                                        <?php if ($t['is_verified'] == 1): ?>
                                            <span class="badge-terjawab">Terjawab</span>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?= base_url('thread/detail/' . $t['slug']); ?>" class="thread-title"><?= $t['title']; ?></a>
                                    <div class="thread-meta">
                                        <span><i class="bi bi-person"></i> @<?= htmlspecialchars($t['username']); ?></span>
                                        <span><i class="bi bi-chat"></i> <?= $t['total_balasan']; ?> Balasan</span>
                                        <span><i class="bi bi-calendar3"></i> <?= date('d M Y', strtotime($t['created_at'])); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-thread">
                            <i class="bi bi-chat-dots"></i>
                            <?php if ($current_filter == 'unanswered'): ?>
                                <h5>Semua pertanyaan sudah terjawab 🎉</h5>
                                <p>Belum ada diskusi yang membutuhkan jawaban.</p>
                            <?php else: ?>
                                <h5>Belum Ada Diskusi</h5>
                                <p>Jadilah siswa pertama yang memulai diskusi akademik di EduConnect.</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="<?= base_url('thread/create'); ?>" class="btn-floating">
                    <i class="bi bi-plus-lg"></i>
                    <span class="btn-floating-text">Mulai Diskusi</span>
                </a>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar dropdown di HP
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarContainer = document.getElementById('sidebarContainer');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function closeSidebar() {
            sidebarContainer.classList.remove('sidebar-open');
            sidebarOverlay.classList.remove('overlay-active');
        }

        sidebarToggleBtn?.addEventListener('click', () => {
            sidebarContainer.classList.toggle('sidebar-open');
            sidebarOverlay.classList.toggle('overlay-active');
        });

        sidebarOverlay?.addEventListener('click', closeSidebar);

        // Tutup sidebar otomatis saat salah satu menu diklik (di HP)
        document.querySelectorAll('.sidebar-container .nav-link-custom').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) closeSidebar();
            });
        });

        // Toggle search form mobile
        const searchToggleBtn = document.getElementById('searchToggleBtn');
        const searchFormMobile = document.getElementById('searchFormMobile');

        searchToggleBtn?.addEventListener('click', () => {
            searchFormMobile.classList.toggle('search-mobile-open');
        });
    </script>
</body>
</html>