<?php

namespace App\Controllers;

class Izin extends BaseController
{
    public function form($token)
    {
        $db = \Config\Database::connect();
        $siswa = $db->table('siswa')->where('token_izin', $token)->get()->getRowArray();
        
        if (!$siswa) {
            return redirect()->to('/')->with('error', 'Token tidak valid.');
        }
        
        return view('izin/form', ['siswa' => $siswa]);
    }
    
    public function kirim()
    {
        $token = $this->request->getPost('token');
        $alasan = $this->request->getPost('alasan');
        $keterangan = $this->request->getPost('keterangan');
        
        $db = \Config\Database::connect();
        $siswa = $db->table('siswa')->where('token_izin', $token)->get()->getRowArray();
        
        if (!$siswa) {
            return redirect()->to('/')->with('error', 'Token tidak valid.');
        }
        
        // Simpan absen IZIN
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jayapura'));
        
        $db->table('absensi')->insert([
            'siswa_id'      => $siswa['id'],
            'tanggal'       => $now->format('Y-m-d'),
            'jam_absen'     => $now->format('Y-m-d H:i:s'),
            'tipe_absensi'  => 'izin',
            'status_id'     => 3, // Izin
            'metode_absensi'=> 'whatsapp',
            'keterangan'    => $alasan . ': ' . $keterangan,
        ]);
        
        // Hapus token
        $db->table('siswa')->where('id', $siswa['id'])->update(['token_izin' => null]);
        
        return view('izin/sukses', ['siswa' => $siswa]);
    }
}