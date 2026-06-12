<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru Dashboard - SMKS Diaspora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-width: 250px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; background: #f0f2f5; min-height: 100vh; }
        
        .sidebar {
            position: fixed; top: 0; left: 0; width: var(--sidebar-width); height: 100vh;
            background: linear-gradient(180deg, #1a472a 0%, #2d6a4f 100%); color: white;
            z-index: 1040; transition: all 0.3s; overflow-y: auto; box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }
        .sidebar.collapsed { left: calc(-1 * var(--sidebar-width)); }
        .sidebar-header { padding: 20px 15px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); position: sticky; top: 0; background: inherit; z-index: 2; }
        .sidebar-header .logo-icon { font-size: 40px; color: #52b788; display: block; margin-bottom: 5px; }
        .sidebar-header h5 { font-size: 14px; margin: 5px 0 0; font-weight: 600; }
        .sidebar-header small { font-size: 10px; opacity: 0.7; display: block; }
        .menu-section { padding: 15px 20px 6px; font-size: 10px; text-transform: uppercase; letter-spacing: 2px; color: rgba(255,255,255,0.4); font-weight: 700; }
        .sidebar-menu-item { list-style: none; }
        .sidebar-link {
            color: rgba(255,255,255,0.75) !important; padding: 11px 20px; display: flex;
            align-items: center; text-decoration: none; transition: all 0.25s;
            border-left: 3px solid transparent; cursor: pointer; font-size: 14px;
            width: 100%; background: none; border: none; text-align: left;
        }
        .sidebar-link:hover { background: rgba(255,255,255,0.08); color: white !important; border-left-color: #52b788; }
        .sidebar-link.active { background: rgba(82,183,136,0.25); color: white !important; border-left-color: #52b788; font-weight: 600; }
        .sidebar-link i:first-child { width: 22px; margin-right: 12px; text-align: center; font-size: 16px; }
        
        .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1035; display: none; }
        .sidebar-overlay.show { display: block; }
        
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; transition: margin-left 0.3s; }
        .main-content.expanded { margin-left: 0; }
        
        .topbar { background: white; padding: 14px 20px; border-radius: 0 0 12px 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; position: sticky; top: 0; z-index: 1020; }
        .hamburger { font-size: 24px; cursor: pointer; color: #555; padding: 5px 10px; border-radius: 8px; background: none; border: none; }
        .hamburger:hover { background: #f0f0f0; color: #52b788; }
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #52b788, #40916c); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px; }
        
        .content-wrapper { padding: 20px; }
        
        .stat-card { border-radius: 14px; padding: 20px; color: white; box-shadow: 0 4px 20px rgba(0,0,0,0.08); transition: all 0.3s; position: relative; overflow: hidden; height: 100%; }
        .stat-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0,0,0,0.15); }
        .stat-card .stat-icon { position: absolute; right: -10px; bottom: -10px; font-size: 80px; opacity: 0.12; transform: rotate(-15deg); }
        .stat-card .stat-value { font-size: 30px; font-weight: 700; margin: 5px 0; }
        
        .card { border: none; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.04); margin-bottom: 20px; }
        .card-header { background: white; border-bottom: 1px solid #eee; padding: 15px 20px; border-radius: 14px 14px 0 0 !important; font-weight: 600; }
        
        .qr-area {
            background: white; border: 3px dashed #52b788; border-radius: 15px;
            padding: 30px; text-align: center; min-height: 280px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .qr-area.generated { border-color: #40916c; background: #f0faf4; }
        .qr-area .qr-timer { font-size: 45px; font-weight: 700; color: #e74c3c; }
        
        .jadwal-item {
            padding: 15px; border-left: 4px solid #52b788; margin-bottom: 10px;
            background: #f8faf8; border-radius: 8px; cursor: pointer; transition: all 0.3s;
        }
        .jadwal-item.berlangsung { border-left-color: #2ecc71; background: #f0faf4; }
        
        .table th { background: #f8faf8; font-weight: 600; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; color: #555; border-top: none; padding: 10px 12px; }
        .table td { padding: 9px 12px; vertical-align: middle; font-size: 12px; }
        
        .page-content { display: none; animation: fadeSlideIn 0.35s ease; }
        .page-content.active { display: block; }
        @keyframes fadeSlideIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        
        @media (max-width: 991.98px) {
            .sidebar { left: calc(-1 * var(--sidebar-width)); }
            .sidebar.mobile-show { left: 0; }
            .main-content { margin-left: 0 !important; }
            .stat-card .stat-value { font-size: 24px; }
            .qr-area { min-height: 200px; padding: 20px; }
            .qr-area .qr-timer { font-size: 30px; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <i class="bi bi-qr-code-scan logo-icon"></i>
        <h5>Absensi QR - Guru</h5>
        <small>SMKS Diaspora</small>
    </div>
    <div class="sidebar-menu">
        <div class="menu-section">Menu Utama</div>
        <ul class="nav flex-column">
            <li class="sidebar-menu-item"><button class="sidebar-link active" onclick="navigateTo('dashboard', this)"><i class="bi bi-speedometer2"></i> Dashboard</button></li>
            <li class="sidebar-menu-item"><button class="sidebar-link" onclick="navigateTo('generate-qr', this)"><i class="bi bi-qr-code"></i> Generate QR Code</button></li>
        </ul>
        <div class="menu-section">Data</div>
        <ul class="nav flex-column">
            <li class="sidebar-menu-item"><button class="sidebar-link" onclick="navigateTo('jadwal', this)"><i class="bi bi-calendar-check"></i> Jadwal Mengajar</button></li>
            <li class="sidebar-menu-item"><button class="sidebar-link" onclick="navigateTo('absensi-kelas', this)"><i class="bi bi-clipboard-check"></i> Absensi Kelas</button></li>
            <li class="sidebar-menu-item"><button class="sidebar-link" onclick="navigateTo('rekap', this)"><i class="bi bi-clipboard-data"></i> Rekap Absensi</button></li>
        </ul>
        <div class="menu-section">Lainnya</div>
        <ul class="nav flex-column">
            <li class="sidebar-menu-item"><button class="sidebar-link" onclick="navigateTo('qr-sessions', this)"><i class="bi bi-clock-history"></i> Riwayat QR</button></li>
            <li class="sidebar-menu-item"><button class="sidebar-link" onclick="navigateTo('notifikasi', this)"><i class="bi bi-whatsapp"></i> Notifikasi Ortu</button></li>
        </ul>
    </div>
</aside>

<div class="main-content" id="mainContent">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="hamburger d-lg-none" onclick="toggleMobileSidebar()"><i class="bi bi-list"></i></button>
            <button class="hamburger d-none d-lg-inline-block" onclick="toggleDesktopSidebar()"><i class="bi bi-list"></i></button>
            <div><h5 class="mb-0 fw-bold" id="pageTitle">Dashboard Guru</h5><small class="text-muted">role_id: 2 (guru)</small></div>
        </div>
        <div class="user-info">
            <span class="badge bg-success"><i class="bi bi-circle-fill me-1" style="font-size:7px;"></i>Online</span>
            <div class="user-avatar">B</div>
            <div><strong>Budi Hartono, S.Kom</strong><br><small class="text-muted">guru_id: 2</small></div>
        </div>
    </div>

    <div class="content-wrapper">

        <!-- DASHBOARD -->
        <div class="page-content active" id="page-dashboard">
            <div class="row g-3 mb-4">
                <div class="col-md-3 col-6"><div class="stat-card" style="background:linear-gradient(135deg,#52b788,#40916c);"><i class="bi bi-check-circle-fill stat-icon"></i><div class="stat-label">Hadir</div><div class="stat-value">118</div></div></div>
                <div class="col-md-3 col-6"><div class="stat-card" style="background:linear-gradient(135deg,#f39c12,#d68910);"><i class="bi bi-clock-fill stat-icon"></i><div class="stat-label">Terlambat</div><div class="stat-value">7</div></div></div>
                <div class="col-md-3 col-6"><div class="stat-card" style="background:linear-gradient(135deg,#e74c3c,#c0392b);"><i class="bi bi-x-circle-fill stat-icon"></i><div class="stat-label">Tidak Hadir</div><div class="stat-value">5</div></div></div>
                <div class="col-md-3 col-6"><div class="stat-card" style="background:linear-gradient(135deg,#3498db,#2980b9);"><i class="bi bi-qr-code stat-icon"></i><div class="stat-label">QR Sesi</div><div class="stat-value">3</div></div></div>
            </div>
            <div class="card"><div class="card-header">Jadwal Hari Ini</div>
                <div class="card-body">
                    <div class="jadwal-item berlangsung"><strong>07:00-08:30</strong> Pemrograman Web - XII RPL A <span class="badge bg-success">Berlangsung</span></div>
                    <div class="jadwal-item"><strong>08:30-10:00</strong> Basis Data - XI RPL B</div>
                </div>
            </div>
        </div>

        <!-- GENERATE QR -->
        <div class="page-content" id="page-generate-qr">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card"><div class="card-header">Generate QR Code</div>
                        <div class="card-body">
                            <div class="qr-area mb-3" id="qrArea">
                                <i class="bi bi-qr-code" style="font-size:100px;color:#52b788;" id="qrIcon"></i>
                                <h5 class="mt-3" id="qrText">QR Siap</h5>
                                <div id="qrDetail" style="display:none;"><hr><span class="badge bg-success">AKTIF</span><p><strong>Token:</strong> <code id="qrToken">-</code></p><div class="qr-timer" id="qrTimer">10:00</div></div>
                            </div>
                            <button class="btn btn-success w-100" id="btnGenerate" onclick="generateQR()">Generate QR</button>
                            <button class="btn btn-outline-danger w-100 mt-2 d-none" id="btnStop" onclick="stopQR()">Hentikan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PLACEHOLDER -->
        <div class="page-content" id="page-jadwal"><div class="card"><div class="card-header">Jadwal Mengajar</div><div class="card-body text-center py-5">📅 Tabel: jadwal WHERE guru_id=2</div></div></div>
        <div class="page-content" id="page-absensi-kelas"><div class="card"><div class="card-header">Absensi Kelas</div><div class="card-body text-center py-5">📋 Tabel: absensi JOIN siswa</div></div></div>
        <div class="page-content" id="page-rekap"><div class="card"><div class="card-header">Rekap Absensi</div><div class="card-body text-center py-5">📊 Tabel: rekap_absensi_harian</div></div></div>
        <div class="page-content" id="page-qr-sessions"><div class="card"><div class="card-header">Riwayat QR</div><div class="card-body text-center py-5">📋 Tabel: qr_sessions WHERE dibuat_oleh=2</div></div></div>
        <div class="page-content" id="page-notifikasi"><div class="card"><div class="card-header">Notifikasi Ortu</div><div class="card-body text-center py-5">📱 Tabel: notifikasi_absensi</div></div></div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleMobileSidebar(){document.getElementById('sidebar').classList.toggle('mobile-show');document.getElementById('sidebarOverlay').classList.toggle('show');}
    function toggleDesktopSidebar(){document.getElementById('sidebar').classList.toggle('collapsed');document.getElementById('mainContent').classList.toggle('expanded');}
    function closeSidebar(){document.getElementById('sidebar').classList.remove('mobile-show');document.getElementById('sidebarOverlay').classList.remove('show');}
    
    function navigateTo(page,el){
        stopQR();document.querySelectorAll('.page-content').forEach(p=>p.classList.remove('active'));
        const t=document.getElementById('page-'+page);if(t)t.classList.add('active');
        document.querySelectorAll('.sidebar-link').forEach(l=>l.classList.remove('active'));
        if(el)el.classList.add('active');
        document.getElementById('pageTitle').textContent=page.replace(/-/g,' ').toUpperCase();
        window.scrollTo({top:0,behavior:'smooth'});
    }
    
    let qrInterval=null,qrSeconds=0;
    function generateQR(){
        qrSeconds=600;document.getElementById('qrArea').classList.add('generated');
        document.getElementById('qrIcon').style.color='#40916c';document.getElementById('qrText').textContent='QR Aktif!';
        document.getElementById('qrDetail').style.display='block';document.getElementById('qrToken').textContent='QR-'+Date.now().toString(36).toUpperCase();
        document.getElementById('btnGenerate').classList.add('d-none');document.getElementById('btnStop').classList.remove('d-none');
        updateTimer();qrInterval=setInterval(updateTimer,1000);showToast('QR generated!','success');
    }
    function updateTimer(){
        const m=Math.floor(qrSeconds/60),s=qrSeconds%60;
        document.getElementById('qrTimer').textContent=String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
        if(qrSeconds<=60)document.getElementById('qrTimer').style.color='#e74c3c';
        if(qrSeconds<=0){stopQR();showToast('QR expired!','warning');}qrSeconds--;
    }
    function stopQR(){
        clearInterval(qrInterval);qrInterval=null;
        document.getElementById('qrArea').classList.remove('generated');document.getElementById('qrIcon').style.color='#52b788';
        document.getElementById('qrText').textContent='QR Siap';document.getElementById('qrDetail').style.display='none';
        document.getElementById('btnGenerate').classList.remove('d-none');document.getElementById('btnStop').classList.add('d-none');
    }
    
    function showToast(m,type='info'){
        const c=document.getElementById('toastContainer');
        const bc=type==='success'?'#52b788':type==='danger'?'#e74c3c':type==='warning'?'#f39c12':'#3498db';
        const h=`<div class="toast align-items-center border-0 shadow-lg" style="background:white;border-left:4px solid ${bc};min-width:280px;"><div class="d-flex"><div class="toast-body"><span>${m}</span></div><button class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button></div></div>`;
        c.insertAdjacentHTML('beforeend',h);const te=c.lastElementChild;new bootstrap.Toast(te,{delay:3000}).show();te.addEventListener('hidden.bs.toast',()=>te.remove());
    }
    
    document.addEventListener('DOMContentLoaded',()=>navigateTo('dashboard',document.querySelector('.sidebar-link.active')));
</script>
</body>
</html>