<div class="page-title">
    <i class="fas fa-qrcode" style="color:#2563EB;margin-right:8px;"></i>QR Code Siswa
</div>
<div class="page-subtitle">
    <?= esc($siswa['nama_lengkap']) ?> — NIS: <?= esc($siswa['nis']) ?>
</div>

<!-- Tombol Navigasi -->
<div style="display:flex;gap:8px;margin-bottom:20px;">
    <a href="/admin/siswa/detail/<?= $siswa['id'] ?>" class="btn btn-outline btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali ke Detail
    </a>
    <a href="/admin/siswa" class="btn btn-outline btn-sm">
        <i class="fas fa-users"></i> Daftar Siswa
    </a>
</div>

<div class="card" style="max-width:450px;margin:0 auto;">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-qrcode"></i> QR Code Pribadi
        </div>
    </div>
    <div class="card-body text-center" style="padding:30px;">
        <!-- Canvas QR -->
        <div style="background:white;border:3px solid #E5E7EB;border-radius:16px;padding:24px;display:inline-block;margin-bottom:16px;transition:border-color 0.3s;" id="qrBorder">
            <canvas id="qrCanvas" width="300" height="300"></canvas>
        </div>
        
        <!-- Info Siswa -->
        <h4 style="margin-bottom:4px;"><?= esc($siswa['nama_lengkap']) ?></h4>
        <p style="color:#6B7280;margin-bottom:8px;">
            NIS: <?= esc($siswa['nis']) ?> 
            <?php if (!empty($siswa['nisn'])): ?>| NISN: <?= esc($siswa['nisn']) ?><?php endif; ?>
        </p>
        <p><code style="font-size:11px;word-break:break-all;"><?= esc($siswa['qr_code']) ?></code></p>
        
        <!-- Pilih Warna -->
        <div style="display:flex;gap:8px;justify-content:center;align-items:center;margin-bottom:12px;flex-wrap:wrap;">
            <span style="font-size:12px;color:#6B7280;">🎨 Warna:</span>
            <button class="btn btn-sm" style="background:#000000;color:white;min-width:30px;height:30px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 1px #000;" onclick="regenerateQR('#000000')" title="Hitam"></button>
            <button class="btn btn-sm" style="background:#2563EB;color:white;min-width:30px;height:30px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 1px #2563EB;" onclick="regenerateQR('#2563EB')" title="Biru"></button>
            <button class="btn btn-sm" style="background:#059669;color:white;min-width:30px;height:30px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 1px #059669;" onclick="regenerateQR('#059669')" title="Hijau"></button>
            <button class="btn btn-sm" style="background:#7C3AED;color:white;min-width:30px;height:30px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 1px #7C3AED;" onclick="regenerateQR('#7C3AED')" title="Ungu"></button>
            <button class="btn btn-sm" style="background:#DC2626;color:white;min-width:30px;height:30px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 1px #DC2626;" onclick="regenerateQR('#DC2626')" title="Merah"></button>
            <input type="color" value="#000000" onchange="regenerateQR(this.value)" style="width:30px;height:30px;border:none;cursor:pointer;border-radius:50%;" title="Custom">
        </div>
        
        <!-- Tombol Aksi -->
        <div style="display:flex;gap:8px;justify-content:center;margin-top:12px;flex-wrap:wrap;">
            <button class="btn btn-primary btn-sm" onclick="downloadQR()">
                <i class="fas fa-download"></i> Download PNG
            </button>
            <button class="btn btn-outline btn-sm" onclick="printQR()">
                <i class="fas fa-print"></i> Cetak
            </button>
        </div>
        
        <!-- Info -->
        <div class="alert alert-info" style="margin-top:16px;text-align:left;">
            <i class="fas fa-info-circle"></i>
            <strong>Tips:</strong> QR Code ini digunakan untuk absensi. Siswa dapat menunjukkan QR ini ke guru untuk di-scan.
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
var qrData = '<?= esc($siswa['qr_code'], 'js') ?>';
var currentLogo = null;

// Generate QR pertama kali
generateQR('#000000');

function regenerateQR(color) {
    // Update border color
    document.getElementById('qrBorder').style.borderColor = color;
    generateQR(color);
}

function generateQR(color) {
    var qrCanvas = document.getElementById('qrCanvas');
    var ctx = qrCanvas.getContext('2d');
    
    ctx.clearRect(0, 0, 300, 300);
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, 300, 300);
    
    var tempDiv = document.createElement('div');
    tempDiv.style.display = 'none';
    document.body.appendChild(tempDiv);
    
    var qrCode = new QRCode(tempDiv, {
        text: qrData,
        width: 300,
        height: 300,
        colorDark: color,
        colorLight: '#FFFFFF',
        correctLevel: QRCode.CorrectLevel.H
    });
    
    setTimeout(function() {
        var qrImg = tempDiv.querySelector('img') || tempDiv.querySelector('canvas');
        
        if (qrImg) {
            ctx.drawImage(qrImg, 0, 0, 300, 300);
        }
        
        if (currentLogo) {
            drawLogoOnQR(ctx, currentLogo, color);
        } else {
            loadAndDrawLogo(ctx, color);
        }
        
        document.body.removeChild(tempDiv);
    }, 500);
}

function loadAndDrawLogo(ctx, color) {
    var logoImg = new Image();
    logoImg.crossOrigin = 'anonymous';
    
    tryLoadLogo(0, logoImg, ctx, color);
}

function tryLoadLogo(index, logoImg, ctx, color) {
    var logoPaths = [
        '<?= base_url('uploads/logo/logo.jpeg') ?>',
        '<?= base_url('uploads/logo/logo.png') ?>',
        '<?= base_url('uploads/logo/logo.jpg') ?>'
    ];
    
    if (index >= logoPaths.length) {
        drawFallbackLogo(ctx, color);
        return;
    }
    
    logoImg.onload = function() {
        currentLogo = logoImg;
        drawLogoOnQR(ctx, logoImg, color);
    };
    logoImg.onerror = function() {
        tryLoadLogo(index + 1, logoImg, ctx, color);
    };
    
    logoImg.src = logoPaths[index];
}

function drawLogoOnQR(ctx, logoImg, color) {
    var qrSize = 300;
    var logoSize = Math.round(qrSize * 0.22);
    var centerX = qrSize / 2;
    var centerY = qrSize / 2;
    
    // Background putih bulat
    ctx.fillStyle = '#FFFFFF';
    ctx.beginPath();
    ctx.arc(centerX, centerY, logoSize/2 + 8, 0, 2 * Math.PI);
    ctx.fill();
    
    // Border warna QR
    ctx.fillStyle = color;
    ctx.beginPath();
    ctx.arc(centerX, centerY, logoSize/2 + 4, 0, 2 * Math.PI);
    ctx.fill();
    
    // Inner putih
    ctx.fillStyle = '#FFFFFF';
    ctx.beginPath();
    ctx.arc(centerX, centerY, logoSize/2, 0, 2 * Math.PI);
    ctx.fill();
    
    // Clip lingkaran
    ctx.save();
    ctx.beginPath();
    ctx.arc(centerX, centerY, logoSize/2, 0, 2 * Math.PI);
    ctx.clip();
    
    // Gambar logo (cover)
    var imgRatio = logoImg.width / logoImg.height;
    var drawWidth, drawHeight, drawX, drawY;
    
    if (imgRatio > 1) {
        drawHeight = logoSize;
        drawWidth = logoSize * imgRatio;
        drawX = centerX - drawWidth / 2;
        drawY = centerY - drawHeight / 2;
    } else {
        drawWidth = logoSize;
        drawHeight = logoSize / imgRatio;
        drawX = centerX - drawWidth / 2;
        drawY = centerY - drawHeight / 2;
    }
    
    ctx.drawImage(logoImg, drawX, drawY, drawWidth, drawHeight);
    ctx.restore();
}

function drawFallbackLogo(ctx, color) {
    var centerX = 150;
    var centerY = 150;
    var logoSize = 66;
    
    ctx.fillStyle = '#FFFFFF';
    ctx.beginPath();
    ctx.arc(centerX, centerY, logoSize/2 + 8, 0, 2 * Math.PI);
    ctx.fill();
    
    ctx.fillStyle = color;
    ctx.beginPath();
    ctx.arc(centerX, centerY, logoSize/2 + 4, 0, 2 * Math.PI);
    ctx.fill();
    
    ctx.fillStyle = '#FFFFFF';
    ctx.beginPath();
    ctx.arc(centerX, centerY, logoSize/2, 0, 2 * Math.PI);
    ctx.fill();
    
    ctx.fillStyle = color;
    ctx.font = 'bold 36px Arial';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText('<?= esc(strtoupper(substr($siswa['nama_lengkap'] ?? 'S', 0, 1))) ?>', centerX, centerY);
}

function downloadQR() {
    var link = document.createElement('a');
    link.download = 'QR-<?= esc($siswa['nis']) ?>-<?= esc(preg_replace('/[^a-zA-Z0-9]/', '-', $siswa['nama_lengkap'] ?? 'siswa')) ?>.png';
    link.href = document.getElementById('qrCanvas').toDataURL('image/png');
    link.click();
}

function printQR() {
    var canvas = document.getElementById('qrCanvas');
    var dataUrl = canvas.toDataURL('image/png');
    var win = window.open('', '_blank');
    win.document.write('<!DOCTYPE html><html><head><title>QR Code - <?= esc($siswa['nama_lengkap'], 'js') ?></title>');
    win.document.write('<style>body{display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;}</style></head><body>');
    win.document.write('<div style="text-align:center;"><img src="' + dataUrl + '" style="width:350px;"><br>');
    win.document.write('<h3><?= esc($siswa['nama_lengkap'], 'js') ?></h3>');
    win.document.write('<p>NIS: <?= esc($siswa['nis'], 'js') ?></p></div>');
    win.document.write('<script>window.onload=function(){window.print();}<\/script></body></html>');
    win.document.close();
}
</script>