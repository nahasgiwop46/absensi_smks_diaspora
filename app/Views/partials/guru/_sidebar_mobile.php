<?php $currentUri = uri_string(); ?>
<aside class="sidebar-mobile" id="sidebarMobile">
    <div class="text-center py-4 border-bottom border-white/10">
        <i class="bi bi-qr-code-scan" style="font-size:40px;color:#34d399;"></i>
        <h6 class="mt-2 mb-0">Guru Panel</h6>
        <small class="opacity-50">SMKS Diaspora</small>
    </div>
    <div class="py-2">
        <a href="<?= base_url('guru/dashboard') ?>" class="nav-link <?= ($currentUri == 'guru/dashboard' || $currentUri == 'guru') ? 'active' : '' ?>" onclick="closeMobileSidebar()">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="<?= base_url('guru/qrcode') ?>" class="nav-link" onclick="closeMobileSidebar()">
            <i class="bi bi-qr-code"></i> Generate QR
        </a>
        <a href="<?= base_url('guru/jadwal') ?>" class="nav-link" onclick="closeMobileSidebar()">
            <i class="bi bi-calendar-check"></i> Jadwal
        </a>
        <a href="<?= base_url('guru/absensi') ?>" class="nav-link" onclick="closeMobileSidebar()">
            <i class="bi bi-clipboard-check"></i> Absensi
        </a>
        <a href="<?= base_url('logout') ?>" class="logout-btn" onclick="closeMobileSidebar(); return confirm('Yakin keluar?');">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</aside>