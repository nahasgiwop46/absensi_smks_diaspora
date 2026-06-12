<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalModel;
use App\Models\KelasModel;
use App\Models\MataPelajaranModel;
use App\Models\SemesterModel;
use App\Models\UserModel;

class Jadwal extends BaseController
{
    protected $jadwalModel;
    protected $kelasModel;
    protected $mapelModel;
    protected $semesterModel;
    protected $userModel;

    public function __construct()
    {
        $this->jadwalModel   = new JadwalModel();
        $this->kelasModel    = new KelasModel();
        $this->mapelModel    = new MataPelajaranModel();
        $this->semesterModel = new SemesterModel();
        $this->userModel     = new UserModel();
    }

    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $semesterAktif = $this->semesterModel->getAktif();
        $kelasId       = $this->request->getGet('kelas_id');
        $jadwal        = [];

        if ($kelasId && $semesterAktif) {
            $jadwal = $this->jadwalModel->getJadwalByKelas($kelasId, $semesterAktif['id']);
        }

        $this->setPageTitle('Jadwal Pelajaran');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Jadwal');

        return $this->render('admin/jadwal/index', [
            'kelas'         => $this->kelasModel->getActiveKelas(),
            'jadwal'        => $jadwal,
            'semester_aktif'=> $semesterAktif,
            'selected_kelas'=> $kelasId,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $semesterAktif = $this->semesterModel->getAktif();

        $this->setPageTitle('Tambah Jadwal');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Jadwal', '/admin/jadwal');
        $this->addBreadcrumb('Tambah');

        return $this->render('admin/jadwal/form', [
            'kelas'         => $this->kelasModel->getActiveKelas(),
            'mapel'         => $this->mapelModel->findAll(),
            'guru'          => $this->userModel->getGuru(['is_active' => true]),
            'semester_aktif'=> $semesterAktif,
            'hari_list'     => ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'],
        ]);
    }

    public function store()
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $data = $this->sanitizeInput($this->request->getPost());

    // Auto isi semester_id
    if (empty($data['semester_id'])) {
        $semesterAktif = $this->semesterModel->getAktif();
        if (!$semesterAktif) {
            return redirect()->back()->withInput()
                ->with('error', 'Tidak ada semester aktif.');
        }
        $data['semester_id'] = $semesterAktif['id'];
    }

    // Cek bentrok
    $bentrok = $this->jadwalModel->cekBentrok(
        $data['kelas_id'], $data['hari'],
        $data['jam_mulai'], $data['jam_selesai'], $data['semester_id']
    );

    if ($bentrok) {
        return redirect()->back()->withInput()
            ->with('error', 'Jadwal bentrok dengan mata pelajaran lain.');
    }

    // ✅ SIMPAN DATA
    if ($this->jadwalModel->insert($data)) {
        $this->setFlashSuccess('Jadwal berhasil ditambahkan!');
        return redirect()->to('/admin/jadwal?kelas_id=' . $data['kelas_id']);
    }

    return redirect()->back()->withInput()
        ->with('error', 'Gagal menambahkan jadwal.');
}

    // ... lanjut cek bentrok & insert
  public function edit($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $jadwal = $this->jadwalModel->find($id);
        if (!$jadwal) {
            return redirect()->to('/admin/jadwal')->with('error', 'Jadwal tidak ditemukan.');
        }

        $this->setPageTitle('Edit Jadwal');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Jadwal', '/admin/jadwal');
        $this->addBreadcrumb('Edit');

        return $this->render('admin/jadwal/form', [
            'jadwal'    => $jadwal,
            'kelas'     => $this->kelasModel->getActiveKelas(),
            'mapel'     => $this->mapelModel->findAll(),
            'guru'      => $this->userModel->getGuru(['is_active' => true]),
            'hari_list' => ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'],
        ]);
    }

    public function update($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $data = $this->sanitizeInput($this->request->getPost());

        $bentrok = $this->jadwalModel->cekBentrok(
            $data['kelas_id'], $data['hari'],
            $data['jam_mulai'], $data['jam_selesai'], $data['semester_id'], $id
        );

        if ($bentrok) {
            return redirect()->back()->withInput()
                ->with('error', 'Jadwal bentrok dengan mata pelajaran lain.');
        }

        if ($this->jadwalModel->update($id, $data)) {
            $this->setFlashSuccess('Jadwal berhasil diupdate!');
            return redirect()->to('/admin/jadwal?kelas_id=' . $data['kelas_id']);
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate jadwal.');
    }

    public function delete($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $jadwal  = $this->jadwalModel->find($id);
        $kelasId = $jadwal['kelas_id'] ?? null;

        if ($this->jadwalModel->delete($id)) {
            $this->setFlashSuccess('Jadwal berhasil dihapus!');
        } else {
            $this->setFlashError('Gagal menghapus jadwal.');
        }

        return redirect()->to('/admin/jadwal?kelas_id=' . $kelasId);
    }
}