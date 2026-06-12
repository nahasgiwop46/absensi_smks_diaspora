<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    private $routeRoles = [
        'admin'     => ['ADMIN'],
        'guru'      => ['ADMIN', 'GURU', 'KEPSEK'],
        'siswa'     => ['ADMIN', 'GURU', 'KEPSEK', 'SISWA'],
        'kepsek'    => ['ADMIN', 'KEPSEK'],
        'all'       => ['ADMIN', 'GURU', 'KEPSEK', 'SISWA'],
    ];
    
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek login
        if (!session()->get('is_logged_in')) {
            return $this->redirectToLogin();
        }
        
        // Cek role
        if (!empty($arguments)) {
            $requiredRoles = [];
            
            foreach ($arguments as $arg) {
                if (isset($this->routeRoles[$arg])) {
                    $requiredRoles = array_merge($requiredRoles, $this->routeRoles[$arg]);
                } else {
                    $requiredRoles[] = strtoupper($arg);
                }
            }
            
            $requiredRoles = array_unique($requiredRoles);
            
            if (!$this->hasAccess($requiredRoles)) {
                return $this->accessDenied();
            }
        }
        
        // Cek user aktif
        if (!$this->isUserActive()) {
            session()->destroy();
            return redirect()->to('/login')->with('error', 'Akun Anda telah dinonaktifkan');
        }
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action
    }
    
    // ========================================
    // PRIVATE METHODS
    // ========================================
    
    private function redirectToLogin()
    {
        session()->set('redirect_url', current_url());
        
        if (service('request')->isAJAX()) {
            return service('response')->setStatusCode(401)->setJSON([
                'status'  => 'error',
                'message' => 'Session expired, silakan login kembali'
            ]);
        }
        
        return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
    }
    
    private function hasAccess(array $allowedRoles): bool
    {
        $userRole = session()->get('role_kode');
        return in_array(strtoupper($userRole ?? ''), $allowedRoles);
    }
    
    private function accessDenied()
    {
        if (service('request')->isAJAX()) {
            return service('response')->setStatusCode(403)->setJSON([
                'status'  => 'error',
                'message' => 'Anda tidak memiliki akses ke halaman ini'
            ]);
        }
        
        // ✅ FIX: redirect ke dashboard sesuai role, bukan /dashboard
        $userRole = strtolower(session()->get('role_kode') ?? '');
        return redirect()->to('/' . $userRole)
                         ->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
    }
    
    private function isUserActive(): bool
    {
        $userId = session()->get('user_id');
        if (!$userId) return false;
        
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        
        return $user && $user['is_active'] == 1;
    }
}