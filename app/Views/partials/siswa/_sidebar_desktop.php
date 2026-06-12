<?php $currentUri = uri_string(); ?>
<aside class="sidebar-desktop" id="sidebarDesktop">
    <div class="sidebar-header">
        <i class="bi bi-mortarboard-fill logo-icon-sidebar"></i>
        <div class="logo-text">
            <h6 class="mt-2 mb-0">Siswa Panel</h6>
            <small class="opacity-50">SMKS Diaspora</small>
        </div>
    </div>
    
    <div class="py-2">
        <div class="menu-title">Menu Utama</div>
        
        <a href="<?= base_url('siswa/dashboard') ?>" 
           class="nav-link <?= $currentUri == 'siswa/dashboard' || $currentUri == 'siswa' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>
        
        <a href="<?= base_url('siswa/scan') ?>" 
           class="nav-link <?= strpos($currentUri, 'scan') !== false ? 'active' : '' ?>">
            <i class="bi bi-qr-code-scan"></i> <span>Scan QR Code</span>
        </a>
        
        <div class="menu-title">Data Saya</div>
        
        <a href="<?= base_url('siswa/riwayat') ?>" 
           class="nav-link <?= strpos($currentUri, 'riwayat') !== false ? 'active' : '' ?>">
            <i class="bi bi-clock-history"></i> <span>Riwayat Absensi</span>
        </a>
        
        <a href="<?= base_url('siswa/rekap') ?>" 
           class="nav-link <?= strpos($currentUri, 'rekap') !== false ? 'active' : '' ?>">
            <i class="bi bi-graph-up"></i> <span>Rekap Kehadiran</span>
        </a>
        
        <a href="<?= base_url('siswa/profil') ?>" 
           class="nav-link <?= strpos($currentUri, 'profil') !== false ? 'active' : '' ?>">
            <i class="bi bi-person-fill"></i> <span>Profil Saya</span>
        </a>
        
        <div class="menu-title">Lainnya</div>
        
        <a href="<?= base_url('logout') ?>" class="logout-btn" onclick="return confirm('Yakin keluar?')">
            <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
        </a>
    </div>
</aside>