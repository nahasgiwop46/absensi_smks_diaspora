<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterStatusAbsensiModel extends BaseModel
{
    protected $table            = 'master_status_absensi';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = ['kode', 'label', 'warna', 'is_hadir', 'deskripsi'];

    // QUERY METHODS
    public function getStatusHadir()
    {
        return $this->where('is_hadir', 1)->findAll();
    }

    public function getStatusTidakHadir()
    {
        return $this->where('is_hadir', 0)->findAll();
    }

    public function getByKode($kode)
    {
        return $this->where('kode', $kode)->first();
    }
}