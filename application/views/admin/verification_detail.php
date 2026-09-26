<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Diskusi | EduConnect Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #F8F9FA; min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333333; }

        .navbar-custom { background-color: #FFFFFF; border-bottom: 1px solid #E9ECEF; }
        .brand-title { color: #2C5EAD; font-weight: 700; }

        .main-container-box {
            background: #FFFFFF;
            border: 1px solid #E9ECEF;
            border-radius: 12px;
            overflow: hidden;
        }

        .thread-section { padding: 20px; border-bottom: 1px solid #F1F3F5; }
        .comments-section { padding: 20px; background-color: #FAFAFA; }

        .avatar-placeholder {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 2px solid #CED4DA;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #FFFFFF;
            color: #ADB5BD;
            overflow: hidden;
        }
        .avatar-placeholder-comment {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 2px solid #CED4DA;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #FFFFFF;
            color: #ADB5BD;
            overflow: hidden;
        }
        .avatar-circle-img { width: 100%; height: 100%; object-fit: cover; display: block; }

        .badge-mapel { background-color: #E3F2FD; color: #0D6EFD; font-weight: 600; font-size: 11px; padding: 3px 8px; }
        .badge-terjawab { background-color: #D1E7DD; color: #0F5132; font-weight: 600; font-size: 11px; padding: 3px 8px; }

        .comment-item { position: relative; padding-left: 14px; margin-bottom: 14px; }
        .comment-item:last-child { margin-bottom: 0; }
        .comment-item::before { content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background-color: #CCCCCC; border-radius: 2px; }
        .comment-item.best-answer::before { background-color: #198754; width: 4px; }
        .comment-item.best-answer { background-color: #F4FBF7; border-radius: 8px; padding: 10px 14px; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-custom py-3 mb-4">
    <div class="container px-4">
        <a class="btn btn-light btn-sm d-flex align-items-center gap-2 rounded-pill border" href="<?= base_url('admin/verification'); ?>">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>

        <div class="ms-auto">
            <span class="navbar-brand d-flex align-items-center m-0">
                <span class="brand-title fs-5">Verifikasi Jawaban Diskusi</span>
            </span>
        </div>
    </div>
</nav>

<div class="container px-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <?php if($this->session->flashdata('success_comment')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-check-circle-fill"></i> <?= $this->session->flashdata('success_comment'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error_action')): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= $this->session->flashdata('error_action'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= $this->session->flashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="main-container-box shadow-sm">

                <!-- ================= DETAIL DISKUSI ================= -->
                <div class="thread-section">
                    <div class="mb-1">
                        <span class="badge badge-mapel rounded"><?= $thread['nama_kategori']; ?></span>
                        <?php if($thread['is_verified'] == 1): ?>
                            <span class="badge badge-terjawab rounded ms-1">Terjawab</span>
                        <?php endif; ?>
                    </div>
                    <h5 class="fw-bold mb-2" style="color: #1A1A1A;"><?= $thread['title']; ?></h5>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="avatar-placeholder">
                            <?php if(!empty($thread['avatar'])): ?>
                                <img src="<?= base_url('assets/uploads/avatars/'.$thread['avatar']); ?>" class="avatar-circle-img" alt="Avatar">
                            <?php else: ?>
                                <i class="bi bi-person-fill" style="font-size: 24px;"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <span class="fw-bold small d-block text-dark" style="font-size: 12px;">
                                @<?= $thread['username']; ?>
                            </span>
                            <small class="text-muted" style="font-size: 11px;"><?= date('j M Y', strtotime($thread['created_at'])); ?></small>
                        </div>
                    </div>

                    <p class="text-secondary-emphasis mb-3" style="white-space: pre-line; line-height: 1.5; font-size: 14px;">
                        <?= $thread['body']; ?>
                    </p>

                    <?php if(!empty($thread['image'])): ?>
                        <div class="mb-2">
                            <a href="<?= base_url('assets/uploads/threads/' . $thread['image']); ?>" target="_blank" class="d-inline-block shadow-sm rounded border p-1 bg-white">
                                <img src="<?= base_url('assets/uploads/threads/' . $thread['image']); ?>" class="rounded" style="width: 100px; height: 100px; object-fit: cover; display: block;">
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ================= DAFTAR JAWABAN / KOMENTAR ================= -->
                <div class="comments-section">
                    <h6 class="fw-bold mb-3 text-secondary" style="font-size: 13px;">
                        <i class="bi bi-chat-left-text me-1"></i> Jawaban / Balasan (<?= count($comments); ?>)
                    </h6>

                    <?php if(!empty($comments)): ?>
                        <?php foreach($comments as $c): ?>
                            <?php $is_best = ($thread['verified_comment_id'] == $c['id']); ?>

                            <div class="comment-item <?= $is_best ? 'best-answer' : ''; ?>">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-placeholder-comment">
                                            <?php if(!empty($c['avatar'])): ?>
                                                <img src="<?= base_url('assets/uploads/avatars/'.$c['avatar']); ?>" class="avatar-circle-img" alt="Avatar">
                                            <?php else: ?>
                                                <i class="bi bi-person-fill" style="font-size: 18px;"></i>
                                            <?php endif; ?>
                                        </div>
                                        <span class="fw-bold text-dark" style="font-size: 12px;">
                                            @<?= $c['username']; ?>
                                        </span>
                                    </div>

                                    <!-- Aksi Verifikasi -->
                                    <?php if($is_best): ?>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success rounded-pill d-flex align-items-center gap-1 small py-1 px-2" style="font-size: 11px;">
                                                <i class="bi bi-check-circle-fill"></i> Jawaban Terverifikasi
                                            </span>
                                            <a href="<?= base_url('admin/unverify_comment/' . $thread['id']); ?>" class="btn btn-outline-danger btn-sm py-0 px-2 rounded-pill" style="font-size: 10px;" onclick="return confirm('Batalkan verifikasi jawaban ini?');">
                                                Batal Verifikasi
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <?php if(empty($thread['verified_comment_id'])): ?>
                                            <a href="<?= base_url('admin/verify_comment/' . $c['id']); ?>" class="btn btn-outline-success btn-sm py-0 px-2 rounded-pill d-flex align-items-center gap-1" style="font-size: 10px;" onclick="return confirm('Verifikasi komentar ini sebagai jawaban terbaik?');">
                                                <i class="bi bi-check2-circle"></i> Verifikasi Jawaban
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>

                                <p class="mb-1 text-dark-emphasis" style="font-size: 13px; padding-left: 38px; line-height: 1.4; white-space: pre-line;">
                                    <?= $c['comment']; ?>
                                </p>

                                <?php if(!empty($c['image'])): ?>
                                    <div class="mb-1" style="padding-left: 38px;">
                                        <a href="<?= base_url('assets/uploads/comments/' . $c['image']); ?>" target="_blank" class="d-inline-block shadow-sm rounded border p-1 bg-white">
                                            <img src="<?= base_url('assets/uploads/comments/' . $c['image']); ?>" class="rounded" style="width: 100px; height: 100px; object-fit: cover; display: block;">
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-chat-square-text fs-2 d-block mb-1 text-black-50"></i>
                            <p class="small mb-0">Belum ada jawaban untuk diskusi ini.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>