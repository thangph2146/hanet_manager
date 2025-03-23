<?php

namespace App\Modules\facenguoidung\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class Facenguoidung extends BaseEntity
{
    protected $tableName = 'face_nguoi_dung';
    protected $primaryKey = 'face_nguoi_dung_id';
    
    protected $dates = [
        'ngay_cap_nhat',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'face_nguoi_dung_id' => 'int',
        'nguoi_dung_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $datamap = [];
    
    protected $attributes = [
        'face_nguoi_dung_id' => null,
        'nguoi_dung_id' => null,
        'duong_dan_anh' => null,
        'ngay_cap_nhat' => null,
        'status' => 1,
        'bin' => 0,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];
    
    protected $jsonFields = [];
    
    // Fields that should not be returned to the client
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Validation rules
    protected $validationRules = [
        'nguoi_dung_id' => 'required',
        'duong_dan_anh' => 'required',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'nguoi_dung_id' => [
            'required' => 'Người dùng là bắt buộc',
        ],
        'duong_dan_anh' => [
            'required' => 'Đường dẫn ảnh là bắt buộc',
        ],
    ];
    
    /**
     * Set image path
     */
    public function setDuongDanAnh(string $path = null)
    {
        $this->attributes['duong_dan_anh'] = $path;
        return $this;
    }
    
    /**
     * Get image URL
     */
    public function getImageURL(): string
    {
        if (empty($this->attributes['duong_dan_anh'])) {
            return base_url('assets/images/default-avatar.jpg');
        }
        
        return base_url($this->attributes['duong_dan_anh']);
    }
    
    /**
     * Set the update date to current time
     */
    public function setUpdateDate()
    {
        $this->attributes['ngay_cap_nhat'] = Time::now();
        return $this;
    }
} 