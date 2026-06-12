<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\MasterStatusAbsensiModel;

class Absensi extends BaseController
{
    protected $absensiModel;
    protected $kelasModel;
    protected $siswaModel;
    protected $statusAbsensiModel;

    public function __construct()
    {
        $this->absensiModel       = new AbsensiModel();
        $this->kelasModel         = new KelasModel();
        $this->siswaModel         = new SiswaModel();
        $this->statusAbsensiModel = new MasterStatusAbsensiModel();
    }

    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $kelasId = $this->request->getGet('kelas_id');

        $absensi = $this->absensiModel->getRekapByTanggal($tanggal, $kelasId);

        $totalHadir     = 0;
        $totalIzin      = 0;
        $totalSakit     = 0;
        $totalAlpa      = 0;
        $totalTerlambat = 0;

        foreach ($absensi as $a) {
            // ✅ Gunakan status_kode yang sudah ditambahkan di query model
            $statusKode = strtolower($a['status_kode'] ?? $a['status_label'] ?? '');
            
            switch ($statusKode) {
                case 'hadir':
                    $totalHadir++;
                    break;
                case 'izin':
                    $totalIzin++;
                    break;
                case 'sakit':
                    $totalSakit++;
                    break;
                case 'alpa':
                    $totalAlpa++;
                    break;
            }
            
            if (($a['menit_keterlambatan'] ?? 0) > 0) {
                $totalTerlambat++;
            }
        }

        $totalSiswa      = count($absensi);
        $persentaseHadir = $totalSiswa > 0 ? round(($totalHadir / $totalSiswa) * 100, 1) : 0;

        $this->setPageTitle('Data Absensi');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Absensi');

        return $this->render('admin/absensi/index', [
            'absensi'          => $absensi,
            'kelas'            => $this->kelasModel->getActiveKelas(),
            'tanggal'          => $tanggal,
            'selected_kelas'   => $kelasId,
            'total_hadir'      => $totalHadir,
            'total_izin'       => $totalIzin,
            'total_sakit'      => $totalSakit,
            'total_alpa'       => $totalAlpa,
            'total_terlambat'  => $totalTerlambat,
            'total_siswa'      => $totalSiswa,
            'persentase_hadir' => $persentaseHadir,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $kelasId = $this->request->getGet('kelas_id');
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        $siswa = [];
        if ($kelasId) {
            $siswa = $this->siswaModel->getSiswaByKelas($kelasId);
        }

        $this->setPageTitle('Input Absensi Manual');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Absensi', '/admin/absensi');
        $this->addBreadcrumb('Input');

        return $this->render('admin/absensi/form', [
            'kelas'          => $this->kelasModel->getActiveKelas(),
            'status'         => $this->statusAbsensiModel->findAll(),
            'siswa'          => $siswa,
            'tanggal'        => $tanggal,
            'selected_kelas' => $kelasId,
        ]);
    }

    public function store()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $tanggal     = $this->request->getPost('tanggal');
        $kelasId     = $this->request->getPost('kelas_id');
        $absensiData = $this->request->getPost('absensi');

        if (empty($absensiData)) {
            return redirect()->back()->with('error', 'Tidak ada data absensi yang dikirim.');
        }

        $data      = [];
        $petugasId = session()->get('user_id');

        foreach ($absensiData as $siswaId => $item) {
            $sudahAbsen = $this->absensiModel->cekSudahAbsen($siswaId, $tanggal);
            if ($sudahAbsen) continue;

            $data[] = [
                'siswa_id'            => $siswaId,
                'tanggal'             => $tanggal,
                'jam_absen'           => date('Y-m-d H:i:s'),
                'tipe_absensi'        => 'hadir',
                'status_id'           => $item['status_id'] ?? 1,
                'metode_absensi'      => 'manual_guru',
                'menit_keterlambatan' => $item['menit_keterlambatan'] ?? 0,
                'keterangan'          => $item['keterangan'] ?? null,
                'petugas_id'          => $petugasId,
            ];
        }

        if (!empty($data)) {
            $this->absensiModel->insertBatch($data);
            
            // ✅ HAPUS - Class NotifikasiWhatsApp belum ada
            // TODO: Integrasi WhatsApp Gateway nanti
            
            $this->setFlashSuccess(count($data) . ' data absensi berhasil disimpan!');
        } else {
            $this->setFlashWarning('Semua siswa sudah diabsen atau tidak ada data baru.');
        }

        return redirect()->to('/admin/absensi?tanggal=' . $tanggal . '&kelas_id=' . $kelasId);
    }

    public function detail($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $absensi = $this->absensiModel->find($id);
        if (!$absensi) {
            return redirect()->to('/admin/absensi')->with('error', 'Data absensi tidak ditemukan.');
        }

        $siswa   = $this->siswaModel->find($absensi['siswa_id']);
        $riwayat = $this->absensiModel->getRiwayatSiswa($absensi['siswa_id']);

        $this->setPageTitle('Detail Absensi');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Absensi', '/admin/absensi');
        $this->addBreadcrumb('Detail');

        return $this->render('admin/absensi/detail', [
            'absensi' => $absensi,
            'siswa'   => $siswa,
            'riwayat' => $riwayat,
        ]);
    }

    public function belumAbsen()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $kelasId = $this->request->getGet('kelas_id');

        $belumAbsen = [];
        if ($kelasId) {
            $belumAbsen = $this->absensiModel->getBelumAbsen($tanggal, $kelasId);
        }

        $this->setPageTitle('Siswa Belum Absen');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Absensi', '/admin/absensi');
        $this->addBreadcrumb('Belum Absen');

        return $this->render('admin/absensi/belum_absen', [
            'kelas'          => $this->kelasModel->getActiveKelas(),
            'belum_absen'    => $belumAbsen,
            'tanggal'        => $tanggal,
            'selected_kelas' => $kelasId,
        ]);
    }

    /**
     * Rekap absen per sesi QR (untuk monitoring real-time)
     */
    public function rekapSesi($qrSessionId)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $qrSessionModel = new \App\Models\QrSessionsModel();
        $session = $qrSessionModel->find($qrSessionId);
        
        if (!$session) {
            return redirect()->back()->with('error', 'Sesi absen tidak ditemukan.');
        }

        $rekap = $this->absensiModel->getRekapBySession($qrSessionId);

        $this->setPageTitle('Rekap Sesi Absen');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Absensi', '/admin/absensi');
        $this->addBreadcrumb('Rekap Sesi');

        return $this->render('admin/absensi/rekap_sesi', [
            'session' => $session,
            'rekap'   => $rekap,
        ]);
    }

    /**
     * Halaman monitoring absensi real-time
     */
    public function monitoring()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $qrSessionModel = new \App\Models\QrSessionsModel();
        $kelasId = $this->request->getGet('kelas_id');
        
        $sesiAktif = [];
        if ($kelasId) {
            $sesiAktif = $qrSessionModel->getAktifByKelas($kelasId);
        }

        $this->setPageTitle('Monitoring Absensi');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Absensi', '/admin/absensi');
        $this->addBreadcrumb('Monitoring');

        return $this->render('admin/absensi/monitoring', [
            'kelas'          => $this->kelasModel->getActiveKelas(),
            'sesi_aktif'     => $sesiAktif,
            'selected_kelas' => $kelasId,
        ]);
    }
    public function detailSesi($id)
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $absensi = $this->absensiModel
        ->select('absensi.*, master_status_absensi.label as status_label, master_status_absensi.warna as status_warna, master_status_absensi.kode as status_kode')
        ->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id', 'left')
        ->where('absensi.id', $id)
        ->where('absensi.deleted_at', null)
        ->first();

    if (!$absensi) {
        return redirect()->to('/admin/absensi')->with('error', 'Data absensi tidak ditemukan.');
    }

    $siswa   = $this->siswaModel->find($absensi['siswa_id']);
    $riwayat = $this->absensiModel->getRiwayatSiswa($absensi['siswa_id'], null, null);

    $this->setPageTitle('Detail Absensi');
    $this->addBreadcrumb('Dashboard', '/admin');
    $this->addBreadcrumb('Absensi', '/admin/absensi');
    $this->addBreadcrumb('Detail');

    return $this->render('admin/absensi/detail', [
        'absensi' => $absensi,
        'siswa'   => $siswa,
        'riwayat' => $riwayat,
    ]);
}
}