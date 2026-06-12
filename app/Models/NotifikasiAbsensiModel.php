<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiAbsensiModel extends BaseModel
{
    protected $table            = 'notifikasi_absensi';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'siswa_id', 'absensi_id', 'tipe', 'nomor_tujuan',
        'status_kirim', 'pesan', 'dikirim_oleh'
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    // QUERY
    public function getPending()
    {
        return $this->where('status_kirim', 'pending')->findAll();
    }

    public function getByAbsensi($absensiId)
    {
        return $this->where('absensi_id', $absensiId)->first();
    }
}