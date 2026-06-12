<?php

namespace App\Models;

use CodeIgniter\Model;

class RekapAbsensiHarianModel extends BaseModel
{
    protected $table            = 'rekap_absensi_harian';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'tanggal', 'kelas_id', 'total_siswa',
        'hadir', 'izin', 'sakit', 'alpa', 'terlambat'
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // QUERY
    public function getRekapByTanggal($tanggal)
    {
        return $this->select('rekap_absensi_harian.*, 
                              CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas')
                    ->join('kelas', 'kelas.id = rekap_absensi_harian.kelas_id')
                    ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                    ->where('rekap_absensi_harian.tanggal', $tanggal)
                    ->orderBy('kelas.tingkat ASC, kelas.rombel ASC')
                    ->findAll();
    }
}