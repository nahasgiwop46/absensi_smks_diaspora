<?php
$currentUri = uri_string();
?>
<nav class="bottom-nav d-lg-none">
    <a href="<?= base_url('guru/dashboard') ?>" 
       class="nav-item <?= ($currentUri == 'guru/dashboard' || $currentUri == 'guru') ? 'active' : '' ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    
    <a href="<?= base_url('guru/qrcode') ?>" 
       class="nav-item <?= strpos($currentUri, 'guru/qrcode') !== false ? 'active' : '' ?>">
        <i class="bi bi-qr-code"></i> QR
    </a>
    
    <a href="<?= base_url('guru/jadwal') ?>" 
       class="nav-item <?= strpos($currentUri, 'guru/jadwal') !== false ? 'active' : '' ?>">
        <i class="bi bi-calendar-check"></i> Jadwal
    </a>
    
    <a href="<?= base_url('guru/absensi') ?>" 
       class="nav-item <?= strpos($currentUri, 'guru/absensi') !== false ? 'active' : '' ?>">
        <i class="bi bi-clipboard-check"></i> Absensi
    </a>
    
    <div class="nav-item" onclick="openMobileSidebar()">
        <i class="bi bi-grid"></i> Menu
    </div>
</nav>