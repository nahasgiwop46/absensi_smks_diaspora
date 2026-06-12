<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\AbsensiModel;
use App\Models\QrSessionsModel;

class Scan extends BaseController
{
    protected $db;
    protected $siswaModel;
    protected $absensiModel;
    protected $qrSessionsModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        $this->absensiModel = new AbsensiModel();
        $this->qrSessionsModel = new QrSessionsModel();

      if (session()->get('role_kode') !== 'guru') {
    header('Location: ' . base_url('login'));
    exit;  // ✅
}
    }

    /**
     * Halaman scan QR siswa
     */
    public function index()
    {
        $guruId = session()->get('user_id');
        $tanggal = date('Y-m-d');

        $scanHistory = $this->absensiModel
            ->select('absensi.*, siswa.nis, siswa.nama_lengkap, master_status_absensi.label as status_label')
            ->join('siswa', 'siswa.id = absensi.siswa_id')
            ->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id')
            ->where('absensi.petugas_id', $guruId)
            ->where('absensi.tanggal', $tanggal)
            ->where('absensi.metode_absensi', 'scan_qr_guru')
            ->orderBy('absensi.jam_absen', 'DESC')
            ->limit(20)
            ->findAll();

        return $this->render('guru/scan/index', [
            'title'       => 'Scan QR Siswa',
            'scanHistory' => $scanHistory,
        ]);
    }

    /**
     * Proses scan QR siswa (oleh guru)
     */
public function proses()
{
    $json = $this->request->getJSON();
    if ($json) {
        $qrData = $json->qr_data ?? null;
    } else {
        $qrData = $this->request->getPost('qr_data');
    }

    if (empty($qrData)) {
        return $this->response->setJSON(['success' => false, 'message' => 'QR Code tidak terbaca']);
    }

    $siswa = null;
    $decoded = json_decode(base64_decode($qrData), true);
    
    if ($decoded && isset($decoded['nis'])) {
        $siswa = $this->siswaModel->where('nis', $decoded['nis'])->first();
        $token = $decoded['token'] ?? null;
        $jadwalId = $decoded['jadwal_id'] ?? null;
    } else {
        $siswa = $this->siswaModel->where('nis', $qrData)->first();
        if (!$siswa) {
            $siswa = $this->siswaModel->where('qr_code', $qrData)->first();
        }
        $token = null;
        $jadwalId = null;
    }

    if (!$siswa) {
        return $this->response->setJSON(['success' => false, 'message' => 'Siswa tidak ditemukan']);
    }

    $tanggal = date('Y-m-d');
    $jamAbsen = date('Y-m-d H:i:s');
    $qrSessionId = null;
    $jamMulai = null;

    if ($token) {
        $qrSession = $this->qrSessionsModel->where('token', $token)
            ->where('is_active', 1)
            ->where('expired_at >', date('Y-m-d H:i:s'))
            ->first();
    }
    
    // ✅ FALLBACK: Cari sesi aktif untuk kelas siswa
    if (empty($qrSession)) {
        $db = \Config\Database::connect();
        $kelasSiswa = $db->table('kelas_siswa')
            ->where('siswa_id', $siswa['id'])
            ->where('status', 'aktif')
            ->get()->getRowArray();

        if ($kelasSiswa) {
            $qrSession = $this->qrSessionsModel
                ->where('kelas_id', $kelasSiswa['kelas_id'])
                ->where('is_active', 1)
                ->where('expired_at >', date('Y-m-d H:i:s'))
                ->orderBy('id', 'DESC')
                ->first();
        }
    }
    
    if (!empty($qrSession)) {
        $qrSessionId = $qrSession['id'];
        $jamMulai = $qrSession['jam_mulai_absen'];

        if ($this->absensiModel->cekSudahAbsenPerSesi($siswa['id'], $qrSessionId)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Siswa sudah absen di sesi ini']);
        }
    }

    $toleransiMenit = 30;
    $statusId = 1;
    $menitTerlambat = 0;

    if ($jamMulai) {
        $selisih = strtotime($jamAbsen) - strtotime($jamMulai);
        if ($selisih > ($toleransiMenit * 60)) {
            $statusId = 2;
            $menitTerlambat = round($selisih / 60);
        }
    }

    $this->absensiModel->insert([
        'siswa_id'            => $siswa['id'],
        'jadwal_id'           => $jadwalId,
        'qr_session_id'       => $qrSessionId,     // ✅ PASTIKAN TERISI
        'tanggal'             => $tanggal,
        'jam_absen'           => $jamAbsen,
        'tipe_absensi'        => 'hadir',
        'status_id'           => $statusId,
        'menit_keterlambatan' => $menitTerlambat,
        'metode_absensi'      => 'scan_qr_guru',
        'petugas_id'          => session()->get('user_id'),
    ]);

    return $this->response->setJSON([
        'success' => true,
        'message' => 'Absensi berhasil!',
        'siswa'   => [
            'nama'   => $siswa['nama_lengkap'],
            'nis'    => $siswa['nis'],
            'waktu'  => date('H:i:s'),
            'status' => $statusId == 1 ? 'Hadir' : 'Terlambat (' . $menitTerlambat . ' menit)',
        ]
    ]);
}
}