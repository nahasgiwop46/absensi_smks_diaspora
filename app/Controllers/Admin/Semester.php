<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SemesterModel;
use App\Models\TahunAjaranModel;

class Semester extends BaseController
{
    protected $semesterModel;
    protected $tahunAjaranModel;

    public function __construct()
    {
        $this->semesterModel    = new SemesterModel();
        $this->tahunAjaranModel = new TahunAjaranModel();
    }

    public function index()
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $tahunAjaranId = $this->request->getGet('tahun_ajaran_id');

    $this->setPageTitle('Data Semester');
    $this->addBreadcrumb('Dashboard', '/admin');
    $this->addBreadcrumb('Semester');

    return $this->render('admin/semester/index', [
        'semester'     => $tahunAjaranId ? $this->semesterModel->getByTahunAjaran($tahunAjaranId) : $this->semesterModel->findAll(),
        'tahun_ajaran' => $this->tahunAjaranModel->findAll(),
        'selected_ta'  => $tahunAjaranId,
    ]);
}
    public function create()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Tambah Semester');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Semester', '/admin/semester');
        $this->addBreadcrumb('Tambah');

        return $this->render('admin/semester/form', [
            'tahun_ajaran' => $this->tahunAjaranModel->findAll(),
        ]);
    }

    public function store()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $rules = [
            'tahun_ajaran_id' => 'required|integer',
            'nama'            => 'required|max_length[20]',
            'kode'            => 'required|in_list[1,2]',
            'tanggal_mulai'   => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return $this->redirectBackWithErrors($this->validator->getErrors());
        }

        $post = $this->request->getPost();
        $existing = $this->semesterModel
            ->where('tahun_ajaran_id', $post['tahun_ajaran_id'])
            ->where('kode', $post['kode'])
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()
                ->with('error', 'Semester dengan kode tersebut sudah ada di tahun ajaran ini.');
        }

        $data = $this->sanitizeInput($post);
        $data['is_active'] = 0;

        if ($this->semesterModel->insert($data)) {
            $this->setFlashSuccess('Semester berhasil ditambahkan!');
            return redirect()->to('/admin/semester?tahun_ajaran_id=' . $post['tahun_ajaran_id']);
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan semester.');
    }

    public function edit($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $semester = $this->semesterModel->find($id);
        if (!$semester) {
            return redirect()->to('/admin/semester')->with('error', 'Data tidak ditemukan.');
        }

        $this->setPageTitle('Edit Semester');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Semester', '/admin/semester');
        $this->addBreadcrumb('Edit');

        return $this->render('admin/semester/form', [
            'semester'     => $semester,
            'tahun_ajaran' => $this->tahunAjaranModel->findAll(),
        ]);
    }

    public function update($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $rules = [
            'tahun_ajaran_id' => 'required|integer',
            'nama'            => 'required|max_length[20]',
            'kode'            => 'required|in_list[1,2]',
            'tanggal_mulai'   => 'required|valid_date',
            'tanggal_selesai' => 'required|valid_date',
        ];

        if (!$this->validate($rules)) {
            return $this->redirectBackWithErrors($this->validator->getErrors());
        }

        $data = $this->sanitizeInput($this->request->getPost());

        if ($this->semesterModel->update($id, $data)) {
            $this->setFlashSuccess('Semester berhasil diupdate!');
            return redirect()->to('/admin/semester?tahun_ajaran_id=' . $data['tahun_ajaran_id']);
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate semester.');
    }

    public function aktifkan($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $semester = $this->semesterModel->find($id);
        if (!$semester) {
            return redirect()->to('/admin/semester')->with('error', 'Data tidak ditemukan.');
        }

        $this->semesterModel->nonaktifkanSemua();
        $this->semesterModel->update($id, ['is_active' => 1]);

        $this->setFlashSuccess('Semester ' . $semester['nama'] . ' berhasil diaktifkan!');
        return redirect()->to('/admin/semester');
    }

    public function delete($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $semester = $this->semesterModel->find($id);

        if ($semester && $semester['is_active'] == 1) {
            return redirect()->to('/admin/semester')
                ->with('error', 'Tidak dapat menghapus semester yang sedang aktif.');
        }

        if ($this->semesterModel->delete($id)) {
            $this->setFlashSuccess('Semester berhasil dihapus!');
        } else {
            $this->setFlashError('Gagal menghapus semester.');
        }

        return redirect()->to('/admin/semester');
    }
}