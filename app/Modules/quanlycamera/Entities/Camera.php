<?php

namespace App\Modules\quanlycamera\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class Camera extends BaseEntity
{
    protected $tableName = 'camera';
    protected $primaryKey = 'camera_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'camera_id' => 'int',
        'port' => 'int',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_ten_camera' => ['ten_camera']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_ten_camera' => ['ten_camera']
    ];
    
    // Các quy tắc xác thực cụ thể cho Camera
    protected $validationRules = [
        'ten_camera' => [
            'rules' => 'required|max_length[255]|is_unique[camera.ten_camera,camera_id,{camera_id}]',
            'label' => 'Tên camera'
        ],
        'ma_camera' => [
            'rules' => 'permit_empty|max_length[20]',
            'label' => 'Mã camera'
        ],
        'ip_camera' => [
            'rules' => 'permit_empty|max_length[100]|valid_ip',
            'label' => 'IP camera'
        ],
        'port' => [
            'rules' => 'permit_empty|integer|greater_than[0]|less_than[65536]',
            'label' => 'Port'
        ],
        'status' => [
            'rules' => 'required|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ten_camera' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 255 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'ma_camera' => [
            'max_length' => '{field} không được vượt quá 20 ký tự'
        ],
        'ip_camera' => [
            'max_length' => '{field} không được vượt quá 100 ký tự',
            'valid_ip' => '{field} không hợp lệ'
        ],
        'port' => [
            'integer' => '{field} phải là số nguyên',
            'greater_than' => '{field} phải lớn hơn 0',
            'less_than' => '{field} phải nhỏ hơn 65536'
        ],
        'status' => [
            'required' => '{field} không được để trống',
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của camera
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên camera
     *
     * @return string
     */
    public function getTenCamera(): string
    {
        return $this->attributes['ten_camera'] ?? '';
    }
    
    /**
     * Lấy mã camera
     *
     * @return string|null
     */
    public function getMaCamera(): ?string
    {
        return $this->attributes['ma_camera'] ?? null;
    }
    
    /**
     * Lấy IP camera
     *
     * @return string|null
     */
    public function getIpCamera(): ?string
    {
        return $this->attributes['ip_camera'] ?? null;
    }
    
    /**
     * Lấy port
     *
     * @return int|null
     */
    public function getPort(): ?int
    {
        return $this->attributes['port'] ?? null;
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