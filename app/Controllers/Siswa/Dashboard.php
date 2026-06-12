<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\SiswaModel;
use App\Models\KelasSiswaModel;

class Dashboard extends BaseController
{
    protected $absensiModel;
    protected $siswaModel;
    protected $kelasSiswaModel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->siswaModel = new SiswaModel();
        $this->kelasSiswaModel = new KelasSiswaModel();
    }

    private function getSiswaData()
    {
        $userId = session()->get('user_id');
        $username = session()->get('username');

        $siswa = $this->siswaModel->where('user_id', $userId)->first();
        if ($siswa) return $siswa;

        $siswa = $this->siswaModel->where('nis', $username)->first();
        if ($siswa) return $siswa;

        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($userId);
        if ($user && !empty($user['email'])) {
            $siswa = $this->siswaModel->where('email', $user['email'])->first();
            if ($siswa) return $siswa;
        }

        return null;
    }

    // ==================== DASHBOARD ====================
    public function index()
    {
        if ($redirect = $this->requireRole('siswa')) {
            return $redirect;
        }

        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return $this->render('siswa/dashboard', [
                'title'          => 'Dashboard Siswa',
                'siswa'          => null,
                'nama_kelas'     => '-',
                'absensiHariIni' => null,
                'rekap'          => [],
                'riwayat'        => [],
            ]);
        }

        $tanggal = date('Y-m-d');
        $bulan = date('m');
        $tahun = date('Y');

        $absensiHariIni = $this->absensiModel
            ->where('siswa_id', $siswa['id'])
            ->where('tanggal', $tanggal)
            ->first();

        $riwayat = $this->absensiModel->getRiwayatSiswa($siswa['id'], $bulan, $tahun);

        // ✅ FIX: case-insensitive comparison
        $hadir = 0; $terlambat = 0; $izin = 0; $sakit = 0; $alpa = 0;
        if (!empty($riwayat)) {
            foreach ($riwayat as $r) {
                $label = strtolower($r['status_label'] ?? $r['label'] ?? '');
                $telat = $r['menit_keterlambatan'] ?? 0;
                if ($telat > 0) { $terlambat++; $hadir++; }
                elseif ($label === 'hadir') $hadir++;
                elseif ($label === 'izin') $izin++;
                elseif ($label === 'sakit') $sakit++;
                elseif ($label === 'alpa') $alpa++;
            }
        }

        $totalHari = count($riwayat ?? []);
        $persentase = $totalHari > 0 ? round(($hadir / $totalHari) * 100, 1) : 0;

        $riwayatTerakhir = array_slice($riwayat ?? [], 0, 5);

        $kelasAktif = $this->kelasSiswaModel
            ->select('kelas_siswa.*, CONCAT(kelas.tingkat," ",jurusan.singkatan," ",kelas.rombel) as nama_kelas')
            ->join('kelas', 'kelas.id = kelas_siswa.kelas_id')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->where('kelas_siswa.siswa_id', $siswa['id'])
            ->where('kelas_siswa.status', 'aktif')
            ->first();

        $this->setPageTitle('Dashboard Siswa');
        $this->addBreadcrumb('Dashboard');

        return $this->render('siswa/dashboard', [
            'title'          => 'Dashboard Siswa',
            'siswa'          => $siswa,
            'nama_kelas'     => $kelasAktif['nama_kelas'] ?? '-',
            'absensiHariIni' => $absensiHariIni,
            'rekap'          => [
                'hadir'      => $hadir,
                'izin'       => $izin,
                'sakit'      => $sakit,
                'alpa'       => $alpa,
                'terlambat'  => $terlambat,
                'total_hari' => $totalHari,
                'persentase' => $persentase,
            ],
            'riwayat' => $riwayatTerakhir,
        ]);
    }

    // ==================== RIWAYAT ====================
    public function riwayat()
    {
        if ($redirect = $this->requireRole('siswa')) {
            return $redirect;
        }

        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }

        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $riwayat = $this->absensiModel->getRiwayatSiswa($siswa['id'], $bulan, $tahun);

        $this->setPageTitle('Riwayat Absensi');
        $this->addBreadcrumb('Dashboard', '/siswa');
        $this->addBreadcrumb('Riwayat');

        return $this->render('siswa/riwayat', [
            'title'   => 'Riwayat Absensi',
            'riwayat' => $riwayat ?? [],
            'bulan'   => $bulan,
            'tahun'   => $tahun,
        ]);
    }

    // ==================== PROFIL ====================
    public function profil()
    {
        if ($redirect = $this->requireRole('siswa')) {
            return $redirect;
        }

        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }

        $kelasAktif = $this->kelasSiswaModel
            ->select('kelas_siswa.*, CONCAT(kelas.tingkat," ",jurusan.singkatan," ",kelas.rombel) as nama_kelas')
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

    // ==================== REKAP ====================
    public function rekap()
    {
        if ($redirect = $this->requireRole('siswa')) {
            return $redirect;
        }

        $siswa = $this->getSiswaData();
        if (!$siswa) {
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }

        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $riwayat = $this->absensiModel->getRiwayatSiswa($siswa['id'], $bulan, $tahun);

        $hadir = 0; $terlambat = 0; $izin = 0; $sakit = 0; $alpa = 0;
        if (!empty($riwayat)) {
            foreach ($riwayat as $r) {
                $label = strtolower($r['status_label'] ?? $r['label'] ?? '');
                $telat = $r['menit_keterlambatan'] ?? 0;
                if ($telat > 0) { $terlambat++; $hadir++; }
                elseif ($label === 'hadir') $hadir++;
                elseif ($label === 'izin') $izin++;
                elseif ($label === 'sakit') $sakit++;
                elseif ($label === 'alpa') $alpa++;
            }
        }

        $totalHari = count($riwayat ?? []);
        $persentase = $totalHari > 0 ? round(($hadir / $totalHari) * 100, 1) : 0;
        $bulanNama = date('F', mktime(0, 0, 0, $bulan, 1));

        $this->setPageTitle('Rekap Kehadiran');
        $this->addBreadcrumb('Dashboard', '/siswa');
        $this->addBreadcrumb('Rekap');

        return $this->render('siswa/rekap', [
            'title'     => 'Rekap Kehadiran',
            'rekap'     => [
                'hadir'      => $hadir,
                'izin'       => $izin,
                'sakit'      => $sakit,
                'alpa'       => $alpa,
                'terlambat'  => $terlambat,
                'total_hari' => $totalHari,
                'persentase' => $persentase,
            ],
            'bulan'     => $bulan,
            'bulanNama' => $bulanNama,
            'tahun'     => $tahun,
        ]);
    }
}