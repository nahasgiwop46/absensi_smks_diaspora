<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\I18n\Time;

class BaseModel extends Model
{
    // ========================================
    // DEFAULT CONFIG (bisa di-override di child)
    // ========================================
    protected $useTimestamps    = true;
    protected $useSoftDeletes   = false;  // ✅ Default FALSE, aktifkan di model yg butuh
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';
    
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;
    protected $protectFields    = true;
    
    protected $allowCallbacks   = true;
    
    // ========================================
    // BEFORE INSERT
    // ========================================
    protected function beforeInsert(array $data)
    {
        $data = $this->setCreatedBy($data);
        return $data;
    }
    
    // ========================================
    // BEFORE UPDATE
    // ========================================
    protected function beforeUpdate(array $data)
    {
        $data = $this->setUpdatedBy($data);
        return $data;
    }
    
    // ========================================
    // SET CREATED BY
    // ========================================
    protected function setCreatedBy(array $data)
    {
        if ($this->db->fieldExists('created_by', $this->table)) {
            if (!isset($data['data']['created_by'])) {
                $data['data']['created_by'] = session()->get('user_id') ?? null;
            }
        }
        return $data;
    }
    
    // ========================================
    // SET UPDATED BY
    // ========================================
    protected function setUpdatedBy(array $data)
    {
        if ($this->db->fieldExists('updated_by', $this->table)) {
            if (!isset($data['data']['updated_by'])) {
                $data['data']['updated_by'] = session()->get('user_id') ?? null;
            }
        }
        return $data;
    }
    
    // ========================================
    // SOFT DELETE
    // ========================================
    public function delete($id = null, bool $purge = false)
{
    // Kalau purge = true, hapus permanen
    if ($purge) {
        return parent::delete($id, $purge);
    }

    if ($id !== null) {
        $this->where($this->primaryKey, $id);
    }

    // Cek apakah field deleted_at ADA
    if (!$this->db->fieldExists('deleted_at', $this->table)) {
        return parent::delete($id, true);
    }

    // ✅ PAKAI Query Builder LANGSUNG (hindari doProtectFields)
    $data = ['deleted_at' => date('Y-m-d H:i:s')];

    if ($this->db->fieldExists('deleted_by', $this->table)) {
        $data['deleted_by'] = session()->get('user_id') ?? null;
    }

    // ✅ UPDATE LANGSUNG dengan builder
    return $this->db->table($this->table)
                    ->set($data)
                    ->where($this->primaryKey, $id)
                    ->update();
}
    // ========================================
    // RESTORE
    // ========================================
    public function restore($id = null)
    {
        if (!$this->useSoftDeletes) {
            return false;
        }
        
        if ($id !== null) {
            $this->where($this->primaryKey, $id);
        }
        
        $data = [$this->deletedField => null];
        
        if ($this->db->fieldExists('deleted_by', $this->table)) {
            $data['deleted_by'] = null;
        }
        
        return $this->set($data)->update();
    }
    
    // ========================================
    // GET WITH TRASHED
    // ========================================
    public function withTrashed()
    {
        if ($this->useSoftDeletes) {
            $this->withDeleted();
        }
        return $this;
    }
    
    // ========================================
    // GET ONLY TRASHED
    // ========================================
    public function onlyTrashed()
    {
        if ($this->useSoftDeletes) {
            $this->onlyDeleted();
        }
        return $this;
    }
    
    // ========================================
    // FIND ACTIVE ONLY
    // ========================================
    public function findActive($id = null)
    {
        if ($this->db->fieldExists('is_active', $this->table)) {
            $this->where('is_active', 1);
        }
        
        if ($id !== null) {
            return $this->find($id);
        }
        
        return $this->findAll();
    }
    
    // ========================================
    // TOGGLE ACTIVE
    // ========================================
    public function toggleActive($id): bool
    {
        if (!$this->db->fieldExists('is_active', $this->table)) {
            return false;
        }
        
        $record = $this->find($id);
        if (!$record) {
            return false;
        }
        
        return $this->update($id, [
            'is_active' => $record['is_active'] == 1 ? 0 : 1,
        ]);
    }
    
    // ========================================
    // BULK INSERT WITH TRANSACTION
    // ========================================
    public function bulkInsert(array $data): bool
    {
        $this->db->transBegin();
        
        foreach ($data as $row) {
            $this->insert($row);
        }
        
        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return false;
        }
        
        return $this->db->transCommit();
    }
    
    // ========================================
    // GET DROPDOWN
    // ========================================
    public function getDropdown(
        string $keyField = 'id', 
        string $valueField = 'nama', 
        string $condition = ''
    ): array {
        $this->select("{$keyField}, {$valueField}");
        
        if ($this->db->fieldExists('is_active', $this->table)) {
            $this->where('is_active', 1);
        }
        
        if (!empty($condition)) {
            $this->where($condition);
        }
        
        $this->orderBy($valueField, 'ASC');
        
        $results = $this->findAll();
        $dropdown = [];
        
        foreach ($results as $row) {
            $dropdown[$row[$keyField]] = $row[$valueField];
        }
        
        return $dropdown;
    }
    
    // ========================================
    // CHECK EXISTS
    // ========================================
    public function exists(array $where): bool
    {
        return $this->where($where)->countAllResults() > 0;
    }
    
    // ========================================
    // FIND OR FAIL
    // ========================================
    public function findOrFail($id)
    {
        $record = $this->find($id);
        
        if (!$record) {
            throw new \RuntimeException("Record tidak ditemukan di tabel {$this->table}");
        }
        
        return $record;
    }
    
    // ========================================
    // PAGINATE HELPER
    // ========================================
    public function paginateData(int $perPage = 10, string $groupName = 'default'): array
    {
        return [
            'data'        => $this->paginate($perPage, $groupName),
            'pager'       => $this->pager,
            'total'       => $this->pager->getTotal($groupName),
            'perPage'     => $perPage,
            'currentPage' => $this->pager->getCurrentPage($groupName),
        ];
    }

}