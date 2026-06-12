<?php

if (!function_exists('detectSuspiciousActivity')) {
    function detectSuspiciousActivity($userId = null)
    {
        $db = \Config\Database::connect();
        $ip = service('request')->getIPAddress();
        
        // 1. Cek IP diblokir
        $blocked = $db->table('blocked_ips')->where('ip_address', $ip)->countAllResults();
        if ($blocked > 0) {
            log_message('warning', 'Blocked IP attempted access: ' . $ip);
            return true;
        }
        
        // 2. Cek >10x gagal dalam 1 jam
        $failedAttempts = $db->table('login_logs')
            ->where('ip_address', $ip)
            ->where('status', 'failed')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-1 hour')))
            ->countAllResults();
            
        if ($failedAttempts > 10) {
            // Auto block
            $db->table('blocked_ips')->insert([
                'ip_address' => $ip,
                'reason' => 'Auto blocked: ' . $failedAttempts . ' failed attempts',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            log_message('error', 'Auto blocked IP: ' . $ip);
            return true;
        }
        
        // 3. Cek SQL Injection pattern
        $uri = service('request')->getUri()->getPath();
        $query = service('request')->getUri()->getQuery();
        $dangerousPatterns = ['union', 'select', 'insert', 'update', 'delete', 'drop', 'exec', 'script', '<script', '<?php'];
        
        foreach ($dangerousPatterns as $pattern) {
            if (stripos($uri . $query, $pattern) !== false) {
                log_message('error', 'SQL Injection attempt from IP: ' . $ip . ' URL: ' . $uri);
                return true;
            }
        }
        
        return false;
    }
}

if (!function_exists('logActivity')) {
    function logActivity($action, $description = '')
    {
        $db = \Config\Database::connect();
        $db->table('activity_logs')->insert([
            'user_id'     => session()->get('user_id'),
            'action'      => $action,
            'description' => $description,
            'ip_address'  => service('request')->getIPAddress(),
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }
}

if (!function_exists('generateSecureToken')) {
    function generateSecureToken($length = 64)
    {
        return bin2hex(random_bytes($length));
    }
}

if (!function_exists('validatePasswordStrength')) {
    function validatePasswordStrength($password)
    {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Minimal 8 karakter';
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Harus mengandung huruf besar';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Harus mengandung huruf kecil';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Harus mengandung angka';
        }
        if (!preg_match('/[!@#$%^&*()]/', $password)) {
            $errors[] = 'Harus mengandung karakter spesial (!@#$%^&*)';
        }
        
        return $errors;
    }
}