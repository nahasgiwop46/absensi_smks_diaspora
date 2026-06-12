<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
    <div>
        <div class="page-title">Dashboard Siswa</div>
        <div class="page-subtitle">
            Selamat datang, <strong><?= esc($siswa['nama_lengkap'] ?? session()->get('nama_lengkap') ?? 'Siswa') ?></strong>
            <?php if (!empty($nama_kelas)): ?>
                <span class="badge badge-purple" style="margin-left:8px;"><?= esc($nama_kelas) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <a href="/siswa/absen" class="btn btn-primary" style="padding:12px 24px;font-size:14px;">
        <i class="fas fa-camera"></i> Absen
    </a>
</div>

<!-- Status Hari Ini -->
<div style="margin-bottom:20px;">
    <?php if (!empty($absensiHariIni)): ?>
        <?php 
        $statusKode = $absensiHariIni['status_id'] ?? '';
        $statusLabel = $absensiHariIni['status'] ?? '';
        $mapel = $absensiHariIni['mapel'] ?? '-';
        $jamAbsen = isset($absensiHariIni['jam_absen']) ? substr($absensiHariIni['jam_absen'], 0, 5) : '-';
        $menitTelat = $absensiHariIni['menit_keterlambatan'] ?? 0;
        ?>
        
        <?php if ($statusKode == '1' || $statusLabel == 'Hadir'): ?>
            <div class="stat-card" style="border-left:4px solid #059669;flex-direction:row;text-align:left;gap:12px;">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-value text-success" style="font-size:20px;">Sudah Absen</div>
                    <div class="stat-label"><?= esc($mapel) ?> | Jam: <?= esc($jamAbsen) ?></div>
                </div>
            </div>
        <?php elseif ($statusKode == '5' || $statusLabel == 'Terlambat'): ?>
            <div class="stat-card" style="border-left:4px solid #D97706;flex-direction:row;text-align:left;gap:12px;">
                <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-value" style="color:#D97706;font-size:20px;">Terlambat <?= esc($menitTelat) ?> Menit</div>
                    <div class="stat-label"><?= esc($mapel) ?> | Jam: <?= esc($jamAbsen) ?></div>
                </div>
            </div>
        <?php elseif ($statusKode == '2' || $statusLabel == 'Izin'): ?>
            <div class="stat-card" style="border-left:4px solid #F59E0B;flex-direction:row;text-align:left;gap:12px;">
                <div class="stat-icon yellow"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="stat-value text-warning" style="font-size:20px;">Izin</div>
                    <div class="stat-label"><?= esc($mapel) ?></div>
                </div>
            </div>
        <?php elseif ($statusKode == '3' || $statusLabel == 'Sakit'): ?>
            <div class="stat-card" style="border-left:4px solid #3B82F6;flex-direction:row;text-align:left;gap:12px;">
                <div class="stat-icon blue"><i class="fas fa-heart"></i></div>
                <div>
                    <div class="stat-value text-info" style="font-size:20px;">Sakit</div>
                    <div class="stat-label"><?= esc($mapel) ?></div>
                </div>
            </div>
        <?php else: ?>
            <div class="stat-card" style="border-left:4px solid #E2E8F0;flex-direction:row;text-align:left;gap:12px;">
                <div class="stat-icon" style="background:#E2E8F0;color:#4A5568;"><i class="fas fa-file-text"></i></div>
                <div>
                    <div class="stat-value text-muted" style="font-size:20px;"><?= esc($statusLabel) ?></div>
                    <div class="stat-label">Tidak hadir hari ini</div>
                </div>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="stat-card" style="border-left:4px solid #7C3AED;flex-direction:row;text-align:left;gap:12px;">
            <div class="stat-icon purple"><i class="fas fa-qrcode"></i></div>
            <div>
                <div class="stat-value" style="color:#7C3AED;font-size:20px;">Belum Absen</div>
                <div class="stat-label">Klik tombol Absen untuk absensi</div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Statistik Bulanan -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-value text-success"><?= esc($rekap['hadir'] ?? 0) ?></div>
        <div class="stat-label">Hadir</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-file-alt"></i></div>
        <div class="stat-value text-warning"><?= esc($rekap['izin'] ?? 0) ?></div>
        <div class="stat-label">Izin</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#FED7AA;color:#9A3412;"><i class="fas fa-clock"></i></div>
        <div class="stat-value" style="color:#9A3412;"><?= esc($rekap['terlambat'] ?? 0) ?></div>
        <div class="stat-label">Terlambat</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="stat-value text-info"><?= esc($rekap['persentase'] ?? 0) ?>%</div>
        <div class="stat-label">Kehadiran</div>
    </div>
</div>

<!-- Progress + Riwayat -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
    <!-- Progress Kehadiran -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-chart-line"></i> Kehadiran Bulan Ini</div>
            <span style="font-weight:700;color:#7C3AED;"><?= esc($rekap['persentase'] ?? 0) ?>%</span>
        </div>
        <div class="card-body">
            <div style="height:8px;background:#E5E7EB;border-radius:4px;overflow:hidden;margin-bottom:12px;">
                <div style="height:100%;width:<?= esc($rekap['persentase'] ?? 0) ?>%;background:linear-gradient(135deg,#7C3AED,#6D28D9);border-radius:4px;"></div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                <span class="text-muted">Hadir</span><strong><?= esc($rekap['hadir'] ?? 0) ?> mapel</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                <span class="text-muted">Izin</span><strong><?= esc($rekap['izin'] ?? 0) ?> mapel</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                <span class="text-muted">Sakit</span><strong><?= esc($rekap['sakit'] ?? 0) ?> mapel</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                <span class="text-muted">Alpa</span><strong><?= esc($rekap['alpa'] ?? 0) ?> mapel</strong>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;">
                <span class="text-muted">Total</span><strong><?= esc($rekap['total_hari'] ?? 0) ?> mapel</strong>
            </div>
        </div>
    </div>

    <!-- Riwayat Terbaru -->
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-clock"></i> Riwayat Terbaru</div>
            <a href="/siswa/absensi" style="color:#7C3AED;font-size:12px;">Lihat Semua →</a>
        </div>
        <div class="card-body" style="padding:0;">
            <?php if (!empty($riwayat)): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead><tr><th>Tanggal</th><th>Mapel</th><th>Status</th></tr></thead>
                        <tbody>
                            <?php foreach ($riwayat as $r): ?>
                            <tr>
                                <td><?= esc($r['tanggal'] ?? '') ?></td>
                                <td><strong><?= esc($r['mapel'] ?? '-') ?></strong></td>
                                <td>
                                    <?php
                                    $badge = 'badge-info';
                                    $st = $r['status'] ?? $r['status_label'] ?? '';
                                    if ($st == 'Hadir') $badge = 'badge-success';
                                    elseif ($st == 'Izin') $badge = 'badge-warning';
                                    elseif ($st == 'Sakit') $badge = 'badge-info';
                                    elseif ($st == 'Terlambat') $badge = 'badge-warning';
                                    elseif ($st == 'Alpa') $badge = 'badge-danger';
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= esc($st) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state"><i class="fas fa-inbox"></i><p>Belum ada riwayat</p></div>
            <?php endif; ?>
        </div>
    </div>
</div>