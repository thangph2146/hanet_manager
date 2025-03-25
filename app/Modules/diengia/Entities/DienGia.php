<?php

namespace App\Modules\diengia\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class DienGia extends BaseEntity
{
    protected $tableName = 'dien_gia';
    protected $primaryKey = 'dien_gia_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'dien_gia_id' => 'int',
        'thu_tu' => 'int',
        'bin' => 'int',
    ];
    
    protected $attributes = [
        'dien_gia_id' => null,
        'ten_dien_gia' => null,
        'chuc_danh' => null,
        'to_chuc' => null,
        'gioi_thieu' => null,
        'avatar' => null,
        'thu_tu' => 0,
        'bin' => 0,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Validation rules
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
    
    /**
     * Set avatar path
     */
    public function setAvatar(string $path = null)
    {
        $this->attributes['avatar'] = $path;
        return $this;
    }
    
    /**
     * Get avatar URL
     */
    public function getAvatarURL(): string
    {
        if (empty($this->attributes['avatar'])) {
            return base_url('assets/images/default-avatar.jpg');
        }
        
        return base_url($this->attributes['avatar']);
    }
} 