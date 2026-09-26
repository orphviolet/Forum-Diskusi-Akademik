<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mata Pelajaran <?= $category_name; ?> | EduConnect</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Menggunakan CSS kategori utama -->
    <link rel="stylesheet" href="<?= base_url('assets/css/daftar_diskusi.css'); ?>">
</head>
<body>

    <!-- ========================= NAVBAR ========================= -->
    <!-- Menyelaraskan struktur penulisan navbar agar presisi seperti Kategori Mapel -->
    <nav class="navbar navbar-expand-lg navbar-custom py-3 mb-4">
        <div class="container px-4">
            <a class="btn btn-light btn-sm d-flex align-items-center gap-2 rounded-pill border back-btn" href="<?= base_url('dashboard/categories_list'); ?>">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <div class="ms-auto">
                <span class="navbar-brand m-0 d-flex align-items-center">
                    <span class="brand-title fs-5"><?= $category_name; ?></span>
                </span>
            </div>
        </div>
    </nav>

    <!-- ========================= CONTENT AREA ========================= -->
    <div class="container px-4">
        
        <!-- BAR PENCARIAN & URUTKAN -->
        <div class="top-toolbar">
            <form action="<?= base_url('dashboard/category'); ?>" method="GET" class="search-form">
                <input type="hidden" name="category" value="<?= $current_category; ?>">
                <input type="hidden" name="type" value="<?= $current_filter; ?>">
                <input type="hidden" name="sort" value="<?= $current_sort; ?>">
                <input type="text" name="search" class="form-control search-input" 
                    placeholder="Cari di mapel ini..." value="<?= htmlspecialchars($search_keyword ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="search-button">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <div class="sort-wrapper">
                <small class="sort-label">Urutkan:</small>
                <select class="form-select sort-select" onchange="location = this.value;">
                    <option value="<?= base_url('dashboard/category?category='.$current_category.'&type='.$current_filter.'&sort=latest&search='.($search_keyword ?? '')); ?>" <?= (isset($current_sort) && $current_sort == 'latest') ? 'selected' : ''; ?>>Terbaru</option>
                    <option value="<?= base_url('dashboard/category?category='.$current_category.'&type='.$current_filter.'&sort=oldest&search='.($search_keyword ?? '')); ?>" <?= (isset($current_sort) && $current_sort == 'oldest') ? 'selected' : ''; ?>>Terlama</option>
                </select>
            </div>
        </div>

        <!-- KONTAINER DAFTAR THREAD -->
        <div class="thread-container">
            <!-- FILTER TAB -->
            <div class="thread-header-bar d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <a href="<?= base_url('dashboard/category?category='.$current_category.'&type=all&sort='.($current_sort ?? 'latest').'&search='.($search_keyword ?? '')); ?>" class="btn-filter <?= ($current_filter == 'all') ? 'active' : ''; ?>">Semua</a>
                    <a href="<?= base_url('dashboard/category?category='.$current_category.'&type=unanswered&sort='.($current_sort ?? 'latest').'&search='.($search_keyword ?? '')); ?>" class="btn-filter <?= ($current_filter == 'unanswered') ? 'active' : ''; ?>">Belum terjawab</a>
                </div>
            </div>

            <!-- DAFTAR ITEM DISKUSI -->
            <?php if(!empty($threads)): ?>
                <?php foreach($threads as $t): ?>
                    <div class="thread-item">
                        <!-- FOTO PROFIL / ICON USER -->
                        <div class="thread-user">
                            <?php if (!empty($t['avatar'])): ?>
                                <img src="<?= base_url('assets/uploads/avatars/' . $t['avatar']); ?>" class="avatar-circle" alt="Avatar">
                            <?php else: ?>
                                <i class="bi bi-person-circle avatar-placeholder-icon"></i>
                            <?php endif; ?>
                        </div>
                        
                        <div class="thread-content">
                            <!-- LENCANA -->
                            <div class="thread-badge">
                                <span class="badge-mapel"><?= $t['nama_kategori']; ?></span>
                                <?php if(isset($t['is_verified']) && $t['is_verified'] == 1): ?>
                                    <span class="badge-terjawab">Terjawab</span>
                                <?php endif; ?>
                            </div>
                            
                            <!-- JUDUL DISKUSI -->
                            <a href="<?= base_url('thread/detail/' . $t['slug']); ?>" class="thread-title text-decoration-none">
                                <?= $t['title']; ?>
                            </a>
                            
                            <!-- METADATA -->
                            <div class="thread-meta">
                                <span><i class="bi bi-person"></i> @<?= htmlspecialchars($t['username'] ?? ''); ?></span>
                                <span><i class="bi bi-chat"></i> <?= $t['total_balasan'] ?? 0; ?> Balasan</span>
                                <span><i class="bi bi-calendar3"></i> <?= isset($t['created_at']) ? date('d M Y', strtotime($t['created_at'])) : ''; ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- EMPTY STATE -->
                <div class="empty-thread">
                    <i class="bi bi-chat-dots"></i>
                    <h5>Tidak ada diskusi ditemukan</h5>
                    <p class="small text-muted">Belum ada pertanyaan aktif dengan kriteria filter saat ini.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- PAGINASI -->
        <?php if(isset($total_pages) && $total_pages > 1): ?>
            <nav class="d-flex justify-content-center mt-4">
                <ul class="pagination pagination-sm shadow-sm rounded-pill overflow-hidden">
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $current_page ? 'active' : ''; ?>">
                            <a class="page-link px-3" href="<?= base_url('dashboard/category?category='.$current_category.'&type='.$current_filter.'&sort='.($current_sort ?? 'latest').'&search='.($search_keyword ?? '').'&page='.$i); ?>"><?= $i; ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>