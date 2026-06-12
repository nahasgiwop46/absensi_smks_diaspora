<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LogAbsensiModel;

class LogAktivitas extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new LogAbsensiModel();
    }

    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $logs    = $this->logModel->getLogByTanggal($tanggal);

        $this->setPageTitle('Log Aktivitas');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Log Aktivitas');

        return $this->render('admin/log_aktivitas/index', [
            'logs'    => $logs,
            'tanggal' => $tanggal,
        ]);
    }
}