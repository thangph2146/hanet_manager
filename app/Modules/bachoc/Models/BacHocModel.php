<?php

namespace App\Modules\bachoc\Models;

use App\Models\BaseModel;
use App\Modules\bachoc\Entities\BacHoc;

class BacHocModel extends BaseModel
{
    protected $table = 'bac_hoc';
    protected $primaryKey = 'bac_hoc_id';
    protected $returnType = BacHoc::class;
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    
    protected $allowedFields = [
        'ten_bac_hoc',
        'ma_bac_hoc',
        'status',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    // Searchable fields
    protected $searchableFields = [
        'ten_bac_hoc',
        'ma_bac_hoc'
    ];
    
    // Filterable fields
    protected $filterableFields = [
        'status',
        'bin'
    ];
    
    // Fields that need whitespace trimming
    protected $beforeSpaceRemoval = [
        'ten_bac_hoc',
        'ma_bac_hoc'
    ];
    
    /**
     * Get all active records
     *
     * @return array
     */
    public function getAllActive()
    {
        return $this->where('bin', 0)
                    ->orderBy('ten_bac_hoc', 'ASC')
                    ->findAll();
    }
    
    /**
     * Get all deleted records
     *
     * @return array
     */
    public function getAllDeleted()
    {
        return $this->onlyDeleted()
                    ->orderBy('deleted_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Get active records
     *
     * @return array
     */
    public function getActive()
    {
        return $this->where('status', 1)
                    ->where('bin', 0)
                    ->orderBy('ten_bac_hoc', 'ASC')
                    ->findAll();
    }
    
    /**
     * Check if name exists
     *
     * @param string $tenBacHoc
     * @param int|null $exceptId ID to exclude when checking
     * @return bool
     */
    public function isNameExists(string $tenBacHoc, ?int $exceptId = null): bool
    {
        $builder = $this->where('ten_bac_hoc', $tenBacHoc);
        
        if ($exceptId !== null) {
            $builder->where('bac_hoc_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Move to recycle bin
     *
     * @param int $id
     * @return bool
     */
    public function moveToBin(int $id): bool
    {
        return $this->update($id, ['bin' => 1]);
    }
    
    /**
     * Restore from recycle bin
     *
     * @param int $id
     * @return bool
     */
    public function restoreFromBin(int $id): bool
    {
        return $this->update($id, ['bin' => 0]);
    }
    
    /**
     * Get items in recycle bin
     *
     * @return array
     */
    public function getBinnedItems()
    {
        return $this->where('bin', 1)
                    ->orderBy('updated_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Soft delete record
     *
     * @param int $id
     * @return bool
     */
    public function softDelete(int $id): bool
    {
        return $this->delete($id);
    }
    
    /**
     * Soft delete multiple records
     *
     * @param array $ids IDs to delete
     * @return bool
     */
    public function softDeleteMultiple(array $ids): bool
    {
        return $this->delete($ids);
    }
    
    /**
     * Restore deleted record
     *
     * @param int $id
     * @return bool
     */
    public function restore($id): bool
    {
        return $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->update([$this->deletedField => null]);
    }
    
    /**
     * Restore multiple deleted records
     *
     * @param array $ids IDs to restore
     * @return bool
     */
    public function restoreMultiple(array $ids): bool
    {
        $success = true;
        foreach ($ids as $id) {
            if (!$this->restore($id)) {
                $success = false;
            }
        }
        return $success;
    }
    
    /**
     * Get soft deleted records
     *
     * @return array
     */
    public function getSoftDeleted()
    {
        return $this->onlyDeleted()->findAll();
    }
    
    /**
     * Permanently delete record
     *
     * @param int $id
     * @return bool
     */
    public function permanentDelete(int $id): bool
    {
        return $this->delete($id, true);
    }
    
    /**
     * Permanently delete multiple records
     *
     * @param array $ids IDs to permanently delete
     * @return bool
     */
    public function permanentDeleteMultiple(array $ids): bool
    {
        $success = true;
        foreach ($ids as $id) {
            if (!$this->delete($id, true)) {
                $success = false;
            }
        }
        return $success;
    }
}