<div class="page-title">📡 Monitoring Absensi Real-Time</div>
<div class="page-subtitle">Pantau sesi absensi yang sedang berlangsung</div>

<!-- Filter Kelas -->
<div style="margin-bottom:20px;">
    <form method="get" action="<?= base_url('admin/absensi/monitoring') ?>" style="display:flex;gap:10px;align-items:center;">
        <select name="kelas_id" class="filter-select" style="min-width:200px;" onchange="this.form.submit()">
            <option value="">-- Semua Kelas --</option>
            <?php foreach ($kelas as $k): ?>
                <option value="<?= $k['id'] ?>" <?= ($selected_kelas ?? '') == $k['id'] ? 'selected' : '' ?>>
                    <?= esc($k['tingkat']) ?> <?= esc($k['jurusan_singkatan'] ?? '') ?> <?= esc($k['rombel']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<?php if (empty($sesi_aktif)): ?>
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-clock" style="font-size:48px;color:#D1D5DB;"></i>
                <h4 style="color:#6B7280;">Tidak Ada Sesi Aktif</h4>
                <p style="color:#9CA3AF;">Belum ada sesi absensi yang sedang berlangsung</p>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kelas</th>
                    <th>Mapel</th>
                    <th>Guru</th>
                    <th>Mulai</th>
                    <th>Selesai</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($sesi_aktif as $s): 
                    $expired = strtotime($s['expired_at'] ?? '');
                    $sekarang = time();
                    $masihAktif = $expired > $sekarang;
                    
                    // Hitung sisa waktu
                    $sisaDetik = $expired - $sekarang;
                    $sisaMenit = $sisaDetik > 0 ? floor($sisaDetik / 60) : 0;
                    $sisaDetik2 = $sisaDetik > 0 ? $sisaDetik % 60 : 0;
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><strong><?= esc($s['nama_kelas'] ?? '-') ?></strong></td>
                    <td><?= esc($s['mapel_nama'] ?? '-') ?></td>
                    <td><?= esc($s['guru_nama'] ?? '-') ?></td>
                    <td><?= !empty($s['jam_mulai_absen']) ? date('H:i', strtotime($s['jam_mulai_absen'])) : '-' ?></td>
                    <td><?= !empty($s['jam_selesai_absen']) ? date('H:i', strtotime($s['jam_selesai_absen'])) : '-' ?></td>
                    <td><?= esc($s['durasi_menit'] ?? 0) ?> menit</td>
                    <td>
                        <?php if ($masihAktif): ?>
                            <span class="badge badge-success">🟢 Aktif</span>
                            <br>
                            <small style="color:#6B7280;">Sisa: <?= $sisaMenit ?>:<?= str_pad($sisaDetik2, 2, '0', STR_PAD_LEFT) ?></small>
                        <?php else: ?>
                            <span class="badge badge-danger">🔴 Expired</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= base_url('admin/absensi/rekapSesi/' . $s['id']) ?>" 
                           class="btn btn-sm btn-outline">
                            <i class="fas fa-eye"></i> Lihat
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- Auto Refresh -->
<script>
// Auto refresh setiap 30 detik
setTimeout(function() {
    location.reload();
}, 30000);
</script>