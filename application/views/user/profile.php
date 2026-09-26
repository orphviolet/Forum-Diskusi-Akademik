<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | EduConnect</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/profile.css'); ?>">
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-custom py-3">
        <div class="container px-4">
            <a class="btn btn-light btn-sm rounded-pill border back-btn d-flex align-items-center gap-2" href="<?= base_url('dashboard'); ?>">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <div class="ms-auto">
                <span class="navbar-brand m-0">
                    <span class="brand-title fs-5">Profil Saya</span>
                </span>
            </div>
        </div>
    </nav>

    <!-- ================= HEADER ================= -->
    <section class="profile-header">
        <div class="container px-4">
            <div class="header-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2>Profil Saya</h2>
                        <p>Lihat informasi profil serta kelola profilmu dengan mudah.</p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="header-icon">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= PROFILE ================= -->
    <section class="profile-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    
                    <?php if($this->session->flashdata('success_profile')): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <?= $this->session->flashdata('success_profile'); ?>
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="profile-card">
                        <div class="profile-top">
                            <a href="<?= base_url('user/edit'); ?>" class="edit-btn">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <?php if(!empty($user['avatar'])): ?>
                                <img src="<?= base_url('assets/uploads/avatars/'.$user['avatar']); ?>" class="profile-avatar" alt="Avatar">
                            <?php else: ?>
                                <div class="profile-avatar">
                                    <?= strtoupper(substr($user['nama'], 0, 2)); ?>
                                </div>
                            <?php endif; ?>

                            <div class="profile-username">@<?= htmlspecialchars($user['username']); ?></div>
                            <h3><?= htmlspecialchars($user['nama']); ?></h3>
                            <span><?= htmlspecialchars($user['kelas']); ?></span>
                        </div>

                        <div class="stats-wrapper">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="bi bi-chat-left-text-fill"></i></div>
                                <h4><?= $total_threads; ?></h4>
                                <p>Diskusi Dibuat</p>
                            </div>
                            <div class="stat-card">
                                <div class="stat-icon"><i class="bi bi-chat-dots-fill"></i></div>
                                <h4><?= $total_comments; ?></h4>
                                <p>Balasan Ditulis</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>