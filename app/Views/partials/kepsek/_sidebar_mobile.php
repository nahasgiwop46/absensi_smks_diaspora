<?php $currentUri = uri_string(); ?>
<aside class="sidebar-mobile" id="sidebarMobile">
    <div class="text-center py-4 border-bottom border-white/10">
        <i class="bi bi-building" style="font-size:40px;color:#a78bfa;"></i>
        <h6 class="mt-2 mb-0">Kepala Sekolah</h6>
        <small class="opacity-50">SMKS Diaspora</small>
    </div>
    <div class="py-2">
        <a href="<?= base_url('kepsek/dashboard') ?>" class="nav-link <?= $currentUri == 'kepsek/dashboard' || $currentUri == 'kepsek' ? 'active' : '' ?>" onclick="closeMobileSidebar()">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="<?= base_url('kepsek/rekap') ?>" class="nav-link" onclick="closeMobileSidebar()">
            <i class="bi bi-clipboard-data"></i> Rekap Harian
        </a>
        <a href="<?= base_url('kepsek/per-kelas') ?>" class="nav-link" onclick="closeMobileSidebar()">
            <i class="bi bi-door-open"></i> Per Kelas
        </a>
        <a href="<?= base_url('kepsek/bermasalah') ?>" class="nav-link" onclick="closeMobileSidebar()">
            <i class="bi bi-exclamation-triangle"></i> Bermasalah
        </a>
        <a href="<?= base_url('logout') ?>" class="logout-btn" onclick="closeMobileSidebar(); return confirm('Yakin keluar?');">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</aside>