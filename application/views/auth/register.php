<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Akun Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body { 
            background-color: #C4E2F5; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
        }
        .register-card { 
            background-color: #FFFFFF; 
            border-radius: 15px; 
            box-shadow: 0 8px 24px rgba(0,0,0,0.1); 
        }
        .btn-custom { 
            background-color: #1591DC; 
            color: #FFFFFF; 
            font-weight: 600; 
            border-radius: 50px; 
            border: none; 
        }
        .btn-custom:hover { 
            background-color: #2C5EAD; 
            color: #FFFFFF; 
        }
        .form-control:focus, .form-select:focus { 
            border-color: #4BB8FA; 
            box-shadow: 0 0 0 0.25rem rgba(75, 184, 250, 0.25); 
        }
        .text-custom-link { 
            color: #1591DC; 
            text-decoration: none; 
        }
        .text-custom-link:hover { 
            color: #2C5EAD; 
            text-decoration: underline; 
        }
        .input-desc { 
            font-size: 11px; 
            color: #6c757d; 
            display: block; 
            margin-top: -2px; 
            margin-bottom: 4px; 
        }
        .password-group {
            position: relative;
        }
        .password-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #7B8794;
            cursor: pointer;
            transition: .3s;
            z-index: 5;
        }
        .password-icon:hover {
            color: #1591DC;
        }
        .back-home {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            background: #FFFFFF;
            color: #2C5EAD;
            border: 1px solid #DCE6F2;
            border-radius: 50px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
            transition: .25s;
        }
        .back-home:hover {
            background: #2C5EAD;
            color: #FFFFFF;
            border-color: #2C5EAD;
        }
        @media(max-width:576px) {
            .back-home {
                top: 15px;
                left: 15px;
                padding: 7px 14px;
                font-size: 12px;
            }
            body {
                padding-top: 40px;
            }
        }
    </style>
</head>
<body>

    <a href="<?= base_url('landing'); ?>" class="back-home">
        <i class="bi bi-arrow-left"></i> Halaman Utama
    </a>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5">
                
                <div class="card register-card border-0 p-4 p-sm-5">
                    <div class="mb-4 text-center">
                        <h3 class="fw-bold mb-1" style="color: #2C5EAD;">Daftar Akun Baru</h3>
                        <p class="text-muted small mb-0">Lengkapi data diri untuk mulai berdiskusi.</p>
                    </div>

                    <form action="<?= base_url('auth/register'); ?>" method="POST">
                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-0" style="color: #2C5EAD;">Nama Lengkap</label>
                            <span class="input-desc">isikan nama lengkap anda</span>
                            <input type="text" class="form-control form-control-sm" name="nama" value="<?= set_value('nama'); ?>">
                            <?php if(form_error('nama')): ?>
                                <span class="text-danger small mt-1 d-block" style="font-size: 11px;"><?= form_error('nama'); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-0" style="color: #2C5EAD;">Kelas</label>
                            <span class="input-desc">contoh: X 1, XI IPA 3, XII IPS 2</span>
                            <input type="text" class="form-control form-control-sm" name="kelas" value="<?= set_value('kelas'); ?>" style="text-transform: uppercase;">
                            <?php if(form_error('kelas')): ?>
                                <span class="text-danger small mt-1 d-block" style="font-size: 11px;"><?= form_error('kelas'); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-0" style="color: #2C5EAD;">Email</label>
                            <span class="input-desc">alamat email aktif</span>
                            <input type="email" class="form-control form-control-sm" name="email" value="<?= set_value('email'); ?>">
                            <?php if(form_error('email')): ?>
                                <span class="text-danger small mt-1 d-block" style="font-size: 11px;"><?= form_error('email'); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="mb-2">
                            <label class="form-label small fw-semibold mb-0" style="color: #2C5EAD;">Username</label>
                            <span class="input-desc">username tidak boleh mengandung unsur nama anda</span>
                            <input type="text" class="form-control form-control-sm" name="username" value="<?= set_value('username'); ?>">
                            <?php if(form_error('username')): ?>
                                <span class="text-danger small mt-1 d-block" style="font-size: 11px;"><?= form_error('username'); ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-semibold mb-0" style="color: #2C5EAD;">Password</label>
                            <span class="input-desc">password minimal 8 karakter</span>
                            <div class="password-group">
                                <input type="password" class="form-control form-control-sm pe-5" id="password" name="password">
                                <i class="bi bi-eye password-icon" onclick="togglePassword('password', this)"></i>
                            </div>
                            <?php if(form_error('password')): ?>
                                <span class="text-danger small mt-1 d-block" style="font-size: 11px;"><?= form_error('password'); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-custom btn-sm py-2 shadow-sm">Register</button>
                        </div>
                    </form>

                    <div class="mt-3 text-center small">
                        <span class="text-muted">Sudah punya akun?</span> 
                        <a href="<?= base_url('auth'); ?>" class="fw-semibold text-custom-link ms-1">Login</a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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