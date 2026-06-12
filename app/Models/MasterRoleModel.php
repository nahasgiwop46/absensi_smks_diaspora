<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterRoleModel extends BaseModel
{
    protected $table            = 'master_role';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = ['kode', 'nama', 'deskripsi'];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // Tidak ada updated_at
    protected $deletedField     = ''; // Tidak menggunakan soft delete
    
    protected $validationRules  = [
        'kode' => 'required|max_length[20]|is_unique[master_role.kode,id,{id}]',
        'nama' => 'required|max_length[50]',
    ];

    protected $validationMessages = [
        'kode' => [
            'required'   => 'Kode role harus diisi.',
            'is_unique'  => 'Kode role sudah digunakan.',
        ],
        'nama' => [
            'required'   => 'Nama role harus diisi.',
        ],
    ];

    /**
     * Get all roles with user count
     */
  public function getRolesWithUserCount()
{
    return $this->select('master_role.*, COUNT(users.id) as total_user')
                ->join('users', 'users.role_id = master_role.id', 'left')
                ->where('users.deleted_at', null)
                ->groupBy('master_role.id')
                ->orderBy('master_role.id', 'ASC')
                ->findAll();
}
}