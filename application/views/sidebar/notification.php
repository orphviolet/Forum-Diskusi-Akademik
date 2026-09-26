<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi | EduConnect</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/notification.css');?>">
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a href="<?= base_url('dashboard');?>" class="btn btn-light btn-sm rounded-pill border back-btn d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <span class="navbar-brand ms-auto brand-title">Notifikasi</span>
        </div>
    </nav>

    <!-- ================= HERO ================= -->
    <section class="page-header">
        <div class="container">
            <div class="header-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2>Notifikasi</h2>
                        <p>Lihat seluruh aktivitas terbaru dari diskusi, balasan, serta interaksi yang berkaitan dengan akunmu.</p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="header-icon">
                            <i class="bi bi-bell"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CONTENT ================= -->
    <section class="notification-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <?php if(!empty($notifications)): ?>
                        <div class="action-bar">
                            <a href="<?= base_url('notification/mark_all_read');?>" class="mark-read-btn">
                                <i class="bi bi-check2-all"></i> Tandai Semua Dibaca
                            </a>
                        </div>

                        <div class="notification-card">
                            <div class="notification-scroll">
                                <?php
                                $grouped_notif = [];
                                foreach($notifications as $n) {
                                    $grouped_notif[$n['day_group']][] = $n;
                                }
                                ?>

                                <?php foreach($grouped_notif as $day => $items): ?>
                                    <div class="day-header"><?= $day;?></div>
                                    
                                    <?php foreach($items as $item): ?>
                                        <?php
                                        $avatar_file = $item['sender_avatar'];
                                        $bg_class = $item['is_read'] == 0 ? 'notif-unread' : '';
                                        $clean_message = $item['message'];

                                        if(!empty($item['sender_username'])) {
                                            $clean_message = preg_replace(
                                                '/<strong[^>]*>.*?<\/strong>/',
                                                '<strong class="mention">@'.$item['sender_username'].'</strong>',
                                                $item['message'],
                                                1
                                            );
                                        }
                                        ?>
                                        <a href="<?= base_url('notification/read/'.$item['id']);?>" class="notification-item <?= $bg_class;?>">
                                            <div class="notif-avatar">
                                                <?php if(!empty($avatar_file)): ?>
                                                    <img src="<?= base_url('assets/uploads/avatars/'.$avatar_file);?>" alt="Avatar">
                                                <?php else: ?>
                                                    <div class="avatar-placeholder"><i class="bi bi-person-fill"></i></div>
                                                <?php endif; ?>
                                            </div>

                                            <div class="notif-content">
                                                <div class="notif-message"><?= $clean_message;?></div>
                                                <div class="notif-time">
                                                    <i class="bi bi-clock"></i>
                                                    <?= is_numeric($item['created_at']) ? $item['created_at'] : date('H.i', strtotime($item['created_at'])); ?>
                                                </div>
                                            </div>

                                            <?php if($item['is_read'] == 0): ?>
                                                <div class="notif-status">
                                                    <span class="notif-dot"></span>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="empty-card">
                            <div class="empty-icon"><i class="bi bi-bell-slash"></i></div>
                            <h4>Belum Anda Notifikasi</h4>
                            <p>Semua aktivitas diskusi, balasan, mention, dan pemberitahuan lainnya akan muncul di sini.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>