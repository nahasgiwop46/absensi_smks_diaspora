<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
    <div>
        <h2 class="page-title" style="margin-bottom:4px;">
            <i class="fas fa-chart-bar" style="color:#059669;"></i> Rekap Absensi
        </h2>
        <p class="page-subtitle">Rekap absensi per kelas — <?= date('d F Y', strtotime($tanggal ?? date('Y-m-d'))) ?></p>
    </div>
    <a href="/guru/absensi" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-body" style="padding:12px 16px;">
        <form method="get" class="toolbar" style="gap:8px;">
            <input type="date" name="tanggal" class="form-input" 
                   value="<?= esc($tanggal ?? date('Y-m-d')) ?>" 
                   onchange="this.form.submit()" style="min-width:150px;padding:8px;">
            <button type="submit" class="btn btn-primary btn-sm" style="padding:8px 16px;">
                <i class="fas fa-search"></i> Tampilkan
            </button>
        </form>
    </div>
</div>

<?php if (!empty($rekap)): ?>
    <?php foreach ($rekap as $r): 
        // Hitung statistik per kelas
        $totalHadir = 0; $totalIzin = 0; $totalSakit = 0; $totalAlpa = 0; $totalTerlambat = 0;
        if (!empty($r['absensi'])) {
            foreach ($r['absensi'] as $a) {
                $label = strtolower($a['status_label'] ?? '');
                if ($label === 'hadir') $totalHadir++;
                elseif ($label === 'izin') $totalIzin++;
                elseif ($label === 'sakit') $totalSakit++;
                elseif ($label === 'alpa') $totalAlpa++;
                if (($a['menit_keterlambatan'] ?? 0) > 0) $totalTerlambat++;
            }
        }
        $totalSiswa = count($r['absensi'] ?? []);
        $persen = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100, 1) : 0;
    ?>
    <div class="card" style="margin-bottom:16px;">
        <div class="card-header" style="padding:10px 16px;">
            <div class="card-title" style="font-size:15px;">
                <i class="fas fa-door-open" style="color:#059669;"></i>
                <?= esc($r['kelas']['tingkat'] ?? '') ?> <?= esc($r['kelas']['rombel'] ?? '') ?>
            </div>
            <div style="display:flex;gap:8px;align-items:center;">
                <span style="font-size:12px;color:#059669;">✅ <?= $totalHadir ?></span>
                <span style="font-size:12px;color:#D97706;">📝 <?= $totalIzin ?></span>
                <span style="font-size:12px;color:#2563EB;">🏥 <?= $totalSakit ?></span>
                <span style="font-size:12px;color:#DC2626;">❌ <?= $totalAlpa ?></span>
                <span class="badge badge-info"><?= $persen ?>%</span>
            </div>
        </div>
        <div class="card-body" style="padding:0;">
            <?php if (!empty($r['absensi'])): ?>
            <div class="table-responsive">
                <table class="table" style="font-size:13px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Mapel</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th>Terlambat</th>
                            <th>Metode</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($r['absensi'] as $a): 
                            $label = strtolower($a['status_label'] ?? '');
                            $badge = match($label) {
                                'hadir' => 'badge-success', 'izin' => 'badge-warning',
                                'sakit' => 'badge-info', 'alpa' => 'badge-danger',
                                default => 'badge-info'
                            };
                            $metode = $a['metode_absensi'] ?? '';
                            $iconMetode = strpos($metode, 'qr') !== false ? '📱 QR' : (strpos($metode, 'manual') !== false ? '✍️ Manual' : $metode);
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($a['nis'] ?? '-') ?></td>
                            <td><strong><?= esc($a['nama_lengkap'] ?? '-') ?></strong></td>
                            <td><?= esc($a['mapel'] ?? '-') ?></td>
                            <td><?= isset($a['jam_absen']) ? date('H:i', strtotime($a['jam_absen'])) : '-' ?></td>
                            <td><span class="badge <?= $badge ?>"><?= esc($a['status_label'] ?? '-') ?></span></td>
                            <td><?= ($a['menit_keterlambatan'] ?? 0) > 0 ? esc($a['menit_keterlambatan']).' mnt' : '-' ?></td>
                            <td><?= $iconMetode ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div style="padding:20px;text-align:center;color:#9CA3AF;">Belum ada data absensi</div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="card">
        <div class="card-body" style="padding:40px;text-align:center;">
            <i class="fas fa-chart-bar" style="font-size:48px;color:#D1D5DB;"></i>
            <p style="color:#9CA3AF;margin-top:12px;">Tidak ada data rekap untuk tanggal ini</p>
        </div>
    </div>
<?php endif; ?>