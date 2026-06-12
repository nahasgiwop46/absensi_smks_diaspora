<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\KelasSiswaModel;
use App\Models\TahunAjaranModel;
use App\Models\JurusanModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use CodeIgniter\Pager\Pager;


class Siswa extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;
    protected $kelasSiswaModel;
    protected $tahunAjaranModel;
    protected $jurusanModel;
    protected $pager;

    public function __construct()
    {
        $this->siswaModel        = new SiswaModel();
        $this->kelasModel        = new KelasModel();
        $this->kelasSiswaModel   = new KelasSiswaModel();
        $this->tahunAjaranModel  = new TahunAjaranModel();
        $this->jurusanModel      = new JurusanModel();
    }

    // ==================== READ ====================
    public function index()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $filters = [
            'kelas_id'   => $this->request->getGet('kelas_id'),
            'jurusan_id' => $this->request->getGet('jurusan_id'),
            'is_active'  => $this->request->getGet('is_active'),
            'search'     => $this->request->getGet('search'),
            
        ];

        $perPage = 10;
        $page    = (int) ($this->request->getGet('page') ?? 1);

        $siswa   = $this->siswaModel->getAllSiswa($filters, $perPage, $page);
        $kelas   = $this->kelasModel->getActiveKelas();
        $jurusan = $this->jurusanModel->getActive();
        $pager   = $this->siswaModel->pager;

        $this->setPageTitle('Data Siswa');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Siswa');

        return $this->render('admin/siswa/index', [
            'siswa'   => $siswa,
            'kelas'   => $kelas,
            'jurusan' => $jurusan,
            'pager'   => $pager,
            'filters' => $filters,
        ]);
    }

    // ==================== DETAIL ====================
    public function detail($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $siswa = $this->siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('/admin/siswa')->with('error', 'Siswa tidak ditemukan.');
        }

        $riwayatKelas = $this->kelasSiswaModel->getRiwayatKelasSiswa($id);
        $kelasAktif   = $this->kelasSiswaModel
            ->select('kelas_siswa.*, 
                      CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas,
                      kelas.tingkat, kelas.rombel, jurusan.singkatan as jurusan_singkatan')
            ->join('kelas', 'kelas.id = kelas_siswa.kelas_id')
            ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
            ->where('kelas_siswa.siswa_id', $id)
            ->where('kelas_siswa.status', 'aktif')
            ->first();

        $this->setPageTitle('Detail Siswa');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Siswa', '/admin/siswa');
        $this->addBreadcrumb('Detail');

        return $this->render('admin/siswa/detail', [
            'siswa'        => $siswa,
            'riwayatKelas' => $riwayatKelas,
            'kelasAktif'   => $kelasAktif,
        ]);
    }

    // ==================== CREATE ====================
    public function create()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $this->setPageTitle('Tambah Siswa');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Siswa', '/admin/siswa');
        $this->addBreadcrumb('Tambah');

        return $this->render('admin/siswa/form', [
            'kelas_list' => $this->kelasModel->findAll(),
        ]);
    }

    // ==================== STORE ====================
    public function store()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if (!$this->validate($this->siswaModel->validationRules)) {
           return $this->redirectBackWithErrors($this->validator->getErrors());
        }

        $data = $this->sanitizeInput($this->request->getPost());
        $data['qr_code'] = 'QR-' . strtoupper(bin2hex(random_bytes(16)));

        if ($this->siswaModel->insert($data)) {
            $this->setFlashSuccess('Data siswa berhasil ditambahkan!');
            return redirect()->to('/admin/siswa');
        }

        return redirect()->back()->withInput()->with('error', 'Gagal menambahkan data siswa.');
    }

    // ==================== EDIT ====================
    public function edit($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $siswa = $this->siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('/admin/siswa')->with('error', 'Siswa tidak ditemukan.');
        }

        $this->setPageTitle('Edit Siswa');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Siswa', '/admin/siswa');
        $this->addBreadcrumb('Edit');

        return $this->render('admin/siswa/form', [
            'siswa'      => $siswa,
            'kelas_list' => $this->kelasModel->findAll(),
        ]);
    }

    // ==================== UPDATE ====================
  // HANYA BAGIAN METHOD update() YANG DIREVISI

public function update($id)
{
    if ($redirect = $this->requireRole('admin')) {
        return $redirect;
    }

    $data = [
        'nis'            => $this->request->getPost('nis'),
        'nisn'           => $this->request->getPost('nisn'),
        'nama_lengkap'   => $this->request->getPost('nama_lengkap'),
        'jenis_kelamin'  => $this->request->getPost('jenis_kelamin'),
        'tempat_lahir'   => $this->request->getPost('tempat_lahir'),
        'tanggal_lahir'  => $this->request->getPost('tanggal_lahir'),
        'alamat'         => $this->request->getPost('alamat'),
        'no_hp'          => $this->request->getPost('no_hp'),
        'no_hp_ortu'     => $this->request->getPost('no_hp_ortu'),     // ✅ TAMBAH
        'email'          => $this->request->getPost('email'),
        'is_active'      => $this->request->getPost('is_active') ?? 1,
    ];

    // Cek duplikat NIS/NISN
    $exist = $this->siswaModel
        ->where('id !=', $id)
        ->groupStart()
            ->where('nis', $data['nis'])
            ->orWhere('nisn', $data['nisn'])
        ->groupEnd()
        ->first();

    if ($exist) {
        return redirect()->back()->withInput()
            ->with('error', 'NIS atau NISN sudah digunakan siswa lain!');
    }

    $this->siswaModel->update($id, $data);

    if (!empty($this->siswaModel->errors())) {
        return redirect()->back()->withInput()
            ->with('error', 'Gagal update: ' . implode(', ', $this->siswaModel->errors()));
    }

    // Update kelas
    $kelasId = $this->request->getPost('kelas_id');
    if (!empty($kelasId)) {
        $db = \Config\Database::connect();
        $tahunAjaranAktif = $this->tahunAjaranModel->getAktif();

        if ($tahunAjaranAktif) {
            $tahunId = $tahunAjaranAktif['id'];

            $alreadyExist = $db->table('kelas_siswa')
                ->where('siswa_id', $id)
                ->where('kelas_id', $kelasId)
                ->where('tahun_ajaran_id', $tahunId)
                ->where('status', 'aktif')
                ->get()
                ->getRow();

            if (!$alreadyExist) {
                // ✅ PERBAIKI: nonaktif → pindah (sesuai enum database)
                $db->table('kelas_siswa')
                    ->where('siswa_id', $id)
                    ->where('status', 'aktif')
                    ->update(['status' => 'pindah']);

                $cekDuplicate = $db->table('kelas_siswa')
                    ->where('siswa_id', $id)
                    ->where('kelas_id', $kelasId)
                    ->where('tahun_ajaran_id', $tahunId)
                    ->get()
                    ->getRow();

                if (!$cekDuplicate) {
                    $db->table('kelas_siswa')->insert([
                        'siswa_id'        => $id,
                        'kelas_id'        => $kelasId,
                        'tahun_ajaran_id' => $tahunId,
                        'tanggal_masuk'   => date('Y-m-d'),
                        'status'          => 'aktif',
                    ]);
                } else {
                    $db->table('kelas_siswa')
                        ->where('id', $cekDuplicate->id)
                        ->update(['status' => 'aktif']);
                }
            }
        }
    }

    return redirect()->to('/admin/siswa')
        ->with('success', 'Data siswa berhasil diupdate!');
}    // ==================== DELETE ====================
    public function delete($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        if ($this->siswaModel->delete($id)) {
            $this->setFlashSuccess('Data siswa berhasil dihapus!');
        } else {
            $this->setFlashError('Gagal menghapus data siswa.');
        }

        return redirect()->to('/admin/siswa');
    }

    // ==================== QR CODE ====================
    public function qrcode($id)
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $siswa = $this->siswaModel->find($id);
        if (!$siswa) {
            return redirect()->to('/admin/siswa')->with('error', 'Siswa tidak ditemukan.');
        }

        if (empty($siswa['qr_code'])) {
            $siswa['qr_code'] = 'QR-' . strtoupper(bin2hex(random_bytes(16)));
            $this->siswaModel->update($id, ['qr_code' => $siswa['qr_code']]);
        }

        $this->setPageTitle('QR Code - ' . $siswa['nama_lengkap']);
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Siswa', '/admin/siswa');
        $this->addBreadcrumb('QR Code');

        return $this->render('admin/siswa/qrcode', [
            'siswa' => $siswa,
        ]);
    }

    // ==================== IMPORT ====================
    public function import()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $file = $this->request->getFile('file_siswa');

        if (!$file->isValid() || $file->hasMoved()) {
            return redirect()->back()->with('error', 'File tidak valid.');
        }

        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['xlsx', 'xls', 'csv'])) {
            return redirect()->back()->with('error', 'Format: .xlsx, .xls, atau .csv');
        }

        $imported = 0;
        $errors = [];

        try {
            $spreadsheet = IOFactory::load($file->getTempName());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            array_shift($rows);

            foreach ($rows as $row) {
                if (empty($row[0]) && empty($row[2])) continue;

                $nis  = trim((string)($row[0] ?? ''));
                $nisn = trim((string)($row[1] ?? ''));
                $nama = trim((string)($row[2] ?? ''));
                $jk   = strtoupper(trim((string)($row[3] ?? 'L')));

                if (empty($nis) || empty($nama)) {
                    $errors[] = "Data tidak lengkap: {$nis}";
                    continue;
                }

                $exist = $this->siswaModel->where('nis', $nis)->first();
                if ($exist) {
                    $errors[] = "NIS sudah ada: {$nis}";
                    continue;
                }

                $this->siswaModel->insert([
                    'nis'           => $nis,
                    'nisn'          => $nisn,
                    'nama_lengkap'  => $nama,
                    'jenis_kelamin' => in_array($jk, ['L', 'P']) ? $jk : 'L',
                    'is_active'     => 1,
                    'qr_code'       => 'QR-' . strtoupper(bin2hex(random_bytes(8))),
                ]);

                $imported++;
            }

            $msg = $imported . ' siswa berhasil diimport!';
            if (!empty($errors)) {
                session()->setFlashdata('warning', implode('<br>', array_slice($errors, 0, 10)));
            }

            return redirect()->to('/admin/siswa')->with('success', $msg);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // ==================== PENEMPATAN KELAS ====================
    public function penempatanKelas()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $tahunAjaranAktif      = $this->tahunAjaranModel->getAktif();
        $kelas                 = $this->kelasModel->getActiveKelas();
        $siswaBelumDitempatkan = $tahunAjaranAktif
            ? $this->siswaModel->getSiswaBelumDitempatkan($tahunAjaranAktif['id'])
            : [];

        $this->setPageTitle('Penempatan Kelas');
        $this->addBreadcrumb('Dashboard', '/admin');
        $this->addBreadcrumb('Siswa', '/admin/siswa');
        $this->addBreadcrumb('Penempatan Kelas');

        return $this->render('admin/siswa/penempatan_kelas', [
            'tahun_ajaran_aktif'     => $tahunAjaranAktif,
            'kelas'                  => $kelas,
            'siswa_belum_ditempatkan'=> $siswaBelumDitempatkan,
            'selected_kelas'         => $this->request->getGet('kelas_id'),
        ]);
    }

    // ==================== SIMPAN PENEMPATAN ====================
    public function simpanPenempatanKelas()
    {
        if ($redirect = $this->requireRole('admin')) {
            return $redirect;
        }

        $siswaIds       = $this->request->getPost('siswa_ids');
        $kelasId        = $this->request->getPost('kelas_id');
        $tahunAjaranId  = $this->request->getPost('tahun_ajaran_id');

        if (empty($siswaIds) || !$kelasId || !$tahunAjaranId) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        $data = [];
        foreach ($siswaIds as $siswaId) {
            $data[] = [
                'siswa_id'        => $siswaId,
                'kelas_id'        => $kelasId,
                'tahun_ajaran_id' => $tahunAjaranId,
                'tanggal_masuk'   => date('Y-m-d'),
                'status'          => 'aktif',
            ];
        }

        if ($this->kelasSiswaModel->insertBatch($data)) {
            return redirect()->to('/admin/siswa/penempatan-kelas?kelas_id=' . $kelasId)
                ->with('success', count($data) . ' siswa berhasil ditempatkan!');
        } else {
            return redirect()->back()->with('error', 'Gagal menempatkan siswa.');
        }
    }
}