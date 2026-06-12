<?php

namespace App\Models;

class JurusanModel extends BaseModel
{
    protected $table          = 'jurusan';

    protected $primaryKey     = 'id';

    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'kode',
        'singkatan',
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected $useTimestamps = true;

    protected $createdField = 'created_at';

    protected $updatedField = 'updated_at';

    protected $validationRules = [

        'kode' => [
            'rules' => 'required|max_length[10]',
            'errors' => [
                'required' => 'Kode jurusan wajib diisi.',
            ],
        ],

        'nama' => [
            'rules' => 'required|max_length[100]',
            'errors' => [
                'required' => 'Nama jurusan wajib diisi.',
            ],
        ],

    ];

    public function getJurusanWithKelasCount()
    {
        return $this->select('jurusan.*, COUNT(kelas.id) as total_kelas')

            ->join(
                'kelas',
                'kelas.jurusan_id = jurusan.id AND kelas.is_active = 1',
                'left'
            )

            ->groupBy('jurusan.id')

            ->orderBy('jurusan.nama', 'ASC')

            ->findAll();
    }

    public function getActive()
    {
        return $this->where('is_active', 1)

            ->orderBy('nama', 'ASC')

            ->findAll();
    }
}