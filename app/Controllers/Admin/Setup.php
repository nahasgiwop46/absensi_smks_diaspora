<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TahunAjaranModel;
use App\Models\SemesterModel;
use App\Models\JurusanModel;
use App\Models\MataPelajaranModel;
use App\Models\KelasModel;
use App\Models\JadwalModel;
use App\Models\UserModel;

class Setup extends BaseController
{
    protected $tahunAjaranModel;
    protected $semesterModel;
    protected $jurusanModel;
    protected $mapelModel;
    protected $kelasModel;
    protected $jadwalModel;
    protected $userModel;

    public function __construct()
    {
        $this->tahunAjaranModel = new TahunAjaranModel();
        $this->semesterModel    = new SemesterModel();
        $this->jurusanModel     = new JurusanModel();
        $this->mapelModel       = new MataPelajaranModel();
        $this->kelasModel       = new KelasModel();
        $this->jadwalModel      = new JadwalModel();
        $this->userModel        = new UserModel();
    }

 public function index()
{
    $step = (int)($this->request->getGet('step') ?? 1);

    $data = [
        'step'              => $step,
        'tahun_ajaran_aktif'=> $this->tahunAjaranModel->getAktif(),
        'semester_aktif'    => $this->semesterModel->getAktif(),
        'jurusan_list'      => $this->jurusanModel->findAll(),
        'mapel_list'        => $this->mapelModel->findAll(),
        'kelas_list'        => $this->kelasModel->findAll(),
        'jadwal_list'       => $this->jadwalModel->findAll(),
    ];

    $this->setPageTitle('Setup Wizard');
    $this->addBreadcrumb('Dashboard', '/admin');
    $this->addBreadcrumb('Setup Wizard');

    // ✅ PAKAI render(), BUKAN view()
    return $this->render('admin/setup/wizard', $data);
}
}