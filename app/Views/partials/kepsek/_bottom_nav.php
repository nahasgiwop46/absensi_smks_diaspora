<?php $currentUri = uri_string(); ?>
<nav class="bottom-nav d-lg-none">
    <a href="<?= base_url('kepsek/dashboard') ?>" class="nav-item <?= $currentUri == 'kepsek/dashboard' || $currentUri == 'kepsek' ? 'active' : '' ?>">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>
    <a href="<?= base_url('kepsek/rekap') ?>" class="nav-item">
        <i class="bi bi-clipboard-data"></i> Rekap
    </a>
    <a href="<?= base_url('kepsek/per-kelas') ?>" class="nav-item">
        <i class="bi bi-door-open"></i> Kelas
    </a>
    <div class="nav-item" onclick="openMobileSidebar()"><i class="bi bi-grid"></i> Menu</div>
</nav>