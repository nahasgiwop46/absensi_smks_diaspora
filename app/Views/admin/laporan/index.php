<div class="page-title">📊 Laporan Absensi</div>
<div class="page-subtitle">Rekap dan statistik kehadiran</div>

<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;">
    <a href="/admin/laporan/rekap-harian" class="btn btn-outline">
        <i class="fas fa-calendar-day"></i> Rekap Harian
    </a>
    <a href="/admin/laporan/rekap-bulanan" class="btn btn-outline">
        <i class="fas fa-calendar-alt"></i> Rekap Bulanan
    </a>
    <a href="/admin/laporan/rekap-sesi" class="btn btn-outline">
        <i class="fas fa-qrcode"></i> Rekap Sesi
    </a>
    <a href="/admin/laporan/exportPdf" class="btn btn-outline">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
</div>

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

<!-- Statistik Harian -->
<div class="stats-grid" style="margin-bottom:20px;">
    <?php 
    $totalHadir = 0; $totalIzin = 0; $totalSakit = 0; $totalAlpa = 0; $totalTerlambat = 0; $totalSiswa = 0;
    if (!empty($rekap)) {
        foreach ($rekap as $r) {
            $totalHadir += $r['hadir'] ?? 0;
            $totalIzin += $r['izin'] ?? 0;
            $totalSakit += $r['sakit'] ?? 0;
            $totalAlpa += $r['alpa'] ?? 0;
            $totalTerlambat += $r['terlambat'] ?? 0;
            $totalSiswa += $r['total_siswa'] ?? 0;
        }
    }
    $persenHadir = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100, 1) : 0;
    ?>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#10B981;"><?= $totalHadir ?></div>
        <div class="stat-label">✅ Hadir</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#3B82F6;"><?= $totalIzin ?></div>
        <div class="stat-label">📝 Izin</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#8B5CF6;"><?= $totalSakit ?></div>
        <div class="stat-label">🏥 Sakit</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#EF4444;"><?= $totalAlpa ?></div>
        <div class="stat-label">❌ Alpa</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#F59E0B;"><?= $totalTerlambat ?></div>
        <div class="stat-label">⏰ Terlambat</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#2563EB;"><?= $persenHadir ?>%</div>
        <div class="stat-label">📈 Kehadiran</div>
    </div>
</div>

<!-- Rekap Table -->
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-clipboard-list"></i> 
            Rekap Absensi: <?= formatTanggal($tanggal ?? date('Y-m-d'), 'l, d F Y') ?>
        </div>
        <span style="font-size:14px;color:#6B7280;"><?= count($rekap ?? []) ?> kelas</span>
    </div>
    <div class="card-body">
        <?php if (empty($rekap)): ?>
            <div class="empty-state">
                <i class="fas fa-file-alt" style="font-size:64px;"></i>
                <p style="font-size:18px;">Belum ada data rekap</p>
                <p style="color:#9CA3AF;">Pilih tanggal lain atau pastikan absensi sudah diinput</p>
            </div>
        <?php else: ?>
        <div class="table">
            <table style="min-width:800px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Total</th>
                        <th>✅ Hadir</th>
                        <th>📝 Izin</th>
                        <th>🏥 Sakit</th>
                        <th>❌ Alpa</th>
                        <th>⏰ Terlambat</th>
                        <th>📈 % Hadir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($rekap as $r): 
                        $persen = ($r['total_siswa'] ?? 0) > 0 ? round(($r['hadir'] / $r['total_siswa']) * 100, 1) : 0;
                        $warna = $persen >= 90 ? 'badge-success' : ($persen >= 75 ? 'badge-warning' : 'badge-danger');
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= esc($r['nama_kelas'] ?? '-') ?></strong></td>
                        <td><?= esc($r['total_siswa'] ?? 0) ?></td>
                        <td style="color:#059669;font-weight:700;"><?= esc($r['hadir'] ?? 0) ?></td>
                        <td><?= esc($r['izin'] ?? 0) ?></td>
                        <td><?= esc($r['sakit'] ?? 0) ?></td>
                        <td style="color:#DC2626;"><?= esc($r['alpa'] ?? 0) ?></td>
                        <td><?= esc($r['terlambat'] ?? 0) ?></td>
                        <td>
                            <span class="badge <?= $warna ?>"><?= $persen ?>%</span>
                        </td>
                        <td>
                            <a href="/admin/absensi?kelas_id=<?= $r['kelas_id'] ?? '' ?>&tanggal=<?= $tanggal ?>" 
                               class="btn btn-sm btn-outline" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>