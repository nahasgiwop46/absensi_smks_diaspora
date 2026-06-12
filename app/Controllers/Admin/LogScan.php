<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LogScanQrModel;

class LogScan extends BaseController
{
    protected $logScanModel;

    public function __construct()
    {
        $this->logScanModel = new LogScanQrModel();
    }

    /**
     * Halaman log scan QR
     */
    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $qrSessionId = $this->request->getGet('qr_session_id');
        
        $rekap = [];
        if ($qrSessionId) {
            $rekap = $this->logScanModel->getRekapScanBySession($qrSessionId);
        }

        $this->setPageTitle('Log Scan QR');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Log Scan');

        return $this->render('admin/log_scan/index', [
            'rekap'         => $rekap,
            'qr_session_id' => $qrSessionId,
        ]);
    }

    /**
     * Deteksi potensi kecurangan
     */
    public function deteksiKecurangan($qrSessionId)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        // Cari device yang scan lebih dari 1 siswa berbeda
        $db = \Config\Database::connect();
        $builder = $db->table('log_scan_qr');
        $builder->select('device_info, COUNT(DISTINCT siswa_id) as total_siswa');
        $builder->where('qr_session_id', $qrSessionId);
        $builder->where('status_scan', 'berhasil');
        $builder->groupBy('device_info');
        $builder->having('total_siswa >', 1);
        
        $mencurigakan = $builder->get()->getResultArray();

        $this->setPageTitle('Deteksi Kecurangan');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Log Scan', '/admin/log-scan');
        $this->addBreadcrumb('Deteksi');

        return $this->render('admin/log_scan/deteksi', [
            'mencurigakan'  => $mencurigakan,
            'qr_session_id' => $qrSessionId,
        ]);
    }
}