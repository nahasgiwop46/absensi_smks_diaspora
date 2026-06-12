<div class="page-title">📋 Riwayat Absensi</div>
<div class="page-subtitle">Riwayat kehadiran Anda</div>

<!-- Filter Bulan & Tahun -->
<div style="display:flex;gap:10px;margin-bottom:20px;flex-wrap:wrap;">
    <select class="filter-select" onchange="window.location='?bulan='+this.value+'&tahun=<?= esc($tahun ?? date('Y')) ?>'" style="min-width:150px;">
        <?php 
        $bulanList = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni',
                      '07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
        foreach ($bulanList as $val => $label): ?>
            <option value="<?= $val ?>" <?= ($bulan ?? date('m')) == $val ? 'selected' : '' ?>><?= $label ?></option>
        <?php endforeach; ?>
    </select>
    <select class="filter-select" onchange="window.location='?bulan=<?= esc($bulan ?? date('m')) ?>&tahun='+this.value" style="min-width:120px;">
        <?php for ($y = date('Y')-1; $y <= date('Y')+1; $y++): ?>
            <option value="<?= $y ?>" <?= ($tahun ?? date('Y')) == $y ? 'selected' : '' ?>><?= $y ?></option>
        <?php endfor; ?>
    </select>
</div>

<!-- Tabel Riwayat -->
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-clock"></i> Riwayat Absensi</div>
        <span class="badge badge-info"><?= count($riwayat ?? []) ?> catatan</span>
    </div>
    <div class="card-body" style="padding:0;">
        <?php if (!empty($riwayat)): ?>
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
                                'hadir' => 'badge-success',
                                'izin' => 'badge-warning',
                                'sakit' => 'badge-info',
                                'alpa' => 'badge-danger',
                                'terlambat' => 'badge-warning',
                                default => 'badge-secondary'
                            };
                            $metode = $r['metode_absensi'] ?? '';
                            $iconMetode = match($metode) {
                                'scan_qr_siswa' => '📱 QR Siswa',
                                'scan_qr_guru' => '📷 QR Guru',
                                'manual_guru' => '✍️ Manual',
                                'whatsapp' => '💬 WhatsApp',
                                default => esc($metode)
                            };
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php 
                                $tgl = $r['tanggal'] ?? '';
                                if ($tgl) {
                                    $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                                    $bln = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                                    $ts = strtotime($tgl);
                                    echo $hari[date('w',$ts)] . ', ' . date('d',$ts) . ' ' . $bln[date('n',$ts)-1] . ' ' . date('Y',$ts);
                                }
                                ?>
                            </td>
                            <td><strong><?= esc($r['mapel'] ?? '-') ?></strong></td>
                            <td><?= !empty($r['jam_absen']) ? date('H:i', strtotime($r['jam_absen'])) : '-' ?></td>
                            <td><span class="badge <?= $badge ?>"><?= esc($r['status_label'] ?? '-') ?></span></td>
                            <td><?= ($r['menit_keterlambatan'] ?? 0) > 0 ? esc($r['menit_keterlambatan']).' mnt' : '-' ?></td>
                            <td><?= $iconMetode ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-clock"></i>
                <p>Belum ada riwayat absensi</p>
            </div>
        <?php endif; ?>
    </div>
</div>