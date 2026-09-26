<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Diskusi | EduConnect</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/create-thread.css'); ?>">
</head>
<body>

    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a href="<?= base_url('dashboard'); ?>" class="btn btn-light btn-sm rounded-pill border back-btn d-flex align-items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <span class="navbar-brand ms-auto brand-title">Mulai Diskusi Baru</span>
        </div>
    </nav>

    <!-- ================= HERO ================= -->
    <section class="page-header">
        <div class="container">
            <div class="header-card">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2>Buat Diskusi Baru</h2>
                        <p>Ajukan pertanyaan, bagikan kesulitan belajar, atau mulai diskusi akademik bersama siswa lainnya.</p>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="header-icon">
                            <i class="bi bi-chat-square-text"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FORM ================= -->
    <section class="create-thread-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="thread-card">
                        <div class="thread-card-header">
                            <h4><i class="bi bi-pencil-square me-2"></i> Form Diskusi</h4>
                            <p>Lengkapi informasi berikut agar pertanyaanmu mudah dipahami oleh siswa lain.</p>
                        </div>

                        <form action="<?= base_url('thread/create'); ?>" method="POST" enctype="multipart/form-data">
                            <!-- Judul -->
                            <div class="mb-4">
                                <label class="form-label">Judul Pertanyaan</label>
                                <input type="text" class="form-control custom-input" name="title" value="<?= set_value('title'); ?>" placeholder="Contoh: Bagaimana cara menghitung invers matriks 3×3?">
                                <span class="text-danger small"><?= form_error('title'); ?></span>
                            </div>

                            <!-- Kategori -->
                            <div class="mb-4">
                                <label class="form-label">Mata Pelajaran</label>
                                <select class="form-select custom-input" name="category_id">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    <?php foreach($categories as $cat): ?>
                                        <option value="<?= $cat['id']; ?>" <?= set_select('category_id', $cat['id']); ?>>
                                            <?= $cat['nama_kategori']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="text-danger small"><?= form_error('category_id'); ?></span>
                            </div>

                            <!-- Isi -->
                            <div class="mb-4">
                                <label class="form-label">Detail Pertanyaan</label>
                                <textarea class="form-control custom-input custom-textarea" name="body" rows="7" placeholder="Jelaskan pertanyaanmu secara lengkap agar pengguna lain lebih mudah membantu..."><?= set_value('body'); ?></textarea>
                                <span class="text-danger small"><?= form_error('body'); ?></span>
                            </div>

                            <!-- Upload -->
                            <div class="mb-4">
                                <label class="form-label">Lampiran Gambar (Opsional)</label>
                                <div class="upload-box">
                                    <input type="file" class="form-control custom-input" name="image_lampiran" accept="image/jpeg,image/jpg,image/png">
                                    <div class="upload-info">
                                        <i class="bi bi-image me-1"></i> Format JPG, JPEG, PNG • Maksimal 5 MB <br>
                                        <span>Pengguna iPhone (HEIC), ubah terlebih dahulu ke JPG sebelum mengunggah.</span>
                                    </div>
                                </div>
                                <?php if(isset($error_upload)): ?>
                                    <span class="text-danger small d-block mt-2"><?= $error_upload; ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Action -->
                            <div class="form-action">
                                <a href="<?= base_url('dashboard'); ?>" class="btn btn-cancel">Batal</a>
                                <button type="submit" class="btn btn-submit">
                                    <i class="bi bi-send-fill me-2"></i> Kirim Diskusi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>