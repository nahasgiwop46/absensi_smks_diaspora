<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Keamanan extends BaseController
{
    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $db = \Config\Database::connect();
        $today = date('Y-m-d');
        
        // Statistik
        $totalFailedToday = $db->table('login_logs')
            ->where('status', 'failed')
            ->where('DATE(created_at)', $today)
            ->countAllResults();
            
        $totalBlocked = $db->table('blocked_ips')->countAllResults();
        
        // IP mencurigakan (>5x gagal hari ini)
        $suspiciousIPs = $db->table('login_logs')
            ->select('ip_address, COUNT(*) as total, MAX(created_at) as last_attempt, MAX(username) as last_username, MAX(user_agent) as user_agent')
            ->where('status', 'failed')
            ->where('DATE(created_at)', $today)
            ->groupBy('ip_address')
            ->having('total >=', 5)
            ->orderBy('total', 'DESC')
            ->get()
            ->getResultArray();
            
        // Semua log hari ini
        $logs = $db->table('login_logs')
            ->where('DATE(created_at)', $today)
            ->orderBy('created_at', 'DESC')
            ->limit(200)
            ->get()
            ->getResultArray();
            
        // Blocked IPs
        $blockedIPs = $db->table('blocked_ips')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();
            
        // Activity logs
        $activities = $db->table('activity_logs')
            ->select('activity_logs.*, users.nama_lengkap')
            ->join('users', 'users.id = activity_logs.user_id', 'left')
            ->orderBy('created_at', 'DESC')
            ->limit(100)
            ->get()
            ->getResultArray();

        $this->setPageTitle('Keamanan Sistem');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Keamanan');

        return $this->render('admin/keamanan/index', [
            'total_failed'   => $totalFailedToday,
            'total_blocked'  => $totalBlocked,
            'suspicious_ips' => $suspiciousIPs,
            'blocked_ips'    => $blockedIPs,
            'logs'           => $logs,
            'activities'     => $activities,
        ]);
    }
    
  public function blokirIP($ip)
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }
    
    $db = \Config\Database::connect();
    
    // ✅ Cek apakah sudah diblokir
    $exist = $db->table('blocked_ips')->where('ip_address', $ip)->get()->getRowArray();
    
    if ($exist) {
        return redirect()->back()->with('warning', 'IP ' . $ip . ' sudah diblokir sebelumnya.');
    }
    
    $db->table('blocked_ips')->insert([
        'ip_address' => $ip,
        'reason'     => 'Manual block by admin',
        'blocked_by' => session()->get('user_id'),
        'created_at' => date('Y-m-d H:i:s'),
    ]);
    
    logActivity('block_ip', 'Memblokir IP: ' . $ip);
    
    return redirect()->back()->with('success', 'IP ' . $ip . ' berhasil diblokir!');
}
    public function bukaBlokir($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }
        
        $db = \Config\Database::connect();
        $db->table('blocked_ips')->where('id', $id)->delete();
        
        logActivity('unblock_ip', 'Membuka blokir IP ID: ' . $id);
        
        return redirect()->back()->with('success', 'IP berhasil dibuka!');
    }
    
    public function blokirSemua()
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }
    
    $db = \Config\Database::connect();
    $today = date('Y-m-d');
    
    $ips = $db->table('login_logs')
        ->select('ip_address')
        ->where('status', 'failed')
        ->where('DATE(created_at)', $today)
        ->groupBy('ip_address')
        ->having('COUNT(*) >=', 5)
        ->get()
        ->getResultArray();
        
    $count = 0;
    foreach ($ips as $ip) {
        // ✅ Cek dulu sebelum insert
        $exist = $db->table('blocked_ips')->where('ip_address', $ip['ip_address'])->countAllResults();
        
        if ($exist == 0) {
            $db->table('blocked_ips')->insert([
                'ip_address' => $ip['ip_address'],
                'reason'     => 'Auto block (batch)',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
            $count++;
        }
    }
    
    return redirect()->back()->with('success', $count . ' IP berhasil diblokir! (dilewati yang sudah ada)');
}
}