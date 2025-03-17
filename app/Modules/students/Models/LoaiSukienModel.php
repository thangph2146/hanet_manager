<?php

namespace App\Modules\students\Models;

use CodeIgniter\Model;

class LoaiSukienModel extends Model
{
    protected $table = 'loai_sukien';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'loai_su_kien', 'mo_ta', 'ngay_tao', 'ngay_cap_nhat'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'ngay_tao';
    protected $updatedField = 'ngay_cap_nhat';
    
    // Lấy tất cả loại sự kiện
    public function getAllTypes()
    {
        return $this->findAll();
    }
} 