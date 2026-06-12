<div class="page-title">📷 Scan QR Guru</div>
<div class="page-subtitle">Scan QR Code guru atau upload gambar QR</div>

<div class="card" style="text-align:center;max-width:500px;margin:0 auto;">
    <div class="card-body" style="padding:30px;">
        
        <!-- Scanner Area -->
        <div id="reader" style="width:280px;height:280px;margin:0 auto;border-radius:12px;overflow:hidden;display:none;"></div>
        
        <!-- Placeholder Default -->
        <div id="scannerPlaceholder" style="width:280px;height:280px;margin:0 auto;border:3px dashed #D1D5DB;border-radius:16px;display:flex;align-items:center;justify-content:center;">
            <div style="text-align:center;color:#9CA3AF;">
                <i class="fas fa-camera" style="font-size:48px;display:block;margin-bottom:12px;"></i>
                <p style="font-size:14px;margin:4px 0;">Klik tombol di bawah</p>
                <p style="font-size:12px;">atau upload gambar QR</p>
            </div>
        </div>
        
        <p style="font-size:16px;margin-top:12px;font-weight:600;" id="scanStatus">
            <span style="color:#2563EB;">📷 Siap Scan</span>
        </p>
        
        <!-- Tombol Scan -->
        <button class="btn btn-primary" onclick="startScanner()" id="btnStartScan" 
                style="padding:12px 24px;font-size:14px;">
            <i class="fas fa-camera"></i> Buka Kamera
        </button>
        <button class="btn btn-outline btn-sm" onclick="stopScanner()" id="btnStopScan" style="display:none;">
            <i class="fas fa-stop"></i> Tutup Kamera
        </button>
        
        <!-- Upload Gambar QR (Fallback) -->
        <div style="margin-top:16px;padding:16px;background:#F3F4F6;border-radius:12px;">
            <p style="font-weight:600;color:#6B7280;margin-bottom:8px;font-size:13px;">
                📁 Upload Gambar QR Code:
            </p>
            <input type="file" id="qrImage" accept="image/*" onchange="scanFromImage()" 
                   style="display:block;width:100%;font-size:13px;">
            <p id="imageScanStatus" style="color:#6B7280;font-size:12px;margin-top:4px;"></p>
        </div>
        
        <!-- Hasil Scan -->
        <div id="scanResult" style="display:none;margin-top:16px;"></div>
        
        <a href="/siswa/absen" class="btn btn-outline btn-sm mt-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrCode = null;

function startScanner() {
    document.getElementById('scannerPlaceholder').style.display = 'none';
    document.getElementById('reader').style.display = 'block';
    document.getElementById('btnStartScan').style.display = 'none';
    document.getElementById('btnStopScan').style.display = 'inline-flex';
    document.getElementById('scanStatus').innerHTML = '<span style="color:#2563EB;">📷 Arahkan ke QR Code...</span>';
    
    html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        function(decodedText) {
            document.getElementById('scanStatus').innerHTML = '<span style="color:#059669;">✅ QR Terdeteksi!</span>';
            prosesAbsen(decodedText);
        },
        function() {}
    ).catch(() => {
        // ✅ Auto fallback ke upload gambar
        document.getElementById('reader').style.display = 'none';
        document.getElementById('scannerPlaceholder').innerHTML = `
            <div style="text-align:center;color:#DC2626;">
                <i class="fas fa-camera-slash" style="font-size:48px;display:block;margin-bottom:8px;"></i>
                <p style="font-size:14px;">Kamera tidak tersedia</p>
                <p style="font-size:12px;">Gunakan upload gambar di bawah ⬇️</p>
            </div>`;
        document.getElementById('scannerPlaceholder').style.display = 'flex';
        document.getElementById('btnStartScan').style.display = 'inline-flex';
        document.getElementById('btnStopScan').style.display = 'none';
        document.getElementById('scanStatus').innerHTML = '<span style="color:#DC2626;">❌ Kamera tidak bisa diakses</span>';
    });
}

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().then(resetUI).catch(resetUI);
    } else {
        resetUI();
    }
}

function resetUI() {
    document.getElementById('reader').style.display = 'none';
    document.getElementById('scannerPlaceholder').style.display = 'flex';
    document.getElementById('scannerPlaceholder').innerHTML = `
        <div style="text-align:center;color:#9CA3AF;">
            <i class="fas fa-camera" style="font-size:48px;display:block;margin-bottom:12px;"></i>
            <p style="font-size:14px;margin:4px 0;">Klik tombol di bawah</p>
            <p style="font-size:12px;">atau upload gambar QR</p>
        </div>`;
    document.getElementById('btnStartScan').style.display = 'inline-flex';
    document.getElementById('btnStopScan').style.display = 'none';
    document.getElementById('scanStatus').innerHTML = '<span style="color:#2563EB;">📷 Siap Scan</span>';
}

// ✅ Scan dari gambar
function scanFromImage() {
    var file = document.getElementById('qrImage').files[0];
    if (!file) return;
    
    document.getElementById('imageScanStatus').textContent = '⏳ Memproses gambar...';
    
    html5QrCode = new Html5Qrcode("reader");
    
    html5QrCode.scanFile(file, true)
        .then(decodedText => {
            document.getElementById('imageScanStatus').textContent = '✅ QR Terdeteksi!';
            document.getElementById('scanStatus').innerHTML = '<span style="color:#059669;">✅ QR Terdeteksi dari gambar!</span>';
            prosesAbsen(decodedText);
        })
        .catch(err => {
            document.getElementById('imageScanStatus').textContent = '❌ QR tidak ditemukan di gambar';
        });
}

function prosesAbsen(token) {
    document.getElementById('scanStatus').innerHTML = '<span style="color:#F59E0B;">⏳ Memproses...</span>';
    
    fetch('<?= base_url("siswa/scan/verifikasi") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'token=' + encodeURIComponent(token) + '&device_info=' + encodeURIComponent(navigator.userAgent)
    })
    .then(r => r.json())
    .then(response => {
        if (!response.success) {
            tampilError(response.message || 'QR tidak valid');
            return;
        }
        
        var el = document.getElementById('scanResult');
        el.style.display = 'block';
        el.innerHTML = `
            <div style="background:white;border:2px solid #059669;border-radius:16px;padding:24px;text-align:center;animation:fadeIn 0.3s;">
                <div style="font-size:48px;">👤</div>
                <h3 style="margin:4px 0;font-size:20px;">${response.data.siswa.nama_lengkap || 'Siswa'}</h3>
                <p style="color:#6B7280;margin:2px 0;">NIS: ${response.data.siswa.nis || '-'}</p>
                <p style="color:#6B7280;font-size:13px;margin:2px 0;">
                    📘 ${response.data.session.mapel_nama || '-'} | 👨‍🏫 ${response.data.session.guru_nama || '-'}
                </p>
                
                <button class="btn btn-primary btn-lg" 
                        style="background:#059669;border:none;margin-top:16px;width:100%;padding:14px;border-radius:10px;font-size:16px;font-weight:600;"
                        onclick="konfirmasiAbsen('${response.data.session.id}')">
                    ✅ Absen Sekarang
                </button>
            </div>`;
    })
    .catch(() => tampilError('Gagal koneksi ke server'));
}

function konfirmasiAbsen(sessionId) {
    document.getElementById('scanResult').innerHTML = 
        '<div style="text-align:center;padding:30px;">' +
        '<div style="width:40px;height:40px;border:4px solid #E5E7EB;border-top-color:#2563EB;border-radius:50%;animation:spin 0.8s linear infinite;margin:0 auto 12px;"></div>' +
        '<p style="color:#2563EB;font-weight:600;">⏳ Menyimpan absensi...</p></div>';
    
    fetch('<?= base_url("siswa/scan/konfirmasi") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'qr_session_id=' + sessionId + '&device_info=' + encodeURIComponent(navigator.userAgent)
    })
    .then(r => r.json())
    .then(response => {
        var el = document.getElementById('scanResult');
        if (response.success) {
            el.innerHTML = `
                <div style="background:#D1FAE5;border-radius:16px;padding:24px;text-align:center;animation:fadeIn 0.3s;">
                    <div style="font-size:64px;">✅</div>
                    <h3 style="color:#059669;">Absen Berhasil!</h3>
                    <p style="color:#6B7280;">${response.data?.waktu_absen || ''} | ${response.data?.status || 'Hadir'}</p>
                    <div style="margin-top:12px;">
                        <a href="/siswa" class="btn btn-outline btn-sm">🏠 Dashboard</a>
                    </div>
                </div>`;
        } else {
            tampilError(response.message || 'Gagal absen');
        }
    });
}

function tampilError(msg) {
    document.getElementById('scanResult').style.display = 'block';
    document.getElementById('scanResult').innerHTML = `
        <div style="background:#FEE2E2;border-radius:12px;padding:16px;text-align:center;animation:shake 0.5s;">
            <p style="color:#DC2626;margin:0;">❌ ${msg}</p>
            <button class="btn btn-outline btn-sm mt-2" onclick="location.reload()">🔄 Coba Lagi</button>
        </div>`;
}
</script>

<style>
@keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
@keyframes shake { 0%,100% { transform: translateX(0); } 25% { transform: translateX(-6px); } 75% { transform: translateX(6px); } }
@keyframes spin { to { transform: rotate(360deg); } }
</style>