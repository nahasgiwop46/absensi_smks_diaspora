<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\QrSessionsModel;
use App\Models\SiswaModel;
use App\Models\AbsensiModel;
use App\Models\LogScanQrModel;

class Scan extends BaseController
{
    protected $qrSessionsModel;
    protected $siswaModel;
    protected $absensiModel;
    protected $logScanModel;

    public function __construct()
    {
        $this->qrSessionsModel = new QrSessionsModel();
        $this->siswaModel      = new SiswaModel();
        $this->absensiModel    = new AbsensiModel();
        $this->logScanModel    = new LogScanQrModel();
    }

    private function getSiswaId()
    {
        $userId   = session()->get('user_id');
        $username = session()->get('username');

        $siswa = $this->siswaModel->where('user_id', $userId)->first();
        if ($siswa) return $siswa['id'];

        $siswa = $this->siswaModel->where('nis', $username)->first();
        if ($siswa) return $siswa['id'];

        return null;
    }

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

        $this->setPageTitle('Scan QR Code');
        $this->addBreadcrumb('Dashboard', '/siswa');
        $this->addBreadcrumb('Scan QR');

        return $this->render('siswa/scan', [
            'title' => 'Scan QR Code',
            'siswa' => $siswa,
        ]);
    }

    /**
     * API: Verifikasi token QR
     */
    public function verifikasi()
    {
        if ($redirect = $this->requireRole('siswa')) {
            return $redirect;
        }

        $token      = $this->request->getPost('token');
        $deviceInfo = $this->request->getPost('device_info') ?? null;
        $ipAddress  = $this->request->getIPAddress();

        if (!$token) {
            return $this->jsonError('Token QR tidak valid.');
        }

        // Validasi sesi
        $session = $this->qrSessionsModel->getByToken($token);
        if (!$session) {
            $this->logScanModel->catatScan([
                'qr_session_id' => 0,
                'siswa_id'      => null,
                'status_scan'   => 'expired',
                'pesan_error'   => 'Token expired atau tidak valid',
                'device_info'   => $deviceInfo,
                'ip_address'    => $ipAddress,
            ]);
            return $this->jsonError('QR Code sudah expired atau tidak valid.');
        }

        // Ambil data siswa
        $siswaId = $this->getSiswaId();
        if (!$siswaId) {
            return $this->jsonError('Data siswa tidak ditemukan.');
        }

        $siswa = $this->siswaModel->find($siswaId);

        // Cek duplikat per sesi
        $sudahAbsen = $this->absensiModel->cekSudahAbsenPerSesi($siswaId, $session['id']);
        if ($sudahAbsen) {
            $this->logScanModel->catatScan([
                'qr_session_id' => $session['id'],
                'siswa_id'      => $siswaId,
                'status_scan'   => 'duplikat',
                'pesan_error'   => 'Sudah absen di sesi ini',
                'device_info'   => $deviceInfo,
                'ip_address'    => $ipAddress,
            ]);
            return $this->jsonError('Anda sudah absen di sesi ini.');
        }

        // Return data untuk konfirmasi
        return $this->jsonSuccess('QR Valid', [
            'session' => [
                'id'                => $session['id'],
                'mapel_nama'        => $session['mapel_nama'] ?? '-',
                'guru_nama'         => $session['guru_nama'] ?? '-',
                'nama_kelas'        => $session['nama_kelas'] ?? '-',
                'jam_selesai_absen' => $session['jam_selesai_absen'] ?? null,
            ],
            'siswa' => [
                'id'           => $siswa['id'],
                'nis'          => $siswa['nis'],
                'nama_lengkap' => $siswa['nama_lengkap'],
                'foto'         => $siswa['foto'] ?? null,
            ],
        ]);
    }

    /**
     * API: Konfirmasi absen
     */
   public function konfirmasi()
{
    if ($redirect = $this->requireRole('siswa')) {
        return $redirect;
    }

    $qrSessionId = $this->request->getPost('qr_session_id');
    $deviceInfo  = $this->request->getPost('device_info') ?? null;
    $koordinat   = $this->request->getPost('koordinat') ?? null;
    $ipAddress   = $this->request->getIPAddress();

    if (!$qrSessionId) {
        return $this->jsonError('Data tidak lengkap.');
    }

    $siswaId = $this->getSiswaId();
    if (!$siswaId) {
        return $this->jsonError('Data siswa tidak ditemukan.');
    }

    $sudahAbsen = $this->absensiModel->cekSudahAbsenPerSesi($siswaId, $qrSessionId);
    if ($sudahAbsen) {
        return $this->jsonError('Anda sudah absen di sesi ini.');
    }

    $session = $this->qrSessionsModel->find($qrSessionId);
    
    // ✅ TOLERANSI 30 MENIT
    $toleransiMenit = 30;
    $jamAbsen = date('Y-m-d H:i:s');
    $menitTerlambat = 0;

    if ($session && !empty($session['jam_mulai_absen'])) {
        $jamMulai = strtotime($session['jam_mulai_absen']);
        $jamAbsenTime = strtotime($jamAbsen);
        $selisih = $jamAbsenTime - $jamMulai;
        
        if ($selisih > ($toleransiMenit * 60)) {
            $menitTerlambat = round($selisih / 60);
        }
    }

    $userAgent = null;
    try {
        $userAgent = $this->request->getUserAgent()->getAgentString();
    } catch (\Exception $e) {
        $userAgent = $deviceInfo ?? 'Unknown';
    }

    $this->absensiModel->insert([
        'siswa_id'            => $siswaId,
        'qr_session_id'       => $qrSessionId,
        'tanggal'             => date('Y-m-d'),
        'jam_absen'           => $jamAbsen,
        'tipe_absensi'        => 'hadir',
        'status_id'           => $menitTerlambat > 0 ? 2 : 1,
        'metode_absensi'      => 'scan_qr_siswa',
        'menit_keterlambatan' => $menitTerlambat,
        'device_siswa'        => $deviceInfo,
        'user_agent_siswa'    => $userAgent,
        'koordinat_siswa'     => $koordinat,
    ]);

    $this->logScanModel->catatScan([
        'qr_session_id' => $qrSessionId,
        'siswa_id'      => $siswaId,
        'status_scan'   => 'berhasil',
        'device_info'   => $deviceInfo,
        'koordinat'     => $koordinat,
        'ip_address'    => $ipAddress,
    ]);

    return $this->jsonSuccess('Absen berhasil!', [
        'waktu_absen'         => date('H:i:s'),
        'status'              => $menitTerlambat > 0 ? 'Terlambat' : 'Hadir',
        'menit_keterlambatan' => $menitTerlambat,
    ]);
}
}