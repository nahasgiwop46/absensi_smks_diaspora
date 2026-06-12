<?php
$currentUri = uri_string();
function isActive($uri, $match) {
    return strpos($uri, $match) !== false ? 'active' : '';
}
?>

<aside class="sidebar-desktop" id="sidebarDesktop">
    <div class="sidebar-header">
        <i class="bi bi-qr-code-scan logo-icon-sidebar"></i>
        <div class="logo-text">
            <h6 class="mt-2 mb-0">Absensi QR Code</h6>
            <small class="opacity-50">SMKS Diaspora</small>
        </div>
    </div>
    
    <div class="py-2">
        <!-- Menu Utama -->
        <div class="menu-title">Menu Utama</div>
        
        <a href="<?= base_url('admin/dashboard') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/dashboard') || $currentUri == 'admin' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
        </a>
        
        <a href="<?= base_url('admin/users') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/users') ?>">
            <i class="bi bi-people-fill"></i> <span>Manajemen User</span>
        </a>
        
        <a href="<?= base_url('admin/siswa') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/siswa') ?>">
            <i class="bi bi-mortarboard-fill"></i> <span>Data Siswa</span>
        </a>
        
        <a href="<?= base_url('admin/guru') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/guru') ?>">
            <i class="bi bi-person-badge-fill"></i> <span>Data Guru</span>
        </a>
        
        <!-- Akademik -->
        <div class="menu-title">Akademik</div>
        
        <a href="<?= base_url('admin/jurusan') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/jurusan') ?>">
            <i class="bi bi-diagram-3"></i> <span>Jurusan</span>
        </a>
        
        <a href="<?= base_url('admin/kelas') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/kelas') ?>">
            <i class="bi bi-door-open"></i> <span>Kelas</span>
        </a>
        
        <!-- TAMBAHAN: Mata Pelajaran -->
        <a href="<?= base_url('admin/mapel') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/mapel') ?>">
            <i class="bi bi-book-fill"></i> <span>Mata Pelajaran</span>
        </a>
        
        <a href="<?= base_url('admin/jadwal') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/jadwal') ?>">
            <i class="bi bi-calendar-check"></i> <span>Jadwal</span>
        </a>
        
        <!-- TAMBAHAN: Tahun Ajaran -->
        <a href="<?= base_url('admin/tahun-ajaran') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/tahun-ajaran') ?>">
            <i class="bi bi-calendar3"></i> <span>Tahun Ajaran</span>
        </a>
        
        <!-- TAMBAHAN: Penempatan Siswa -->
        <a href="<?= base_url('admin/siswa/penempatan-kelas') ?>" 
           class="nav-link <?= isActive($currentUri, 'penempatan-kelas') ?>">
            <i class="bi bi-person-lines-fill"></i> <span>Penempatan Siswa</span>
        </a>
        
        <!-- Absensi & Laporan -->
        <div class="menu-title">Absensi & Laporan</div>
        
        <a href="<?= base_url('admin/absensi') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/absensi') ?>">
            <i class="bi bi-clipboard-check"></i> <span>Data Absensi</span>
        </a>
        
        <a href="<?= base_url('admin/laporan') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/laporan') ?>">
            <i class="bi bi-file-earmark-bar-graph"></i> <span>Laporan</span>
        </a>
        
        <!-- Sistem -->
        <div class="menu-title">Sistem</div>
        
        <a href="<?= base_url('admin/pengaturan') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/pengaturan') ?>">
            <i class="bi bi-gear-fill"></i> <span>Pengaturan</span>
        </a>
        
        <!-- TAMBAHAN: Hari Libur -->
        <a href="<?= base_url('admin/libur') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/libur') ?>">
            <i class="bi bi-calendar-x"></i> <span>Hari Libur</span>
        </a>
        
        <a href="<?= base_url('admin/log-aktivitas') ?>" 
           class="nav-link <?= isActive($currentUri, 'admin/log-aktivitas') ?>">
            <i class="bi bi-journal-text"></i> <span>Log Aktivitas</span>
        </a>
        
        <!-- Logout -->
        <a href="<?= base_url('logout') ?>" 
           class="logout-btn" 
           onclick="return confirm('Apakah Anda yakin ingin keluar?')">
            <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
        </a>
    </div>
</aside>