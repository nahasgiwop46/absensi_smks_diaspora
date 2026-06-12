<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MataPelajaranModel;

class Mapel extends BaseController
{
    protected $mataPelajaranModel;

    public function __construct()
    {
        $this->mataPelajaranModel = new MataPelajaranModel();
    }

    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Mata Pelajaran');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Mata Pelajaran');

        return $this->render('admin/mapel/index', [
            'mapel' => $this->mataPelajaranModel->findAll(),
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Tambah Mata Pelajaran');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Mata Pelajaran', '/admin/mapel');
        $this->addBreadcrumb('Tambah');

        return $this->render('admin/mapel/form');
    }

    public function store()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if (!$this->validate($this->mataPelajaranModel->validationRules)) {
            return $this->redirectBackWithErrors($this->validator->getErrors());
        }

        $data = $this->sanitizeInput($this->request->getPost());

        if ($this->mataPelajaranModel->insert($data)) {
            $this->setFlashSuccess('Mata pelajaran berhasil ditambahkan!');
            return redirect()->to('/admin/mapel');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan mata pelajaran.');
    }

    public function edit($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $mapel = $this->mataPelajaranModel->find($id);
        if (!$mapel) {
            return redirect()->to('/admin/mapel')->with('error', 'Data tidak ditemukan.');
        }

        $this->setPageTitle('Edit Mata Pelajaran');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Mata Pelajaran', '/admin/mapel');
        $this->addBreadcrumb('Edit');

        return $this->render('admin/mapel/form', ['mapel' => $mapel]);
    }

    public function update($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if (!$this->validate($this->mataPelajaranModel->validationRules)) {
            return $this->redirectBackWithErrors($this->validator->getErrors());
        }

        $data = $this->sanitizeInput($this->request->getPost());

        if ($this->mataPelajaranModel->update($id, $data)) {
            $this->setFlashSuccess('Mata pelajaran berhasil diupdate!');
            return redirect()->to('/admin/mapel');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate mata pelajaran.');
    }

    public function delete($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if ($this->mataPelajaranModel->delete($id)) {
            $this->setFlashSuccess('Mata pelajaran berhasil dihapus!');
        } else {
            $this->setFlashError('Gagal menghapus mata pelajaran.');
        }
        return redirect()->to('/admin/mapel');
    }
}