<?php

namespace App\Modules\phongkhoa\Entities;

use App\Entities\BaseEntity;

class PhongKhoa extends BaseEntity
{
    protected $tableName = 'phong_khoa';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'phong_khoa_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $datamap = [];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Các quy tắc xác thực cụ thể cho PhongKhoa
    protected $validationRules = [
        'ma_phong_khoa' => 'required|min_length[2]|max_length[20]',
        'ten_phong_khoa' => 'required|min_length[3]|max_length[100]',
        'ghi_chu' => 'permit_empty|max_length[1000]',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ma_phong_khoa' => [
            'required' => 'Mã phòng khoa là bắt buộc',
            'min_length' => 'Mã phòng khoa phải có ít nhất {param} ký tự',
            'max_length' => 'Mã phòng khoa không được vượt quá {param} ký tự',
        ],
        'ten_phong_khoa' => [
            'required' => 'Tên phòng khoa là bắt buộc',
            'min_length' => 'Tên phòng khoa phải có ít nhất {param} ký tự',
            'max_length' => 'Tên phòng khoa không được vượt quá {param} ký tự',
        ],
    ];
}
