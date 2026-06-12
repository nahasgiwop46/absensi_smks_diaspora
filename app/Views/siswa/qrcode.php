<h2 class="page-title">QR Code Pribadi</h2>
<p class="page-subtitle">QR code unik Anda untuk keperluan absensi</p>

<div style="background:linear-gradient(135deg, #7C3AED, #6D28D9);border-radius:20px;padding:32px;color:white;margin-bottom:24px;">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:center;">
        
        <!-- Info Siswa -->
        <div style="text-align:center;">
            <div style="width:80px;height:80px;background:rgba(255,255,255,0.2);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:40px;font-weight:700;margin-bottom:12px;border:3px solid rgba(255,255,255,0.3);">
                <?= esc(strtoupper(substr($siswa['nama_lengkap'] ?? 'S', 0, 1))) ?>
            </div>
            <h2 style="font-size:24px;margin-bottom:4px;"><?= esc($siswa['nama_lengkap'] ?? '') ?></h2>
            <p style="opacity:0.9;font-size:14px;">
                <?= esc(($kelasAktif['tingkat'] ?? '') . ' ' . ($kelasAktif['jurusan_singkatan'] ?? '') . ' ' . ($kelasAktif['rombel'] ?? '')) ?>
            </p>
            
            <div style="display:flex;flex-direction:column;gap:8px;margin-top:16px;text-align:left;">
                <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:12px 16px;">
                    <p style="font-size:11px;opacity:0.8;">NIS</p>
                    <p style="font-size:16px;font-weight:600;"><?= esc($siswa['nis'] ?? '-') ?></p>
                </div>
                <div style="background:rgba(255,255,255,0.15);border-radius:12px;padding:12px 16px;">
                    <p style="font-size:11px;opacity:0.8;">NISN</p>
                    <p style="font-size:16px;font-weight:600;"><?= esc($siswa['nisn'] ?? '-') ?></p>
                </div>
            </div>
        </div>

        <!-- QR Code -->
        <div style="text-align:center;">
            <?php if (!empty($siswa['qr_code'])): ?>
                <div style="background:white;border-radius:16px;padding:20px;display:inline-block;position:relative;" id="qrCodeBox">
                    <div id="siswaQRCode"></div>
                    <!-- ✅ Logo Sekolah di Tengah QR -->
                    <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:50px;height:50px;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.3);overflow:hidden;">
                        <img src="/uploads/logo/logo.jpeg" 
                             style="width:40px;height:40px;object-fit:contain;" 
                             onerror="this.style.display='none';this.parentElement.innerHTML='<i class=\'fas fa-school\' style=\'font-size:24px;color:#7C3AED;\'></i>';">
                    </div>
                </div>
                <p style="margin-top:12px;opacity:0.8;font-size:13px;">
                    📱 Tunjukkan QR ini kepada guru untuk absensi
                </p>
            <?php else: ?>
                <div style="background:rgba(255,255,255,0.15);border-radius:16px;padding:30px;text-align:center;">
                    <i class="fas fa-qrcode" style="font-size:48px;opacity:0.5;"></i>
                    <p style="margin-top:12px;opacity:0.8;">QR Code belum dibuat</p>
                    <p style="font-size:12px;opacity:0.6;">Hubungi admin untuk membuat QR Code</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($siswa['qr_code'])): ?>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:24px;flex-wrap:wrap;">
        <button class="btn" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.3);" onclick="downloadQR()">
            <i class="fas fa-download"></i> Download
        </button>
        <button class="btn" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.3);" onclick="printQR()">
            <i class="fas fa-print"></i> Cetak
        </button>
    </div>
    <?php endif; ?>
</div>

<!-- Info -->
<div style="background:#FFFBEB;border:1px solid #FDE68A;border-radius:16px;padding:20px;">
    <h3 style="font-size:16px;margin-bottom:12px;">⚠️ Penting!</h3>
    <ul style="font-size:13px;color:#92400E;padding-left:20px;line-height:1.8;">
        <li>QR code ini bersifat <strong>pribadi</strong>, jangan bagikan kepada orang lain</li>
        <li>Pastikan QR code dalam kondisi <strong>jelas</strong> saat ditampilkan</li>
        <li>QR code berisi <strong>NIS</strong> Anda untuk keperluan absensi</li>
        <li>Jika QR code belum ada, hubungi <strong>admin</strong> sekolah</li>
    </ul>
</div>

<?php if (!empty($siswa['qr_code'])): ?>
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
let qrCodeInstance = null;

document.addEventListener('DOMContentLoaded', function() {
    var qrContainer = document.getElementById('siswaQRCode');
    if (qrContainer) {
        qrCodeInstance = new QRCode(qrContainer, {
            text: '<?= esc($siswa['nis'] ?? $siswa['qr_code'] ?? '') ?>',
            width: 200, height: 200,
            colorDark: '#000000', colorLight: '#FFFFFF',
            correctLevel: QRCode.CorrectLevel.H
        });
    }
});

function downloadQR() {
    var qrCanvas = document.querySelector('#siswaQRCode canvas');
    if (!qrCanvas) return;
    
    var size = 400;
    var finalCanvas = document.createElement('canvas');
    finalCanvas.width = size;
    finalCanvas.height = size;
    var ctx = finalCanvas.getContext('2d');
    
    // Background putih
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, size, size);
    
    // QR Code
    ctx.drawImage(qrCanvas, 0, 0, size, size);
    
    // ✅ Logo di tengah
    var logoImg = new Image();
    logoImg.crossOrigin = 'anonymous';
    logoImg.onload = function() {
        var logoSize = 60;
        var lx = (size - logoSize) / 2;
        var ly = (size - logoSize) / 2;
        
        // Background putih
        ctx.fillStyle = '#FFFFFF';
        ctx.beginPath();
        ctx.arc(size/2, size/2, logoSize/2 + 4, 0, Math.PI * 2);
        ctx.fill();
        ctx.strokeStyle = '#7C3AED';
        ctx.lineWidth = 2;
        ctx.stroke();
        
        // Logo
        ctx.save();
        ctx.beginPath();
        ctx.arc(size/2, size/2, logoSize/2, 0, Math.PI * 2);
        ctx.clip();
        ctx.drawImage(logoImg, lx, ly, logoSize, logoSize);
        ctx.restore();
        
        var link = document.createElement('a');
        link.download = 'QR-<?= esc($siswa['nis'] ?? 'siswa') ?>.png';
        link.href = finalCanvas.toDataURL('image/png');
        link.click();
    };
    logoImg.onerror = function() {
        var link = document.createElement('a');
        link.download = 'QR-<?= esc($siswa['nis'] ?? 'siswa') ?>.png';
        link.href = finalCanvas.toDataURL('image/png');
        link.click();
    };
    logoImg.src = '/uploads/logo/logo.jpeg';
}

function printQR() {
    var canvas = document.querySelector('#siswaQRCode canvas');
    if (canvas) {
        var win = window.open('', '_blank', 'width=400,height=500');
        win.document.write('<html><head><title>QR Code</title>');
        win.document.write('<style>body{text-align:center;padding:20px;font-family:Arial;}img{max-width:280px;}</style>');
        win.document.write('</head><body>');
        win.document.write('<h3><?= esc($siswa['nama_lengkap'] ?? '') ?></h3>');
        win.document.write('<p>NIS: <?= esc($siswa['nis'] ?? '') ?></p>');
        win.document.write('<img src="' + canvas.toDataURL() + '">');
        win.document.write('</body></html>');
        win.document.close();
        setTimeout(function() { win.print(); }, 500);
    }
}
</script>
<?php endif; ?>