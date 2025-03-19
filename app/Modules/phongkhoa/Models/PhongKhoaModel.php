<?php

namespace App\Modules\phongkhoa\Models;

use App\Models\BaseModel;

class PhongKhoaModel extends BaseModel
{
    protected $table = 'phong_khoa';
    protected $primaryKey = 'phong_khoa_id';
    protected $returnType = 'App\Modules\phongkhoa\Entities\PhongKhoa';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    
    protected $allowedFields = [
        'ma_phong_khoa',
        'ten_phong_khoa',
        'ghi_chu',
        'status',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    // Các trường có thể tìm kiếm
    protected $searchableFields = [
        'ma_phong_khoa',
        'ten_phong_khoa',
        'ghi_chu'
    ];
    
    // Các trường có thể lọc
    protected $filterableFields = [
        'status',
        'bin'
    ];
    
    // Các trường cần loại bỏ khoảng trắng thừa
    protected $beforeSpaceRemoval = [
        'ma_phong_khoa',
        'ten_phong_khoa',
        'ghi_chu'
    ];
    
    // Định nghĩa các mối quan hệ - không sử dụng
    protected $relations = [];
    
    // Quy tắc xác thực
    public $validationRules = [
        'ma_phong_khoa' => 'required|min_length[2]|max_length[20]',
        'ten_phong_khoa' => 'required|min_length[3]|max_length[100]',
        'ghi_chu' => 'permit_empty|max_length[1000]',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]'
    ];
    
    // Các phương thức tùy chỉnh cho PhongKhoaModel
    
    /**
     * Lấy danh sách phòng khoa đang hoạt động
     *
     * @return array
     */
    public function getActive()
    {
        return $this->where('status', 1)
                    ->where('bin', 0)
                    ->orderBy('ten_phong_khoa', 'ASC')
                    ->findAll();
    }
    
    /**
     * Kiểm tra mã phòng khoa đã tồn tại chưa
     *
     * @param string $maPhongKhoa
     * @param int|null $exceptId ID phòng khoa cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isCodeExists(string $maPhongKhoa, int $exceptId = null)
    {
        $builder = $this->where('ma_phong_khoa', $maPhongKhoa);
        
        if ($exceptId !== null) {
            $builder->where('phong_khoa_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Chuyển phòng khoa vào thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function moveToBin(int $id)
    {
        return $this->update($id, ['bin' => 1]);
    }
    
    /**
     * Khôi phục phòng khoa từ thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function restoreFromBin(int $id)
    {
        return $this->update($id, ['bin' => 0]);
    }
    
    /**
     * Lấy danh sách phòng khoa trong thùng rác
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
     * Xóa tạm thời phòng khoa (soft delete)
     *
     * @param int $id
     * @return bool
     */
    public function softDelete(int $id)
    {
        return $this->delete($id);
    }
    
    /**
     * Xóa tạm thời nhiều phòng khoa
     *
     * @param array $ids Mảng các ID cần xóa
     * @return bool
     */
    public function softDeleteMultiple(array $ids)
    {
        return $this->delete($ids);
    }
    
    /**
     * Khôi phục phòng khoa đã xóa tạm thời
     *
     * @param int $id
     * @return bool
     */
    public function restoreDeleted(int $id)
    {
        return $this->restore($id);
    }
    
    /**
     * Khôi phục nhiều phòng khoa đã xóa tạm thời
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
     * Lấy danh sách phòng khoa đã xóa tạm thời
     *
     * @return array
     */
    public function getSoftDeleted()
    {
        return $this->onlyDeleted()->findAll();
    }
    
    /**
     * Xóa vĩnh viễn phòng khoa
     *
     * @param int $id
     * @return bool
     */
    public function permanentDelete(int $id)
    {
        return $this->delete($id, true);
    }
    
    /**
     * Xóa vĩnh viễn nhiều phòng khoa
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
}
