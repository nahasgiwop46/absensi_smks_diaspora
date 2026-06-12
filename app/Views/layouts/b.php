<!-- app/Views/layouts/admin.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="theme-color" content="#2563EB">
    <title><?= esc($pageTitle ?? 'Dashboard Admin') ?> - Absensi QR SMKS 006</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1D4ED8;
            --primary-light: #EFF6FF;
            --sidebar-width: 260px;
            --sidebar-collapsed: 70px;
            --header-height: 70px;
            --header-mobile: 60px;
            --bg: #F3F4F6;
            --white: #FFFFFF;
            --border: #E5E7EB;
            --text: #111827;
            --text-secondary: #6B7280;
            --success: #10B981;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #3B82F6;
            --purple: #8B5CF6;
            --shadow: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
            --radius: 12px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--bg); color: var(--text);
            display: flex; min-height: 100vh; min-height: -webkit-fill-available;
            -webkit-tap-highlight-color: transparent; overflow-x: hidden;
        }

        @supports (padding-top: env(safe-area-inset-top)) {
            body { padding-top: env(safe-area-inset-top); }
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            position: fixed; top: 0; left: 0;
            height: 100vh; height: -webkit-fill-available;
            width: var(--sidebar-width); background: var(--white);
            border-right: 1px solid var(--border); z-index: 1000;
            transition: var(--transition); display: flex; flex-direction: column;
            box-shadow: var(--shadow);
        }
        .sidebar.collapsed { width: var(--sidebar-collapsed); }

        .sidebar-header {
            height: var(--header-height); display: flex; align-items: center;
            padding: 0 20px; border-bottom: 1px solid var(--border); gap: 12px;
        }
        .sidebar-logo {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 12px; display: flex; align-items: center; justify-content: center;
            color: white; font-size: 20px; flex-shrink: 0;
        }
        .sidebar-title { font-size: 18px; font-weight: 700; white-space: nowrap; overflow: hidden; transition: var(--transition); }
        .sidebar.collapsed .sidebar-title { opacity: 0; width: 0; }

        .sidebar-toggle {
            margin-left: auto; width: 36px; height: 36px;
            border: none; background: #F9FAFB; border-radius: 8px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary); font-size: 16px; flex-shrink: 0; transition: var(--transition);
        }
        .sidebar-toggle:hover { background: var(--primary-light); color: var(--primary); }

        .sidebar-nav { flex: 1; overflow-y: auto; padding: 16px 12px; }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }

        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px; margin-bottom: 4px;
            border-radius: var(--radius); cursor: pointer;
            transition: var(--transition); color: var(--text-secondary);
            text-decoration: none; white-space: nowrap; position: relative;
        }
        .nav-item:hover { background: var(--primary-light); color: var(--primary); }
        .nav-item:active { transform: scale(0.97); }
        .nav-item.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }
        .nav-item i { font-size: 18px; width: 20px; text-align: center; flex-shrink: 0; }
        .nav-item span { font-size: 14px; font-weight: 500; transition: var(--transition); }
        .sidebar.collapsed .nav-item span { opacity: 0; width: 0; }

        .sidebar-footer { padding: 16px; border-top: 1px solid var(--border); }
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar {
            width: 42px; height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 16px; flex-shrink: 0;
        }
        .user-details { flex: 1; min-width: 0; transition: var(--transition); }
        .sidebar.collapsed .user-details { opacity: 0; width: 0; }
        .user-name { font-size: 14px; font-weight: 600; }
        .user-role { font-size: 12px; color: var(--text-secondary); }

        /* ============ MAIN CONTENT ============ */
        .main-content {
            margin-left: var(--sidebar-width); flex: 1; transition: var(--transition);
            min-height: 100vh; animation: contentFade 0.4s ease;
        }
        .main-content.expanded { margin-left: var(--sidebar-collapsed); }
        @keyframes contentFade { from { opacity: 0.6; } to { opacity: 1; } }

        /* Header */
        .header {
            height: var(--header-height); background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px; position: sticky; top: 0; z-index: 999;
            box-shadow: var(--shadow); transition: box-shadow 0.3s;
        }
        .header.scrolled { box-shadow: 0 4px 20px rgba(0,0,0,0.15); }
        .header-left { display: flex; align-items: center; gap: 16px; }
        .mobile-toggle {
            display: none; width: 40px; height: 40px;
            border: none; background: #F9FAFB; border-radius: 8px;
            cursor: pointer; font-size: 18px; color: var(--text-secondary);
        }
        .breadcrumb { display: flex; align-items: center; gap: 8px; font-size: 14px; color: var(--text-secondary); }
        .breadcrumb a { color: var(--text-secondary); text-decoration: none; }
        .breadcrumb a:hover { color: var(--primary); }
        .breadcrumb .current { color: var(--primary); font-weight: 600; }
        .breadcrumb i { font-size: 12px; }
        .header-right { display: flex; align-items: center; gap: 12px; }

        .btn-profile {
            display: flex; align-items: center; gap: 8px;
            padding: 6px 12px 6px 6px; border: none; background: #F9FAFB;
            border-radius: 25px; cursor: pointer; transition: var(--transition);
        }
        .btn-profile:hover { background: var(--primary-light); }
        .profile-avatar {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 14px;
        }
        .profile-name { font-size: 13px; font-weight: 600; }
        .profile-role { font-size: 11px; color: var(--text-secondary); }

        /* Page Content */
        .page-content { padding: 24px; animation: pageEnter 0.5s ease; }
        @keyframes pageEnter { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
        .page-title { font-size: 26px; font-weight: 700; margin-bottom: 8px; }
        .page-subtitle { color: var(--text-secondary); font-size: 14px; margin-bottom: 24px; }

        /* Stats */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-bottom: 24px; }
        .stat-card {
            background: var(--white); border-radius: 16px; padding: 24px;
            border: 1px solid var(--border); box-shadow: var(--shadow);
            transition: var(--transition); animation: cardPop 0.5s ease both;
        }
        .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.15s; }
        .stat-card:nth-child(4) { animation-delay: 0.2s; }
        @keyframes cardPop { from { opacity: 0; transform: scale(0.9) translateY(20px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .stat-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }
        .stat-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
        .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; }
        .stat-icon.blue { background: #EFF6FF; color: #2563EB; }
        .stat-icon.green { background: #ECFDF5; color: #10B981; }
        .stat-icon.purple { background: #F5F3FF; color: #8B5CF6; }
        .stat-icon.orange { background: #FFF7ED; color: #F97316; }
        .stat-value { font-size: 32px; font-weight: 700; margin-bottom: 4px; }
        .stat-label { font-size: 14px; color: var(--text-secondary); }

        /* Cards & Tables */
        .card {
            background: var(--white); border-radius: 16px;
            border: 1px solid var(--border); box-shadow: var(--shadow);
            margin-bottom: 24px; animation: cardSlideUp 0.5s ease both;
        }
        @keyframes cardSlideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .card-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; }
        .card-title { font-size: 18px; font-weight: 700; }
        .card-body { padding: 24px; overflow-x: auto; -webkit-overflow-scrolling: touch; }

        .table { width: 100%; border-collapse: collapse; font-size: 14px; min-width: 600px; }
        .table thead { background: #F9FAFB; }
        .table th { padding: 14px 16px; text-align: left; font-weight: 600; color: var(--text-secondary); font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid var(--border); white-space: nowrap; }
        .table td { padding: 14px 16px; border-bottom: 1px solid var(--border); }
        .table tbody tr { transition: all 0.2s ease; }
        .table tbody tr:hover { background: #EFF6FF; transform: translateX(4px); box-shadow: -3px 0 0 var(--primary); }

        /* Badges */
        .badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; white-space: nowrap; }
        .badge-success { background: #ECFDF5; color: #059669; }
        .badge-danger { background: #FEF2F2; color: #DC2626; }
        .badge-warning { background: #FFFBEB; color: #D97706; }
        .badge-info { background: #EFF6FF; color: #2563EB; }
        .badge-purple { background: #F5F3FF; color: #7C3AED; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: var(--transition); white-space: nowrap; text-decoration: none; }
        .btn:active { transform: scale(0.95); }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3); }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4); transform: translateY(-2px); }
        .btn-outline { background: var(--white); border: 2px solid var(--border); color: var(--text); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-sm { padding: 6px 12px; font-size: 12px; border-radius: 8px; }
        .btn-icon { width: 34px; height: 34px; padding: 0; border-radius: 8px; justify-content: center; background: transparent; border: none; cursor: pointer; font-size: 16px; color: var(--text-secondary); transition: var(--transition); display: inline-flex; align-items: center; }
        .btn-icon:hover { background: #F3F4F6; }
        .btn-icon.edit:hover { color: var(--warning); background: #FFFBEB; }
        .btn-icon.delete:hover { color: var(--danger); background: #FEF2F2; }
        .btn-group { display: flex; gap: 4px; }

        /* Toolbar */
        .search-box { position: relative; flex: 1; min-width: 200px; }
        .search-box i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 16px; pointer-events: none; }
        .search-box input { width: 100%; padding: 12px 16px 12px 44px; border: 2px solid var(--border); border-radius: 12px; font-size: 14px; background: #F9FAFB; transition: var(--transition); }
        .search-box input:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        .filter-select { padding: 12px 36px 12px 16px; border: 2px solid var(--border); border-radius: 12px; font-size: 14px; background: #F9FAFB; cursor: pointer; appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; }
        .toolbar { display: flex; gap: 12px; flex-wrap: wrap; }

        /* Form */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .form-input, .form-select, .form-textarea { width: 100%; padding: 12px 16px; border: 2px solid var(--border); border-radius: 10px; font-size: 14px; background: #F9FAFB; transition: var(--transition); font-family: inherit; }
        .form-input:focus, .form-select:focus { outline: none; border-color: var(--primary); background: white; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

        /* Alerts */
        .alert { padding: 14px 16px; border-radius: 12px; margin-bottom: 14px; display: flex; align-items: flex-start; gap: 10px; font-size: 13px; animation: alertIn 0.4s ease; }
        @keyframes alertIn { from { opacity: 0; transform: translateY(-15px); } to { opacity: 1; transform: translateY(0); } }
        .alert i { font-size: 16px; margin-top: 1px; flex-shrink: 0; }
        .alert-success { background: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
        .alert-warning { background: #FFFBEB; color: #D97706; border: 1px solid #FDE68A; }

        /* Overlay */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; opacity: 0; transition: opacity 0.3s; }
        .sidebar-overlay.active { display: block; opacity: 1; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
            .mobile-toggle { display: flex; align-items: center; justify-content: center; }
            .stats-grid { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
            .form-row { grid-template-columns: 1fr; }
            .page-title { font-size: 22px; }
        }

        @media (max-width: 768px) {
            .header { height: var(--header-mobile); padding: 0 14px; }
            .sidebar-header { height: var(--header-mobile); }
            .page-content { padding: 16px; }
            .profile-name, .profile-role { display: none; }
            .toolbar { flex-direction: column; }
            .search-box { min-width: 100%; }
            .stats-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .stat-card { padding: 16px; }
            .stat-value { font-size: 24px; }
            .stat-icon { width: 40px; height: 40px; font-size: 18px; }
            .table { font-size: 13px; }
            .table th, .table td { padding: 10px 12px; }
            .card-header { padding: 14px 16px; }
            .card-body { padding: 16px; }
            .btn { padding: 8px 14px; font-size: 12px; }
            .btn-sm { padding: 5px 10px; font-size: 11px; }
            .btn-icon { width: 30px; height: 30px; font-size: 14px; }
            .badge { font-size: 10px; padding: 3px 8px; }
            .form-group { margin-bottom: 14px; }
            .form-input, .form-select { padding: 10px 14px; font-size: 13px; }
            .page-subtitle { font-size: 12px; margin-bottom: 16px; }
        }

        @media (max-width: 480px) {
            .header { height: 56px; padding: 0 10px; }
            .sidebar-header { height: 56px; padding: 0 14px; }
            .page-content { padding: 12px; }
            .stats-grid { grid-template-columns: 1fr; gap: 8px; }
            .stat-card { padding: 14px; flex-direction: row; text-align: left; align-items: center; gap: 12px; }
            .stat-value { font-size: 20px; }
            .stat-label { font-size: 11px; }
            .stat-icon { width: 36px; height: 36px; font-size: 16px; border-radius: 10px; }
            .page-title { font-size: 18px; }
            .card { border-radius: 12px; margin-bottom: 12px; }
            .card-header { padding: 12px 14px; }
            .card-body { padding: 12px; }
            .card-title { font-size: 14px; }
            .btn { padding: 7px 12px; font-size: 11px; border-radius: 8px; }
            .btn-icon { width: 28px; height: 28px; font-size: 13px; }
            .table { font-size: 11px; min-width: 400px; }
            .table th { font-size: 9px; padding: 8px 10px; }
            .table td { padding: 8px 10px; }
            .search-box input { padding: 9px 10px 9px 34px; font-size: 12px; }
            .search-box i { left: 10px; font-size: 12px; }
            .filter-select { padding: 9px 28px 9px 10px; font-size: 12px; background-position: right 8px center; }
            .form-input, .form-select { padding: 9px 12px; font-size: 12px; }
            .form-label { font-size: 11px; }
            .alert { padding: 10px 12px; font-size: 11px; }
            .alert i { font-size: 13px; }
            .breadcrumb { font-size: 11px; }
            .mobile-toggle { width: 34px; height: 34px; font-size: 16px; }
            .sidebar { width: 260px; max-width: 80vw; }
            .sidebar-logo { width: 34px; height: 34px; font-size: 16px; }
            .sidebar-title { font-size: 15px; }
            .nav-item { padding: 10px 12px; font-size: 13px; gap: 10px; }
            .nav-item i { font-size: 15px; width: 18px; }
            .user-avatar { width: 34px; height: 34px; font-size: 14px; }
            .user-name { font-size: 12px; }
            .user-role { font-size: 10px; }
            .sidebar-footer { padding: 12px; }
            .sidebar-nav { padding: 10px 8px; }
        }

        @media (max-width: 360px) {
            .header { height: 50px; padding: 0 8px; }
            .page-content { padding: 8px; }
            .stats-grid { gap: 6px; }
            .stat-card { padding: 10px; gap: 8px; }
            .stat-value { font-size: 18px; }
            .stat-icon { width: 30px; height: 30px; font-size: 14px; border-radius: 8px; }
            .page-title { font-size: 16px; }
            .breadcrumb { font-size: 10px; }
            .btn { padding: 6px 10px; font-size: 10px; }
            .btn-sm { padding: 4px 8px; font-size: 10px; }
            .btn-icon { width: 26px; height: 26px; font-size: 12px; }
            .table { min-width: 350px; }
            .table th { padding: 6px 8px; font-size: 8px; }
            .table td { padding: 6px 8px; font-size: 10px; }
        }

        @media (max-width: 768px) and (orientation: landscape) {
            .header { height: 48px; }
            .sidebar-header { height: 48px; }
            .page-content { padding: 10px; }
            .stats-grid { grid-template-columns: repeat(4, 1fr); gap: 6px; }
            .stat-card { padding: 10px; flex-direction: column; text-align: center; gap: 4px; }
            .stat-value { font-size: 16px; }
            .stat-label { font-size: 9px; }
            .stat-icon { width: 28px; height: 28px; font-size: 12px; }
        }

        @media print {
            .sidebar, .sidebar-overlay, .header, .btn, .btn-icon, .no-print { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .page-content { padding: 0 !important; }
            body { background: white; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo"><i class="fas fa-graduation-cap"></i></div>
        <span class="sidebar-title">Absensi QR</span>
        <button class="sidebar-toggle" onclick="toggleSidebar()" title="Toggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    <nav class="sidebar-nav">
        <a href="/admin" class="nav-item <?= current_url() == '/admin' ? 'active' : '' ?>">
            <i class="fas fa-home"></i> <span>Dashboard</span>
        </a>
        <a href="/admin/users" class="nav-item <?= strpos(current_url(), '/admin/users') !== false ? 'active' : '' ?>">
            <i class="fas fa-users"></i> <span>Manajemen User</span>
        </a>
        <a href="/admin/siswa" class="nav-item <?= strpos(current_url(), '/admin/siswa') !== false ? 'active' : '' ?>">
            <i class="fas fa-user-graduate"></i> <span>Data Siswa</span>
        </a>
        <a href="/admin/guru" class="nav-item <?= strpos(current_url(), '/admin/guru') !== false ? 'active' : '' ?>">
            <i class="fas fa-chalkboard-teacher"></i> <span>Data Guru</span>
        </a>
        <a href="/admin/kelas" class="nav-item <?= strpos(current_url(), '/admin/kelas') !== false ? 'active' : '' ?>">
            <i class="fas fa-school"></i> <span>Data Kelas</span>
        </a>
        <a href="/admin/jurusan" class="nav-item <?= strpos(current_url(), '/admin/jurusan') !== false ? 'active' : '' ?>">
            <i class="fas fa-book"></i> <span>Jurusan</span>
        </a>
        <a href="/admin/mapel" class="nav-item <?= strpos(current_url(), '/admin/mapel') !== false ? 'active' : '' ?>">
            <i class="fas fa-book-open"></i> <span>Mata Pelajaran</span>
        </a>
        <a href="/admin/jadwal" class="nav-item <?= strpos(current_url(), '/admin/jadwal') !== false ? 'active' : '' ?>">
            <i class="fas fa-calendar-alt"></i> <span>Jadwal</span>
        </a>
        <a href="/admin/semester" class="nav-item <?= strpos(current_url(), '/admin/semester') !== false ? 'active' : '' ?>">
            <i class="fas fa-clock"></i> <span>Semester</span>
        </a>
        <a href="/admin/tahun-ajaran" class="nav-item <?= strpos(current_url(), '/admin/tahun-ajaran') !== false ? 'active' : '' ?>">
            <i class="fas fa-calendar"></i> <span>Tahun Ajaran</span>
        </a>
        <a href="/admin/absensi" class="nav-item <?= strpos(current_url(), '/admin/absensi') !== false ? 'active' : '' ?>">
            <i class="fas fa-clipboard-list"></i> <span>Absensi</span>
        </a>
        <a href="/admin/laporan" class="nav-item <?= strpos(current_url(), '/admin/laporan') !== false ? 'active' : '' ?>">
            <i class="fas fa-file-pdf"></i> <span>Laporan</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar"><?= esc(substr(session()->get('nama_lengkap') ?? 'A', 0, 1)) ?></div>
            <div class="user-details">
                <div class="user-name"><?= esc(session()->get('nama_lengkap') ?? 'Admin') ?></div>
                <div class="user-role"><?= esc(session()->get('role_nama') ?? 'Administrator') ?></div>
            </div>
        </div>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="main-content" id="mainContent">
    <header class="header" id="header">
        <div class="header-left">
            <button class="mobile-toggle" onclick="toggleMobileSidebar()"><i class="fas fa-bars"></i></button>
            <nav class="breadcrumb">
                <a href="/admin"><i class="fas fa-home"></i></a>
                <?php if (!empty($breadcrumbs)): ?>
                    <?php foreach ($breadcrumbs as $bc): ?>
                        <i class="fas fa-chevron-right"></i>
                        <?php if (!empty($bc['url'])): ?>
                            <a href="<?= esc($bc['url']) ?>"><?= esc($bc['name']) ?></a>
                        <?php else: ?>
                            <span class="current"><?= esc($bc['name']) ?></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </nav>
        </div>
        <div class="header-right">
            <div class="btn-profile">
                <div class="profile-avatar"><?= esc(substr(session()->get('nama_lengkap') ?? 'A', 0, 1)) ?></div>
                <div>
                    <div class="profile-name"><?= esc(explode(' ', session()->get('nama_lengkap') ?? 'Admin')[0]) ?></div>
                    <div class="profile-role"><?= esc(session()->get('role_nama') ?? 'Admin') ?></div>
                </div>
            </div>
            <a href="/logout" class="btn btn-outline btn-sm no-print"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </header>

    <div class="page-content">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('warning')): ?>
            <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> <?= session()->getFlashdata('warning') ?></div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </div>
</main>

<script>
let sidebarCollapsed = false;

function toggleSidebar() {
    sidebarCollapsed = !sidebarCollapsed;
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('mainContent').classList.toggle('expanded');
}

function toggleMobileSidebar() {
    document.getElementById('sidebar').classList.toggle('mobile-open');
    document.getElementById('sidebarOverlay').classList.toggle('active');
}

function closeMobileSidebar() {
    document.getElementById('sidebar').classList.remove('mobile-open');
    document.getElementById('sidebarOverlay').classList.remove('active');
}

document.querySelectorAll('.sidebar .nav-item').forEach(item => {
    item.addEventListener('click', () => {
        if (window.innerWidth <= 1024) setTimeout(closeMobileSidebar, 200);
    });
});

window.addEventListener('scroll', () => {
    document.getElementById('header').classList.toggle('scrolled', window.scrollY > 10);
}, { passive: true });

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMobileSidebar(); });

document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(a => {
            a.style.transition = 'opacity 0.5s';
            a.style.opacity = '0';
            setTimeout(() => { if (a.parentNode) a.remove(); }, 500);
        });
    }, 5000);
});

console.log('🚀 Admin Panel Ready!');
</script>
</body>
</html>