<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Reward | EduConnect Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin_dashboard.css'); ?>">
</head>
<body>
    <div class="wrapper">
        <!-- ===================== NAVBAR ===================== -->
        <nav class="topbar">
            <div class="logo-section">
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
            <!-- ===================== SIDEBAR ===================== -->
            <aside class="sidebar">
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

            <!-- ===================== MAIN (SCROLLABLE AREA) ===================== -->
            <main class="content-area">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-primary mb-1">Kelola Reward</h2>
                        <p class="text-muted mb-0">Atur katalog hadiah/reward dan penukaran poin untuk para siswa.</p>
                    </div>
                    <button class="btn btn-primary px-4 py-2 rounded-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahReward">
                        <i class="bi bi-plus-lg me-2"></i>Tambah Reward
                    </button>
                </div>

                <!-- Flash Message -->
                <?php if($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('success'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $this->session->flashdata('error'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Table Container -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4" width="5%">No</th>
                                        <th width="10%">Ikon</th>
                                        <th width="20%">Judul Reward</th>
                                        <th width="23%">Deskripsi</th>
                                        <th width="12%">Harga (Poin)</th>
                                        <th width="15%">Manfaat & Syarat</th>
                                        <th class="text-center" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($rewards)): ?>
                                        <?php $no = 1; foreach($rewards as $r): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-secondary"><?= $no++; ?></td>
                                                <td>
                                                    <?php 
                                                        $icon_path = 'assets/uploads/rewards/' . $r['icon'];
                                                        if(!empty($r['icon']) && file_exists(FCPATH . $icon_path)): 
                                                    ?>
                                                        <img src="<?= base_url($icon_path); ?>" alt="Ikon" class="rounded-3 object-fit-cover" style="width: 48px; height: 48px;">
                                                    <?php else: ?>
                                                        <div class="icon-box text-primary bg-primary-subtle rounded-3" style="width: 48px; height: 48px;">
                                                            <i class="bi bi-gift fs-5"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="fw-semibold text-dark"><?= htmlspecialchars($r['title']); ?></span></td>
                                                <td>
                                                    <small class="text-muted d-block text-truncate" style="max-width: 220px;" title="<?= htmlspecialchars($r['description']); ?>">
                                                        <?= htmlspecialchars($r['description']); ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">
                                                        <i class="bi bi-coin me-1"></i><?= number_format($r['price'], 0, ',', '.'); ?> Poin
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="d-block text-secondary"><strong>Benefits:</strong> <?= htmlspecialchars($r['benefits'] ?? '-'); ?></small>
                                                    <small class="d-block text-muted"><strong>Terms:</strong> <?= htmlspecialchars($r['terms'] ?? '-'); ?></small>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-warning me-1 rounded-2 btn-edit-reward"
                                                            data-id="<?= $r['id']; ?>"
                                                            data-title="<?= htmlspecialchars($r['title']); ?>"
                                                            data-description="<?= htmlspecialchars($r['description']); ?>"
                                                            data-benefits="<?= htmlspecialchars($r['benefits']); ?>"
                                                            data-terms="<?= htmlspecialchars($r['terms']); ?>"
                                                            data-price="<?= $r['price']; ?>"
                                                            data-icon="<?= htmlspecialchars($r['icon']); ?>"
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#modalEditReward">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </button>
                                                    <a href="<?= base_url('admin/delete_reward/' . $r['id']); ?>" 
                                                       class="btn btn-sm btn-outline-danger rounded-2" 
                                                       onclick="return confirm('Apakah Anda yakin ingin menghapus reward ini?')">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data reward tersedia.</td>
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

    <!-- ===================== MODAL TAMBAH REWARD ===================== -->
    <div class="modal fade" id="modalTambahReward" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <!-- Tambahkan enctype="multipart/form-data" untuk upload file -->
                <form action="<?= base_url('admin/add_reward'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold text-primary">Tambah Reward Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Judul Reward</label>
                            <input type="text" class="form-control rounded-3" name="title" required placeholder="Contoh: Voucher Diskon 50%">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Upload Gambar Ikon</label>
                            <input type="file" class="form-control rounded-3" name="icon" accept="image/*" required>
                            <small class="text-muted fs-7">Format: PNG/JPG/JPEG (Max: 2MB)</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Harga (Poin)</label>
                            <input type="number" class="form-control rounded-3" name="price" required placeholder="100">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea class="form-control rounded-3" name="description" rows="2" required placeholder="Jelaskan detail singkat tentang reward ini..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Manfaat (Benefits)</label>
                            <textarea class="form-control rounded-3" name="benefits" rows="3" placeholder="Contoh: Akses fitur premium selama 1 bulan..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Syarat & Ketentuan (Terms)</label>
                            <textarea class="form-control rounded-3" name="terms" rows="3" placeholder="Contoh: Berlaku hanya untuk 1 kali penggunaan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Reward</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ===================== MODAL EDIT REWARD ===================== -->
    <div class="modal fade" id="modalEditReward" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow rounded-4">
                <form id="formEditReward" method="POST" enctype="multipart/form-data">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold text-primary">Edit Reward</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3">
                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Judul Reward</label>
                            <input type="text" id="editTitle" class="form-control rounded-3" name="title" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Ganti Gambar Ikon (Opsional)</label>
                            <input type="file" class="form-control rounded-3" name="icon" accept="image/*">
                            <small class="text-muted fs-7">Kosongkan jika tidak ingin mengubah gambar.</small>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Harga (Poin)</label>
                            <input type="number" id="editPrice" class="form-control rounded-3" name="price" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Deskripsi</label>
                            <textarea id="editDescription" class="form-control rounded-3" name="description" rows="2" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Manfaat (Benefits)</label>
                            <textarea id="editBenefits" class="form-control rounded-3" name="benefits" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Syarat & Ketentuan (Terms)</label>
                            <textarea id="editTerms" class="form-control rounded-3" name="terms" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 pt-0">
                        <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-3 px-4">Update Reward</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.btn-edit-reward').forEach(function(button) {
            button.addEventListener('click', function() {
                let id = this.dataset.id;
                document.getElementById('editTitle').value = this.dataset.title;
                document.getElementById('editPrice').value = this.dataset.price;
                document.getElementById('editDescription').value = this.dataset.description;
                document.getElementById('editBenefits').value = this.dataset.benefits;
                document.getElementById('editTerms').value = this.dataset.terms;

                document.getElementById('formEditReward').action = "<?= base_url('admin/edit_reward/'); ?>" + id;
            });
        });
    </script>
</body>
</html>