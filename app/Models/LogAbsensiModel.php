<?php

namespace App\Models;

use CodeIgniter\Model;

class LogAbsensiModel extends BaseModel
{
    protected $table            = 'log_absensi';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'absensi_id', 'aksi', 'field', 'nilai_lama', 'nilai_baru',
        'users_id', 'ip_address'
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    /**
     * Get log by absensi ID
     */
    public function getLogByAbsensi($absensiId)
    {
        return $this->select('log_absensi.*')
                    ->where('absensi_id', $absensiId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get log by tanggal
     */
    public function getLogByTanggal($tanggal)
    {
        return $this->select('log_absensi.*')
                    ->where('DATE(log_absensi.created_at)', $tanggal)
                    ->orderBy('log_absensi.created_at', 'DESC')
                    ->findAll();
    }
}