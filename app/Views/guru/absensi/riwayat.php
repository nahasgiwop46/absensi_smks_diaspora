<div class="page-title">📋 Riwayat Absensi</div>
<div class="page-subtitle">
    <?php if (!empty($siswa)): ?>
        Siswa: <strong><?= esc($siswa['nama_lengkap']) ?></strong> | NIS: <?= esc($siswa['nis']) ?>
    <?php endif; ?>
</div>

<!-- Filter Bulan & Tahun -->
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;">
    <select class="filter-select" onchange="window.location='?bulan='+this.value+'&tahun=<?= esc($filter_tahun ?? date('Y')) ?>'" style="min-width:150px;">
        <?php 
        $bulanList = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
                      '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
        foreach ($bulanList as $val => $label): ?>
            <option value="<?= $val ?>" <?= ($filter_bulan ?? date('m')) == $val ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
    </select>
    <select class="filter-select" onchange="window.location='?bulan=<?= esc($filter_bulan ?? date('m')) ?>&tahun='+this.value" style="min-width:120px;">
        <?php for ($y = date('Y')-1; $y <= date('Y')+1; $y++): ?>
            <option value="<?= $y ?>" <?= ($filter_tahun ?? date('Y')) == $y ? 'selected' : '' ?>><?= $y ?></option>
        <?php endfor; ?>
    </select>
</div>

<!-- Statistik -->
<div class="stats-grid" style="margin-bottom:20px;">
    <?php 
    $totalHadir = 0; $totalIzin = 0; $totalSakit = 0; $totalAlpa = 0; $totalTerlambat = 0;
    if (!empty($riwayat)) {
        foreach ($riwayat as $r) {
            $label = strtolower($r['status_label'] ?? '');
            if ($label === 'hadir') $totalHadir++;
            elseif ($label === 'izin') $totalIzin++;
            elseif ($label === 'sakit') $totalSakit++;
            elseif ($label === 'alpa') $totalAlpa++;
            if (($r['menit_keterlambatan'] ?? 0) > 0) $totalTerlambat++;
        }
    }
    $total = count($riwayat ?? []);
    $persen = $total > 0 ? round(($totalHadir / $total) * 100, 1) : 0;
    ?>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#059669;"><?= $totalHadir ?></div>
        <div class="stat-label">✅ Hadir</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#D97706;"><?= $totalIzin ?></div>
        <div class="stat-label">📝 Izin</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#2563EB;"><?= $totalSakit ?></div>
        <div class="stat-label">🏥 Sakit</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#DC2626;"><?= $totalAlpa ?></div>
        <div class="stat-label">❌ Alpa</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#9A3412;"><?= $totalTerlambat ?></div>
        <div class="stat-label">⏰ Terlambat</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#2563EB;"><?= $persen ?>%</div>
        <div class="stat-label">📈 Kehadiran</div>
    </div>
</div>

<!-- Tabel -->
<?php if (!empty($riwayat)): ?>
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-clock"></i> Riwayat Absensi</div>
        <span class="badge badge-info"><?= count($riwayat) ?> catatan</span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Mapel</th>
                        <th>Jam</th>
                        <th>Status</th>
                        <th>Terlambat</th>
                        <th>Metode</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($riwayat as $r): 
                        $label = strtolower($r['status_label'] ?? '');
                        $badge = match($label) {
                            'hadir' => 'badge-success', 'izin' => 'badge-warning',
                            'sakit' => 'badge-info', 'alpa' => 'badge-danger',
                            default => 'badge-info'
                        };
                        $metode = $r['metode_absensi'] ?? '';
                        $iconMetode = strpos($metode, 'qr') !== false ? '📱 QR' : (strpos($metode, 'manual') !== false ? '✍️ Manual' : $metode);
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= date('d/m/Y', strtotime($r['tanggal'] ?? '')) ?></td>
                        <td><strong><?= esc($r['mapel'] ?? '-') ?></strong></td>
                        <td><?= !empty($r['jam_absen']) ? date('H:i', strtotime($r['jam_absen'])) : '-' ?></td>
                        <td><span class="badge <?= $badge ?>"><?= esc($r['status_label'] ?? '-') ?></span></td>
                        <td><?= ($r['menit_keterlambatan'] ?? 0) > 0 ? $r['menit_keterlambatan'].' mnt' : '-' ?></td>
                        <td><?= $iconMetode ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
<div class="empty-state">
    <i class="fas fa-clock"></i>
    <p>Belum ada riwayat absensi</p>
</div>
<?php endif; ?>

<div style="margin-top:16px;">
    <a href="/guru/siswa" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Siswa
    </a>
</div>