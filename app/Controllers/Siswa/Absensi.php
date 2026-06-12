<?php

namespace App\Controllers\Siswa;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\SiswaModel;
use App\Models\LogScanQrModel;

class Absensi extends BaseController
{
    protected $absensiModel;
    protected $siswaModel;
    protected $logScanModel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->siswaModel   = new SiswaModel();
        $this->logScanModel = new LogScanQrModel();
        
        if (session()->get('role_kode') !== 'siswa') {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    // ==================== HELPER ====================

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

    private function now()
    {
        return new \DateTime('now', new \DateTimeZone('Asia/Jayapura'));
    }

    // ==================== RIWAYAT ABSENSI ====================

    public function index()
    {
        if ($redirect = $this->requireRole('siswa')) {
            return $redirect;
        }

        $siswaId = $this->getSiswaId();
        if (!$siswaId) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        $riwayat = $this->absensiModel
            ->select('absensi.*, master_status_absensi.label as status_label, master_status_absensi.warna as status_warna, mata_pelajaran.nama as mapel')
            ->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id')
            ->join('jadwal', 'jadwal.id = absensi.jadwal_id', 'left')
            ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal.mata_pelajaran_id', 'left')
            ->where('absensi.siswa_id', $siswaId)
            ->where('absensi.deleted_at', null)
            ->orderBy('absensi.tanggal', 'DESC')
            ->orderBy('absensi.jam_absen', 'DESC')
            ->findAll();

        $this->setPageTitle('Riwayat Absensi');
        $this->addBreadcrumb('Dashboard', '/siswa');
        $this->addBreadcrumb('Riwayat Absensi');

        return $this->render('siswa/absensi/index', [
            'title'   => 'Riwayat Absensi',
            'riwayat' => $riwayat,
        ]);
    }

    // ==================== KONFIRMASI ABSEN (MANUAL/KODE CADANGAN) ====================

    public function konfirmasi()
    {
        if ($redirect = $this->requireRole('siswa')) {
            return $redirect;
        }

        // ✅ Support JSON dan POST
        $json = $this->request->getJSON();
        if ($json) {
            $qrSessionId = $json->qr_session_id ?? null;
            $kodeInput   = $json->kode ?? null;
            $deviceInfo  = $json->device_info ?? null;
            $koordinat   = $json->koordinat ?? null;
        } else {
            $qrSessionId = $this->request->getPost('qr_session_id');
            $kodeInput   = $this->request->getPost('kode');
            $deviceInfo  = $this->request->getPost('device_info');
            $koordinat   = $this->request->getPost('koordinat');
        }

        if (!$qrSessionId) {
            return $this->jsonError('Data tidak lengkap.');
        }

        $siswaId = $this->getSiswaId();
        if (!$siswaId) {
            return $this->jsonError('Data siswa tidak ditemukan.');
        }

        $db  = \Config\Database::connect();
        $now = $this->now();
        $ipAddress = $this->request->getIPAddress();

        // ✅ 1. Cek sesi
        $session = $db->table('qr_sessions')->where('id', $qrSessionId)->get()->getRowArray();
        if (!$session || $session['is_active'] != 1) {
            return $this->jsonError('Sesi absen sudah ditutup!');
        }
        if (strtotime($session['expired_at']) < $now->getTimestamp()) {
            return $this->jsonError('Waktu absen sudah HABIS!');
        }

        // ✅ 2. Validasi kode HANYA jika kode dikirim (input manual)
        if (!empty($kodeInput)) {
            if (!empty($session['kode_cadangan']) && strtoupper($kodeInput) !== strtoupper($session['kode_cadangan'])) {
                // Catat log gagal
                $this->logScanModel->catatScan([
                    'qr_session_id' => $qrSessionId,
                    'siswa_id'      => $siswaId,
                    'status_scan'   => 'gagal',
                    'pesan_error'   => 'Kode cadangan tidak valid',
                    'device_info'   => $deviceInfo,
                    'ip_address'    => $ipAddress,
                ]);
                return $this->jsonError('Kode cadangan TIDAK VALID!');
            }
        }

        // ✅ 3. Cek duplikat
        $sudahAbsen = $db->table('absensi')
            ->where('siswa_id', $siswaId)
            ->where('qr_session_id', $qrSessionId)
            ->where('deleted_at', null)
            ->get()
            ->getRowArray();

        if ($sudahAbsen) {
            return $this->jsonError('Anda SUDAH absen di sesi ini!');
        }

        // ✅ 4. Hitung keterlambatan
        $jamAbsen = $now->format('Y-m-d H:i:s');
        $menitTerlambat = 0;
        if (!empty($session['jam_mulai_absen'])) {
            $selisih = strtotime($jamAbsen) - strtotime($session['jam_mulai_absen']);
            if ($selisih > 0) {
                $menitTerlambat = round($selisih / 60);
            }
        }

        // ✅ 5. Ambil user agent dengan aman
        $userAgent = null;
        try {
            $userAgent = $this->request->getUserAgent()->getAgentString();
        } catch (\Exception $e) {
            $userAgent = $deviceInfo ?? 'Unknown';
        }

        // ✅ 6. Tentukan metode absensi
        $metodeAbsensi = !empty($kodeInput) ? 'scan_qr_siswa' : 'scan_qr_siswa';
        // Jika pakai kode cadangan, tetap dianggap scan_qr_siswa (karena via HP siswa)

        // ✅ 7. Simpan absensi
        $db->table('absensi')->insert([
            'siswa_id'            => $siswaId,
            'qr_session_id'       => $qrSessionId,
            'tanggal'             => $now->format('Y-m-d'),
            'jam_absen'           => $jamAbsen,
            'tipe_absensi'        => 'hadir',
            'status_id'           => $menitTerlambat > 0 ? 2 : 1,
            'metode_absensi'      => $metodeAbsensi,
            'menit_keterlambatan' => $menitTerlambat,
            'device_siswa'        => $deviceInfo,
            'user_agent_siswa'    => $userAgent,
            'koordinat_siswa'     => $koordinat,
        ]);

        // ✅ 8. Catat log berhasil
        $this->logScanModel->catatScan([
            'qr_session_id' => $qrSessionId,
            'siswa_id'      => $siswaId,
            'status_scan'   => 'berhasil',
            'device_info'   => $deviceInfo,
            'koordinat'     => $koordinat,
            'ip_address'    => $ipAddress,
        ]);

        return $this->jsonSuccess('Absen berhasil!', [
            'waktu_absen'         => $now->format('H:i:s') . ' WIT',
            'status'              => $menitTerlambat > 0 ? 'Terlambat' : 'Hadir',
            'menit_keterlambatan' => $menitTerlambat,
        ]);
    }
}