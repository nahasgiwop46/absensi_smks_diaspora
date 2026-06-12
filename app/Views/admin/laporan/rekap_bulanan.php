<div class="page-title">📅 Rekap Bulanan</div>
<div class="page-subtitle">Rekap absensi per bulan</div>

<a href="/admin/laporan" class="btn btn-outline" style="margin-bottom:20px;">
    <i class="fas fa-arrow-left"></i> Kembali
</a>

<!-- Filter -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-body">
        <form method="get" class="toolbar">
            <div class="form-group" style="margin-bottom:0;min-width:150px;">
                <label class="form-label">📅 Bulan</label>
                <select name="bulan" class="form-select">
                    <?php 
                    $bulanList = [
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                        '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                        '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                        '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
                    ];
                    foreach ($bulanList as $val => $label): ?>
                    <option value="<?= $val ?>" <?= ($bulan ?? date('m')) == $val ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;min-width:120px;">
                <label class="form-label">📆 Tahun</label>
                <select name="tahun" class="form-select">
                    <?php for ($y = date('Y') - 2; $y <= date('Y') + 1; $y++): ?>
                    <option value="<?= $y ?>" <?= ($tahun ?? date('Y')) == $y ? 'selected' : '' ?>>
                        <?= $y ?>
                    </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group" style="margin-bottom:0;min-width:200px;">
                <label class="form-label">🏫 Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($kelas ?? [] as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= ($selected_kelas ?? '') == $k['id'] ? 'selected' : '' ?>>
                        <?= esc($k['tingkat']) ?> <?= esc($k['jurusan_singkatan'] ?? '') ?> <?= esc($k['rombel']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
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

<!-- Hasil -->
<?php if (!empty($statistik)): ?>
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-chart-pie"></i> 
            Statistik Bulan <?= $bulanList[$bulan] ?? '' ?> <?= $tahun ?? '' ?>
        </div>
    </div>
    <div class="card-body">
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Total</th>
                        <th>✅ Hadir</th>
                        <th>📝 Izin</th>
                        <th>🏥 Sakit</th>
                        <th>❌ Alpa</th>
                        <th>📈 % Hadir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($statistik as $s): 
                        $total = ($s['hadir'] ?? 0) + ($s['izin'] ?? 0) + ($s['sakit'] ?? 0) + ($s['alpa'] ?? 0);
                        $persen = $total > 0 ? round(($s['hadir'] / $total) * 100, 1) : 0;
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= esc($s['nama_kelas'] ?? '-') ?></strong></td>
                        <td><?= $total ?></td>
                        <td><?= esc($s['hadir'] ?? 0) ?></td>
                        <td><?= esc($s['izin'] ?? 0) ?></td>
                        <td><?= esc($s['sakit'] ?? 0) ?></td>
                        <td><?= esc($s['alpa'] ?? 0) ?></td>
                        <td>
                            <span class="badge <?= $persen >= 90 ? 'badge-success' : ($persen >= 75 ? 'badge-warning' : 'badge-danger') ?>">
                                <?= $persen ?>%
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php else: ?>
<div class="card">
    <div class="card-body" style="text-align:center;padding:60px;color:#9CA3AF;">
        <i class="fas fa-chart-pie" style="font-size:64px;display:block;margin-bottom:16px;"></i>
        <p style="font-size:18px;">Silakan pilih filter untuk melihat rekap bulanan</p>
    </div>
</div>
<?php endif; ?>