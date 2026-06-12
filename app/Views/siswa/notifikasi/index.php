<div class="page-title">🔔 Notifikasi</div>
<div class="page-subtitle">Notifikasi absensi dan informasi</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-bell"></i> Notifikasi Saya
        </div>
        <?php if (!empty($notifikasi)): ?>
            <span class="badge badge-info"><?= count($notifikasi) ?> notifikasi</span>
        <?php endif; ?>
    </div>
    <div class="card-body" style="padding:0;">
        <?php if (empty($notifikasi)): ?>
            <div class="empty-state">
                <i class="fas fa-bell-slash" style="font-size:48px;"></i>
                <p>Tidak ada notifikasi</p>
            </div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($notifikasi as $n): 
                    $icon = match($n['tipe'] ?? '') {
                        'absen_dibuka' => '🔔',
                        'absen_ditutup' => '🔕',
                        'info' => 'ℹ️',
                        'warning' => '⚠️',
                        'reminder' => '⏰',
                        default => '📋'
                    };
                    $bgColor = ($n['is_dibaca'] ?? 0) == 1 ? '#fff' : '#F0F9FF';
                ?>
                <div style="padding:16px;border-bottom:1px solid #E5E7EB;background:<?= $bgColor ?>;display:flex;gap:12px;align-items:flex-start;cursor:pointer;"
                     onclick="tandaiDibaca(<?= $n['id'] ?>)">
                    <span style="font-size:24px;"><?= $icon ?></span>
                    <div style="flex:1;">
                        <h4 style="font-size:14px;font-weight:600;margin:0;">
                            <?= esc($n['judul'] ?? '') ?>
                            <?php if (($n['is_dibaca'] ?? 0) == 0): ?>
                                <span class="badge badge-danger" style="font-size:10px;margin-left:6px;">BARU</span>
                            <?php endif; ?>
                        </h4>
                        <p style="font-size:13px;color:#6B7280;margin:4px 0;"><?= esc($n['pesan'] ?? '') ?></p>
                        <small style="color:#9CA3AF;">
                            <?= formatTanggal($n['created_at'] ?? '', 'd/m/Y H:i') ?>
                        </small>
                        
                        <?php if ($n['tipe'] === 'absen_dibuka' && ($n['is_clicked'] ?? 0) == 0): ?>
                            <div style="margin-top:8px;">
                                <a href="/siswa/absensi/scan" class="btn btn-primary btn-sm">
                                    <i class="fas fa-camera"></i> Absen Sekarang
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function tandaiDibaca(id) {
    fetch('/siswa/notifikasi/tandaiDibaca/' + id, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        }
    });
}
</script>