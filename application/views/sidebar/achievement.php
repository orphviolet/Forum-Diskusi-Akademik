<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pencapaian | EduConnect</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/layout.css'); ?>">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-custom py-3">
    <div class="container px-4">
        <a class="btn btn-light btn-sm d-flex align-items-center gap-2 rounded-pill border" href="<?= base_url('dashboard'); ?>">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
        
        <div class="ms-auto">
            <span class="navbar-brand d-flex align-items-center m-0">
                <span class="brand-title fs-5">Pencapaian</span>
            </span>
        </div>
    </div>
</nav>

<div class="container pb-4 col-xl-8 col-lg-10 mx-auto">
    <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger py-2 px-3 small mb-3 text-center" role="alert">
            <?= $this->session->flashdata('error'); ?>
        </div>
    <?php endif; ?>

    <div class="main-card">
        <div class="profile">
            <div class="avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center;">
                <?php if(!empty($user['avatar'])): ?>
                    <img src="<?= base_url('assets/uploads/avatars/'.$user['avatar']); ?>" style="width: 100%; height: 100%; object-fit: cover;" alt="Avatar">
                <?php else: ?>
                    <i class="bi bi-person-fill"></i>
                <?php endif; ?>
            </div>
            <div>
                <h3>Halo, <?= htmlspecialchars($user['username']); ?>!</h3>
                <p>Pundi-pundi poin keaktifanmu saat ini</p>
            </div>
        </div>

        <div class="point-card">
            <small>Total Poin</small>
            <h1><?= number_format($user['poin'], 0, ',', '.'); ?> <span>POIN</span></h1>
        </div>

        <div class="tips">
            <i class="bi bi-stars"></i>
            <div>
                <h6>Info Poin</h6>
                <strong>+10 poin</strong> jika diskusi atau tanggapan yang kamu buat mendapatkan upvote. <br> <strong>+50 poin</strong> jika jawabanmu diverifikasi sebagai jawaban terbaik.
            </div>
        </div>

        <h2 class="section-title">
            <i class="bi bi-gift"></i> Rewards yang Bisa Kamu Dapatkan!
        </h2>

        <div class="row g-2">
            <?php foreach ($rewards as $item): ?>
                <div class="col-lg-6 mb-2">
                    <div class="reward-card">
                        <div>
                            <div class="reward-header">
                                <div class="reward-icon">
                                    <img src="<?= base_url('assets/uploads/rewards/' . $item['icon']); ?>" alt="Ikon Hadiah" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                                <div>
                                    <h5><?= htmlspecialchars($item['title']); ?></h5>
                                    <p><?= htmlspecialchars($item['description']); ?></p>
                                </div>
                            </div>
                            <hr class="my-2">
                            <div class="reward-footer">
                                <div>
                                    <small style="font-size: 10px; color:#777;">Harga</small>
                                    <div class="price">
                                        <i class="bi bi-coin coin"></i> <?= $item['price']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <a href="<?= base_url('achievement/detail/'.$item['id']); ?>"
                                class="btn-reward btn-active d-inline-flex align-items-center justify-content-center gap-2">

                                <i class="bi bi-eye"></i>
                                Lihat Detail

                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h2 class="section-title mt-4 pt-2">
            <i class="bi bi-clock-history"></i> Riwayat Voucher Milikmu
        </h2>
        
        <?php if(empty($my_claims)): ?>
            <div class="text-center p-3 text-muted border rounded" style="background: #fafafa; border-style: dashed;">
                Belum ada hadiah yang kamu klaim. Yuk kumpulkan poin keaktifanmu!
            </div>
        <?php else: ?>
            <div class="row g-2">
                <?php foreach($my_claims as $claim): ?>
                    <div class="col-md-6 mb-2">
                        <div class="p-2 border rounded shadow-sm d-flex justify-content-between align-items-center bg-white" style="font-size:12px;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="reward-icon m-0" style="width:32px; height:32px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                                    <img src="<?= base_url('assets/uploads/rewards/' . $claim['icon']); ?>" alt="Ikon" style="width: 100%; height: 100%; object-fit: contain;">
                                </div>
                                <div>
                                    <strong class="d-block text-truncate" style="max-width: 180px;"><?= htmlspecialchars($claim['title']); ?></strong>
                                    <code class="text-primary fw-bold" id="claimVoucher-<?= $claim['id']; ?>"><?= $claim['voucher_code']; ?></code>
                                </div>
                            </div>
                            <button class="btn btn-sm btn-light border py-1 px-2 text-primary" style="font-size:11px;" onclick="copyVoucherText('<?= $claim['id']; ?>')">
                                <i class="bi bi-copy"></i> Salin
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php if($this->session->flashdata('claim_success')): ?>
<div class="modal fade" id="claimModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold text-primary w-100 text-center mb-0">🎉 Klaim Berhasil!</h5>
            </div>
            <div class="modal-body text-center py-2">
                <p class="mb-1">Selamat! Kamu berhasil menukarkan <strong><?= $this->session->flashdata('claimed_price'); ?> poin</strong> untuk:</p>
                <h6 class="fw-bold mb-2">🦉 <?= $this->session->flashdata('claimed_title'); ?></h6>
                <p class="text-muted mb-1" style="font-size: 10px;">Kode voucher unik milikmu</p>
                <div class="voucher-box" id="voucherCodeSuccess"><?= $this->session->flashdata('claimed_voucher'); ?></div>
                <button class="btn btn-sm btn-outline-primary rounded-pill mb-2 py-1 px-3" style="font-size:11px;" onclick="copyVoucherSuccess()">
                    <i class="bi bi-copy"></i> Salin Kode
                </button>
            </div>
            <div class="modal-footer border-0 pt-0 justify-content-center">
                <button class="btn btn-sm btn-primary rounded-pill px-3 py-1" style="font-size:11px;" data-bs-dismiss="modal">Selesai</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
<?php if($this->session->flashdata('claim_success')): ?>
    const myModal = new bootstrap.Modal(document.getElementById('claimModal'));
    myModal.show();
<?php endif; ?>

function copyVoucherSuccess(){
    const text = document.getElementById("voucherCodeSuccess").innerText;
    navigator.clipboard.writeText(text);
    alert("Kode voucher berhasil disalin!");
}

function copyVoucherText(id){
    const text = document.getElementById("claimVoucher-" + id).innerText;
    navigator.clipboard.writeText(text);
    alert("Kode voucher berhasil disalin!");
}
</script>

</body>
</html>