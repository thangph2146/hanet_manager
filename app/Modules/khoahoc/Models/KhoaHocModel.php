<?php

namespace App\Modules\khoahoc\Models;

use App\Models\BaseModel;

class KhoaHocModel extends BaseModel
{
    protected $table = 'khoa_hoc';
    protected $primaryKey = 'khoa_hoc_id';
    protected $returnType = 'App\Modules\khoahoc\Entities\KhoaHoc';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    
    protected $allowedFields = [
        'ten_khoa_hoc',
        'nam_bat_dau',
        'nam_ket_thuc',
        'phong_khoa_id',
        'status',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    // Các trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_khoa_hoc',
        'nam_bat_dau',
        'nam_ket_thuc'
    ];
    
    // Các trường có thể lọc
    protected $filterableFields = [
        'phong_khoa_id',
        'status',
        'bin'
    ];
    
    // Các trường cần loại bỏ khoảng trắng thừa
    protected $beforeSpaceRemoval = [
        'ten_khoa_hoc'
    ];
    
    // Định nghĩa các mối quan hệ - không sử dụng
    protected $relations = [];
    
    // Các phương thức tùy chỉnh cho KhoaHocModel
    
    /**
     * Lấy danh sách tất cả khóa học đang hoạt động và không bị xóa
     *
     * @return array
     */
    public function getAllActive()
    {
        return $this->where('bin', 0)
                    ->orderBy('ten_khoa_hoc', 'ASC')
                    ->findAll();
    }
    
    /**
     * Lấy danh sách tất cả khóa học đã bị xóa tạm thời
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
     * Lấy danh sách khóa học đang hoạt động
     *
     * @return array
     */
    public function getActive()
    {
        return $this->where('status', 1)
                    ->where('bin', 0)
                    ->orderBy('ten_khoa_hoc', 'ASC')
                    ->findAll();
    }
    
    /**
     * Kiểm tra tên khóa học đã tồn tại chưa
     *
     * @param string $tenKhoaHoc
     * @param int|null $exceptId ID khóa học cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isNameExists(string $tenKhoaHoc, int $exceptId = null)
    {
        $builder = $this->where('ten_khoa_hoc', $tenKhoaHoc);
        
        if ($exceptId !== null) {
            $builder->where('khoa_hoc_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Chuyển khóa học vào thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function moveToBin(int $id)
    {
        return $this->update($id, ['bin' => 1]);
    }
    
    /**
     * Khôi phục khóa học từ thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function restoreFromBin(int $id)
    {
        return $this->update($id, ['bin' => 0]);
    }
    
    /**
     * Lấy danh sách khóa học trong thùng rác
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
     * Xóa tạm thời khóa học (soft delete)
     *
     * @param int $id
     * @return bool
     */
    public function softDelete(int $id)
    {
        return $this->delete($id);
    }
    
    /**
     * Xóa tạm thời nhiều khóa học
     *
     * @param array $ids Mảng các ID cần xóa
     * @return bool
     */
    public function softDeleteMultiple(array $ids)
    {
        return $this->delete($ids);
    }
    
    /**
     * Khôi phục khóa học đã xóa tạm thời
     *
     * @param int $id
     * @return bool
     */
    public function restoreDeleted(int $id)
    {
        return $this->restore($id);
    }
    
    /**
     * Khôi phục nhiều khóa học đã xóa tạm thời
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
     * Lấy danh sách khóa học đã xóa tạm thời
     *
     * @return array
     */
    public function getSoftDeleted()
    {
        return $this->onlyDeleted()->findAll();
    }
    
    /**
     * Xóa vĩnh viễn khóa học
     *
     * @param int $id
     * @return bool
     */
    public function permanentDelete(int $id)
    {
        return $this->delete($id, true);
    }
    
    /**
     * Xóa vĩnh viễn nhiều khóa học
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
     * Khôi phục một khóa học đã xóa
     * 
     * @param int $id ID khóa học cần khôi phục
     * @return bool Kết quả khôi phục
     */
    public function restore($id)
    {
        return $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->update([$this->deletedField => null]);
    }
}
