<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\NotifikasiSiswaModel;
use App\Models\KelasModel;
use App\Models\QrSessionsModel;
use App\Models\JadwalModel;
use App\Models\SemesterModel;

class Notifikasi extends BaseController
{
    protected $notifikasiModel;
    protected $kelasModel;
    protected $qrSessionsModel;
    protected $jadwalModel;
    protected $semesterModel;

    public function __construct()
    {
        $this->notifikasiModel = new NotifikasiSiswaModel();
        $this->kelasModel = new KelasModel();
        $this->qrSessionsModel = new QrSessionsModel();
        $this->jadwalModel = new JadwalModel();
        $this->semesterModel = new SemesterModel();
    }

    /**
     * Halaman kirim notifikasi
     */
    public function index()
    {
        $guruId = session()->get('user_id');
        $semesterAktif = $this->semesterModel->getAktif();

        $jadwalList = [];
        if ($semesterAktif) {
            $jadwalList = $this->jadwalModel
                ->select('jadwal.*, mata_pelajaran.nama as mapel, CONCAT(kelas.tingkat," ",jurusan.singkatan," ",kelas.rombel) as nama_kelas')
                ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal.mata_pelajaran_id')
                ->join('kelas', 'kelas.id = jadwal.kelas_id')
                ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                ->where('jadwal.guru_id', $guruId)
                ->where('jadwal.semester_id', $semesterAktif['id'])
                ->findAll();
        }

        $this->setPageTitle('Kirim Notifikasi');
        $this->addBreadcrumb('Dashboard', '/guru');
        $this->addBreadcrumb('Notifikasi');

        return $this->render('guru/notifikasi/index', [
            'jadwal_list' => $jadwalList,
        ]);
    }

    /**
     * Kirim notifikasi ke kelas
     */
    public function kirim()
    {
        $kelasId = $this->request->getPost('kelas_id');
        $judul = $this->request->getPost('judul');
        $pesan = $this->request->getPost('pesan');
        $tipe = $this->request->getPost('tipe') ?? 'info';

        if (!$kelasId || !$judul || !$pesan) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        $result = $this->notifikasiModel->kirimKeKelas($kelasId, null, $judul, $pesan, $tipe);

        if ($result) {
            return redirect()->to('/guru/notifikasi')->with('success', 'Notifikasi berhasil dikirim!');
        } else {
            return redirect()->back()->with('error', 'Gagal mengirim notifikasi.');
        }
    }
}