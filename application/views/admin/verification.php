<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Jawaban | EduConnect Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin_dashboard.css'); ?>">
</head>
<body>
    <div class="wrapper">
        <!-- TOPBAR -->
        <nav class="topbar">
            <div class="logo-section">
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

        <div class="main-container">
            <!-- SIDEBAR -->
            <aside class="sidebar">
                <a href="<?= base_url('admin'); ?>" class="sidebar-item">
                    <div class="icon-box"><i class="bi bi-grid-fill"></i></div>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('admin/students'); ?>" class="sidebar-item <?= ($this->router->fetch_method()=='students') ? 'active' : ''; ?>">
                    <div class="icon-box">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <span>Growth Pengguna</span>
                </a>
                <a href="<?= base_url('admin/category'); ?>" class="sidebar-item">
                    <div class="icon-box"><i class="bi bi-book-fill"></i></div>
                    <span>Kelola Kategori</span>
                </a>
                <a href="<?= base_url('admin/reward'); ?>" class="sidebar-item">
                    <div class="icon-box"><i class="bi bi-award-fill"></i></div>
                    <span>Kelola Reward</span>
                </a>
                <a href="<?= base_url('admin/verification'); ?>" class="sidebar-item active">
                    <div class="icon-box"><i class="bi bi-patch-check-fill"></i></div>
                    <span>Verifikasi Jawaban</span>
                </a>
                <div class="sidebar-bottom">
                    <a href="<?= base_url('auth/logout'); ?>" class="sidebar-item logout">
                        <div class="icon-box logout-icon"><i class="bi bi-box-arrow-right"></i></div>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>

            <!-- MAIN CONTENT -->
            <main class="content-area">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-primary mb-1">Verifikasi Jawaban Diskusi</h2>
                        <p class="text-muted mb-0">Tinjau pertanyaan siswa dan verifikasi jawaban yang paling tepat.</p>
                    </div>
                </div>

                <!-- Table / List Diskusi -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" width="5%">No</th>
                                        <th width="35%">Judul Diskusi / Pertanyaan</th>
                                        <th width="15%">Kategori</th>
                                        <th width="15%">Penanya</th>
                                        <th width="15%">Status Verifikasi</th>
                                        <th class="text-center" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($discussions)): ?>
                                        <?php $no=1; foreach($discussions as $d): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-secondary"><?= $no++; ?></td>
                                                <td>
                                                    <span class="fw-semibold text-dark d-block"><?= htmlspecialchars($d['title']); ?></span>
                                                    <small class="text-muted"><i class="bi bi-chat-left-dots me-1"></i><?= $d['total_answers']; ?> Jawaban</small>
                                                </td>
                                                <td><span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill"><?= htmlspecialchars($d['category_name']); ?></span></td>
                                                <td><?= htmlspecialchars($d['student_name']); ?></td>
                                                <td>
                                                    <?php if($d['is_verified']): ?>
                                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-1"></i>Terverifikasi</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill"><i class="bi bi-hourglass-split me-1"></i>Belum Diverifikasi</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('admin/verification_detail/' . $d['id']); ?>" class="btn btn-sm btn-primary rounded-3 px-3">
                                                        <i class="bi bi-eye-fill me-1"></i>Tinjau
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">Belum ada diskusi yang perlu diverifikasi.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>