<?php

namespace App\Models;

use CodeIgniter\Model;

class SemesterModel extends BaseModel
{
    protected $table            = 'semester';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
   // ✅ TAMBAH validationRules
protected $validationRules = [
    'tahun_ajaran_id' => 'required|integer',
    'nama'            => 'required|max_length[20]',
    'kode'            => 'required|in_list[1,2]',  // ✅ Validasi enum
    'tanggal_mulai'   => 'required|valid_date',
    'tanggal_selesai' => 'required|valid_date',
];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];

    protected function setCreatedBy(array $data)
    {
        $data['data']['created_by'] = session()->get('user_id');
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        $data['data']['updated_by'] = session()->get('user_id');
        return $data;
    }

    // QUERY METHODS
    public function getAktif()
    {
        return $this->select('semester.*, tahun_ajaran.nama as tahun_ajaran_nama')
                    ->join('tahun_ajaran', 'tahun_ajaran.id = semester.tahun_ajaran_id')
                    ->where('semester.is_active', 1)
                    ->first();
    }

    public function getByTahunAjaran($tahunAjaranId)
    {
        return $this->where('tahun_ajaran_id', $tahunAjaranId)
                    ->orderBy('kode', 'ASC')
                    ->findAll();
    }

/**
 * Nonaktifkan semua semester dalam satu tahun ajaran
 */
public function nonaktifkanSemua(?int $tahunAjaranId = null)
{
    if ($tahunAjaranId !== null) {
        $this->where('tahun_ajaran_id', $tahunAjaranId);
    }
    
    return $this->where('is_active', 1)
                ->set(['is_active' => 0])
                ->update();
}

// Tambah method cek semester aktif berdasarkan tanggal
public function getAktifByTanggal($tanggal = null)
{
    $tanggal = $tanggal ?? date('Y-m-d');
    
    return $this->select('semester.*, tahun_ajaran.nama as tahun_ajaran_nama')
                ->join('tahun_ajaran', 'tahun_ajaran.id = semester.tahun_ajaran_id')
                ->where('semester.is_active', 1)
                ->where('semester.tanggal_mulai <=', $tanggal)
                ->where('semester.tanggal_selesai >=', $tanggal)
                ->first();
}

}