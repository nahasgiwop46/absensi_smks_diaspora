<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- Favicon - langsung pakai gambar asli, browser yang resize otomatis -->
     <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon-180.png">
    
    <title>Lupa Password - Absensi QR SMKS Diaspora</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563EB;
            --primary-dark: #1D4ED8;
            --bg: #F3F4F6;
            --white: #FFFFFF;
            --border: #E5E7EB;
            --text: #111827;
            --text-secondary: #6B7280;
            --danger: #EF4444;
            --success: #10B981;
            --radius: 16px;
            --radius-sm: 10px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 50%, #BFDBFE 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            overflow: hidden;
        }

        /* ============ ANIMATED BACKGROUND ============ */
        .bg-circle {
            position: fixed;
            border-radius: 50%;
            opacity: 0.08;
            pointer-events: none;
            z-index: 0;
        }
        .bg-circle-1 {
            width: 400px; height: 400px;
            background: var(--primary);
            top: -150px; right: -100px;
            animation: float1 8s ease-in-out infinite;
        }
        .bg-circle-2 {
            width: 300px; height: 300px;
            background: #7C3AED;
            bottom: -120px; left: -100px;
            animation: float2 10s ease-in-out infinite;
        }
        .bg-circle-3 {
            width: 150px; height: 150px;
            background: var(--primary-dark);
            top: 50%; left: 5%;
            animation: float3 6s ease-in-out infinite;
        }

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-30px, 20px) scale(1.05); }
        }
        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(25px, -20px) scale(1.08); }
        }
        @keyframes float3 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-15px, 10px); }
        }

        .forgot-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        /* ============ LOGO ANIMATION ============ */
        .forgot-logo {
            text-align: center;
            margin-bottom: 24px;
            animation: slideDown 0.8s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .forgot-logo-icon {
            width: 64px; height: 64px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: white;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(37, 99, 235, 0.2);
            margin-bottom: 12px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { box-shadow: 0 8px 30px rgba(37, 99, 235, 0.2); }
            50% { box-shadow: 0 8px 40px rgba(37, 99, 235, 0.4); }
        }

        .forgot-logo-icon img {
            width: 100%; height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }

        .forgot-logo h1 {
            font-size: 20px;
            font-weight: 700;
            color: var(--text);
        }

        .forgot-logo p {
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* ============ CARD ANIMATION ============ */
        .forgot-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            border: 1px solid var(--border);
            animation: slideUp 0.8s ease 0.15s both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            margin-bottom: 16px;
            animation: fadeIn 0.6s ease 0.3s both;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 16px;
            transition: color 0.3s;
        }

        .input-wrapper:focus-within i {
            color: var(--primary);
        }

        .form-input {
            width: 100%;
            padding: 12px 16px 12px 42px;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            background: #F9FAFB;
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            transform: scale(1.01);
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.6s ease 0.4s both;
        }

        .btn-submit:hover {
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            transform: translateY(-2px);
        }

        .btn-submit:active {
            transform: scale(0.97);
        }

        /* Shine effect */
        .btn-submit::after {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            margin-bottom: 14px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: shake 0.5s ease;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            50% { transform: translateX(5px); }
            75% { transform: translateX(-5px); }
        }

        .alert-error {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
        }

        .alert-success {
            background: #ECFDF5;
            color: #059669;
            border: 1px solid #A7F3D0;
            animation: slideUp 0.5s ease;
        }

        .back-link {
            text-align: center;
            margin-top: 16px;
            font-size: 13px;
            animation: fadeIn 0.8s ease 0.5s both;
        }

        .back-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .back-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .forgot-card { padding: 20px; }
            .bg-circle-1 { width: 200px; height: 200px; }
            .bg-circle-2 { width: 150px; height: 150px; }
        }
    </style>
</head>
<body>

    <!-- Animated Background -->
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>
    <div class="bg-circle bg-circle-3"></div>

    <div class="forgot-wrapper">
        
        <!-- Logo -->
        <div class="forgot-logo">
            <div class="forgot-logo-icon">
                <?php if (file_exists(FCPATH . 'uploads/logo/logo.jpeg')): ?>
                    <img src="<?= base_url('uploads/logo/logo.jpeg') ?>" alt="Logo">
                <?php else: ?>
                    <i class="fas fa-graduation-cap" style="font-size:28px;color:#2563EB;"></i>
                <?php endif; ?>
            </div>
            <h1>Lupa Password</h1>
            <p>Masukkan username atau email untuk reset password</p>
        </div>

        <!-- Card -->
        <div class="forgot-card">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('auth/send-reset-link') ?>" method="post">
                <?= csrf_field() ?>
                
                <div class="form-group">
                    <label class="form-label">Username atau Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input type="text" name="credential" class="form-input" 
                               placeholder="Masukkan username atau email" required autofocus>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Kirim Link Reset
                </button>
            </form>
        </div>

        <div class="back-link">
            <a href="<?= base_url('login') ?>"><i class="fas fa-arrow-left"></i> Kembali ke Login</a>
        </div>
    </div>

</body>
</html>