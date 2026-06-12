<!DOCTYPE html>
<html>
<head>
    <title>Izin Berhasil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial; background: #f5f5f5; padding: 20px; text-align: center; }
        .card { background: white; border-radius: 12px; padding: 40px 24px; max-width: 400px; margin: 0 auto; }
        .icon { font-size: 64px; margin-bottom: 16px; }
        h2 { color: #059669; margin: 0; }
        p { color: #6B7280; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">✅</div>
        <h2>Izin Berhasil!</h2>
        <p>Izin untuk <strong><?= esc($siswa['nama_lengkap']) ?></strong> telah dikirim.</p>
        <p>Status absen berubah menjadi <strong>IZIN</strong>.</p>
    </div>
</body>
</html>