<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $roleModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        helper(['form', 'url', 'security']);
    }
    
    // ==================== LOGIN ====================
    public function login()
    {
        if (session()->get('is_logged_in')) {
            $roleKode = strtolower(session()->get('role_kode') ?? '');
            return redirect()->to('/' . $roleKode);
        }
        
        $this->setPageTitle('Login');
        return $this->render('auth/login');
    }
    
    public function authenticate()
    {
        // ✅ 1. VALIDASI reCAPTCHA DULU
        $recaptcha = new \ReCaptcha\ReCaptcha(getenv('RECAPTCHA_SECRET_KEY'));
        $resp = $recaptcha->verify(
            $this->request->getPost('g-recaptcha-response'),
            $this->request->getIPAddress()
        );
        
        if (!$resp->isSuccess()) {
            return redirect()->back()->withInput()->with('error', '⚠️ Harap centang "Saya bukan robot"!');
        }

        // ✅ 2. Baru lanjut proses login
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $ip = $this->request->getIPAddress();
        
        $logData = [
            'username'   => $username,
            'ip_address' => $ip,
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
        ];

        $db = \Config\Database::connect();
        $blocked = $db->table('blocked_ips')->where('ip_address', $ip)->countAllResults();
        if ($blocked > 0) {
            $logData['status'] = 'blocked';
            $this->catatLoginLog($logData);
            return redirect()->back()->with('error', 'Akses Anda diblokir. Hubungi admin.');
        }

        $user = $this->userModel->findByUsername($username);
        
        if (!$user) {
            $logData['status'] = 'failed';
            $this->catatLoginLog($logData);
            $this->updateLoginAttempts($ip);
            return redirect()->back()->withInput()->with('error', 'Username tidak ditemukan');
        }
        
        if ($user['is_active'] == 0) {
            $logData['status'] = 'failed';
            $this->catatLoginLog($logData);
            return redirect()->back()->withInput()->with('error', 'Akun Anda telah dinonaktifkan');
        }
        
        if (!password_verify($password, $user['password'])) {
            $logData['status'] = 'failed';
            $this->catatLoginLog($logData);
            $this->updateLoginAttempts($ip);
            return redirect()->back()->withInput()->with('error', 'Password salah');
        }
        
        $role = $this->roleModel->find($user['role_id']);
        
        if (!$role) {
            return redirect()->back()->with('error', 'Role tidak ditemukan');
        }
        
        $logData['status'] = 'success';
        $this->catatLoginLog($logData);
        
        session()->remove('login_attempts_' . str_replace('.', '_', $ip));
        session()->regenerate(true);
        
        session()->set([
            'user_id'       => $user['id'],
            'username'      => $user['username'],
            'nama_lengkap'  => $user['nama_lengkap'],
            'email'         => $user['email'],
            'role_id'       => $user['role_id'],
            'role_kode'     => strtolower($role['kode']),
            'role_nama'     => $role['nama'],
            'is_logged_in'  => true,
            'last_activity' => time(),
        ]);
        
        logActivity('login', 'Login berhasil');
        
        $roleKode = strtolower($role['kode']);
        
        return redirect()->to('/' . $roleKode)
            ->with('success', "Selamat datang, {$user['nama_lengkap']}!");
    }
    
    // ==================== LOGOUT ====================
    public function logout()
    {
        logActivity('logout', 'Logout');
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah logout');
    }
    
    // ==================== HELPER ====================
    
    private function catatLoginLog($data)
    {
        try {
            $db = \Config\Database::connect();
            $db->table('login_logs')->insert([
                'username'   => $data['username'] ?? null,
                'ip_address' => $data['ip_address'] ?? null,
                'user_agent' => $data['user_agent'] ?? null,
                'status'     => $data['status'] ?? 'failed',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Gagal catat login log: ' . $e->getMessage());
        }
    }
    
    private function updateLoginAttempts($ip)
    {
        try {
            $key = 'login_attempts_' . str_replace('.', '_', $ip);
            $data = session()->get($key) ?? ['count' => 0, 'time' => time()];
            
            if (time() - $data['time'] > 900) {
                $data = ['count' => 1, 'time' => time()];
            } else {
                $data['count']++;
            }
            
            session()->set($key, $data);
            
            if ($data['count'] >= 10) {
                $db = \Config\Database::connect();
                $exist = $db->table('blocked_ips')->where('ip_address', $ip)->countAllResults();
                if ($exist == 0) {
                    $db->table('blocked_ips')->insert([
                        'ip_address' => $ip,
                        'reason'     => 'Auto blocked: ' . $data['count'] . ' failed attempts',
                        'created_at' => date('Y-m-d H:i:s'),
                    ]);
                    log_message('error', 'Auto blocked IP: ' . $ip);
                }
            }
        } catch (\Exception $e) {
            // Ignore
        }
    }
    
    // ==================== FORGOT PASSWORD ====================
    public function forgotPassword()
    {
        $this->setPageTitle('Lupa Password');
        return $this->render('auth/forgot_password');
    }

    public function sendResetLink()
    {
        $credential = $this->request->getPost('credential');

        if (empty($credential)) {
            return redirect()->back()->with('error', 'Username atau email wajib diisi');
        }

        $user = $this->userModel
            ->groupStart()
                ->where('username', $credential)
                ->orWhere('email', $credential)
            ->groupEnd()
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Username atau email tidak ditemukan');
        }

        $token = bin2hex(random_bytes(32));
        $this->userModel->update($user['id'], ['reset_token' => $token]);
        
        session()->set('reset_token', $token);
        session()->set('reset_user_id', $user['id']);
        session()->set('reset_expires', time() + 3600);

        logActivity('reset_password', 'Request reset password untuk: ' . $user['username']);

        return redirect()->back()->with('success', 
            'Link reset telah dikirim. <br><small>Dev: <a href="' . base_url('auth/reset-password/' . $token) . '">Klik di sini</a></small>');
    }

    public function resetPassword($token)
    {
        $user = $this->userModel->where('reset_token', $token)->first();
        
        if ($user) {
            $this->setPageTitle('Reset Password');
            return $this->render('auth/reset_password', [
                'token'   => $token,
                'user_id' => $user['id'],
            ]);
        }

        $storedToken = session()->get('reset_token');
        $expires = session()->get('reset_expires');

        if (!$storedToken || $storedToken !== $token || time() > $expires) {
            return redirect()->to('/login')->with('error', 'Token tidak valid atau sudah kadaluarsa');
        }

        $this->setPageTitle('Reset Password');
        return $this->render('auth/reset_password', [
            'token'   => $token,
            'user_id' => session()->get('reset_user_id'),
        ]);
    }

    public function updatePassword()
    {
        $token    = $this->request->getPost('token');
        $userId   = $this->request->getPost('user_id');
        $password = $this->request->getPost('password');

        if (empty($password) || strlen($password) < 8) {
            return redirect()->back()->with('error', 'Password minimal 8 karakter');
        }

        $errors = validatePasswordStrength($password);
        if (!empty($errors)) {
            return redirect()->back()->with('error', implode('<br>', $errors));
        }

        $user = $this->userModel->where('reset_token', $token)->first();
        
        if ($user && $user['id'] == $userId) {
            $this->userModel->update($userId, [
                'password'    => password_hash($password, PASSWORD_DEFAULT),
                'reset_token' => null,
            ]);
            
            session()->remove('reset_token');
            session()->remove('reset_user_id');
            session()->remove('reset_expires');
            
            logActivity('password_changed', 'Password berhasil direset');
            
            return redirect()->to('/login')->with('success', 'Password berhasil direset! Silakan login.');
        }

        $storedToken  = session()->get('reset_token');
        $storedUserId = session()->get('reset_user_id');

        if (!$storedToken || $storedToken !== $token || $storedUserId != $userId) {
            return redirect()->to('/login')->with('error', 'Token tidak valid atau sudah kadaluarsa');
        }

        $this->userModel->update($userId, [
            'password'    => password_hash($password, PASSWORD_DEFAULT),
            'reset_token' => null,
        ]);

        session()->remove('reset_token');
        session()->remove('reset_user_id');
        session()->remove('reset_expires');

        logActivity('password_changed', 'Password berhasil direset');

        return redirect()->to('/login')->with('success', 'Password berhasil direset! Silakan login.');
    }
}