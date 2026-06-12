<div class="page-title">📊 Rekap Sesi Absen</div>
<div class="page-subtitle">
    Tanggal: 
    <?php 
    $hariList = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    $tgl = !empty($tanggal) ? $tanggal : date('Y-m-d');
    $ts = strtotime($tgl);
    echo $hariList[date('w', $ts)] . ', ' . date('d', $ts) . ' ' . $bulanList[date('n', $ts) - 1] . ' ' . date('Y', $ts);
    ?>
</div>

<a href="/admin/laporan" class="btn btn-outline" style="margin-bottom:16px;">
    <i class="fas fa-arrow-left"></i> Kembali
</a>

<!-- Filter -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-body">
        <form method="get" action="<?= base_url('admin/laporan/rekapSesi') ?>" class="toolbar">
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

<!-- Tabel Sesi -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-clock"></i> Daftar Sesi Absen</div>
        <span style="font-size:13px;color:#6B7280;"><?= count($sesi_list ?? []) ?> sesi</span>
    </div>
    <div class="card-body" style="padding:0;">
        <?php if (empty($sesi_list)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Tidak ada sesi absen pada tanggal ini</p>
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
                        <?php $no = 1; foreach ($sesi_list as $s): 
                            $expired = strtotime($s['expired_at'] ?? '');
                            $sekarang = time();
                            $masihAktif = $expired > $sekarang && ($s['is_active'] ?? 0) == 1;
                            
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
                            <td><?= esc($s['durasi_menit'] ?? 0) ?> mnt</td>
                            <td>
                                <?php if ($masihAktif): ?>
                                    <span class="badge badge-success">🟢 Aktif</span>
                                    <?php if ($sisaDetik > 0): ?>
                                        <br><small style="color:#6B7280;">Sisa: <?= $sisaMenit ?>:<?= str_pad($sisaDetik2, 2, '0', STR_PAD_LEFT) ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge badge-secondary">🔴 Selesai</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/absensi/rekapSesi/' . $s['id']) ?>" 
                                   class="btn btn-sm btn-outline">
                                    <i class="fas fa-eye"></i> Detail
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