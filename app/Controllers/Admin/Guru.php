<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Guru extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $filters = [
            'search'    => $this->request->getGet('search'),
            'is_active' => $this->request->getGet('is_active'),
        ];

        $this->setPageTitle('Data Guru');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Guru');

        return $this->render('admin/guru/index', [
            'guru'    => $this->userModel->getGuru($filters),
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Tambah Guru');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Guru', '/admin/guru');
        $this->addBreadcrumb('Tambah');

        return $this->render('admin/guru/form');
    }

    public function store()
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $rules = [

        'username' => [
            'rules' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'errors' => [
                'is_unique' => 'Username sudah digunakan.',
            ],
        ],

        'password' => [
            'rules' => 'required|min_length[6]',
        ],

        'nama_lengkap' => [
            'rules' => 'required|max_length[100]',
        ],

        'email' => [
            'rules' => 'permit_empty|valid_email|is_unique[users.email]',
            'errors' => [
                'is_unique' => 'Email sudah digunakan.',
            ],
        ],

    ];

    if (!$this->validate($rules)) {

        return $this->redirectBackWithErrors(
            $this->validator->getErrors()
        );
    }

    $data = $this->request->getPost();

    // Role guru
    $data['role_id'] = 2;

    // default active
    $data['is_active'] = $data['is_active'] ?? 1;

    // ❌ HAPUS password_hash()

    if ($this->userModel->insert($data)) {

        $this->setFlashSuccess('Data guru berhasil ditambahkan!');

        return redirect()->to('/admin/guru');
    }

    return redirect()->back()
        ->withInput()
        ->with('error', 'Gagal menambahkan data guru.');
}
    public function detail($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $guru = $this->userModel->find($id);
        if (!$guru) {
            return redirect()->to('/admin/guru')->with('error', 'Guru tidak ditemukan.');
        }

        $this->setPageTitle('Detail Guru');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Guru', '/admin/guru');
        $this->addBreadcrumb('Detail');

        return $this->render('admin/guru/detail', ['guru' => $guru]);
    }

    public function edit($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $guru = $this->userModel->find($id);
        if (!$guru) {
            return redirect()->to('/admin/guru')->with('error', 'Guru tidak ditemukan.');
        }

        $this->setPageTitle('Edit Guru');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Guru', '/admin/guru');
        $this->addBreadcrumb('Edit');

        return $this->render('admin/guru/form', ['guru' => $guru]);
    }

    public function update($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $rules = [
            'username'     => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'nama_lengkap' => 'required|max_length[100]',
            'email'        => 'permit_empty|valid_email',
        ];

        if (!$this->validate($rules)) {
            return $this->redirectBackWithErrors($this->validator->getErrors());
        }

        $data = $this->request->getPost();

        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        if ($this->userModel->update($id, $data)) {
            $this->setFlashSuccess('Data guru berhasil diupdate!');
            return redirect()->to('/admin/guru');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal mengupdate data guru.');
    }

    public function delete($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if ($this->userModel->delete($id)) {
            $this->setFlashSuccess('Data guru berhasil dihapus!');
        } else {
            $this->setFlashError('Gagal menghapus data guru.');
        }

        return redirect()->to('/admin/guru');
    }
    /**
 * Penempatan Guru - Lihat guru mengajar di kelas mana
 */
public function penempatan()
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $guruId = $this->request->getGet('guru_id');
    $semesterAktif = (new \App\Models\SemesterModel())->getAktif();

    $guruList = $this->userModel->getGuru(['is_active' => true]);
    
    $jadwalGuru = [];
    if ($guruId && $semesterAktif) {
        $jadwalModel = new \App\Models\JadwalModel();
        $jadwalGuru = $jadwalModel->getJadwalByGuru($guruId, $semesterAktif['id']);
    }

    $this->setPageTitle('Penempatan Guru');
    $this->addBreadcrumb('Dashboard', '/admin');
    $this->addBreadcrumb('Guru', '/admin/guru');
    $this->addBreadcrumb('Penempatan');

    return $this->render('admin/guru/penempatan', [
        'guru_list'      => $guruList,
        'jadwal_guru'    => $jadwalGuru,
        'selected_guru'  => $guruId,
        'semester_aktif' => $semesterAktif,
    ]);
}
}