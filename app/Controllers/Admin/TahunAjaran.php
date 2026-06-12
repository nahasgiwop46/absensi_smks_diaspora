<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TahunAjaranModel;
use App\Models\SemesterModel;

class TahunAjaran extends BaseController
{
    protected $tahunAjaranModel;
    protected $semesterModel;

    public function __construct()
    {
        $this->tahunAjaranModel = new TahunAjaranModel();
        $this->semesterModel    = new SemesterModel();
    }

    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Tahun Ajaran');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Tahun Ajaran');

        return $this->render('admin/tahun_ajaran/index', [
            'tahun_ajaran' => $this->tahunAjaranModel->getAllWithSemester(),
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Tambah Tahun Ajaran');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Tahun Ajaran', '/admin/tahun-ajaran');
        $this->addBreadcrumb('Tambah');

        return $this->render('admin/tahun_ajaran/form');
    }

    public function store()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $rules = [
            'nama'            => 'required|max_length[50]',
            'tanggal_mulai'   => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return $this->redirectBackWithErrors($this->validator->getErrors());
        }

        $data = $this->sanitizeInput($this->request->getPost());
        $data['is_active'] = 0;

        if ($this->tahunAjaranModel->insert($data)) {
            $this->setFlashSuccess('Tahun ajaran berhasil ditambahkan!');
            return redirect()->to('/admin/tahun-ajaran');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan tahun ajaran.');
    }

    public function edit($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $ta = $this->tahunAjaranModel->find($id);
        if (!$ta) {
            return redirect()->to('/admin/tahun-ajaran')->with('error', 'Data tidak ditemukan.');
        }

        $this->setPageTitle('Edit Tahun Ajaran');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Tahun Ajaran', '/admin/tahun-ajaran');
        $this->addBreadcrumb('Edit');

        return $this->render('admin/tahun_ajaran/form', ['tahun_ajaran' => $ta]);
    }

    public function update($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $rules = [
            'nama'            => 'required|max_length[50]',
            'tanggal_mulai'   => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return $this->redirectBackWithErrors($this->validator->getErrors());
        }

        $data = $this->sanitizeInput($this->request->getPost());

        if ($this->tahunAjaranModel->update($id, $data)) {
            $this->setFlashSuccess('Tahun ajaran berhasil diupdate!');
            return redirect()->to('/admin/tahun-ajaran');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate tahun ajaran.');
    }

    public function aktifkan($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $db->table('tahun_ajaran')->set('is_active', 0)->update();
        $this->tahunAjaranModel->update($id, ['is_active' => 1]);

        $db->transComplete();

        if ($db->transStatus()) {
            $this->setFlashSuccess('Tahun ajaran berhasil diaktifkan!');
        } else {
            $this->setFlashError('Gagal mengaktifkan tahun ajaran.');
        }

        return redirect()->to('/admin/tahun-ajaran');
    }

    public function delete($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $ta = $this->tahunAjaranModel->find($id);

        if ($ta && $ta['is_active'] == 1) {
            return redirect()->to('/admin/tahun-ajaran')
                ->with('error', 'Tidak dapat menghapus tahun ajaran yang sedang aktif.');
        }

        if ($this->tahunAjaranModel->delete($id)) {
            $this->setFlashSuccess('Tahun ajaran berhasil dihapus!');
        } else {
            $this->setFlashError('Gagal menghapus tahun ajaran.');
        }

        return redirect()->to('/admin/tahun-ajaran');
    }

    public function detail($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $ta = $this->tahunAjaranModel->find($id);
        if (!$ta) {
            return redirect()->to('/admin/tahun-ajaran')->with('error', 'Data tidak ditemukan.');
        }

        $this->setPageTitle('Detail Tahun Ajaran');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Tahun Ajaran', '/admin/tahun-ajaran');
        $this->addBreadcrumb('Detail');

        return $this->render('admin/tahun_ajaran/detail', [
            'tahun_ajaran' => $ta,
            'semester'     => $this->semesterModel->getByTahunAjaran($id),
        ]);
    }
}