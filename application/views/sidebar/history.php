<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Saya | EduConnect</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/history.css'); ?>">
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a href="<?= base_url('dashboard');?>" class="btn btn-light btn-sm rounded-pill border back-btn d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <span class="navbar-brand ms-auto brand-title">Riwayat Saya</span>
        </div>
    </nav>

    <!-- ================= HERO ================= -->
    <section class="page-header">
        <div class="container">
            <div class="header-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2>Riwayat Saya</h2>
                        <p>Lihat seluruh riwayat diskusi dan balasan yang pernah kamu buat. Semua aktivitas tersimpan rapi sehingga mudah ditemukan kembali kapan saja.</p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="header-icon">
                            <i class="bi bi-journal-text"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <section class="history-section">
        <div class="container">
            <!-- FILTER -->
            <div class="filter-wrapper">
                <div class="filter-box">
                    <span><i class="bi bi-sort-down"></i> Urutkan</span>
                    <select class="form-select form-select-sm" onchange="location=this.value;">
                        <option value="<?= base_url('dashboard/history?sort=newest');?>" <?= $current_sort == 'newest' ? 'selected' : '';?>>Terbaru</option>
                        <option value="<?= base_url('dashboard/history?sort=oldest');?>" <?= $current_sort == 'oldest' ? 'selected' : '';?>>Terlama</option>
                    </select>
                </div>
            </div>

            <!-- CARD -->
            <div class="history-card">
                <!-- TAB -->
                <div class="history-tabs">
                    <ul class="nav nav-pills" id="historyTab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="threads-tab" data-bs-toggle="pill" data-bs-target="#threads-pane" type="button">
                                <i class="bi bi-chat-left-text-fill me-2"></i> Diskusi Saya 
                                <span class="tab-count"><?= count($my_threads_history);?></span>
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="comments-tab" data-bs-toggle="pill" data-bs-target="#comments-pane" type="button">
                                <i class="bi bi-reply-fill me-2"></i> Balasan Saya 
                                <span class="tab-count"><?= count($my_comments_history);?></span>
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- CONTENT -->
                <div class="tab-content">
                    <!-- THREAD PANE -->
                    <div class="tab-pane fade show active" id="threads-pane">
                        <?php if(empty($my_threads_history)): ?>
                            <div class="empty-state">
                                <div class="empty-icon"><i class="bi bi-chat-left-dots"></i></div>
                                <h4>Belum Ada Diskusi</h4>
                                <p>Kamu belum pernah membuat topik diskusi.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($my_threads_history as $t): ?>
                                <a href="<?= base_url('thread/detail/'.$t['slug']);?>" class="history-item">
                                    <div class="history-top">
                                        <span class="category-badge"><?= $t['nama_kategori'];?></span>
                                        <small><i class="bi bi-calendar3"></i> <?= date('j M Y, H:i', strtotime($t['created_at']));?> WIB</small>
                                    </div>
                                    <h5><?= htmlspecialchars($t['title'], ENT_QUOTES, 'UTF-8');?></h5>
                                    <p><?= strip_tags($t['body']);?></p>
                                </a>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>

                    <!-- COMMENT PANE -->
                    <div class="tab-pane fade" id="comments-pane">
                        <?php if(empty($my_comments_history)): ?>
                            <div class="empty-state">
                                <div class="empty-icon"><i class="bi bi-chat-square-quote"></i></div>
                                <h4>Belum Ada Balasan</h4>
                                <p>Kamu belum pernah memberikan balasan.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($my_comments_history as $c): ?>
                                <a href="<?= base_url('thread/detail/'.$c['thread_slug'].'#comment-'.$c['id']);?>" class="history-item">
                                    <div class="reply-title">
                                        <span>Membalas diskusi</span>
                                        <strong><?= htmlspecialchars($c['thread_title'], ENT_QUOTES, 'UTF-8');?></strong>
                                    </div>
                                    <div class="reply-box">
                                        <?= htmlspecialchars($c['comment'], ENT_QUOTES, 'UTF-8');?>
                                    </div>
                                    <div class="history-footer">
                                        <small><i class="bi bi-clock-history"></i> <?= date('j M Y, H:i', strtotime($c['created_at']));?> WIB</small>
                                    </div>
                                </a>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>