<?php $currentUri = uri_string(); ?>
<aside class="sidebar-mobile" id="sidebarMobile">
    <div class="text-center py-4 border-bottom border-white/10">
        <i class="bi bi-mortarboard-fill" style="font-size:40px;color:#22d3ee;"></i>
        <h6 class="mt-2 mb-0">Siswa Panel</h6>
        <small class="opacity-50">SMKS Diaspora</small>
    </div>
    <div class="py-2">
        <a href="<?= base_url('siswa/dashboard') ?>" 
           class="nav-link <?= $currentUri == 'siswa/dashboard' || $currentUri == 'siswa' ? 'active' : '' ?>" 
           onclick="closeMobileSidebar()">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        
        <a href="<?= base_url('siswa/scan') ?>" 
           class="nav-link <?= strpos($currentUri, 'scan') !== false ? 'active' : '' ?>" 
           onclick="closeMobileSidebar()">
            <i class="bi bi-qr-code-scan"></i> Scan QR Code
        </a>
        
        <a href="<?= base_url('siswa/riwayat') ?>" 
           class="nav-link <?= strpos($currentUri, 'riwayat') !== false ? 'active' : '' ?>" 
           onclick="closeMobileSidebar()">
            <i class="bi bi-clock-history"></i> Riwayat Absensi
        </a>
        
        <a href="<?= base_url('siswa/rekap') ?>" 
           class="nav-link <?= strpos($currentUri, 'rekap') !== false ? 'active' : '' ?>" 
           onclick="closeMobileSidebar()">
            <i class="bi bi-graph-up"></i> Rekap Kehadiran
        </a>
        
        <a href="<?= base_url('siswa/profil') ?>" 
           class="nav-link <?= strpos($currentUri, 'profil') !== false ? 'active' : '' ?>" 
           onclick="closeMobileSidebar()">
            <i class="bi bi-person-fill"></i> Profil Saya
        </a>
        
        <hr class="border-white/10 mx-3">
        
        <a href="<?= base_url('logout') ?>" class="logout-btn" 
           onclick="closeMobileSidebar(); return confirm('Yakin keluar?');">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</aside>