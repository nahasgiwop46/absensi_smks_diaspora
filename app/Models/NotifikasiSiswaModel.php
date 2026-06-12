<?php

namespace App\Models;

use CodeIgniter\Model;

class NotifikasiSiswaModel extends BaseModel
{
    protected $table            = 'notifikasi_siswa';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'siswa_id',
        'qr_session_id',
        'judul',
        'pesan',
        'tipe',
        'is_dibaca',
        'is_clicked',
        'read_at',
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    /**
     * Ambil notifikasi yang belum dibaca oleh siswa
     */
    public function getUnreadBySiswa($siswaId, $limit = 10)
    {
        return $this->select('
                notifikasi_siswa.*,
                qr_sessions.token,
                qr_sessions.expired_at,
                qr_sessions.is_active as session_active,
                mata_pelajaran.nama as mapel_nama,
                users.nama_lengkap as guru_nama
            ')
            ->join('qr_sessions', 'qr_sessions.id = notifikasi_siswa.qr_session_id', 'left')
            ->join('mata_pelajaran', 'mata_pelajaran.id = qr_sessions.mata_pelajaran_id', 'left')
            ->join('users', 'users.id = qr_sessions.dibuat_oleh', 'left')
            ->where('notifikasi_siswa.siswa_id', $siswaId)
            ->where('notifikasi_siswa.is_dibaca', 0)
            ->orderBy('notifikasi_siswa.created_at', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    /**
     * Tandai notifikasi sudah dibaca
     */
    public function tandaiDibaca($id)
    {
        return $this->update($id, [
            'is_dibaca' => 1,
            'read_at'   => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Tandai notifikasi sudah diklik (absen)
     */
    public function tandaiDiklik($id)
    {
        return $this->update($id, [
            'is_clicked' => 1,
        ]);
    }

    /**
     * Kirim notifikasi ke semua siswa dalam satu kelas
     */
    public function kirimKeKelas($kelasId, $qrSessionId, $judul, $pesan, $tipe = 'absen_dibuka')
    {
        $siswaModel = new \App\Models\SiswaModel();
        $siswaList = $siswaModel->getSiswaByKelas($kelasId);
        
        $data = [];
        foreach ($siswaList as $siswa) {
            $data[] = [
                'siswa_id'       => $siswa['id'],
                'qr_session_id'  => $qrSessionId,
                'judul'          => $judul,
                'pesan'          => $pesan,
                'tipe'           => $tipe,
                'created_at'     => date('Y-m-d H:i:s'),
            ];
        }
        
        if (!empty($data)) {
            return $this->db->table('notifikasi_siswa')->insertBatch($data);
        }
        
        return false;
    }

    /**
     * Hitung notifikasi belum dibaca
     */
    public function countUnread($siswaId)
    {
        return $this->where('siswa_id', $siswaId)
                    ->where('is_dibaca', 0)
                    ->countAllResults();
    }

    /**
     * Ambil riwayat notifikasi siswa
     */
    public function getRiwayat($siswaId, $limit = 50)
    {
        return $this->where('siswa_id', $siswaId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}