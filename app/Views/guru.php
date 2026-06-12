<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kepala Sekolah - Absensi QRcode SMKS Diaspora</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --diaspora-primary: #0D6EFD;
            --diaspora-dark: #0B3D7B;
            --diaspora-light: #E8F0FE;
            --diaspora-gradient: linear-gradient(135deg, #0B3D7B 0%, #0D6EFD 100%);
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #F5F7FA;
            min-height: 100vh;
        }

        /* ========== NAVBAR ========== */
        .navbar-top {
            background: var(--diaspora-gradient);
            height: 60px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
        .navbar-top .brand {
            color: white; font-weight: 700; font-size: 1.1rem;
            text-decoration: none; letter-spacing: 0.5px;
        }
        .navbar-top .brand i { margin-right: 8px; }
        .toggle-sidebar-btn {
            background: none; border: none; color: white;
            font-size: 1.5rem; cursor: pointer;
            padding: 6px 10px; border-radius: 8px;
            transition: all 0.3s; margin-right: 10px;
            display: none;
        }
        .toggle-sidebar-btn:hover { background: rgba(255,255,255,0.15); }
        .navbar-top .user-menu {
            margin-left: auto;
            display: flex; align-items: center; gap: 15px;
        }
        .navbar-top .role-badge {
            background: rgba(255,255,255,0.2); color: white;
            padding: 5px 14px; border-radius: 20px;
            font-size: 0.8rem; font-weight: 500;
        }
        .navbar-top .btn-icon {
            background: none; border: none; color: white;
            font-size: 1.2rem; position: relative; cursor: pointer;
        }
        .navbar-top .btn-icon .badge-notif {
            position: absolute; top: -5px; right: -5px;
            background: #DC3545; color: white;
            font-size: 0.6rem; width: 18px; height: 18px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed; top: 60px; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.05);
            z-index: 1020; overflow-y: auto;
            transition: transform 0.3s ease;
        }
        .sidebar .sidebar-header {
            padding: 20px; text-align: center;
            border-bottom: 1px solid #E2E8F0;
        }
        .sidebar .sidebar-header .avatar {
            width: 65px; height: 65px; border-radius: 50%;
            background: var(--diaspora-light); color: var(--diaspora-primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; margin: 0 auto 10px;
        }
        .sidebar .sidebar-header h6 { font-weight: 700; color: #1A202C; margin-bottom: 2px; }
        .sidebar .sidebar-header small { color: #718096; font-size: 0.75rem; }
        .sidebar .nav-menu { padding: 15px; }
        .sidebar .nav-item { margin-bottom: 4px; }
        .sidebar .nav-link {
            color: #4A5568; padding: 11px 16px; border-radius: 10px;
            transition: all 0.2s; display: flex; align-items: center;
            gap: 12px; font-weight: 500; font-size: 0.9rem;
            cursor: pointer; text-decoration: none;
        }
        .sidebar .nav-link:hover { background: #F7FAFC; color: var(--diaspora-primary); }
        .sidebar .nav-link.active { background: var(--diaspora-light); color: var(--diaspora-primary); font-weight: 700; }
        .sidebar .nav-link i { font-size: 1.1rem; width: 22px; text-align: center; }
        .sidebar .nav-section {
            font-size: 0.7rem; text-transform: uppercase;
            letter-spacing: 1.5px; color: #A0AEC0;
            font-weight: 700; padding: 20px 16px 8px;
        }
        .sidebar-overlay {
            display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5); z-index: 1015;
        }
        .sidebar-overlay.show { display: block; }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 60px;
            padding: 24px;
            min-height: calc(100vh - 60px);
        }

        /* ========== STAT CARDS ========== */
        .stat-card {
            border: none; border-radius: 16px; padding: 22px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05);
            transition: all 0.3s; background: white; cursor: pointer;
        }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(13,110,253,0.12); }
        .stat-card .stat-icon {
            width: 50px; height: 50px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
        }
        .stat-card .stat-value { font-size: 1.8rem; font-weight: 800; color: #1A202C; line-height: 1; }
        .stat-card .stat-label { color: #718096; font-size: 0.85rem; font-weight: 500; }
        .stat-card .stat-sub { font-size: 0.75rem; color: #A0AEC0; }

        /* ========== CARD ========== */
        .card-diaspora {
            border: none; border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.05); background: white;
        }
        .card-diaspora .card-header {
            background: white; border-bottom: 1px solid #E2E8F0;
            font-weight: 700; color: #1A202C;
            border-radius: 16px 16px 0 0;
            padding: 18px 22px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .card-diaspora .card-body { padding: 22px; }
        .card-diaspora .card-footer {
            background: #F7FAFC; border-top: 1px solid #E2E8F0;
            border-radius: 0 0 16px 16px; padding: 14px 22px;
        }

        /* ========== TABLE ========== */
        .table-diaspora { font-size: 0.88rem; }
        .table-diaspora thead th {
            background: #F7FAFC; color: #4A5568;
            font-size: 0.78rem; text-transform: uppercase;
            letter-spacing: 0.5px; font-weight: 700;
            border-bottom: 2px solid #E2E8F0; padding: 14px 12px;
        }
        .table-diaspora tbody td { padding: 12px; vertical-align: middle; }

        /* ========== BADGES ========== */
        .badge-status {
            padding: 6px 12px; border-radius: 8px;
            font-size: 0.78rem; font-weight: 600;
        }
        .badge-tinggi { background: #D1FAE5; color: #065F46; }
        .badge-sedang { background: #FEF3C7; color: #92400E; }
        .badge-rendah { background: #FEE2E2; color: #991B1B; }

        /* ========== PROGRESS BAR ========== */
        .progress-diaspora {
            height: 8px; border-radius: 4px; background: #E2E8F0;
        }
        .progress-diaspora .progress-bar { border-radius: 4px; }

        /* ========== RANKING LIST ========== */
        .ranking-item {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 16px; border-radius: 12px;
            background: #F8FAFC; margin-bottom: 8px;
            transition: all 0.2s;
        }
        .ranking-item:hover { background: var(--diaspora-light); }
        .ranking-item .rank-num {
            width: 38px; height: 38px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1rem; color: white;
        }
        .ranking-item .rank-info { flex: 1; }
        .ranking-item .rank-info .kelas { font-weight: 700; color: #1A202C; }
        .ranking-item .rank-info .detail { font-size: 0.78rem; color: #718096; }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .toggle-sidebar-btn { display: flex; }
        }
    </style>
</head>
<body>

    <!-- ==================== NAVBAR TOP ==================== -->
    <nav class="navbar-top">
        <button class="toggle-sidebar-btn" onclick="toggleSidebar()" id="toggleSidebarBtn">
            <i class="bi bi-list" id="sidebarToggleIcon"></i>
        </button>
        <a href="index.html" class="brand">
            <i class="bi bi-qr-code-scan"></i>Absensi SMKS Diaspora Kotaraja Dalam
        </a>
        <div class="user-menu">
            <button class="btn-icon" title="Notifikasi">
                <i class="bi bi-bell-fill"></i>
                <span class="badge-notif">2</span>
            </button>
            <span class="role-badge">
                <i class="bi bi-star-fill me-1"></i>Kepala Sekolah
            </span>
            <div class="dropdown">
                <button class="btn-icon dropdown-toggle" data-bs-toggle="dropdown" style="color:white;">
                    <i class="bi bi-person-circle fs-5"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profil</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="login.html"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- OVERLAY -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="avatar">
                <i class="bi bi-star-fill"></i>
            </div>
            <h6>Dr. Ahmad Fauzi, M.Pd</h6>
            <small>Kepala SMKS Diaspora Kotaraja</small>
        </div>
        <div class="nav-menu">
            <div class="nav-section">Menu Utama</div>
            <div class="nav-item">
                <a class="nav-link active" href="#" onclick="loadPage('dashboard', this)">
                    <i class="bi bi-grid-fill"></i>Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('harian', this)">
                    <i class="bi bi-calendar-check"></i>Laporan Harian
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('bulanan', this)">
                    <i class="bi bi-calendar-month"></i>Laporan Bulanan
                </a>
            </div>

            <div class="nav-section">Monitoring</div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('per-kelas', this)">
                    <i class="bi bi-building"></i>Kehadiran Per Kelas
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('keterlambatan', this)">
                    <i class="bi bi-clock-history"></i>Rekap Keterlambatan
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('guru-terbaik', this)">
                    <i class="bi bi-trophy-fill"></i>Guru Teraktif
                </a>
            </div>

            <div class="nav-section">Ekspor</div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="exportPDF()">
                    <i class="bi bi-file-pdf"></i>Export PDF
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="exportExcel()">
                    <i class="bi bi-file-excel"></i>Export Excel
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="window.print()">
                    <i class="bi bi-printer"></i>Cetak Laporan
                </a>
            </div>
        </div>
    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="main-content" id="mainContent">
        <!-- Konten dinamis -->
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // ==================== DATA DUMMY ====================
        const DB = {
            kepsek: {
                nama: 'Dr. Ahmad Fauzi, M.Pd',
                nip: '197805152005011002',
                email: 'ahmad@smksdiaspora.sch.id',
            },
            // Statistik umum
            statistik: {
                totalSiswa: 1250,
                totalGuru: 45,
                totalKelas: 36,
                totalJurusan: 3,
                hadirHariIni: 1180,
                izinHariIni: 35,
                sakitHariIni: 20,
                alpaHariIni: 10,
                terlambatHariIni: 5,
                persentaseHariIni: 94.4,
                persentaseBulanan: 93.8,
            },
            // Kehadiran per kelas
            kehadiranPerKelas: [
                { kelas: 'XII RPL A', total: 35, hadir: 34, izin: 1, sakit: 0, alpa: 0, terlambat: 1, persentase: 97.1, waliKelas: 'Budi Santoso, S.Kom' },
                { kelas: 'XII RPL B', total: 35, hadir: 33, izin: 1, sakit: 1, alpa: 0, terlambat: 2, persentase: 94.3, waliKelas: 'Siti Aminah, S.Pd' },
                { kelas: 'XI TKJ A', total: 32, hadir: 31, izin: 0, sakit: 1, alpa: 0, terlambat: 0, persentase: 96.9, waliKelas: 'Rudi Hartono, S.T' },
                { kelas: 'XI TKJ B', total: 32, hadir: 29, izin: 2, sakit: 0, alpa: 1, terlambat: 3, persentase: 90.6, waliKelas: 'Dewi Lestari, S.Pd' },
                { kelas: 'X MM A', total: 30, hadir: 28, izin: 1, sakit: 1, alpa: 0, terlambat: 1, persentase: 93.3, waliKelas: 'Anton Wijaya, S.Kom' },
                { kelas: 'X MM B', total: 30, hadir: 27, izin: 2, sakit: 0, alpa: 1, terlambat: 4, persentase: 90.0, waliKelas: 'Fitriani, S.Sn' },
                { kelas: 'XII TKJ A', total: 33, hadir: 32, izin: 1, sakit: 0, alpa: 0, terlambat: 0, persentase: 97.0, waliKelas: 'Hendra Gunawan, S.T' },
                { kelas: 'XII TKJ B', total: 33, hadir: 31, izin: 1, sakit: 0, alpa: 1, terlambat: 2, persentase: 93.9, waliKelas: 'Ratna Sari, S.Pd' },
            ],
            // Keterlambatan
            topKeterlambatan: [
                { kelas: 'X MM B', total: 4, rataRata: 15, waliKelas: 'Fitriani, S.Sn' },
                { kelas: 'XI TKJ B', total: 3, rataRata: 12, waliKelas: 'Dewi Lestari, S.Pd' },
                { kelas: 'XII RPL B', total: 2, rataRata: 10, waliKelas: 'Siti Aminah, S.Pd' },
                { kelas: 'X MM A', total: 1, rataRata: 8, waliKelas: 'Anton Wijaya, S.Kom' },
                { kelas: 'XII RPL A', total: 1, rataRata: 10, waliKelas: 'Budi Santoso, S.Kom' },
            ],
            // Trend 7 hari
            trendHarian: [
                { tanggal: '6 Mei', hadir: 1150, tidakHadir: 100 },
                { tanggal: '7 Mei', hadir: 1165, tidakHadir: 85 },
                { tanggal: '8 Mei', hadir: 1140, tidakHadir: 110 },
                { tanggal: '9 Mei', hadir: 1170, tidakHadir: 80 },
                { tanggal: '10 Mei', hadir: 1180, tidakHadir: 70 },
                { tanggal: '11 Mei', hadir: 1160, tidakHadir: 90 },
                { tanggal: '12 Mei', hadir: 1180, tidakHadir: 70 },
            ],
            // Guru teraktif (input absensi)
            guruTeraktif: [
                { nama: 'Budi Santoso, S.Kom', totalInput: 156, qrGenerated: 28, kelas: 'XII RPL A' },
                { nama: 'Siti Aminah, S.Pd', totalInput: 142, qrGenerated: 25, kelas: 'XII RPL B' },
                { nama: 'Rudi Hartono, S.T', totalInput: 135, qrGenerated: 22, kelas: 'XI TKJ A' },
                { nama: 'Anton Wijaya, S.Kom', totalInput: 128, qrGenerated: 20, kelas: 'X MM A' },
                { nama: 'Dewi Lestari, S.Pd', totalInput: 120, qrGenerated: 18, kelas: 'XI TKJ B' },
            ],
            // Bulanan
            trendBulanan: [
                { bulan: 'Jan', persentase: 94.2 },
                { bulan: 'Feb', persentase: 93.8 },
                { bulan: 'Mar', persentase: 95.1 },
                { bulan: 'Apr', persentase: 94.5 },
                { bulan: 'Mei', persentase: 93.8 },
            ],
        };

        // ==================== TOGGLE SIDEBAR ====================
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const icon = document.getElementById('sidebarToggleIcon');
            sidebar.classList.toggle('show');
            if (sidebar.classList.contains('show')) {
                overlay.classList.add('show');
                icon.classList.replace('bi-list', 'bi-x-lg');
            } else {
                overlay.classList.remove('show');
                icon.classList.replace('bi-x-lg', 'bi-list');
            }
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.remove('show');
            document.getElementById('sidebarToggleIcon').classList.replace('bi-x-lg', 'bi-list');
        }

        // ==================== LOAD PAGE ====================
        function loadPage(page, el) {
            if (el) {
                document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
                el.classList.add('active');
            }
            closeSidebar();
            switch(page) {
                case 'dashboard': renderDashboard(); break;
                case 'harian': renderHarian(); break;
                case 'bulanan': renderBulanan(); break;
                case 'per-kelas': renderPerKelas(); break;
                case 'keterlambatan': renderKeterlambatan(); break;
                case 'guru-terbaik': renderGuruTerbaik(); break;
            }
        }

        // ==================== DASHBOARD UTAMA ====================
        function renderDashboard() {
            const s = DB.statistik;
            let html = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="bi bi-grid-fill text-primary me-2"></i>Dashboard Kepala Sekolah</h4>
                        <p class="text-muted mb-0">Monitoring kehadiran SMKS Diaspora Kotaraja | ${new Date().toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'})}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-danger rounded-pill" onclick="exportPDF()"><i class="bi bi-file-pdf me-1"></i>PDF</button>
                        <button class="btn btn-outline-success rounded-pill" onclick="exportExcel()"><i class="bi bi-file-excel me-1"></i>Excel</button>
                    </div>
                </div>

                <!-- Statistik Utama -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-lg-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="stat-icon" style="background:#E8F0FE;color:#0D6EFD;"><i class="bi bi-people-fill"></i></div>
                            </div>
                            <div class="stat-value">${s.totalSiswa.toLocaleString()}</div>
                            <div class="stat-label">Total Siswa</div>
                            <div class="stat-sub">${s.totalKelas} kelas aktif</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="stat-icon" style="background:#D1FAE5;color:#059669;"><i class="bi bi-check-circle-fill"></i></div>
                            </div>
                            <div class="stat-value">${s.hadirHariIni.toLocaleString()}</div>
                            <div class="stat-label">Hadir Hari Ini</div>
                            <div class="stat-sub">${s.persentaseHariIni}% kehadiran</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="stat-icon" style="background:#FEE2E2;color:#DC2626;"><i class="bi bi-exclamation-triangle-fill"></i></div>
                            </div>
                            <div class="stat-value">${s.alpaHariIni + s.terlambatHariIni}</div>
                            <div class="stat-label">Alpa & Terlambat</div>
                            <div class="stat-sub">${s.terlambatHariIni} terlambat</div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="stat-icon" style="background:#EDE9FE;color:#7C3AED;"><i class="bi bi-graph-up-arrow"></i></div>
                            </div>
                            <div class="stat-value">${s.persentaseBulanan}%</div>
                            <div class="stat-label">Rata² Bulanan</div>
                            <div class="stat-sub">Mei 2026</div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Trend + Ranking -->
                <div class="row g-3 mb-4">
                    <div class="col-lg-8">
                        <div class="card-diaspora">
                            <div class="card-header">
                                <span><i class="bi bi-graph-up me-2"></i>Trend Kehadiran 7 Hari Terakhir</span>
                            </div>
                            <div class="card-body">
                                <canvas id="chartTrendHarian" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-diaspora h-100">
                            <div class="card-header">
                                <span><i class="bi bi-trophy me-2"></i>Top 5 Kelas Hari Ini</span>
                            </div>
                            <div class="card-body p-0">
                                ${DB.kehadiranPerKelas
                                    .sort((a, b) => b.persentase - a.persentase)
                                    .slice(0, 5)
                                    .map((k, i) => {
                                        const medals = ['🥇','🥈','🥉','4','5'];
                                        const colors = ['#FFD700','#C0C0C0','#CD7F32','#0D6EFD','#0D6EFD'];
                                        return `
                                            <div class="ranking-item">
                                                <div class="rank-num" style="background:${colors[i]};color:${i<3?'#1A202C':'white'};">${medals[i]}</div>
                                                <div class="rank-info">
                                                    <div class="kelas">${k.kelas}</div>
                                                    <div class="detail">Hadir: ${k.hadir}/${k.total} (${k.persentase}%)</div>
                                                </div>
                                            </div>
                                        `;
                                    }).join('')}
                            </div>
                            <div class="card-footer text-center">
                                <a href="#" onclick="loadPage('per-kelas')" class="text-primary text-decoration-none small">Lihat Semua Kelas →</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Keterlambatan + Guru Teraktif -->
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="card-diaspora">
                            <div class="card-header">
                                <span><i class="bi bi-exclamation-triangle me-2"></i>Kelas Keterlambatan Tertinggi</span>
                                <a href="#" onclick="loadPage('keterlambatan')" class="text-danger small text-decoration-none">Detail →</a>
                            </div>
                            <div class="card-body p-0">
                                ${DB.topKeterlambatan.slice(0, 3).map((k, i) => {
                                    const icons = ['🔴','🟠','🟡'];
                                    return `
                                        <div class="ranking-item">
                                            <div style="font-size:1.3rem;">${icons[i]}</div>
                                            <div class="rank-info">
                                                <div class="kelas">${k.kelas}</div>
                                                <div class="detail">${k.total} siswa | Rata² ${k.rataRata} menit</div>
                                            </div>
                                            <small class="text-muted">Wali: ${k.waliKelas.split(',')[0]}</small>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card-diaspora">
                            <div class="card-header">
                                <span><i class="bi bi-star-fill me-2"></i>Guru Teraktif (Input Absensi)</span>
                            </div>
                            <div class="card-body p-0">
                                ${DB.guruTeraktif.slice(0, 3).map((g, i) => {
                                    const medals = ['🥇','🥈','🥉'];
                                    return `
                                        <div class="ranking-item">
                                            <div style="font-size:1.5rem;">${medals[i]}</div>
                                            <div class="rank-info">
                                                <div class="kelas">${g.nama}</div>
                                                <div class="detail">${g.totalInput} input | ${g.qrGenerated} QR Generated</div>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('mainContent').innerHTML = html;

            // Render Chart
            setTimeout(() => {
                const ctx = document.getElementById('chartTrendHarian');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: DB.trendHarian.map(t => t.tanggal),
                            datasets: [
                                {
                                    label: 'Hadir',
                                    data: DB.trendHarian.map(t => t.hadir),
                                    borderColor: '#059669',
                                    backgroundColor: 'rgba(5,150,105,0.1)',
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 3,
                                },
                                {
                                    label: 'Tidak Hadir',
                                    data: DB.trendHarian.map(t => t.tidakHadir),
                                    borderColor: '#DC2626',
                                    backgroundColor: 'rgba(220,38,38,0.05)',
                                    fill: true,
                                    tension: 0.4,
                                    borderWidth: 3,
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { position: 'bottom' } },
                            scales: { y: { beginAtZero: false, min: 0, max: 1300 } }
                        }
                    });
                }
            }, 300);
        }

        // ==================== LAPORAN HARIAN ====================
        function renderHarian() {
            const s = DB.statistik;
            let html = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="bi bi-calendar-check text-primary me-2"></i>Laporan Harian</h4>
                        <p class="text-muted mb-0">${new Date().toLocaleDateString('id-ID', {weekday:'long', day:'numeric', month:'long', year:'numeric'})}</p>
                    </div>
                    <input type="date" class="form-control rounded-pill" value="2026-05-12" style="width:180px;" onchange="alert('Filter tanggal: '+this.value)">
                </div>

                <!-- Ringkasan -->
                <div class="row g-3 mb-4">
                    <div class="col-4 col-md-2"><div class="stat-card text-center p-3"><div class="stat-value text-success">${s.hadirHariIni.toLocaleString()}</div><div class="stat-label">Hadir</div></div></div>
                    <div class="col-4 col-md-2"><div class="stat-card text-center p-3"><div class="stat-value text-warning">${s.izinHariIni}</div><div class="stat-label">Izin</div></div></div>
                    <div class="col-4 col-md-2"><div class="stat-card text-center p-3"><div class="stat-value text-danger">${s.sakitHariIni}</div><div class="stat-label">Sakit</div></div></div>
                    <div class="col-4 col-md-2"><div class="stat-card text-center p-3"><div class="stat-value text-secondary">${s.alpaHariIni}</div><div class="stat-label">Alpa</div></div></div>
                    <div class="col-4 col-md-2"><div class="stat-card text-center p-3"><div class="stat-value" style="color:#9A3412;">${s.terlambatHariIni}</div><div class="stat-label">Terlambat</div></div></div>
                    <div class="col-4 col-md-2"><div class="stat-card text-center p-3"><div class="stat-value text-primary">${s.persentaseHariIni}%</div><div class="stat-label">Kehadiran</div></div></div>
                </div>

                <!-- Tabel Per Kelas -->
                <div class="card-diaspora">
                    <div class="card-header"><span><i class="bi bi-building me-2"></i>Detail Per Kelas</span></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-diaspora mb-0">
                                <thead>
                                    <tr><th>Kelas</th><th>Wali Kelas</th><th>Total</th><th>Hadir</th><th>Izin</th><th>Sakit</th><th>Alpa</th><th>Terlambat</th><th>%</th></tr>
                                </thead>
                                <tbody>
            `;

            DB.kehadiranPerKelas.forEach(k => {
                let badgeClass = k.persentase >= 95 ? 'badge-tinggi' : k.persentase >= 90 ? 'badge-sedang' : 'badge-rendah';
                html += `
                    <tr>
                        <td><strong>${k.kelas}</strong></td>
                        <td>${k.waliKelas}</td>
                        <td>${k.total}</td>
                        <td>${k.hadir}</td>
                        <td>${k.izin}</td>
                        <td>${k.sakit}</td>
                        <td>${k.alpa}</td>
                        <td>${k.terlambat}</td>
                        <td><span class="badge-status ${badgeClass}">${k.persentase}%</span></td>
                    </tr>
                `;
            });

            html += `</tbody></table></div></div>`;
            document.getElementById('mainContent').innerHTML = html;
        }

        // ==================== LAPORAN BULANAN ====================
        function renderBulanan() {
            let html = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="bi bi-calendar-month text-primary me-2"></i>Laporan Bulanan</h4>
                        <p class="text-muted mb-0">Trend kehadiran tahun ajaran 2025/2026</p>
                    </div>
                    <select class="form-select rounded-pill" style="width:200px;">
                        <option>2025/2026</option>
                        <option>2024/2025</option>
                    </select>
                </div>
                <div class="card-diaspora mb-4">
                    <div class="card-body">
                        <canvas id="chartBulanan" height="250"></canvas>
                    </div>
                </div>
                <div class="card-diaspora">
                    <div class="card-header"><span><i class="bi bi-table me-2"></i>Detail Bulanan</span></div>
                    <div class="card-body p-0">
                        <table class="table table-diaspora mb-0">
                            <thead><tr><th>Bulan</th><th>Persentase</th><th>Status</th></tr></thead>
                            <tbody>
                                ${DB.trendBulanan.map(b => `
                                    <tr>
                                        <td>${b.bulan} 2026</td>
                                        <td><strong>${b.persentase}%</strong></td>
                                        <td><span class="badge-status ${b.persentase >= 95 ? 'badge-tinggi' : b.persentase >= 90 ? 'badge-sedang' : 'badge-rendah'}">${b.persentase >= 95 ? 'Baik' : b.persentase >= 90 ? 'Cukup' : 'Perlu Perhatian'}</span></td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;

            document.getElementById('mainContent').innerHTML = html;

            setTimeout(() => {
                const ctx = document.getElementById('chartBulanan');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: DB.trendBulanan.map(b => b.bulan),
                            datasets: [{
                                label: 'Persentase Kehadiran',
                                data: DB.trendBulanan.map(b => b.persentase),
                                backgroundColor: DB.trendBulanan.map(b => b.persentase >= 95 ? '#059669' : b.persentase >= 90 ? '#D97706' : '#DC2626'),
                                borderRadius: 8,
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: { legend: { display: false } },
                            scales: { y: { min: 80, max: 100 } }
                        }
                    });
                }
            }, 300);
        }

        // ==================== PER KELAS ====================
        function renderPerKelas() {
            let html = `
                <h4 class="fw-bold mb-4"><i class="bi bi-building text-primary me-2"></i>Kehadiran Per Kelas</h4>
                <div class="row g-3">
            `;

            DB.kehadiranPerKelas.forEach(k => {
                let barColor = k.persentase >= 95 ? '#059669' : k.persentase >= 90 ? '#D97706' : '#DC2626';
                html += `
                    <div class="col-md-6 col-lg-4">
                        <div class="card-diaspora">
                            <div class="card-body">
                                <h6 class="fw-bold">${k.kelas}</h6>
                                <p class="text-muted small mb-2">Wali: ${k.waliKelas}</p>
                                <div class="d-flex justify-content-between mb-1"><span>Hadir</span><strong>${k.hadir}/${k.total}</strong></div>
                                <div class="progress progress-diaspora mb-3">
                                    <div class="progress-bar" style="width:${k.persentase}%;background:${barColor};"></div>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Izin: ${k.izin}</span>
                                    <span>Sakit: ${k.sakit}</span>
                                    <span>Alpa: ${k.alpa}</span>
                                    <span>Terlambat: ${k.terlambat}</span>
                                </div>
                                <div class="text-end mt-2"><strong style="color:${barColor};">${k.persentase}%</strong></div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `</div>`;
            document.getElementById('mainContent').innerHTML = html;
        }

        // ==================== KETERLAMBATAN ====================
        function renderKeterlambatan() {
            let html = `
                <h4 class="fw-bold mb-4"><i class="bi bi-clock-history text-danger me-2"></i>Rekap Keterlambatan</h4>
                <div class="card-diaspora">
                    <div class="card-body p-0">
                        <table class="table table-diaspora mb-0">
                            <thead><tr><th>No</th><th>Kelas</th><th>Wali Kelas</th><th>Jumlah Siswa</th><th>Rata² Keterlambatan</th><th>Status</th></tr></thead>
                            <tbody>
                                ${DB.topKeterlambatan.map((k, i) => `
                                    <tr>
                                        <td>${i+1}</td>
                                        <td><strong>${k.kelas}</strong></td>
                                        <td>${k.waliKelas}</td>
                                        <td>${k.total} siswa</td>
                                        <td>${k.rataRata} menit</td>
                                        <td><span class="badge-status ${k.rataRata > 10 ? 'badge-rendah' : 'badge-sedang'}">${k.rataRata > 10 ? '⚠️ Tinggi' : '⚠️ Sedang'}</span></td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            document.getElementById('mainContent').innerHTML = html;
        }

        // ==================== GURU TERAKTIF ====================
        function renderGuruTerbaik() {
            let html = `
                <h4 class="fw-bold mb-4"><i class="bi bi-trophy-fill text-warning me-2"></i>Guru Teraktif</h4>
                <div class="row g-3">
            `;

            DB.guruTeraktif.forEach((g, i) => {
                const medals = ['🥇','🥈','🥉','🏅','🏅'];
                html += `
                    <div class="col-md-6">
                        <div class="card-diaspora">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <div style="font-size:2.5rem;">${medals[i]}</div>
                                    <div>
                                        <h6 class="fw-bold mb-1">${g.nama}</h6>
                                        <p class="text-muted small mb-2">Wali Kelas ${g.kelas}</p>
                                        <div class="d-flex gap-3">
                                            <div><small class="text-muted">Input Absensi</small><br><strong>${g.totalInput}</strong></div>
                                            <div><small class="text-muted">QR Generated</small><br><strong>${g.qrGenerated}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += `</div>`;
            document.getElementById('mainContent').innerHTML = html;
        }

        // ==================== EXPORT ====================
        function exportPDF() {
            alert('📄 Laporan akan diunduh dalam format PDF.\n\nFitur ini akan terintegrasi dengan library jsPDF.');
        }
        function exportExcel() {
            alert('📊 Laporan akan diunduh dalam format Excel.\n\nFitur ini akan terintegrasi dengan library SheetJS.');
        }

        // ==================== INIT ====================
        document.addEventListener('DOMContentLoaded', () => renderDashboard());
    </script>
</body>
</html>