<h2 class="page-title">📷 Scan QR Siswa</h2>
<p class="page-subtitle">Scan QR code siswa untuk mencatat kehadiran</p>

<div style="display:flex;gap:20px;flex-wrap:wrap;">
    
    <!-- Scanner -->
    <div style="flex:1;min-width:280px;max-width:350px;">
        <div class="card" style="text-align:center;">
            <div class="card-body" style="padding:20px;">
                
                <!-- Kamera (awalnya tersembunyi) -->
                <div id="reader" style="width:250px;height:250px;margin:0 auto;display:none;"></div>
                
                <!-- Placeholder sebelum kamera aktif -->
                <div id="scannerPlaceholder" style="width:250px;height:250px;margin:0 auto;border:3px dashed #D1D5DB;border-radius:16px;display:flex;align-items:center;justify-content:center;">
                    <div style="text-align:center;color:#9CA3AF;">
                        <i class="fas fa-camera" style="font-size:48px;display:block;margin-bottom:8px;"></i>
                        <p style="font-size:14px;">Klik tombol di bawah</p>
                    </div>
                </div>
                
                <p style="font-size:14px;margin-top:12px;font-weight:600;" id="scanStatus">📷 Siap Scan</p>
                
                <button class="btn btn-primary" onclick="startScan()" id="btnStartScan">
                    <i class="fas fa-camera"></i> Mulai Scan
                </button>
                <button class="btn btn-outline btn-sm" onclick="stopScan()" id="btnStopScan" style="display:none;">
                    <i class="fas fa-stop"></i> Tutup Kamera
                </button>
            </div>
        </div>
    </div>

    <!-- Hasil Scan -->
    <div style="flex:1;min-width:280px;">
        <div id="scanResult"></div>
        
        <!-- Riwayat -->
        <?php if (!empty($scanHistory)): ?>
        <div class="card" style="margin-top:12px;">
            <div class="card-header" style="padding:10px 16px;">
                <span style="font-size:13px;font-weight:600;">📋 Riwayat Hari Ini</span>
                <span class="badge badge-success" style="font-size:11px;"><?= count($scanHistory) ?></span>
            </div>
            <div class="card-body" style="padding:0;max-height:300px;overflow-y:auto;">
                <table class="table" style="font-size:12px;">
                    <thead><tr><th>Nama</th><th>Waktu</th><th>Status</th></tr></thead>
                    <tbody>
                        <?php foreach ($scanHistory as $item): ?>
                        <tr>
                            <td><strong><?= esc($item['nama_lengkap'] ?? '-') ?></strong></td>
                            <td><?= esc(substr($item['jam_absen'] ?? '', 0, 5)) ?></td>
                            <td>
                                <span class="badge <?= ($item['status_label'] ?? '') == 'Hadir' ? 'badge-success' : 'badge-warning' ?>" style="font-size:10px;">
                                    <?= esc($item['status_label'] ?? '-') ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-6px); }
    75% { transform: translateX(6px); }
}
</style>

<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let html5QrCode;
let isProcessing = false;

function startScan() {
    document.getElementById('scannerPlaceholder').style.display = 'none';
    document.getElementById('reader').style.display = 'block';
    document.getElementById('btnStartScan').style.display = 'none';
    document.getElementById('btnStopScan').style.display = 'inline-flex';
    document.getElementById('scanStatus').textContent = '📷 Arahkan ke QR Code...';
    document.getElementById('scanStatus').style.color = '#2563EB';
    
    html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" },
        { fps: 10, qrbox: { width: 200, height: 200 } },
        function(decodedText) {
            if (isProcessing) return;
            isProcessing = true;
            
            document.getElementById('scanStatus').textContent = '⏳ Memproses...';
            document.getElementById('scanStatus').style.color = '#F59E0B';
            
            fetch('/guru/scan/proses', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ qr_data: decodedText })
            })
            .then(res => res.json())
            .then(data => {
                var resultEl = document.getElementById('scanResult');
                
                if (data.success) {
                    resultEl.innerHTML = `
                        <div style="background:#D1FAE5;border:2px solid #059669;border-radius:12px;padding:16px;text-align:center;animation:fadeIn 0.3s;">
                            <div style="font-size:40px;">✅</div>
                            <h4 style="color:#059669;margin:4px 0;">Berhasil!</h4>
                            <p style="font-weight:700;font-size:16px;margin:4px 0;">${data.siswa.nama}</p>
                            <p style="color:#6B7280;font-size:12px;margin:2px 0;">NIS: ${data.siswa.nis}</p>
                            <p style="color:#6B7280;font-size:12px;margin:2px 0;">${data.siswa.waktu}</p>
                            <span style="display:inline-block;padding:4px 12px;border-radius:20px;font-weight:600;font-size:12px;margin-top:6px;
                                background:${data.siswa.status.includes('Terlambat') ? '#FEF3C7' : '#D1FAE5'};
                                color:${data.siswa.status.includes('Terlambat') ? '#D97706' : '#059669'};">
                                ${data.siswa.status}
                            </span>
                        </div>`;
                    document.getElementById('scanStatus').textContent = '📷 Siap Scan Lagi';
                    document.getElementById('scanStatus').style.color = '#059669';
                    
                } else if (data.message.includes('sudah absen')) {
                    resultEl.innerHTML = `
                        <div style="background:#FEF3C7;border:2px solid #F59E0B;border-radius:12px;padding:16px;text-align:center;animation:shake 0.5s;">
                            <div style="font-size:40px;">⚠️</div>
                            <h4 style="color:#D97706;margin:4px 0;">Sudah Absen</h4>
                            <p style="color:#92400E;font-size:13px;">Siswa ini sudah absen di sesi ini.</p>
                        </div>`;
                    document.getElementById('scanStatus').textContent = '📷 Siap Scan Lagi';
                    document.getElementById('scanStatus').style.color = '#D97706';
                    
                } else {
                    resultEl.innerHTML = `
                        <div style="background:#FEE2E2;border:2px solid #DC2626;border-radius:12px;padding:16px;text-align:center;animation:shake 0.5s;">
                            <div style="font-size:40px;">❌</div>
                            <h4 style="color:#DC2626;margin:4px 0;">Gagal</h4>
                            <p style="color:#991B1B;font-size:13px;">${data.message}</p>
                        </div>`;
                    document.getElementById('scanStatus').textContent = '📷 Siap Scan Lagi';
                    document.getElementById('scanStatus').style.color = '#DC2626';
                }
                
                isProcessing = false;
            })
            .catch(() => {
                document.getElementById('scanResult').innerHTML = `
                    <div style="background:#FEE2E2;border-radius:12px;padding:16px;text-align:center;">
                        <p style="color:#DC2626;">❌ Gagal koneksi</p>
                    </div>`;
                isProcessing = false;
            });
        },
        function() {}
    ).catch(function() {
        document.getElementById('scanStatus').textContent = '❌ Gagal akses kamera';
        document.getElementById('scanStatus').style.color = '#DC2626';
        stopScan();
    });
}

function stopScan() {
    if (html5QrCode) {
        html5QrCode.stop().then(() => {
            resetUI();
        }).catch(() => {
            resetUI();
        });
    } else {
        resetUI();
    }
}

function resetUI() {
    document.getElementById('reader').style.display = 'none';
    document.getElementById('scannerPlaceholder').style.display = 'flex';
    document.getElementById('btnStartScan').style.display = 'inline-flex';
    document.getElementById('btnStopScan').style.display = 'none';
    document.getElementById('scanStatus').textContent = '📷 Siap Scan';
    document.getElementById('scanStatus').style.color = '#059669';
    isProcessing = false;
}
</script>