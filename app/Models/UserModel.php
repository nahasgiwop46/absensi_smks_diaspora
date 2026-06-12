<?php

namespace App\Models;

class UserModel extends BaseModel
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';

    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';

    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // ========================================
    // CALLBACK
    // ========================================

    protected $allowCallbacks = true;

    protected $beforeInsert = ['hashPasswordBeforeInsert'];
    protected $beforeUpdate = ['hashPasswordBeforeUpdate'];

    // ========================================
    // ALLOWED FIELDS
    // ========================================

    protected $allowedFields = [
        'role_id',
        'username',
        'password',
        'nama_lengkap',
        'nuptk',
        'nip',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'email',
        'no_hp',
        'foto',
        'is_active',
        'created_by',
        'updated_by',
        'reset_token', // ✅ Tambah field untuk reset password
    ];

    // ========================================
    // VALIDATION
    // ========================================

       protected $validationRules = [
        'role_id' => [
            'rules' => 'required|integer|is_not_unique[master_role.id]',
            'errors' => [
                'required'      => 'Role harus dipilih.',
                'is_not_unique' => 'Role tidak valid.',
            ],
        ],
        'username' => [
            'rules' => 'required|alpha_numeric|min_length[5]|max_length[50]|is_unique[users.username,id,{id}]',
            'errors' => [
                'required'      => 'Username wajib diisi.',
                'alpha_numeric' => 'Username hanya boleh huruf dan angka.',
                'min_length'    => 'Username minimal 5 karakter.',
                'is_unique'     => 'Username sudah digunakan.',
            ],
        ],
        'password' => [
            'rules' => 'permit_empty|min_length[6]',
            'errors' => [
                'min_length' => 'Password minimal 6 karakter.',
            ],
        ],
        'nama_lengkap' => [
            'rules' => 'required|min_length[3]|max_length[100]',
            'errors' => [
                'required'   => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama minimal 3 karakter.',
            ],
        ],
        'email' => [
            'rules' => 'permit_empty|valid_email|is_unique[users.email,id,{id}]',
            'errors' => [
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah digunakan.',
            ],
        ],
        'nuptk' => [
            'rules' => 'permit_empty|is_unique[users.nuptk,id,{id}]',
            'errors' => [
                'is_unique' => 'NUPTK sudah digunakan.',
            ],
        ],
        'nip' => [
            'rules' => 'permit_empty|is_unique[users.nip,id,{id}]',
            'errors' => [
                'is_unique' => 'NIP sudah digunakan.',
            ],
        ],
        'no_hp' => [
            'rules' => 'permit_empty|numeric|min_length[10]|max_length[15]',
            'errors' => [
                'numeric'    => 'Nomor HP harus berupa angka.',
                'min_length' => 'Nomor HP minimal 10 digit.',
            ],
        ],
    ];

    protected $skipValidation = true;
   
    // ========================================
    // HASH PASSWORD BEFORE INSERT
    // ========================================

    public function hashPasswordBeforeInsert(array $data)
    {
        if (!empty($data['data']['password'])) {

            $data['data']['password'] = password_hash(
                $data['data']['password'],
                PASSWORD_DEFAULT
            );
        }

        return $data;
    }

    // ========================================
    // HASH PASSWORD BEFORE UPDATE
    // ========================================

    public function hashPasswordBeforeUpdate(array $data)
    {
        if (!empty($data['data']['password'])) {

            $data['data']['password'] = password_hash(
                $data['data']['password'],
                PASSWORD_DEFAULT
            );

        } else {

            unset($data['data']['password']);
        }

        return $data;
    }

    // ========================================
    // FIND USERNAME
    // ========================================

    public function findByUsername(string $username)
    {
        return $this->where('username', $username)
            ->where('is_active', 1)
            ->first();
    }

    // ========================================
    // GET ALL USER WITH ROLE
    // ========================================

    public function getAllUsersWithRole(array $filters = []): array
    {
        $builder = $this->select('
                users.*,
                master_role.nama as role_nama,
                master_role.kode as role_kode
            ')
            ->join('master_role', 'master_role.id = users.role_id')
            ->where('users.deleted_at', null);

        if (!empty($filters['role_id'])) {
            $builder->where('users.role_id', $filters['role_id']);
        }

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $builder->where('users.is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {

            $builder->groupStart()
                ->like('users.nama_lengkap', $filters['search'])
                ->orLike('users.username', $filters['search'])
                ->orLike('users.email', $filters['search'])
                ->groupEnd();
        }

        return $builder
            ->orderBy('users.nama_lengkap', 'ASC')
            ->findAll();
    }

    // ========================================
    // GET GURU
    // ========================================

    public function getGuru(array $filters = []): array
    {
        $builder = $this->select('
                users.*,
                master_role.nama as role_nama
            ')
            ->join('master_role', 'master_role.id = users.role_id')
            ->where('master_role.kode', 'GURU')
            ->where('users.deleted_at', null);

        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $builder->where('users.is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {

            $builder->groupStart()
                ->like('users.nama_lengkap', $filters['search'])
                ->orLike('users.username', $filters['search'])
                ->orLike('users.email', $filters['search'])
                ->groupEnd();
        }

        return $builder
            ->orderBy('users.nama_lengkap', 'ASC')
            ->findAll();
    }

    // ========================================
    // GURU DROPDOWN
    // ========================================

    public function getGuruDropdown(): array
    {
        return $this->select('users.id, users.nama_lengkap')
            ->join('master_role', 'master_role.id = users.role_id')
            ->where('master_role.kode', 'GURU')
            ->where('users.is_active', 1)
            ->where('users.deleted_at', null)
            ->orderBy('users.nama_lengkap', 'ASC')
            ->findAll();
    }

    // ========================================
    // CHANGE PASSWORD
    // ========================================

    public function changePassword(int $userId, string $newPassword): bool
    {
        return $this->update($userId, [
            'password' => $newPassword,
        ]);
    }

    // ========================================
    // DEACTIVATE USER
    // ========================================

    public function deactivate(int $userId): bool
    {
        return $this->update($userId, [
            'is_active' => 0,
        ]);
    }

    // ========================================
    // COUNT BY ROLE
    // ========================================

    public function countByRole(string $roleKode): int
    {
        return $this->select('users.id')
            ->join('master_role', 'master_role.id = users.role_id')
            ->where('master_role.kode', strtoupper($roleKode))
            ->where('users.is_active', 1)
            ->where('users.deleted_at', null)
            ->countAllResults();
    }

    // Tambah method set reset token
public function setResetToken($userId)
{
    $token = bin2hex(random_bytes(32));
    $this->update($userId, ['reset_token' => $token]);
    return $token;
}

// Tambah method validasi reset token
public function validasiResetToken($token)
{
    return $this->where('reset_token', $token)
                ->where('is_active', 1)
                ->where('deleted_at', null)
                ->first();
}

// Tambah method hapus reset token setelah digunakan
public function hapusResetToken($userId)
{
    return $this->update($userId, ['reset_token' => null]);
}
}