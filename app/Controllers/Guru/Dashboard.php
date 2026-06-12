<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\JadwalModel;
use App\Models\SemesterModel;
use App\Models\KelasSiswaModel;

class Dashboard extends BaseController
{
    protected $absensiModel;
    protected $jadwalModel;
    protected $semesterModel;
    protected $kelasSiswaModel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->jadwalModel = new JadwalModel();
        $this->semesterModel = new SemesterModel();
        $this->kelasSiswaModel = new KelasSiswaModel();

        if (session()->get('role_kode') !== 'guru') {
    header('Location: ' . base_url('login'));
    exit;  // ✅
}
    }

    public function index()
    {
        $guruId = session()->get('user_id');
        $tanggal = date('Y-m-d');
        $semesterAktif = $this->semesterModel->getAktif();

        // Hari ini
        $hari = strtolower(date('l'));
        $hariMap = ['monday'=>'senin','tuesday'=>'selasa','wednesday'=>'rabu','thursday'=>'kamis','friday'=>'jumat','saturday'=>'sabtu'];
        $hariIni = $hariMap[$hari] ?? 'senin';

        // Jadwal hari ini
        $jadwal = [];
        if ($semesterAktif) {
            $jadwal = $this->jadwalModel
                ->select('jadwal.*, mata_pelajaran.nama as mapel, CONCAT(kelas.tingkat," ",jurusan.singkatan," ",kelas.rombel) as nama_kelas, kelas.id as kelas_id')
                ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal.mata_pelajaran_id')
                ->join('kelas', 'kelas.id = jadwal.kelas_id')
                ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                ->where('jadwal.guru_id', $guruId)
                ->where('jadwal.hari', $hariIni)
                ->where('jadwal.semester_id', $semesterAktif['id'])
                ->orderBy('jadwal.jam_mulai', 'ASC')
                ->findAll();
        }

        // Statistik absensi hari ini
        $hadir = $this->absensiModel->getCountByStatus($tanggal, 'hadir');
        $izin = $this->absensiModel->getCountByStatus($tanggal, 'izin');
        $sakit = $this->absensiModel->getCountByStatus($tanggal, 'sakit');
        $alpa = $this->absensiModel->getCountByStatus($tanggal, 'alpa');
        $terlambat = $this->absensiModel->getCountTerlambat($tanggal);
        $totalSiswa = $this->kelasSiswaModel->where('status', 'aktif')->countAllResults();
        $persentase = $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100) : 0;

        // ========== TAMBAHAN: Sesi absen aktif ==========
        $qrSessionModel = new \App\Models\QrSessionsModel();
        $sesiAktif = $qrSessionModel->where('dibuat_oleh', $guruId)
                                    ->where('is_active', 1)
                                    ->where('tanggal', $tanggal)
                                    ->countAllResults();
        // =================================================

        // Absensi terbaru
        $absensi = $this->absensiModel
            ->select('absensi.*, siswa.nis, siswa.nama_lengkap, master_status_absensi.label as status_label')
            ->join('siswa', 'siswa.id = absensi.siswa_id')
            ->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id')
            ->where('absensi.petugas_id', $guruId)
            ->where('absensi.tanggal', $tanggal)
            ->orderBy('absensi.jam_absen', 'DESC')
            ->limit(10)
            ->findAll();

        $this->setPageTitle('Dashboard Guru');
        $this->addBreadcrumb('Dashboard');

        return $this->render('guru/dashboard', [
            'title'       => 'Dashboard Guru',
            'jadwal'      => $jadwal,
            'absensi'     => $absensi,
            'hadir'       => $hadir,
            'izin'        => $izin,
            'sakit'       => $sakit,
            'alpa'        => $alpa,
            'terlambat'   => $terlambat,
            'totalSiswa'  => $totalSiswa,
            'persentase'  => $persentase,
            'tanggal'     => $tanggal,
            'sesi_aktif'  => $sesiAktif,  // ✅ TAMBAHAN
        ]);
    }

    public function profil()
    {
        $userModel = new \App\Models\UserModel();
        
        $this->setPageTitle('Profil Saya');
        $this->addBreadcrumb('Dashboard', '/guru');
        $this->addBreadcrumb('Profil');

        return $this->render('guru/profil', [
            'user' => $userModel->find(session()->get('user_id')),
        ]);
    }
}