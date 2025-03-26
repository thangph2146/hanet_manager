<?php

namespace App\Modules\khoahoc\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class KhoaHoc extends BaseEntity
{
    protected $tableName = 'khoa_hoc';
    protected $primaryKey = 'khoa_hoc_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'khoa_hoc_id' => 'int',
        'nam_bat_dau' => 'int',
        'nam_ket_thuc' => 'int',
        'phong_khoa_id' => 'int',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_ten_khoa_hoc' => ['ten_khoa_hoc'],
        'idx_phong_khoa_id' => ['phong_khoa_id']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_ten_khoa_hoc' => ['ten_khoa_hoc']
    ];
    
    // Các quy tắc xác thực cụ thể cho KhoaHoc
    protected $validationRules = [
        'ten_khoa_hoc' => [
            'rules' => 'required|max_length[100]|is_unique[khoa_hoc.ten_khoa_hoc,khoa_hoc_id,{khoa_hoc_id}]',
            'label' => 'Tên khóa học'
        ],
        'nam_bat_dau' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Năm bắt đầu'
        ],
        'nam_ket_thuc' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Năm kết thúc'
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
        'ten_khoa_hoc' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 100 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'nam_bat_dau' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'nam_ket_thuc' => [
            'integer' => '{field} phải là số nguyên'
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
     * Lấy ID của khóa học
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên khóa học
     *
     * @return string
     */
    public function getTenKhoaHoc(): string
    {
        return $this->attributes['ten_khoa_hoc'] ?? '';
    }
    
    /**
     * Lấy năm bắt đầu
     *
     * @return int|null
     */
    public function getNamBatDau(): ?int
    {
        return isset($this->attributes['nam_bat_dau']) ? (int)$this->attributes['nam_bat_dau'] : null;
    }
    
    /**
     * Lấy năm kết thúc
     *
     * @return int|null
     */
    public function getNamKetThuc(): ?int
    {
        return isset($this->attributes['nam_ket_thuc']) ? (int)$this->attributes['nam_ket_thuc'] : null;
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