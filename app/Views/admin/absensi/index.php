<div class="page-title">Rekap Absensi</div>
<div class="page-subtitle">Data absensi per tanggal</div>

<div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;">
    <a href="/admin/absensi/create" class="btn btn-primary"><i class="fas fa-plus"></i> Input Manual</a>
    <a href="/admin/absensi/belum-absen" class="btn btn-outline"><i class="fas fa-exclamation-triangle"></i> Belum Absen</a>
    <a href="/admin/absensi/monitoring" class="btn btn-outline"><i class="fas fa-eye"></i> Monitoring Sesi</a>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-body">
        <form method="get" class="toolbar">
            <div style="min-width:160px;">
                <label class="form-label" style="margin-bottom:2px;">Tanggal</label>
                <input type="date" name="tanggal" class="form-input" value="<?= esc($tanggal ?? date('Y-m-d')) ?>">
            </div>
            <div style="min-width:220px;">
                <label class="form-label" style="margin-bottom:2px;">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua Kelas</option>
                    <?php foreach ($kelas as $k): ?>
                    <option value="<?= $k['id'] ?>" <?= ($selected_kelas ?? '') == $k['id'] ? 'selected' : '' ?>>
                        <?= esc($k['tingkat'] . ' ' . ($k['jurusan_singkatan'] ?? '') . ' ' . $k['rombel']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="form-label" style="margin-bottom:2px;">&nbsp;</label>
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan</button>
            </div>
        </form>
    </div>
</div>

<!-- Statistik -->
<div class="stats-grid">
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#10B981;"><?= esc($total_hadir ?? 0) ?></div>
        <div class="stat-label">Hadir</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#3B82F6;"><?= esc($total_izin ?? 0) ?></div>
        <div class="stat-label">Izin</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#8B5CF6;"><?= esc($total_sakit ?? 0) ?></div>
        <div class="stat-label">Sakit</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#EF4444;"><?= esc($total_alpa ?? 0) ?></div>
        <div class="stat-label">Alpa</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#F59E0B;"><?= esc($total_terlambat ?? 0) ?></div>
        <div class="stat-label">Terlambat</div>
    </div>
    <div class="stat-card" style="text-align:center;">
        <div class="stat-value" style="color:#2563EB;"><?= esc($persentase_hadir ?? 0) ?>%</div>
        <div class="stat-label">Kehadiran</div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
       <div class="card-title">
    Data Absensi: 
    <?php 
    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    $tgl = !empty($tanggal) ? $tanggal : date('Y-m-d');
    $time = strtotime($tgl);
    echo $hari[date('w', $time)] . ', ' . date('d', $time) . ' ' . $bulan[date('n', $time) - 1] . ' ' . date('Y', $time);
    ?>
</div>
        <span style="font-size:13px;color:#6B7280;"><?= count($absensi ?? []) ?> data</span>
    </div>
    <div class="card-body">
        <?php if (empty($absensi)): ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-list"></i>
                <p>Belum ada data absensi</p>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table" style="min-width:800px;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Status</th>
                        <th>Mapel</th>
                        <th>Jam</th>
                        <th>Terlambat</th>
                        <th>Metode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($absensi as $a): 
                        $label = strtolower($a['status_label'] ?? '');
                        $badge = match($label) {
                            'hadir' => 'badge-success',
                            'izin' => 'badge-info',
                            'sakit' => 'badge-purple',
                            'alpa' => 'badge-danger',
                            'terlambat' => 'badge-warning',
                            default => 'badge-secondary'
                        };
                        $metode = $a['metode_absensi'] ?? '';
                        $iconMetode = match($metode) {
                            'scan_qr_siswa' => '📱 QR Siswa',
                            'scan_qr_guru' => '📷 QR Guru',
                            'manual_guru' => '✍️ Manual',
                            default => esc($metode)
                        };
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($a['nis'] ?? '-') ?></td>
                        <td><strong><?= esc($a['nama_lengkap'] ?? '-') ?></strong></td>
                        <td><span class="badge <?= $badge ?>"><?= esc($a['status_label'] ?? '-') ?></span></td>
                        <td><?= esc($a['mapel'] ?? '-') ?></td>
                        <td><?= !empty($a['jam_absen']) ? date('H:i', strtotime($a['jam_absen'])) : '-' ?></td>
                        <td><?= ($a['menit_keterlambatan'] ?? 0) > 0 ? esc($a['menit_keterlambatan']) . ' mnt' : '-' ?></td>
                        <td><span class="badge badge-light"><?= $iconMetode ?></span></td>
                        <td>
                            <a href="/admin/absensi/detail/<?= $a['id'] ?>" class="btn btn-sm btn-outline" title="Detail">
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