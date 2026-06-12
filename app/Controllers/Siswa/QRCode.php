<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasSiswaModel;

class QRCode extends BaseController
{
    protected $siswaModel;
    protected $kelasSiswaModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->kelasSiswaModel = new KelasSiswaModel();
    }

    /**
     * Ambil ID siswa dari session
     */
    private function getSiswaId()
    {
        $userId = session()->get('user_id');
        $username = session()->get('username');

        $siswa = $this->siswaModel->where('user_id', $userId)->first();
        if ($siswa) return $siswa['id'];

        $siswa = $this->siswaModel->where('nis', $username)->first();
        if ($siswa) return $siswa['id'];

        return null;
    }

    /**
     * Halaman QR Code siswa
     */
    public function index()
    {
        if ($redirect = $this->requireRole('siswa')) {
            return $redirect;
        }

        $siswaId = $this->getSiswaId();
        
        if (!$siswaId) {
            return redirect()->to('/siswa')->with('error', 'Data siswa tidak ditemukan.');
        }

        $siswa = $this->siswaModel->find($siswaId);

        // Ambil kelas aktif
        $kelasAktif = $this->kelasSiswaModel
            ->select('kelas_siswa.*, CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas')
            ->join('kelas', 'kelas.id = kelas_siswa.kelas_id')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->where('kelas_siswa.siswa_id', $siswaId)
            ->where('kelas_siswa.status', 'aktif')
            ->first();

        // Generate QR code jika belum ada
        if (empty($siswa['qr_code'])) {
            $siswa['qr_code'] = 'QR-' . strtoupper(bin2hex(random_bytes(16)));
            $this->siswaModel->update($siswaId, ['qr_code' => $siswa['qr_code']]);
        }

        $this->setPageTitle('QR Code Saya');
        $this->addBreadcrumb('Dashboard', '/siswa');
        $this->addBreadcrumb('QR Code');

        return $this->render('siswa/qrcode', [
            'title'      => 'QR Code Saya',
            'siswa'      => $siswa,
            'kelasAktif' => $kelasAktif,
        ]);
    }
}