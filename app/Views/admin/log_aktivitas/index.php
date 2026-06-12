<?php
/** @var array $logs */
/** @var string $tanggal */
?>
<div class="page-title">📝 Log Aktivitas</div>
<div class="page-subtitle">Riwayat aktivitas sistem</div>

<!-- Filter -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="get" class="toolbar">
            <div class="form-group" style="margin-bottom:0;min-width:200px;">
                <label class="form-label">📅 Tanggal</label>
                <input type="date" name="tanggal" class="form-input" 
                       value="<?= esc($tanggal ?? date('Y-m-d')) ?>">
            </div>
            <div>
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Statistik -->
<div class="stats-grid" style="margin-bottom:20px;">
    <?php 
    $totalInsert = 0; $totalUpdate = 0; $totalDelete = 0;
    if (!empty($logs)) {
        foreach ($logs as $log) {
            $aksi = strtoupper($log['aksi'] ?? '');
            if ($aksi === 'INSERT') $totalInsert++;
            elseif ($aksi === 'UPDATE') $totalUpdate++;
            elseif ($aksi === 'DELETE') $totalDelete++;
        }
    }
    ?>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#10B981;"><?= $totalInsert ?></div>
        <div class="stat-label">➕ Insert</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#F59E0B;"><?= $totalUpdate ?></div>
        <div class="stat-label">✏️ Update</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#EF4444;"><?= $totalDelete ?></div>
        <div class="stat-label">🗑️ Delete</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#2563EB;"><?= count($logs ?? []) ?></div>
        <div class="stat-label">📋 Total Log</div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-history"></i> 
            Log Aktivitas: <?= formatTanggal($tanggal ?? date('Y-m-d'), 'l, d F Y') ?>
        </div>
        <span style="font-size:14px;color:#6B7280;"><?= count($logs ?? []) ?> log</span>
    </div>
    <div class="card-body">
        <?php if (empty($logs)): ?>
            <div class="empty-state">
                <i class="fas fa-history" style="font-size:64px;"></i>
                <p style="font-size:18px;">Tidak ada log aktivitas</p>
                <p style="color:#9CA3AF;">Pilih tanggal lain untuk melihat log</p>
            </div>
        <?php else: ?>
        <div class="table">
            <table style="min-width:800px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aksi</th>
                        <th>Field</th>
                        <th>Nilai Lama</th>
                        <th>Nilai Baru</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($logs as $log): 
                        $aksi = strtoupper($log['aksi'] ?? '');
                        $badgeAksi = [
                            'INSERT' => 'badge-success',
                            'UPDATE' => 'badge-warning',
                            'DELETE' => 'badge-danger',
                        ];
                        $iconAksi = [
                            'INSERT' => '➕',
                            'UPDATE' => '✏️',
                            'DELETE' => '🗑️',
                        ];
                        $badge = $badgeAksi[$aksi] ?? 'badge-info';
                        $icon = $iconAksi[$aksi] ?? '📋';
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td style="white-space:nowrap;">
                            <?= formatTanggal($log['created_at'] ?? '', 'd/m/Y H:i:s') ?>
                        </td>
                        <td><strong><?= esc($log['user_nama'] ?? '-') ?></strong></td>
                        <td>
                            <span class="badge <?= $badge ?>"><?= $icon ?> <?= esc($aksi) ?></span>
                        </td>
                        <td><code style="font-size:12px;"><?= esc($log['field'] ?? '-') ?></code></td>
                        <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" 
                            title="<?= esc($log['nilai_lama'] ?? '') ?>">
                            <?= !empty($log['nilai_lama']) ? esc($log['nilai_lama']) : '<span style="color:#9CA3AF;">-</span>' ?>
                        </td>
                        <td style="max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                            title="<?= esc($log['nilai_baru'] ?? '') ?>">
                            <?= !empty($log['nilai_baru']) ? '<strong>' . esc($log['nilai_baru']) . '</strong>' : '<span style="color:#9CA3AF;">-</span>' ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>