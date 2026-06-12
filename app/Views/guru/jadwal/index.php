<div class="page-title">
    <i class="fas fa-calendar-week" style="color:#2563EB;margin-right:8px;"></i>Jadwal Mengajar
</div>
<div class="page-subtitle">
    Semester: <?= esc($semester['nama'] ?? '-') ?> | T.A <?= esc($semester['tahun_ajaran_nama'] ?? '') ?>
</div>

<?php if (!empty($jadwal_by_hari)): ?>
    <?php 
    $hari_list = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
    $hari_icons = [
        'senin' => '📅', 'selasa' => '📅', 'rabu' => '📅',
        'kamis' => '📅', 'jumat' => '🕌', 'sabtu' => '📅'
    ];
    foreach ($hari_list as $hari): 
    ?>
    <div class="card" style="margin-bottom:14px;">
        <div class="card-header" style="text-transform:capitalize;">
            <div class="card-title">
                <?= $hari_icons[$hari] ?> <?= $hari ?>
            </div>
            <span style="font-size:12px;color:#6B7280;">
                <?= count($jadwal_by_hari[$hari] ?? []) ?> jadwal
            </span>
        </div>
        <div class="card-body" style="padding:0;">
            <?php if (!empty($jadwal_by_hari[$hari])): ?>
                <?php foreach ($jadwal_by_hari[$hari] as $j): ?>
                <?php
                $jamSekarang = date('H:i');
                $hariSekarang = strtolower(date('l'));
                $hariMap = ['monday'=>'senin','tuesday'=>'selasa','wednesday'=>'rabu','thursday'=>'kamis','friday'=>'jumat','saturday'=>'sabtu'];
                $isNow = ($jamSekarang >= $j['jam_mulai'] && $jamSekarang <= $j['jam_selesai'] && ($hariMap[$hariSekarang] ?? '') == $hari);
                ?>
                <div style="display:flex;align-items:center;padding:16px;border-bottom:1px solid #E5E7EB;<?= $isNow ? 'background:#ECFDF5;border-left:4px solid #059669;' : '' ?>">
                    <!-- Jam -->
                    <div style="text-align:center;min-width:90px;margin-right:16px;">
                        <div style="font-size:18px;font-weight:700;color:#111827;"><?= esc($j['jam_mulai']) ?></div>
                        <div style="font-size:10px;color:#9CA3AF;">s/d</div>
                        <div style="font-size:18px;font-weight:700;color:#111827;"><?= esc($j['jam_selesai']) ?></div>
                    </div>
                    
                    <!-- Info -->
                    <div style="flex:1;">
                        <div style="font-weight:700;font-size:16px;color:#111827;">
                            <?= esc($j['mapel_nama']) ?>
                        </div>
                        <div style="font-size:13px;color:#6B7280;margin-top:2px;">
                            🏫 <?= esc($j['nama_kelas']) ?>
                        </div>
                    </div>
                    
                    <!-- Status -->
                    <?php if ($isNow): ?>
                    <span class="badge badge-success" style="font-size:12px;padding:6px 12px;">
                        🔵 Sedang Berlangsung
                    </span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align:center;padding:24px;color:#9CA3AF;">
                    <small>— Tidak ada jadwal —</small>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
<div class="card">
    <div class="card-body">
        <div class="empty-state">
            <i class="fas fa-calendar-xmark" style="font-size:64px;"></i>
            <p style="font-size:18px;">Belum ada jadwal untuk semester ini</p>
        </div>
    </div>
</div>
<?php endif; ?>