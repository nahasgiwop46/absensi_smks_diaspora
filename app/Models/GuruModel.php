<?php

namespace App\Models;

class GuruModel extends BaseModel
{
    protected $table            = 'guru';  // ✅ FIX
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';
    
    protected $allowedFields    = [
        'role_id', 'username', 'password', 'nama_lengkap',
        'nuptk', 'nip', 'jenis_kelamin', 'tempat_lahir',
        'tanggal_lahir', 'alamat', 'email', 'no_hp', 'foto',
        'is_active',
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getGuruAktif()
    {
        return $this->select('users.*, master_role.nama as role_nama')
                    ->join('master_role', 'master_role.id = users.role_id')
                    ->where('master_role.kode', 'GURU')
                    ->where('users.is_active', 1)
                    ->where('users.deleted_at', null)
                    ->findAll();
    }

    public function getGuruById($id)
    {
        return $this->select('users.*, master_role.nama as role_nama, master_role.kode as role_kode')
                    ->join('master_role', 'master_role.id = users.role_id')
                    ->where('users.id', $id)
                    ->where('users.deleted_at', null)
                    ->first();
    }

    public function getByNuptk($nuptk)
    {
        return $this->where('nuptk', $nuptk)
                    ->where('deleted_at', null)
                    ->first();
    }

    public function getJadwalMengajar($guruId, $semesterId = null)
    {
        $builder = $this->select('jadwal.*, mata_pelajaran.nama as mapel_nama,
                                  CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas')
                        ->join('jadwal', 'jadwal.guru_id = users.id')
                        ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal.mata_pelajaran_id')
                        ->join('kelas', 'kelas.id = jadwal.kelas_id')
                        ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                        ->where('users.id', $guruId);
        
        if ($semesterId) {
            $builder->where('jadwal.semester_id', $semesterId);
        }
        
        return $builder->orderBy('jadwal.hari', 'ASC')
                       ->orderBy('jadwal.jam_mulai', 'ASC')
                       ->findAll();
    }
    
}