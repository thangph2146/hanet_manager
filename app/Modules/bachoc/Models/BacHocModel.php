<?php

namespace App\Modules\bachoc\Models;

use App\Models\BaseModel;

class BacHocModel extends BaseModel
{
    protected $table = 'bac_hoc';
    protected $primaryKey = 'bac_hoc_id';
    protected $returnType = 'App\Modules\bachoc\Entities\BacHoc';
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
    
    // Các trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_bac_hoc',
        'ma_bac_hoc'
    ];
    
    // Các trường có thể lọc
    protected $filterableFields = [
        'status',
        'bin'
    ];
    
    // Các trường cần loại bỏ khoảng trắng thừa
    protected $beforeSpaceRemoval = [
        'ten_bac_hoc',
        'ma_bac_hoc'
    ];
    
    // Định nghĩa các mối quan hệ - không sử dụng
    protected $relations = [];
    
    // Các phương thức tùy chỉnh cho BacHocModel
    
    /**
     * Lấy danh sách tất cả bậc học đang hoạt động và không bị xóa
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
     * Lấy danh sách tất cả bậc học đã bị xóa tạm thời
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
     * Lấy danh sách bậc học đang hoạt động
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
     * Kiểm tra tên bậc học đã tồn tại chưa
     *
     * @param string $tenBacHoc
     * @param int|null $exceptId ID bậc học cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isNameExists(string $tenBacHoc, int $exceptId = null)
    {
        $builder = $this->where('ten_bac_hoc', $tenBacHoc);
        
        if ($exceptId !== null) {
            $builder->where('bac_hoc_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Chuyển bậc học vào thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function moveToBin(int $id)
    {
        return $this->update($id, ['bin' => 1]);
    }
    
    /**
     * Khôi phục bậc học từ thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function restoreFromBin(int $id)
    {
        return $this->update($id, ['bin' => 0]);
    }
    
    /**
     * Lấy danh sách bậc học trong thùng rác
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
     * Xóa tạm thời bậc học (soft delete)
     *
     * @param int $id
     * @return bool
     */
    public function softDelete(int $id)
    {
        return $this->delete($id);
    }
    
    /**
     * Xóa tạm thời nhiều bậc học
     *
     * @param array $ids Mảng các ID cần xóa
     * @return bool
     */
    public function softDeleteMultiple(array $ids)
    {
        return $this->delete($ids);
    }
    
    /**
     * Khôi phục bậc học đã xóa tạm thời
     *
     * @param int $id
     * @return bool
     */
    public function restoreDeleted(int $id)
    {
        return $this->restore($id);
    }
    
    /**
     * Khôi phục nhiều bậc học đã xóa tạm thời
     *
     * @param array $ids Mảng các ID cần khôi phục
     * @return bool
     */
    public function restoreMultiple(array $ids)
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
     * Lấy danh sách bậc học đã xóa tạm thời
     *
     * @return array
     */
    public function getSoftDeleted()
    {
        return $this->onlyDeleted()->findAll();
    }
    
    /**
     * Xóa vĩnh viễn bậc học
     *
     * @param int $id
     * @return bool
     */
    public function permanentDelete(int $id)
    {
        return $this->delete($id, true);
    }
    
    /**
     * Xóa vĩnh viễn nhiều bậc học
     *
     * @param array $ids Mảng các ID cần xóa vĩnh viễn
     * @return bool
     */
    public function permanentDeleteMultiple(array $ids)
    {
        $success = true;
        foreach ($ids as $id) {
            if (!$this->delete($id, true)) {
                $success = false;
            }
        }
        return $success;
    }
    
    /**
     * Khôi phục một bậc học đã xóa
     * 
     * @param int $id ID bậc học cần khôi phục
     * @return bool Kết quả khôi phục
     */
    public function restore($id)
    {
        return $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->update([$this->deletedField => null]);
    }
} 