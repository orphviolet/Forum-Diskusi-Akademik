<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Mata Pelajaran | EduConnect</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/category.css'); ?>">
</head>
<body>

    <!-- ========================= NAVBAR ========================= -->
    <!-- Jangan diubah tampilannya, hanya dibuat sticky melalui CSS -->
    <nav class="navbar navbar-expand-lg navbar-custom py-3">
        <div class="container px-4">
            <a class="btn btn-light btn-sm d-flex align-items-center gap-2 rounded-pill border back-btn" href="<?= base_url('dashboard'); ?>">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            <div class="ms-auto">
                <span class="navbar-brand m-0 d-flex align-items-center">
                    <span class="brand-title fs-5">Kategori Mapel</span>
                </span>
            </div>
        </div>
    </nav>

    <!-- ========================= HEADER ========================= -->
    <section class="hero-section">
        <div class="container px-4">
            <div class="hero-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="hero-title">
                            Temukan Forum Diskusi Berdasarkan Mata Pelajaran
                        </h1>
                        <p class="hero-description">
                            Pilih kategori mata pelajaran untuk melihat berbagai diskusi akademik, bertanya kepada siswa lain, berbagi pengetahuan, serta menemukan solusi pembelajaran dengan lebih mudah.
                        </p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="hero-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================= CATEGORY ========================= -->
    <section class="category-section">
        <div class="container px-4">
            <?php if(!empty($categories)): ?>
                <div class="row g-4">
                    <?php foreach($categories as $cat): ?>
                        <?php
                        $display_name = isset($cat['nama_kategori']) 
                            ? $cat['nama_kategori'] 
                            : (isset($cat['name']) ? $cat['name'] : 'Kategori');
                        ?>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <a href="<?= base_url('dashboard/category?category=' . $cat['id']); ?>" class="category-card">
                                <div class="card-left">
                                    <div class="icon-box">
                                        <?= strtoupper(substr($display_name, 0, 1)); ?>
                                    </div>
                                </div>
                                <div class="card-content">
                                    <h5><?= $display_name; ?></h5>
                                    <p>
                                        Jelajahi berbagai topik diskusi akademik, ajukan pertanyaan, dan bagikan pengetahuan.
                                    </p>
                                    <span class="explore-text">
                                        Lihat Diskusi <i class="bi bi-arrow-right-short"></i>
                                    </span>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="bi bi-folder2-open"></i>
                    </div>
                    <h3>Belum Ada Kategori</h3>
                    <p>Saat ini belum terdapat kategori mata pelajaran yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>