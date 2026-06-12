<?php

namespace App\Models;

use CodeIgniter\Model;

class LiburModel extends Model
{
    protected $table            = 'hari_libur'; // Sesuaikan nama tabel
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'tanggal',
        'keterangan',
        'is_nasional'
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Cek apakah tanggal tertentu adalah hari libur
     */
    public function isLibur(string $tanggal): bool
    {
        return $this->where('tanggal', $tanggal)->countAllResults() > 0;
    }

    /**
     * Ambil data libur berdasarkan tanggal
     */
    public function getByTanggal(string $tanggal)
    {
        return $this->where('tanggal', $tanggal)->first();
    }
}