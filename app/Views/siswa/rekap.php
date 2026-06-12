<div class="page-title">📊 Rekap Kehadiran</div>
<div class="page-subtitle">Statistik kehadiran bulan <?= esc($bulanNama ?? '') ?> <?= esc($tahun ?? '') ?></div>

<!-- Filter -->
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;">
    <select class="filter-select" onchange="window.location='?bulan='+this.value+'&tahun=<?= esc($tahun ?? date('Y')) ?>'" style="min-width:150px;">
        <?php 
        $bulanList = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April', '05' => 'Mei', '06' => 'Juni',
            '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];
        foreach ($bulanList as $val => $label): ?>
            <option value="<?= $val ?>" <?= ($bulan ?? date('m')) == $val ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-value text-success"><?= esc($rekap['hadir'] ?? 0) ?></div>
        <div class="stat-label">✅ Hadir</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-file-alt"></i></div>
        <div class="stat-value text-warning"><?= esc($rekap['izin'] ?? 0) ?></div>
        <div class="stat-label">📝 Izin</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-heart"></i></div>
        <div class="stat-value text-info"><?= esc($rekap['sakit'] ?? 0) ?></div>
        <div class="stat-label">🏥 Sakit</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
        <div class="stat-value text-danger"><?= esc($rekap['alpa'] ?? 0) ?></div>
        <div class="stat-label">❌ Alpa</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FED7AA;color:#9A3412;"><i class="fas fa-clock"></i></div>
        <div class="stat-value" style="color:#9A3412;"><?= esc($rekap['terlambat'] ?? 0) ?></div>
        <div class="stat-label">⏰ Terlambat</div>
    </div>
</div>

<!-- Progress -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-chart-pie"></i> Persentase Kehadiran</div>
    </div>
    <div class="card-body text-center">
        <?php 
        $persen = $rekap['persentase'] ?? 0;
        $warna = $persen >= 90 ? '#059669' : ($persen >= 75 ? '#D97706' : '#DC2626');
        ?>
        <div style="font-size:64px;font-weight:700;color:<?= $warna ?>;"><?= $persen ?>%</div>
        <div style="height:12px;background:#E5E7EB;border-radius:6px;overflow:hidden;max-width:350px;margin:12px auto;">
            <div style="height:100%;width:<?= $persen ?>%;background:<?= $warna ?>;border-radius:6px;transition:width 0.5s;"></div>
        </div>
        <p style="color:#6B7280;">
            <?= esc($rekap['hadir'] ?? 0) ?> hadir dari <?= esc($rekap['total_hari'] ?? 0) ?> total
        </p>
    </div>
</div>