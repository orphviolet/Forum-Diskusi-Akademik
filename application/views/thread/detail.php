<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $thread['title']; ?> | EduConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        html { scroll-behavior: smooth; }
        body { background-color: #F8F9FA; min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333333; }
        
        .navbar-custom { background-color: #FFFFFF; border-bottom: 1px solid #E9ECEF; }
        .brand-title { color: #2C5EAD; font-weight: 700; }

        .main-container-box { 
            background: #FFFFFF; 
            border: 1px solid #E9ECEF; 
            border-radius: 12px; 
            overflow: hidden; 
            display: flex;
            flex-direction: column;
            height: auto; 
        }
        
        .thread-section { padding: 16px 20px; border-bottom: 1px solid #F1F3F5; flex-shrink: 0; }
        .comments-section { padding: 16px 20px; background-color: #FAFAFA; }
        
        .comments-scrollbox {
            max-height: 380px;
            overflow-y: auto;
        }
        
        .avatar-placeholder { 
            width: 42px; 
            height: 42px; 
            border-radius: 50%; 
            border: 2px solid #CED4DA; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background-color: #FFFFFF; 
            color: #ADB5BD; 
            overflow: hidden;
        }
        .avatar-placeholder-comment {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            border: 2px solid #CED4DA;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #FFFFFF;
            color: #ADB5BD;
            overflow: hidden;
        }
        .avatar-circle-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .badge-mapel { background-color: #E3F2FD; color: #0D6EFD; font-weight: 600; font-size: 11px; padding: 3px 8px; }
        .badge-terjawab { background-color: #D1E7DD; color: #0F5132; font-weight: 600; font-size: 11px; padding: 3px 8px; }
        .interaction-btn { background: none; border: none; color: #6C757D; font-size: 13px; padding: 0; display: inline-flex; align-items: center; gap: 4px; text-decoration: none; cursor: pointer; }
        .interaction-btn:hover { color: #2C5EAD; }
        .cursor-default { cursor: default !important; }
        .cursor-default:hover { color: #6C757D !important; }
        
        .comment-item { position: relative; padding-left: 14px; margin-bottom: 12px; transition: background-color 0.5s ease; }
        .comment-item:last-child { margin-bottom: 0; }
        .comment-item::before { content: ""; position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background-color: #CCCCCC; border-radius: 2px; }
        .comment-item.best-answer::before { background-color: #198754; width: 4px; }
        
        .comment-item:target {
            animation: highlightAnim 2s ease-out;
            background-color: #FFFDE7;
            padding-top: 6px;
            padding-bottom: 6px;
            border-radius: 6px;
        }

        @keyframes highlightAnim {
            0% { background-color: #FFF59D; }
            100% { background-color: #FFFDE7; }
        }

        .reply-box-footer { background-color: #FFFFFF; border-top: 1px solid #E9ECEF; padding: 14px 20px; flex-shrink: 0; }
        
        .input-reply-custom { background-color: #F8F9FA; border: 1px solid #E9ECEF; border-radius: 16px; padding: 10px 20px; font-size: 14px; width: 100%; transition: all 0.2s; height: 42px; }
        .input-reply-custom:focus { background-color: #FFFFFF; border-color: #A5C5F5; outline: none; box-shadow: 0 0 0 0.2rem rgba(44, 94, 173, 0.1); }
        .btn-send-custom { background: none; border: none; color: #2C5EAD; font-size: 20px; padding: 0; display: flex; align-items: center; transition: transform 0.2s; }
        .btn-send-custom:hover { color: #1591DC; transform: scale(1.1); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom py-3 mb-4">
    <div class="container px-4">
        <?php 
            $ref_param = $this->input->get('ref');
            if (!empty($ref_param)) {
                // Asal halaman dibawa dari parameter ref (misal habis mampir ke halaman laporan)
                $url_asal = urldecode($ref_param);
            } else {
                $url_asal = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : base_url('dashboard'); 
                if (strpos($url_asal, 'thread/detail') !== false || strpos($url_asal, 'thread/edit') !== false || strpos($url_asal, 'thread/create') !== false || strpos($url_asal, 'thread/report') !== false) {
                    $url_asal = base_url('dashboard');
                }
            }
        ?>

        <a class="btn btn-light btn-sm d-flex align-items-center gap-2 rounded-pill border" href="<?= $url_asal; ?>">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        <div class="ms-auto">
            <span class="navbar-brand d-flex align-items-center m-0">
                <span class="brand-title fs-5">Detail Diskusi</span>
            </span>
        </div>
    </div>
</nav>

<div class="container px-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <?php if($this->session->flashdata('success_comment')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-check-circle-fill"></i> <?= $this->session->flashdata('success_comment'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('error_action')): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= $this->session->flashdata('error_action'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="main-container-box shadow-sm">
                
                <div class="thread-section">
                    <div class="mb-1">
                        <span class="badge badge-mapel rounded"><?= $thread['nama_kategori']; ?></span>
                        <?php if($thread['is_verified'] == 1): ?>
                            <span class="badge badge-terjawab rounded ms-1">Terjawab</span>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <h5 class="fw-bold mb-0" style="color: #1A1A1A;"><?= $thread['title']; ?></h5>
                        <a href="<?= base_url('thread/report/' . $thread['slug']) . '?ref=' . urlencode($url_asal); ?>" class="text-secondary flex-shrink-0" style="font-size: 18px;" title="Lihat laporan analitik diskusi ini">
                            <i class="bi bi-printer"></i>
                        </a>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-placeholder">
                                <?php if(!empty($thread['avatar'])): ?>
                                    <img src="<?= base_url('assets/uploads/avatars/'.$thread['avatar']); ?>" class="avatar-circle-img" alt="Avatar">
                                <?php else: ?>
                                    <i class="bi bi-person-fill" style="font-size: 24px;"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <span class="fw-bold small d-block text-dark" style="font-size: 12px;">
                                    @<?= $thread['username']; ?>
                                </span>
                                <small class="text-muted" style="font-size: 11px;"><?= date('j M Y', strtotime($thread['created_at'])); ?> • <?= $thread['views_count']; ?> dilihat</small>
                            </div>
                        </div>
                        <span class="text-muted small" style="font-size: 12px;"><?= count($comments); ?> balasan</span>
                    </div>

                    <p class="text-secondary-emphasis mb-3" style="white-space: pre-line; line-height: 1.5; font-size: 14px;">
                        <?= $thread['body']; ?>
                    </p>

                    <?php if(!empty($thread['image'])): ?>
                        <div class="mb-3">
                            <a href="<?= base_url('assets/uploads/threads/' . $thread['image']); ?>" target="_blank" class="d-inline-block shadow-sm rounded border p-1 bg-white">
                                <img src="<?= base_url('assets/uploads/threads/' . $thread['image']); ?>" class="rounded" style="width: 100px; height: 100px; object-fit: cover; display: block;">
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <div class="d-flex gap-3 align-items-center">
                            <?php 
                                $total_likes_thread = $this->db->where(['thread_id' => $thread['id'], 'vote_type' => 'up'])->count_all_results('votes');
                                $sudah_like_thread = $this->db->get_where('votes', [
                                    'user_id' => $this->session->userdata('user_id'), 
                                    'thread_id' => $thread['id'],
                                    'vote_type' => 'up'
                                    ])->num_rows();
                                $warna_ikon_thread = ($sudah_like_thread > 0) ? 'text-primary fw-bold' : '';
                            ?>
                            
                            <?php if($thread['user_id'] != $this->session->userdata('user_id')): ?>
                                <a href="<?= base_url('vote/thread/' . $thread['id']); ?>" class="interaction-btn text-decoration-none <?= $warna_ikon_thread; ?>">
                                    <i class="bi bi-hand-thumbs-up<?= ($sudah_like_thread > 0) ? '-fill' : ''; ?>"></i> 
                                    <span><?= $total_likes_thread; ?></span>
                                </a>
                            <?php else: ?>
                                <span class="interaction-btn text-muted cursor-default">
                                    <i class="bi bi-hand-thumbs-up"></i> <span><?= $total_likes_thread; ?></span>
                                </span>
                            <?php endif; ?>

                            <button class="interaction-btn"><i class="bi bi-chat-left"></i> <span><?= count($comments); ?></span></button>

                            <?php if($thread['user_id'] != $this->session->userdata('user_id')): ?>
                                <button type="button" class="interaction-btn text-danger-emphasis" style="font-size: 13px;" onclick="bukaModalReport('Laporkan Diskusi Utama', '<?= base_url('thread/report_thread/' . $thread['id']); ?>')">
                                    <i class="bi bi-flag"></i> Laporkan
                                </button>
                            <?php endif; ?>

                            <?php if($thread['user_id'] == $this->session->userdata('user_id')): ?>
                                <div class="border-start ps-3 d-flex gap-2">
                                    <a href="<?= base_url('thread/edit/'.$thread['id']); ?>" class="text-secondary small text-decoration-none fw-semibold" style="font-size: 12px;"><i class="bi bi-pencil-square"></i> Edit</a>
                                    <a href="<?= base_url('thread/delete/'.$thread['id']); ?>" class="text-danger small text-decoration-none fw-semibold" style="font-size: 12px;" onclick="return confirm('Hapus diskusi ini?');"><i class="bi bi-trash"></i> Hapus</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="comments-section <?= (count($comments) > 3) ? 'comments-scrollbox' : ''; ?>">
                    <?php if(!empty($comments)): ?>
                        <?php foreach($comments as $c): ?>
                            <?php $is_best = ($thread['verified_comment_id'] == $c['id']) ? 'best-answer' : ''; ?>
                            
                            <div class="comment-item <?= $is_best; ?>" id="comment-<?= $c['id']; ?>">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-placeholder-comment">
                                            <?php if(!empty($c['avatar'])): ?>
                                                <img src="<?= base_url('assets/uploads/avatars/'.$c['avatar']); ?>" class="avatar-circle-img" alt="Avatar">
                                            <?php else: ?>
                                                <i class="bi bi-person-fill" style="font-size: 18px;"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark" style="font-size: 12px;">
                                                @<?= $c['username']; ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <?php $is_komentar_terpilih = ($thread['verified_comment_id'] == $c['id']); ?>

                                        <!-- Badge Jawaban Terverifikasi (Hanya Menampilkan Indikator) -->
                                        <?php if ($is_komentar_terpilih): ?>
                                            <span class="badge bg-success rounded-pill d-flex align-items-center gap-1 small py-1 px-2" style="font-size: 11px;">
                                                <i class="bi bi-check-circle-fill"></i> Jawaban Terverifikasi
                                            </span>
                                        <?php endif; ?>

                                        <?php if($c['user_id'] == $this->session->userdata('user_id')): ?>
                                            <div class="dropdown">
                                                <button class="btn btn-sm text-secondary p-0 border-0" type="button" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-light">
                                                    <li><button type="button" data-comment-id="<?= $c['id']; ?>" data-comment-text="<?= htmlspecialchars($c['comment'], ENT_QUOTES, 'UTF-8'); ?>" onclick="pemicuEditKomen(this)" class="dropdown-item small text-secondary"><i class="bi bi-pencil me-2"></i> Edit</button></li>
                                                    <li><a href="<?= base_url('comment/delete_comment/'.$c['id']); ?>" class="dropdown-item small text-danger" onclick="return confirm('Hapus komentar ini?');"><i class="bi bi-trash"></i> Hapus</a></li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <p class="mb-1 text-dark-emphasis" style="font-size: 13px; padding-left: 38px; line-height: 1.4; white-space: pre-line;">
                                    <?= $c['comment']; ?>
                                </p>

                                <?php if(!empty($c['image'])): ?>
                                    <div class="mb-2" style="padding-left: 38px;">
                                        <a href="<?= base_url('assets/uploads/comments/' . $c['image']); ?>" target="_blank" class="d-inline-block shadow-sm rounded border p-1 bg-white">
                                            <img src="<?= base_url('assets/uploads/comments/' . $c['image']); ?>" class="rounded" style="width: 100px; height: 100px; object-fit: cover; display: block;">
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <div class="d-flex gap-3 mb-2" style="padding-left: 38px;">
                                    <?php 
                                        $total_likes_comment = $this->db->where(['comment_id' => $c['id'], 'vote_type' => 'up'])->count_all_results('votes');
                                        $sudah_like_comment = $this->db->get_where('votes', [
                                            'user_id' => $this->session->userdata('user_id'), 
                                            'comment_id' => $c['id'],
                                            'vote_type' => 'up'
                                        ])->num_rows();
                                        $warna_ikon_comment = ($sudah_like_comment > 0) ? 'text-primary fw-bold' : '';
                                    ?>
                                    
                                    <?php if($c['user_id'] != $this->session->userdata('user_id')): ?>
                                        <a href="<?= base_url('vote/comment/' . $c['id']); ?>" class="interaction-btn text-decoration-none <?= $warna_ikon_comment; ?>" style="font-size: 11px;">
                                            <i class="bi bi-hand-thumbs-up<?= ($sudah_like_comment > 0) ? '-fill' : ''; ?>"></i> 
                                            <span><?= $total_likes_comment; ?></span>
                                        </a>
                                    <?php else: ?>
                                        <span class="interaction-btn text-muted cursor-default" style="font-size: 11px;">
                                            <i class="bi bi-hand-thumbs-up"></i> <span><?= $total_likes_comment; ?></span>
                                        </span>
                                    <?php endif; ?>

                                    <button type="button" class="interaction-btn" style="font-size: 11px; color: #0D6EFD;" onclick="replyToUser('<?= $c['username']; ?>')">Balas</button>
                                    
                                    <?php if($c['user_id'] != $this->session->userdata('user_id')): ?>
                                        <button type="button" class="interaction-btn text-danger-emphasis" style="font-size: 11px;" onclick="bukaModalReport('Laporkan Komentar', '<?= base_url('comment/report_comment/' . $c['id']); ?>')">
                                            <i class="bi bi-flag"></i> Laporkan
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-chat-square-text fs-2 d-block mb-1 text-black-50"></i>
                            <p class="small mb-0">Belum ada balasan untuk diskusi ini.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="reply-box-footer">
                    <form action="<?= base_url('comment/add_comment/' . $thread['slug']); ?>" method="POST" enctype="multipart/form-data" class="d-flex align-items-end gap-2">
                        <div class="position-relative flex-grow-1">
                            <textarea name="comment" id="input-reply-id" class="input-reply-custom" placeholder="Tulis balasanmu..." rows="1" style="resize: none; overflow-y: hidden;" required><?= set_value('comment'); ?></textarea>
                        </div>
                        <input type="file" name="image_komentar" id="image_komentar" accept="image/jpeg, image/jpg, image/png" style="display: none;" onchange="updateFileIndicator()">
                        <button type="button" class="btn text-secondary p-1 fs-5 mb-1" id="btn-clip" onclick="document.getElementById('image_komentar').click();">
                            <i class="bi bi-paperclip"></i>
                        </button>
                        <button type="submit" class="btn-send-custom mb-1"><i class="bi bi-send-fill"></i></button>
                    </form>

                    <div id="file-chosen-indicator" class="text-success small ps-2 mt-1 fw-semibold" style="display: none; font-size: 11px;"></div>
                    <span class="text-danger small d-block mt-1 ps-2"><?= form_error('comment'); ?></span>
                    <?php if(isset($error_upload_comment)): ?>
                        <span class="text-danger small d-block mt-1 ps-2"><?= $error_upload_comment; ?></span>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditKomen" tabindex="-1" aria-labelledby="modalEditKomenLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h6 class="modal-title fw-bold" id="modalEditKomenLabel"><i class="bi bi-pencil-square text-primary me-2"></i> Ubah Komentar Anda</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formEditKomen" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Isi Komentar</label>
                        <textarea name="comment" id="isiKomenLama" class="form-control border-secondary-subtle" rows="4" required></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-secondary">Ganti Gambar Lampiran <span class="text-muted fw-normal">(Opsional)</span></label>
                        <input type="file" name="image_komentar" class="form-control border-secondary-subtle" accept="image/jpeg, image/jpg, image/png">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn type-sm btn-light border fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-semibold px-3" style="background-color: #2C5EAD; border-color: #2C5EAD;">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalReportKomen" tabindex="-1" aria-labelledby="modalReportKomenLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0">
                <h6 class="modal-title fw-bold text-danger" id="modalReportKomenLabel"><i class="bi bi-flag-fill me-2"></i> Laporkan Konten</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formReportKomen" method="POST">
                <div class="modal-body py-2">
                    <p class="text-muted small mb-3">Pilih alasan mengapa Anda melaporkan konten ini agar dapat ditinjau oleh tim moderator:</p>
                    
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="alasan" id="alasan1" value="Spam / Iklan tidak relevan" checked>
                        <label class="form-check-label small text-dark" for="alasan1">Spam / Iklan tidak relevan</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="alasan" id="alasan2" value="Mengandung unsur SARA atau Ujaran Kebencian">
                        <label class="form-check-label small text-dark" for="alasan2">Mengandung unsur SARA atau Ujaran Kebencian</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="alasan" id="alasan3" value="Bahasa kasar, tidak sopan, atau perundungan">
                        <label class="form-check-label small text-dark" for="alasan3">Bahasa kasar, tidak sopan, atau perundungan (Bullying)</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="alasan" id="alasan4" value="Informasi palsu / Hoaks / Menyesatkan">
                        <label class="form-check-label small text-dark" for="alasan4">Informasi palsu / Hoaks / Menyesatkan</label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="alasan" id="alasan5" value="Lainnya / Melanggar aturan akademik sekolah">
                        <label class="form-check-label small text-dark" for="alasan5">Lainnya / Melanggar aturan akademik sekolah</label>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-2">
                    <button type="button" class="btn btn-sm btn-light border fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-danger fw-semibold px-3">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateFileIndicator() {
        const fileInput = document.getElementById('image_komentar');
        const indicator = document.getElementById('file-chosen-indicator');
        const clipIcon = document.getElementById('btn-clip');

        if (fileInput.files.length > 0) {
            indicator.innerText = "📎 Siap dikirim: " + fileInput.files[0].name;
            indicator.style.display = "block";
            clipIcon.classList.remove('text-secondary');
            clipIcon.classList.add('text-success'); 
        } else {
            indicator.style.display = "none";
            clipIcon.classList.remove('text-success');
            clipIcon.classList.add('text-secondary');
        }
    }

    const sampaikanKomen = document.getElementById('input-reply-id');
    sampaikanKomen.addEventListener('input', function() {
        this.style.height = 'auto';
        if (this.scrollHeight < 150) {
            this.style.height = this.scrollHeight + 'px';
            this.style.overflowY = 'hidden';
        } else {
            this.style.height = '150px';
            this.style.overflowY = 'auto';
        }
    });

    function replyToUser(username) {
        const replyInput = document.getElementById('input-reply-id');
        replyInput.value = "@" + username + " ";
        replyInput.focus();
        replyInput.dispatchEvent(new Event('input'));
    }

    function pemicuEditKomen(element) {
        const id = element.getAttribute('data-comment-id');
        const teksLama = element.getAttribute('data-comment-text');
        
        document.getElementById('isiKomenLama').value = teksLama;
        document.getElementById('formEditKomen').action = '<?= base_url("comment/edit_comment/"); ?>' + id;
        
        var modalSaya = new bootstrap.Modal(document.getElementById('modalEditKomen'));
        modalSaya.show();
    }

    function bukaModalReport(judul, urlAction) {
        document.getElementById('modalReportKomenLabel').innerHTML = '<i class="bi bi-flag-fill me-2"></i> ' + judul;
        document.getElementById('formReportKomen').action = urlAction;
        
        var modalReport = new bootstrap.Modal(document.getElementById('modalReportKomen'));
        modalReport.show();
    }
</script>

</body>
</html>