<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <!-- Favicon - langsung pakai gambar asli, browser yang resize otomatis -->
    <link rel="icon" type="image/jpeg" sizes="16x16" href="<?= base_url('uploads/logo/logo.jpeg') ?>">
    <link rel="icon" type="image/jpeg" sizes="32x32" href="<?= base_url('uploads/logo/logo.jpeg') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= base_url('uploads/logo/logo.jpeg') ?>">
    

    <title>Reset Password - Absensi QR</title>
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

        /* Background Animation */
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

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-30px, 20px) scale(1.05); }
        }
        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(25px, -20px) scale(1.08); }
        }

        .reset-wrapper {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s ease;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .reset-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 28px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            border: 1px solid var(--border);
        }

        .reset-card .icon {
            text-align: center;
            margin-bottom: 12px;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .reset-card h2 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
            text-align: center;
        }

        .reset-card p {
            font-size: 13px;
            color: var(--text-secondary);
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group { 
            margin-bottom: 16px;
            animation: fadeIn 0.6s ease 0.2s both;
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

        .input-wrapper { position: relative; }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 16px;
            transition: color 0.3s;
        }

        .input-wrapper:focus-within i { color: var(--primary); }

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
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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
            animation: fadeIn 0.6s ease 0.3s both;
        }

        .btn-submit:hover {
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
            transform: translateY(-2px);
        }

        .btn-submit:active { transform: scale(0.97); }

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

        @media (max-width: 480px) {
            .reset-card { padding: 20px; }
            .bg-circle-1 { width: 200px; height: 200px; }
            .bg-circle-2 { width: 150px; height: 150px; }
        }

        .btn-cancel {
    padding: 13px 20px;
    background: var(--white);
    border: 2px solid var(--border);
    border-radius: var(--radius-sm);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    color: var(--text-secondary);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.3s;
    animation: fadeIn 0.6s ease 0.3s both;
}

.btn-cancel:hover {
    background: #F3F4F6;
    border-color: #D1D5DB;
}
    </style>
</head>
<body>

    <!-- Background -->
    <div class="bg-circle bg-circle-1"></div>
    <div class="bg-circle bg-circle-2"></div>

    <div class="reset-wrapper">
        <div class="reset-card">
            <div class="icon">
                <i class="fas fa-lock" style="font-size:48px;color:#2563EB;"></i>
            </div>
            <h2>Reset Password</h2>
            <p>Masukkan password baru Anda</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

<form action="<?= base_url('auth/update-password') ?>" method="post">
    <?= csrf_field() ?>
    
    <input type="hidden" name="token" value="<?= esc($token ?? '') ?>">
    <input type="hidden" name="user_id" value="<?= esc($user_id ?? '') ?>">

    <div class="form-group">
        <label class="form-label">Password Baru</label>
        <div class="input-wrapper">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-input" 
                   placeholder="Minimal 6 karakter" required minlength="6" autofocus>
        </div>
    </div>

    <!-- ✅ Tombol Batal + Simpan -->
    <div style="display:flex;gap:12px;">
        <a href="<?= base_url('login') ?>" class="btn-cancel">
            <i class="fas fa-times"></i> Batal
        </a>
        <button type="submit" class="btn-submit" style="flex:1;">
            <i class="fas fa-save"></i> Simpan Password
        </button>
    </div>
</form>
        </div>
    </div>

</body>
</html>