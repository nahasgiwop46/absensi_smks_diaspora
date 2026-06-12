<div class="page-title">
    <i class="fas fa-qrcode" style="color:#2563EB;margin-right:8px;"></i>Tampilkan QR Code
</div>
<div class="page-subtitle">
    Mapel: <strong><?= esc($mapel['nama'] ?? '') ?></strong> | 
    Kelas: <strong><?= esc($kelas['tingkat'] ?? '') ?> <?= esc($kelas['rombel'] ?? '') ?></strong>
</div>

<div style="text-align:center;">

    <!-- Info Sesi -->
    <div class="card" style="max-width:500px;margin:0 auto 20px;">
        <div class="card-body" style="text-align:center;">
            <div style="font-size:48px;margin-bottom:8px;">⏱️</div>
            <h3 style="color:#EF4444;font-size:28px;" id="countdown">--:--</h3>
            <p style="color:#6B7280;">Sesi akan berakhir</p>
            
            <?php if (!empty($session['kode_cadangan'])): ?>
            <div style="background:#FEF3C7;border-radius:10px;padding:12px;margin-top:12px;">
                <p style="font-size:12px;color:#92400E;margin:0;">Kode Cadangan:</p>
                <strong style="font-size:28px;letter-spacing:6px;color:#92400E;">
                    <?= esc($session['kode_cadangan']) ?>
                </strong>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- QR Code + Logo -->
    <div style="background:white;border:3px solid #2563EB;border-radius:16px;padding:32px;display:inline-block;margin-bottom:20px;position:relative;">
        <div id="qrCodeDisplay"></div>
        <!-- Logo di tengah QR -->
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);width:60px;height:60px;background:white;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,0.3);overflow:hidden;">
            <img src="/uploads/logo/logo.jpeg" style="width:48px;height:48px;object-fit:contain;" onerror="this.style.display='none';this.parentElement.innerHTML='<i class=\'fas fa-school\' style=\'font-size:28px;color:#2563EB;\'></i>';">
        </div>
    </div>

    <!-- Tombol -->
    <div style="display:flex;gap:8px;justify-content:center;">
        <a href="/guru/qrcode/tutup/<?= $session['id'] ?>" class="btn btn-outline" 
           onclick="return confirm('Tutup sesi absen?')" style="color:#EF4444;">
            <i class="fas fa-stop-circle"></i> Tutup Sesi
        </a>
        <a href="/guru/qrcode" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
<script>
new QRCode(document.getElementById('qrCodeDisplay'), {
    text: '<?= esc($session['token'], 'js') ?>',
    width: 300, height: 300,
    colorDark: '#000000', colorLight: '#FFFFFF',
    correctLevel: QRCode.CorrectLevel.H
});

var expiredAt = new Date('<?= $session['expired_at'] ?>'.replace(' ', 'T') + '+09:00').getTime();
var timer = setInterval(function() {
    var diff = expiredAt - new Date().getTime();
    if (diff <= 0) {
        document.getElementById('countdown').textContent = 'HABIS';
        document.getElementById('countdown').style.color = '#DC2626';
        clearInterval(timer);
    } else {
        var m = Math.floor(diff / 60000);
        var s = Math.floor((diff % 60000) / 1000);
        document.getElementById('countdown').textContent = m + ':' + String(s).padStart(2, '0');
        if (diff < 120000) document.getElementById('countdown').style.color = '#DC2626';
        else if (diff < 300000) document.getElementById('countdown').style.color = '#F59E0B';
    }
}, 1000);
</script>