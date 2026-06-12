<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\QrSessionsModel;
use App\Models\KelasModel;
use App\Models\JadwalModel;
use App\Models\SemesterModel;

class QrCode extends BaseController
{
    protected $qrSessionsModel;
    protected $kelasModel;
    protected $jadwalModel;
    protected $semesterModel;

    public function __construct()
    {
        $this->qrSessionsModel = new QrSessionsModel();
        $this->kelasModel = new KelasModel();
        $this->jadwalModel = new JadwalModel();
        $this->semesterModel = new SemesterModel();
    }

    public function index()
    {
        $guruId = session()->get('user_id');
        $semesterAktif = $this->semesterModel->getAktif();

        $jadwal_list = [];
        if ($semesterAktif) {
            $jadwal_list = $this->jadwalModel
                ->select('jadwal.*, mata_pelajaran.nama as mapel, CONCAT(kelas.tingkat," ",jurusan.singkatan," ",kelas.rombel) as nama_kelas')
                ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal.mata_pelajaran_id')
                ->join('kelas', 'kelas.id = jadwal.kelas_id')
                ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                ->where('jadwal.guru_id', $guruId)
                ->where('jadwal.semester_id', $semesterAktif['id'])
                ->orderBy('jadwal.hari', 'ASC')
                ->orderBy('jadwal.jam_mulai', 'ASC')
                ->findAll();
        }

        $qrAktif = $this->qrSessionsModel->getAktifByGuru($guruId);

        return $this->render('guru/qrcode/index', [
            'title'        => 'Generate QR Code',
            'jadwal_list'  => $jadwal_list,
            'qr_aktif'     => $qrAktif,
        ]);
    }

    public function generate()
    {
        $jadwalId       = $this->request->getPost('jadwal_id');
        $menitBerlaku   = (int)($this->request->getPost('menit_berlaku') ?? 15);
        $metodeTampilan = $this->request->getPost('metode_tampilan') ?? 'layar_laptop';

        if (empty($jadwalId)) {
            return redirect()->back()->with('error', 'Jadwal harus dipilih!');
        }

        $jadwal = $this->jadwalModel->find($jadwalId);
        if (!$jadwal) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan!');
        }

        $token         = bin2hex(random_bytes(32));
        $kodeCadangan  = strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
        $qrImageUrl    = base_url("qr/generate/{$token}");

        // ✅ WIT (Asia/Jayapura)
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jayapura'));
        $expired = clone $now;
        $expired->add(new \DateInterval("PT{$menitBerlaku}M"));

        $jamMulai   = $now->format('Y-m-d H:i:s');
        $jamSelesai = $expired->format('Y-m-d H:i:s');

        $qrSessionId = $this->qrSessionsModel->insert([
            'jadwal_id'          => $jadwalId,
            'mata_pelajaran_id'  => $jadwal['mata_pelajaran_id'],
            'token'              => $token,
            'jenis_absensi'      => 'masuk',
            'tanggal'            => $now->format('Y-m-d'),
            'dibuat_oleh'        => session()->get('user_id'),
            'kelas_id'           => $jadwal['kelas_id'],
            'jam_mulai_absen'    => $jamMulai,
            'jam_selesai_absen'  => $jamSelesai,
            'durasi_menit'       => $menitBerlaku,
            'expired_at'         => $jamSelesai,
            'is_active'          => 1,
            'qr_image_url'       => $qrImageUrl,
            'kode_cadangan'      => $kodeCadangan,
            'metode_tampilan'    => $metodeTampilan,
        ]);

        $this->qrSessionsModel->nonaktifkanExpired();

        $notifikasiModel = new \App\Models\NotifikasiSiswaModel();
        $kelas = $this->kelasModel->find($jadwal['kelas_id']);
        $mapelModel = new \App\Models\MataPelajaranModel();
        $mapel = $mapelModel->find($jadwal['mata_pelajaran_id']);
        
        $notifikasiModel->kirimKeKelas(
            $jadwal['kelas_id'],
            $qrSessionId,
            'Absen Dibuka!',
            "Guru membuka absen {$mapel['nama']} kelas {$kelas['tingkat']} {$kelas['rombel']}. Segera absen sebelum pukul " . $expired->format('H:i') . " WIT!",
            'absen_dibuka'
        );

        return redirect()->to('/guru/qrcode')
            ->with('success', 'QR Code berhasil!')
            ->with('token', substr($token, 0, 8))
            ->with('kode_cadangan', $kodeCadangan)
            ->with('qr_image_url', $qrImageUrl)
            ->with('expired_at', $jamSelesai);
    }

    public function tampilkan($token)
    {
        $session = $this->qrSessionsModel->getByToken($token);
        if (!$session) {
            return redirect()->to('/guru/qrcode')->with('error', 'QR Code sudah expired.');
        }

        if ($session['dibuat_oleh'] != session()->get('user_id')) {
            return redirect()->to('/guru/qrcode')->with('error', 'Akses ditolak.');
        }

        $kelas = $this->kelasModel->find($session['kelas_id']);
        $mapelModel = new \App\Models\MataPelajaranModel();
        $mapel = $mapelModel->find($session['mata_pelajaran_id']);

        return $this->render('guru/qrcode/tampilkan', [
            'title'    => 'Tampilkan QR Code',
            'session'  => $session,
            'kelas'    => $kelas,
            'mapel'    => $mapel,
        ]);
    }

    public function tutup($id)
    {
        $this->qrSessionsModel->update($id, ['is_active' => 0]);

        $session = $this->qrSessionsModel->find($id);
        if ($session) {
            $notifikasiModel = new \App\Models\NotifikasiSiswaModel();
            $notifikasiModel->kirimKeKelas(
                $session['kelas_id'],
                $id,
                'Absen Ditutup',
                'Sesi absen telah ditutup. Bagi yang belum absen, silakan hubungi guru.',
                'absen_ditutup'
            );
        }

        return redirect()->to('/guru/qrcode')->with('success', 'Sesi absen ditutup.');
    }

    public function nonaktifkan($id)
    {
        $this->qrSessionsModel->update($id, ['is_active' => 0]);
        return redirect()->to('/guru/qrcode')->with('success', 'QR Code dinonaktifkan.');
    }

    public function stop()
    {
        $guruId = session()->get('user_id');
        $this->qrSessionsModel->where('dibuat_oleh', $guruId)
            ->where('is_active', 1)
            ->set(['is_active' => 0])
            ->update();
        return redirect()->to('/guru/qrcode')->with('success', 'Semua QR dihentikan.');
    }
}