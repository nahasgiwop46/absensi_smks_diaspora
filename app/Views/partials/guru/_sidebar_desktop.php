<?php
$currentUri = uri_string();
$guruNama = session()->get('nama_lengkap') ?? 'Guru';
$guruFoto = session()->get('foto') ?? null;
$waliKelas = session()->get('wali_kelas') ?? '';

$uri = service('uri');
$seg2 = $uri->getSegment(2) ?? '';
$seg3 = $uri->getSegment(3) ?? '';

// Deteksi halaman aktif (1 menu = 1 active)
$isDashboard  = ($seg2 === '' || $seg2 === 'dashboard');
$isQRCode     = ($seg2 === 'qrcode');
$isScan       = ($seg2 === 'scan');
$isAbsensi    = ($seg2 === 'absensi' && $seg3 !== 'rekap');
$isRekap      = ($seg2 === 'absensi' && $seg3 === 'rekap');
$isNotif      = ($seg2 === 'notifikasi');
$isJadwal     = ($seg2 === 'jadwal');
$isProfil     = ($seg2 === 'profil');
$isSiswa      = ($seg2 === 'siswa');
?>



<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="guru-avatar">
            <?php if ($guruFoto): ?>
                <img src="<?= base_url('uploads/foto/' . $guruFoto) ?>" alt="Foto Guru">
            <?php else: ?>
                <i class="bi bi-person-workspace"></i>
            <?php endif; ?>
        </div>
        <h6><?= esc($guruNama) ?></h6>
        <small><?= $waliKelas ? 'Wali Kelas ' . esc($waliKelas) : 'Guru' ?></small>
    </div>
    
    <div class="nav-menu">
        <div class="nav-section">Menu Utama</div>
        
        <div class="nav-item">
            <a href="<?= base_url('guru') ?>" class="nav-link <?= $isDashboard ? 'active' : '' ?>">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= base_url('guru/qrcode') ?>" class="nav-link <?= $isQRCode ? 'active' : '' ?>">
                <i class="bi bi-qr-code"></i> Generate QR
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= base_url('guru/scan') ?>" class="nav-link <?= $isScan ? 'active' : '' ?>">
                <i class="bi bi-camera-fill"></i> Scan QR Siswa
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= base_url('guru/absensi') ?>" class="nav-link <?= $isAbsensi ? 'active' : '' ?>">
                <i class="bi bi-pencil-square"></i> Absensi Manual
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= base_url('guru/absensi/rekap') ?>" class="nav-link <?= $isRekap ? 'active' : '' ?>">
                <i class="bi bi-bar-chart-fill"></i> Rekap Absensi
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= base_url('guru/siswa') ?>" class="nav-link <?= $isSiswa ? 'active' : '' ?>">
                <i class="bi bi-people-fill"></i> Daftar Siswa
            </a>
        </div>
        
        <div class="nav-section">Lainnya</div>
        
        <div class="nav-item">
            <a href="<?= base_url('guru/jadwal') ?>" class="nav-link <?= $isJadwal ? 'active' : '' ?>">
                <i class="bi bi-calendar-week"></i> Jadwal Mengajar
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= base_url('guru/notifikasi') ?>" class="nav-link <?= $isNotif ? 'active' : '' ?>">
                <i class="bi bi-bell-fill"></i> Kirim Notifikasi
            </a>
        </div>
        
        <div class="nav-item">
            <a href="<?= base_url('guru/profil') ?>" class="nav-link <?= $isProfil ? 'active' : '' ?>">
                <i class="bi bi-person-fill"></i> Profil Saya
            </a>
        </div>
    </div>
</aside>