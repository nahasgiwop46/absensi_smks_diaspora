<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Absensi QRcode SMKS Diaspora</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1D4ED8;
            --primary-light: #DBEAFE;
            --white: #FFFFFF;
            --gray-light: #F3F4F6;
            --gray: #6B7280;
            --dark: #111827;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #06B6D4;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gray-light);
            color: var(--dark);
            min-height: 100vh;
        }

        /* ========== NAVBAR ========== */
        .navbar-top {
            background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%);
            height: 64px;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1040;
            display: flex;
            align-items: center;
            padding: 0 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .navbar-top .brand {
            color: white;
            font-weight: 700;
            font-size: 1.15rem;
            text-decoration: none;
            letter-spacing: -0.3px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .navbar-top .brand .logo-icon {
            width: 38px; height: 38px;
            background: rgba(255,255,255,0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }
        .toggle-sidebar-btn {
            display: none;
            background: none; border: none; color: white;
            font-size: 1.4rem; cursor: pointer;
            padding: 6px; border-radius: 8px;
            margin-right: 12px;
        }
        .toggle-sidebar-btn:hover { background: rgba(255,255,255,0.1); }
        .navbar-top .right-menu {
            margin-left: auto;
            display: flex; align-items: center; gap: 16px;
        }
        .navbar-top .btn-notif {
            background: none; border: none; color: white;
            font-size: 1.2rem; position: relative; cursor: pointer;
            padding: 8px; border-radius: 10px;
        }
        .navbar-top .btn-notif:hover { background: rgba(255,255,255,0.1); }
        .navbar-top .btn-notif .dot {
            position: absolute; top: 6px; right: 6px;
            width: 8px; height: 8px;
            background: var(--danger); border-radius: 50%;
        }
        .navbar-top .user-dropdown .btn-user {
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            color: white; border-radius: 12px;
            padding: 8px 14px; font-size: 0.85rem;
            font-weight: 500; cursor: pointer;
            display: flex; align-items: center; gap: 8px;
        }
        .navbar-top .user-dropdown .btn-user:hover { background: rgba(255,255,255,0.25); }
        .navbar-top .user-dropdown .avatar-sm {
            width: 30px; height: 30px; border-radius: 8px;
            background: rgba(255,255,255,0.3);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.85rem;
        }

        /* ========== SIDEBAR ========== */
        .sidebar {
            position: fixed; top: 64px; left: 0; bottom: 0;
            width: var(--sidebar-width);
            background: var(--white);
            border-right: 1px solid #E5E7EB;
            z-index: 1030; overflow-y: auto;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar .sidebar-logo {
            padding: 20px; text-align: center;
            border-bottom: 1px solid #F3F4F6;
        }
        .sidebar .sidebar-logo .avatar-lg {
            width: 56px; height: 56px; border-radius: 14px;
            background: var(--primary-light); color: var(--primary);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin: 0 auto 10px;
        }
        .sidebar .sidebar-logo h6 { font-weight: 700; color: var(--dark); font-size: 0.9rem; }
        .sidebar .sidebar-logo small { color: var(--gray); font-size: 0.75rem; }
        .sidebar .nav-menu { padding: 16px 12px; }
        .sidebar .nav-section {
            font-size: 0.68rem; text-transform: uppercase;
            letter-spacing: 1.5px; color: #9CA3AF;
            font-weight: 700; padding: 16px 12px 6px;
        }
        .sidebar .nav-link {
            color: #4B5563; padding: 10px 14px; border-radius: 10px;
            display: flex; align-items: center; gap: 12px;
            font-size: 0.88rem; font-weight: 500;
            text-decoration: none; cursor: pointer;
            transition: all 0.15s; margin-bottom: 2px;
        }
        .sidebar .nav-link:hover { background: #F9FAFB; color: var(--primary); }
        .sidebar .nav-link.active {
            background: var(--primary-light); color: var(--primary);
            font-weight: 600;
        }
        .sidebar .nav-link i { font-size: 1.1rem; width: 22px; text-align: center; }
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.4); z-index: 1025;
        }
        .sidebar-overlay.show { display: block; }

        /* ========== MAIN CONTENT ========== */
        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: 64px;
            padding: 28px;
            min-height: calc(100vh - 64px);
        }

        /* ========== STAT CARDS ========== */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--white);
            border-radius: 16px; padding: 22px 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.06);
            transition: all 0.2s; cursor: pointer;
            border: 1px solid #F3F4F6;
        }
        .stat-card:hover {
            box-shadow: 0 10px 25px rgba(37,99,235,0.1);
            border-color: #DBEAFE;
        }
        .stat-card .icon-wrap {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; margin-bottom: 14px;
        }
        .stat-card .stat-value { font-size: 1.8rem; font-weight: 800; color: var(--dark); line-height: 1; }
        .stat-card .stat-label { font-size: 0.85rem; color: var(--gray); font-weight: 500; margin-top: 4px; }
        .stat-card .stat-sub { font-size: 0.75rem; margin-top: 2px; }

        /* ========== CARD PANEL ========== */
        .card-panel {
            background: var(--white);
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            border: 1px solid #F3F4F6;
            margin-bottom: 24px;
        }
        .card-panel .card-header {
            padding: 18px 24px; border-bottom: 1px solid #F3F4F6;
            font-weight: 700; font-size: 0.95rem;
            display: flex; justify-content: space-between; align-items: center;
        }
        .card-panel .card-body { padding: 20px 24px; }
        .card-panel .card-body.p-0 { padding: 0; }

        /* ========== TABLE ========== */
        .table-modern {
            width: 100%; font-size: 0.88rem; border-collapse: collapse;
        }
        .table-modern thead th {
            background: #F9FAFB; color: var(--gray);
            font-size: 0.75rem; text-transform: uppercase;
            letter-spacing: 0.5px; font-weight: 700;
            padding: 12px 16px; border-bottom: 2px solid #E5E7EB;
            white-space: nowrap;
        }
        .table-modern tbody td {
            padding: 12px 16px; border-bottom: 1px solid #F3F4F6;
            vertical-align: middle;
        }
        .table-modern tbody tr:hover { background: #FAFAFA; }

        /* ========== BADGES ========== */
        .badge-sm {
            padding: 5px 10px; border-radius: 8px;
            font-size: 0.75rem; font-weight: 600;
        }
        .badge-hadir { background: #D1FAE5; color: #065F46; }
        .badge-izin { background: #FEF3C7; color: #92400E; }
        .badge-sakit { background: #FEE2E2; color: #991B1B; }
        .badge-alpa { background: #F3F4F6; color: #4B5563; }
        .badge-terlambat { background: #FED7AA; color: #9A3412; }
        .badge-active { background: #D1FAE5; color: #065F46; }
        .badge-inactive { background: #FEE2E2; color: #991B1B; }

        /* ========== BUTTONS ========== */
        .btn-primary-sm {
            background: var(--primary); color: white;
            border: none; padding: 9px 18px; border-radius: 10px;
            font-weight: 600; font-size: 0.85rem; cursor: pointer;
            transition: all 0.2s; display: inline-flex; align-items: center; gap: 6px;
        }
        .btn-primary-sm:hover { background: var(--primary-dark); box-shadow: 0 4px 12px rgba(37,99,235,0.3); }
        .btn-outline-sm {
            background: white; color: var(--primary);
            border: 1.5px solid #DBEAFE; padding: 9px 18px;
            border-radius: 10px; font-weight: 600; font-size: 0.85rem;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-outline-sm:hover { background: var(--primary-light); }

        /* ========== MODAL ========== */
        .modal-modern .modal-content {
            border: none; border-radius: 20px; overflow: hidden;
        }
        .modal-modern .modal-header {
            padding: 20px 24px; border-bottom: 1px solid #F3F4F6;
            font-weight: 700;
        }
        .modal-modern .modal-body { padding: 24px; }
        .modal-modern .modal-footer {
            padding: 16px 24px; border-top: 1px solid #F3F4F6;
        }

        /* ========== TOAST ========== */
        .toast-float {
            position: fixed; top: 80px; right: 24px;
            z-index: 9999; padding: 14px 20px;
            border-radius: 14px; color: white; font-weight: 600;
            font-size: 0.9rem; box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease; display: none;
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .toggle-sidebar-btn { display: flex; }
            .stat-grid { grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 12px; }
        }
    </style>
</head>
<body>

    <!-- ==================== NAVBAR ==================== -->
    <nav class="navbar-top">
        <button class="toggle-sidebar-btn" onclick="toggleSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <a href="#" class="brand">
            <div class="logo-icon"><i class="bi bi-qr-code-scan"></i></div>
            Absensi QRcode SMKS Diaspora
        </a>
        <div class="right-menu">
            <button class="btn-notif">
                <i class="bi bi-bell"></i>
                <span class="dot"></span>
            </button>
            <div class="user-dropdown dropdown">
                <button class="btn-user dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="avatar-sm"><i class="bi bi-shield-check"></i></div>
                    Admin
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
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
        <div class="sidebar-logo">
            <div class="avatar-lg"><i class="bi bi-shield-check"></i></div>
            <h6>Administrator12</h6>
            <small>admin@smksdiaspora.sch.id</small>
        </div>
        <div class="nav-menu">
            <div class="nav-section">Menu Utama</div>
            <a class="nav-link active" href="#" data-page="dashboard">
                <i class="bi bi-grid-1x2-fill"></i>Dashboard
            </a>
            <a class="nav-link" href="#" data-page="absensi">
                <i class="bi bi-clipboard-check"></i>Monitoring Absensi
            </a>
            <a class="nav-link" href="#" data-page="rekap">
                <i class="bi bi-file-bar-graph"></i>Rekap Absensi
            </a>

            <div class="nav-section">Data Master</div>
            <a class="nav-link" href="#" data-page="users">
                <i class="bi bi-people-fill"></i>Manajemen User
            </a>
            <a class="nav-link" href="#" data-page="siswa">
                <i class="bi bi-mortarboard-fill"></i>Data Siswa
            </a>
            <a class="nav-link" href="#" data-page="guru">
                <i class="bi bi-person-badge"></i>Data Guru
            </a>
            <a class="nav-link" href="#" data-page="kelas">
                <i class="bi bi-door-open"></i>Data Kelas
            </a>
            <a class="nav-link" href="#" data-page="jurusan">
                <i class="bi bi-diagram-3"></i>Data Jurusan
            </a>
            <a class="nav-link" href="#" data-page="mapel">
                <i class="bi bi-book"></i>Mata Pelajaran
            </a>
            <a class="nav-link" href="#" data-page="jadwal">
                <i class="bi bi-calendar-week"></i>Jadwal Pelajaran
            </a>

            <div class="nav-section">Akademik</div>
            <a class="nav-link" href="#" data-page="tahun-ajaran">
                <i class="bi bi-calendar3"></i>Tahun Ajaran
            </a>
            <a class="nav-link" href="#" data-page="semester">
                <i class="bi bi-calendar-check"></i>Semester
            </a>
            <a class="nav-link" href="#" data-page="penempatan">
                <i class="bi bi-person-lines-fill"></i>Penempatan Siswa
            </a>

            <div class="nav-section">Sistem</div>
            <a class="nav-link" href="#" data-page="pengaturan">
                <i class="bi bi-gear"></i>Pengaturan
            </a>
            <a class="nav-link" href="#" data-page="log">
                <i class="bi bi-journal-text"></i>Log Aktivitas
            </a>
        </div>
    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="main-content" id="mainContent">
        <!-- Dinamis diisi JS -->
    </div>

    <!-- ==================== TOAST ==================== -->
    <div class="toast-float" id="toast"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ==================== DATA ====================
        const DB = {
            statistik: { totalSiswa: 1250, totalGuru: 45, totalKelas: 36, hadir: 1180, izin: 35, sakit: 20, alpa: 10, terlambat: 5, persentase: 94.4 },
            users: [
                { id:1, username:'admin_diaspora', nama:'Administrator', role:'Admin', email:'admin@smksdiaspora.sch.id', is_active:1 },
                { id:2, username:'budi_guru', nama:'Budi Santoso, S.Kom', role:'Guru', email:'budi@smksdiaspora.sch.id', is_active:1 },
                { id:3, username:'siti_guru', nama:'Siti Aminah, S.Pd', role:'Guru', email:'siti@smksdiaspora.sch.id', is_active:1 },
                { id:4, username:'ahmad_kepsek', nama:'Dr. Ahmad Fauzi, M.Pd', role:'Kepsek', email:'ahmad@smksdiaspora.sch.id', is_active:1 },
            ],
            siswa: [
                { id:1, nis:'2024001', nama:'Andi Pratama', kelas:'XII RPL A', is_active:1 },
                { id:2, nis:'2024002', nama:'Budi Setiawan', kelas:'XII RPL A', is_active:1 },
                { id:3, nis:'2024003', nama:'Cici Nurhaliza', kelas:'XII RPL A', is_active:1 },
                { id:4, nis:'2024004', nama:'Dewi Sartika', kelas:'XI TKJ A', is_active:1 },
            ],
            absensi: [
                { nis:'2024001', nama:'Andi Pratama', kelas:'XII RPL A', status:'Terlambat', jam:'07:10', metode:'QR' },
                { nis:'2024002', nama:'Budi Setiawan', kelas:'XII RPL A', status:'Hadir', jam:'06:55', metode:'QR' },
                { nis:'2024003', nama:'Cici Nurhaliza', kelas:'XII RPL A', status:'Izin', jam:'-', metode:'Manual' },
                { nis:'2024004', nama:'Dewi Sartika', kelas:'XI TKJ A', status:'Hadir', jam:'06:50', metode:'QR' },
            ],
            kelas: [
                { id:1, tingkat:'XII', jurusan:'RPL', rombel:'A', kapasitas:35 },
                { id:2, tingkat:'XII', jurusan:'RPL', rombel:'B', kapasitas:35 },
                { id:3, tingkat:'XI', jurusan:'TKJ', rombel:'A', kapasitas:30 },
            ],
            jurusan: [
                { id:1, kode:'RPL', singkatan:'RPL', nama:'Rekayasa Perangkat Lunak' },
                { id:2, kode:'TKJ', singkatan:'TKJ', nama:'Teknik Komputer dan Jaringan' },
            ],
        };

        // ==================== SIDEBAR TOGGLE ====================
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('show');
            document.getElementById('sidebarOverlay').classList.remove('show');
        }

        // ==================== TOAST ====================
        function showToast(msg, bg='#2563EB') {
            const t = document.getElementById('toast');
            t.textContent = msg; t.style.background = bg; t.style.display = 'block';
            setTimeout(() => t.style.display = 'none', 3000);
        }

        // ==================== NAVIGASI ====================
        document.querySelectorAll('.sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.sidebar .nav-link').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                const page = this.dataset.page;
                if (page === 'dashboard') renderDashboard();
                else if (page === 'absensi') renderMonitoring();
                else if (page === 'siswa') renderSiswa();
                else if (page === 'users') renderUsers();
                else if (page === 'kelas') renderKelas();
                else if (page === 'jurusan') renderJurusan();
                else if (page === 'mapel') renderPlaceholder('Mata Pelajaran');
                else if (page === 'jadwal') renderPlaceholder('Jadwal Pelajaran');
                else if (page === 'guru') renderGuru();
                else if (page === 'rekap') renderRekap();
                else if (page === 'pengaturan') renderPlaceholder('Pengaturan');
                else if (page === 'log') renderPlaceholder('Log Aktivitas');
                else if (page === 'tahun-ajaran') renderPlaceholder('Tahun Ajaran');
                else if (page === 'semester') renderPlaceholder('Semester');
                else if (page === 'penempatan') renderPlaceholder('Penempatan Siswa');
                closeSidebar();
            });
        });

        // ==================== DASHBOARD ====================
        function renderDashboard() {
            const s = DB.statistik;
            document.getElementById('mainContent').innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">Dashboard Admin</h4>
                        <p class="text-muted mb-0" style="font-size:0.9rem;">Ringkasan data sistem absensi</p>
                    </div>
                    <span class="badge bg-light text-dark p-2 rounded-3">
                        <i class="bi bi-calendar3 me-1"></i>${new Date().toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'})}
                    </span>
                </div>

                <div class="stat-grid">
                    <div class="stat-card">
                        <div class="icon-wrap" style="background:#DBEAFE;color:#2563EB;"><i class="bi bi-mortarboard-fill"></i></div>
                        <div class="stat-value">${s.totalSiswa.toLocaleString()}</div>
                        <div class="stat-label">Total Siswa</div>
                    </div>
                    <div class="stat-card">
                        <div class="icon-wrap" style="background:#D1FAE5;color:#10B981;"><i class="bi bi-people-fill"></i></div>
                        <div class="stat-value">${s.totalGuru}</div>
                        <div class="stat-label">Total Guru</div>
                    </div>
                    <div class="stat-card">
                        <div class="icon-wrap" style="background:#EDE9FE;color:#7C3AED;"><i class="bi bi-door-open"></i></div>
                        <div class="stat-value">${s.totalKelas}</div>
                        <div class="stat-label">Kelas Aktif</div>
                    </div>
                    <div class="stat-card">
                        <div class="icon-wrap" style="background:#D1FAE5;color:#10B981;"><i class="bi bi-check-circle-fill"></i></div>
                        <div class="stat-value">${s.hadir.toLocaleString()}</div>
                        <div class="stat-label">Hadir Hari Ini</div>
                        <div class="stat-sub text-success">${s.persentase}% kehadiran</div>
                    </div>
                    <div class="stat-card">
                        <div class="icon-wrap" style="background:#FED7AA;color:#9A3412;"><i class="bi bi-clock-fill"></i></div>
                        <div class="stat-value">${s.terlambat}</div>
                        <div class="stat-label">Terlambat</div>
                    </div>
                    <div class="stat-card">
                        <div class="icon-wrap" style="background:#FEE2E2;color:#EF4444;"><i class="bi bi-x-circle-fill"></i></div>
                        <div class="stat-value">${s.alpa}</div>
                        <div class="stat-label">Tidak Hadir (Alpa)</div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card-panel">
                            <div class="card-header"><span><i class="bi bi-bar-chart me-2"></i>Grafik Kehadiran 7 Hari</span></div>
                            <div class="card-body"><canvas id="chartKehadiran" height="250"></canvas></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card-panel h-100">
                            <div class="card-header"><span><i class="bi bi-pie-chart me-2"></i>Status Hari Ini</span></div>
                            <div class="card-body"><canvas id="chartPie" height="220"></canvas></div>
                        </div>
                    </div>
                </div>

                <div class="card-panel mt-4">
                    <div class="card-header">
                        <span><i class="bi bi-list-ul me-2"></i>Absensi Terbaru Hari Ini</span>
                        <button class="btn-outline-sm">Lihat Semua</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead><tr><th>NIS</th><th>Nama</th><th>Kelas</th><th>Status</th><th>Jam</th><th>Metode</th></tr></thead>
                                <tbody>
                                    ${DB.absensi.map(a => {
                                        let b = 'badge-alpa';
                                        switch(a.status){ case'Hadir':b='badge-hadir';break; case'Izin':b='badge-izin';break; case'Terlambat':b='badge-terlambat';break; }
                                        return `<tr><td>${a.nis}</td><td><strong>${a.nama}</strong></td><td>${a.kelas}</td><td><span class="badge-sm ${b}">${a.status}</span></td><td>${a.jam}</td><td>${a.metode}</td></tr>`;
                                    }).join('')}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            `;

            // Charts
            setTimeout(() => {
                const ctx1 = document.getElementById('chartKehadiran');
                if(ctx1) new Chart(ctx1, { type:'bar', data:{ labels:['6 Mei','7 Mei','8 Mei','9 Mei','10 Mei','11 Mei','12 Mei'], datasets:[{ label:'Hadir', data:[1150,1170,1140,1160,1180,1165,1180], backgroundColor:'#2563EB', borderRadius:8 },{ label:'Tidak Hadir', data:[100,80,110,90,70,85,70], backgroundColor:'#E5E7EB', borderRadius:8 }] }, options:{ responsive:true, plugins:{legend:{position:'bottom'}}, scales:{y:{stacked:true}} } });

                const ctx2 = document.getElementById('chartPie');
                if(ctx2) new Chart(ctx2, { type:'doughnut', data:{ labels:['Hadir','Izin','Sakit','Alpa','Terlambat'], datasets:[{ data:[s.hadir,s.izin,s.sakit,s.alpa,s.terlambat], backgroundColor:['#10B981','#F59E0B','#EF4444','#6B7280','#F97316'], borderWidth:0 }] }, options:{ responsive:true, plugins:{legend:{position:'bottom'}} } });
            }, 300);
        }

        // ==================== MONITORING ABSENSI ====================
        function renderMonitoring() {
            document.getElementById('mainContent').innerHTML = `
                <h4 class="fw-bold mb-4"><i class="bi bi-clipboard-check me-2"></i>Monitoring Absensi Realtime</h4>
                <div class="card-panel"><div class="card-body p-0"><div class="table-responsive">
                    <table class="table-modern"><thead><tr><th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>Status</th><th>Jam</th><th>Metode</th><th>Aksi</th></tr></thead><tbody>
                        ${DB.absensi.map((a,i) => {
                            let b='badge-alpa'; switch(a.status){ case'Hadir':b='badge-hadir';break; case'Izin':b='badge-izin';break; case'Terlambat':b='badge-terlambat';break; }
                            return `<tr><td>${i+1}</td><td>${a.nis}</td><td><strong>${a.nama}</strong></td><td>${a.kelas}</td><td><span class="badge-sm ${b}">${a.status}</span></td><td>${a.jam}</td><td>${a.metode}</td><td><button class="btn-outline-sm" onclick="showToast('Detail absensi','#2563EB')"><i class="bi bi-eye"></i></button></td></tr>`;
                        }).join('')}
                </tbody></table></div></div></div>
            `;
        }

        // ==================== REKAP ====================
        function renderRekap() {
            document.getElementById('mainContent').innerHTML = `
                <h4 class="fw-bold mb-4"><i class="bi bi-file-bar-graph me-2"></i>Rekap Absensi</h4>
                <div class="card-panel"><div class="card-body"><canvas id="chartRekap" height="250"></canvas></div></div>
            `;
            setTimeout(() => {
                const ctx = document.getElementById('chartRekap');
                if(ctx) new Chart(ctx, { type:'bar', data:{ labels:['XII RPL A','XII RPL B','XI TKJ A','XI TKJ B','X MM A'], datasets:[{ label:'Hadir (%)', data:[97,94,98,90,93], backgroundColor:'#2563EB', borderRadius:8 }] }, options:{ responsive:true } });
            }, 300);
        }

        // ==================== CRUD USERS ====================
        function renderUsers() {
            document.getElementById('mainContent').innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-people-fill me-2"></i>Manajemen User</h4>
                    <button class="btn-primary-sm" onclick="showToast('Form tambah user','#2563EB')"><i class="bi bi-plus-lg"></i>Tambah User</button>
                </div>
                <div class="card-panel"><div class="card-body p-0"><div class="table-responsive">
                    <table class="table-modern"><thead><tr><th>No</th><th>Username</th><th>Nama</th><th>Role</th><th>Email</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
                        ${DB.users.map((u,i) => `
                            <tr><td>${i+1}</td><td><strong>${u.username}</strong></td><td>${u.nama}</td><td><span class="badge bg-primary">${u.role}</span></td><td>${u.email}</td><td><span class="badge-sm ${u.is_active?'badge-active':'badge-inactive'}">${u.is_active?'Aktif':'Nonaktif'}</span></td><td><button class="btn-outline-sm me-1" onclick="showToast('Edit user','#F59E0B')"><i class="bi bi-pencil"></i></button><button class="btn-outline-sm text-danger" onclick="showToast('Hapus user','#EF4444')"><i class="bi bi-trash"></i></button></td></tr>
                        `).join('')}
                </tbody></table></div></div></div>
            `;
        }

        // ==================== CRUD SISWA ====================
        function renderSiswa() {
            document.getElementById('mainContent').innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-mortarboard-fill me-2"></i>Data Siswa</h4>
                    <div class="d-flex gap-2">
                        <button class="btn-outline-sm"><i class="bi bi-download me-1"></i>Export</button>
                        <button class="btn-primary-sm"><i class="bi bi-plus-lg"></i>Tambah Siswa</button>
                    </div>
                </div>
                <div class="card-panel"><div class="card-body p-0"><div class="table-responsive">
                    <table class="table-modern"><thead><tr><th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th><th>QR Code</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
                        ${DB.siswa.map((s,i) => `
                            <tr><td>${i+1}</td><td>${s.nis}</td><td><strong>${s.nama}</strong></td><td><span class="badge bg-info">${s.kelas}</span></td><td><i class="bi bi-qr-code text-primary"></i> QR-${s.nis}</td><td><span class="badge-sm ${s.is_active?'badge-active':'badge-inactive'}">${s.is_active?'Aktif':'Nonaktif'}</span></td><td><button class="btn-outline-sm me-1" onclick="showToast('QR digenerate ulang','#2563EB')"><i class="bi bi-arrow-repeat"></i></button><button class="btn-outline-sm me-1"><i class="bi bi-pencil"></i></button><button class="btn-outline-sm text-danger"><i class="bi bi-trash"></i></button></td></tr>
                        `).join('')}
                </tbody></table></div></div></div>
            `;
        }

        // ==================== CRUD KELAS ====================
        function renderKelas() {
            document.getElementById('mainContent').innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-door-open me-2"></i>Data Kelas</h4>
                    <button class="btn-primary-sm"><i class="bi bi-plus-lg"></i>Tambah Kelas</button>
                </div>
                <div class="row g-3">
                    ${DB.kelas.map(k => `
                        <div class="col-md-4 col-lg-3">
                            <div class="stat-card text-center">
                                <div class="icon-wrap mx-auto mb-3" style="background:#DBEAFE;color:#2563EB;width:50px;height:50px;"><i class="bi bi-door-open"></i></div>
                                <h6 class="fw-bold">${k.tingkat} ${k.jurusan} ${k.rombel}</h6>
                                <p class="text-muted small mb-2">Kapasitas: ${k.kapasitas} siswa</p>
                                <span class="badge-sm badge-active">Aktif</span>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        // ==================== CRUD JURUSAN ====================
        function renderJurusan() {
            document.getElementById('mainContent').innerHTML = `
                <h4 class="fw-bold mb-4"><i class="bi bi-diagram-3 me-2"></i>Data Jurusan</h4>
                <div class="card-panel"><div class="card-body p-0"><div class="table-responsive">
                    <table class="table-modern"><thead><tr><th>No</th><th>Kode</th><th>Singkatan</th><th>Nama Jurusan</th><th>Aksi</th></tr></thead><tbody>
                        ${DB.jurusan.map((j,i) => `<tr><td>${i+1}</td><td>${j.kode}</td><td>${j.singkatan}</td><td>${j.nama}</td><td><button class="btn-outline-sm me-1"><i class="bi bi-pencil"></i></button><button class="btn-outline-sm text-danger"><i class="bi bi-trash"></i></button></td></tr>`).join('')}
                </tbody></table></div></div></div>
            `;
        }

        // ==================== GURU ====================
        function renderGuru() {
            document.getElementById('mainContent').innerHTML = `
                <h4 class="fw-bold mb-4"><i class="bi bi-person-badge me-2"></i>Data Guru</h4>
                <div class="card-panel"><div class="card-body p-0"><div class="table-responsive">
                    <table class="table-modern"><thead><tr><th>No</th><th>Nama</th><th>NUPTK</th><th>Email</th><th>Status</th></tr></thead><tbody>
                        ${DB.users.filter(u=>u.role==='Guru').map((u,i) => `<tr><td>${i+1}</td><td><strong>${u.nama}</strong></td><td>-</td><td>${u.email}</td><td><span class="badge-sm badge-active">Aktif</span></td></tr>`).join('')}
                </tbody></table></div></div></div>
            `;
        }

        function renderPlaceholder(title) {
            document.getElementById('mainContent').innerHTML = `
                <h4 class="fw-bold mb-4"><i class="bi bi-gear me-2"></i>${title}</h4>
                <div class="card-panel"><div class="card-body text-center py-5 text-muted">
                    <i class="bi bi-tools" style="font-size:4rem;display:block;"></i>
                    <span class="mt-3 d-block">Fitur ${title} sedang dalam pengembangan</span>
                </div></div>
            `;
        }

        // ==================== INIT ====================
        document.addEventListener('DOMContentLoaded', () => renderDashboard());
    </script>
</body>
</html>