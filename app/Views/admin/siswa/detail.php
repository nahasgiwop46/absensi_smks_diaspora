<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
    <div>
        <h2 class="page-title" style="margin-bottom:4px;">
            <i class="fas fa-user-graduate" style="color:#2563EB;margin-right:8px;"></i>Detail Siswa
        </h2>
        <p class="page-subtitle">Informasi lengkap data siswa</p>
    </div>
    <div style="display:flex;gap:8px;">
      <a href="javascript:void(0)" class="btn btn-outline btn-sm" 
   onclick="showQR('<?= esc($siswa['nis'], 'js') ?>', '<?= esc($siswa['nama_lengkap'], 'js') ?>')">
    <i class="fas fa-qrcode"></i> QR Code
</a>
        <a href="/admin/siswa/edit/<?= $siswa['id'] ?>" class="btn btn-outline btn-sm">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="/admin/siswa" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px;">
    <!-- QR Code & Foto -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-qrcode"></i> QR Code</span>
        </div>
        <div class="card-body" style="text-align:center;">
            <div style="width:100px;height:100px;background:#EFF6FF;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:48px;margin-bottom:12px;overflow:hidden;">
                <?php if (!empty($siswa['foto'])): ?>
                    <img src="<?= base_url('uploads/siswa/' . $siswa['foto']) ?>" style="width:100%;height:100%;object-fit:cover;">
                <?php else: ?>
                    <?= ($siswa['jenis_kelamin'] ?? '') == 'L' ? '👦' : '👧' ?>
                <?php endif; ?>
            </div>
            <h3 style="font-size:18px;"><?= esc($siswa['nama_lengkap'] ?? '') ?></h3>
            <p style="color:#6B7280;">NIS: <?= esc($siswa['nis'] ?? '') ?></p>
            
            <?php if (!empty($siswa['qr_code'])): ?>
                <span class="badge badge-success"><i class="fas fa-check"></i> QR Ready</span>
            <?php else: ?>
                <span class="badge badge-warning">QR Belum Dibuat</span>
            <?php endif; ?>
            
            <div style="margin-top:12px;">
               <a href="javascript:void(0)" class="btn btn-primary btn-sm" 
   onclick="showQR('<?= esc($siswa['nis'], 'js') ?>', '<?= esc($siswa['nama_lengkap'], 'js') ?>')">
    <i class="fas fa-qrcode"></i> Lihat QR
</a>
            </div>
        </div>
        
        <?php if (!empty($kelasAktif)): ?>
        <div class="card-body" style="border-top:1px solid #E5E7EB;text-align:center;">
            <span class="badge badge-info" style="font-size:13px;padding:8px 16px;">
                🏫 <?= esc($kelasAktif['nama_kelas'] ?? $kelasAktif['tingkat'] . ' ' . ($kelasAktif['jurusan_singkatan'] ?? '') . ' ' . $kelasAktif['rombel']) ?>
            </span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Informasi Siswa -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-info-circle"></i> Informasi Siswa</span>
        </div>
        <div class="card-body">
            <table style="width:100%;border-collapse:collapse;">
                <tr><td style="padding:8px 0;width:150px;font-weight:600;color:#6B7280;">NIS</td><td>: <?= esc($siswa['nis'] ?? '') ?></td></tr>
                <tr><td style="padding:8px 0;font-weight:600;color:#6B7280;">NISN</td><td>: <?= esc($siswa['nisn'] ?? '-') ?></td></tr>
                <tr><td style="padding:8px 0;font-weight:600;color:#6B7280;">Nama Lengkap</td><td>: <strong><?= esc($siswa['nama_lengkap'] ?? '') ?></strong></td></tr>
                <tr><td style="padding:8px 0;font-weight:600;color:#6B7280;">Jenis Kelamin</td><td>: <?= ($siswa['jenis_kelamin'] ?? '') == 'L' ? '👨 Laki-laki' : '👩 Perempuan' ?></td></tr>
                <tr><td style="padding:8px 0;font-weight:600;color:#6B7280;">Tempat, Tgl Lahir</td><td>: <?= esc($siswa['tempat_lahir'] ?? '-') ?>, <?= !empty($siswa['tanggal_lahir']) ? formatTanggal($siswa['tanggal_lahir'], 'd F Y') : '-' ?></td></tr>
                <tr><td style="padding:8px 0;font-weight:600;color:#6B7280;">No HP</td><td>: <?= esc($siswa['no_hp'] ?? '-') ?></td></tr>
                <tr><td style="padding:8px 0;font-weight:600;color:#6B7280;">Email</td><td>: <?= esc($siswa['email'] ?? '-') ?></td></tr>
                <tr><td style="padding:8px 0;font-weight:600;color:#6B7280;">Alamat</td><td>: <?= esc($siswa['alamat'] ?? '-') ?></td></tr>
                <tr><td style="padding:8px 0;font-weight:600;color:#6B7280;">Status</td><td>: <?= badgeAktif($siswa['is_active'] ?? 0) ?></td></tr>
            </table>
        </div>
    </div>
</div>

<?php if (!empty($riwayatKelas)): ?>
<!-- Riwayat Kelas -->
<div class="card" style="margin-top:20px;">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-history"></i> Riwayat Kelas</span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Tgl Masuk</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($riwayatKelas as $rk): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($rk['nama_kelas'] ?? '-') ?></td>
                        <td><?= esc($rk['tahun_ajaran_nama'] ?? '-') ?></td>
                        <td><?= formatTanggal($rk['tanggal_masuk'] ?? '', 'd/m/Y') ?></td>
                        <td><?= badgeAktif($rk['status'] === 'aktif' ? 1 : 0) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- MODAL QR -->
<div id="qrModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:16px;padding:24px;text-align:center;max-width:340px;width:85%;">
        <h4 style="margin:0;" id="qrModalName"></h4>
        <p style="color:#6B7280;font-size:13px;margin:4px 0 12px;" id="qrModalNis"></p>
        <div style="background:white;border:2px solid #000;border-radius:12px;padding:16px;display:inline-block;margin-bottom:12px;position:relative;">
            <div id="qrModalCanvas"></div>
            <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:50px;height:50px;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.3);overflow:hidden;">
                <img src="/uploads/logo/logo.jpeg" style="width:40px;height:40px;object-fit:contain;" onerror="this.style.display='none';this.parentElement.innerHTML='<i class=\'fas fa-school\' style=\'font-size:24px;color:#000;\'></i>';">
            </div>
        </div>
        <div style="display:flex;gap:6px;justify-content:center;">
            <button class="btn btn-primary btn-sm" onclick="downloadModalQR()"><i class="fas fa-download"></i></button>
            <button class="btn btn-outline btn-sm" onclick="printModalQR()"><i class="fas fa-print"></i></button>
            <button class="btn btn-outline btn-sm" onclick="closeQR()">Tutup</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
let modalQRData = '';
function showQR(nis, nama) {
    document.getElementById('qrModal').style.display = 'flex';
    document.getElementById('qrModalName').textContent = nama;
    document.getElementById('qrModalNis').textContent = 'NIS: ' + nis;
    document.getElementById('qrModalCanvas').innerHTML = '';
    new QRCode(document.getElementById('qrModalCanvas'), { text: nis, width: 220, height: 220, colorDark: '#000', colorLight: '#fff' });
    modalQRData = nis;
}
function closeQR() { document.getElementById('qrModal').style.display = 'none'; }
function downloadModalQR() { var c = document.querySelector('#qrModalCanvas canvas'); if (c) { var a = document.createElement('a'); a.download = 'QR-' + modalQRData + '.png'; a.href = c.toDataURL(); a.click(); } }
function printModalQR() { var c = document.querySelector('#qrModalCanvas canvas'); if (c) { var w = window.open(); w.document.write('<img src="' + c.toDataURL() + '" style="width:250px;">'); w.print(); } }
</script>