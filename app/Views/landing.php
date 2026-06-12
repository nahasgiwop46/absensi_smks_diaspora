<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi QR - SMKS DIASPORA</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: linear-gradient(135deg, #1a1a2e, #16213e); color: white; min-height: 100vh; }
        
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 24px; background: rgba(0,0,0,0.3); }
        .navbar .logo { font-size: 20px; font-weight: 700; }
        .navbar .logo i { color: #2563EB; margin-right: 8px; }
        .navbar a { color: white; text-decoration: none; margin-left: 16px; font-weight: 500; }
        .btn-login { background: #2563EB; padding: 10px 24px; border-radius: 10px; font-weight: 600; }
        .btn-login:hover { background: #1D4ED8; }
        
        .hero { text-align: center; padding: 80px 20px 40px; }
        .hero h1 { font-size: 42px; margin-bottom: 16px; }
        .hero h1 span { color: #2563EB; }
        .hero p { font-size: 18px; opacity: 0.8; max-width: 600px; margin: 0 auto 32px; }
        .btn-hero { background: #2563EB; color: white; padding: 14px 32px; border-radius: 12px; font-size: 16px; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-hero:hover { background: #1D4ED8; transform: translateY(-2px); }
        
        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px; padding: 40px 24px; max-width: 1000px; margin: 0 auto; }
        .feature-card { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 24px; text-align: center; transition: 0.3s; }
        .feature-card:hover { background: rgba(255,255,255,0.1); transform: translateY(-4px); }
        .feature-card i { font-size: 36px; margin-bottom: 12px; color: #2563EB; }
        .feature-card h3 { font-size: 18px; margin-bottom: 8px; }
        .feature-card p { font-size: 13px; opacity: 0.7; }
        
        .footer { text-align: center; padding: 24px; font-size: 12px; opacity: 0.5; }
        
        @media (max-width: 768px) {
            .hero h1 { font-size: 28px; }
            .hero p { font-size: 14px; }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo"><i class="fas fa-qrcode"></i> Absensi QR</div>
        <div>
            <a href="/login" class="btn-login"><i class="fas fa-sign-in-alt"></i> Login</a>
        </div>
    </nav>

    <section class="hero">
        <h1>Sistem Absensi <span>QR Code</span></h1>
        <p>SMKS DIASPORA — Solusi absensi modern dengan QR Code. Cepat, akurat, dan real-time dengan notifikasi WhatsApp ke orang tua.</p>
        <a href="/login" class="btn-hero"><i class="fas fa-arrow-right"></i> Mulai Sekarang</a>
    </section>

    <section class="features">
        <div class="feature-card">
            <i class="fas fa-qrcode"></i>
            <h3>QR Code</h3>
            <p>Absen cukup dengan scan QR Code, tanpa kertas</p>
        </div>
        <div class="feature-card">
            <i class="fab fa-whatsapp"></i>
            <h3>Notifikasi WhatsApp</h3>
            <p>Orang tua dapat notifikasi real-time</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-chart-bar"></i>
            <h3>Laporan Lengkap</h3>
            <p>Rekap kehadiran harian, bulanan, dan semester</p>
        </div>
    </section>

    <div class="footer">
        © <?= date('Y') ?> SMKS DIASPORA — Kotaraja Dalam. All rights reserved.
    </div>
</body>
</html>