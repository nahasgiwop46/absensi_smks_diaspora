<?= $this->extend('layouts/guru') ?>

<?= $this->section('content') ?>

<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
    <div>
        <h2 class="page-title" style="margin-bottom:4px;">
            <i class="fas fa-clipboard-list" style="color:#059669;margin-right:8px;"></i>Input Absensi
        </h2>
        <p class="page-subtitle">
            Pilih kelas untuk mengisi absensi • <?= date('d F Y', strtotime($tanggal_filter ?? date('Y-m-d'))) ?>
        </p>
    </div>
    <a href="/guru" class="btn btn-outline btn-sm no-print">
        <i class="fas fa-arrow-left"></i> Dashboard
    </a>
</div>

<?php if (!empty($jadwal_kelas)): ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
        <?php foreach ($jadwal_kelas as $jk): ?>
        <a href="<?= base_url('guru/absensi/input-kelas/' . $jk['kelas_id'] . '?tanggal=' . $tanggal_filter) ?>" 
           style="text-decoration:none;">
            <div class="card" style="text-align:center;padding:32px 20px;cursor:pointer;transition:all 0.2s;border:2px solid var(--border);">
                <div style="width:56px;height:56px;background:var(--primary-light);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fas fa-door-open" style="font-size:28px;color:#059669;"></i>
                </div>
                <h3 style="font-size:18px;margin-bottom:4px;color:var(--text);"><?= esc($jk['nama_kelas'] ?? '') ?></h3>
                <p style="font-size:12px;color:var(--text-secondary);margin-bottom:12px;">
                    <i class="fas fa-clock"></i> Klik untuk input absensi
                </p>
                <span style="display:inline-block;background:#ECFDF5;color:#059669;padding:6px 16px;border-radius:20px;font-size:12px;font-weight:600;">
                    <i class="fas fa-edit"></i> Input Absensi
                </span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="empty-state">
        <i class="fas fa-calendar-times" style="font-size:48px;color:#D1D5DB;"></i>
        <h3 style="margin-top:16px;">Tidak ada jadwal mengajar</h3>
        <p>Anda tidak memiliki jadwal kelas hari ini (<?= date('d F Y', strtotime($tanggal_filter ?? date('Y-m-d'))) ?>).</p>
        <a href="/guru" class="btn btn-primary btn-sm" style="margin-top:12px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>