<?php

namespace App\Models;

class RoleModel extends BaseModel
{
    protected $table            = 'master_role';
    protected $primaryKey       = 'id';
    
    protected $useSoftDeletes   = false;
    
    protected $allowedFields = [
        'kode',
        'nama',
        'deskripsi',
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';
    protected $deletedField     = '';
    
    protected $validationRules = [
        'kode' => [
            'rules'  => 'required|alpha_numeric|min_length[2]|max_length[20]|is_unique[master_role.kode,id,{id}]',
            'errors' => [
                'required'      => 'Kode role wajib diisi',
                'alpha_numeric' => 'Kode hanya boleh huruf dan angka',
                'is_unique'     => 'Kode role sudah digunakan',
                'min_length'    => 'Kode minimal 2 karakter',
                'max_length'    => 'Kode maksimal 20 karakter',
            ]
        ],
        'nama' => [
            'rules'  => 'required|min_length[3]|max_length[50]',
            'errors' => [
                'required'   => 'Nama role wajib diisi',
                'min_length' => 'Nama role minimal 3 karakter',
                'max_length' => 'Nama role maksimal 50 karakter',
            ]
        ],
        'deskripsi' => [
            'rules'  => 'permit_empty|max_length[255]',
            'errors' => [
                'max_length' => 'Deskripsi maksimal 255 karakter',
            ]
        ],
    ];
    
    // ========================================
    // Get role dengan jumlah user
    // ========================================
    public function getRolesWithUserCount(): array
    {
        return $this->select("
                master_role.*,
                COUNT(CASE WHEN users.is_active = 1 AND users.deleted_at IS NULL THEN 1 END) as total_user_aktif,
                COUNT(CASE WHEN users.deleted_at IS NULL THEN 1 END) as total_user
            ")
            ->join('users', 'users.role_id = master_role.id', 'left')  // ✅ FIX: user → users
            ->groupBy('master_role.id')
            ->orderBy('master_role.nama', 'ASC')
            ->findAll();
    }
    
    // ========================================
    // Check if role is in use
    // ========================================
public function isRoleInUse(int $roleId): bool
{
    return $this->db->table('users')
                    ->where('role_id', $roleId)
                    ->where('is_active', 1)
                    ->where('deleted_at', null)
                    ->countAllResults() > 0;
}
    
    // ========================================
    // Get role by kode
    // ========================================
    public function getByKode(string $kode)
    {
        return $this->where('kode', strtoupper($kode))->first();
    }
}