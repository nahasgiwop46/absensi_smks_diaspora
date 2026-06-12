<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends BaseModel
{
    protected $table            = 'siswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deleted_at';
    // ✅ PERBAIKI allowedFields
protected $allowedFields = [
    'user_id',            // ✅ TAMBAH
    'nis',
    'nisn',
    'nama_lengkap',
    'jenis_kelamin',
    'tempat_lahir',
    'tanggal_lahir',
    'alamat',
    'no_hp',
    'no_hp_ortu',         // ✅ TAMBAH
    'token_izin',          // ✅ TAMBAH
    'email',
    'qr_code',
    'foto',
    'is_active',
    'created_by',
    'updated_by',
];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $dateFormat       = 'datetime';
    
    public $validationRules = [
    'nis'           => 'required|max_length[20]',
    'nisn'          => 'permit_empty|max_length[10]',
    'nama_lengkap'  => 'required|max_length[100]',
    'jenis_kelamin' => 'permit_empty|in_list[L,P]',
    'email'         => 'permit_empty|valid_email|max_length[100]',
    'no_hp'         => 'permit_empty|max_length[15]',
    'is_active'     => 'permit_empty|in_list[0,1]',
];

    protected $validationMessages = [
        'nis' => [
            'required'  => 'NIS harus diisi.',
            // 'is_unique' => 'NIS sudah digunakan.',
        ],
        'nama_lengkap' => [
            // 'required'  => 'Nama lengkap harus diisi.',
        ],
    ];

    protected $beforeInsert = ['setCreatedBy'];
    protected $beforeUpdate = ['setUpdatedBy'];


protected $validationRulesUpdate = [
    'nis' => [
        'label' => 'NIS',
        'rules' => 'required|max_length[20]|is_unique[siswa.nis,id,{id}]'
    ],

    'nisn' => [
        'label' => 'NISN',
        'rules' => "permit_empty|max_length[10]|is_unique[siswa.nisn,id,{id}]"
    ],
    'nama_lengkap'  => 'required|max_length[100]',
    'jenis_kelamin' => 'permit_empty|in_list[L,P]',
    'email'         => 'permit_empty|valid_email|max_length[100]',
    'no_hp'         => 'permit_empty|max_length[15]',
    'is_active'     => 'permit_empty|in_list[0,1]',
];
    protected function setCreatedBy(array $data)
    {
        if (!isset($data['data']['created_by'])) {
            $data['data']['created_by'] = session()->get('user_id');
        }
        return $data;
    }

    protected function setUpdatedBy(array $data)
    {
        $data['data']['updated_by'] = session()->get('user_id');
        return $data;
    }

    // ==================== QUERY METHODS ====================

    /**
     * Get semua siswa dengan info kelas terbaru
     */
    public function getAllSiswa($filters = [], $perPage = 10, $page = 1)
    {
        $builder = $this->select('siswa.*, 
                                  kelas_siswa.kelas_id,
                                  kelas_siswa.status as status_kelas,
                                  CONCAT(kelas.tingkat, " ", jurusan.singkatan, " ", kelas.rombel) as nama_kelas')
                        ->join('kelas_siswa', 'kelas_siswa.siswa_id = siswa.id AND kelas_siswa.status = "aktif"', 'left')
                        ->join('kelas', 'kelas.id = kelas_siswa.kelas_id', 'left')
                        ->join('jurusan', 'jurusan.id = kelas.jurusan_id', 'left')
                        ->where('siswa.deleted_at', null);

        // Filter by status siswa
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $builder->where('siswa.is_active', $filters['is_active']);
        }

        // Filter by kelas
        if (!empty($filters['kelas_id'])) {
            $builder->where('kelas_siswa.kelas_id', $filters['kelas_id']);
        }

        // Filter by jurusan
        if (!empty($filters['jurusan_id'])) {
            $builder->where('jurusan.id', $filters['jurusan_id']);
        }

        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                    ->like('siswa.nis', $filters['search'])
                    ->orLike('siswa.nisn', $filters['search'])
                    ->orLike('siswa.nama_lengkap', $filters['search'])
                    ->groupEnd();
        }
  $builder->groupBy('siswa.id');
        return $builder->orderBy('siswa.nama_lengkap', 'ASC')->paginate($perPage, 'siswa', $page);
    }

    /**
     * Get siswa by kelas
     */
    public function getSiswaByKelas($kelasId, $tahunAjaranId = null)
    {
        $builder = $this->select('siswa.*, kelas_siswa.status as status_kelas, kelas_siswa.tanggal_masuk')
                        ->join('kelas_siswa', 'kelas_siswa.siswa_id = siswa.id')
                        ->where('kelas_siswa.kelas_id', $kelasId)
                        ->where('kelas_siswa.status', 'aktif')
                        ->where('siswa.deleted_at', null)
                        ->where('siswa.is_active', 1);

        if ($tahunAjaranId) {
            $builder->where('kelas_siswa.tahun_ajaran_id', $tahunAjaranId);
        }

        return $builder->orderBy('siswa.nama_lengkap', 'ASC')->findAll();
    }

    /**
     * Get siswa yang belum ditempatkan di kelas
     */
    public function getSiswaBelumDitempatkan($tahunAjaranId)
{
    $db = \Config\Database::connect();
    $siswaSudahDitempatkan = $db->table('kelas_siswa')
        ->select('siswa_id')
        ->where('tahun_ajaran_id', $tahunAjaranId)
        ->where('status', 'aktif')
        ->get()
        ->getResultArray();
    return $this->select('siswa.*')
                ->where('siswa.is_active', 1)
                ->where('siswa.deleted_at', null)
                ->whereNotIn('siswa.id', array_column($siswaSudahDitempatkan, 'siswa_id'), false)
                ->orderBy('siswa.nama_lengkap', 'ASC')
                ->findAll();
}

    /**
     * Generate QR Code untuk siswa
     */
    public function generateQrCode($siswaId)
    {
        $qrCode = 'QR-' . strtoupper(bin2hex(random_bytes(16)));
        
        $this->update($siswaId, ['qr_code' => $qrCode]);
        
        return $qrCode;
    }

    /**
     * Count total siswa aktif
     */
    public function countActive()
    {
        return $this->where('is_active', 1)
                    ->where('deleted_at', null)
                    ->countAllResults();
    }

    // Tambah method untuk reset token izin siswa
public function setTokenIzin($siswaId)
{
    $token = bin2hex(random_bytes(32));
    $this->update($siswaId, ['token_izin' => $token]);
    return $token;
}

// Tambah method validasi token izin
public function validasiTokenIzin($token)
{
    return $this->where('token_izin', $token)
                ->where('is_active', 1)
                ->where('deleted_at', null)
                ->first();
}

// Tambah method update nomor HP orang tua
public function updateNoHpOrtu($siswaId, $noHp)
{
    return $this->update($siswaId, ['no_hp_ortu' => $noHp]);
}

// Tambah method cari siswa by NIS
public function getByNis($nis)
{
    return $this->where('nis', $nis)
                ->where('deleted_at', null)
                ->first();
}

// Tambah method cari siswa by QR code
public function getByQrCode($qrCode)
{
    return $this->where('qr_code', $qrCode)
                ->where('is_active', 1)
                ->where('deleted_at', null)
                ->first();
}
}