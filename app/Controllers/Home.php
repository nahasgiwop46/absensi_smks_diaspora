<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\UserModel;
use App\Models\KelasModel;
use App\Models\PengaturanSekolahModel;

class Home extends BaseController
{
    protected $siswaModel;
    protected $userModel;
    protected $kelasModel;
    protected $pengaturanModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->userModel = new UserModel();
        $this->kelasModel = new KelasModel();
        $this->pengaturanModel = new PengaturanSekolahModel();
    }

    public function index()
    {
        // Kalau sudah login, redirect ke dashboard
        if (session()->get('is_logged_in')) {
            $role = strtolower(session()->get('role_kode') ?? '');
            return redirect()->to('/' . $role);
        }

        // Data real dari database
        $totalSiswa = $this->siswaModel->where('is_active', 1)->where('deleted_at', null)->countAllResults();
        $totalGuru = $this->userModel->where('role_id', 2)->where('is_active', 1)->countAllResults();
        $totalKelas = $this->kelasModel->where('is_active', 1)->countAllResults();
        $pengaturan = $this->pengaturanModel->getPengaturan();

        $data = [
            'title'         => $pengaturan['nama_sekolah'] ?? 'SMKS Diaspora',
            'pengaturan'    => $pengaturan,
            'total_siswa'   => $totalSiswa,
            'total_guru'    => $totalGuru,
            'total_kelas'   => $totalKelas,
        ];

        return view('home/index', $data);
    }
}