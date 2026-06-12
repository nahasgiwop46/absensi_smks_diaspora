<?php

namespace App\Models;

use CodeIgniter\Model;

class TahunAjaranModel extends BaseModel
{
    protected $table            = 'tahun_ajaran';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'nama', 'tanggal_mulai', 'tanggal_selesai', 'semester', 'is_active', 'created_by'
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    protected $beforeInsert = ['setCreatedBy'];

    protected function setCreatedBy(array $data)
    {
        $data['data']['created_by'] = session()->get('user_id');
        return $data;
    }

    // QUERY METHODS
    public function getAktif()
    {
        return $this->where('is_active', 1)->first();
    }

    public function getAllWithSemester()
    {
        return $this->select('tahun_ajaran.*, COUNT(semester.id) as total_semester')
                    ->join('semester', 'semester.tahun_ajaran_id = tahun_ajaran.id', 'left')
                    ->groupBy('tahun_ajaran.id')
                    ->orderBy('tahun_ajaran.tanggal_mulai', 'DESC')
                    ->findAll();
    }
}