<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil | EduConnect</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/edit_profile.css'); ?>">
</head>
<body>

    <!-- ===================== NAVBAR ===================== -->
    <nav class="navbar navbar-expand-lg navbar-custom py-3">
        <div class="container">
            <a href="<?= base_url('user/profile');?>" class="btn btn-light btn-sm rounded-pill border back-btn d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <span class="navbar-brand ms-auto brand-title">Edit Profil</span>
        </div>
    </nav>

    <!-- ===================== HEADER ===================== -->
    <section class="page-header">
        <div class="container">
            <div class="header-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2>Ubah Profil</h2>
                        <p>Perbarui informasi akun, foto profil, serta kata sandi agar akunmu tetap aman dan selalu menggunakan data terbaru.</p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="header-icon">
                            <i class="bi bi-pencil-square"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===================== CONTENT ===================== -->
    <section class="edit-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">

                    <?php if($this->session->flashdata('error_edit')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <?= $this->session->flashdata('error_edit'); ?>
                            <button class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('user/edit');?>" method="POST" enctype="multipart/form-data">
                        <div class="edit-card">
                            
                            <!-- ================= AVATAR ================= -->
                            <div class="avatar-section">
                                <div class="avatar-wrapper">
                                    <?php if(!empty($user['avatar'])): ?>
                                        <img src="<?= base_url('assets/uploads/avatars/'.$user['avatar']);?>" id="avatarRender" class="avatar-image" alt="Avatar">
                                    <?php else: ?>
                                        <div id="avatarRender" class="avatar-image">
                                            <?= strtoupper(substr($user['nama'], 0, 2));?>
                                        </div>
                                    <?php endif; ?>

                                    <label for="fileAvatarUpload" class="camera-btn">
                                        <i class="bi bi-camera-fill"></i>
                                    </label>
                                    <input type="file" name="avatar" id="fileAvatarUpload" accept="image/*" hidden onchange="livePreviewImage(this)">
                                </div>
                                <h5><?= htmlspecialchars($user['nama']);?></h5>
                                <p><?= htmlspecialchars($user['kelas']);?></p>
                            </div>

                            <!-- ================= FORM ================= -->
                            <div class="form-section">
                                <div class="mb-3">
                                    <label class="form-label-custom">Nama Lengkap</label>
                                    <input type="text" name="nama" class="form-control form-control-custom" value="<?= set_value('nama', $user['nama']);?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-custom">Kelas</label>
                                    <input type="text" name="kelas" class="form-control form-control-custom" value="<?= set_value('kelas', $user['kelas']);?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-custom">Username</label>
                                    <input type="text" name="username" class="form-control form-control-custom"
                                        value="<?= set_value('username', $user['username']);?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-custom">Password Lama</label>
                                    <div class="password-group">
                                        <input type="password" id="inputPasswordLama" name="password_lama" class="form-control form-control-custom pe-5" placeholder="Masukkan password lama">
                                        <i class="bi bi-eye password-icon" onclick="togglePassword('inputPasswordLama', this)"></i>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label-custom">Password Baru</label>
                                    <div class="password-group">
                                        <input type="password" id="inputPasswordBaru" name="password_baru" class="form-control form-control-custom pe-5" placeholder="Minimal 6 karakter">
                                        <i class="bi bi-eye password-icon" onclick="togglePassword('inputPasswordBaru', this)"></i>
                                    </div>
                                    <?php if(form_error('password_baru')): ?>
                                        <span class="text-danger small mt-2 d-block"><?= form_error('password_baru');?></span>
                                    <?php endif; ?>
                                </div>

                                <div class="row g-3">
                                    <div class="col-4">
                                        <a href="<?= base_url('user/profile');?>" class="btn btn-light cancel-btn w-100">Batal</a>
                                    </div>
                                    <div class="col-8">
                                        <button type="submit" class="btn save-btn w-100">
                                            <i class="bi bi-check-circle-fill me-1"></i> Simpan
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function livePreviewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let target = document.getElementById('avatarRender');
                    if (target.tagName === "DIV") {
                        let img = document.createElement("img");
                        img.id = "avatarRender";
                        img.className = "avatar-image";
                        img.src = e.target.result;
                        img.alt = "Avatar";
                        target.parentNode.replaceChild(img, target);
                    } else {
                        target.src = e.target.result;
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function togglePassword(id, icon) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("bi-eye", "bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("bi-eye-slash", "bi-eye");
            }
        }
    </script>
</body>
</html>