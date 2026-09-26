<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori | EduConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin_dashboard.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin_category.css'); ?>">
</head>
<body>
    <div class="wrapper">
        <!-- ===================== NAVBAR ===================== -->
        <nav class="topbar">
            <div class="logo-section">
                <button type="button" class="menu-toggle" id="menuToggle" aria-label="Buka menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>
                <img src="<?= base_url('assets/uploads/ikon.png'); ?>" alt="Logo">
                <div>
                    <h4>EduConnect</h4>
                    <span>Forum Diskusi Akademik</span>
                </div>
            </div>
            <div class="admin-profile">
                <div class="avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
            </div>
        </nav>

        <!-- ===================== CONTENT ===================== -->
        <div class="main-container">

            <!-- Overlay, muncul di belakang sidebar saat mode mobile terbuka -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>

            <!-- ===================== SIDEBAR ===================== -->
            <aside class="sidebar" id="sidebar">
                <a href="<?= base_url('admin'); ?>" class="sidebar-item <?= ($this->router->fetch_method()=='index') ? 'active' : ''; ?>">
                    <div class="icon-box">
                        <i class="bi bi-grid-fill"></i>
                    </div>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('admin/students'); ?>" class="sidebar-item <?= ($this->router->fetch_method()=='students') ? 'active' : ''; ?>">
                    <div class="icon-box">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>
                    <span>Growth Pengguna</span>
                </a>
                <a href="<?= base_url('admin/category'); ?>" class="sidebar-item <?= ($this->router->fetch_method()=='category') ? 'active' : ''; ?>">
                    <div class="icon-box">
                        <i class="bi bi-book-fill"></i>
                    </div>
                    <span>Kelola Kategori</span>
                </a>
                <a href="<?= base_url('admin/reward'); ?>" class="sidebar-item <?= ($this->router->fetch_method()=='reward') ? 'active' : ''; ?>">
                    <div class="icon-box">
                        <i class="bi bi-award-fill"></i>
                    </div>
                    <span>Kelola Reward</span>
                </a>
                <a href="<?= base_url('admin/verification'); ?>" class="sidebar-item <?= ($this->router->fetch_method()=='verification') ? 'active' : ''; ?>">
                    <div class="icon-box">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                    <span>Verifikasi Jawaban</span>
                </a>
                <div class="sidebar-bottom">
                    <a href="<?= base_url('auth/logout'); ?>" class="sidebar-item logout">
                        <div class="icon-box logout-icon">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>

            <!-- ===================== MAIN ===================== -->
            <main class="content-area">
                <!-- Header Halaman & Tombol Tambah -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold text-primary mb-1">Kelola Kategori</h3>
                        <p class="text-muted mb-0">Daftar kategori mata pelajaran forum diskusimu.</p>
                    </div>
                    <button class="btn btn-primary px-4 py-2 rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
                    </button>
                </div>

                <!-- Alert Flashdata Notifikasi -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i><?= $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Tabel Data Kategori -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4 py-3" style="width: 80px;">No</th>
                                        <th class="py-3">Nama Kategori</th>
                                        <th class="pe-4 py-3 text-end" style="width: 200px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($categories)): ?>
                                        <?php $no = 1; foreach ($categories as $cat): ?>
                                            <tr>
                                                <td class="ps-4 fw-semibold text-secondary"><?= $no++; ?></td>
                                                <td class="fw-medium text-dark"><?= htmlspecialchars($cat['nama_kategori']); ?></td>
                                                <td class="pe-4 text-end">
                                                    <button class="btn btn-sm btn-outline-warning me-1 rounded-2 btn-edit" 
                                                            data-id="<?= $cat['id']; ?>" 
                                                            data-kategori="<?= htmlspecialchars($cat['nama_kategori']); ?>"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalEditKategori">
                                                        <i class="bi bi-pencil-square"></i> Edit
                                                    </button>
                                                    <a href="<?= base_url('admin/delete_category/' . $cat['id']); ?>" 
                                                       class="btn btn-sm btn-outline-danger rounded-2" 
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                                                        <i class="bi bi-trash-fill"></i> Hapus
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                                Belum ada kategori yang ditambahkan.
                                            </td>
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

    <!-- Modal Tambah Kategori -->
    <div class="modal fade" id="modalTambahKategori" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <form action="<?= base_url('admin/add_category'); ?>" method="POST">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Tambah Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-4">
                        <label class="form-label font-semibold">Nama Kategori</label>
                        <input type="text" class="form-control rounded-3" name="nama_kategori" placeholder="Contoh: Matematika" required>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div class="modal fade" id="modalEditKategori" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0">
                <form id="formEditKategori" method="POST">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-4">
                        <label class="form-label font-semibold">Nama Kategori</label>
                        <input type="text" id="editKategori" class="form-control rounded-3" name="nama_kategori" required>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.btn-edit').forEach(function(button) {
            button.addEventListener('click', function() {
                let id = this.dataset.id;
                let nama = this.dataset.kategori;
                document.getElementById('editKategori').value = nama;
                document.getElementById('formEditKategori').action = "<?= base_url('admin/edit_category/'); ?>" + id;
            });
        });

        // ===================== MOBILE SIDEBAR TOGGLE =====================
        (function () {
            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                sidebar.classList.add('is-open');
                overlay.classList.add('is-open');
                menuToggle.setAttribute('aria-expanded', 'true');
            }

            function closeSidebar() {
                sidebar.classList.remove('is-open');
                overlay.classList.remove('is-open');
                menuToggle.setAttribute('aria-expanded', 'false');
            }

            menuToggle.addEventListener('click', function () {
                sidebar.classList.contains('is-open') ? closeSidebar() : openSidebar();
            });

            overlay.addEventListener('click', closeSidebar);

            sidebar.querySelectorAll('.sidebar-item').forEach(function (item) {
                item.addEventListener('click', function () {
                    if (window.innerWidth <= 992) {
                        closeSidebar();
                    }
                });
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth > 992) {
                    closeSidebar();
                }
            });
        })();
    </script>
</body>
</html>