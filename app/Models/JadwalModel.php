<?php

namespace App\Models;

class JadwalModel extends BaseModel
{
    protected $table            = 'jadwal';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'kelas_id', 'semester_id', 'hari', 'jam_mulai', 'jam_selesai',
        'mata_pelajaran_id', 'guru_id',
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    protected $validationRules  = [
        'kelas_id'          => 'required|integer',
        'semester_id'       => 'required|integer',
        'hari'              => 'required|in_list[senin,selasa,rabu,kamis,jumat,sabtu]',
        'jam_mulai'         => 'required',
        'jam_selesai'       => 'required',
        'mata_pelajaran_id' => 'required|integer',
        'guru_id'           => 'required|integer',
    ];

    public function getJadwalByKelas($kelasId, $semesterId)
    {
        return $this->select('jadwal.*, mata_pelajaran.nama as mapel_nama,
                              users.nama_lengkap as guru_nama')
                    ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal.mata_pelajaran_id')
                    ->join('users', 'users.id = jadwal.guru_id')
                    ->where('jadwal.kelas_id', $kelasId)
                    ->where('jadwal.semester_id', $semesterId)
                    ->orderBy("FIELD(jadwal.hari, 'senin','selasa','rabu','kamis','jumat','sabtu')")
                    ->orderBy('jadwal.jam_mulai', 'ASC')
                    ->findAll();
    }

    public function getJadwalByGuru($guruId, $semesterId)
    {
        return $this->select('jadwal.*, mata_pelajaran.nama as mapel_nama,
                              CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas')
                    ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal.mata_pelajaran_id')
                    ->join('kelas', 'kelas.id = jadwal.kelas_id')
                    ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                    ->where('jadwal.guru_id', $guruId)
                    ->where('jadwal.semester_id', $semesterId)
                    ->orderBy("FIELD(jadwal.hari, 'senin','selasa','rabu','kamis','jumat','sabtu')")
                    ->orderBy('jadwal.jam_mulai', 'ASC')
                    ->findAll();
    }

    /**
 * Cek jadwal bentrok (kelas)
 */
public function cekBentrok($kelasId, $hari, $jamMulai, $jamSelesai, $semesterId, $excludeId = null)
{
    $builder = $this->where('kelas_id', $kelasId)
                    ->where('hari', $hari)
                    ->where('semester_id', $semesterId)
                    ->groupStart()
                    ->where('jam_mulai <', $jamSelesai)
                    ->where('jam_selesai >', $jamMulai)
                    ->groupEnd();

    if ($excludeId) {
        $builder->where('id !=', $excludeId);
    }

    return $builder->first();
}

// app/Models/JadwalModel.php
// app/Models/JadwalModel.php
public function getAllWithDetail()
{
    return $this->select('jadwal.*, 
                         mata_pelajaran.nama as mapel, 
                         CONCAT(kelas.tingkat," ",jurusan.singkatan," ",kelas.rombel) as nama_kelas,
                         users.nama_lengkap as nama_guru')
                ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal.mata_pelajaran_id')
                ->join('kelas', 'kelas.id = jadwal.kelas_id')
                ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')  // ✅ pakai 'left' bukan leftJoin
                ->join('users', 'users.id = jadwal.guru_id')
                ->findAll();
}
}