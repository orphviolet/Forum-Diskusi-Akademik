<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa | EduConnect</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin_dashboard.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/students.css'); ?>">
</head>
<body>
    <div class="wrapper">
        <nav class="topbar no-print">
            <div class="logo-section">
                <button type="button" class="menu-toggle no-print" id="menuToggle" aria-label="Buka menu" aria-controls="sidebar" aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>
                <img src="<?= base_url('assets/uploads/ikon.png'); ?>" alt="Logo">
                <div>
                    <h4>EduConnect</h4>
                    <span>Forum Diskusi Akademik</span>
                </div>
            </div>
            <div class="admin-profile">
                <div class="avatar"><i class="bi bi-person-circle"></i></div>
            </div>
        </nav>

        <div class="main-container">

            <!-- Overlay, muncul di belakang sidebar saat mode mobile terbuka -->
            <div class="sidebar-overlay no-print" id="sidebarOverlay"></div>

            <aside class="sidebar no-print" id="sidebar">
                <a href="<?= base_url('admin'); ?>" class="sidebar-item">
                    <div class="icon-box"><i class="bi bi-grid-fill"></i></div>
                    <span>Dashboard</span>
                </a>
                <a href="<?= base_url('admin/students'); ?>" class="sidebar-item active">
                    <div class="icon-box"><i class="bi bi-mortarboard-fill"></i></div>
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
                <a href="<?= base_url('admin/verification'); ?>" class="sidebar-item">
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

            <main class="content-area">

                <div class="d-flex justify-content-between align-items-center mb-3 no-print">
                    <h4 class="fw-bold m-0">Growth Pengguna</h4>
                    <button onclick="window.print()" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-printer"></i> Cetak Laporan
                    </button>
                </div>

               <!-- ===================== RINGKASAN ===================== -->
                <div class="report-box">
                    <p class="fw-bold small text-muted mb-3">Ringkasan</p>
                    <div class="stat-row">
                        <div class="stat-col">
                            <div class="stat-mini">
                                <small>Jumlah Pengguna</small>
                                <h3><?= $total_siswa; ?></h3>
                            </div>
                        </div>
                        <div class="stat-col">
                            <div class="stat-mini">
                                <small>Akses Pengguna Bulan Ini</small>
                                <h3><?= $pengguna_akses_bulan_ini; ?></h3>
                            </div>
                        </div>
                        <div class="stat-col">
                            <div class="stat-mini">
                                <small>Pengguna Baru Bulan Ini</small>
                                <h3><?= $siswa_baru_bulan_ini; ?></h3>
                            </div>
                        </div>
                        <div class="stat-col">
                            <?php
                                if ($growth_persen === null) {
                                    $growth_class = '';
                                    $growth_text  = '-';
                                } elseif ($growth_persen >= 0) {
                                    $growth_class = 'growth-up';
                                    $growth_text  = '+' . $growth_persen . '%';
                                } else {
                                    $growth_class = 'growth-down';
                                    $growth_text  = $growth_persen . '%';
                                }
                            ?>
                            <div class="stat-mini <?= $growth_class; ?>">
                                <small>Pertumbuhan Pendaftar Bulan Ini</small>
                                <h3><?= $growth_text; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===================== VISUALISASI UTAMA: PENGGUNA AKTIF PER BULAN ===================== -->
                <div class="report-box">
                    <p class="fw-bold small text-muted mb-1">Akses Pengguna per Bulan</p>
                    <p class="small text-muted mb-2">Jumlah pengguna yang mengakses sistem tiap bulan</p>
                    <div class="chart-wrapper">
                        <canvas id="activeUserChart" role="img" aria-label="Grafik batang jumlah pengguna aktif per bulan, 6 bulan terakhir"></canvas>
                    </div>
                </div>

                <!-- ===================== VISUALISASI KEDUA: ANGGOTA BARU PER BULAN ===================== -->
                <div class="report-box">
                    <p class="fw-bold small text-muted mb-1">Kenaikan Pengguna Baru per Bulan</p>
                    <p class="small text-muted mb-2">Jumlah pengguna baru yang mendaftar tiap bulan</p>
                    <div class="chart-wrapper">
                        <canvas id="growthChart" role="img" aria-label="Grafik batang jumlah siswa baru per bulan, 6 bulan terakhir"></canvas>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        // Ukuran font/label chart menyesuaikan lebar layar agar tetap mudah dibaca di HP.
        // Saat mencetak (forceDesktop = true), selalu pakai gaya desktop/awal —
        // tidak peduli device-nya sedang dalam tampilan responsive HP atau bukan.
        function isMobileScreen() {
            return window.innerWidth <= 576;
        }

        function chartResponsiveOptions(forceDesktop) {
            const mobile = !forceDesktop && isMobileScreen();
            return {
                tickFont: { size: mobile ? 9 : 12 },
                maxRotation: mobile ? 60 : 0,
                minRotation: mobile ? 45 : 0
            };
        }

        // Ukuran chart tetap untuk print, sama seperti tampilan report awal di desktop
        const PRINT_CHART_WIDTH = 680;
        const PRINT_CHART_HEIGHT = 260;

        // ===== Chart 1: Pengguna aktif per bulan =====
        const monthlyActiveData = <?= json_encode($monthly_active); ?>;

        const activeLabels = monthlyActiveData.map(m => m.label);
        const activeJumlah = monthlyActiveData.map(m => m.jumlah);

        const activeOpts = chartResponsiveOptions();
        const activeUserChartInstance = new Chart(document.getElementById('activeUserChart'), {
            type: 'bar',
            data: {
                labels: activeLabels,
                datasets: [{
                    label: 'Pengguna aktif',
                    data: activeJumlah,
                    backgroundColor: '#2a78d6',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.parsed.y + ' pengguna aktif'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: activeOpts.tickFont,
                            maxRotation: activeOpts.maxRotation,
                            minRotation: activeOpts.minRotation
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: activeOpts.tickFont }
                    }
                }
            }
        });

        // ===== Chart 2: Anggota baru per bulan =====
        const growthData = <?= json_encode($growth); ?>;

        const labels    = growthData.map(g => g.bulan);
        const baruBulan = growthData.map(g => g.jumlah_baru);

        const growthOpts = chartResponsiveOptions();
        const growthChartInstance = new Chart(document.getElementById('growthChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Siswa baru',
                    data: baruBulan,
                    backgroundColor: '#eb6834',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => ctx.parsed.y + ' siswa baru'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            font: growthOpts.tickFont,
                            maxRotation: growthOpts.maxRotation,
                            minRotation: growthOpts.minRotation
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, font: growthOpts.tickFont }
                    }
                }
            }
        });

        function applyChartMode(forceDesktop) {
            const opts = chartResponsiveOptions(forceDesktop);

            [activeUserChartInstance, growthChartInstance].forEach(function (chartInstance) {
                chartInstance.options.scales.x.ticks.font = opts.tickFont;
                chartInstance.options.scales.x.ticks.maxRotation = opts.maxRotation;
                chartInstance.options.scales.x.ticks.minRotation = opts.minRotation;
                chartInstance.options.scales.y.ticks.font = opts.tickFont;

                if (forceDesktop) {
                    // Ukuran dipatok manual (bukan ikut lebar layar) supaya hasil cetak
                    // selalu konsisten sama seperti tampilan report di desktop
                    chartInstance.options.responsive = false;
                    chartInstance.resize(PRINT_CHART_WIDTH, PRINT_CHART_HEIGHT);
                } else {
                    chartInstance.options.responsive = true;
                    chartInstance.canvas.style.width = '';
                    chartInstance.canvas.style.height = '';
                    chartInstance.resize();
                }

                chartInstance.update();
            });
        }

        window.addEventListener('beforeprint', function () {
            applyChartMode(true);
        });

        window.addEventListener('afterprint', function () {
            applyChartMode(false);
        });

        // Re-render label chart saat orientasi/lebar layar berubah signifikan
        let lastMobileState = isMobileScreen();
        window.addEventListener('resize', function () {
            const nowMobile = isMobileScreen();
            if (nowMobile !== lastMobileState) {
                lastMobileState = nowMobile;
                applyChartMode(false);
            }
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