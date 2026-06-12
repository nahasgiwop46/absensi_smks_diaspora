<?php
$currentUri = uri_string();
?>

<nav class="bottom-nav d-lg-none">
    <a href="<?= base_url('admin/dashboard') ?>" 
       class="nav-item <?= isActive($currentUri, 'admin/dashboard') || $currentUri == 'admin' ? 'active' : '' ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    
    <a href="<?= base_url('admin/users') ?>" 
       class="nav-item <?= isActive($currentUri, 'admin/users') ?>">
        <i class="bi bi-people-fill"></i> User
    </a>
    
    <a href="<?= base_url('admin/siswa') ?>" 
       class="nav-item <?= isActive($currentUri, 'admin/siswa') ?>">
        <i class="bi bi-mortarboard-fill"></i> Siswa
    </a>
    
    <div class="nav-item" onclick="openMobileSidebar()">
        <i class="bi bi-grid"></i> Menu
    </div>
</nav>