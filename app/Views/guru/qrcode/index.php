<div class="page-title">
    <i class="fas fa-qrcode" style="color:#2563EB;margin-right:8px;"></i>Generate QR Code
</div>
<div class="page-subtitle">Buat sesi absensi per mata pelajaran</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
    
    <!-- Form Generate -->
    <div class="card">
        <div class="card-header">
            <span class="card-title"><i class="fas fa-plus-circle" style="color:#2563EB;"></i> Buat Sesi Absensi</span>
        </div>
        <div class="card-body">
            <form action="<?= base_url('guru/qrcode/generate') ?>" method="post">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label class="form-label"><i class="fas fa-calendar-alt"></i> Jadwal <span style="color:#EF4444;">*</span></label>
                    <select name="jadwal_id" class="form-select" required>
                        <option value="">-- Pilih Jadwal --</option>
                        <?php if (!empty($jadwal_list)): ?>
                            <?php foreach ($jadwal_list as $j): ?>
                            <option value="<?= $j['id'] ?>"><?= esc(ucfirst($j['hari'])) ?> | <?= esc($j['jam_mulai']) ?>-<?= esc($j['jam_selesai']) ?> | <?= esc($j['mapel']) ?> | <?= esc($j['nama_kelas']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-clock"></i> Durasi (menit)</label>
                        <input type="number" name="menit_berlaku" class="form-input" value="15" min="1" max="180" required>
                        <small style="color:#6B7280;">1-180 menit (default 15)</small>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-display"></i> Tampilan</label>
                        <select name="metode_tampilan" class="form-select">
                            <option value="layar_laptop">💻 Layar Laptop</option>
                            <option value="proyektor">📽️ Proyektor</option>
                            <option value="share_link">🔗 Share Link</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;"><i class="fas fa-qrcode"></i> Buat Sesi Absensi</button>
            </form>
        </div>
    </div>
    
    <!-- Sesi Aktif + QR Result -->
    <div>
        <!-- Sesi Aktif -->
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <span class="card-title"><i class="fas fa-broadcast-tower" style="color:#2563EB;"></i> Sesi Aktif</span>
                <span class="badge badge-info"><?= count($qr_aktif ?? []) ?> sesi</span>
            </div>
            <div class="card-body" style="padding:0;">
                <?php if (!empty($qr_aktif)): ?>
                <div class="table"><table>
                    <thead><tr><th>Mapel</th><th>Kelas</th><th>Sisa</th><th style="text-align:center;">Aksi</th></tr></thead>
                    <tbody>
                        <?php foreach ($qr_aktif as $qr): 
                            $sisa = strtotime($qr['expired_at'] ?? '') - time();
                            $m = $sisa > 0 ? floor($sisa/60) : 0; $s = $sisa > 0 ? $sisa%60 : 0;
                            $badgeSisa = $sisa > 180 ? 'badge-success' : ($sisa > 60 ? 'badge-warning' : 'badge-danger');
                        ?>
                        <tr>
                            <td><?= esc($qr['mapel_nama'] ?? $qr['mapel'] ?? '-') ?></td>
                            <td><?= esc($qr['nama_kelas'] ?? '-') ?></td>
                            <td><span class="badge <?= $badgeSisa ?>"><?= $sisa > 0 ? $m.':'.str_pad($s,2,'0',STR_PAD_LEFT) : 'EXPIRED' ?></span></td>
                            <td>
                                <div class="btn-group" style="justify-content:center;">
                                    <a href="/guru/qrcode/tampilkan/<?= $qr['token'] ?>" class="btn-icon view" title="Tampilkan" target="_blank"><i class="fas fa-eye"></i></a>
                                    <a href="<?= base_url('guru/qrcode/nonaktifkan/'.$qr['id']) ?>" class="btn-icon delete" title="Hentikan" onclick="return confirm('Hentikan sesi ini?')"><i class="fas fa-stop-circle"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table></div>
                <?php else: ?>
                <div class="empty-state"><i class="fas fa-qrcode"></i><p>Belum ada sesi aktif</p></div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- QR Result (setelah generate) -->
        <?php if (session()->getFlashdata('token')): ?>
        <div class="card" style="border:2px solid #10B981;">
            <div class="card-header" style="background:#ECFDF5;">
                <span class="card-title" style="color:#059669;"><i class="fas fa-check-circle"></i> QR Berhasil Dibuat!</span>
                <span class="badge badge-success" id="countdown">--:--</span>
            </div>
            <div class="card-body" style="text-align:center;">
                <!-- ✅ QR + Logo -->
                <div style="position:relative;display:inline-block;">
                    <div id="qrcode"></div>
                    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:44px;height:44px;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.3);overflow:hidden;">
                        <img src="/uploads/logo/logo.jpeg" style="width:34px;height:34px;object-fit:contain;" onerror="this.style.display='none';this.parentElement.innerHTML='<i class=\'fas fa-school\' style=\'font-size:20px;color:#000;\'></i>';">
                    </div>
                </div>
                <p style="color:#6B7280;margin-top:8px;font-size:12px;">⏰ Berlaku sampai: <?= date('H:i:s', strtotime(session()->getFlashdata('expired_at') ?? '')) ?> WIT</p>
                <?php if (session()->getFlashdata('kode_cadangan')): ?>
                <div style="background:#FEF3C7;border-radius:8px;padding:8px;margin-top:8px;">
                    <p style="font-size:11px;color:#92400E;margin:0;">Kode Cadangan:</p>
                    <strong style="font-size:24px;letter-spacing:6px;color:#92400E;"><?= esc(session()->getFlashdata('kode_cadangan')) ?></strong>
                </div>
                <?php endif; ?>
                <div style="display:flex;gap:8px;justify-content:center;margin-top:12px;">
                    <button class="btn btn-outline btn-sm" onclick="saveQRImage()"><i class="fas fa-download"></i> Simpan QR</button>
                    <a href="/guru/qrcode/tampilkan/<?= session()->getFlashdata('token') ?>" class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-eye"></i> Tampilkan</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (session()->getFlashdata('token')): ?>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
new QRCode(document.getElementById('qrcode'), {
    text: '<?= esc(session()->getFlashdata('token'), 'js') ?>',
    width: 200, height: 200,
    colorDark: '#000000', colorLight: '#FFFFFF',
    correctLevel: QRCode.CorrectLevel.H
});

var expiredAt = new Date('<?= session()->getFlashdata('expired_at') ?>'.replace(' ', 'T') + '+09:00').getTime();
var countdownEl = document.getElementById('countdown');

function updateCountdown() {
    var diff = expiredAt - Date.now();
    if (diff <= 0) { countdownEl.textContent = 'EXPIRED'; countdownEl.className = 'badge badge-danger'; setTimeout(() => location.reload(), 3000); return; }
    var m = Math.floor(diff / 60000), s = Math.floor((diff % 60000) / 1000);
    countdownEl.textContent = m + ':' + String(s).padStart(2, '0');
    countdownEl.className = diff < 60000 ? 'badge badge-danger' : (diff < 180000 ? 'badge badge-warning' : 'badge badge-success');
    requestAnimationFrame(updateCountdown);
}
updateCountdown();

function saveQRImage() {
    var qrCanvas = document.querySelector('#qrcode canvas');
    if (!qrCanvas) return;
    
    // Buat canvas gabungan (QR + Logo)
    var finalCanvas = document.createElement('canvas');
    finalCanvas.width = qrCanvas.width;
    finalCanvas.height = qrCanvas.height;
    var ctx = finalCanvas.getContext('2d');
    
    // Gambar QR
    ctx.drawImage(qrCanvas, 0, 0);
    
    // Gambar logo di tengah
    var logoImg = new Image();
    logoImg.crossOrigin = 'anonymous';
    logoImg.onload = function() {
        var logoSize = 44;
        var x = (finalCanvas.width - logoSize) / 2;
        var y = (finalCanvas.height - logoSize) / 2;
        
        // Background putih bulat
        ctx.fillStyle = '#FFFFFF';
        ctx.beginPath();
        ctx.arc(x + logoSize/2, y + logoSize/2, logoSize/2 + 4, 0, Math.PI * 2);
        ctx.fill();
        
        // Border
        ctx.strokeStyle = '#000000';
        ctx.lineWidth = 1;
        ctx.stroke();
        
        // Gambar logo
        ctx.save();
        ctx.beginPath();
        ctx.arc(x + logoSize/2, y + logoSize/2, logoSize/2, 0, Math.PI * 2);
        ctx.clip();
        ctx.drawImage(logoImg, x, y, logoSize, logoSize);
        ctx.restore();
        
        // Download
        var link = document.createElement('a');
        link.download = 'QR-Absensi-<?= date('Ymd-His') ?>.png';
        link.href = finalCanvas.toDataURL('image/png');
        link.click();
    };
    logoImg.onerror = function() {
        // Kalau logo gagal, download QR polos
        var link = document.createElement('a');
        link.download = 'QR-Absensi-<?= date('Ymd-His') ?>.png';
        link.href = qrCanvas.toDataURL('image/png');
        link.click();
    };
    logoImg.src = '/uploads/logo/logo.jpeg';
}
</script>
<?php endif; ?>