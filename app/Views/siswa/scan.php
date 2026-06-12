<div class="page-title">📷 Scan QR Guru</div>
<div class="page-subtitle">Arahkan kamera ke QR Code guru</div>

<div class="card" style="text-align:center;">
    <div class="card-body" style="padding:30px;">
        
        <!-- Scanner -->
        <div id="reader" style="width:300px;height:300px;margin:0 auto;border-radius:12px;overflow:hidden;"></div>
        <p style="font-size:16px;margin-top:12px;color:#2563EB;font-weight:600;" id="scanStatus">Arahkan kamera ke QR Code</p>
        
        <!-- Hasil Scan -->
        <div id="scanResult" style="display:none;margin-top:16px;"></div>
        
        <a href="/siswa/absensi/scan" class="btn btn-outline btn-sm mt-3">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrCode = null;

document.addEventListener('DOMContentLoaded', startScanner);

function startScanner() {
    html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 250, height: 250 } },
        function(decodedText) {
            // STOP scanner setelah dapat QR
            html5QrCode.stop().then(() => {
                document.getElementById('scanStatus').textContent = '✅ QR Terdeteksi!';
                prosesAbsen(decodedText);
            });
        },
        function() {}
    ).catch(() => {
        document.getElementById('scanStatus').textContent = '❌ Gagal kamera. Pastikan izin kamera diaktifkan.';
    });
}

function prosesAbsen(token) {
    document.getElementById('scanStatus').textContent = '⏳ Memproses...';
    
    // Ambil sesi aktif
    fetch('/siswa/absensi/getStatusHariIni')
    .then(r => r.json())
    .then(response => {
        var data = response.data || response;
        
        if (!data.ada_sesi) {
            tampilError('Tidak ada sesi absen aktif');
            return;
        }
        
        // Cek sudah absen
        if (data.sudah_absen) {
            tampilError('Anda sudah absen di sesi ini');
            return;
        }
        
        // ✅ TAMPILKAN PROFIL SISWA + TOMBOL ABSEN
        var el = document.getElementById('scanResult');
        el.style.display = 'block';
        el.innerHTML = `
            <div style="background:white;border:2px solid #059669;border-radius:16px;padding:24px;text-align:center;">
                <div style="width:80px;height:80px;background:#D1FAE5;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;">
                    <i class="fas fa-user-check" style="font-size:32px;color:#059669;"></i>
                </div>
                <h3 style="margin:4px 0;font-size:20px;" id="profilNama">Memuat...</h3>
                <p style="color:#6B7280;margin:2px 0;" id="profilNis">NIS: -</p>
                <p style="color:#6B7280;font-size:13px;margin:2px 0;">Sesi Absen Aktif</p>
                
                <button class="btn btn-primary btn-lg" 
                        style="background:#059669;border:none;margin-top:16px;width:100%;padding:14px;border-radius:10px;font-size:16px;font-weight:600;"
                        onclick="konfirmasiAbsen(${data.sesi.id})">
                    ✅ Absen Sekarang
                </button>
            </div>`;
        
        // Update profil dari API verifikasi
        fetch('/siswa/absensi/verifikasi', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ token: token })
        })
        .then(r => r.json())
        .then(verif => {
            if (verif.data && verif.data.siswa) {
                document.getElementById('profilNama').textContent = verif.data.siswa.nama_lengkap;
                document.getElementById('profilNis').textContent = 'NIS: ' + verif.data.siswa.nis;
            }
        });
    });
}

function konfirmasiAbsen(sessionId) {
    document.getElementById('scanResult').innerHTML = '<div style="text-align:center;padding:30px;"><p style="color:#2563EB;font-weight:600;">⏳ Menyimpan absensi...</p></div>';
    
    fetch('/siswa/absensi/konfirmasi', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify({ qr_session_id: sessionId, device_info: navigator.userAgent })
    })
    .then(r => r.json())
    .then(response => {
        var el = document.getElementById('scanResult');
        if (response.success) {
            el.innerHTML = `
                <div style="background:#D1FAE5;border-radius:16px;padding:24px;text-align:center;">
                    <div style="font-size:64px;">✅</div>
                    <h3 style="color:#059669;">Absen Berhasil!</h3>
                    <p style="color:#6B7280;">${response.data?.waktu_absen || ''} | ${response.data?.status || 'Hadir'}</p>
                    <a href="/siswa" class="btn btn-outline btn-sm mt-2">Dashboard</a>
                </div>`;
        } else {
            tampilError(response.message || 'Gagal absen');
        }
    });
}

function tampilError(msg) {
    document.getElementById('scanResult').style.display = 'block';
    document.getElementById('scanResult').innerHTML = `
        <div style="background:#FEE2E2;border-radius:12px;padding:16px;text-align:center;">
            <p style="color:#DC2626;margin:0;">❌ ${msg}</p>
            <button class="btn btn-outline btn-sm mt-2" onclick="location.reload()">Coba Lagi</button>
        </div>`;
}
</script>