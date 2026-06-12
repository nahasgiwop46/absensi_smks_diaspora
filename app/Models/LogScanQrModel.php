<?php

namespace App\Models;

use CodeIgniter\Model;

class LogScanQrModel extends BaseModel
{
    protected $table            = 'log_scan_qr';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    
    protected $allowedFields    = [
        'qr_session_id',
        'siswa_id',
        'status_scan',
        'pesan_error',
        'device_info',
        'koordinat',
        'ip_address',
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';

    /**
     * Catat percobaan scan
     */
    public function catatScan($data)
    {
        return $this->insert([
            'qr_session_id' => $data['qr_session_id'],
            'siswa_id'      => $data['siswa_id'] ?? null,
            'status_scan'   => $data['status_scan'],
            'pesan_error'   => $data['pesan_error'] ?? null,
            'device_info'   => $data['device_info'] ?? null,
            'koordinat'     => $data['koordinat'] ?? null,
            'ip_address'    => $data['ip_address'] ?? null,
        ]);
    }

    /**
     * Cek apakah siswa sudah pernah scan di sesi ini
     */
    public function cekSudahScan($qrSessionId, $siswaId)
    {
        return $this->where('qr_session_id', $qrSessionId)
                    ->where('siswa_id', $siswaId)
                    ->where('status_scan', 'berhasil')
                    ->first();
    }

    /**
     * Rekap scan per sesi
     */
    public function getRekapScanBySession($qrSessionId)
    {
        $builder = $this->db->table('log_scan_qr');
        $builder->select('status_scan, COUNT(*) as total');
        $builder->where('qr_session_id', $qrSessionId);
        $builder->groupBy('status_scan');
        
        return $builder->get()->getResultArray();
    }

    /**
     * Deteksi kecurangan: 1 device scan banyak siswa
     */
    public function deteksiKecurangan($qrSessionId, $deviceInfo)
    {
        return $this->where('qr_session_id', $qrSessionId)
                    ->where('device_info', $deviceInfo)
                    ->where('status_scan', 'berhasil')
                    ->countAllResults();
    }
}