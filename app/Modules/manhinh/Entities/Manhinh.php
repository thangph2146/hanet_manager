<?php

namespace App\Modules\manhinh\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class ManHinh extends BaseEntity
{
    protected $tableName = 'man_hinh';
    protected $primaryKey = 'man_hinh_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'man_hinh_id' => 'int',
        'camera_id' => 'int',
        'template_id' => 'int',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_ten_man_hinh' => ['ten_man_hinh']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_ten_man_hinh' => ['ten_man_hinh']
    ];
    
    // Các quy tắc xác thực cụ thể cho ManHinh
    protected $validationRules = [
        'ten_man_hinh' => [
            'rules' => 'required|max_length[255]|is_unique[man_hinh.ten_man_hinh,man_hinh_id,{man_hinh_id}]',
            'label' => 'Tên màn hình'
        ],
        'ma_man_hinh' => [
            'rules' => 'permit_empty|max_length[20]',
            'label' => 'Mã màn hình'
        ],
        'camera_id' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Camera'
        ],
        'template_id' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Template'
        ],
        'status' => [
            'rules' => 'required|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ten_man_hinh' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 255 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'ma_man_hinh' => [
            'max_length' => '{field} không được vượt quá 20 ký tự'
        ],
        'camera_id' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'template_id' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'status' => [
            'required' => '{field} không được để trống',
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của màn hình
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên màn hình
     *
     * @return string
     */
    public function getTenManHinh(): string
    {
        return $this->attributes['ten_man_hinh'] ?? '';
    }
    
    /**
     * Lấy mã màn hình
     *
     * @return string|null
     */
    public function getMaManHinh(): ?string
    {
        return $this->attributes['ma_man_hinh'] ?? null;
    }
    
    /**
     * Lấy ID camera
     *
     * @return int|null
     */
    public function getCameraId(): ?int
    {
        return isset($this->attributes['camera_id']) ? (int)$this->attributes['camera_id'] : null;
    }
    
    /**
     * Lấy ID template
     *
     * @return int|null
     */
    public function getTemplateId(): ?int
    {
        return isset($this->attributes['template_id']) ? (int)$this->attributes['template_id'] : null;
    }
    
    /**
     * Kiểm tra trạng thái hoạt động
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? true);
    }
    
    /**
     * Đặt trạng thái hoạt động
     *
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status)
    {
        $this->attributes['status'] = (int)$status;
        return $this;
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
     * Lấy nhãn trạng thái hiển thị
     *
     * @return string HTML với badge status
     */
    public function getStatusLabel(): string
    {
        if ($this->status == 1) {
            return '<span class="badge bg-success">Hoạt động</span>';
        } else {
            return '<span class="badge bg-danger">Không hoạt động</span>';
        }
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