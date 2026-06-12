<?php

namespace App\Models;

class AbsensiModel extends BaseModel
{
    protected $table            = 'absensi';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';
    
    protected $allowedFields    = [
        'siswa_id', 'jadwal_id', 'qr_session_id', 'tanggal',
        'jam_absen', 'tipe_absensi', 'status_id', 'metode_absensi',
        'menit_keterlambatan', 'keterangan', 'koordinat',
        'device_info', 'foto_absensi', 'petugas_id',
        // ✅ FIELD BARU (tambahan)
        'device_siswa',
        'user_agent_siswa',
        'koordinat_siswa',
        'foto_verifikasi',
    ];
    
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    
    protected $validationRules  = [
        'siswa_id'      => 'required|integer',
        'qr_session_id' => 'required|integer',
        'tanggal'       => 'required|valid_date',
        'status_id'     => 'required|integer',
        'tipe_absensi'  => 'required|in_list[hadir,izin,sakit,alpa]',
    ];

    // ==================== HELPER ====================
    
    /**
     * Ambil kelas_id yang diajar oleh guru (dari tabel jadwal)
     */
    private function getKelasAjar($guruId)
    {
        $semesterModel = new \App\Models\SemesterModel();
        $semesterAktif = $semesterModel->getAktif();
        
        if (!$semesterAktif) return [];
        
        $jadwalKelas = $this->db->table('jadwal')
            ->select('kelas_id')
            ->where('guru_id', $guruId)
            ->where('semester_id', $semesterAktif['id'])
            ->distinct()
            ->get()
            ->getResultArray();
        
        return array_column($jadwalKelas, 'kelas_id');
    }
    
    /**
     * Ambil siswa_id dari kelas tertentu
     */
    private function getSiswaIdsByKelas($kelasId)
    {
        if (!$kelasId) return [];
        
        $siswaIds = $this->db->table('kelas_siswa')
            ->select('siswa_id')
            ->where('kelas_id', $kelasId)
            ->where('status', 'aktif')
            ->get()
            ->getResultArray();
        
        return array_column($siswaIds, 'siswa_id');
    }

    // ==================== QUERY METHODS ====================

    /**
     * ✅ TAMBAH: Cek siswa sudah absen di sesi tertentu
     */
public function cekSudahAbsenPerSesi($siswaId, $qrSessionId)
{
    $db = \Config\Database::connect();
    
    // Ambil tanggal sesi
    $session = $db->table('qr_sessions')->where('id', $qrSessionId)->get()->getRowArray();
    
    if (!$session) return null;
    
    return $this->where('siswa_id', $siswaId)
                ->where('qr_session_id', $qrSessionId)
                ->where('tanggal', $session['tanggal'])  // ✅ Pakai tanggal dari sesi
                ->where('deleted_at', null)
                ->first();
}

    /**
     * ✅ TAMBAH: Rekap absen per QR session
     */
    public function getRekapBySession($qrSessionId)
{
    $builder = $this->db->table('absensi');
    $builder->select('
        absensi.*,
        siswa.nis,
        siswa.nama_lengkap,
        siswa.foto as siswa_foto,
        master_status_absensi.label as status_label,
        master_status_absensi.warna as status_warna,
        mata_pelajaran.nama as mapel
    ');
    $builder->join('siswa', 'siswa.id = absensi.siswa_id');
    $builder->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id', 'left');
    // ✅ Join via qr_sessions
    $builder->join('qr_sessions', 'qr_sessions.id = absensi.qr_session_id', 'left');
    $builder->join('jadwal', 'jadwal.id = COALESCE(absensi.jadwal_id, qr_sessions.jadwal_id)', 'left');
    $builder->join('mata_pelajaran', 'mata_pelajaran.id = COALESCE(jadwal.mata_pelajaran_id, qr_sessions.mata_pelajaran_id)', 'left');
    $builder->where('absensi.qr_session_id', $qrSessionId);
    $builder->where('absensi.deleted_at', null);
    $builder->orderBy('absensi.jam_absen', 'ASC');
    
    return $builder->get()->getResultArray();
}

    /**
     * Rekap absensi per tanggal & kelas
     */
  public function getRekapByTanggal($tanggal, $kelasId = null)
{
    $builder = $this->db->table('absensi');
    $builder->select('
        absensi.*,
        siswa.nis,
        siswa.nama_lengkap,
        siswa.foto as siswa_foto,
        master_status_absensi.label as status_label,
        master_status_absensi.warna as status_warna,
        master_status_absensi.kode as status_kode,
        jadwal.jam_mulai,
        jadwal.jam_selesai,
        mata_pelajaran.nama as mapel
    ');
    $builder->join('siswa', 'siswa.id = absensi.siswa_id');
    $builder->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id', 'left');
    // ✅ Join via qr_sessions
    $builder->join('qr_sessions', 'qr_sessions.id = absensi.qr_session_id', 'left');
    $builder->join('jadwal', 'jadwal.id = COALESCE(absensi.jadwal_id, qr_sessions.jadwal_id)', 'left');
    $builder->join('mata_pelajaran', 'mata_pelajaran.id = COALESCE(jadwal.mata_pelajaran_id, qr_sessions.mata_pelajaran_id)', 'left');
    $builder->where('absensi.tanggal', $tanggal);
    $builder->where('absensi.deleted_at', null);
    
    if ($kelasId) {
        $siswaIds = $this->getSiswaIdsByKelas($kelasId);
        if (!empty($siswaIds)) {
            $builder->whereIn('absensi.siswa_id', $siswaIds);
        } else {
            return [];
        }
    }
    
    $this->applyGuruFilter($builder);
    
    $builder->orderBy('absensi.jam_absen', 'DESC');
    
    return $builder->get()->getResultArray();
}

    /**
     * Hitung jumlah absensi berdasarkan status
     */
    public function getCountByStatus($tanggal, $status)
    {
        $builder = $this->db->table('absensi');
        $builder->select('COUNT(*) as total');
        $builder->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id');
        $builder->where('absensi.tanggal', $tanggal);
        $builder->where('absensi.deleted_at', null);
        $builder->where('master_status_absensi.kode', $status);
        
        // Filter untuk guru (hanya kelas yang diajar)
        if (session()->get('role_kode') === 'guru' || session()->get('role_kode') === 'GURU') {
            $kelasIds = $this->getKelasAjar(session()->get('user_id'));
            if (!empty($kelasIds)) {
                $allSiswaIds = [];
                foreach ($kelasIds as $kId) {
                    $allSiswaIds = array_merge($allSiswaIds, $this->getSiswaIdsByKelas($kId));
                }
                if (!empty($allSiswaIds)) {
                    $builder->whereIn('absensi.siswa_id', array_unique($allSiswaIds));
                } else {
                    return 0;
                }
            }
        }
        // ✅ Pakai method reusable
           $this->applyGuruFilter($builder);
        $result = $builder->get()->getRow();
        return $result ? (int)$result->total : 0;
    }

    /**
     * Hitung jumlah siswa terlambat
     */
    public function getCountTerlambat($tanggal)
    {
        $builder = $this->db->table('absensi');
        $builder->select('COUNT(*) as total');
        $builder->where('tanggal', $tanggal);
        $builder->where('menit_keterlambatan >', 0);
        $builder->where('deleted_at', null);
        
        // Filter untuk guru
        if (session()->get('role_kode') === 'guru' || session()->get('role_kode') === 'GURU') {
            $kelasIds = $this->getKelasAjar(session()->get('user_id'));
            if (!empty($kelasIds)) {
                $allSiswaIds = [];
                foreach ($kelasIds as $kId) {
                    $allSiswaIds = array_merge($allSiswaIds, $this->getSiswaIdsByKelas($kId));
                }
                if (!empty($allSiswaIds)) {
                    $builder->whereIn('siswa_id', array_unique($allSiswaIds));
                } else {
                    return 0;
                }
            }
        }

        // ✅ Pakai method reusable
            $this->applyGuruFilter($builder);

        $result = $builder->get()->getRow();
        return $result ? (int)$result->total : 0;
    }

    /**
     * Riwayat absensi per siswa
     */
public function getRiwayatSiswa($siswaId, $bulan = null, $tahun = null)
{
    $builder = $this->db->table('absensi');
    $builder->select('
        absensi.*,
        master_status_absensi.label as status_label,
        master_status_absensi.warna as status_warna,
        mata_pelajaran.nama as mapel
    ');
    $builder->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id');
    // ✅ Join via qr_sessions untuk dapat mapel
    $builder->join('qr_sessions', 'qr_sessions.id = absensi.qr_session_id', 'left');
    $builder->join('jadwal', 'jadwal.id = COALESCE(absensi.jadwal_id, qr_sessions.jadwal_id)', 'left');
    $builder->join('mata_pelajaran', 'mata_pelajaran.id = COALESCE(jadwal.mata_pelajaran_id, qr_sessions.mata_pelajaran_id)', 'left');
    $builder->where('absensi.siswa_id', $siswaId);
    $builder->where('absensi.deleted_at', null);

    if ($bulan) {
        $builder->where('MONTH(absensi.tanggal)', $bulan);
    }
    if ($tahun) {
        $builder->where('YEAR(absensi.tanggal)', $tahun);
    }

    $builder->orderBy('absensi.tanggal', 'DESC');
    $builder->orderBy('absensi.jam_absen', 'DESC');

    return $builder->get()->getResultArray();
}
    /**
     * Cek apakah siswa sudah absen
     */
    public function cekSudahAbsen($siswaId, $tanggal, $jadwalId = null)
    {
        $builder = $this->where('siswa_id', $siswaId)
                        ->where('tanggal', $tanggal)
                        ->where('deleted_at', null);
        
        if ($jadwalId) {
            $builder->where('jadwal_id', $jadwalId);
        }
        
        return $builder->first();
    }

    /**
     * Ambil data siswa yang belum absen
     */
    public function getBelumAbsen($tanggal, $kelasId): array
    {
        $subQuery = $this->db->table('absensi')
                             ->select('siswa_id')
                             ->where('tanggal', $tanggal)
                             ->where('deleted_at', null);

        return $this->db->table('kelas_siswa')
                        ->select('siswa.id, siswa.nis, siswa.nama_lengkap')
                        ->join('siswa', 'siswa.id = kelas_siswa.siswa_id')
                        ->where('kelas_siswa.kelas_id', $kelasId)
                        ->where('kelas_siswa.status', 'aktif')
                        ->where('siswa.is_active', 1)
                        ->where('siswa.deleted_at', null)
                        ->whereNotIn('siswa.id', $subQuery)
                        ->orderBy('siswa.nama_lengkap', 'ASC')
                        ->get()
                        ->getResultArray();
    }

    /**
 * Terapkan filter guru (hanya kelas yang diajar)
 */
private function applyGuruFilter(&$builder)
{
    $roleKode = session()->get('role_kode');
    
    if ($roleKode === 'guru' || $roleKode === 'GURU') {
        $guruId = session()->get('user_id');
        $kelasIds = $this->getKelasAjar($guruId);
        
        if (!empty($kelasIds)) {
            $allSiswaIds = [];
            foreach ($kelasIds as $kId) {
                $allSiswaIds = array_merge($allSiswaIds, $this->getSiswaIdsByKelas($kId));
            }
            $allSiswaIds = array_unique($allSiswaIds);
            
            if (!empty($allSiswaIds)) {
                $builder->whereIn('absensi.siswa_id', $allSiswaIds);
            } else {
                // Force empty result
                $builder->where('1 = 0');
            }
        } else {
            // Force empty result
            $builder->where('1 = 0');
        }
    }
    
    return $builder;
}


// Tambah method ambil absensi by QR session + siswa
public function getBySessionDanSiswa($qrSessionId, $siswaId)
{
    return $this->where('qr_session_id', $qrSessionId)
                ->where('siswa_id', $siswaId)
                ->where('deleted_at', null)
                ->first();
}

// Tambah method hitung total absen hari ini per kelas
public function countByKelasTanggal($kelasId, $tanggal)
{
    $siswaIds = $this->getSiswaIdsByKelas($kelasId);
    
    if (empty($siswaIds)) {
        return 0;
    }
    
    return $this->where('tanggal', $tanggal)
                ->whereIn('siswa_id', $siswaIds)
                ->where('deleted_at', null)
                ->countAllResults();
}

// Tambah method rekap bulanan per siswa
public function getRekapBulananSiswa($siswaId, $bulan, $tahun)
{
    $builder = $this->db->table('absensi');
    $builder->select('
        master_status_absensi.kode,
        master_status_absensi.label,
        COUNT(*) as total
    ');
    $builder->join('master_status_absensi', 'master_status_absensi.id = absensi.status_id');
    $builder->where('absensi.siswa_id', $siswaId);
    $builder->where('MONTH(absensi.tanggal)', $bulan);
    $builder->where('YEAR(absensi.tanggal)', $tahun);
    $builder->where('absensi.deleted_at', null);
    $builder->groupBy('master_status_absensi.id');
    
    return $builder->get()->getResultArray();
}
}