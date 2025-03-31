<?php

namespace App\Modules\bachoc\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class BacHoc extends BaseEntity
{
    protected $tableName = 'bac_hoc';
    protected $primaryKey = 'bac_hoc_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'bac_hoc_id' => 'int',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_ten_bac_hoc' => ['ten_bac_hoc']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_ten_bac_hoc' => ['ten_bac_hoc']
    ];
    
    // Các quy tắc xác thực cụ thể cho BacHoc
    protected $validationRules = [
        'ten_bac_hoc' => [
            'rules' => 'required|max_length[100]|is_unique[bac_hoc.ten_bac_hoc,bac_hoc_id,{bac_hoc_id}]',
            'label' => 'Tên bậc học'
        ],
        'ma_bac_hoc' => [
            'rules' => 'permit_empty|max_length[20]',
            'label' => 'Mã bậc học'
        ],
        'status' => [
            'rules' => 'required|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ten_bac_hoc' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 100 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'ma_bac_hoc' => [
            'max_length' => '{field} không được vượt quá 20 ký tự'
        ],
        'status' => [
            'required' => '{field} không được để trống',
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của bậc học
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên bậc học
     *
     * @return string
     */
    public function getTenBacHoc(): string
    {
        return $this->attributes['ten_bac_hoc'] ?? '';
    }
    
    /**
     * Lấy mã bậc học
     *
     * @return string|null
     */
    public function getMaBacHoc(): ?string
    {
        return $this->attributes['ma_bac_hoc'] ?? null;
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