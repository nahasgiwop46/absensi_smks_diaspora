<?php
$currentUri = uri_string();
function isActiveMobile($uri, $match) {
    return strpos($uri, $match) !== false ? 'active' : '';
}
?>

<aside class="sidebar-mobile" id="sidebarMobile">
    <div class="text-center py-4 border-bottom border-white/10">
        <i class="bi bi-qr-code-scan" style="font-size:40px;color:#3b82f6;"></i>
        <h6 class="mt-2 mb-0">Absensi QR Code</h6>
        <small class="opacity-50">SMKS Diaspora</small>
    </div>
    
    <div class="py-2">
        <a href="<?= base_url('admin/dashboard') ?>" 
           class="nav-link <?= isActiveMobile($currentUri, 'admin/dashboard') || $currentUri == 'admin' ? 'active' : '' ?>"
           onclick="closeMobileSidebar()">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        
        <a href="<?= base_url('admin/users') ?>" 
           class="nav-link <?= isActiveMobile($currentUri, 'admin/users') ?>"
           onclick="closeMobileSidebar()">
            <i class="bi bi-people-fill"></i> Manajemen User
        </a>
        
        <a href="<?= base_url('admin/siswa') ?>" 
           class="nav-link <?= isActiveMobile($currentUri, 'admin/siswa') ?>"
           onclick="closeMobileSidebar()">
            <i class="bi bi-mortarboard-fill"></i> Data Siswa
        </a>
        
        " 
           class="nav-link <?= isActiveMobile($currentUri, 'admin/guru') ?>"
           onclick="closeMobileSidebar()">
            <i class="bi bi-person-badge-fill"></i> Data Guru
        </a>
        
        <a href="<?= base_url('admin/jurusan') ?>" 
           class="nav-link <?= isActiveMobile($currentUri, 'admin/jurusan') ?>"
           onclick="closeMobileSidebar()">
            <i class="bi bi-diagram-3"></i> Jurusan
        </a>
        
        <a href="<?= base_url('admin/kelas') ?>" 
           class="nav-link <?= isActiveMobile($currentUri, 'admin/kelas') ?>"
           onclick="closeMobileSidebar()">
            <i class="bi bi-door-open"></i> Kelas
        </a>
        
        <a href="<?= base_url('admin/absensi') ?>" 
           class="nav-link <?= isActiveMobile($currentUri, 'admin/absensi') ?>"
           onclick="closeMobileSidebar()">
            <i class="bi bi-clipboard-check"></i> Absensi
        </a>
        
        <a href="<?= base_url('admin/pengaturan') ?>" 
           class="nav-link <?= isActiveMobile($currentUri, 'admin/pengaturan') ?>"
           onclick="closeMobileSidebar()">
            <i class="bi bi-gear-fill"></i> Pengaturan
        </a>
        
        <a href="<?= base_url('logout') ?>" 
           class="logout-btn" 
           onclick="return confirm('Apakah Anda yakin ingin keluar?'); closeMobileSidebar();">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</aside>