<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\UserModel;
use App\Models\KelasModel;
use App\Models\AbsensiModel;
use App\Models\PengaturanSekolahModel;

class Dashboard extends BaseController
{
    protected $siswaModel;
    protected $userModel;
    protected $kelasModel;
    protected $absensiModel;
    protected $pengaturanModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->userModel = new UserModel();
        $this->kelasModel = new KelasModel();
        $this->absensiModel = new AbsensiModel();
        $this->pengaturanModel = new PengaturanSekolahModel();

        // Hanya admin yang bisa akses
        // Cek sederhana
if (session()->get('role_kode') !== 'admin') {
    header('Location: ' . base_url('login'));
    exit;
}
    }

      public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $tanggal = date('Y-m-d');

        $totalSiswa = $this->siswaModel->where('is_active', 1)->where('deleted_at', null)->countAllResults();
        $totalGuru  = $this->userModel->countByRole('GURU');
        $totalKelas = $this->kelasModel->where('is_active', 1)->countAllResults();
        $hadir = $this->absensiModel->getCountByStatus($tanggal, 'HADIR');
        $izin  = $this->absensiModel->getCountByStatus($tanggal, 'IZIN');
        $sakit = $this->absensiModel->getCountByStatus($tanggal, 'SAKIT');
        $alpa  = $this->absensiModel->getCountByStatus($tanggal, 'ALPA');
        $terlambat  = $this->absensiModel->getCountTerlambat($tanggal);

        // ========== TAMBAHAN BARU ==========
        $qrSessionModel = new \App\Models\QrSessionsModel();
        $sesiAktif = $qrSessionModel->where('is_active', 1)
                                    ->where('tanggal', $tanggal)
                                    ->countAllResults();
        // ===================================

        $data = [
            'total_siswa'          => $totalSiswa,
            'total_guru'           => $totalGuru,
            'total_kelas'          => $totalKelas,
            'hadir_hari_ini'       => $hadir,
            'izin_hari_ini'        => $izin,
            'sakit_hari_ini'       => $sakit,
            'alpa_hari_ini'        => $alpa,
            'tanggal'              => $tanggal,
            'terlambat_hari_ini'   => $terlambat,
            'sesi_aktif_hari_ini'  => $sesiAktif,   // ✅ TAMBAHAN BARU
        ];

        $this->setPageTitle('Dashboard Admin');
        $this->addBreadcrumb('Dashboard');
        return $this->render('admin/dashboard', $data);
    }
public function profil()
{
    $this->setPageTitle('Profil Saya');
    $this->addBreadcrumb('Dashboard', '/admin');
    $this->addBreadcrumb('Profil');

    return $this->render('admin/profil', [
        'user' => $this->userModel->find(session()->get('user_id')),
    ]);
}
 public function uploadFoto()
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $file = $this->request->getFile('foto');
    
    if (!$file->isValid() || $file->hasMoved()) {
        return redirect()->back()->with('error', 'File tidak valid.');
    }

    // Validasi
    $rules = [
        'foto' => 'uploaded[foto]|is_image[foto]|max_size[foto,1024]|mime_in[foto,image/jpg,image/jpeg,image/png]',
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->with('error', 'Foto harus JPG/PNG max 1MB.');
    }

    $userId = session()->get('user_id');
    
    // Hapus foto lama
    $user = $this->userModel->find($userId);
    if (!empty($user['foto'])) {
        $oldFile = FCPATH . 'uploads/foto/' . $user['foto'];
        if (file_exists($oldFile)) {
            unlink($oldFile);
        }
    }

    // Simpan foto baru
    $newName = 'user_' . $userId . '_' . time() . '.' . $file->getExtension();
    $file->move(FCPATH . 'uploads/foto', $newName);

    // Update database
    $this->userModel->update($userId, ['foto' => $newName]);

    return redirect()->to('/admin/profil')->with('success', 'Foto berhasil diupdate!');
} 

public function updateProfil()
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $userId = session()->get('user_id');
    $password = $this->request->getPost('password');
    $passwordConfirm = $this->request->getPost('password_confirm');

    // Validasi password
    if (!empty($password)) {
        if (strlen($password) < 6) {
            return redirect()->back()->with('error', 'Password minimal 6 karakter.');
        }
        if ($password !== $passwordConfirm) {
            return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
        }
    }

    $data = [
        'nama_lengkap' => $this->request->getPost('nama_lengkap'),
        'email'        => $this->request->getPost('email'),
        'no_hp'        => $this->request->getPost('no_hp'),
    ];

    if (!empty($password)) {
        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    $this->userModel->update($userId, $data);

    // Update session
    session()->set('nama_lengkap', $data['nama_lengkap']);

    return redirect()->to('/admin/profil')->with('success', 'Profil berhasil diupdate!');
}
}