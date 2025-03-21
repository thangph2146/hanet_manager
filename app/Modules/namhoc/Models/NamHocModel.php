<?php

namespace App\Modules\namhoc\Models;

use App\Models\BaseModel;

class NamHocModel extends BaseModel
{
    protected $table = 'nam_hoc';
    protected $primaryKey = 'nam_hoc_id';
    protected $returnType = 'App\Modules\namhoc\Entities\NamHoc';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    
    protected $allowedFields = [
        'ten_nam_hoc',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'status',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    // Các trường liên quan đến timestamp
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Các trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_nam_hoc'
    ];
    
    // Các trường có thể lọc
    protected $filterableFields = [
        'status',
        'bin'
    ];
    
    // Các trường cần loại bỏ khoảng trắng thừa
    protected $beforeSpaceRemoval = [
        'ten_nam_hoc'
    ];
    
    // Định nghĩa các mối quan hệ - không sử dụng
    protected $relations = [];
    
    // Thêm log để theo dõi lúc khởi tạo model
    public function __construct()
    {
        parent::__construct();
        log_message('debug', 'NamHocModel initialized with table: ' . $this->table);
    }
    
    // Các phương thức tùy chỉnh cho NamHocModel
    
    /**
     * Lấy danh sách tất cả năm học đang hoạt động và không bị xóa
     *
     * @return array
     */
    public function getAllActive()
    {
        // Ghi log để debug
        log_message('debug', 'Đang lấy danh sách năm học đang hoạt động');
        
        // Đảm bảo loại bỏ những bản ghi đã bị xóa mềm
        return $this->where('deleted_at IS NULL')
                    ->orderBy('ten_nam_hoc', 'ASC')
                    ->findAll();
    }
    
    /**
     * Lấy danh sách tất cả năm học đã bị xóa tạm thời
     *
     * @return array
     */
    public function getAllDeleted()
    {
        return $this->onlyDeleted()
                    ->where('deleted_at IS NOT NULL')
                    ->orderBy('deleted_at', 'DESC')
                    ->findAll();
    }
    
    /**
     * Lấy danh sách năm học đang hoạt động
     *
     * @return array
     */
    public function getActive()
    {
        return $this->where('status', 1)
                    ->where('bin', 0)
                    ->orderBy('ten_nam_hoc', 'ASC')
                    ->findAll();
    }
    
    /**
     * Kiểm tra tên năm học đã tồn tại chưa
     *
     * @param string $tenNamHoc
     * @param int|null $exceptId ID năm học cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isNameExists(string $tenNamHoc, int $exceptId = null)
    {
        $builder = $this->where('ten_nam_hoc', $tenNamHoc);
        
        if ($exceptId !== null) {
            $builder->where('nam_hoc_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Đưa một bản ghi vào thùng rác
     *
     * @param int $id ID của năm học cần đưa vào thùng rác
     * @return bool Kết quả thao tác
     */
    public function moveToBin($id)
    {
        log_message('debug', "NamHocModel::moveToBin - Moving ID $id to bin");
        
        // Tìm bản ghi theo ID
        $namHoc = $this->find($id);
        
        // Kiểm tra xem bản ghi có tồn tại không
        if (!$namHoc) {
            log_message('debug', "NamHocModel::moveToBin - ID $id not found");
            return false;
        }
        
        // Kiểm tra xem bản ghi đã ở trong thùng rác chưa
        if ($namHoc->is_deleted) {
            log_message('debug', "NamHocModel::moveToBin - ID $id already in bin (is_deleted = {$namHoc->is_deleted})");
            return true; // Đã ở trong thùng rác, coi như thành công
        }
        
        // Đưa vào thùng rác
        $namHoc->is_deleted = 1;
        $namHoc->deleted_at = date('Y-m-d H:i:s');
        
        // Lưu thay đổi
        $result = $this->save($namHoc);
        
        if ($result) {
            log_message('debug', "NamHocModel::moveToBin - Successfully moved ID $id to bin");
        } else {
            log_message('debug', "NamHocModel::moveToBin - Failed to move ID $id to bin. Errors: " . print_r($this->errors(), true));
        }
        
        return $result;
    }
    
    /**
     * Khôi phục năm học từ thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function restoreFromBin(int $id)
    {
        return $this->update($id, ['bin' => 0]);
    }
    
    /**
     * Lấy danh sách năm học trong thùng rác
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
     * Xóa tạm thời năm học (soft delete)
     *
     * @param int $id
     * @return bool
     */
    public function softDelete(int $id)
    {
        return $this->delete($id);
    }
    
    /**
     * Xóa tạm thời nhiều năm học
     *
     * @param array $ids Mảng các ID cần xóa
     * @return bool
     */
    public function softDeleteMultiple(array $ids)
    {
        return $this->delete($ids);
    }
    
    /**
     * Khôi phục năm học đã xóa tạm thời
     *
     * @param int $id
     * @return bool
     */
    public function restoreDeleted(int $id)
    {
        return $this->restore($id);
    }
    
    /**
     * Khôi phục nhiều năm học đã xóa tạm thời
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
     * Lấy danh sách năm học đã xóa tạm thời
     *
     * @return array
     */
    public function getSoftDeleted()
    {
        return $this->onlyDeleted()->findAll();
    }
    
    /**
     * Xóa vĩnh viễn năm học
     *
     * @param int $id
     * @return bool
     */
    public function permanentDelete(int $id)
    {
        return $this->delete($id, true);
    }
    
    /**
     * Xóa vĩnh viễn nhiều năm học
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
     * Khôi phục một năm học đã xóa
     * 
     * @param int $id ID năm học cần khôi phục
     * @return bool Kết quả khôi phục
     */
    public function restore($id)
    {
        return $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->update([$this->deletedField => null]);
    }
} 