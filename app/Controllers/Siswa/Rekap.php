<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\AbsensiModel;
use App\Models\KelasSiswaModel;

class Rekap extends BaseController
{
    protected $siswaModel;
    protected $absensiModel;
    protected $kelasSiswaModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->absensiModel = new AbsensiModel();
        $this->kelasSiswaModel = new KelasSiswaModel();
    }

    /**
     * Ambil ID siswa dari session
     */
    private function getSiswaId()
    {
        $userId = session()->get('user_id');
        $username = session()->get('username');

        $siswa = $this->siswaModel->where('user_id', $userId)->first();
        if ($siswa) return $siswa['id'];

        $siswa = $this->siswaModel->where('nis', $username)->first();
        if ($siswa) return $siswa['id'];

        return null;
    }

    /**
     * Halaman rekap absensi
     */
    public function index()
    {
        if ($redirect = $this->requireRole('siswa')) {
            return $redirect;
        }

        $siswaId = $this->getSiswaId();
        
        if (!$siswaId) {
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }

        $siswa = $this->siswaModel->find($siswaId);

        // Filter bulan dan tahun
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil riwayat absensi
        $riwayat = $this->absensiModel->getRiwayatSiswa($siswaId, $bulan, $tahun);

        // Hitung statistik
        $hadir = 0;
        $terlambat = 0;
        $izin = 0;
        $sakit = 0;
        $alpa = 0;

        foreach ($riwayat as $item) {
            $label = strtolower($item['status_label'] ?? $item['label'] ?? '');
            $telat = $item['menit_keterlambatan'] ?? 0;

            if ($telat > 0) {
                $terlambat++;
                $hadir++;
            } elseif ($label === 'hadir') {
                $hadir++;
            } elseif ($label === 'izin') {
                $izin++;
            } elseif ($label === 'sakit') {
                $sakit++;
            } elseif ($label === 'alpa') {
                $alpa++;
            }
        }

        $totalHari = count($riwayat);
        $persentase = $totalHari > 0 ? round(($hadir / $totalHari) * 100, 1) : 0;
        $bulanNama = date('F', mktime(0, 0, 0, $bulan, 1));

        $this->setPageTitle('Rekap Absensi');
        $this->addBreadcrumb('Dashboard', '/siswa');
        $this->addBreadcrumb('Rekap');

        return $this->render('siswa/rekap', [
            'title'     => 'Rekap Absensi',
            'siswa'     => $siswa,
            'riwayat'   => $riwayat,
            'bulan'     => $bulan,
            'bulanNama' => $bulanNama,
            'tahun'     => $tahun,
            'rekap'     => [
                'hadir'      => $hadir,
                'izin'       => $izin,
                'sakit'      => $sakit,
                'alpa'       => $alpa,
                'terlambat'  => $terlambat,
                'total_hari' => $totalHari,
                'persentase' => $persentase,
            ],
        ]);
    }
}