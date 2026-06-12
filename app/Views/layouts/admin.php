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
            --sidebar-hover-width: 260px;
            --header-height: 60px;
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
            min-height: 100vh; line-height: 1.5;
            -webkit-tap-highlight-color: transparent; overflow-x: hidden;
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            position: fixed; top: 0; left: 0;
            height: 100vh; height: -webkit-fill-available;
            width: var(--sidebar-width); background: var(--white);
            border-right: 1px solid var(--border); z-index: 1100;
            transition: var(--transition); display: flex; flex-direction: column;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        /* Collapsed state */
        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }
        
        /* Hover expand - only on desktop */
        @media (min-width: 1024px) {
            .sidebar.collapsed:hover {
                width: var(--sidebar-hover-width);
                box-shadow: var(--shadow-lg);
            }
            
            .sidebar.collapsed:hover .sidebar-title,
            .sidebar.collapsed:hover .nav-item span,
            .sidebar.collapsed:hover .sidebar-user-name,
            .sidebar.collapsed:hover .sidebar-user-role {
                opacity: 1;
                width: auto;
                visibility: visible;
            }
            
            .sidebar.collapsed:hover .sidebar-user > div {
                display: block;
            }
        }

        .sidebar-header {
            height: var(--header-height); display: flex; align-items: center;
            padding: 0 16px; border-bottom: 1px solid var(--border); gap: 10px;
            flex-shrink: 0;
        }
        
        .sidebar-logo {
            width: 38px; height: 38px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: white; font-size: 16px; flex-shrink: 0;
            overflow: hidden;
            background: var(--primary);
        }
        
        .sidebar-title { 
            font-size: 16px; font-weight: 700; white-space: nowrap; 
            transition: var(--transition);
            opacity: 1;
        }
        
        .sidebar.collapsed .sidebar-title { 
            opacity: 0; width: 0; visibility: hidden;
        }

        .sidebar-toggle {
            margin-left: auto; width: 32px; height: 32px;
            border: none; background: #F9FAFB; border-radius: 8px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            color: var(--text-secondary); font-size: 14px; flex-shrink: 0; 
            transition: var(--transition);
        }
        
        .sidebar-toggle:hover { 
            background: var(--primary-light); color: var(--primary); 
        }
        
        .sidebar-toggle:active {
            transform: scale(0.9);
        }

        .sidebar-nav { 
            flex: 1; overflow-y: auto; overflow-x: hidden;
            padding: 12px 8px; 
        }
        
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { 
            background: var(--border); border-radius: 4px; 
        }

        .nav-section {
            padding: 8px 0;
        }
        
        .nav-section-title {
            font-size: 10px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.5px; color: var(--text-secondary);
            padding: 8px 16px; margin-top: 8px;
            transition: var(--transition);
            white-space: nowrap;
        }
        
        .sidebar.collapsed .nav-section-title {
            opacity: 0; height: 0; padding: 0; margin: 0; overflow: hidden;
        }

        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px; margin-bottom: 2px;
            border-radius: 10px; cursor: pointer;
            transition: var(--transition); color: var(--text-secondary);
            text-decoration: none; white-space: nowrap;
            position: relative;
            overflow: hidden;
        }
        
        .nav-item::before {
            content: '';
            position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 0; background: var(--primary);
            border-radius: 0 4px 4px 0; transition: var(--transition);
        }
        
        .nav-item:hover { 
            background: var(--primary-light); color: var(--primary); 
        }
        
        .nav-item:hover::before {
            height: 60%;
        }
        
     .nav-item:active { 
    /* No transform */
}
        
        .nav-item.active {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white; 
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4); 
            font-weight: 600;
        }
        
        .nav-item.active::before {
            height: 60%; background: white;
        }
        
        .nav-item i { 
            font-size: 18px; width: 22px; text-align: center; flex-shrink: 0;
            transition: var(--transition);
        }
        
        .nav-item span { 
            font-size: 13px; font-weight: 500; 
            transition: var(--transition);
            opacity: 1;
        }
        
        .sidebar.collapsed .nav-item span { 
            opacity: 0; width: 0; visibility: hidden;
        }
        
        /* Tooltip for collapsed sidebar */
        @media (min-width: 1024px) {
            .sidebar.collapsed .nav-item {
                position: relative;
            }
            
            .sidebar.collapsed .nav-item::after {
                content: attr(data-tooltip);
                position: absolute; left: 100%; top: 50%; transform: translateY(-50%);
                background: var(--text); color: white;
                padding: 6px 12px; border-radius: 6px;
                font-size: 12px; white-space: nowrap;
                opacity: 0; visibility: hidden;
                transition: var(--transition);
                margin-left: 10px;
                z-index: 1200;
                pointer-events: none;
            }
            
            .sidebar.collapsed .nav-item:hover::after {
                opacity: 1; visibility: visible;
                margin-left: 14px;
            }
            
            /* Hide tooltip when hovering to expand */
            .sidebar.collapsed:hover .nav-item::after {
                opacity: 0 !important;
                visibility: hidden !important;
            }
        }

        .sidebar-footer { 
            padding: 14px; border-top: 1px solid var(--border); 
            flex-shrink: 0;
        }
        
        .sidebar-user { 
            display: flex; align-items: center; gap: 10px; 
            transition: var(--transition);
        }
        
        .sidebar-avatar {
            width: 38px; height: 38px; 
            background: linear-gradient(135deg, var(--primary), var(--purple));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 15px; flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }
        
        .sidebar-user > div {
            transition: var(--transition);
            overflow: hidden;
        }
        
        .sidebar-user-name { 
            font-size: 13px; font-weight: 600; 
            transition: var(--transition);
            white-space: nowrap;
        }
        
        .sidebar-user-role { 
            font-size: 11px; color: var(--text-secondary); 
            transition: var(--transition);
            white-space: nowrap;
        }
        
        .sidebar.collapsed .sidebar-user-name,
        .sidebar.collapsed .sidebar-user-role {
            opacity: 0; width: 0;
        }

        /* ==================== OVERLAY ==================== */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.5); z-index: 1050;
            opacity: 0; transition: opacity 0.3s ease;
            backdrop-filter: blur(2px);
        }
        
        .sidebar-overlay.active { display: block; opacity: 1; }

        /* ==================== MAIN CONTENT ==================== */
        .main-content {
            margin-left: var(--sidebar-width); 
            transition: var(--transition);
            min-height: 100vh; display: flex; flex-direction: column;
            animation: fadeIn 0.4s ease;
        }
        
        .main-content.expanded { margin-left: var(--sidebar-collapsed); }
        
        @keyframes fadeIn { from { opacity: 0.6; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        /* ==================== HEADER ==================== */
        .header {
            height: var(--header-height); background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 14px; position: sticky; top: 0; z-index: 1000;
            flex-shrink: 0; gap: 10px; transition: box-shadow 0.3s;
            backdrop-filter: blur(10px); background: rgba(255, 255, 255, 0.95);
        }
        
        .header.scrolled { box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
        
        .header-left { display: flex; align-items: center; gap: 10px; min-width: 0; flex: 1; }
        .header-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

        .hamburger-btn {
            display: flex; align-items: center; justify-content: center;
            background: #F9FAFB; border: 2px solid var(--border); 
            width: 38px; height: 38px;
            border-radius: 10px; cursor: pointer; color: var(--text);
            font-size: 18px; flex-shrink: 0; transition: var(--transition);
        }
        
        .hamburger-btn:hover { 
            background: var(--primary-light); border-color: var(--primary); 
            color: var(--primary);
        }
        
        .hamburger-btn:active { 
            transform: scale(0.93); 
        }

        .breadcrumb {
            font-size: 13px; color: var(--text-secondary);
            display: flex; align-items: center; gap: 6px;
            min-width: 0; overflow: hidden;
        }
        
        .breadcrumb a { color: var(--text-secondary); text-decoration: none; white-space: nowrap; }
        .breadcrumb a:hover { color: var(--primary); }
        .breadcrumb .separator { font-size: 9px; flex-shrink: 0; color: #9CA3AF; }
        .breadcrumb .current { color: var(--primary); font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .header-user { 
            display: none; font-size: 12px; align-items: center; gap: 6px; 
            color: var(--text-secondary);
        }
        
        .header-user i { color: var(--primary); font-size: 16px; }

        .header-logout {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px;
            height: 36px; padding: 0 12px;
            border-radius: 10px; border: 2px solid var(--border);
            background: var(--white); color: var(--text);
            text-decoration: none; font-size: 13px; font-weight: 500;
            transition: var(--transition); flex-shrink: 0;
        }
        
        .header-logout:hover { 
            background: #FEF2F2; color: var(--danger); 
            border-color: var(--danger);
        }
        
        .header-logout:active { 
            transform: scale(0.93); 
        }
        
        .header-logout i { font-size: 15px; }

        /* ==================== PAGE CONTENT ==================== */
        .page-content { padding: 16px; flex: 1; animation: pageEnter 0.4s ease; }
        
        @keyframes pageEnter { 
            from { opacity: 0; transform: translateY(12px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        
        .page-title { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .page-subtitle { font-size: 13px; color: var(--text-secondary); margin-bottom: 16px; }

        /* ==================== STATS ==================== */
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 16px; }
        
        .stats-grid .stat-card { animation: cardPop 0.5s ease both; }
        .stats-grid .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stats-grid .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stats-grid .stat-card:nth-child(3) { animation-delay: 0.15s; }
        .stats-grid .stat-card:nth-child(4) { animation-delay: 0.2s; }
        
        @keyframes cardPop { 
            from { opacity: 0; transform: scale(0.9) translateY(20px); } 
            to { opacity: 1; transform: scale(1) translateY(0); } 
        }

        .stat-card {
            background: var(--white); border-radius: 14px; padding: 16px;
            border: 1px solid var(--border); box-shadow: var(--shadow);
            display: flex; flex-direction: column; align-items: center;
            text-align: center; gap: 6px; transition: var(--transition);
            cursor: pointer;
        }
        
        .stat-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }
        
        .stat-card:active { transform: scale(0.97); }
        
        .stat-icon { 
            width: 38px; height: 38px; border-radius: 10px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 16px; 
        }
        
        .stat-icon.blue { background: #EFF6FF; color: #2563EB; }
        .stat-icon.green { background: #ECFDF5; color: #10B981; }
        .stat-icon.purple { background: #F5F3FF; color: #8B5CF6; }
        .stat-icon.orange { background: #FFF7ED; color: #F97316; }
        .stat-value { font-size: 22px; font-weight: 700; }
        .stat-label { font-size: 11px; color: var(--text-secondary); }

        /* ==================== CARDS ==================== */
        .card {
            background: var(--white); border-radius: 14px;
            border: 1px solid var(--border); box-shadow: var(--shadow);
            margin-bottom: 16px; overflow: hidden;
            animation: cardSlide 0.5s ease both;
        }
        
        @keyframes cardSlide { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        
        .card-header { 
            padding: 14px 16px; border-bottom: 1px solid var(--border); 
            display: flex; flex-wrap: wrap; justify-content: space-between; 
            align-items: center; gap: 10px; 
        }
        
        .card-title { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
        .card-title i { color: var(--primary); }
        
        .card-body { 
            padding: 16px; overflow-x: auto; 
            -webkit-overflow-scrolling: touch; 
        }

        /* ==================== TABLE ==================== */
        .table { 
            width: 100%; border-collapse: collapse; font-size: 13px; 
            min-width: 500px; 
        }
        
        .table thead { background: #F9FAFB; }
        
        .table th { 
            padding: 10px 12px; text-align: left; font-weight: 600; 
            color: var(--text-secondary); font-size: 10px; text-transform: uppercase; 
            letter-spacing: 0.3px; border-bottom: 2px solid var(--border); 
            white-space: nowrap; 
        }
        
        .table td { padding: 10px 12px; border-bottom: 1px solid var(--border); }
        .table tbody tr { transition: all 0.2s ease; }
        .table tbody tr:hover { background: #EFF6FF; }

        /* ==================== BADGES ==================== */
        .badge { 
            display: inline-flex; align-items: center; gap: 4px;
            padding: 4px 10px; border-radius: 20px; 
            font-size: 10px; font-weight: 600; white-space: nowrap; 
        }
        
        .badge i { font-size: 9px; }
        .badge-success { background: #ECFDF5; color: #059669; }
        .badge-danger { background: #FEF2F2; color: #DC2626; }
        .badge-warning { background: #FFFBEB; color: #D97706; }
        .badge-info { background: #EFF6FF; color: #2563EB; }
        .badge-purple { background: #F5F3FF; color: #7C3AED; }

        /* ==================== BUTTONS ==================== */
        .btn { 
            display: inline-flex; align-items: center; justify-content: center; 
            gap: 6px; padding: 9px 16px; border-radius: 10px; 
            font-size: 13px; font-weight: 600; cursor: pointer; border: none; 
            text-decoration: none; transition: var(--transition); white-space: nowrap; 
        }
        
        .btn:active { transform: scale(0.95); }
        .btn-primary { 
            background: linear-gradient(135deg, var(--primary), var(--primary-dark)); 
            color: white; box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }
        
        .btn-primary:hover {
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
            transform: translateY(-1px);
        }
        
        .btn-outline { 
            background: var(--white); border: 2px solid var(--border); 
            color: var(--text); 
        }
        
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-sm { padding: 5px 10px; font-size: 11px; border-radius: 8px; }
        .btn-icon { 
            width: 32px; height: 32px; padding: 0; border: none; 
            background: transparent; cursor: pointer; font-size: 14px; 
            color: var(--text-secondary); border-radius: 8px; 
            display: inline-flex; align-items: center; justify-content: center; 
            transition: var(--transition); 
        }
        
        .btn-icon:hover { background: #F3F4F6; }
        .btn-icon.edit { color: var(--warning); }
        .btn-icon.edit:hover { background: #FFFBEB; }
        .btn-icon.delete { color: var(--danger); }
        .btn-icon.delete:hover { background: #FEF2F2; }
        .btn-group { display: flex; gap: 4px; }

        /* ==================== TOOLBAR ==================== */
        .toolbar { 
            display: flex; flex-direction: column; gap: 8px; 
            margin-bottom: 12px; 
        }
        
        .search-box { position: relative; flex: 1; min-width: 0; }
        .search-box i { 
            position: absolute; left: 12px; top: 50%; 
            transform: translateY(-50%); color: #9CA3AF; 
            font-size: 13px; pointer-events: none; 
        }
        
        .search-box input { 
            width: 100%; padding: 10px 12px 10px 36px; 
            border: 2px solid var(--border); border-radius: 10px; 
            font-size: 13px; background: var(--white); 
            transition: var(--transition); 
        }
        
        .search-box input:focus { 
            outline: none; border-color: var(--primary); 
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); 
        }
        
        .filter-select { 
            padding: 10px 30px 10px 12px; border: 2px solid var(--border); 
            border-radius: 10px; font-size: 13px; background: var(--white); 
            cursor: pointer; appearance: none; 
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); 
            background-repeat: no-repeat; background-position: right 10px center; 
        }

        /* ==================== FORM ==================== */
        .form-group { margin-bottom: 14px; }
        .form-label { 
            display: block; font-size: 12px; font-weight: 600; 
            color: var(--text); margin-bottom: 4px; 
        }
        
        .form-input, .form-select, .form-textarea { 
            width: 100%; padding: 10px 14px; border: 2px solid var(--border); 
            border-radius: 10px; font-size: 13px; background: #F9FAFB; 
            transition: var(--transition); font-family: inherit; 
        }
        
        .form-input:focus, .form-select:focus { 
            outline: none; border-color: var(--primary); background: white; 
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); 
        }
        
        .form-row { display: grid; grid-template-columns: 1fr; gap: 0; }

        /* ==================== ALERTS ==================== */
        .alert { 
            padding: 12px 14px; border-radius: 10px; margin-bottom: 12px; 
            display: flex; align-items: flex-start; gap: 8px; font-size: 12px; 
            animation: alertIn 0.3s ease; border-left: 4px solid;
        }
        
        @keyframes alertIn { 
            from { opacity: 0; transform: translateY(-10px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        
        .alert i { font-size: 14px; margin-top: 1px; flex-shrink: 0; }
        .alert-success { 
            background: #ECFDF5; color: #059669; 
            border-left-color: #059669; 
        }
        
        .alert-error { 
            background: #FEF2F2; color: #DC2626; 
            border-left-color: #DC2626; 
        }
        
        .alert-warning { 
            background: #FFFBEB; color: #D97706; 
            border-left-color: #D97706; 
        }

        /* ==================== EMPTY STATE ==================== */
        .empty-state { 
            text-align: center; padding: 40px 16px; 
            color: var(--text-secondary); 
        }
        
        .empty-state i { font-size: 40px; margin-bottom: 10px; color: #D1D5DB; }
        .empty-state p { font-size: 13px; }

        /* ==================== LOADING SKELETON ==================== */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 8px;
        }
        
        @keyframes shimmer {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* ==================== RESPONSIVE - MOBILE (<1024px) ==================== */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.mobile-open { transform: translateX(0); }
            .main-content { margin-left: 0 !important; }
            .hamburger-btn { display: flex; }
            .header-user { display: none; }
            
            /* Disable hover expand on mobile */
            .sidebar.collapsed:hover { width: var(--sidebar-collapsed); }
        }

        /* ==================== RESPONSIVE - TABLET (≥640px) ==================== */
        @media (min-width: 640px) {
            .page-content { padding: 20px; }
            .page-title { font-size: 22px; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
            .stat-card { padding: 18px; flex-direction: row; text-align: left; align-items: center; gap: 12px; }
            .toolbar { flex-direction: row; align-items: center; }
            .card-header { padding: 16px 20px; }
            .card-body { padding: 20px; }
            .header { padding: 0 18px; }
            .header-user { display: flex; }
            .header-logout { width: auto; height: auto; padding: 7px 14px; font-size: 12px; }
        }

        /* ==================== RESPONSIVE - TABLET LANDSCAPE (≥768px) ==================== */
        @media (min-width: 768px) {
            .page-title { font-size: 24px; }
            .stat-value { font-size: 26px; }
            .form-row { grid-template-columns: 1fr 1fr; gap: 12px; }
            .table { font-size: 13px; min-width: auto; }
            .table th { padding: 12px 14px; font-size: 11px; }
            .table td { padding: 12px 14px; }
        }

        /* ==================== RESPONSIVE - DESKTOP (≥1024px) ==================== */
        @media (min-width: 1024px) {
            .sidebar { transform: translateX(0); width: 240px; }
            .main-content { margin-left: 240px; }
            .hamburger-btn { display: none; }
            .header { padding: 0 24px; }
            .page-content { padding: 24px; }
            .page-title { font-size: 26px; }
            .stats-grid { grid-template-columns: repeat(4, 1fr); gap: 16px; }
            .stat-card { padding: 20px; flex-direction: column; text-align: center; align-items: center; gap: 8px; }
            .stat-value { font-size: 28px; }
            .card-header { padding: 18px 24px; }
            .card-body { padding: 24px; }
            .table { font-size: 14px; }
            .toolbar { flex-direction: row; }
            .form-row { gap: 16px; }
        }

        /* ==================== RESPONSIVE - LARGE DESKTOP (≥1280px) ==================== */
        @media (min-width: 1280px) {
            .sidebar { width: 260px; }
            .main-content { margin-left: 260px; }
            .page-content { padding: 28px 32px; }
            .stat-card { padding: 24px; }
            .stat-value { font-size: 32px; }
        }

        /* ==================== PRINT ==================== */
        @media print {
            .sidebar, .sidebar-overlay, .header, .btn, .btn-icon, .no-print { 
                display: none !important; 
            }
            .main-content { margin-left: 0 !important; }
            .page-content { padding: 0 !important; }
            body { background: white; }
        }

        /* ==================== HEADER DROPDOWN ==================== */
.header-dropdown {
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    padding: 6px 10px;
    border-radius: 10px;
    transition: var(--transition);
}
.header-dropdown:hover {
    background: #F9FAFB;
}
.header-dropdown .dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    min-width: 200px;
    padding: 8px;
    display: none;
    z-index: 1001;
}
.header-dropdown.active .dropdown-menu {
    display: block;
    animation: fadeIn 0.2s ease;
}
.header-dropdown .dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 8px;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 13px;
    transition: var(--transition);
    cursor: pointer;
}
.header-dropdown .dropdown-item:hover {
    background: #F3F4F6;
    color: var(--text);
}
.header-dropdown .dropdown-item.danger {
    color: var(--danger);
}
.header-dropdown .dropdown-item.danger:hover {
    background: #FEF2F2;
}
.header-dropdown .dropdown-divider {
    height: 1px;
    background: var(--border);
    margin: 6px 0;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

/* ==================== HEADER DROPDOWN ==================== */
.header-dropdown {
    position: relative;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    padding: 6px 10px;
    border-radius: 10px;
    transition: var(--transition);
}
.header-dropdown:hover {
    background: #F9FAFB;
}
.header-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563EB, #8B5CF6);
    display: flex; align-items: center; justify-content: center;
    color: white; font-weight: 700; font-size: 14px;
    flex-shrink: 0;
}
.header-user-info {
    display: none;
    font-size: 12px;
    align-items: center;
    gap: 6px;
    color: var(--text-secondary);
}
.header-user-info i { color: var(--primary); font-size: 16px; }
.dropdown-arrow-icon {
    font-size: 10px;
    color: #6B7280;
    transition: transform 0.2s;
}
.header-dropdown.active .dropdown-arrow-icon {
    transform: rotate(180deg);
}

.dropdown-menu {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    background: white;
    border: 1px solid var(--border);
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    min-width: 220px;
    padding: 6px;
    display: none;
    z-index: 1001;
}
.header-dropdown.active .dropdown-menu {
    display: block;
    animation: fadeInDown 0.2s ease;
}
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

.dropdown-header {
    padding: 10px 14px;
    border-bottom: 1px solid var(--border);
    margin-bottom: 4px;
}
.dropdown-user-name {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
}
.dropdown-user-role {
    font-size: 11px;
    color: var(--text-secondary);
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 8px;
    color: var(--text-secondary);
    text-decoration: none;
    font-size: 13px;
    transition: var(--transition);
    cursor: pointer;
}
.dropdown-item:hover {
    background: #F3F4F6;
    color: var(--text);
}
.dropdown-item i {
    width: 18px;
    text-align: center;
    font-size: 14px;
}
.dropdown-item.danger {
    color: var(--danger);
}
.dropdown-item.danger:hover {
    background: #FEF2F2;
}

.dropdown-divider {
    height: 1px;
    background: var(--border);
    margin: 4px 0;
}

/* Tablet & Desktop */
@media (min-width: 640px) {
    .header-user-info {
        display: flex;
    }
}



/*  */
.sidebar-nav {
    scroll-behavior: auto !important; /* Tidak ada animasi scroll */
    overflow-y: overlay; /* Scrollbar tidak memakan space */
}

.sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: #D1D5DB;
    border-radius: 4px;
}
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="tutupSidebar()"></div>

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <?php if (file_exists(FCPATH . 'uploads/logo/logo.jpeg')): ?>
                <img src="<?= base_url('uploads/logo/logo.jpeg') ?>" alt="Logo" 
                     style="width:100%;height:100%;object-fit:contain;background:white;border-radius:10px;">
            <?php else: ?>
                <i class="fas fa-graduation-cap"></i>
            <?php endif; ?>
        </div>
        <span class="sidebar-title">Absensi QR</span>
        <button class="sidebar-toggle" onclick="toggleSidebar()" title="Toggle Sidebar">
            <i class="fas fa-chevron-left" id="toggleIcon"></i>
        </button>
    </div>
    
   <?php
$currentUrl = current_url();
$uri = service('uri');
$segments = $uri->getSegments();
$seg2 = $segments[1] ?? '';
$seg3 = $segments[2] ?? '';
$seg4 = $segments[3] ?? '';
?>

<nav class="sidebar-nav">
    <!-- MENU UTAMA -->
    <div class="nav-section-title">Menu Utama</div>
    
    <a href="/admin" class="nav-item <?= ($seg2 === '' || $seg2 === 'dashboard') ? 'active' : '' ?>" data-tooltip="Dashboard">
        <i class="fas fa-th-large"></i> <span>Dashboard</span>
    </a>
    
    <a href="/admin/setup" class="nav-item <?= ($seg2 === 'setup') ? 'active' : '' ?>" data-tooltip="Setup Wizard">
        <i class="fas fa-magic"></i> <span>Setup Wizard</span>
    </a>
    
    <a href="/admin/users" class="nav-item <?= ($seg2 === 'users') ? 'active' : '' ?>" data-tooltip="Manajemen User">
        <i class="fas fa-users-cog"></i> <span>Manajemen User</span>
    </a>
    
    <!-- DATA MASTER -->
    <div class="nav-section-title">Data Master</div>
    
    <a href="/admin/siswa" class="nav-item <?= ($seg2 === 'siswa' && $seg3 === '') ? 'active' : '' ?>" data-tooltip="Data Siswa">
        <i class="fas fa-user-graduate"></i> <span>Data Siswa</span>
    </a>
    
    <a href="/admin/guru" class="nav-item <?= ($seg2 === 'guru' && $seg3 === '') ? 'active' : '' ?>" data-tooltip="Data Guru">
        <i class="fas fa-chalkboard-teacher"></i> <span>Data Guru</span>
    </a>
    
    <a href="/admin/kelas" class="nav-item <?= ($seg2 === 'kelas') ? 'active' : '' ?>" data-tooltip="Data Kelas">
        <i class="fas fa-door-open"></i> <span>Data Kelas</span>
    </a>
    
    <a href="/admin/jurusan" class="nav-item <?= ($seg2 === 'jurusan') ? 'active' : '' ?>" data-tooltip="Jurusan">
        <i class="fas fa-graduation-cap"></i> <span>Jurusan</span>
    </a>
    
    <a href="/admin/mapel" class="nav-item <?= ($seg2 === 'mapel') ? 'active' : '' ?>" data-tooltip="Mata Pelajaran">
        <i class="fas fa-book-open"></i> <span>Mata Pelajaran</span>
    </a>
    
    <a href="/admin/siswa/penempatan-kelas" class="nav-item <?= ($seg2 === 'siswa' && $seg3 === 'penempatan-kelas') ? 'active' : '' ?>" data-tooltip="Penempatan Kelas">
        <i class="fas fa-people-arrows"></i> <span>Penempatan Kelas</span>
    </a>
    
    <a href="/admin/guru/penempatan" class="nav-item <?= ($seg2 === 'guru' && $seg3 === 'penempatan') ? 'active' : '' ?>" data-tooltip="Penempatan Guru">
        <i class="fas fa-user-check"></i> <span>Penempatan Guru</span>
    </a>
    
    <!-- AKADEMIK -->
    <div class="nav-section-title">Akademik</div>
    
    <a href="/admin/jadwal" class="nav-item <?= ($seg2 === 'jadwal') ? 'active' : '' ?>" data-tooltip="Jadwal">
        <i class="fas fa-calendar-alt"></i> <span>Jadwal Pelajaran</span>
    </a>
    
    <a href="/admin/semester" class="nav-item <?= ($seg2 === 'semester') ? 'active' : '' ?>" data-tooltip="Semester">
        <i class="fas fa-clock"></i> <span>Semester</span>
    </a>
    
    <a href="/admin/tahun-ajaran" class="nav-item <?= ($seg2 === 'tahun-ajaran') ? 'active' : '' ?>" data-tooltip="Tahun Ajaran">
        <i class="fas fa-calendar-check"></i> <span>Tahun Ajaran</span>
    </a>
    
    <!-- ABSENSI & LAPORAN -->
    <div class="nav-section-title">Absensi & Laporan</div>
    
    <a href="/admin/absensi" class="nav-item <?= ($seg2 === 'absensi') ? 'active' : '' ?>" data-tooltip="Absensi">
        <i class="fas fa-clipboard-check"></i> <span>Data Absensi</span>
    </a>
    
    <a href="/admin/laporan" class="nav-item <?= ($seg2 === 'laporan') ? 'active' : '' ?>" data-tooltip="Laporan">
        <i class="fas fa-file-alt"></i> <span>Laporan</span>
    </a>
    <div class="nav-section-title">Sistem</div>

<a href="/admin/keamanan" class="nav-item <?= ($seg2 === 'keamanan') ? 'active' : '' ?>" data-tooltip="Keamanan">
    <i class="fas fa-shield-alt"></i> <span>Keamanan</span>
</a>
<a href="/admin/backup" class="nav-item <?= ($seg2 === 'backup') ? 'active' : '' ?>">
    <i class="fas fa-download"></i> <span>Backup Data</span>
</a>
</nav>   <div class="sidebar-footer">
    <div class="sidebar-user">
        <div class="sidebar-avatar">
            <?php 
            $userModel = new \App\Models\UserModel();
            $userData = $userModel->find(session()->get('user_id'));
            $foto = $userData['foto'] ?? null;
            
            if (!empty($foto) && file_exists(FCPATH . 'uploads/foto/' . $foto)): 
            ?>
                <img src="<?= base_url('uploads/foto/' . $foto) ?>?t=<?= time() ?>" 
                     style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
            <?php else: ?>
                <?= esc(strtoupper(substr(session()->get('nama_lengkap') ?? 'A', 0, 1))) ?>
            <?php endif; ?>
        </div>
        <div>
            <div class="sidebar-user-name"><?= esc(session()->get('nama_lengkap') ?? 'Admin') ?></div>
            <div class="sidebar-user-role"><?= esc(session()->get('role_nama') ?? 'Administrator') ?></div>
        </div>
    </div>
</div>
</aside>

<!-- MAIN CONTENT -->
<main class="main-content" id="mainContent">
    <!-- ==================== HEADER ==================== -->
    <header class="header" id="header">
        <div class="header-left">
            <button class="hamburger-btn" onclick="bukaSidebar()" aria-label="Buka menu">
                <i class="fas fa-bars"></i>
            </button>
            <nav class="breadcrumb">
                <a href="/admin"><i class="fas fa-home"></i> Home</a>
                <?php if (!empty($breadcrumbs)): ?>
                    <?php foreach ($breadcrumbs as $bc): ?>
                        <i class="fas fa-chevron-right separator"></i>
                        <?php if (!empty($bc['url'])): ?>
                            <a href="<?= esc($bc['url']) ?>"><?= esc($bc['name']) ?></a>
                        <?php else: ?>
                            <span class="current"><?= esc($bc['name']) ?></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </nav>
        </div>

        <!-- ==================== HEADER RIGHT ==================== -->
      <div class="header-right">
            <!-- Dropdown User -->
            <div class="header-dropdown" id="userDropdown" onclick="toggleDropdown()">
                <div class="header-avatar">
                    <?php 
                    // ✅ Ambil foto terbaru dari database
                    $userModel = new \App\Models\UserModel();
                    $userData = $userModel->find(session()->get('user_id'));
                    $foto = $userData['foto'] ?? null;
                    
                    if (!empty($foto) && file_exists(FCPATH . 'uploads/foto/' . $foto)): 
                    ?>
                        <img src="<?= base_url('uploads/foto/' . $foto) ?>?t=<?= time() ?>" 
                             style="width:100%;height:100%;border-radius:50%;object-fit:cover;">
                    <?php else: ?>
                        <?= esc(strtoupper(substr(session()->get('nama_lengkap') ?? 'A', 0, 1))) ?>
                    <?php endif; ?>
                </div>
                <div class="header-user-info">
                    <i class="fas fa-user-circle"></i>
                    <span><?= esc(explode(' ', session()->get('nama_lengkap') ?? 'Admin')[0]) ?></span>
                </div>
                <i class="fas fa-chevron-down dropdown-arrow-icon"></i>

                <!-- Dropdown Menu -->
                <div class="dropdown-menu">
                    <!-- Info User -->
                    <div class="dropdown-header">
                        <div class="dropdown-user-name"><?= esc(session()->get('nama_lengkap') ?? 'Admin') ?></div>
                        <div class="dropdown-user-role"><?= esc(session()->get('role_nama') ?? 'Administrator') ?></div>
                    </div>

                    <a href="/admin/profil" class="dropdown-item">
                        <i class="fas fa-user-circle"></i> Profil Saya
                    </a>

                    <a href="/admin/pengaturan" class="dropdown-item">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a>

                    <div class="dropdown-divider"></div>

                    <a href="/admin/log-aktivitas" class="dropdown-item">
                        <i class="fas fa-history"></i> Log Aktivitas
                    </a>

                    <div class="dropdown-divider"></div>

                    <a href="/logout" class="dropdown-item danger">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- ==================== PAGE CONTENT ==================== -->
    <div class="page-content">
        <!-- Alert Success -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle"></i>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <!-- Alert Error -->
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Alert Warning -->
        <?php if (session()->getFlashdata('warning')): ?>
            <div class="alert alert-warning" role="alert">
                <i class="fas fa-exclamation-triangle"></i>
                <span><?= session()->getFlashdata('warning') ?></span>
            </div>
        <?php endif; ?>

        <!-- Konten -->
        <?= $content ?? '' ?>
    </div>
</main>

<script>

    // Toggle dropdown user
function toggleDropdown(e) {
    e?.stopPropagation();
    const dropdown = document.getElementById('userDropdown');
    dropdown.classList.toggle('active');
}

// Tutup dropdown saat klik di luar
document.addEventListener('click', function(e) {
    const dropdown = document.getElementById('userDropdown');
    if (dropdown && !dropdown.contains(e.target)) {
        dropdown.classList.remove('active');
    }
});

// Tutup dropdown saat tekan Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.getElementById('userDropdown')?.classList.remove('active');
    }
});
    // State management
    let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    
    // Initialize sidebar state
    function initSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (sidebarCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('expanded');
            if (toggleIcon) toggleIcon.classList.add('fa-chevron-right');
            if (toggleIcon) toggleIcon.classList.remove('fa-chevron-left');
        }
    }
    
    // Toggle sidebar collapse
    function toggleSidebar() {
        sidebarCollapsed = !sidebarCollapsed;
        localStorage.setItem('sidebarCollapsed', sidebarCollapsed);
        
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        const toggleIcon = document.getElementById('toggleIcon');
        
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        
        if (toggleIcon) {
            toggleIcon.classList.toggle('fa-chevron-right');
            toggleIcon.classList.toggle('fa-chevron-left');
        }
    }
    
    // Mobile sidebar functions
    function bukaSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        sidebar.classList.add('mobile-open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function tutupSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    // Close sidebar when nav item clicked on mobile
    document.querySelectorAll('.sidebar .nav-item').forEach(item => {
        item.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                setTimeout(tutupSidebar, 200);
            }
        });
    });
    
    // Header scroll effect
    window.addEventListener('scroll', () => {
        const header = document.getElementById('header');
        if (header) {
            header.classList.toggle('scrolled', window.scrollY > 10);
        }
    }, { passive: true });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Escape to close mobile sidebar
        if (e.key === 'Escape') tutupSidebar();
        
        // Ctrl+B to toggle sidebar on desktop
        if (e.ctrlKey && e.key === 'b') {
            e.preventDefault();
            if (window.innerWidth >= 1024) toggleSidebar();
        }
    });
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if (window.innerWidth >= 1024) {
                tutupSidebar();
            }
        }, 200);
    });
    
    // Auto dismiss alerts
    document.addEventListener('DOMContentLoaded', () => {
        initSidebar();
        
        // Auto hide alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.transition = 'all 0.4s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(20px)';
                setTimeout(() => {
                    if (alert.parentNode) alert.remove();
                }, 400);
            });
        }, 5000);
        
        // Add ripple effect to buttons
// Add ripple effect to buttons ONLY (bukan nav-item)
document.querySelectorAll('.btn, .btn-icon').forEach(element => {
            element.addEventListener('click', function(e) {
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.4);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => ripple.remove(), 600);
            });
        });
    });
    
    // Add ripple keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Service Worker Registration (optional for PWA)
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            // Uncomment if you have a service worker
            // navigator.serviceWorker.register('/sw.js').catch(() => {});
        });
    }

    // ==================== SCROLL SIDEBAR SEMPURNA ====================
const sidebarNav = document.querySelector('.sidebar-nav');

// 1. SIMPAN posisi scroll sebelum klik
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', (e) => {
        if (sidebarNav) {
            const scrollTop = sidebarNav.scrollTop;
            localStorage.setItem('sidebarScroll', scrollTop);
        }
    });
});

// 2. KEMBALIKAN posisi scroll setelah load (TANPA animasi)
document.addEventListener('DOMContentLoaded', () => {
    const savedScroll = localStorage.getItem('sidebarScroll');
    if (savedScroll && sidebarNav) {
        // Langsung set tanpa smooth scroll
        sidebarNav.style.scrollBehavior = 'auto';
        sidebarNav.scrollTop = parseInt(savedScroll);
    }
    
    // 3. Auto scroll ke menu aktif (kalau tidak ada saved scroll)
    if (!savedScroll) {
        const activeItem = document.querySelector('.nav-item.active');
        if (activeItem) {
            activeItem.scrollIntoView({ block: 'center' });
        }
    }
});
    
    console.log('🚀 Admin Panel Ready with Hover Expand Sidebar!');
    console.log('💡 Tips: Use Ctrl+B to toggle sidebar on desktop');




</script>
</body>
</html>