<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;

class UserManagement extends BaseController
{
    protected $userModel;
    protected $roleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }

    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $filters = [
            'role_id'   => $this->request->getGet('role_id'),
            'is_active' => $this->request->getGet('is_active'),
            'search'    => $this->request->getGet('search'),
        ];

        $users = $this->userModel->getAllUsersWithRole($filters);
        $roles = $this->roleModel->findAll();

        $this->setPageTitle('Manajemen User');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('User');

        return $this->render('admin/user_management/index', [
            'users'   => $users,
            'roles'   => $roles,
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $siswaModel = new \App\Models\SiswaModel();
        $siswaList = $siswaModel->where('user_id', null)->findAll();

        $guruList = $this->userModel->where('role_id', 2)->where('is_active', 1)->findAll();

        $this->setPageTitle('Tambah User');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('User', '/admin/users');
        $this->addBreadcrumb('Tambah');

        return $this->render('admin/user_management/form', [
            'roles'      => $this->roleModel->findAll(),
            'siswa_list' => $siswaList,
            'guru_list'  => $guruList,
        ]);
    }

    public function store()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $rules = [
            'role_id' => [
                'rules' => 'required|integer',
            ],
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
            'nuptk' => [
                'rules' => 'permit_empty|is_unique[users.nuptk]',
                'errors' => [
                    'is_unique' => 'NUPTK sudah digunakan.',
                ],
            ],
            'nip' => [
                'rules' => 'permit_empty|is_unique[users.nip]',
                'errors' => [
                    'is_unique' => 'NIP sudah digunakan.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'role_id'       => $this->request->getPost('role_id'),
            'username'      => $this->request->getPost('username'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
            'email'         => $this->request->getPost('email'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'nuptk'         => $this->request->getPost('nuptk'),
            'nip'           => $this->request->getPost('nip'),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'alamat'        => $this->request->getPost('alamat'),
            'is_active'     => 1,
        ];

        if (!$this->userModel->insert($data)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan user.');
        }

        $this->setFlashSuccess('User berhasil ditambahkan!');
        return redirect()->to('/admin/users');
    }

   public function edit($id)
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $user = $this->userModel->find($id);
    if (!$user) {
        return redirect()->to('/admin/users')->with('error', 'User tidak ditemukan.');
    }

    // ✅ Ambil siswa yang belum punya akun + yang terhubung ke user ini
    $siswaModel = new \App\Models\SiswaModel();
    $siswaList = $siswaModel
        ->groupStart()
            ->where('user_id', null)
            ->orWhere('user_id', $id)
        ->groupEnd()
        ->where('is_active', 1)
        ->where('deleted_at', null)
        ->orderBy('nama_lengkap', 'ASC')
        ->findAll();

    $this->setPageTitle('Edit User');
    $this->addBreadcrumb('Dashboard', '/admin');
    $this->addBreadcrumb('User', '/admin/users');
    $this->addBreadcrumb('Edit');

    return $this->render('admin/user_management/form', [
        'user'       => $user,
        'roles'      => $this->roleModel->findAll(),
        'siswa_list' => $siswaList,     // ✅ KIRIM KE VIEW
    ]);
}
   public function update($id)
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $rules = [
        'role_id' => [
            'rules' => 'required|integer',
        ],
        'username' => [
            'rules' => "required|min_length[3]|max_length[50]|is_unique[users.username,id,{$id}]",
            'errors' => ['is_unique' => 'Username sudah digunakan.'],
        ],
        'nama_lengkap' => [
            'rules' => 'required|max_length[100]',
        ],
        'email' => [
            'rules' => "permit_empty|valid_email|is_unique[users.email,id,{$id}]",
            'errors' => ['is_unique' => 'Email sudah digunakan.'],
        ],
        'nuptk' => [
            'rules' => "permit_empty|is_unique[users.nuptk,id,{$id}]",
            'errors' => ['is_unique' => 'NUPTK sudah digunakan.'],
        ],
        'nip' => [
            'rules' => "permit_empty|is_unique[users.nip,id,{$id}]",
            'errors' => ['is_unique' => 'NIP sudah digunakan.'],
        ],
    ];

    if (!$this->validate($rules)) {
        return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $data = [
        'role_id'       => $this->request->getPost('role_id'),
        'username'      => $this->request->getPost('username'),
        'nama_lengkap'  => $this->request->getPost('nama_lengkap'),
        'email'         => $this->request->getPost('email'),
        'no_hp'         => $this->request->getPost('no_hp'),
        'nuptk'         => $this->request->getPost('nuptk'),
        'nip'           => $this->request->getPost('nip'),
        'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
        'alamat'        => $this->request->getPost('alamat'),
        'is_active'     => $this->request->getPost('is_active') ?? 1,
    ];

    $password = $this->request->getPost('password');
    if (!empty($password)) {
        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
    }

    if (!$this->userModel->update($id, $data)) {
        return redirect()->back()->withInput()->with('error', 'Gagal update user.');
    }

    // ✅ OPSIONAL: Update siswa_id hanya jika dipilih
    $siswaId = $this->request->getPost('siswa_id');
    
    if (!empty($siswaId)) {
        $siswaModel = new \App\Models\SiswaModel();
        $siswaModel->where('user_id', $id)->set(['user_id' => null])->update();
        $siswaModel->update($siswaId, ['user_id' => $id]);
    }
    // ✅ Kalau tidak dipilih (kosong), abaikan saja

    $this->setFlashSuccess('User berhasil diupdate!');
    return redirect()->to('/admin/users');
}
    public function delete($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if ($id == session()->get('user_id')) {
            return redirect()->to('/admin/users')->with('error', 'Anda tidak bisa menghapus akun sendiri.');
        }

        if ($this->userModel->delete($id)) {
            $this->setFlashSuccess('User berhasil dihapus!');
        } else {
            $this->setFlashError('Gagal menghapus user.');
        }

        return redirect()->to('/admin/users');
    }

    public function toggleStatus($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if ($id == session()->get('user_id')) {
            return $this->jsonError('Anda tidak bisa mengubah status akun sendiri.');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return $this->jsonError('User tidak ditemukan.');
        }

        $newStatus = $user['is_active'] == 1 ? 0 : 1;
        $this->userModel->update($id, ['is_active' => $newStatus]);

        return $this->jsonSuccess('Status user berhasil diubah!', ['is_active' => $newStatus]);
    }
}