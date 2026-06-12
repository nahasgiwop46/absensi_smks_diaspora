<!DOCTYPE html>
<html>
<head>
    <title>Form Izin - <?= esc($siswa['nama_lengkap']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; }
        .card { background: white; border-radius: 12px; padding: 24px; max-width: 400px; margin: 0 auto; }
        h2 { margin: 0 0 16px; font-size: 20px; }
        .info { background: #EFF6FF; padding: 12px; border-radius: 8px; margin-bottom: 16px; }
        .form-group { margin-bottom: 12px; }
        label { font-weight: 600; display: block; margin-bottom: 4px; }
        select, textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; }
        .btn { width: 100%; padding: 14px; background: #059669; color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: 600; cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <h2>📝 Form Izin</h2>
        <div class="info">
            <strong><?= esc($siswa['nama_lengkap']) ?></strong><br>
            NIS: <?= esc($siswa['nis']) ?><br>
            Tanggal: <?= date('d/m/Y') ?>
        </div>
        
        <form action="/izin/kirim" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= esc($siswa['token_izin']) ?>">
            
            <div class="form-group">
                <label>Alasan</label>
                <select name="alasan" required>
                    <option value="">-- Pilih --</option>
                    <option value="Sakit">🏥 Sakit</option>
                    <option value="Izin Keluarga">👨‍👩‍👧 Izin Keluarga</option>
                    <option value="Lainnya">📋 Lainnya</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" rows="3" placeholder="Tulis keterangan..."></textarea>
            </div>
            
            <button type="submit" class="btn">✅ Kirim Izin</button>
        </form>
    </div>
</body>
</html>