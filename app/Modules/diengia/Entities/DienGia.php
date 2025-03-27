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
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_ten_dien_gia' => ['ten_dien_gia']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_ten_dien_gia' => ['ten_dien_gia']
    ];
    
    // Các quy tắc xác thực cụ thể cho DienGia
    protected $validationRules = [
        'ten_dien_gia' => [
            'rules' => 'required|max_length[255]|is_unique[dien_gia.ten_dien_gia,dien_gia_id,{dien_gia_id}]',
            'label' => 'Tên diễn giả'
        ],
        'chuc_danh' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Chức danh'
        ],
        'to_chuc' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Tổ chức'
        ],
        'gioi_thieu' => [
            'rules' => 'permit_empty',
            'label' => 'Giới thiệu'
        ],
        'avatar' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Ảnh đại diện'
        ],
        'thu_tu' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Thứ tự'
        ]
    ];
    
    protected $validationMessages = [
        'ten_dien_gia' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 255 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'chuc_danh' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'to_chuc' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'avatar' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'thu_tu' => [
            'integer' => '{field} phải là số nguyên'
        ]
    ];
    
    /**
     * Lấy ID của diễn giả
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên diễn giả
     *
     * @return string
     */
    public function getTenDienGia(): string
    {
        return $this->attributes['ten_dien_gia'] ?? '';
    }
    
    /**
     * Lấy chức danh
     *
     * @return string|null
     */
    public function getChucDanh(): ?string
    {
        return $this->attributes['chuc_danh'] ?? null;
    }
    
    /**
     * Lấy tổ chức
     *
     * @return string|null
     */
    public function getToChuc(): ?string
    {
        return $this->attributes['to_chuc'] ?? null;
    }
    
    /**
     * Lấy giới thiệu
     *
     * @return string|null
     */
    public function getGioiThieu(): ?string
    {
        return $this->attributes['gioi_thieu'] ?? null;
    }
    
    /**
     * Lấy avatar
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->attributes['avatar'] ?? null;
    }
    
    /**
     * Lấy thứ tự
     *
     * @return int
     */
    public function getThuTu(): int
    {
        return (int)($this->attributes['thu_tu'] ?? 0);
    }
    
    /**
     * Kiểm tra xem bản ghi đã bị xóa chưa
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return !empty($this->attributes['deleted_at']);
    }
    
    /**
     * Lấy ngày tạo đã định dạng
     * 
     * @return string
     */
    public function getCreatedAtFormatted(): string
    {
        if (empty($this->attributes['created_at'])) {
            return '<span class="text-muted fst-italic">Chưa cập nhật</span>';
        }
        
        $time = $this->attributes['created_at'] instanceof Time 
            ? $this->attributes['created_at'] 
            : new Time($this->attributes['created_at']);
            
        return $time->format('Y-m-d H:i:s');
    }
    
    /**
     * Lấy ngày cập nhật đã định dạng
     * 
     * @return string
     */
    public function getUpdatedAtFormatted(): string
    {
        if (empty($this->attributes['updated_at'])) {
            return '<span class="text-muted fst-italic">Chưa cập nhật</span>';
        }
        
        $time = $this->attributes['updated_at'] instanceof Time 
            ? $this->attributes['updated_at'] 
            : new Time($this->attributes['updated_at']);
            
        return $time->format('Y-m-d H:i:s');
    }
    
    /**
     * Lấy ngày xóa đã định dạng
     * 
     * @return string
     */
    public function getDeletedAtFormatted(): string
    {
        if (empty($this->attributes['deleted_at'])) {
            return '<span class="text-muted fst-italic">Chưa xóa</span>';
        }
        
        $time = $this->attributes['deleted_at'] instanceof Time 
            ? $this->attributes['deleted_at'] 
            : new Time($this->attributes['deleted_at']);
            
        return $time->format('Y-m-d H:i:s');
    }
    
    /**
     * Lấy các quy tắc xác thực
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->validationRules;
    }
    
    /**
     * Lấy các thông báo xác thực
     *
     * @return array
     */
    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }
} 