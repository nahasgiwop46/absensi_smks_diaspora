<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotifikasiSiswaModel;
use App\Models\KelasModel;

class Notifikasi extends BaseController
{
    protected $notifikasiModel;
    protected $kelasModel;

    public function __construct()
    {
        $this->notifikasiModel = new NotifikasiSiswaModel();
        $this->kelasModel = new KelasModel();
    }

    /**
     * Halaman riwayat notifikasi
     */
    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Riwayat Notifikasi');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Notifikasi');

        return $this->render('admin/notifikasi/index', [
            'kelas' => $this->kelasModel->getActiveKelas(),
        ]);
    }

    /**
     * Kirim notifikasi massal ke kelas
     */
    public function kirimMassal()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $kelasId = $this->request->getPost('kelas_id');
        $qrSessionId = $this->request->getPost('qr_session_id');
        $judul = $this->request->getPost('judul');
        $pesan = $this->request->getPost('pesan');
        $tipe = $this->request->getPost('tipe') ?? 'info';

        if (!$kelasId || !$judul || !$pesan) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        $result = $this->notifikasiModel->kirimKeKelas($kelasId, $qrSessionId, $judul, $pesan, $tipe);

        if ($result) {
            $this->setFlashSuccess('Notifikasi berhasil dikirim!');
        } else {
            $this->setFlashError('Gagal mengirim notifikasi.');
        }

        return redirect()->to('/admin/notifikasi');
    }
}