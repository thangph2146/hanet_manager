<?php

namespace App\Modules\diengia\Models;

use App\Models\BaseModel;
use App\Modules\diengia\Entities\DienGia;

class DienGiaModel extends BaseModel
{
    protected $table = 'dien_gia';
    protected $primaryKey = 'dien_gia_id';
    protected $returnType = 'App\Modules\diengia\Entities\DienGia';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    
    protected $allowedFields = [
        'ten_dien_gia',
        'chuc_danh',
        'to_chuc',
        'gioi_thieu',
        'avatar',
        'thu_tu',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    // Timestamps
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'ten_dien_gia' => 'required',
        'chuc_danh' => 'permit_empty',
        'to_chuc' => 'permit_empty',
        'gioi_thieu' => 'permit_empty',
        'avatar' => 'permit_empty',
        'thu_tu' => 'permit_empty|numeric',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_dien_gia' => [
            'required' => 'Tên diễn giả là bắt buộc',
        ],
    ];
    
    // Searchable fields
    protected $searchableFields = [
        'ten_dien_gia',
        'chuc_danh',
        'to_chuc'
    ];
    
    // Filterable fields
    protected $filterableFields = [
        'bin'
    ];
    
    // Relations
    // Define any necessary relations here
} 