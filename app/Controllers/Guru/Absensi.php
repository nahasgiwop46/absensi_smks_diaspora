<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\KelasModel;
use App\Models\KelasSiswaModel;
use App\Models\SiswaModel;
use App\Models\MasterStatusAbsensiModel;
use App\Models\SemesterModel;
use App\Models\JadwalModel;

class Absensi extends BaseController
{
    protected $absensiModel;
    protected $kelasModel;
    protected $kelasSiswaModel;
    protected $siswaModel;
    protected $statusAbsensiModel;
    protected $semesterModel;
    protected $jadwalModel;

    public function __construct()
    {
        $this->absensiModel       = new AbsensiModel();
        $this->kelasModel         = new KelasModel();
        $this->kelasSiswaModel    = new KelasSiswaModel();
        $this->siswaModel         = new SiswaModel();
        $this->statusAbsensiModel = new MasterStatusAbsensiModel();
        $this->semesterModel      = new SemesterModel();
        $this->jadwalModel        = new JadwalModel();

        if (session()->get('role_kode') !== 'guru') {
            header('Location: ' . base_url('login'));
            exit;
        }
    }

    // ==================== INDEX ====================
    public function index()
    {
        $guruId = session()->get('user_id');
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $kelasId = $this->request->getGet('kelas');

        $semesterAktif = $this->semesterModel->getAktif();
        $kelasIds = [];
        if ($semesterAktif) {
            $jadwalKelas = $this->jadwalModel
                ->select('kelas_id')
                ->where('guru_id', $guruId)
                ->where('semester_id', $semesterAktif['id'])
                ->distinct()
                ->findAll();
            $kelasIds = array_column($jadwalKelas, 'kelas_id');
        }

        $siswaList = [];
        $statusAbsensi = $this->statusAbsensiModel->findAll();
        
        if ($kelasId) {
            $siswaList = $this->kelasSiswaModel
                ->select('siswa.id, siswa.nis, siswa.nama_lengkap')
                ->join('siswa', 'siswa.id = kelas_siswa.siswa_id')
                ->where('kelas_siswa.kelas_id', $kelasId)
                ->where('kelas_siswa.status', 'aktif')
                ->where('siswa.is_active', 1)
                ->where('siswa.deleted_at', null)
                ->orderBy('siswa.nama_lengkap', 'ASC')
                ->findAll();
        } elseif (!empty($kelasIds)) {
            $siswaList = $this->kelasSiswaModel
                ->select('siswa.id, siswa.nis, siswa.nama_lengkap, 
                          CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas')
                ->join('siswa', 'siswa.id = kelas_siswa.siswa_id')
                ->join('kelas', 'kelas.id = kelas_siswa.kelas_id')
                ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                ->whereIn('kelas_siswa.kelas_id', $kelasIds)
                ->where('kelas_siswa.status', 'aktif')
                ->where('siswa.is_active', 1)
                ->where('siswa.deleted_at', null)
                ->orderBy('siswa.nama_lengkap', 'ASC')
                ->findAll();
        }

        // ✅ Ambil data absensi dengan join status
        foreach ($siswaList as &$s) {
            $absen = $this->absensiModel
                ->select('absensi.*, master_status_absensi.label as status_label')
                ->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id', 'left')
                ->where('absensi.siswa_id', $s['id'])
                ->where('absensi.tanggal', $tanggal)
                ->where('absensi.deleted_at', null)
                ->first();
            $s['sudah_absen'] = !empty($absen);
            $s['status_label'] = $absen['status_label'] ?? '-';
            $s['jam_absen'] = $absen['jam_absen'] ?? null;
        }

        $qrSessionsModel = new \App\Models\QrSessionsModel();
        $sesiAktif = $qrSessionsModel->where('dibuat_oleh', $guruId)
            ->where('is_active', 1)
            ->where('expired_at >', date('Y-m-d H:i:s'))
            ->first();

        $data = [
            'title'            => 'Data Absensi',
            'absensi'          => $this->absensiModel->getRekapByTanggal($tanggal, $kelasId),
            'kelas'            => !empty($kelasIds) ? $this->kelasModel->whereIn('id', $kelasIds)->findAll() : [],
            'filter_tanggal'   => $tanggal,
            'filter_kelas'     => $kelasId,
            'total_hadir'      => $this->absensiModel->getCountByStatus($tanggal, 'HADIR'),
            'total_izin'       => $this->absensiModel->getCountByStatus($tanggal, 'IZIN'),
            'total_sakit'      => $this->absensiModel->getCountByStatus($tanggal, 'SAKIT'),
            'total_alpa'       => $this->absensiModel->getCountByStatus($tanggal, 'ALPA'),
            'total_terlambat'  => $this->absensiModel->getCountTerlambat($tanggal),
            'sesi_aktif'       => $sesiAktif,
            'siswa_list'       => $siswaList,
            'status_absensi'   => $statusAbsensi,
        ];
        
        $this->setPageTitle('Absensi Manual');
        $this->addBreadcrumb('Dashboard', '/guru');
        $this->addBreadcrumb('Absensi');
        
        return $this->render('guru/absensi/index', $data);
    }

    // ==================== CREATE ====================
    public function create()
    {
        $guruId = session()->get('user_id');
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $semesterAktif = $this->semesterModel->getAktif();
        $jadwalId = $this->request->getGet('jadwal_id');

        $jadwalList = [];
        $siswaList = [];
        
        if ($semesterAktif) {
            $jadwalList = $this->jadwalModel
                ->select('jadwal.*, mata_pelajaran.nama as mapel, CONCAT(kelas.tingkat," ",jurusan.singkatan," ",kelas.rombel) as nama_kelas')
                ->join('mata_pelajaran', 'mata_pelajaran.id = jadwal.mata_pelajaran_id')
                ->join('kelas', 'kelas.id = jadwal.kelas_id')
                ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                ->where('jadwal.guru_id', $guruId)
                ->where('jadwal.semester_id', $semesterAktif['id'])
                ->orderBy('jadwal.hari', 'ASC')
                ->findAll();

            if ($jadwalId) {
                $jadwal = $this->jadwalModel->find($jadwalId);
                if ($jadwal) {
                    $siswaList = $this->kelasSiswaModel
                        ->select('siswa.id, siswa.nis, siswa.nama_lengkap')
                        ->join('siswa', 'siswa.id = kelas_siswa.siswa_id')
                        ->where('kelas_siswa.kelas_id', $jadwal['kelas_id'])
                        ->where('kelas_siswa.status', 'aktif')
                        ->where('siswa.is_active', 1)
                        ->where('siswa.deleted_at', null)
                        ->findAll();
                }
            }
        }

        $this->setPageTitle('Input Absensi');
        $this->addBreadcrumb('Dashboard', '/guru');
        $this->addBreadcrumb('Absensi', '/guru/absensi');
        $this->addBreadcrumb('Input');

        return $this->render('guru/absensi/create', [
            'title'          => 'Input Absensi',
            'jadwal_list'    => $jadwalList,
            'siswa_list'     => $siswaList,
            'status_absensi' => $this->statusAbsensiModel->findAll(),
            'filter_tanggal' => $tanggal,
            'filter_jadwal'  => $jadwalId,
        ]);
    }

    // ==================== STORE ====================
    public function store()
    {
        $siswaId = $this->request->getPost('siswa_id');
        $tanggal = $this->request->getPost('tanggal');
        $jadwalId = $this->request->getPost('jadwal_id');
        $statusId = $this->request->getPost('status_id');

        $sudahAbsen = $this->absensiModel->cekSudahAbsen($siswaId, $tanggal, $jadwalId);
        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Siswa sudah absen di mapel ini');
        }

        $this->absensiModel->save([
            'siswa_id'            => $siswaId,
            'jadwal_id'           => $jadwalId,
            'tanggal'             => $tanggal,
            'jam_absen'           => date('Y-m-d H:i:s'),
            'tipe_absensi'        => 'hadir',
            'status_id'           => $statusId,
            'menit_keterlambatan' => $this->request->getPost('menit_keterlambatan') ?? 0,
            'keterangan'          => $this->request->getPost('keterangan'),
            'metode_absensi'      => 'manual_guru',
            'petugas_id'          => session()->get('user_id'),
        ]);

        // ✅ HAPUS - NotifikasiWhatsApp belum ada
        // TODO: Integrasi WhatsApp nanti

        session()->setFlashdata('success', 'Absensi berhasil disimpan');
        return redirect()->to('/guru/absensi?tanggal=' . $tanggal);
    }

    // ==================== STORE BATCH ====================
    public function storeBatch()
    {
        $tanggal   = $this->request->getPost('tanggal');
        $jadwalId  = $this->request->getPost('jadwal_id');
        $statusId  = $this->request->getPost('status_id');
        $siswaIds  = $this->request->getPost('siswa_ids');
        $statusPerSiswa = $this->request->getPost('status_per_siswa') ?? [];

        if (empty($siswaIds) || !is_array($siswaIds)) {
            return redirect()->back()->with('error', 'Pilih minimal satu siswa');
        }

        $batchData = [];
        foreach ($siswaIds as $siswaId) {
            $sudahAbsen = $this->absensiModel->cekSudahAbsen($siswaId, $tanggal, $jadwalId);
            if ($sudahAbsen) continue;

            $status = $statusPerSiswa[$siswaId] ?? $statusId;

            $batchData[] = [
                'siswa_id'            => $siswaId,
                'jadwal_id'           => $jadwalId,
                'tanggal'             => $tanggal,
                'jam_absen'           => date('Y-m-d H:i:s'),
                'tipe_absensi'        => 'hadir',
                'status_id'           => $status,
                'menit_keterlambatan' => $this->request->getPost('menit_keterlambatan') ?? 0,
                'keterangan'          => $this->request->getPost('keterangan'),
                'metode_absensi'      => 'manual_guru',
                'petugas_id'          => session()->get('user_id'),
            ];
        }

        if (!empty($batchData)) {
            $this->absensiModel->insertBatch($batchData);
            session()->setFlashdata('success', count($batchData) . ' siswa berhasil diabsen');
        } else {
            session()->setFlashdata('warning', 'Semua siswa sudah absen');
        }

        return redirect()->to('/guru/absensi?tanggal=' . $tanggal);
    }

    // ==================== EDIT ====================
    public function edit($id)
    {
        $absensi = $this->absensiModel->find($id);
        if (!$absensi) {
            return redirect()->to('/guru/absensi')->with('error', 'Data tidak ditemukan');
        }

        $this->setPageTitle('Edit Absensi');
        $this->addBreadcrumb('Dashboard', '/guru');
        $this->addBreadcrumb('Absensi', '/guru/absensi');
        $this->addBreadcrumb('Edit');

        return $this->render('guru/absensi/edit', [
            'title'          => 'Edit Absensi',
            'absensi'        => $absensi,
            'status_absensi' => $this->statusAbsensiModel->findAll(),
        ]);
    }

    // ==================== UPDATE ====================
    public function update($id)
    {
        $this->absensiModel->update($id, [
            'status_id'           => $this->request->getPost('status_id'),
            'menit_keterlambatan' => $this->request->getPost('menit_keterlambatan'),
            'keterangan'          => $this->request->getPost('keterangan'),
        ]);

        session()->setFlashdata('success', 'Absensi berhasil diupdate');
        return redirect()->to('/guru/absensi');
    }

    // ==================== DELETE ====================
    public function delete($id)
    {
        $this->absensiModel->delete($id);
        session()->setFlashdata('success', 'Absensi berhasil dihapus');
        return redirect()->to('/guru/absensi');
    }

    // ==================== RIWAYAT ====================
    public function riwayat($siswaId = null)
    {
        if (empty($siswaId)) {
            return redirect()->to('/guru/absensi')->with('error', 'Pilih siswa terlebih dahulu');
        }

        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $siswa = $this->siswaModel->find($siswaId);
        $riwayat = $this->absensiModel->getRiwayatSiswa($siswaId, $bulan, $tahun);

        $this->setPageTitle('Riwayat ' . ($siswa['nama_lengkap'] ?? 'Siswa'));
        $this->addBreadcrumb('Dashboard', '/guru');
        $this->addBreadcrumb('Absensi', '/guru/absensi');
        $this->addBreadcrumb('Riwayat');

        return $this->render('guru/absensi/riwayat', [
            'title'        => 'Riwayat ' . ($siswa['nama_lengkap'] ?? ''),
            'siswa'        => $siswa,
            'riwayat'      => $riwayat,
            'filter_bulan' => $bulan,
            'filter_tahun' => $tahun,
        ]);
    }

    // ==================== REKAP ====================
    public function rekap()
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');
        $guruId = session()->get('user_id');
        $semesterAktif = $this->semesterModel->getAktif();

        $kelasIds = [];
        if ($semesterAktif) {
            $jadwalKelas = $this->jadwalModel
                ->select('kelas_id')
                ->where('guru_id', $guruId)
                ->where('semester_id', $semesterAktif['id'])
                ->distinct()
                ->findAll();
            $kelasIds = array_column($jadwalKelas, 'kelas_id');
        }

        $rekap = [];
        foreach ($kelasIds as $kId) {
            $rekap[] = [
                'kelas'   => $this->kelasModel->find($kId),
                'absensi' => $this->absensiModel->getRekapByTanggal($tanggal, $kId),
            ];
        }

        $this->setPageTitle('Rekap Absensi');
        $this->addBreadcrumb('Dashboard', '/guru');
        $this->addBreadcrumb('Absensi', '/guru/absensi');
        $this->addBreadcrumb('Rekap');

        return $this->render('guru/absensi/rekap', [
            'title'   => 'Rekap Absensi',
            'rekap'   => $rekap,
            'tanggal' => $tanggal,
        ]);
    }

    // ==================== INPUT KELAS ====================
    public function inputKelas($kelasId)
    {
        $tanggal = $this->request->getGet('tanggal') ?? date('Y-m-d');

        $this->setPageTitle('Input Absensi');
        $this->addBreadcrumb('Dashboard', '/guru');
        $this->addBreadcrumb('Absensi', '/guru/absensi');
        $this->addBreadcrumb('Input');

        return $this->render('guru/absensi/input_kelas', [
            'title'             => 'Input Absensi',
            'kelas'             => $this->kelasModel->find($kelasId),
            'tanggal_filter'    => $tanggal,
            'siswa_belum_absen' => $this->absensiModel->getBelumAbsen($tanggal, $kelasId),
            'siswa_sudah_absen' => $this->absensiModel->getRekapByTanggal($tanggal, $kelasId),
            'status_absensi'    => $this->statusAbsensiModel->findAll(),
        ]);
    }
}