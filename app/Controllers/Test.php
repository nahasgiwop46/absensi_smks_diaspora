<?php

namespace App\Controllers;

class Test extends BaseController
{
    public function siswa()
    {
        $db = \Config\Database::connect();
        
        // Ambil semua QR session
        $qr = $db->table('qr_sessions')->get()->getResultArray();
        
        // Ambil semua kelas_siswa
        $ks = $db->table('kelas_siswa')
            ->select('kelas_siswa.*, siswa.nama_lengkap, kelas.tingkat, kelas.rombel')
            ->join('siswa', 'siswa.id = kelas_siswa.siswa_id')
            ->join('kelas', 'kelas.id = kelas_siswa.kelas_id')
            ->where('kelas_siswa.status', 'aktif')
            ->get()
            ->getResultArray();
        
        echo "<h3>QR Sessions:</h3><pre>";
        print_r($qr);
        echo "</pre>";
        
        echo "<h3>Kelas Siswa Aktif:</h3><pre>";
        print_r($ks);
        echo "</pre>";
    }
}