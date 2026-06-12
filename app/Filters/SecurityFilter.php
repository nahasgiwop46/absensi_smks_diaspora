<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SecurityFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('security');
        
        // ✅ Deteksi aktivitas mencurigakan
        if (detectSuspiciousActivity()) {
            return redirect()->to('/blocked')->with('error', 'Akses ditolak.');
        }
        
        // ✅ Rate limiting
        $ip = $request->getIPAddress();
        $session = session();
        $key = 'rate_limit_' . $ip;
        $requests = $session->get($key) ?? ['count' => 0, 'time' => time()];
        
        if (time() - $requests['time'] > 60) {
            $requests = ['count' => 1, 'time' => time()];
        } else {
            $requests['count']++;
        }
        
        $session->set($key, $requests);
        
        if ($requests['count'] > 100) {
            log_message('error', 'Rate limit exceeded: ' . $ip);
            return redirect()->to('/blocked')->with('error', 'Terlalu banyak request.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Security headers
        $response->setHeader('X-Content-Type-Options', 'nosniff');
        $response->setHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->setHeader('X-XSS-Protection', '1; mode=block');
        $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->setHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }
}