<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

     <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon-180.png">
    <title><?= esc($pageTitle ?? 'Login') ?> - Absensi QR SMKS Diaspora</title>
     
     <link rel="icon" href="<?= base_url('uploads/logo/logo.jpeg') ?>">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- ✅ reCAPTCHA -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
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
            --danger: #EF4444;
            --success: #10B981;
            --warning: #F59E0B;
            --shadow: 0 1px 3px rgba(0,0,0,0.1);
            --shadow-lg: 0 20px 60px rgba(0,0,0,0.1);
            --radius: 16px;
            --radius-sm: 10px;
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        html {
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: transparent;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 50%, #BFDBFE 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            line-height: 1.5;
            overflow-x: hidden;
        }
        
        /* ============ ANIMATED BACKGROUND ============ */
        .bg-animated {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        
        .bg-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.08;
        }
        
        .bg-circle-1 {
            width: 500px; height: 500px;
            background: var(--primary);
            top: -180px; right: -150px;
            animation: float1 8s ease-in-out infinite;
        }
        
        .bg-circle-2 {
            width: 350px; height: 350px;
            background: #7C3AED;
            bottom: -120px; left: -100px;
            animation: float2 10s ease-in-out infinite;
        }
        
        .bg-circle-3 {
            width: 200px; height: 200px;
            background: var(--primary-dark);
            top: 35%; left: 8%;
            animation: float3 6s ease-in-out infinite;
        }
        
        .bg-circle-4 {
            width: 150px; height: 150px;
            background: #059669;
            bottom: 25%; right: 10%;
            animation: float4 7s ease-in-out infinite;
        }
        
        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(-30px, 20px) scale(1.05); }
            66% { transform: translate(20px, -15px) scale(0.95); }
        }
        
        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(25px, -20px) scale(1.08); }
        }
        
        @keyframes float3 {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-15px, 10px) rotate(5deg); }
        }
        
        @keyframes float4 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(10px, -25px); }
        }
        
        /* Floating dots */
        .bg-dots {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
        }
        
        .dot {
            position: absolute;
            width: 6px; height: 6px;
            background: var(--primary);
            border-radius: 50%;
            opacity: 0.15;
            animation: dotFloat 4s ease-in-out infinite;
        }
        
        .dot:nth-child(1) { top: 15%; left: 10%; animation-delay: 0s; }
        .dot:nth-child(2) { top: 25%; right: 15%; animation-delay: 0.5s; width: 8px; height: 8px; }
        .dot:nth-child(3) { bottom: 30%; left: 20%; animation-delay: 1s; width: 4px; height: 4px; }
        .dot:nth-child(4) { top: 60%; right: 25%; animation-delay: 1.5s; }
        .dot:nth-child(5) { bottom: 15%; left: 40%; animation-delay: 2s; width: 7px; height: 7px; }
        .dot:nth-child(6) { top: 40%; left: 50%; animation-delay: 2.5s; width: 5px; height: 5px; }
        .dot:nth-child(7) { top: 10%; right: 35%; animation-delay: 3s; }
        .dot:nth-child(8) { bottom: 40%; right: 5%; animation-delay: 3.5s; width: 8px; height: 8px; }
        
        @keyframes dotFloat {
            0%, 100% { transform: translateY(0) scale(1); opacity: 0.15; }
            50% { transform: translateY(-20px) scale(1.5); opacity: 0.3; }
        }
        
        /* ============ LOGIN WRAPPER ============ */
        .login-wrapper {
            width: 100%;
            max-width: 440px;
            position: relative;
            z-index: 1;
        }
        
        /* ============ LOGO (ANIMATED) ============ */
        .login-logo {
            text-align: center;
            margin-bottom: 28px;
            animation: logoEnter 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) both;
        }
        
        @keyframes logoEnter {
            from {
                opacity: 0;
                transform: translateY(-30px) scale(0.8);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
    .login-logo-icon {
    width: 72px; height: 72px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(37, 99, 235, 0.3);
}

.sidebar-logo {
    width: 36px; height: 36px;
    border-radius: 50%; /* ✅ Bulat */
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 16px; flex-shrink: 0;
    overflow: hidden;
}
        
        @keyframes iconPulse {
            0%, 100% { box-shadow: 0 8px 30px rgba(37, 99, 235, 0.3); }
            50% { box-shadow: 0 8px 45px rgba(37, 99, 235, 0.5); }
        }
        
        /* QR ring animation */
        .login-logo-icon::after {
            content: '';
            position: absolute;
            inset: -6px;
            border-radius: 24px;
            border: 2px solid rgba(37, 99, 235, 0.2);
            animation: ringPulse 2s ease-in-out infinite;
        }
        
        @keyframes ringPulse {
            0%, 100% { transform: scale(1); opacity: 0.3; }
            50% { transform: scale(1.1); opacity: 0; }
        }
        
        .login-logo h1 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
        }
        
        .login-logo p {
            font-size: 13px;
            color: var(--text-secondary);
        }
        
        /* ============ CARD (ANIMATED) ============ */
        .login-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 32px 28px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border);
            animation: cardEnter 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.15s both;
            backdrop-filter: blur(10px);
        }
        
        @keyframes cardEnter {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* ============ FORM ELEMENTS (STAGGERED) ============ */
        .form-group {
            margin-bottom: 20px;
            animation: fieldEnter 0.6s ease both;
        }
        
        .form-group:nth-child(1) { animation-delay: 0.3s; }
        .form-group:nth-child(2) { animation-delay: 0.4s; }
        
        @keyframes fieldEnter {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 13px;
            animation: fieldEnter 0.6s ease 0.5s both;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            border-radius: var(--radius-sm);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            font-family: inherit;
            animation: fieldEnter 0.6s ease 0.6s both;
            position: relative;
            overflow: hidden;
        }
        
        /* Shine effect on hover */
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(37, 99, 235, 0.45);
        }
        
        .btn-login:active {
            transform: scale(0.97);
        }
        
        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        /* Form Label */
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 6px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 16px;
            pointer-events: none;
            transition: all 0.3s;
        }
        
        .form-input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 2px solid var(--border);
            border-radius: var(--radius-sm);
            font-size: 14px;
            background: #F9FAFB;
            transition: all 0.3s;
            font-family: inherit;
            color: var(--text);
        }
        
        .form-input::placeholder { color: #9CA3AF; }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        
        .input-wrapper:focus-within .input-icon {
            color: var(--primary);
            transform: translateY(-50%) scale(1.1);
        }
        
        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9CA3AF;
            cursor: pointer;
            font-size: 16px;
            padding: 4px;
            transition: all 0.2s;
        }
        
        .password-toggle:hover { color: var(--text-secondary); }
        
        /* Remember Me */
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            color: var(--text-secondary);
            user-select: none;
        }
        
        .remember-me input[type="checkbox"] {
            width: 18px; height: 18px;
            accent-color: var(--primary);
            cursor: pointer;
        }
        
        .forgot-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .forgot-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        /* Loading Spinner */
        .spinner {
            display: none;
            width: 20px; height: 20px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        
        @keyframes spin { to { transform: rotate(360deg); } }
        
        .btn-login.loading .spinner { display: inline-block; }
        .btn-login.loading .btn-text { display: none; }
        
        /* ============ ALERTS ============ */
        .alert {
            padding: 14px 16px;
            border-radius: var(--radius-sm);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
        }
        
        .alert-error {
            background: #FEF2F2;
            color: #DC2626;
            border: 1px solid #FECACA;
            animation: shake 0.5s ease, fadeInUp 0.5s ease;
        }
        
        .alert-success {
            background: #ECFDF5;
            color: #059669;
            border: 1px solid #A7F3D0;
            animation: fadeInUp 0.5s ease;
        }
        
        .alert-warning {
            background: #FFFBEB;
            color: #D97706;
            border: 1px solid #FDE68A;
            animation: fadeInUp 0.5s ease;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            50% { transform: translateX(6px); }
            75% { transform: translateX(-4px); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert i { font-size: 16px; flex-shrink: 0; }
        
        /* ============ DEMO INFO ============ */
        .demo-info {
            margin-top: 20px;
            padding: 14px 16px;
            background: #FFFBEB;
            border: 1px solid #FDE68A;
            border-radius: var(--radius-sm);
            font-size: 12px;
            color: #92400E;
            animation: fieldEnter 0.6s ease 0.7s both;
        }
        
        .demo-info strong { display: block; margin-bottom: 6px; font-size: 13px; }
        .demo-info code {
            background: #FEF3C7;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
        }
        
        /* ============ FOOTER ============ */
        .login-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: var(--text-secondary);
            animation: fadeIn 1s ease 0.8s both;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* ============ RESPONSIVE ============ */
        @media (max-width: 480px) {
            body {
                padding: 16px;
                align-items: flex-start;
                padding-top: 30px;
            }
            
            .login-card {
                padding: 24px 18px;
                border-radius: 14px;
            }
            
            .login-logo-icon {
                width: 56px; height: 56px;
                font-size: 26px;
                border-radius: 16px;
            }
            
            .login-logo-icon::after {
                inset: -4px;
                border-radius: 20px;
            }
            
            .login-logo h1 { font-size: 20px; }
            
            .form-input {
                padding: 12px 14px 12px 42px;
                font-size: 13px;
            }
            
            .btn-login { padding: 13px; font-size: 14px; }
            
            .form-options {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }
            
            .bg-circle-1 { width: 250px; height: 250px; top: -100px; right: -80px; }
            .bg-circle-2 { width: 180px; height: 180px; bottom: -80px; left: -60px; }
            .bg-circle-3 { width: 120px; height: 120px; }
            .bg-circle-4 { width: 80px; height: 80px; }
        }
        
        @media (max-width: 360px) {
            .login-card { padding: 20px 14px; }
            .login-logo-icon { width: 48px; height: 48px; font-size: 22px; border-radius: 14px; }
            .login-logo h1 { font-size: 18px; }
            .demo-info { font-size: 11px; padding: 12px; }
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
    
    <!-- Floating Dots -->
    <div class="bg-dots">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
    </div>

    <!-- Login Wrapper -->
    <div class="login-wrapper">
        
        <!-- Logo (animated entrance) -->
      <div class="login-logo">
    <div class="login-logo-icon">
        <?php if (file_exists(FCPATH . 'uploads/logo/logo.jpeg')): ?>
            <img src="<?= base_url('uploads/logo/logo.jpeg') ?>" alt="Logo" 
                 style="width:100%;height:100%;object-fit:contain;background:white;border-radius:50%;">
        <?php else: ?>
            <i class="fas fa-graduation-cap"></i>
        <?php endif; ?>
    </div>
            <h1>Absensi QR SMKS Diaspora</h1>
            <p>Sistem Presensi Digital Sekolah</p>
        </div>
        
        <!-- Login Card (animated entrance) -->
        <div class="login-card">
            
            <!-- Error Alert -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>
            
            <!-- Success Alert -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>
            
            <!-- Warning Alert -->
            <?php if (session()->getFlashdata('warning')): ?>
                <div class="alert alert-warning" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span><?= session()->getFlashdata('warning') ?></span>
                </div>
            <?php endif; ?>
            
            <!-- Login Form -->
            <form id="loginForm" action="<?= base_url('auth/authenticate') ?>" method="post" autocomplete="off">
                <?= csrf_field() ?>
                
                <!-- Username -->
                <div class="form-group">
                    <label class="form-label" for="username">
                        <i class="fas fa-user" style="margin-right:4px;font-size:11px;"></i>Username
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            class="form-input" 
                            placeholder="Masukkan username Anda"
                            value="<?= old('username', '') ?>"
                            required
                            autofocus
                        >
                    </div>
                </div>
                
                <!-- Password -->
                <div class="form-group">
                    <label class="form-label" for="password">
                        <i class="fas fa-lock" style="margin-right:4px;font-size:11px;"></i>Password
                    </label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-input" 
                            placeholder="Masukkan password Anda"
                            required
                        >
                        <button type="button" class="password-toggle" onclick="togglePassword()" tabindex="-1" aria-label="Tampilkan password">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Remember & Forgot -->
                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" value="1">
                        <span>Ingat saya</span>
                    </label>
                    <a href="<?= base_url('auth/forgot-password') ?>" class="forgot-link">
                        <i class="fas fa-question-circle"></i> Lupa password?
                    </a>
                </div>
                <!-- ✅ reCAPTCHA -->
<div style="display:flex;justify-content:center;margin-bottom:16px;">
    <div class="g-recaptcha" data-sitekey="<?= getenv('RECAPTCHA_SITE_KEY') ?>"></div>
</div>
                <!-- Submit -->
                <button type="submit" class="btn-login" id="loginButton">
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i> Masuk
                    </span>
                    <span class="spinner"></span>
                </button>
            </form>
            
           
        
        <!-- Footer -->
        <div class="login-footer">
            <p>&copy; <?= date('Y') ?> Absensi QR. All rights reserved.</p>
        </div>
    </div>

    <script>
    /**
     * Toggle password visibility
     */
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    
    /**
     * Loading state saat submit
     */
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        const btn = document.getElementById('loginButton');
        btn.classList.add('loading');
        btn.disabled = true;
    });
    
    /**
     * Auto-hide alert setelah 5 detik
     */
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(function() {
                    if (alert.parentNode) alert.remove();
                }, 500);
            });
        }, 5000);
    });
    </script>
</body>
</html>