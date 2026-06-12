<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32.png">
    <link rel="icon" href="<?= base_url('uploads/logo/logo.jpeg') ?>">
    <title><?= esc($title) ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1D4ED8;
            --primary-light: #EFF6FF;
            --bg: #F3F4F6;
            --white: #FFFFFF;
            --border: #E5E7EB;
            --text: #111827;
            --text-secondary: #6B7280;
            --shadow: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-lg: 0 20px 60px rgba(0,0,0,0.1);
            --radius: 16px;
            --radius-sm: 10px;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { -webkit-text-size-adjust: 100%; -webkit-tap-highlight-color: transparent; }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 50%, #BFDBFE 100%);
            min-height: 100vh; line-height: 1.5; overflow-x: hidden;
        }
        
        /* Animated Background */
        .bg-animated { position: fixed; inset: 0; z-index: 0; pointer-events: none; overflow: hidden; }
        .bg-circle { position: absolute; border-radius: 50%; opacity: 0.08; }
        .bg-circle-1 { width: 500px; height: 500px; background: var(--primary); top: -180px; right: -150px; animation: float1 8s ease-in-out infinite; }
        .bg-circle-2 { width: 350px; height: 350px; background: #7C3AED; bottom: -120px; left: -100px; animation: float2 10s ease-in-out infinite; }
        .bg-circle-3 { width: 200px; height: 200px; background: var(--primary-dark); top: 35%; left: 8%; animation: float3 6s ease-in-out infinite; }
        .bg-circle-4 { width: 150px; height: 150px; background: #059669; bottom: 25%; right: 10%; animation: float4 7s ease-in-out infinite; }
        
        @keyframes float1 { 0%,100%{transform:translate(0,0) scale(1)} 33%{transform:translate(-30px,20px) scale(1.05)} 66%{transform:translate(20px,-15px) scale(0.95)} }
        @keyframes float2 { 0%,100%{transform:translate(0,0) scale(1)} 50%{transform:translate(25px,-20px) scale(1.08)} }
        @keyframes float3 { 0%,100%{transform:translate(0,0) rotate(0deg)} 50%{transform:translate(-15px,10px) rotate(5deg)} }
        @keyframes float4 { 0%,100%{transform:translate(0,0)} 50%{transform:translate(10px,-25px)} }
        
        /* Navbar */
       .navbar {
    display: flex; justify-content: space-between; align-items: center;
    padding: 16px 32px; 
    position: fixed; /* ✅ Fixed */
    top: 0; left: 0; right: 0; /* ✅ Sticky di atas */
    z-index: 100;
    background: rgba(255,255,255,0.85); 
    backdrop-filter: blur(15px);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}
        .navbar .logo { font-size: 20px; font-weight: 700; display: flex; align-items: center; gap: 10px; color: var(--text); }
        .navbar .logo img { height: 32px; border-radius: 50%; }
        .btn-nav { padding: 10px 22px; border-radius: 8px; font-weight: 600; text-decoration: none; font-size: 14px; transition: 0.3s; }
        .btn-outline { border: 2px solid var(--border); color: var(--text); margin-right: 8px; }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-dark); }
        

        
        /* Hero */
       .hero {
    text-align: center; 
    padding: 120px 20px 60px; /* ✅ Tambah padding-top */
    position: relative; z-index: 1;
}
        .hero .badge {
            display: inline-block; background: rgba(37,99,235,0.1); color: var(--primary);
            padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 600;
            margin-bottom: 20px; letter-spacing: 0.5px;
        }
        .hero h1 { font-size: 48px; font-weight: 800; color: var(--text); margin-bottom: 16px; }
        .hero h1 span { background: linear-gradient(135deg, #2563EB, #7C3AED); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero p { font-size: 18px; color: var(--text-secondary); max-width: 550px; margin: 0 auto 40px; }
        .btn-group { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
        .btn-lg { padding: 14px 32px; border-radius: 12px; font-size: 15px; font-weight: 600; text-decoration: none; transition: 0.3s; }
        .btn-lg-primary { background: var(--primary); color: white; box-shadow: 0 4px 20px rgba(37,99,235,0.4); }
        .btn-lg-primary:hover { background: var(--primary-dark); transform: translateY(-2px); }
        .btn-lg-outline { border: 2px solid var(--border); color: var(--text); }
        .btn-lg-outline:hover { border-color: var(--primary); color: var(--primary); }
        
        /* Stats */
        .stats { display: flex; justify-content: center; gap: 40px; margin-top: 50px; flex-wrap: wrap; }
        .stat-item { text-align: center; }
        .stat-item .number { font-size: 40px; font-weight: 800; color: var(--text); }
        .stat-item .label { font-size: 13px; color: var(--text-secondary); margin-top: 4px; }
        .stat-divider { width: 1px; background: var(--border); height: 50px; }
        
        /* Features */
        .section-title { text-align: center; font-size: 28px; font-weight: 700; color: var(--text); margin-bottom: 32px; }
        .features { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; padding: 40px 32px; max-width: 1000px; margin: 0 auto; }
        .feature-card {
            background: var(--white); border: 1px solid var(--border); border-radius: var(--radius);
            padding: 28px; text-align: center; transition: 0.3s; box-shadow: var(--shadow);
        }
        .feature-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-4px); }
        .feature-card .icon {
            width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px; font-size: 24px;
        }
        .icon-blue { background: #EFF6FF; color: #2563EB; }
        .icon-green { background: #ECFDF5; color: #10B981; }
        .icon-purple { background: #F5F3FF; color: #7C3AED; }
        .feature-card h3 { font-size: 17px; margin-bottom: 8px; color: var(--text); }
        .feature-card p { font-size: 13px; color: var(--text-secondary); line-height: 1.5; }
        
        /* CTA */
        .cta {
            text-align: center; padding: 60px 20px;
            background: linear-gradient(135deg, rgba(37,99,235,0.05), rgba(124,58,237,0.05));
        }
        .cta h2 { font-size: 26px; color: var(--text); margin-bottom: 12px; }
        .cta p { color: var(--text-secondary); margin-bottom: 24px; }
        
        /* Footer */
        .footer { text-align: center; padding: 24px; font-size: 12px; color: var(--text-secondary); border-top: 1px solid var(--border); }
        
        @media (max-width: 768px) {
            .hero h1 { font-size: 30px; }
            .features { grid-template-columns: 1fr; padding: 20px; }
            .stats { gap: 20px; }
            .stat-item .number { font-size: 28px; }
            .navbar { padding: 12px 16px; }
        }
    </style>
</head>
<body>
    <!-- Animated Background -->
    <div class="bg-animated">
        <div class="bg-circle bg-circle-1"></div>
        <div class="bg-circle bg-circle-2"></div>
        <div class="bg-circle bg-circle-3"></div>
        <div class="bg-circle bg-circle-4"></div>
    </div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">
            <?php if (file_exists(FCPATH . 'uploads/logo/logo.jpeg')): ?>
                <img src="<?= base_url('uploads/logo/logo.jpeg') ?>" alt="Logo">
            <?php endif; ?>
            <span><?= esc($pengaturan['nama_sekolah'] ?? 'SMKS DIASPORA') ?></span>
        </div>
        <div>
            <a href="/login" class="btn-nav btn-outline">Masuk</a>
            <a href="/login" class="btn-nav btn-primary"><i class="fas fa-qrcode"></i> Absen</a>
        </div>
    </nav>

    <!-- Hero -->
    <section class="hero">
        <div class="badge">🚀 Sistem Absensi Digital</div>
        <h1>Absensi <span>QR Code</span> Sekolah</h1>
        <p>Cukup scan QR Code, absensi tercatat otomatis. Orang tua dapat notifikasi WhatsApp real-time. Praktis, cepat, dan akurat.</p>
        <div class="btn-group">
            <a href="/login" class="btn-lg btn-lg-primary"><i class="fas fa-sign-in-alt"></i> Mulai Absen</a>
            <a href="/login" class="btn-lg btn-lg-outline"><i class="fas fa-info-circle"></i> Info Sekolah</a>
        </div>
        <div class="stats">
            <div class="stat-item"><div class="number"><?= esc($total_siswa) ?></div><div class="label">Siswa Aktif</div></div>
            <div class="stat-divider"></div>
            <div class="stat-item"><div class="number"><?= esc($total_guru) ?></div><div class="label">Guru</div></div>
            <div class="stat-divider"></div>
            <div class="stat-item"><div class="number"><?= esc($total_kelas) ?></div><div class="label">Kelas</div></div>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="feature-card">
            <div class="icon icon-blue"><i class="fas fa-qrcode"></i></div>
            <h3>Scan QR Code</h3>
            <p>Siswa tunjukkan QR pribadi atau scan QR guru via HP. Absen hanya 1 detik.</p>
        </div>
        <div class="feature-card">
            <div class="icon icon-green"><i class="fab fa-whatsapp"></i></div>
            <h3>Notifikasi WhatsApp</h3>
            <p>Orang tua langsung dapat notifikasi saat anak hadir, izin, sakit, atau alpa.</p>
        </div>
        <div class="feature-card">
            <div class="icon icon-purple"><i class="fas fa-chart-bar"></i></div>
            <h3>Laporan Lengkap</h3>
            <p>Rekap kehadiran harian, bulanan, per mata pelajaran. Real-time.</p>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta">
        <h2>Siap Memulai?</h2>
        <p>Login sekarang dan rasakan kemudahan absensi digital</p>
        <a href="/login" class="btn-lg btn-lg-primary"><i class="fas fa-arrow-right"></i> Login Sekarang</a>
    </section>

    <!-- Footer -->
    <div class="footer">
        © <?= date('Y') ?> <?= esc($pengaturan['nama_sekolah'] ?? 'SMKS DIASPORA') ?> — <?= esc($pengaturan['alamat'] ?? 'Kotaraja Dalam') ?>
    </div>
</body>
</html>