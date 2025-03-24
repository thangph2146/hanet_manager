<?php

namespace App\Modules\diengia\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class DienGia extends BaseEntity
{
    protected $tableName = 'dien_gia';

    protected $attributes = [
        'dien_gia_id' => null,
        'ten_dien_gia' => null,
        'chuc_danh' => null, // Lưu dưới dạng chuỗi tự nhập
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

    protected $jsonFields = [];

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'bin' => 'boolean',
        'thu_tu' => 'int',
    ];

    // Getter cho chuc_danh
    public function getChucDanh()
    {
        return $this->attributes['chuc_danh'] ?? 'Chưa xác định';
    }

    // Setter cho chuc_danh
    public function setChucDanh($value)
    {
        $this->attributes['chuc_danh'] = $value;
    }

    // Getter cho to_chuc
    public function getToChuc()
    {
        return $this->attributes['to_chuc'] ?? '';
    }

    // Getter cho gioi_thieu với xử lý HTML
    public function getGioiThieu($format = 'html')
    {
        $content = $this->attributes['gioi_thieu'] ?? '';
        
        if ($format === 'plain') {
            return strip_tags($content);
        }
        
        return $content;
    }

    // Getter cho avatar với đường dẫn đầy đủ
    public function getAvatar($size = 'full')
    {
        $imagePath = 'data/images/diengia/';
        $defaultImage = 'default-avatar.png';
        
        if (empty($this->attributes['avatar'])) {
            return 'https://muster.vn/' . $defaultImage;
        }
        
        // Xử lý kích thước ảnh nếu cần
        if ($size === 'thumb') {
            $path = $imagePath . 'thumbs/' . $this->attributes['avatar'];
        } else {
            $path = $imagePath . $this->attributes['avatar'];
        }
        
        return 'https://muster.vn/' . $path;
    }

    // Phương thức lấy thông tin rút gọn
    public function getSummary($wordLimit = 20)
    {
        $gioiThieu = $this->getGioiThieu('plain');
        $words = explode(' ', $gioiThieu);
        
        if (count($words) <= $wordLimit) {
            return $gioiThieu;
        }
        
        return implode(' ', array_slice($words, 0, $wordLimit)) . '...';
    }

    // Phương thức kiểm tra xem diễn giả có ảnh không
    public function hasAvatar()
    {
        return !empty($this->attributes['avatar']);
    }

    protected $validationRules = [
        'ten_dien_gia' => 'required|min_length[3]|max_length[255]',
        'chuc_danh' => 'permit_empty|max_length[255]',
        'to_chuc' => 'permit_empty|max_length[255]',
        'gioi_thieu' => 'permit_empty',
        'avatar' => 'permit_empty|max_length[255]',
        'thu_tu' => 'permit_empty|integer',
        'bin' => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [
        'ten_dien_gia' => [
            'required' => 'Tên diễn giả là bắt buộc.',
            'min_length' => 'Tên diễn giả phải dài ít nhất 3 ký tự.',
        ],
        'chuc_danh' => [
            'max_length' => 'Chức danh không được vượt quá 255 ký tự.',
        ],
    ];
} 