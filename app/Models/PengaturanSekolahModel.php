<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanSekolahModel extends BaseModel
{
    protected $table            = 'pengaturan_sekolah';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    // ✅ SESUAIKAN DENGAN DATABASE
    protected $allowedFields    = [
        'nama_sekolah',
        'alamat',
        'telepon',           // ✅ ADA di DB
        'email',             // ✅ ADA di DB
        'logo_sekolah',      // ✅ PERBAIKI: logo → logo_sekolah
        'kepala_sekolah',    // ✅ ADA di DB
        'nip_kepsek',        // ✅ ADA di DB
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $beforeUpdate = ['setUpdatedBy'];

    protected function setUpdatedBy(array $data)
    {
        $data['data']['updated_by'] = session()->get('user_id');
        return $data;
    }

    // QUERY METHODS
    public function getPengaturan()
    {
        return $this->first();
    }

    // Tambah method updatePengaturan
public function updatePengaturan($id, array $data)
{
    return $this->update($id, $data);
}
}