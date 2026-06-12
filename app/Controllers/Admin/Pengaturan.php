<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PengaturanSekolahModel;
use App\Models\TahunAjaranModel;
use App\Models\SemesterModel;
use App\Models\MasterStatusAbsensiModel;

class Pengaturan extends BaseController
{
    protected $pengaturanModel;
    protected $tahunAjaranModel;
    protected $semesterModel;
    protected $statusAbsensiModel;

    public function __construct()
    {
        $this->pengaturanModel = new PengaturanSekolahModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
        $this->semesterModel = new SemesterModel();
        $this->statusAbsensiModel = new MasterStatusAbsensiModel();
        
        if (session()->get('role_kode') !== 'admin') {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    /**
     * Halaman pengaturan umum
     */
    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        helper('admin_helper');

        $this->setPageTitle('Pengaturan Sekolah');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Pengaturan');

        return $this->render('admin/pengaturan/index', [
            'title'      => 'Pengaturan Sekolah',
            'pengaturan' => $this->pengaturanModel->getPengaturan(),
            'tahunAjaran'=> $this->tahunAjaranModel->getAllWithSemester(),
            'semester'   => $this->semesterModel->getAktif(),
        ]);
    }

    /**
     * Update pengaturan umum
     */
    public function update()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $data = $this->sanitizeInput($this->request->getPost());
        $data['updated_by'] = session()->get('user_id');

        $pengaturan = $this->pengaturanModel->getPengaturan();

        if ($pengaturan) {
            $this->pengaturanModel->update($pengaturan['id'], $data);
        } else {
            $data['created_by'] = session()->get('user_id');
            $this->pengaturanModel->insert($data);
        }

        $this->setFlashSuccess('Pengaturan berhasil disimpan!');
        return redirect()->to('/admin/pengaturan');
    }

    /**
     * Halaman status absensi
     */
    public function statusAbsensi()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Master Status Absensi');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Pengaturan', '/admin/pengaturan');
        $this->addBreadcrumb('Status Absensi');

        return $this->render('admin/pengaturan/status_absensi', [
            'title'  => 'Master Status Absensi',
            'status' => $this->statusAbsensiModel->findAll(),
        ]);
    }

    /**
     * Simpan status absensi
     */
    public function storeStatusAbsensi()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $data = $this->sanitizeInput($this->request->getPost());

        if ($this->statusAbsensiModel->insert($data)) {
            $this->setFlashSuccess('Status absensi berhasil ditambahkan!');
        } else {
            $this->setFlashError('Gagal menambahkan status absensi.');
        }

        return redirect()->to('/admin/pengaturan/status-absensi');
    }
}