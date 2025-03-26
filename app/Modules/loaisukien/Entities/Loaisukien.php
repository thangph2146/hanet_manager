<?php

namespace App\Modules\loaisukien\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class LoaiSuKien extends BaseEntity
{
    protected $tableName = 'loai_su_kien';
    protected $primaryKey = 'loai_su_kien_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'loai_su_kien_id' => 'int',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_ten_loai_su_kien' => ['ten_loai_su_kien']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_ten_loai_su_kien' => ['ten_loai_su_kien']
    ];
    
    // Các quy tắc xác thực cụ thể cho LoaiSuKien
    protected $validationRules = [
        'ten_loai_su_kien' => [
            'rules' => 'required|max_length[100]|is_unique[loai_su_kien.ten_loai_su_kien,loai_su_kien_id,{loai_su_kien_id}]',
            'label' => 'Tên loại sự kiện'
        ],
        'ma_loai_su_kien' => [
            'rules' => 'permit_empty|max_length[20]',
            'label' => 'Mã loại sự kiện'
        ],
        'status' => [
            'rules' => 'required|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ten_loai_su_kien' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 100 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'ma_loai_su_kien' => [
            'max_length' => '{field} không được vượt quá 20 ký tự'
        ],
        'status' => [
            'required' => '{field} không được để trống',
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của loại sự kiện
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên loại sự kiện
     *
     * @return string
     */
    public function getTenLoaiSuKien(): string
    {
        return $this->attributes['ten_loai_su_kien'] ?? '';
    }
    
    /**
     * Lấy mã loại sự kiện
     *
     * @return string|null
     */
    public function getMaLoaiSuKien(): ?string
    {
        return $this->attributes['ma_loai_su_kien'] ?? null;
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