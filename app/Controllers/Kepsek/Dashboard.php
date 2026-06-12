<?php

namespace App\Controllers\Kepsek;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\RekapAbsensiHarianModel;
use App\Models\KelasSiswaModel;

class Dashboard extends BaseController
{
    protected $absensiModel;
    protected $siswaModel;
    protected $kelasModel;
    protected $rekapModel;
    protected $kelasSiswaModel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->siswaModel = new SiswaModel();
        $this->kelasModel = new KelasModel();
        $this->rekapModel = new RekapAbsensiHarianModel();
        $this->kelasSiswaModel = new KelasSiswaModel();

        if (session()->get('role_kode') !== 'kepsek') {
            return redirect()->to('/login');
        }
    }

    public function index()
    {
        $tanggal = date('Y-m-d');

        // Statistik
        $totalSiswa = $this->siswaModel->countActive();
        $hadir = $this->absensiModel->getCountByStatus($tanggal, 'hadir');
        $persentase = $totalSiswa > 0 ? round(($hadir / $totalSiswa) * 100, 1) : 0;

        // Siswa bermasalah (alpa > 3 bulan ini)
        $bulan = date('m');
        $tahun = date('Y');
        $bermasalah = $this->absensiModel
            ->select('siswa_id, COUNT(*) as total_alpa')
            ->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id')
            ->where('master_status_absensi.kode', 'alpa')
            ->where('MONTH(absensi.tanggal)', $bulan)
            ->where('YEAR(absensi.tanggal)', $tahun)
            ->groupBy('siswa_id')
            ->having('total_alpa >=', 3)
            ->countAllResults();

        // Kehadiran per kelas
        $kelasList = $this->kelasModel->getActiveKelas();
        $perKelas = [];
        foreach ($kelasList as $k) {
            $totalKelas = $this->kelasSiswaModel
                ->where('kelas_id', $k['id'])
                ->where('status', 'aktif')
                ->countAllResults();
            $hadirKelas = $this->absensiModel->getCountByStatus($tanggal, 'hadir');
            $persenKelas = $totalKelas > 0 ? round(($hadirKelas / $totalKelas) * 100, 1) : 0;
            $perKelas[] = [
                'nama'  => $k['tingkat'] . ' ' . ($k['jurusan_singkatan'] ?? '') . ' ' . $k['rombel'],
                'total' => $totalKelas,
                'hadir' => $hadirKelas,
                'persen'=> $persenKelas,
            ];
        }

        $data = [
            'title'         => 'Dashboard Kepala Sekolah',
            'total_siswa'   => $totalSiswa,
            'persentase'    => $persentase,
            'rata_rata'     => $persentase,
            'bermasalah'    => $bermasalah,
            'per_kelas'     => $perKelas,
        ];

        return view('kepsek/dashboard', $data);
    }

    public function rekap()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $rekap = $this->rekapModel->getRekapByTanggal($tanggal);

        return view('kepsek/rekap', [
            'title'   => 'Rekap Harian',
            'rekap'   => $rekap,
            'tanggal' => $tanggal,
        ]);
    }

    public function perKelas()
    {
        $kelasId = $this->request->getGet('kelas_id');
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        $absensi = $kelasId ? $this->absensiModel->getRekapByTanggal($tanggal, $kelasId) : [];
        $kelasList = $this->kelasModel->getActiveKelas();

        return view('kepsek/per_kelas', [
            'title'      => 'Detail Per Kelas',
            'kelas'      => $kelasList,
            'absensi'    => $absensi,
            'selected'   => $kelasId,
            'tanggal'    => $tanggal,
        ]);
    }

    public function bermasalah()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        $siswaBermasalah = $this->absensiModel
            ->select('siswa.nis, siswa.nama_lengkap, COUNT(absensi.id) as total_alpa, 
                      CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas')
            ->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id')
            ->join('siswa', 'siswa.id = absensi.siswa_id')
            ->join('kelas_siswa', 'kelas_siswa.siswa_id = absensi.siswa_id AND kelas_siswa.status = "aktif"', 'left')
            ->join('kelas', 'kelas.id = kelas_siswa.kelas_id', 'left')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->where('master_status_absensi.kode', 'alpa')
            ->where('MONTH(absensi.tanggal)', $bulan)
            ->where('YEAR(absensi.tanggal)', $tahun)
            ->groupBy('absensi.siswa_id')
            ->having('total_alpa >=', 3)
            ->orderBy('total_alpa', 'DESC')
            ->findAll();

        return view('kepsek/bermasalah', [
            'title'   => 'Siswa Bermasalah',
            'siswa'   => $siswaBermasalah,
            'bulan'   => $bulan,
            'tahun'   => $tahun,
        ]);
    }
}