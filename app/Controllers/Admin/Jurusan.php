<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JurusanModel;

class Jurusan extends BaseController
{
    protected $jurusanModel;

    public function __construct()
    {
        $this->jurusanModel = new JurusanModel();
    }

    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Data Jurusan');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Jurusan');

        $data = ['jurusan' => $this->jurusanModel->getJurusanWithKelasCount()];
        return $this->render('admin/jurusan/index', $data);
    }

    public function create()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Tambah Jurusan');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Jurusan', '/admin/jurusan');
        $this->addBreadcrumb('Tambah');

        return $this->render('admin/jurusan/form');
    }

    public function store()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if (!$this->validate($this->jurusanModel->validationRules)) {
            return $this->redirectBackWithErrors($this->validator->getErrors());
        }

        $data = $this->sanitizeInput($this->request->getPost());
        $data['updated_by'] = session()->get('user_id');

        if ($this->jurusanModel->insert($data)) {
            $this->setFlashSuccess('Jurusan berhasil ditambahkan!');
            return redirect()->to('/admin/jurusan');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jurusan.');
    }

    public function edit($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $jurusan = $this->jurusanModel->find($id);
        if (!$jurusan) {
            return redirect()->to('/admin/jurusan')->with('error', 'Jurusan tidak ditemukan.');
        }

        $this->setPageTitle('Edit Jurusan');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Jurusan', '/admin/jurusan');
        $this->addBreadcrumb('Edit');

        return $this->render('admin/jurusan/form', ['jurusan' => $jurusan]);
    }

   public function update($id)
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $jurusan = $this->jurusanModel->find($id);

    if (!$jurusan) {
        return redirect()
            ->to('/admin/jurusan')
            ->with('error', 'Jurusan tidak ditemukan.');
    }

    $rules = [

        'kode' => [
            'rules' => "required|max_length[10]|is_unique[jurusan.kode,id,{$id}]",
            'errors' => [
                'required'  => 'Kode jurusan wajib diisi.',
                'is_unique' => 'Kode jurusan sudah digunakan.',
            ],
        ],

        'nama' => [
            'rules' => "required|max_length[100]|is_unique[jurusan.nama,id,{$id}]",
            'errors' => [
                'required'  => 'Nama jurusan wajib diisi.',
                'is_unique' => 'Nama jurusan sudah digunakan.',
            ],
        ],

    ];

    if (!$this->validate($rules)) {

        return redirect()
            ->back()
            ->withInput()
            ->with('errors', $this->validator->getErrors());
    }

    $data = [

        'kode'       => $this->request->getPost('kode'),
        'singkatan'  => $this->request->getPost('singkatan'),
        'nama'       => $this->request->getPost('nama'),
        'deskripsi'  => $this->request->getPost('deskripsi'),
        'is_active'  => $this->request->getPost('is_active') ?? 1,

    ];

    if ($this->jurusanModel->update($id, $data)) {

        return redirect()
            ->to('/admin/jurusan')
            ->with('success', 'Data jurusan berhasil diupdate.');
    }

    return redirect()
        ->back()
        ->withInput()
        ->with('error', 'Gagal mengupdate data jurusan.');
}
    public function delete($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if ($this->jurusanModel->delete($id)) {
            $this->setFlashSuccess('Jurusan berhasil dihapus!');
        } else {
            $this->setFlashError('Gagal menghapus jurusan.');
        }
        return redirect()->to('/admin/jurusan');
    }

   public function detail($id)
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $jurusan = $this->jurusanModel->find($id);

    if (!$jurusan) {

        return redirect()
            ->to('/admin/jurusan')
            ->with('error', 'Data jurusan tidak ditemukan.');
    }

    $this->setPageTitle('Detail Jurusan');

    $this->addBreadcrumb('Dashboard', '/admin');
    $this->addBreadcrumb('Jurusan', '/admin/jurusan');
    $this->addBreadcrumb('Detail');

    return $this->render('admin/jurusan/detail', [
        'jurusan' => $jurusan,
    ]);
}
}