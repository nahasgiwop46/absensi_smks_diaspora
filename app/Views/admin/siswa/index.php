<div class="page-title">👨‍🎓 Data Siswa</div>
<div class="page-subtitle">Kelola data siswa dan QR Code</div>

<!-- Toolbar -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-body" style="padding:12px 16px;">
        <div class="toolbar" style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;">
            <div class="search-box" style="flex:2;min-width:180px;">
                <i class="fas fa-search"></i>
                <input type="text" id="searchSiswa" placeholder="Cari nama, NIS, atau NISN..." onkeyup="filterTable()" style="padding:8px 12px 8px 36px;">
            </div>
            <select class="filter-select" id="filterKelas" onchange="filterTable()" style="padding:8px;min-width:160px;">
                <option value="">🏫 Semua Kelas</option>
                <?php foreach ($kelas as $k): ?>
                <option value="<?= esc($k['tingkat'] . ' ' . ($k['jurusan_singkatan'] ?? '') . ' ' . $k['rombel']) ?>">
                    <?= esc($k['tingkat'] . ' ' . ($k['jurusan_singkatan'] ?? '') . ' ' . $k['rombel']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <select class="filter-select" id="filterStatus" onchange="filterTable()" style="padding:8px;min-width:130px;">
                <option value="">📋 Semua Status</option>
                <option value="✅ Aktif">✅ Aktif</option>
                <option value="❌ Nonaktif">❌ Nonaktif</option>
            </select>
            <div style="display:flex;gap:6px;">
                <a href="/admin/siswa/create" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Tambah</a>
                <button class="btn btn-outline btn-sm" onclick="document.getElementById('modalImport').style.display='flex'"><i class="fas fa-file-import"></i> Import</button>
                <a href="/admin/siswa/penempatan-kelas" class="btn btn-outline btn-sm"><i class="fas fa-people-arrows"></i> Penempatan</a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Import -->
<div id="modalImport" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:2000;align-items:center;justify-content:center;" onclick="if(event.target===this) this.style.display='none'">
    <div style="background:white;border-radius:16px;padding:24px;max-width:500px;width:90%;" onclick="event.stopPropagation()">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <h3 style="font-size:18px;"><i class="fas fa-file-import" style="color:#2563EB;"></i> Import Data Siswa</h3>
            <button onclick="document.getElementById('modalImport').style.display='none'" style="border:none;background:none;font-size:20px;cursor:pointer;">&times;</button>
        </div>
        <form action="/admin/siswa/import" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="form-group"><label class="form-label">File Excel (.xlsx, .xls, .csv)</label><input type="file" name="file_siswa" class="form-input" accept=".xlsx,.xls,.csv" required></div>
            <div style="background:#FEF3C7;border-radius:8px;padding:10px;margin-bottom:12px;font-size:12px;"><strong>Format:</strong> NIS | NISN | Nama | JK | Kelas</div>
            <div style="display:flex;gap:8px;justify-content:flex-end;">
                <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('modalImport').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-upload"></i> Import</button>
            </div>
        </form>
    </div>
</div>

<!-- Tabel -->
<div class="card">
    <div class="card-header" style="padding:10px 16px;">
        <span class="card-title" style="font-size:15px;">📋 Daftar Siswa</span>
        <span style="font-size:12px;color:#6B7280;">Total: <strong id="totalData"><?= count($siswa ?? []) ?></strong> siswa</span>
    </div>
    <div class="card-body" style="padding:0;">
        <?php if (empty($siswa)): ?>
            <div class="empty-state" style="padding:40px;">
                <i class="fas fa-user-graduate" style="font-size:48px;"></i>
                <p>Belum ada data siswa</p>
                <div style="display:flex;gap:8px;justify-content:center;margin-top:12px;">
                    <a href="/admin/siswa/create" class="btn btn-primary btn-sm">➕ Tambah</a>
                    <button class="btn btn-outline btn-sm" onclick="document.getElementById('modalImport').style.display='flex'">📥 Import</button>
                </div>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table" id="siswaTable" style="font-size:13px;">
                <thead>
                    <tr>
                        <th width="40">No</th><th>NIS</th><th>NISN</th><th>Nama</th><th>Kelas</th>
                        <th width="50">JK</th><th width="80">QR</th><th width="80">Status</th>
                        <th width="120" style="text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($siswa as $s): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($s['nis']) ?></td>
                        <td><?= esc($s['nisn'] ?? '-') ?></td>
                        <td><a href="/admin/siswa/detail/<?= $s['id'] ?>" style="text-decoration:none;font-weight:600;"><?= esc($s['nama_lengkap']) ?></a></td>
                        <td><span class="badge badge-info" style="font-size:11px;"><?= esc($s['nama_kelas'] ?? '-') ?></span></td>
                        <td><?= ($s['jenis_kelamin'] ?? '') == 'L' ? '👨 L' : '👩 P' ?></td>
                        <td>
                            <?php if (!empty($s['qr_code'])): ?>
                                <span class="badge badge-success" style="font-size:10px;cursor:pointer;" onclick="showQR('<?= esc($s['nis'], 'js') ?>', '<?= esc($s['nama_lengkap'], 'js') ?>')">✅ QR</span>
                            <?php else: ?>
                                <span class="badge badge-warning" style="font-size:10px;">⚠️</span>
                            <?php endif; ?>
                        </td>
                        <td><?= badgeAktif($s['is_active'] ?? 1) ?></td>
                        <td>
                            <div class="btn-group" style="justify-content:center;gap:3px;">
                                <a href="/admin/siswa/detail/<?= $s['id'] ?>" class="btn-icon" title="Detail"><i class="fas fa-eye" style="color:#3B82F6;"></i></a>
                                <a href="/admin/siswa/edit/<?= $s['id'] ?>" class="btn-icon" title="Edit"><i class="fas fa-edit" style="color:#F59E0B;"></i></a>
                                <form action="/admin/siswa/delete/<?= $s['id'] ?>" method="post" style="display:inline;" onsubmit="return confirm('Hapus «<?= esc($s['nama_lengkap']) ?>»?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn-icon" title="Hapus"><i class="fas fa-trash" style="color:#EF4444;"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($pager)): ?>
<div style="padding:12px;text-align:center;font-size:13px;"><?= $pager->links() ?></div>
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
var logoImage = new Image();
logoImage.src = '/uploads/logo/logo.jpeg';
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

function downloadModalQR() { 
    var qrCanvas = document.querySelector('#qrModalCanvas canvas'); 
    if (!qrCanvas) return;
    
    var w = 340, h = 480;
    var finalCanvas = document.createElement('canvas');
    finalCanvas.width = w; finalCanvas.height = h;
    var ctx = finalCanvas.getContext('2d');
    
    ctx.fillStyle = '#FFFFFF'; ctx.fillRect(0, 0, w, h);
    ctx.strokeStyle = '#2563EB'; ctx.lineWidth = 3; ctx.strokeRect(5, 5, w - 10, h - 10);
    
    var logoImg = new Image(); logoImg.crossOrigin = 'anonymous';
    logoImg.onload = function() {
        ctx.fillStyle = '#2563EB'; ctx.beginPath(); ctx.roundRect(5, 5, w - 10, 110, [10,10,0,0]); ctx.fill();
        var ls = 40; ctx.fillStyle = '#FFFFFF'; ctx.beginPath(); ctx.arc(w/2, 40, ls/2+6, 0, Math.PI*2); ctx.fill();
        ctx.save(); ctx.beginPath(); ctx.arc(w/2, 40, ls/2, 0, Math.PI*2); ctx.clip(); ctx.drawImage(logoImg, w/2-ls/2, 40-ls/2, ls, ls); ctx.restore();
        ctx.fillStyle = '#FFFFFF'; ctx.font = 'bold 15px Arial'; ctx.textAlign = 'center'; ctx.fillText('SMKS DIASPORA', w/2, 85);
        ctx.font = '11px Arial'; ctx.fillText('Kotaraja Dalam', w/2, 100);
        ctx.fillStyle = '#111827'; ctx.font = 'bold 20px Arial'; ctx.fillText(document.getElementById('qrModalName').textContent, w/2, 145);
        ctx.fillStyle = '#6B7280'; ctx.font = '13px Arial'; ctx.fillText(document.getElementById('qrModalNis').textContent, w/2, 165);
        ctx.strokeStyle = '#E5E7EB'; ctx.lineWidth = 1; ctx.beginPath(); ctx.moveTo(40, 178); ctx.lineTo(w-40, 178); ctx.stroke();
        var qs = 190, qx = (w-qs)/2, qy = 192;
        ctx.fillStyle = '#FFFFFF'; ctx.shadowColor = 'rgba(0,0,0,0.1)'; ctx.shadowBlur = 10; ctx.fillRect(qx-14, qy-14, qs+28, qs+28); ctx.shadowColor = 'transparent';
        ctx.strokeStyle = '#111827'; ctx.lineWidth = 2; ctx.strokeRect(qx-14, qy-14, qs+28, qs+28);
        ctx.drawImage(qrCanvas, qx, qy, qs, qs);
        var ql = 44, lx = qx+(qs-ql)/2, ly = qy+(qs-ql)/2;
        ctx.fillStyle = '#FFFFFF'; ctx.beginPath(); ctx.arc(lx+ql/2, ly+ql/2, ql/2+4, 0, Math.PI*2); ctx.fill();
        ctx.strokeStyle = '#111827'; ctx.lineWidth = 1.5; ctx.stroke();
        ctx.save(); ctx.beginPath(); ctx.arc(lx+ql/2, ly+ql/2, ql/2, 0, Math.PI*2); ctx.clip(); ctx.drawImage(logoImg, lx, ly, ql, ql); ctx.restore();
        ctx.fillStyle = '#2563EB'; ctx.font = 'bold 10px Arial'; ctx.fillText('KARTU ABSENSI SISWA', w/2, h-55);
        ctx.fillStyle = '#9CA3AF'; ctx.font = '9px Arial'; ctx.fillText('Scan QR ini untuk absensi', w/2, h-38);
        ctx.fillText('© '+new Date().getFullYear()+' SMKS DIASPORA', w/2, h-22);
        var a = document.createElement('a'); a.download = 'QR-'+modalQRData+'.png'; a.href = finalCanvas.toDataURL('image/png'); a.click();
    };
    logoImg.onerror = function() { var a = document.createElement('a'); a.download = 'QR-'+modalQRData+'.png'; a.href = finalCanvas.toDataURL('image/png'); a.click(); };
    logoImg.src = '/uploads/logo/logo.jpeg';
}

function printModalQR() { 
    var qrCanvas = document.querySelector('#qrModalCanvas canvas'); 
    if (!qrCanvas) return;
    var w = 350, h = 230;
    var finalCanvas = document.createElement('canvas'); finalCanvas.width = w; finalCanvas.height = h;
    var ctx = finalCanvas.getContext('2d'); ctx.fillStyle = '#FFFFFF'; ctx.fillRect(0, 0, w, h);
    var logoImg = new Image(); logoImg.crossOrigin = 'anonymous';
    logoImg.onload = function() {
        var ls = 35; ctx.drawImage(logoImg, 15, 15, ls, ls);
        ctx.fillStyle = '#111827'; ctx.font = 'bold 12px Arial'; ctx.textAlign = 'left'; ctx.fillText('SMKS DIASPORA', 58, 30);
        ctx.fillStyle = '#6B7280'; ctx.font = '9px Arial'; ctx.fillText('Kotaraja Dalam', 58, 42);
        ctx.strokeStyle = '#2563EB'; ctx.lineWidth = 2; ctx.beginPath(); ctx.moveTo(155, 10); ctx.lineTo(155, 65); ctx.stroke();
        ctx.fillStyle = '#111827'; ctx.font = 'bold 13px Arial'; ctx.fillText(document.getElementById('qrModalName').textContent, 165, 30);
        ctx.fillStyle = '#6B7280'; ctx.font = '10px Arial'; ctx.fillText(document.getElementById('qrModalNis').textContent, 165, 44);
        ctx.fillStyle = '#2563EB'; ctx.font = 'bold 7px Arial'; ctx.fillText('KARTU ABSENSI', 165, 58);
        ctx.strokeStyle = '#2563EB'; ctx.lineWidth = 1; ctx.beginPath(); ctx.moveTo(10, 72); ctx.lineTo(w-10, 72); ctx.stroke();
        var qs = 110, qx = 15, qy = 82;
        ctx.fillStyle = '#FFFFFF'; ctx.fillRect(qx-4, qy-4, qs+8, qs+8); ctx.strokeStyle = '#111827'; ctx.lineWidth = 1.5; ctx.strokeRect(qx-4, qy-4, qs+8, qs+8);
        ctx.drawImage(qrCanvas, qx, qy, qs, qs);
        var ql = 28, lx = qx+(qs-ql)/2, ly = qy+(qs-ql)/2;
        ctx.fillStyle = '#FFFFFF'; ctx.beginPath(); ctx.arc(lx+ql/2, ly+ql/2, ql/2+3, 0, Math.PI*2); ctx.fill();
        ctx.strokeStyle = '#111827'; ctx.lineWidth = 1; ctx.stroke();
        ctx.save(); ctx.beginPath(); ctx.arc(lx+ql/2, ly+ql/2, ql/2, 0, Math.PI*2); ctx.clip(); ctx.drawImage(logoImg, lx, ly, ql, ql); ctx.restore();
        ctx.fillStyle = '#111827'; ctx.font = 'bold 10px Arial'; ctx.textAlign = 'left'; ctx.fillText('KARTU ABSENSI', 140, 95); ctx.fillText('SISWA', 140, 109);
        ctx.fillStyle = '#6B7280'; ctx.font = '8px Arial'; ctx.fillText('Tunjukkan QR ini', 140, 130); ctx.fillText('kepada guru untuk', 140, 142); ctx.fillText('absensi harian.', 140, 154);
        ctx.strokeStyle = '#2563EB'; ctx.lineWidth = 1; ctx.beginPath(); ctx.moveTo(10, h-25); ctx.lineTo(w-10, h-25); ctx.stroke();
        ctx.fillStyle = '#9CA3AF'; ctx.font = '7px Arial'; ctx.textAlign = 'center'; ctx.fillText('© '+new Date().getFullYear()+' SMKS DIASPORA | Scan untuk absensi', w/2, h-10);
        var dataUrl = finalCanvas.toDataURL('image/png');
        var pw = window.open('', '_blank');
        pw.document.write('<!DOCTYPE html><html><head><title>Cetak Kartu QR</title><style>@page{size:A4;margin:10mm}body{margin:0;display:flex;flex-wrap:wrap;gap:10px;justify-content:center}.card{width:48%}img{width:100%}@media print{.card{border:none;padding:2mm}}</style></head><body>');
        for (var i = 0; i < 4; i++) { pw.document.write('<div class="card"><img src="'+dataUrl+'"></div>'); }
        pw.document.write('<script>onload=function(){print();setTimeout(close,500)}<\/script></body></html>');
        pw.document.close();
    };
    logoImg.onerror = function() { var w = window.open(); w.document.write('<img src="'+qrCanvas.toDataURL()+'" style="width:250px;">'); w.print(); };
    logoImg.src = '/uploads/logo/logo.jpeg';
}

function filterTable() {
    var s = (document.getElementById('searchSiswa')?.value || '').toLowerCase();
    var k = document.getElementById('filterKelas')?.value || '';
    var st = document.getElementById('filterStatus')?.value || '';
    var c = 0;
    document.querySelectorAll('#siswaTable tbody tr').forEach(function(r) {
        var t = r.textContent.toLowerCase();
        var rk = (r.cells[4]?.textContent || '').trim();
        var rs = (r.cells[7]?.textContent || '').trim();
        var m = t.includes(s) && (!k || rk.includes(k)) && (!st || rs.includes(st.replace('✅ ','').replace('❌ ','')));
        r.style.display = m ? '' : 'none';
        if (m) c++;
    });
    document.getElementById('totalData').textContent = c;
}
</script>