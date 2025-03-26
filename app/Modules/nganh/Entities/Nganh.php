<?php

namespace App\Modules\nganh\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class Nganh extends BaseEntity
{
    protected $tableName = 'nganh';
    protected $primaryKey = 'nganh_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'nganh_id' => 'int',
        'phong_khoa_id' => 'int',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_ma_nganh' => ['ma_nganh'],
        'idx_ten_nganh' => ['ten_nganh'],
        'idx_phong_khoa_id' => ['phong_khoa_id']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_ma_nganh' => ['ma_nganh']
    ];
    
    // Các quy tắc xác thực cụ thể cho Nganh
    protected $validationRules = [
        'ten_nganh' => [
            'rules' => 'required|max_length[200]',
            'label' => 'Tên ngành'
        ],
        'ma_nganh' => [
            'rules' => 'required|max_length[20]|is_unique[nganh.ma_nganh,nganh_id,{nganh_id}]',
            'label' => 'Mã ngành'
        ],
        'phong_khoa_id' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Phòng khoa'
        ],
        'status' => [
            'rules' => 'required|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ten_nganh' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 200 ký tự'
        ],
        'ma_nganh' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 20 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'phong_khoa_id' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'status' => [
            'required' => '{field} không được để trống',
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của ngành
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên ngành
     *
     * @return string
     */
    public function getTenNganh(): string
    {
        return $this->attributes['ten_nganh'] ?? '';
    }
    
    /**
     * Lấy mã ngành
     *
     * @return string
     */
    public function getMaNganh(): string
    {
        return $this->attributes['ma_nganh'] ?? '';
    }
    
    /**
     * Lấy ID phòng khoa
     *
     * @return int|null
     */
    public function getPhongKhoaId(): ?int
    {
        return isset($this->attributes['phong_khoa_id']) ? (int)$this->attributes['phong_khoa_id'] : null;
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