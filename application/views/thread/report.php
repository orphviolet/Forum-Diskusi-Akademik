<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Diskusi: <?= $thread['title']; ?> | EduConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #F8F9FA; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333333; }
        .navbar-custom { background-color: #FFFFFF; border-bottom: 1px solid #E9ECEF; }
        .brand-title { color: #2C5EAD; font-weight: 700; }

        .report-box { background: #FFFFFF; border: 1px solid #E9ECEF; border-radius: 12px; padding: 24px; }

        .stat-mini { background-color: #F8F9FA; border-radius: 10px; padding: 14px 16px; text-align: center; }
        .stat-mini h3 { font-weight: 700; margin: 4px 0 0; color: #1A1A1A; }
        .stat-mini small { color: #6C757D; font-size: 12px; }

        .contribution-box { background-color: #E3F2FD; border-radius: 10px; padding: 14px 18px; }
        .contribution-item { padding: 8px 0; border-top: 1px solid #CFE3FB; }
        .contribution-item:first-child { border-top: none; }

        .rank-item { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-bottom: 1px solid #F1F3F5; }
        .rank-item:last-child { border-bottom: none; }
        .rank-item.rank-verified { background-color: #F1FBF6; }
        .rank-num { width: 20px; color: #ADB5BD; font-size: 13px; text-align: center; flex-shrink: 0; }
        .rank-avatar { width: 30px; height: 30px; border-radius: 50%; border: 1px solid #CED4DA; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; background: #F8F9FA; color: #ADB5BD; }
        .rank-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .rank-comment-text { font-size: 13px; color: #333; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; margin: 0; }
        .badge-milikmu { background-color: #D1E7DD; color: #0F5132; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 6px; margin-left: 6px; }
        .badge-verified { background-color: #198754; color: #FFFFFF; font-size: 10px; font-weight: 600; padding: 2px 6px; border-radius: 6px; margin-left: 6px; }
        .rank-upvote { font-weight: 600; font-size: 13px; white-space: nowrap; flex-shrink: 0; }

        @media print {
            .no-print { display: none !important; }
            body { background: #FFFFFF; }
            .report-box { border: none; padding: 0; }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom py-3 mb-4 no-print">
    <div class="container px-4">
        <?php $ref_query = !empty($ref) ? '?ref=' . urlencode($ref) : ''; ?>
        <a class="btn btn-light btn-sm d-flex align-items-center gap-2 rounded-pill border" href="<?= base_url('thread/detail/' . $thread['slug'] . $ref_query); ?>">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <div class="ms-auto">
            <span class="navbar-brand d-flex align-items-center m-0">
                <span class="brand-title fs-5">Laporan Analitik Diskusi</span>
            </span>
        </div>
    </div>
</nav>

<div class="container px-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="report-box shadow-sm">

                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <small class="text-muted">Laporan analitik diskusi</small>
                        <h5 class="fw-bold mt-1 mb-1"><?= $thread['title']; ?></h5>
                        <small class="text-muted"><?= $thread['nama_kategori']; ?> &middot; dibuat <?= date('d M Y', strtotime($thread['created_at'])); ?></small>
                    </div>
                    <button onclick="window.print()" class="btn btn-primary btn-sm d-flex align-items-center gap-1 no-print" style="background-color:#2C5EAD; border-color:#2C5EAD;">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </button>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <div class="stat-mini">
                            <small>Total Komentar Kamu</small>
                            <h3><?= $total_komentar_saya; ?></h3>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-mini">
                            <small>Upvote yang Kamu Peroleh</small>
                            <h3><?= $total_upvote_saya; ?></h3>
                        </div>
                    </div>
                </div>

                <?php if (!empty($my_comments)): ?>
                    <div class="contribution-box mb-4">
                        <p class="fw-bold small mb-2" style="color:#0D47A1;">Daftar komentar yang kamu buat di diskusi ini</p>
                        <?php foreach ($my_comments as $mc): ?>
                            <div class="contribution-item d-flex justify-content-between align-items-center">
                                <p class="mb-0 rank-comment-text" style="white-space:normal;">
                                    <?= htmlspecialchars($mc['comment'], ENT_QUOTES, 'UTF-8'); ?>
                                    <?php if ($verified_comment_id && $mc['id'] == $verified_comment_id): ?>
                                        <span class="badge-verified">Terverifikasi</span>
                                    <?php endif; ?>
                                </p>
                                <span class="rank-upvote ms-3">
                                    <i class="bi bi-hand-thumbs-up-fill text-primary"></i> <?= $mc['total_upvote']; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted mb-4 py-3">
                        <p class="small mb-0">Kamu belum pernah berkomentar di diskusi ini.</p>
                    </div>
                <?php endif; ?>

                <p class="fw-bold small text-muted mb-2">Ranking komentar terverifikasi dan upvote terbanyak</p>

                <?php if (!empty($comments_ranked)): ?>
                    <div class="border rounded-3 overflow-hidden">
                        <?php $no = 1; ?>
                        <?php foreach ($comments_ranked as $c): ?>
                            <?php $is_verified = ($verified_comment_id && $c['id'] == $verified_comment_id); ?>
                            <div class="rank-item <?= $is_verified ? 'rank-verified' : ''; ?>">
                                <span class="rank-num"><?= $no++; ?></span>
                                <div class="rank-avatar">
                                    <?php if (!empty($c['avatar'])): ?>
                                        <img src="<?= base_url('assets/uploads/avatars/' . $c['avatar']); ?>" alt="Avatar">
                                    <?php else: ?>
                                        <i class="bi bi-person-fill"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow-1" style="min-width:0;">
                                    <p class="mb-0" style="font-size:12px;">
                                        @<?= htmlspecialchars($c['username'], ENT_QUOTES, 'UTF-8'); ?>
                                        <?php if ($c['user_id'] == $current_user_id): ?>
                                            <span class="badge-milikmu">komentarmu</span>
                                        <?php endif; ?>
                                        <?php if ($is_verified): ?>
                                            <span class="badge-verified">Terverifikasi</span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="rank-comment-text"><?= htmlspecialchars($c['comment'], ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                                <span class="rank-upvote"><?= $c['total_upvote']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-bar-chart fs-2 d-block mb-1 text-black-50"></i>
                        <p class="small mb-0">Belum ada komentar di diskusi ini.</p>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

</body>
</html>