<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi - <?= esc($tanggal ?? date('Y-m-d')) ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #2563EB; color: white; padding: 8px; text-align: left; }
        td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background: #f9f9f9; }
        .badge-success { color: #059669; font-weight: bold; }
        .badge-warning { color: #D97706; font-weight: bold; }
        .badge-danger { color: #DC2626; font-weight: bold; }
        .badge-info { color: #2563EB; font-weight: bold; }
        .footer { margin-top: 20px; text-align: right; font-size: 11px; color: #999; }
    </style>
</head>
<body>

    <h2>LAPORAN ABSENSI</h2>
    <p class="subtitle">
        Tanggal: 
        <?php 
        $hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $ts = strtotime($tanggal ?? date('Y-m-d'));
        echo $hari[date('w',$ts)] . ', ' . date('d',$ts) . ' ' . $bulan[date('n',$ts)-1] . ' ' . date('Y',$ts);
        ?>
    </p>

    <?php if (empty($absensi)): ?>
        <p style="text-align:center;color:#999;">Tidak ada data absensi</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Jam</th>
                    <th>Terlambat</th>
                    <th>Metode</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($absensi as $a): 
                    $label = strtolower($a['status_label'] ?? '');
                    $badge = match($label) {
                        'hadir' => 'badge-success',
                        'izin' => 'badge-warning',
                        'sakit' => 'badge-info',
                        'alpa' => 'badge-danger',
                        default => ''
                    };
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($a['nis'] ?? '-') ?></td>
                    <td><?= esc($a['nama_lengkap'] ?? '-') ?></td>
                    <td><?= esc($a['nama_kelas'] ?? '-') ?></td>
                    <td class="<?= $badge ?>"><?= esc($a['status_label'] ?? '-') ?></td>
                    <td><?= !empty($a['jam_absen']) ? date('H:i', strtotime($a['jam_absen'])) : '-' ?></td>
                    <td><?= ($a['menit_keterlambatan'] ?? 0) > 0 ? esc($a['menit_keterlambatan']).' mnt' : '-' ?></td>
                    <td><?= esc($a['metode_absensi'] ?? '-') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <div class="footer">
        Dicetak: <?= date('d/m/Y H:i:s') ?> | Sistem Absensi QR Code
    </div>

</body>
</html>