<?php

namespace App\Models;

class KelasSiswaModel extends BaseModel
{
    protected $table            = 'kelas_siswa';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;  // ✅ Tabel tidak ada deleted_at
    
    protected $allowedFields    = [
        'siswa_id', 'kelas_id', 'tahun_ajaran_id', 'semester_id',
        'wali_kelas_id', 'tanggal_masuk', 'tanggal_keluar', 'status', 'keterangan',
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';
    
    protected $validationRules  = [
        'siswa_id'        => 'required|integer',
        'kelas_id'        => 'required|integer',
        'tahun_ajaran_id' => 'required|integer',
        'status'          => 'required|in_list[aktif,pindah,lulus,dikeluarkan,mengundurkan_diri]',
    ];

    public function getRiwayatKelasSiswa($siswaId)
    {
        return $this->select('kelas_siswa.*, 
                              CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas,
                              tahun_ajaran.nama as tahun_ajaran_nama')
                    ->join('kelas', 'kelas.id = kelas_siswa.kelas_id')
                    ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                    ->join('tahun_ajaran', 'tahun_ajaran.id = kelas_siswa.tahun_ajaran_id')
                    ->where('kelas_siswa.siswa_id', $siswaId)
                    ->orderBy('kelas_siswa.tanggal_masuk', 'DESC')
                    ->findAll();
    }

    public function getSiswaByKelasAktif($kelasId, $tahunAjaranId)
    {
        return $this->select('kelas_siswa.*, siswa.nis, siswa.nisn, siswa.nama_lengkap')
                    ->join('siswa', 'siswa.id = kelas_siswa.siswa_id')
                    ->where('kelas_siswa.kelas_id', $kelasId)
                    ->where('kelas_siswa.tahun_ajaran_id', $tahunAjaranId)
                    ->where('kelas_siswa.status', 'aktif')
                    ->where('siswa.deleted_at', null)
                    ->orderBy('siswa.nama_lengkap', 'ASC')
                    ->findAll();
    }

    // Tambah method cek siswa di kelas aktif
public function getKelasAktifSiswa($siswaId)
{
    return $this->select('kelas_siswa.*, 
                         CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas')
                ->join('kelas', 'kelas.id = kelas_siswa.kelas_id')
                ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                ->where('kelas_siswa.siswa_id', $siswaId)
                ->where('kelas_siswa.status', 'aktif')
                ->first();
}
}