<div class="header-main">
    <div class="container-fluid px-3">
        <div class="d-flex align-items-center gap-2">
            <!-- Toggle buttons -->
            <button class="btn-touch d-lg-none" onclick="openMobileSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <button class="btn-touch d-none d-lg-flex" onclick="toggleDesktopSidebar()">
                <i class="bi bi-list"></i>
            </button>
            
            <!-- Logo -->
            <div class="logo-icon">
                <i class="bi bi-qr-code-scan"></i>
            </div>
            <div>
                <h1>SMKS DIASPORA</h1>
                <p class="subtitle mb-0">SISTEM ABSENSI QR CODE</p>
            </div>
            
            <!-- User Info -->
            <div class="ms-auto d-flex align-items-center gap-2">
                <div class="d-none d-md-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:50%;background:#3b82f6;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:13px;">
                        <?= esc(substr(session()->get('nama_lengkap') ?? 'A', 0, 1)) ?>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:600;"><?= esc(session()->get('nama_lengkap') ?? 'Administrator') ?></div>
                        <small style="font-size:9px;color:#64748b;"><?= esc(session()->get('email') ?? 'admin@smks.sch.id') ?></small>
                    </div>
                </div>
                <button class="btn-logout" onclick="window.location.href='<?= base_url('logout') ?>'" title="Logout">
                    <i class="bi bi-box-arrow-right"></i> 
                    <span class="d-none d-sm-inline">Keluar</span>
                </button>
            </div>
        </div>
    </div>
</div>