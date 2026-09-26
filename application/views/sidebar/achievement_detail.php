<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($reward['title']); ?> | EduConnect</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/detail.css'); ?>">
</head>
<body class="bg-achievement-global">

    <nav class="navbar navbar-expand-lg navbar-custom py-3 mb-4">
        <div class="container px-4">
            <a class="btn btn-light btn-sm d-flex align-items-center gap-2 rounded-pill border" href="<?= base_url('achievement'); ?>">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <div class="ms-auto">
                <span class="navbar-brand d-flex align-items-center m-0">
                    <span class="brand-title fs-5">Syarat & Ketentuan Reward</span>
                </span>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-9 px-3">
                
                <div class="reward-main-box">
                    <!-- Header -->
                    <div class="reward-header">
                        <div class="reward-detail-icon-box">
                            <img src="<?= base_url('assets/uploads/rewards/'.$reward['icon']); ?>" alt="<?= htmlspecialchars($reward['title']); ?>">
                        </div>
                        <div class="reward-header-content">
                            <h2><?= htmlspecialchars($reward['title']); ?></h2>
                            <p><?= htmlspecialchars($reward['description']); ?></p>
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="reward-body">
                        <div class="section-label">Benefit</div>
                        <ul class="benefit-list-new">
                            <?php foreach(explode("\n", $reward['benefits']) as $benefit): ?>
                                <?php if(trim($benefit) != ""): ?>
                                    <li>
                                        <i class="bi bi-check-circle-fill"></i>
                                        <span><?= htmlspecialchars(trim($benefit)); ?></span>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>

                        <div class="section-label mt-4">Syarat & Ketentuan</div>
                        <ul class="terms-list-new">
                            <?php foreach(explode("\n", $reward['terms']) as $term): ?>
                                <?php if(trim($term) != ""): ?>
                                    <li><?= htmlspecialchars(trim($term)); ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <!-- Footer -->
                    <div class="reward-footer">
                        <?php if($user['poin'] >= $reward['price']): ?>
                            <form action="<?= base_url('achievement/tukar'); ?>" method="POST">
                                <input type="hidden" name="reward_id" value="<?= $reward['id']; ?>">
                                <button type="submit" class="btn btn-primary btn-exchange">Tukar Poin</button>
                            </form>
                        <?php else: ?>
                            <button class="btn btn-secondary btn-exchange" disabled>
                                <i class="bi bi-lock-fill me-2"></i> Poin Tidak Mencukupi
                            </button>
                        <?php endif; ?>
                    </div>
                </div> 

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>