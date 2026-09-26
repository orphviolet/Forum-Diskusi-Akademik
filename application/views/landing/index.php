<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduConnect</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/landing.css')?>">
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top navbar-custom">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="<?= base_url('assets/uploads/ikon.png')?>">
            <div class="ms-2">
                <h4>EduConnect</h4>
                <small>Forum Diskusi Akademik</small>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="#home">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#forum">Tentang Forum</a></li>
                <li class="nav-item"><a class="nav-link" href="#reward">Rewards</a></li>
            </ul>
        </div>
    </div>
</nav>

<section id="home" class="hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 order-2 order-lg-1" data-aos="fade-right">
                <h1>Terhubung untuk Belajar, <span>Bertumbuh untuk Berprestasi!</span></h1>
                <p>Platform forum diskusi akademik bagi siswa SMA Negeri 3 Nabire untuk bertanya, berbagi pengetahuan, dan saling mendukung dalam meraih prestasi bersama.</p>
                <div class="mt-4 button-group">
                    <a href="<?= base_url('auth/register')?>" class="btn btn-main">Mulai Diskusi <i class="bi bi-arrow-right"></i></a>
                    <a href="#forum" class="btn btn-outline-main">Pelajari Lebih Lanjut</a>
                </div>
            </div>
            <div class="col-lg-6 text-center order-1 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left">
                <img src="<?= base_url('assets/uploads/hero.png')?>" class="hero-img">
            </div>
        </div>
    </div>
</section>

<section id="forum" class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 mb-5 mb-lg-0" data-aos="fade-right">
                <div class="section-title">
                    <h2>Tentang Forum</h2>
                    <div class="title-line"></div>
                </div>
                <p class="section-text">Belajar tidak selalu mudah, apalagi ketika tidak tahu harus bertanya kepada siapa. EduConnect dirancang sebagai forum diskusi akademik yang menghubungkan siswa SMA Negeri 3 Nabire dalam satu ruang belajar bersama.</p>
                <p class="section-text">Melalui EduConnect, setiap siswa dapat menemukan teman belajar, memperoleh jawaban dari berbagai sudut pandang, dan membangun budaya belajar yang lebih aktif tanpa harus merasa canggung untuk memulai percakapan.</p>
            </div>
            <div class="col-lg-7" data-aos="fade-left">
                <div class="feature-card">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-chat-dots-fill"></i></div>
                        <div>
                            <h5>Mulai Diskusi</h5>
                            <p>Ajukan pertanyaan kapan saja dan temukan solusi bersama teman.</p>
                        </div>
                    </div>
                    <hr>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <h5>Belajar Bersama</h5>
                            <p>Bangun kebiasaan belajar yang kolaboratif melalui diskusi yang aktif dan saling mendukung.</p>
                        </div>
                    </div>
                    <hr>
                    <div class="feature-item">
                        <div class="feature-icon"><i class="bi bi-rocket-takeoff-fill"></i></div>
                        <div>
                            <h5>Kembangkan Wawasan</h5>
                            <p>Temukan berbagai perspektif baru untuk memperdalam pemahaman dan meraih prestasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="reward" class="reward-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3 mb-5 mb-lg-0" data-aos="fade-right">
                <div class="section-title">
                    <h2>Rewards</h2>
                    <div class="title-line"></div>
                </div>
                <p class="section-text">Siapa bilang belajar nggak bisa dapat hadiah? Yuk, aktif berdiskusi, kumpulkan poin dari setiap kontribusimu, lalu tukarkan dengan reward favorit seperti Duolingo Super, Ruangguru Premium, Spotify Premium, dan masih banyak lagi!</p>
            </div>
            <div class="col-lg-9">
                <div class="row g-4 row-cols-1 row-cols-md-3">
                    <div class="col" data-aos="zoom-in">
                        <div class="reward-card">
                            <img src="<?= base_url('assets/uploads/rewards/duolingo.png')?>">
                            <h4>Duolingo Super</h4>
                            <p>Belajar bahasa asing tanpa iklan dan fitur premium.</p>
                        </div>
                    </div>
                    <div class="col" data-aos="zoom-in" data-aos-delay="100">
                        <div class="reward-card">
                            <img src="<?= base_url('assets/uploads/rewards/ruangguru1.png')?>">
                            <h4>Ruangguru Premium</h4>
                            <p>Akses materi belajar premium untuk persiapan ujian.</p>
                        </div>
                    </div>
                    <div class="col" data-aos="zoom-in" data-aos-delay="200">
                        <div class="reward-card">
                            <img src="<?= base_url('assets/uploads/rewards/spotify2.png')?>">
                            <h4>Spotify Premium</h4>
                            <p>Belajar sambil menikmati musik tanpa iklan.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<footer class="footer">
    <div class="footer-wave">
        <svg viewBox="0 0 1440 180" preserveAspectRatio="none">
            <path fill="#2C5EAD" d="M0,96L80,80C160,64,320,32,480,42.7C640,53,800,107,960,112C1120,117,1280,75,1360,53.3L1440,32L1440,181L0,181Z"></path>
        </svg>
    </div>
    <div class="footer-content">
        <div class="container">
            <div class="row gy-4 align-items-start text-start">
                <div class="col-lg-5 col-12">
                    <div class="d-flex align-items-center mb-3 justify-content-start">
                        <img src="<?= base_url('assets/uploads/ikon1.png') ?>" class="footer-logo">
                        <div class="ms-3">
                            <h3>EduConnect</h3>
                            <span>Forum Diskusi Akademik</span>
                        </div>
                    </div>
                    <div class="social justify-content-start">
                        <a href="#"><i class="bi bi-instagram"></i></a>
                        <a href="#"><i class="bi bi-tiktok"></i></a>
                        <a href="#"><i class="bi bi-youtube"></i></a>
                        <a href="#"><i class="bi bi-envelope-fill"></i></a>
                    </div>
                </div>
                
                <!-- UPDATE: col-6 agar berdampingan di HP -->
                <div class="col-lg-3 col-6">
                    <h5>Navigasi</h5>
                    <ul>
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#forum">Tentang Forum</a></li>
                        <li><a href="#reward">Rewards</a></li>
                    </ul>
                </div>
                
                <!-- UPDATE: col-6 agar berdampingan di HP -->
                <div class="col-lg-4 col-6">
                    <h5>Kontak</h5>
                    <ul>
                        <li><i class="bi bi-envelope me-2"></i>educonnect@gmail.com</li>
                        <li><i class="bi bi-instagram me-2"></i>@educonnect</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="copyright">
                © <?= date('Y'); ?> EduConnect. All Rights Reserved.
            </div>
        </div>
    </div>
</footer>

<button id="backTop"><i class="bi bi-arrow-up"></i></button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script src="<?= base_url('assets/js/landing.js')?>"></script>
</body>
</html>