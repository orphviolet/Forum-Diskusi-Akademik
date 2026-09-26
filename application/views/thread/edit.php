<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Diskusi | EduConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/edit_thread.css'); ?>">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <a href="<?= base_url('thread/detail/' . $thread['slug']); ?>" class="btn btn-light btn-sm rounded-pill border back-btn d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        <span class="navbar-brand ms-auto brand-title">Edit Diskusi</span>
    </div>
</nav>

<section class="page-header">
    <div class="container">
        <div class="header-card">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2>Perbarui Diskusi</h2>
                    <p>Ubah judul, kategori, isi pertanyaan, maupun lampiran agar diskusi yang kamu bagikan tetap jelas, lengkap, dan mudah dipahami oleh siswa lainnya.</p>
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

<section class="edit-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="edit-card">
                    <div class="form-title">
                        <div class="title-icon">
                            <i class="bi bi-pencil-fill"></i>
                        </div>
                        <div>
                            <h4>Edit Pertanyaan</h4>
                            <p>Pastikan informasi yang diperbarui sudah benar sebelum disimpan.</p>
                        </div>
                    </div>

                    <form action="<?= base_url('thread/edit/' . $thread['id']); ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="form-label custom-label">
                                <i class="bi bi-book-half me-2"></i> Mata Pelajaran
                            </label>
                            <select name="category_id" class="form-select custom-input" required>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id']; ?>" <?= ($cat['id'] == $thread['category_id']) ? 'selected' : ''; ?>>
                                        <?= $cat['nama_kategori']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label custom-label">
                                <i class="bi bi-card-heading me-2"></i> Judul Diskusi
                            </label>
                            <input type="text" name="title" class="form-control custom-input" autocomplete="off" value="<?= htmlspecialchars($thread['title']); ?>" placeholder="Masukkan judul pertanyaan..." required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label custom-label">
                                <i class="bi bi-chat-left-text me-2"></i> Detail Pertanyaan
                            </label>
                            <textarea name="body" rows="7" class="form-control custom-input" placeholder="Jelaskan pertanyaanmu secara lengkap..." required><?= htmlspecialchars($thread['body']); ?></textarea>
                        </div>

                        <div class="upload-card">
                            <div class="upload-title">
                                <i class="bi bi-image"></i> Lampiran Gambar
                            </div>
                            <p class="upload-desc">Kamu dapat mengganti gambar lama atau mengunggah gambar baru untuk memperjelas isi pertanyaan.</p>

                            <?php if (!empty($thread['image'])): ?>
                                <div class="current-image">
                                    <span class="image-label">Gambar Saat Ini</span>
                                    <img src="<?= base_url('assets/uploads/threads/' . $thread['image']); ?>" alt="Lampiran">
                                </div>
                            <?php endif; ?>

                            <input type="file" name="image_lampiran" class="form-control custom-input" accept=".jpg,.jpeg,.png">
                            <div class="upload-info">
                                <i class="bi bi-info-circle-fill me-1"></i> Format yang didukung: <strong>JPG, JPEG, PNG</strong> • Maksimal <strong>5 MB</strong>
                            </div>

                            <?php if(isset($error_upload)): ?>
                                <div class="upload-error">
                                    <i class="bi bi-exclamation-circle-fill me-1"></i> <?= $error_upload; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="form-action">
                            <a href="<?= base_url('thread/detail/' . $thread['slug']); ?>" class="btn btn-light btn-cancel">
                                <i class="bi bi-arrow-left-circle me-2"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-save">
                                <i class="bi bi-check-circle-fill me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="page-footer">
    <div class="container">
        <p class="mb-0">
            © <?= date('Y'); ?> <strong>EduConnect</strong> — Terhubung untuk Belajar, Bertumbuh untuk Berprestasi.
        </p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>