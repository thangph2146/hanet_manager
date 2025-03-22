<?php

namespace App\Modules\hedaotao\Models;

use App\Models\BaseModel;

class HeDaoTaoModel extends BaseModel
{
    protected $table = 'he_dao_tao';
    protected $primaryKey = 'he_dao_tao_id';
    protected $returnType = 'App\Modules\hedaotao\Entities\HeDaoTao';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    
    protected $allowedFields = [
        'ten_he_dao_tao',
        'ma_he_dao_tao',
        'status',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    // Các trường có thể tìm kiếm
    protected $searchableFields = [
        'ten_he_dao_tao',
        'ma_he_dao_tao'
    ];
    
    // Các trường có thể lọc
    protected $filterableFields = [
        'status',
        'bin'
    ];
    
    // Các trường cần loại bỏ khoảng trắng thừa
    protected $beforeSpaceRemoval = [
        'ten_he_dao_tao',
        'ma_he_dao_tao'
    ];
    
    // Các phương thức truy vấn tiêu chuẩn
    
    /**
     * Lấy danh sách tất cả hệ đào tạo đang hoạt động và không bị xóa
     *
     * @return array
     */
    public function getAllActive()
    {
        return $this->where('bin', 0)
                    ->orderBy('ten_he_dao_tao', 'ASC')
                    ->findAll();
    }
    
    /**
     * Lấy danh sách tất cả hệ đào tạo đã bị xóa tạm thời
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
     * Lấy danh sách hệ đào tạo đang hoạt động
     *
     * @return array
     */
    public function getActive()
    {
        return $this->where('status', 1)
                    ->where('bin', 0)
                    ->orderBy('ten_he_dao_tao', 'ASC')
                    ->findAll();
    }
    
    /**
     * Kiểm tra tên hệ đào tạo đã tồn tại chưa
     *
     * @param string $tenHeDaoTao
     * @param int|null $exceptId ID hệ đào tạo cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isNameExists(string $tenHeDaoTao, int $exceptId = null)
    {
        $builder = $this->where('ten_he_dao_tao', $tenHeDaoTao);
        
        if ($exceptId !== null) {
            $builder->where('he_dao_tao_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Kiểm tra mã hệ đào tạo đã tồn tại chưa
     *
     * @param string $maHeDaoTao
     * @param int|null $exceptId ID hệ đào tạo cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isCodeExists(string $maHeDaoTao, int $exceptId = null)
    {
        if (empty($maHeDaoTao)) {
            return false;
        }
        
        $builder = $this->where('ma_he_dao_tao', $maHeDaoTao);
        
        if ($exceptId !== null) {
            $builder->where('he_dao_tao_id !=', $exceptId);
        }
        
        return $builder->countAllResults() > 0;
    }
    
    /**
     * Chuyển hệ đào tạo vào thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function moveToBin(int $id)
    {
        return $this->update($id, ['bin' => 1]);
    }
    
    /**
     * Khôi phục hệ đào tạo từ thùng rác
     *
     * @param int $id
     * @return bool
     */
    public function restoreFromBin(int $id)
    {
        return $this->update($id, ['bin' => 0]);
    }
    
    /**
     * Lấy danh sách hệ đào tạo trong thùng rác
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
     * Xóa tạm thời hệ đào tạo (soft delete)
     *
     * @param int $id
     * @return bool
     */
    public function softDelete(int $id)
    {
        return $this->delete($id);
    }
    
    /**
     * Xóa tạm thời nhiều hệ đào tạo
     *
     * @param array $ids Mảng các ID cần xóa
     * @return bool
     */
    public function softDeleteMultiple(array $ids)
    {
        return $this->delete($ids);
    }
    
    /**
     * Khôi phục hệ đào tạo đã xóa tạm thời
     *
     * @param int $id
     * @return bool
     */
    public function restoreDeleted(int $id)
    {
        return $this->restore($id);
    }
    
    /**
     * Khôi phục nhiều hệ đào tạo đã xóa tạm thời
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
     * Lấy danh sách hệ đào tạo đã xóa tạm thời
     *
     * @return array
     */
    public function getSoftDeleted()
    {
        return $this->onlyDeleted()->findAll();
    }
    
    /**
     * Xóa vĩnh viễn hệ đào tạo
     *
     * @param int $id
     * @return bool
     */
    public function permanentDelete(int $id)
    {
        return $this->delete($id, true);
    }
    
    /**
     * Xóa vĩnh viễn nhiều hệ đào tạo
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
     * Khôi phục một hệ đào tạo đã xóa
     * 
     * @param int $id ID hệ đào tạo cần khôi phục
     * @return bool Kết quả khôi phục
     */
    public function restore($id)
    {
        return $this->db->table($this->table)
            ->where($this->primaryKey, $id)
            ->update([$this->deletedField => null]);
    }
} 