<?php $currentUri = uri_string(); ?>
<nav class="bottom-nav d-lg-none">
    <a href="<?= base_url('siswa/dashboard') ?>" class="nav-item <?= $currentUri == 'siswa/dashboard' || $currentUri == 'siswa' ? 'active' : '' ?>">
        <i class="bi bi-house-fill"></i> Home
    </a>
    <a href="<?= base_url('siswa/scan') ?>" class="nav-item <?= strpos($currentUri, 'scan') !== false ? 'active' : '' ?>">
        <i class="bi bi-qr-code-scan"></i> Scan
    </a>
    <a href="<?= base_url('siswa/riwayat') ?>" class="nav-item <?= strpos($currentUri, 'riwayat') !== false ? 'active' : '' ?>">
        <i class="bi bi-clock-history"></i> Riwayat
    </a>
    <a href="<?= base_url('siswa/rekap') ?>" class="nav-item <?= strpos($currentUri, 'rekap') !== false ? 'active' : '' ?>">
        <i class="bi bi-graph-up"></i> Rekap
    </a>
    <div class="nav-item" onclick="openMobileSidebar()">
        <i class="bi bi-grid"></i> Menu
    </div>
</nav>