<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\KelasModel;
use App\Models\RekapAbsensiHarianModel;

class Laporan extends BaseController
{
    protected $absensiModel;
    protected $kelasModel;
    protected $rekapModel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->kelasModel   = new KelasModel();
        $this->rekapModel   = new RekapAbsensiHarianModel();
    }

    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $rekap   = $this->rekapModel->getRekapByTanggal($tanggal);

        $this->setPageTitle('Laporan Absensi');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Laporan');

        return $this->render('admin/laporan/index', [
            'rekap'   => $rekap,
            'tanggal' => $tanggal,
            'kelas'   => $this->kelasModel->getActiveKelas(),
        ]);
    }

   public function rekapHarian()
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $bulan = $this->request->getGet('bulan') ?? date('m');
    $tahun = $this->request->getGet('tahun') ?? date('Y');

    // ✅ Ambil data rekap per tanggal
    $jmlHari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
    $rekapHarian = [];
    
    for ($d = 1; $d <= $jmlHari; $d++) {
        $tgl = sprintf('%s-%s-%02d', $tahun, $bulan, $d);
        $rekapHarian[$tgl] = [
            'hadir'     => $this->absensiModel->getCountByStatus($tgl, 'HADIR'),
            'izin'      => $this->absensiModel->getCountByStatus($tgl, 'IZIN'),
            'sakit'     => $this->absensiModel->getCountByStatus($tgl, 'SAKIT'),
            'alpa'      => $this->absensiModel->getCountByStatus($tgl, 'ALPA'),
            'terlambat' => $this->absensiModel->getCountTerlambat($tgl),
        ];
    }

    // ✅ KIRIM VARIABLE $kelas KE VIEW
    $kelas = $this->kelasModel->getActiveKelas();

    $this->setPageTitle('Rekap Harian');
    $this->addBreadcrumb('Dashboard', '/admin');
    $this->addBreadcrumb('Laporan', '/admin/laporan');
    $this->addBreadcrumb('Rekap Harian');

    return $this->render('admin/laporan/rekap_harian', [
        'bulan'         => $bulan,
        'tahun'         => $tahun,
        'rekap_harian'  => $rekapHarian,
        'kelas'         => $kelas,           // ✅ TAMBAH INI
        'selected_kelas'=> null,             // ✅ TAMBAH INI
    ]);
}

    public function rekapBulanan()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $bulan   = $this->request->getGet('bulan') ?? date('m');
        $tahun   = $this->request->getGet('tahun') ?? date('Y');
        $kelasId = $this->request->getGet('kelas_id');

        $statistik = [];

        $this->setPageTitle('Rekap Bulanan');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Laporan', '/admin/laporan');
        $this->addBreadcrumb('Rekap Bulanan');

        return $this->render('admin/laporan/rekap_bulanan', [
            'bulan'          => $bulan,
            'tahun'          => $tahun,
            'kelas'          => $this->kelasModel->getActiveKelas(),
            'selected_kelas' => $kelasId,
            'statistik'      => $statistik,
        ]);
    }

    public function rekapSesi()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $kelasId = $this->request->getGet('kelas_id');

        $qrSessionModel = new \App\Models\QrSessionsModel();
        
        $builder = $qrSessionModel->select('
                qr_sessions.*,
                mata_pelajaran.nama as mapel_nama,
                users.nama_lengkap as guru_nama,
                CONCAT(kelas.tingkat," ",jurusan.singkatan," ",kelas.rombel) as nama_kelas
            ')
            ->join('kelas', 'kelas.id = qr_sessions.kelas_id', 'left')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->join('mata_pelajaran', 'mata_pelajaran.id = qr_sessions.mata_pelajaran_id', 'left')
            ->join('users', 'users.id = qr_sessions.dibuat_oleh', 'left')
            ->where('qr_sessions.tanggal', $tanggal)
            ->orderBy('qr_sessions.jam_mulai_absen', 'ASC');

        if ($kelasId) {
            $builder->where('qr_sessions.kelas_id', $kelasId);
        }

        $sesiList = $builder->findAll();

        $this->setPageTitle('Rekap Sesi Absen');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Laporan', '/admin/laporan');
        $this->addBreadcrumb('Rekap Sesi');

        return $this->render('admin/laporan/rekap_sesi', [
            'sesi_list'      => $sesiList,
            'tanggal'        => $tanggal,
            'kelas'          => $this->kelasModel->getActiveKelas(),
            'selected_kelas' => $kelasId,
        ]);
    }

 public function exportPdf()
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
    $kelasId = $this->request->getGet('kelas_id');

    $absensi = $this->absensiModel->getRekapByTanggal($tanggal, $kelasId);

    $html = view('admin/laporan/pdf_export', [
        'absensi' => $absensi,
        'tanggal' => $tanggal,
    ]);

    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream('laporan-absensi-' . $tanggal . '.pdf', ['Attachment' => 0]);
}
}