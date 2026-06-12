<?php

namespace App\Models;

use CodeIgniter\Model;

class MataPelajaranModel extends BaseModel
{
    protected $table            = 'mata_pelajaran';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = ['kode', 'nama'];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    protected $validationRules = [
        'kode' => 'required|max_length[20]|is_unique[mata_pelajaran.kode,id,{id}]',
        'nama' => 'required|max_length[100]',
    ];
}