<?php

namespace App\Models;

class KelasModel extends BaseModel
{
    protected $table            = 'kelas';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = ['tingkat', 'jurusan_id', 'rombel', 'kapasitas', 'is_active'];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    
    protected $validationRules  = [
        'tingkat'    => 'required|max_length[5]',
        'jurusan_id' => 'permit_empty|integer',
        'rombel'     => 'permit_empty|max_length[10]',
        'kapasitas'  => 'permit_empty|integer',
    ];

    

    public function getActiveKelas()
    {
        return $this->select('kelas.*, jurusan.nama as jurusan_nama, jurusan.singkatan as jurusan_singkatan')
                    ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                    ->where('kelas.is_active', 1)
                    ->orderBy('kelas.tingkat ASC, jurusan.singkatan ASC, kelas.rombel ASC')
                    ->findAll();
    }

        public function getKelasDetail()
{
    return $this->select('kelas.*, jurusan.nama as jurusan_nama, jurusan.singkatan as jurusan_singkatan,
                          COUNT(kelas_siswa.id) as total_siswa')
                ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                ->join('kelas_siswa', 'kelas_siswa.kelas_id = kelas.id AND kelas_siswa.status = "aktif"', 'left')
                ->where('kelas.is_active', 1)  // ✅ TAMBAH INI
                ->groupBy('kelas.id')
                ->orderBy('kelas.tingkat ASC, jurusan.singkatan ASC, kelas.rombel ASC')
                ->findAll();
}
}