<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasSiswaModel;

class Profil extends BaseController
{
    protected $siswaModel;
    protected $kelasSiswaModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasSiswaModel = new KelasSiswaModel();
    }

    /**
     * Halaman profil siswa
     */
    public function index()
    {
        if ($redirect = $this->requireRole('siswa')) {
            return $redirect;
        }

        $userId = session()->get('user_id');
        $username = session()->get('username');

        // Cari data siswa via user_id dulu, fallback ke nis
        $siswa = $this->siswaModel->where('user_id', $userId)->first();
        if (!$siswa) {
            $siswa = $this->siswaModel->where('nis', $username)->first();
        }

        if (!$siswa) {
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }

        // Ambil kelas aktif
        $kelasAktif = $this->kelasSiswaModel
            ->select('kelas_siswa.*, CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas')
            ->join('kelas', 'kelas.id = kelas_siswa.kelas_id')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->where('kelas_siswa.siswa_id', $siswa['id'])
            ->where('kelas_siswa.status', 'aktif')
            ->first();

        $this->setPageTitle('Profil Saya');
        $this->addBreadcrumb('Dashboard', '/siswa');
        $this->addBreadcrumb('Profil');

        return $this->render('siswa/profil', [
            'title'      => 'Profil Saya',
            'siswa'      => $siswa,
            'nama_kelas' => $kelasAktif['nama_kelas'] ?? '-',
        ]);
    }
}