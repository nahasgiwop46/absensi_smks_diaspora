<div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-bottom:16px;">
    <div>
        <h2 class="page-title" style="margin-bottom:4px;">
            <i class="fas fa-clipboard-list" style="color:#059669;"></i> Absensi Manual
        </h2>
        <p class="page-subtitle">Input absensi siswa</p>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="/guru/qrcode" class="btn btn-outline btn-sm">
            <i class="fas fa-qrcode"></i> Generate QR
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-body" style="padding:12px 16px;">
        <form method="get" class="toolbar" style="gap:8px;">
            <input type="date" name="tanggal" class="form-input" 
                   value="<?= esc($filter_tanggal ?? date('Y-m-d')) ?>" 
                   onchange="this.form.submit()" style="min-width:140px;padding:8px;">
            <select name="kelas" class="form-select" onchange="this.form.submit()" style="min-width:160px;padding:8px;">
                <option value="">Semua Kelas</option>
                <?php foreach ($kelas as $k): ?>
                <option value="<?= $k['id'] ?>" <?= ($filter_kelas ?? '') == $k['id'] ? 'selected' : '' ?>>
                    <?= esc($k['tingkat'] . ' ' . ($k['jurusan_singkatan'] ?? '') . ' ' . $k['rombel']) ?>
                </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
</div>

<!-- Stats Compact -->
<div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
    <div style="flex:1;min-width:80px;background:#F0FDF4;border-radius:10px;padding:12px;text-align:center;">
        <div style="font-size:20px;font-weight:700;color:#059669;"><?= esc($total_hadir ?? 0) ?></div>
        <div style="font-size:11px;color:#6B7280;">Hadir</div>
    </div>
    <div style="flex:1;min-width:80px;background:#FFFBEB;border-radius:10px;padding:12px;text-align:center;">
        <div style="font-size:20px;font-weight:700;color:#D97706;"><?= esc($total_izin ?? 0) ?></div>
        <div style="font-size:11px;color:#6B7280;">Izin</div>
    </div>
    <div style="flex:1;min-width:80px;background:#EFF6FF;border-radius:10px;padding:12px;text-align:center;">
        <div style="font-size:20px;font-weight:700;color:#2563EB;"><?= esc($total_sakit ?? 0) ?></div>
        <div style="font-size:11px;color:#6B7280;">Sakit</div>
    </div>
    <div style="flex:1;min-width:80px;background:#FEF2F2;border-radius:10px;padding:12px;text-align:center;">
        <div style="font-size:20px;font-weight:700;color:#DC2626;"><?= esc($total_alpa ?? 0) ?></div>
        <div style="font-size:11px;color:#6B7280;">Alpa</div>
    </div>
    <div style="flex:1;min-width:80px;background:#FFF7ED;border-radius:10px;padding:12px;text-align:center;">
        <div style="font-size:20px;font-weight:700;color:#9A3412;"><?= esc($total_terlambat ?? 0) ?></div>
        <div style="font-size:11px;color:#6B7280;">Telat</div>
    </div>
</div>

<!-- Sesi Aktif + Scanner -->
<?php if (!empty($sesi_aktif)): ?>
<div style="background:#EFF6FF;border:1px solid #BFDBFE;border-radius:10px;padding:10px 14px;margin-bottom:12px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
    <div style="font-size:13px;">
        🔵 <strong><?= esc($sesi_aktif['mapel_nama'] ?? '') ?></strong> | ⏱️ <strong id="timerSisa">--:--</strong>
    </div>
    <div style="display:flex;gap:6px;">
        <button class="btn btn-primary btn-sm" onclick="toggleScanner()" style="padding:6px 12px;font-size:12px;">
            <i class="fas fa-camera"></i> <span id="btnScannerText">Scan QR</span>
        </button>
        <a href="/guru/qrcode/tampilkan/<?= $sesi_aktif['token'] ?>" class="btn btn-outline btn-sm" target="_blank" style="padding:6px 12px;font-size:12px;">
            <i class="fas fa-eye"></i>
        </a>
    </div>
</div>

<div id="scannerArea" style="display:none;text-align:center;margin-bottom:12px;">
    <div class="card"><div class="card-body" style="padding:16px;">
        <div id="reader" style="width:250px;height:250px;margin:0 auto;border-radius:8px;overflow:hidden;"></div>
        <p style="font-size:13px;margin-top:8px;color:#2563EB;" id="scanStatus"></p>
        <div id="scanResult" style="display:none;margin-top:12px;"></div>
    </div></div>
</div>
<?php endif; ?>

<!-- Daftar Siswa -->
<?php if (!empty($siswa_list)): ?>
<div class="card" style="margin-bottom:16px;">
    <div class="card-header" style="padding:10px 16px;">
        <div class="card-title" style="font-size:15px;"><i class="fas fa-users"></i> Daftar Siswa (<?= count($siswa_list) ?>)</div>
    </div>
    <div class="card-body" style="padding:0;">
        <form action="<?= base_url('guru/absensi/storeBatch') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="tanggal" value="<?= esc($filter_tanggal ?? date('Y-m-d')) ?>">
            <div class="table-responsive">
                <table class="table" style="font-size:13px;">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th width="55">QR</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <?php if (empty($filter_kelas)): ?><th>Kelas</th><?php endif; ?>
                            <th width="120">Status</th>
                            <th width="150">Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($siswa_list as $s): ?>
                        <tr id="row-<?= $s['id'] ?>" style="<?= $s['sudah_absen'] ? 'background:#F0FDF4;' : '' ?>">
                            <td><?= $no++ ?></td>
                            <td style="text-align:center;cursor:pointer;padding:4px;" onclick="showQR('<?= $s['id'] ?>', '<?= esc($s['nis'], 'js') ?>', '<?= esc($s['nama_lengkap'], 'js') ?>')">
                                <div id="qr-<?= $s['id'] ?>" style="display:inline-block;"></div>
                            </td>
                            <td><?= esc($s['nis']) ?></td>
                            <td>
                                <strong><?= esc($s['nama_lengkap']) ?></strong>
                                <?php if ($s['sudah_absen']): ?>
                                    <br><small style="color:#059669;">✅ <?= esc($s['status_label']) ?> | <?= !empty($s['jam_absen']) ? date('H:i', strtotime($s['jam_absen'])) : '-' ?></small>
                                <?php endif; ?>
                            </td>
                            <?php if (empty($filter_kelas)): ?>
                            <td><span class="badge badge-info" style="font-size:11px;"><?= esc($s['nama_kelas'] ?? '-') ?></span></td>
                            <?php endif; ?>
                            <td>
                                <input type="hidden" name="siswa_ids[]" value="<?= $s['id'] ?>">
                                <select name="status_per_siswa[<?= $s['id'] ?>]" class="form-select form-select-sm status-select" style="width:110px;padding:4px;font-size:12px;" <?= $s['sudah_absen'] ? 'disabled' : '' ?>>
                                    <?php foreach ($status_absensi as $st): ?>
                                    <option value="<?= $st['id'] ?>" <?= ($st['kode'] ?? '') == 'hadir' ? 'selected' : '' ?>><?= esc($st['label']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <div class="btn-group" style="gap:2px;">
                                    <button type="button" class="btn btn-sm btn-success" onclick="setStatus(<?= $s['id'] ?>, 1)" <?= $s['sudah_absen'] ? 'disabled' : '' ?> style="padding:3px 7px;font-size:11px;"><i class="fas fa-check"></i></button>
                                    <button type="button" class="btn btn-sm" style="background:#F59E0B;color:white;padding:3px 7px;font-size:11px;" onclick="setStatus(<?= $s['id'] ?>, 2)" <?= $s['sudah_absen'] ? 'disabled' : '' ?>><i class="fas fa-envelope"></i></button>
                                    <button type="button" class="btn btn-sm" style="background:#3B82F6;color:white;padding:3px 7px;font-size:11px;" onclick="setStatus(<?= $s['id'] ?>, 3)" <?= $s['sudah_absen'] ? 'disabled' : '' ?>><i class="fas fa-heart"></i></button>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="setStatus(<?= $s['id'] ?>, 4)" <?= $s['sudah_absen'] ? 'disabled' : '' ?> style="padding:3px 7px;font-size:11px;"><i class="fas fa-times"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 16px;border-top:1px solid #E5E7EB;">
                <button type="button" class="btn btn-sm btn-outline" onclick="tandaiSemuaHadir()" style="font-size:12px;">
                    <i class="fas fa-check-double"></i> Tandai Semua Hadir
                </button>
                <button type="submit" class="btn btn-primary btn-sm" style="font-size:12px;">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
<?php else: ?>
<div class="card"><div class="card-body" style="padding:30px;text-align:center;"><i class="fas fa-users" style="font-size:40px;color:#D1D5DB;"></i><p style="color:#9CA3AF;">Pilih kelas untuk melihat daftar siswa</p></div></div>
<?php endif; ?>

<!-- Riwayat -->
<?php if (!empty($absensi)): ?>
<div class="card">
    <div class="card-header" style="padding:10px 16px;">
        <div class="card-title" style="font-size:15px;"><i class="fas fa-list"></i> Riwayat (<?= count($absensi) ?>)</div>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-responsive">
            <table class="table" style="font-size:12px;">
                <thead><tr><th>No</th><th>NIS</th><th>Nama</th><th>Mapel</th><th>Jam</th><th>Status</th><th>Telat</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php $no = 1; foreach ($absensi as $a): 
                        $label = strtolower($a['status_label'] ?? '');
                        $badge = match($label) {'hadir'=>'badge-success','izin'=>'badge-warning','sakit'=>'badge-info','alpa'=>'badge-danger',default=>'badge-info'};
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($a['nis'] ?? '-') ?></td>
                        <td><strong><?= esc($a['nama_lengkap'] ?? '-') ?></strong></td>
                        <td><?= esc($a['mapel'] ?? '-') ?></td>
                        <td><?= isset($a['jam_absen']) ? date('H:i', strtotime($a['jam_absen'])) : '-' ?></td>
                        <td><span class="badge <?= $badge ?>" style="font-size:11px;"><?= esc($a['status_label'] ?? '-') ?></span></td>
                        <td><?= ($a['menit_keterlambatan'] ?? 0) > 0 ? esc($a['menit_keterlambatan']).'m' : '-' ?></td>
                        <td>
                            <a href="/guru/absensi/edit/<?= $a['id'] ?>" class="btn-icon edit" style="font-size:12px;"><i class="fas fa-edit"></i></a>
                            <a href="/guru/absensi/delete/<?= $a['id'] ?>" class="btn-icon delete" style="font-size:12px;" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                        </td>
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
<script src="https://unpkg.com/html5-qrcode"></script>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>


<script>
// Preload logo
var logoImage = new Image();
logoImage.src = '/uploads/logo/logo.jpeg';

let modalQRData = '';
let modalQRLogo = null;

// QR kecil di tabel
document.addEventListener('DOMContentLoaded', function() {
    <?php foreach ($siswa_list as $s): ?>
    new QRCode(document.getElementById('qr-<?= $s['id'] ?>'), {
        text: '<?= esc($s['nis'], 'js') ?>',
        width: 45, height: 45,
        colorDark: '#000', colorLight: '#fff'
    });
    <?php endforeach; ?>
});

// Modal QR
function showQR(id, nis, nama) {
    document.getElementById('qrModal').style.display = 'flex';
    document.getElementById('qrModalName').textContent = nama;
    document.getElementById('qrModalNis').textContent = 'NIS: ' + nis;
    document.getElementById('qrModalCanvas').innerHTML = '';
    new QRCode(document.getElementById('qrModalCanvas'), { 
        text: nis, width: 220, height: 220, 
        colorDark: '#000000', colorLight: '#FFFFFF' 
    });
    modalQRData = nis;
    modalQRLogo = logoImage;
}

function closeQR() { 
    document.getElementById('qrModal').style.display = 'none'; 
}

function downloadModalQR() { 
    var qrCanvas = document.querySelector('#qrModalCanvas canvas'); 
    if (!qrCanvas) return;
    
    var w = 340, h = 480;
    var finalCanvas = document.createElement('canvas');
    finalCanvas.width = w;
    finalCanvas.height = h;
    var ctx = finalCanvas.getContext('2d');
    
    // ===== BACKGROUND =====
    // Background putih
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, w, h);
    
    // Border luar
    ctx.strokeStyle = '#2563EB';
    ctx.lineWidth = 3;
    ctx.strokeRect(5, 5, w - 10, h - 10);
    
    var logoImg = new Image();
    logoImg.crossOrigin = 'anonymous';
    logoImg.onload = function() {
        // ===== HEADER BACKGROUND =====
        ctx.fillStyle = '#2563EB';
        ctx.beginPath();
        ctx.roundRect(5, 5, w - 10, 110, [10, 10, 0, 0]);
        ctx.fill();
        
        // Logo sekolah
        var headerLogoSize = 40;
        ctx.fillStyle = '#FFFFFF';
        ctx.beginPath();
        ctx.arc(w/2, 40, headerLogoSize/2 + 6, 0, Math.PI * 2);
        ctx.fill();
        ctx.save();
        ctx.beginPath();
        ctx.arc(w/2, 40, headerLogoSize/2, 0, Math.PI * 2);
        ctx.clip();
        ctx.drawImage(logoImg, w/2 - headerLogoSize/2, 40 - headerLogoSize/2, headerLogoSize, headerLogoSize);
        ctx.restore();
        
        // Nama Sekolah
        ctx.fillStyle = '#FFFFFF';
        ctx.font = 'bold 15px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('SMKS DIASPORA', w/2, 85);
        
        ctx.font = '11px Arial';
        ctx.fillText('Kotaraja Dalam', w/2, 100);
        
        // ===== INFO SISWA =====
        ctx.fillStyle = '#111827';
        ctx.font = 'bold 20px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(document.getElementById('qrModalName').textContent, w/2, 145);
        
        ctx.fillStyle = '#6B7280';
        ctx.font = '13px Arial';
        ctx.fillText(document.getElementById('qrModalNis').textContent, w/2, 165);
        
        // Garis pemisah
        ctx.strokeStyle = '#E5E7EB';
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(40, 178);
        ctx.lineTo(w - 40, 178);
        ctx.stroke();
        
        // ===== QR CODE =====
        var qrSize = 190;
        var qrX = (w - qrSize) / 2;
        var qrY = 192;
        
        // Background QR
        ctx.fillStyle = '#FFFFFF';
        ctx.shadowColor = 'rgba(0,0,0,0.1)';
        ctx.shadowBlur = 10;
        ctx.fillRect(qrX - 14, qrY - 14, qrSize + 28, qrSize + 28);
        ctx.shadowColor = 'transparent';
        
        // Border QR
        ctx.strokeStyle = '#111827';
        ctx.lineWidth = 2;
        ctx.strokeRect(qrX - 14, qrY - 14, qrSize + 28, qrSize + 28);
        
        // QR Code
        ctx.drawImage(qrCanvas, qrX, qrY, qrSize, qrSize);
        
        // Logo di tengah QR
        var logoSize = 44;
        var lx = qrX + (qrSize - logoSize) / 2;
        var ly = qrY + (qrSize - logoSize) / 2;
        ctx.fillStyle = '#FFFFFF';
        ctx.beginPath();
        ctx.arc(lx + logoSize/2, ly + logoSize/2, logoSize/2 + 4, 0, Math.PI * 2);
        ctx.fill();
        ctx.strokeStyle = '#111827';
        ctx.lineWidth = 1.5;
        ctx.stroke();
        ctx.save();
        ctx.beginPath();
        ctx.arc(lx + logoSize/2, ly + logoSize/2, logoSize/2, 0, Math.PI * 2);
        ctx.clip();
        ctx.drawImage(logoImg, lx, ly, logoSize, logoSize);
        ctx.restore();
        
        // ===== FOOTER =====
        ctx.fillStyle = '#2563EB';
        ctx.font = 'bold 10px Arial';
        ctx.textAlign = 'center';
        ctx.fillText('KARTU ABSENSI SISWA', w/2, h - 55);
        
        ctx.fillStyle = '#9CA3AF';
        ctx.font = '9px Arial';
        ctx.fillText('Scan QR ini untuk absensi', w/2, h - 38);
        ctx.fillText('© ' + new Date().getFullYear() + ' SMKS DIASPORA', w/2, h - 22);
        
        // Download
        var link = document.createElement('a'); 
        link.download = 'QR-' + modalQRData + '.png'; 
        link.href = finalCanvas.toDataURL('image/png'); 
        link.click(); 
    };
    logoImg.onerror = function() {
        var link = document.createElement('a'); 
        link.download = 'QR-' + modalQRData + '.png'; 
        link.href = finalCanvas.toDataURL('image/png'); 
        link.click(); 
    };
    logoImg.src = '/uploads/logo/logo.jpeg';
}


// Scanner
let html5QrCode = null;
function toggleScanner() {
    var a = document.getElementById('scannerArea'), t = document.getElementById('btnScannerText');
    if (a.style.display === 'none' || !a.style.display) { a.style.display = 'block'; t.textContent = 'Tutup'; startScanner(); }
    else stopScanner();
}
function startScanner() {
    document.getElementById('scanStatus').textContent = 'Arahkan kamera...'; 
    document.getElementById('scanStatus').style.color = '#2563EB';
    html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 250 } },
        function(text) { 
            html5QrCode.stop(); 
            document.getElementById('scanStatus').textContent = '✅ Terdeteksi!';
            fetch('/guru/scan/proses', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ qr_data: text }) })
            .then(r => r.json()).then(d => {
                var e = document.getElementById('scanResult'); e.style.display = 'block';
                if (d.success) { e.innerHTML = `<div style="background:#D1FAE5;border-radius:10px;padding:12px;">✅ ${d.siswa.nama} - ${d.siswa.status}</div>`; setTimeout(() => location.reload(), 1500); }
                else { e.innerHTML = `<div style="background:#FEE2E2;border-radius:10px;padding:12px;color:#DC2626;">❌ ${d.message}</div>`; setTimeout(() => { e.style.display = 'none'; startScanner(); }, 2000); }
            });
        }, function() {}
    ).catch(() => { document.getElementById('scanStatus').textContent = '❌ Gagal kamera'; document.getElementById('scanStatus').style.color = '#DC2626'; });
}
function stopScanner() { if (html5QrCode) html5QrCode.stop(); document.getElementById('scannerArea').style.display = 'none'; document.getElementById('btnScannerText').textContent = 'Scan QR'; }

// Countdown
<?php if (!empty($sesi_aktif)): ?>
(function() { var e = new Date('<?= $sesi_aktif['expired_at'] ?>'.replace(' ', 'T') + '+09:00').getTime(); function u() { var d = e - Date.now(); if (d <= 0) { document.getElementById('timerSisa').textContent = 'HABIS'; return; } var m = Math.floor(d / 60000), s = Math.floor((d % 60000) / 1000); document.getElementById('timerSisa').textContent = m + ':' + String(s).padStart(2, '0'); requestAnimationFrame(u); } u(); })();
<?php endif; ?>

function tandaiSemuaHadir() { document.querySelectorAll('.status-select:not([disabled])').forEach(s => s.value = '1'); }
function setStatus(id, val) { var r = document.getElementById('row-' + id); if (r) { r.querySelector('.status-select').value = val; r.style.background = '#ECFDF5'; setTimeout(() => r.style.background = '', 1000); } }
</script>