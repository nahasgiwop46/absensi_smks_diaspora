<?php $currentUri = uri_string(); ?>
<aside class="sidebar-desktop" id="sidebarDesktop">
    <div class="sidebar-header">
        <i class="bi bi-building" style="font-size:36px;color:#a78bfa;"></i>
        <div class="logo-text"><h6 class="mt-2 mb-0">Kepala Sekolah</h6><small class="opacity-50">SMKS Diaspora</small></div>
    </div>
    <div class="py-2">
        <div class="menu-title">Monitoring</div>
        <a href="<?= base_url('kepsek/dashboard') ?>" class="nav-link <?= $currentUri == 'kepsek/dashboard' || $currentUri == 'kepsek' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>
        <a href="<?= base_url('kepsek/rekap') ?>" class="nav-link <?= strpos($currentUri, 'kepsek/rekap') !== false ? 'active' : '' ?>">
            <i class="bi bi-clipboard-data"></i> <span>Rekap Harian</span>
        </a>
        <a href="<?= base_url('kepsek/per-kelas') ?>" class="nav-link <?= strpos($currentUri, 'kepsek/per-kelas') !== false ? 'active' : '' ?>">
            <i class="bi bi-door-open"></i> <span>Per Kelas</span>
        </a>
        <a href="<?= base_url('kepsek/bermasalah') ?>" class="nav-link <?= strpos($currentUri, 'kepsek/bermasalah') !== false ? 'active' : '' ?>">
            <i class="bi bi-exclamation-triangle"></i> <span>Siswa Bermasalah</span>
        </a>
        <a href="<?= base_url('logout') ?>" class="logout-btn" onclick="return confirm('Yakin keluar?')">
            <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
        </a>
    </div>
</aside>