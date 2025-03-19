<?php

namespace App\Modules\loainguoidung\Entities;

use App\Entities\BaseEntity;

class LoaiNguoiDung extends BaseEntity
{
    protected $tableName = 'loai_nguoi_dung';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'loai_nguoi_dung_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $datamap = [];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Các quy tắc xác thực cụ thể cho LoaiNguoiDung
    protected $validationRules = [
        'ten_loai' => 'required|min_length[3]|max_length[50]',
        'mo_ta' => 'permit_empty|max_length[1000]',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_loai' => [
            'required' => 'Tên loại người dùng là bắt buộc',
            'min_length' => 'Tên loại người dùng phải có ít nhất {param} ký tự',
            'max_length' => 'Tên loại người dùng không được vượt quá {param} ký tự',
        ],
    ];
}
