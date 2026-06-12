<?php

namespace App\Models;

use CodeIgniter\Model;

class QrSessionsModel extends Model
{
    protected $table            = 'qr_sessions';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'jadwal_id', 'mata_pelajaran_id', 'token', 'jenis_absensi',
        'tanggal', 'dibuat_oleh', 'kelas_id', 'jam_mulai_absen',
        'jam_selesai_absen', 'durasi_menit', 'expired_at', 'is_active',
        'qr_image_url', 'kode_cadangan', 'metode_tampilan',
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    // ==================== HELPER ====================
    
    private function now()
    {
        return (new \DateTime('now', new \DateTimeZone('Asia/Jayapura')))->format('Y-m-d H:i:s');
    }

    private function baseSelect()
    {
        return $this->select('
            qr_sessions.*,
            mata_pelajaran.nama as mapel_nama,
            users.nama_lengkap as guru_nama,
            CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas
        ')
        ->join('mata_pelajaran', 'mata_pelajaran.id = qr_sessions.mata_pelajaran_id', 'left')
        ->join('users', 'users.id = qr_sessions.dibuat_oleh', 'left')
        ->join('kelas', 'kelas.id = qr_sessions.kelas_id', 'left')
        ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left');
    }

    // ==================== QUERY ====================

    public function getAktifByGuru(int $guruId)
    {
        return $this->baseSelect()
            ->where('qr_sessions.dibuat_oleh', $guruId)
            ->where('qr_sessions.is_active', 1)
            ->where('qr_sessions.expired_at >', $this->now())
            ->orderBy('qr_sessions.created_at', 'DESC')
            ->findAll();
    }

    public function getByToken($token)
    {
        return $this->baseSelect()
            ->where('qr_sessions.token', $token)
            ->where('qr_sessions.is_active', 1)
            ->where('qr_sessions.expired_at >', $this->now())
            ->first();
    }

    public function getAktifByKelas($kelasId)
    {
        return $this->baseSelect()
            ->where('qr_sessions.kelas_id', $kelasId)
            ->where('qr_sessions.is_active', 1)
            ->where('qr_sessions.expired_at >', $this->now())
            ->orderBy('qr_sessions.created_at', 'DESC')
            ->findAll();
    }

    public function nonaktifkanExpired()
    {
        return $this->where('is_active', 1)
                    ->where('expired_at <', $this->now())
                    ->set(['is_active' => 0])
                    ->update();
    }

    // Tambah method cek session masih aktif
public function isSessionActive($token)
{
    $session = $this->where('token', $token)
                    ->where('is_active', 1)
                    ->where('expired_at >', $this->now())
                    ->first();
    
    return $session !== null;
}

// Tambah method hitung total absen di session
public function countAbsenBySession($sessionId)
{
    return $this->db->table('absensi')
                    ->where('qr_session_id', $sessionId)
                    ->where('deleted_at', null)
                    ->countAllResults();
}
}