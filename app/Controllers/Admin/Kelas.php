<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\JurusanModel;
use App\Models\KelasSiswaModel;
use App\Models\TahunAjaranModel;

class Kelas extends BaseController
{
    protected $kelasModel;
    protected $jurusanModel;
    protected $kelasSiswaModel;
    protected $tahunAjaranModel;

    public function __construct()
    {
        $this->kelasModel       = new KelasModel();
        $this->jurusanModel     = new JurusanModel();
        $this->kelasSiswaModel  = new KelasSiswaModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
    }

    public function index()
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $kelas   = $this->kelasModel->getKelasDetail();
    $jurusan = $this->jurusanModel->getActive();

    $this->setPageTitle('Data Kelas');
    $this->addBreadcrumb('Dashboard', '/admin');
    $this->addBreadcrumb('Kelas');

    return $this->render('admin/kelas/index', [
        'kelas'   => $kelas,
        'jurusan' => $jurusan,
    ]);
}
    public function create()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Tambah Kelas');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Kelas', '/admin/kelas');
        $this->addBreadcrumb('Tambah');

        return $this->render('admin/kelas/form', ['jurusan' => $this->jurusanModel->getActive()]);
    }

    public function store()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if (!$this->validate($this->kelasModel->validationRules)) {
            return $this->redirectBackWithErrors($this->validator->getErrors());
        }

        $data = $this->sanitizeInput($this->request->getPost());

        if ($this->kelasModel->insert($data)) {
            $this->setFlashSuccess('Kelas berhasil ditambahkan!');
            return redirect()->to('/admin/kelas');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kelas.');
    }

    public function edit($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $kelas = $this->kelasModel->find($id);
        if (!$kelas) {
            return redirect()->to('/admin/kelas')->with('error', 'Kelas tidak ditemukan.');
        }

        $this->setPageTitle('Edit Kelas');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Kelas', '/admin/kelas');
        $this->addBreadcrumb('Edit');

        return $this->render('admin/kelas/form', [
            'kelas'   => $kelas,
            'jurusan' => $this->jurusanModel->getActive()
        ]);
    }

   public function update($id)
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    // ✅ HAPUS deklarasi pertama yang tidak terpakai
    $tingkat   = strtoupper(trim($this->request->getPost('tingkat')));
    $jurusanId = $this->request->getPost('jurusan_id');
    $rombel    = strtoupper(trim($this->request->getPost('rombel')));

    // Cek manual duplicate
    $exist = $this->kelasModel
        ->where('tingkat', $tingkat)
        ->where('jurusan_id', $jurusanId)
        ->where('rombel', $rombel)
        ->where('id !=', $id)
        ->first();

    if ($exist) {
        return redirect()->back()->withInput()
            ->with('error', "Kelas {$tingkat} {$rombel} sudah ada untuk jurusan ini!");
    }

    $data = $this->sanitizeInput($this->request->getPost());

    if ($this->kelasModel->update($id, $data)) {
        $this->setFlashSuccess('Kelas berhasil diupdate!');
        return redirect()->to('/admin/kelas');
    }

    return redirect()->back()->withInput()->with('error', 'Gagal mengupdate kelas.');
}    public function delete($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if ($this->kelasModel->delete($id)) {
            $this->setFlashSuccess('Kelas berhasil dihapus!');
        } else {
            $this->setFlashError('Gagal menghapus kelas.');
        }
        return redirect()->to('/admin/kelas');
    }

    public function detail($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $kelas = $this->kelasModel->find($id);
        if (!$kelas) {
            return redirect()->to('/admin/kelas')->with('error', 'Kelas tidak ditemukan.');
        }

        $tahunAjaranAktif = $this->tahunAjaranModel->getAktif();
        $siswa = $tahunAjaranAktif
            ? $this->kelasSiswaModel->getSiswaByKelasAktif($id, $tahunAjaranAktif['id'])
            : [];

        $this->setPageTitle('Detail Kelas');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Kelas', '/admin/kelas');
        $this->addBreadcrumb('Detail');

        return $this->render('admin/kelas/detail', [
            'kelas'             => $kelas,
            'siswa'             => $siswa,
            'tahun_ajaran_aktif'=> $tahunAjaranAktif,
        ]);
    }
}