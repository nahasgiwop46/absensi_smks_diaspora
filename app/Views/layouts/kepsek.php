<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= esc($title ?? 'Kepsek Dashboard') ?> - SMKS Diaspora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('css/kepsek-style.css') ?>">
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeMobileSidebar()"></div>

<?= $this->include('partials/kepsek/_sidebar_mobile') ?>
<?= $this->include('partials/kepsek/_sidebar_desktop') ?>

<div class="main-content" id="mainContent">
    <div class="header-top">
        <div class="container-fluid px-3">
            <div class="d-flex justify-content-between">
                <span class="badge bg-success"><i class="bi bi-circle-fill me-1" style="font-size:5px;"></i>Online</span>
            </div>
        </div>
    </div>
    
    <div class="header-main">
        <div class="container-fluid px-3">
            <div class="d-flex align-items-center gap-2">
                <button class="btn-touch d-lg-none" onclick="openMobileSidebar()"><i class="bi bi-list"></i></button>
                <button class="btn-touch d-none d-lg-flex" onclick="toggleDesktopSidebar()"><i class="bi bi-list"></i></button>
                <div class="logo-icon"><i class="bi bi-building"></i></div>
                <div><h1>KEPALA SEKOLAH</h1></div>
                <div class="ms-auto d-flex align-items-center gap-2">
                    <span class="d-none d-sm-inline fw-semibold" style="font-size:12px;"><?= esc((string)(session()->get('nama_lengkap') ?? '')) ?></span>
                    <a href="<?= base_url('logout') ?>" class="btn-logout" onclick="return confirm('Yakin keluar?')">
                        <i class="bi bi-box-arrow-right"></i> <span class="d-none d-sm-inline">Keluar</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="breadcrumb-bar">
        <div class="container-fluid px-3"><nav><ol class="breadcrumb mb-0"><li class="breadcrumb-item"><a href="<?= base_url('kepsek/dashboard') ?>"><i class="bi bi-house-door"></i></a></li><?= $this->renderSection('breadcrumb') ?></ol></nav></div>
    </div>
    
    <div class="container-fluid px-3 py-3"><?= $this->renderSection('content') ?></div>
    <div class="footer"><div class="container-fluid px-3">&copy; <?= date('Y') ?> SMKS Diaspora</div></div>
</div>

<?= $this->include('partials/kepsek/_bottom_nav') ?>
<div class="toast-container" id="toastContainer"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('js/kepsek-scripts.js') ?>"></script>
<?= $this->renderSection('scripts') ?>
</body>
</html>