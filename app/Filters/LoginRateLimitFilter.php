<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoginRateLimitFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $ip = $request->getIPAddress();
        $key = 'login_attempts_' . str_replace('.', '_', $ip);
        
        $data = $session->get($key) ?? ['count' => 0, 'time' => time()];
        
        // Reset setiap 15 menit
        if (time() - $data['time'] > 900) {
            $data = ['count' => 0, 'time' => time()];
        }
        
        if ($data['count'] >= 5) {
            $waitTime = 900 - (time() - $data['time']);
            $minutes = ceil($waitTime / 60);
            session()->setFlashdata('error', "Terlalu banyak percobaan. Tunggu {$minutes} menit.");
            return redirect()->to('/login');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}