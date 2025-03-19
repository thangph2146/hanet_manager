<?php

namespace App\Modules\loainguoidung\Models;

use App\Models\BaseModel;

class LoaiNguoiDungModel extends BaseModel
{
    protected $table = 'loai_nguoi_dung';
    protected $primaryKey = 'loai_nguoi_dung_id';
    protected $returnType = 'App\Modules\loainguoidung\Entities\LoaiNguoiDung';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    
    protected $allowedFields = [
        'ten_loai',
        'mo_ta',
        'status',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    // Các trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_loai',
        'mo_ta'
    ];
    
    // Các trường có thể lọc
    protected $filterableFields = [
        'status',
        'bin'
    ];
    
    // Các trường cần loại bỏ khoảng trắng thừa
    protected $beforeSpaceRemoval = [
        'ten_loai',
        'mo_ta'
    ];
    
    // Định nghĩa các mối quan hệ - không sử dụng
    protected $relations = [];
    
    // Các phương thức tùy chỉnh cho LoaiNguoiDungModel
    
    /**
     * Lấy danh sách loại người dùng đang hoạt động
     *
     * @return array
     */
    public function getActive()
    {
        return $this->where('status', 1)
                    ->where('bin', 0)
                    ->orderBy('ten_loai', 'ASC')
                    ->findAll();
    }
    
    /**
     * Kiểm tra tên loại người dùng đã tồn tại chưa
     *
     * @param string $tenLoai
     * @param int|null $exceptId ID loại người dùng cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isNameExists(string $tenLoai, int $exceptId = null)
    {
        $builder = $this->where('ten_loai', $tenLoai);
        
        if ($exceptId !== null) {
            $builder->where('loai_nguoi_dung_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Chuyển loại người dùng vào thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function moveToBin(int $id)
    {
        return $this->update($id, ['bin' => 1]);
    }
    
    /**
     * Khôi phục loại người dùng từ thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function restoreFromBin(int $id)
    {
        return $this->update($id, ['bin' => 0]);
    }
    
    /**
     * Lấy danh sách loại người dùng trong thùng rác
     *
     * @return array
     */
    public function getBinnedItems()
    {
        return $this->where('bin', 1)
                    ->orderBy('updated_at', 'DESC')
                    ->findAll();
    }
}
