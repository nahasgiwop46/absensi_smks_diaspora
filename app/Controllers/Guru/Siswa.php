<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\KelasSiswaModel;
use App\Models\JurusanModel;
use App\Models\JadwalModel;
use App\Models\SemesterModel;

class Siswa extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;
    protected $kelasSiswaModel;
    protected $jurusanModel;
    protected $jadwalModel;
    protected $semesterModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->kelasSiswaModel = new KelasSiswaModel();
        $this->jurusanModel = new JurusanModel();
        $this->jadwalModel = new JadwalModel();
        $this->semesterModel = new SemesterModel();
    }

    /**
     * Daftar siswa yang diajar
     */
    public function index()
    {
        $guruId = session()->get('user_id');
        $semesterAktif = $this->semesterModel->getAktif();

        $kelasIds = [];
        if ($semesterAktif) {
            $jadwalKelas = $this->jadwalModel
                ->select('kelas_id')
                ->where('guru_id', $guruId)
                ->where('semester_id', $semesterAktif['id'])
                ->distinct()
                ->findAll();
            $kelasIds = array_column($jadwalKelas, 'kelas_id');
        }

        $siswa = [];
        if (!empty($kelasIds)) {
            $siswa = $this->kelasSiswaModel
                ->select('siswa.*, kelas.tingkat, jurusan.singkatan as jurusan_singkatan, kelas.rombel')
                ->join('siswa', 'siswa.id = kelas_siswa.siswa_id')
                ->join('kelas', 'kelas.id = kelas_siswa.kelas_id')
                ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                ->whereIn('kelas_siswa.kelas_id', $kelasIds)
                ->where('kelas_siswa.status', 'aktif')
                ->where('siswa.deleted_at', null)
                ->orderBy('siswa.nama_lengkap', 'ASC')
                ->findAll();
        }

        $kelasList = !empty($kelasIds) 
            ? $this->kelasModel->whereIn('id', $kelasIds)->findAll() 
            : [];

        return $this->render('guru/siswa/index', [
            'title' => 'Data Siswa',
            'siswa' => $siswa,
            'kelas' => $kelasList,
        ]);
    }

    /**
     * Detail siswa
     */
    public function detail($id)
    {
        $siswa = $this->siswaModel->find($id);
        
        if (!$siswa) {
            return redirect()->to('/guru/siswa')->with('error', 'Siswa tidak ditemukan');
        }

        $this->setPageTitle('Detail Siswa');
        $this->addBreadcrumb('Dashboard', '/guru');
        $this->addBreadcrumb('Siswa', '/guru/siswa');
        $this->addBreadcrumb('Detail');

        return $this->render('guru/siswa/detail', [
            'title' => 'Detail Siswa',
            'siswa' => $siswa,
        ]);
    }

    // ========== TAMBAHAN (OPSIONAL) ==========
    /**
     * Riwayat absensi siswa
     */
    public function riwayat($id)
    {
        $siswa = $this->siswaModel->find($id);
        
        if (!$siswa) {
            return redirect()->to('/guru/siswa')->with('error', 'Siswa tidak ditemukan');
        }

        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $absensiModel = new \App\Models\AbsensiModel();
        $riwayat = $absensiModel->getRiwayatSiswa($id, $bulan, $tahun);

        $this->setPageTitle('Riwayat Absensi - ' . $siswa['nama_lengkap']);
        $this->addBreadcrumb('Dashboard', '/guru');
        $this->addBreadcrumb('Siswa', '/guru/siswa');
        $this->addBreadcrumb('Riwayat');

        return $this->render('guru/siswa/riwayat', [
            'title'   => 'Riwayat Absensi',
            'siswa'   => $siswa,
            'riwayat' => $riwayat,
            'bulan'   => $bulan,
            'tahun'   => $tahun,
        ]);
    }
}