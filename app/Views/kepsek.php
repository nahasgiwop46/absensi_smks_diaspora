<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - Absensi QRcode SMKS Diaspora</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- QR Code JS -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

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
            color: white;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            letter-spacing: 0.5px;
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
        .sidebar .sidebar-header .guru-avatar {
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
        .badge-hadir { background: #D1FAE5; color: #065F46; padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; }
        .badge-izin { background: #FEF3C7; color: #92400E; padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; }
        .badge-sakit { background: #FEE2E2; color: #991B1B; padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; }
        .badge-alpa { background: #E2E8F0; color: #4A5568; padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; }
        .badge-terlambat { background: #FED7AA; color: #9A3412; padding: 6px 12px; border-radius: 8px; font-size: 0.78rem; font-weight: 600; }

        /* ========== QR CODE ========== */
        .qr-container {
            background: white; padding: 30px;
            border-radius: 16px; text-align: center;
            border: 2px dashed #E2E8F0;
            display: inline-block;
        }
        .qr-container canvas { display: block; margin: 0 auto; }
        .token-display {
            background: #F7FAFC; padding: 10px 16px;
            border-radius: 8px; font-family: 'Courier New', monospace;
            font-size: 0.8rem; word-break: break-all;
            color: #4A5568; margin-top: 12px;
        }
        .expired-timer {
            font-size: 0.85rem; color: #DC3545;
            font-weight: 600; margin-top: 8px;
        }

        /* ========== JADWAL ITEM ========== */
        .jadwal-item {
            display: flex; align-items: center; gap: 16px;
            padding: 14px; border-radius: 12px;
            background: #F8FAFC; margin-bottom: 10px;
            transition: all 0.2s;
        }
        .jadwal-item:hover { background: var(--diaspora-light); }
        .jadwal-item .jam-box {
            text-align: center; min-width: 70px;
            background: white; padding: 8px; border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        .jadwal-item .jam-box .jam { font-weight: 700; color: var(--diaspora-primary); font-size: 0.95rem; }
        .jadwal-item .jam-box .sd { font-size: 0.7rem; color: #A0AEC0; }
        .jadwal-item .info .mapel { font-weight: 700; color: #1A202C; }
        .jadwal-item .info .detail { font-size: 0.8rem; color: #718096; }
        .jadwal-item .status-badge { margin-left: auto; }

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
            <button class="btn-icon" onclick="showNotifikasi()">
                <i class="bi bi-bell-fill"></i>
                <span class="badge-notif">3</span>
            </button>
            <span class="role-badge">
                <i class="bi bi-person-workspace me-1"></i>Guru
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

    <!-- OVERLAY SIDEBAR -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="guru-avatar">
                <i class="bi bi-person-workspace"></i>
            </div>
            <h6>Budi Santoso, S.Kom</h6>
            <small>Guru | Wali Kelas XII RPL A</small>
        </div>
        <div class="nav-menu">
            <div class="nav-section">Menu Utama</div>
            <div class="nav-item">
                <a class="nav-link active" href="#" onclick="loadPage('dashboard', this)">
                    <i class="bi bi-grid-fill"></i>Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('generate-qr', this)">
                    <i class="bi bi-qr-code"></i>Generate QR Code
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('input-absensi', this)">
                    <i class="bi bi-pencil-square"></i>Input Absensi
                </a>
            </div>

            <div class="nav-section">Wali Kelas - XII RPL A</div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('absensi-kelas', this)">
                    <i class="bi bi-clipboard-check"></i>Absensi Hari Ini
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('rekap-kelas', this)">
                    <i class="bi bi-bar-chart-fill"></i>Rekap Kelas
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('notifikasi', this)">
                    <i class="bi bi-bell-fill"></i>Kirim Notifikasi
                </a>
            </div>

            <div class="nav-section">Lainnya</div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('jadwal-saya', this)">
                    <i class="bi bi-calendar-week"></i>Jadwal Mengajar
                </a>
            </div>
            <div class="nav-item">
                <a class="nav-link" href="#" onclick="loadPage('profil', this)">
                    <i class="bi bi-person-fill"></i>Profil Saya
                </a>
            </div>
        </div>
    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="main-content" id="mainContent">
        <!-- Konten dinamis -->
    </div>

    <!-- ==================== MODAL QR CODE ==================== -->
    <div class="modal fade" id="qrModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-qr-code me-2 text-primary"></i>QR Code Absensi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kelas</label>
                        <select class="form-select" id="qrKelas">
                            <option value="XII RPL A" selected>XII RPL A</option>
                            <option value="XII RPL B">XII RPL B</option>
                            <option value="XI TKJ A">XI TKJ A</option>
                            <option value="XI TKJ B">XI TKJ B</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jenis Absensi</label>
                        <div class="btn-group w-100">
                            <input type="radio" class="btn-check" name="jenisAbsen" id="qrMasuk" value="masuk" checked>
                            <label class="btn btn-outline-primary" for="qrMasuk"><i class="bi bi-box-arrow-in-right me-1"></i>Masuk</label>
                            <input type="radio" class="btn-check" name="jenisAbsen" id="qrPulang" value="pulang">
                            <label class="btn btn-outline-primary" for="qrPulang"><i class="bi bi-box-arrow-right me-1"></i>Pulang</label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Masa Aktif</label>
                        <select class="form-select" id="qrExpired">
                            <option value="3">3 menit</option>
                            <option value="5" selected>5 menit</option>
                            <option value="10">10 menit</option>
                        </select>
                    </div>
                    <button class="btn btn-primary w-100 rounded-pill mb-3" onclick="generateQRCode()">
                        <i class="bi bi-arrow-repeat me-1"></i>Generate QR Code
                    </button>
                    <div class="qr-container" id="qrContainer" style="display:none;">
                        <div id="qrcode"></div>
                        <div class="token-display" id="tokenDisplay"></div>
                        <div class="expired-timer" id="expiredTimer"></div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL INPUT ABSENSI ==================== -->
    <div class="modal fade" id="inputAbsensiModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>Input Absensi Manual
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Siswa</label>
                        <select class="form-select" id="inputSiswa">
                            <option value="">Pilih Siswa...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select class="form-select" id="inputStatus">
                            <option value="Hadir">✅ Hadir</option>
                            <option value="Izin">📝 Izin</option>
                            <option value="Sakit">🤒 Sakit</option>
                            <option value="Alpa">❌ Alpa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jam Masuk</label>
                        <input type="time" class="form-control" id="inputJamMasuk" value="07:00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Keterangan</label>
                        <textarea class="form-control" id="inputKeterangan" rows="2" placeholder="Opsional..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary rounded-pill px-4" onclick="simpanAbsensiManual()">
                        <i class="bi bi-check-lg me-1"></i>Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- ==================== MODAL NOTIFIKASI ==================== -->
    <div class="modal fade" id="notifModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-whatsapp me-2 text-success"></i>Kirim Notifikasi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Siswa</label>
                        <select class="form-select" id="notifSiswa">
                            <option value="">Pilih Siswa...</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tipe Notifikasi</label>
                        <select class="form-select" id="notifTipe">
                            <option value="whatsapp">📱 WhatsApp</option>
                            <option value="sms">💬 SMS</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pesan</label>
                        <textarea class="form-control" id="notifPesan" rows="3">Ananda [nama] telah hadir di sekolah pukul [jam]. Terima kasih.</textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success rounded-pill px-4" onclick="kirimNotifikasi()">
                        <i class="bi bi-send me-1"></i>Kirim
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // ==================== DATA DUMMY ====================
        const DB = {
            guru: { id: 2, nama: 'Budi Santoso, S.Kom', nip: '198501012010011001', waliKelas: 'XII RPL A' },
            siswaKelas: [
                { id: 1, nis: '2024001', nama: 'Andi Pratama', noHpOrtu: '081111111111' },
                { id: 2, nis: '2024002', nama: 'Budi Setiawan', noHpOrtu: '081222222222' },
                { id: 3, nis: '2024003', nama: 'Cici Nurhaliza', noHpOrtu: '081333333333' },
                { id: 6, nis: '2024006', nama: 'Fani Rahmawati', noHpOrtu: '081666666666' },
                { id: 7, nis: '2024007', nama: 'Gilang Pratama', noHpOrtu: '081777777777' },
            ],
            absensiHariIni: [
                { siswa_id: 1, nama: 'Andi Pratama', status: 'Terlambat', jam_masuk: '07:10', menit: 10, metode: 'qr' },
                { siswa_id: 2, nama: 'Budi Setiawan', status: 'Hadir', jam_masuk: '06:55', menit: 0, metode: 'qr' },
                { siswa_id: 3, nama: 'Cici Nurhaliza', status: 'Izin', jam_masuk: null, menit: 0, metode: 'manual' },
                { siswa_id: 6, nama: 'Fani Rahmawati', status: 'Hadir', jam_masuk: '06:58', menit: 0, metode: 'qr' },
                { siswa_id: 7, nama: 'Gilang Pratama', status: 'Alpa', jam_masuk: null, menit: 0, metode: null },
            ],
            jadwalHariIni: [
                { jam_mulai: '07:00', jam_selesai: '08:30', mapel: 'Pemrograman Web', kelas: 'XII RPL A', ruang: 'Lab Komputer 1' },
                { jam_mulai: '08:30', jam_selesai: '10:00', mapel: 'Jaringan Dasar', kelas: 'XI TKJ B', ruang: 'Lab Jaringan' },
                { jam_mulai: '10:15', jam_selesai: '11:45', mapel: 'Pemrograman Web', kelas: 'XII RPL B', ruang: 'Lab Komputer 2' },
            ],
            rekapBulanan: { hadir: 28, izin: 2, sakit: 1, alpa: 1, terlambat: 3, totalHari: 35 },
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
                case 'generate-qr': renderGenerateQR(); break;
                case 'input-absensi': showInputAbsensi(); break;
                case 'absensi-kelas': renderAbsensiKelas(); break;
                case 'rekap-kelas': renderRekapKelas(); break;
                case 'notifikasi': showNotifikasi(); break;
                case 'jadwal-saya': renderJadwalSaya(); break;
                case 'profil': renderProfil(); break;
            }
        }

        // ==================== DASHBOARD UTAMA ====================
        function renderDashboard() {
            const hadir = DB.absensiHariIni.filter(a => a.status === 'Hadir').length;
            const izin = DB.absensiHariIni.filter(a => a.status === 'Izin').length;
            const sakit = DB.absensiHariIni.filter(a => a.status === 'Sakit').length;
            const alpa = DB.absensiHariIni.filter(a => a.status === 'Alpa').length;
            const terlambat = DB.absensiHariIni.filter(a => a.status === 'Terlambat').length;
            const totalKelas = DB.siswaKelas.length;
            const persentase = ((hadir / totalKelas) * 100).toFixed(1);

            let html = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="bi bi-grid-fill text-primary me-2"></i>Dashboard Guru</h4>
                        <p class="text-muted mb-0">Selamat datang, <strong>${DB.guru.nama}</strong> | Wali Kelas ${DB.guru.waliKelas}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary rounded-pill px-4" onclick="loadPage('generate-qr')">
                            <i class="bi bi-qr-code me-2"></i>Generate QR
                        </button>
                        <button class="btn btn-outline-primary rounded-pill px-4" onclick="showInputAbsensi()">
                            <i class="bi bi-pencil-square me-2"></i>Input Manual
                        </button>
                    </div>
                </div>

                <!-- Stat Cards -->
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="stat-icon" style="background:#D1FAE5;color:#059669;"><i class="bi bi-check-circle-fill"></i></div>
                            </div>
                            <div class="stat-value">${hadir}/${totalKelas}</div>
                            <div class="stat-label">Hadir Hari Ini</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="stat-icon" style="background:#FED7AA;color:#9A3412;"><i class="bi bi-clock-fill"></i></div>
                            </div>
                            <div class="stat-value">${terlambat}</div>
                            <div class="stat-label">Terlambat</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="stat-icon" style="background:#FEF3C7;color:#D97706;"><i class="bi bi-file-text-fill"></i></div>
                            </div>
                            <div class="stat-value">${izin}</div>
                            <div class="stat-label">Izin</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="stat-card">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="stat-icon" style="background:#E8F0FE;color:#0D6EFD;"><i class="bi bi-graph-up"></i></div>
                            </div>
                            <div class="stat-value">${persentase}%</div>
                            <div class="stat-label">Kehadiran</div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Absensi + Chart -->
                <div class="row g-3">
                    <div class="col-md-7">
                        <div class="card-diaspora">
                            <div class="card-header">
                                <span><i class="bi bi-clipboard-check me-2"></i>Absensi ${DB.guru.waliKelas} - Hari Ini</span>
                                <button class="btn btn-sm btn-outline-danger rounded-pill" onclick="showNotifikasi()">
                                    <i class="bi bi-bell me-1"></i>Notifikasi
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-diaspora mb-0">
                                    <thead>
                                        <tr><th>NIS</th><th>Nama</th><th>Status</th><th>Jam Masuk</th><th>Terlambat</th><th>Metode</th></tr>
                                    </thead>
                                    <tbody>
                                        ${DB.absensiHariIni.map(a => {
                                            let badge = '';
                                            switch(a.status) {
                                                case 'Hadir': badge = 'badge-hadir'; break;
                                                case 'Izin': badge = 'badge-izin'; break;
                                                case 'Sakit': badge = 'badge-sakit'; break;
                                                case 'Alpa': badge = 'badge-alpa'; break;
                                                case 'Terlambat': badge = 'badge-terlambat'; break;
                                            }
                                            const siswa = DB.siswaKelas.find(s => s.id === a.siswa_id);
                                            return `
                                                <tr>
                                                    <td>${siswa?.nis || '-'}</td>
                                                    <td><strong>${a.nama}</strong></td>
                                                    <td><span class="${badge}">${a.status}</span></td>
                                                    <td>${a.jam_masuk || '-'}</td>
                                                    <td>${a.menit > 0 ? a.menit + ' mnt' : '-'}</td>
                                                    <td>${a.metode === 'qr' ? '📱 QR' : a.metode === 'manual' ? '✍️ Manual' : '-'}</td>
                                                </tr>
                                            `;
                                        }).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card-diaspora h-100">
                            <div class="card-header"><span><i class="bi bi-calendar-week me-2"></i>Jadwal Hari Ini</span></div>
                            <div class="card-body">
                                ${DB.jadwalHariIni.map(j => `
                                    <div class="jadwal-item">
                                        <div class="jam-box">
                                            <div class="jam">${j.jam_mulai}</div>
                                            <div class="sd">s/d</div>
                                            <div class="jam">${j.jam_selesai}</div>
                                        </div>
                                        <div class="info">
                                            <div class="mapel">${j.mapel}</div>
                                            <div class="detail">${j.kelas} | ${j.ruang}</div>
                                        </div>
                                        <span class="badge bg-primary status-badge">Sekarang</span>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('mainContent').innerHTML = html;
        }

        // ==================== GENERATE QR CODE ====================
        function renderGenerateQR() {
            document.getElementById('mainContent').innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="bi bi-qr-code text-primary me-2"></i>Generate QR Code</h4>
                        <p class="text-muted mb-0">Buat QR Code untuk absensi siswa</p>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6">
                        <div class="card-diaspora">
                            <div class="card-body text-center">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Kelas</label>
                                    <select class="form-select" id="qrKelasPage">
                                        <option value="XII RPL A" selected>XII RPL A</option>
                                        <option value="XII RPL B">XII RPL B</option>
                                        <option value="XI TKJ A">XI TKJ A</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Jenis Absensi</label>
                                    <div class="btn-group w-100">
                                        <input type="radio" class="btn-check" name="jenisAbsenPage" id="masukPage" value="masuk" checked>
                                        <label class="btn btn-outline-primary" for="masukPage">Masuk</label>
                                        <input type="radio" class="btn-check" name="jenisAbsenPage" id="pulangPage" value="pulang">
                                        <label class="btn btn-outline-primary" for="pulangPage">Pulang</label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Masa Aktif</label>
                                    <select class="form-select" id="qrExpiredPage">
                                        <option value="3">3 menit</option>
                                        <option value="5" selected>5 menit</option>
                                        <option value="10">10 menit</option>
                                    </select>
                                </div>
                                <button class="btn btn-primary w-100 rounded-pill mb-3" onclick="generateQRCodePage()">
                                    <i class="bi bi-arrow-repeat me-1"></i>Generate QR Code
                                </button>
                                <div id="qrResultPage" style="display:none;">
                                    <div class="qr-container">
                                        <div id="qrcodePage"></div>
                                        <div class="token-display mt-3" id="tokenDisplayPage"></div>
                                        <div class="expired-timer" id="expiredTimerPage"></div>
                                    </div>
                                    <div class="mt-3 d-flex gap-2 justify-content-center">
                                        <button class="btn btn-outline-primary rounded-pill" onclick="alert('QR Code disimpan!')">
                                            <i class="bi bi-download me-1"></i>Simpan
                                        </button>
                                        <button class="btn btn-outline-success rounded-pill" onclick="alert('Link disalin!')">
                                            <i class="bi bi-copy me-1"></i>Salin Token
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function generateQRCodePage() {
            const token = 'qr_' + Date.now() + '_' + Math.random().toString(36).substr(2, 16);
            const expired = parseInt(document.getElementById('qrExpiredPage').value);

            document.getElementById('qrResultPage').style.display = 'block';
            document.getElementById('qrcodePage').innerHTML = '';

            new QRCode(document.getElementById('qrcodePage'), {
                text: token,
                width: 220,
                height: 220,
                colorDark: '#0B3D7B',
                colorLight: '#FFFFFF',
            });

            document.getElementById('tokenDisplayPage').innerHTML = `<i class="bi bi-key me-1"></i>${token}`;

            let timeLeft = expired * 60;
            const timerEl = document.getElementById('expiredTimerPage');
            timerEl.innerHTML = `⏰ Berlaku: ${Math.floor(timeLeft/60)}:${String(timeLeft%60).padStart(2,'0')}`;

            const interval = setInterval(() => {
                timeLeft--;
                timerEl.innerHTML = `⏰ Berlaku: ${Math.floor(timeLeft/60)}:${String(timeLeft%60).padStart(2,'0')}`;
                if (timeLeft <= 60) timerEl.style.color = '#DC3545';
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    timerEl.innerHTML = '❌ QR Code kadaluarsa';
                    document.getElementById('qrcodePage').style.opacity = '0.4';
                }
            }, 1000);
        }

        function generateQRCode() {
            const token = 'qr_' + Date.now() + '_' + Math.random().toString(36).substr(2, 16);
            document.getElementById('qrContainer').style.display = 'block';
            document.getElementById('qrcode').innerHTML = '';
            new QRCode(document.getElementById('qrcode'), {
                text: token,
                width: 200,
                height: 200,
                colorDark: '#0B3D7B',
                colorLight: '#FFFFFF',
            });
            document.getElementById('tokenDisplay').innerHTML = `<i class="bi bi-key me-1"></i>${token}`;
            let timeLeft = 300;
            const timerEl = document.getElementById('expiredTimer');
            timerEl.innerHTML = `⏰ Berlaku: 5:00`;
            const interval = setInterval(() => {
                timeLeft--;
                timerEl.innerHTML = `⏰ Berlaku: ${Math.floor(timeLeft/60)}:${String(timeLeft%60).padStart(2,'0')}`;
                if (timeLeft <= 0) { clearInterval(interval); timerEl.innerHTML = '❌ Kadaluarsa'; }
            }, 1000);
        }

        // ==================== INPUT ABSENSI MANUAL ====================
        function showInputAbsensi() {
            const select = document.getElementById('inputSiswa');
            select.innerHTML = '<option value="">Pilih Siswa...</option>' +
                DB.siswaKelas.map(s => `<option value="${s.id}">${s.nis} - ${s.nama}</option>`).join('');
            new bootstrap.Modal(document.getElementById('inputAbsensiModal')).show();
        }

        function simpanAbsensiManual() {
            const siswaId = document.getElementById('inputSiswa').value;
            const status = document.getElementById('inputStatus').value;
            const jam = document.getElementById('inputJamMasuk').value;
            const ket = document.getElementById('inputKeterangan').value;

            if (!siswaId) return alert('⚠️ Pilih siswa terlebih dahulu!');

            const siswa = DB.siswaKelas.find(s => s.id == siswaId);
            const existing = DB.absensiHariIni.findIndex(a => a.siswa_id == siswaId);

            const newData = {
                siswa_id: parseInt(siswaId),
                nama: siswa.nama,
                status: status,
                jam_masuk: status === 'Hadir' ? jam : null,
                menit: status === 'Hadir' ? Math.max(0, Math.floor((new Date(`2026-05-12T${jam}:00`) - new Date('2026-05-12T07:00:00')) / 60000)) : 0,
                metode: 'manual'
            };

            if (existing >= 0) {
                DB.absensiHariIni[existing] = newData;
            } else {
                DB.absensiHariIni.push(newData);
            }

            alert(`✅ Absensi ${siswa.nama} berhasil disimpan!\nStatus: ${status}`);
            bootstrap.Modal.getInstance(document.getElementById('inputAbsensiModal')).hide();
            renderDashboard();
        }

        // ==================== ABSENSI KELAS ====================
        function renderAbsensiKelas() {
            document.getElementById('mainContent').innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="bi bi-clipboard-check text-primary me-2"></i>Absensi ${DB.guru.waliKelas}</h4>
                        <p class="text-muted mb-0">Rekap absensi hari ini - ${new Date().toLocaleDateString('id-ID', {day:'numeric',month:'long',year:'numeric'})}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-primary rounded-pill" onclick="showInputAbsensi()">
                            <i class="bi bi-plus-lg me-1"></i>Tambah
                        </button>
                        <button class="btn btn-outline-danger rounded-pill" onclick="showNotifikasi()">
                            <i class="bi bi-bell me-1"></i>Notifikasi
                        </button>
                    </div>
                </div>
                <div class="card-diaspora">
                    <div class="card-body p-0">
                        <table class="table table-diaspora mb-0">
                            <thead>
                                <tr><th>No</th><th>NIS</th><th>Nama</th><th>Status</th><th>Jam</th><th>Terlambat</th><th>Metode</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                                ${DB.absensiHariIni.map((a, i) => {
                                    let badge = '';
                                    switch(a.status) {
                                        case 'Hadir': badge = 'badge-hadir'; break;
                                        case 'Izin': badge = 'badge-izin'; break;
                                        case 'Sakit': badge = 'badge-sakit'; break;
                                        case 'Alpa': badge = 'badge-alpa'; break;
                                        case 'Terlambat': badge = 'badge-terlambat'; break;
                                    }
                                    const siswa = DB.siswaKelas.find(s => s.id === a.siswa_id);
                                    return `
                                        <tr>
                                            <td>${i+1}</td>
                                            <td>${siswa?.nis || '-'}</td>
                                            <td><strong>${a.nama}</strong></td>
                                            <td><span class="${badge}">${a.status}</span></td>
                                            <td>${a.jam_masuk || '-'}</td>
                                            <td>${a.menit > 0 ? a.menit + ' mnt' : '-'}</td>
                                            <td>${a.metode === 'qr' ? '📱 QR' : a.metode === 'manual' ? '✍️ Manual' : '-'}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-warning" onclick="showInputAbsensi()"><i class="bi bi-pencil"></i></button>
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
        }

        // ==================== REKAP KELAS ====================
        function renderRekapKelas() {
            const r = DB.rekapBulanan;
            document.getElementById('mainContent').innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="bi bi-bar-chart-fill text-primary me-2"></i>Rekap ${DB.guru.waliKelas}</h4>
                        <p class="text-muted mb-0">Bulan Mei 2026 | Total: ${r.totalHari} hari efektif</p>
                    </div>
                </div>
                <div class="row g-3 mb-4">
                    <div class="col-6 col-md-2"><div class="stat-card text-center"><div class="stat-value text-success">${r.hadir}</div><div class="stat-label">Hadir</div></div></div>
                    <div class="col-6 col-md-2"><div class="stat-card text-center"><div class="stat-value text-warning">${r.izin}</div><div class="stat-label">Izin</div></div></div>
                    <div class="col-6 col-md-2"><div class="stat-card text-center"><div class="stat-value text-danger">${r.sakit}</div><div class="stat-label">Sakit</div></div></div>
                    <div class="col-6 col-md-2"><div class="stat-card text-center"><div class="stat-value text-secondary">${r.alpa}</div><div class="stat-label">Alpa</div></div></div>
                    <div class="col-6 col-md-2"><div class="stat-card text-center"><div class="stat-value" style="color:#9A3412;">${r.terlambat}</div><div class="stat-label">Terlambat</div></div></div>
                    <div class="col-6 col-md-2"><div class="stat-card text-center"><div class="stat-value text-primary">${((r.hadir/r.totalHari)*100).toFixed(1)}%</div><div class="stat-label">Kehadiran</div></div></div>
                </div>
                <div class="card-diaspora">
                    <div class="card-body">
                        <canvas id="chartRekapKelas" height="200"></canvas>
                    </div>
                </div>
            `;
            setTimeout(() => {
                new Chart(document.getElementById('chartRekapKelas'), {
                    type: 'bar',
                    data: {
                        labels: DB.siswaKelas.map(s => s.nama.split(' ')[0]),
                        datasets: [{
                            label: 'Hadir',
                            data: DB.siswaKelas.map(() => Math.floor(Math.random()*20+10)),
                            backgroundColor: '#0D6EFD',
                            borderRadius: 6,
                        }]
                    },
                    options: { responsive: true, plugins: { legend: { display: false } } }
                });
            }, 300);
        }

        // ==================== NOTIFIKASI ====================
        function showNotifikasi() {
            const select = document.getElementById('notifSiswa');
            select.innerHTML = '<option value="">Pilih Siswa...</option>' +
                DB.siswaKelas.map(s => `<option value="${s.id}">${s.nis} - ${s.nama} (${s.noHpOrtu})</option>`).join('');
            new bootstrap.Modal(document.getElementById('notifModal')).show();
        }

        function kirimNotifikasi() {
            const siswaId = document.getElementById('notifSiswa').value;
            const tipe = document.getElementById('notifTipe').value;
            if (!siswaId) return alert('⚠️ Pilih siswa!');
            const siswa = DB.siswaKelas.find(s => s.id == siswaId);
            alert(`✅ Notifikasi ${tipe.toUpperCase()} terkirim ke ${siswa.nama}\nNomor: ${siswa.noHpOrtu}`);
            bootstrap.Modal.getInstance(document.getElementById('notifModal')).hide();
        }

        // ==================== JADWAL SAYA ====================
        function renderJadwalSaya() {
            document.getElementById('mainContent').innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-1"><i class="bi bi-calendar-week text-primary me-2"></i>Jadwal Mengajar</h4>
                        <p class="text-muted mb-0">${DB.guru.nama}</p>
                    </div>
                </div>
                ${['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'].map(hari => `
                    <div class="card-diaspora mb-3">
                        <div class="card-header"><i class="bi bi-calendar-day me-2"></i>${hari}</div>
                        <div class="card-body">
                            ${DB.jadwalHariIni.map(j => `
                                <div class="jadwal-item">
                                    <div class="jam-box">
                                        <div class="jam">${j.jam_mulai}</div>
                                        <div class="sd">s/d</div>
                                        <div class="jam">${j.jam_selesai}</div>
                                    </div>
                                    <div class="info">
                                        <div class="mapel">${j.mapel}</div>
                                        <div class="detail">${j.kelas} | ${j.ruang}</div>
                                    </div>
                                </div>
                            `).join('')}
                        </div>
                    </div>
                `).join('')}
            `;
        }

        // ==================== PROFIL ====================
        function renderProfil() {
            document.getElementById('mainContent').innerHTML = `
                <h4 class="fw-bold mb-4"><i class="bi bi-person-fill text-primary me-2"></i>Profil Saya</h4>
                <div class="card-diaspora">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width:100px;height:100px;background:#E8F0FE;color:#0D6EFD;font-size:2.5rem;">
                                <i class="bi bi-person-workspace"></i>
                            </div>
                            <h5>${DB.guru.nama}</h5>
                            <p class="text-muted">Wali Kelas ${DB.guru.waliKelas}</p>
                        </div>
                        <table class="table table-borderless">
                            <tr><td width="150"><strong>NIP</strong></td><td>: ${DB.guru.nip}</td></tr>
                            <tr><td><strong>NUPTK</strong></td><td>: 1234567890123456</td></tr>
                            <tr><td><strong>Email</strong></td><td>: budi@smksdiaspora.sch.id</td></tr>
                            <tr><td><strong>No HP</strong></td><td>: 081298765432</td></tr>
                            <tr><td><strong>Wali Kelas</strong></td><td>: ${DB.guru.waliKelas}</td></tr>
                        </table>
                    </div>
                </div>
            `;
        }

        // ==================== INIT ====================
        document.addEventListener('DOMContentLoaded', () => renderDashboard());
    </script>
</body>
</html>