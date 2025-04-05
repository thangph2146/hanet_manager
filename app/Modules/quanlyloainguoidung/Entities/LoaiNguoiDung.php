<?php

namespace App\Modules\quanlyloainguoidung\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class LoaiNguoiDung extends BaseEntity
{
    protected $tableName = 'loai_nguoi_dung';
    protected $primaryKey = 'loai_nguoi_dung_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'loai_nguoi_dung_id' => 'int',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Trường duy nhất cần kiểm tra
    protected $uniqueFields = [
        'ten_loai' => 'Tên loại người dùng'
    ];
    
    // Các quy tắc xác thực cụ thể cho LoaiNguoiDung
    protected $validationRules = [
        'ten_loai' => [
            'rules' => 'required|min_length[2]|max_length[50]|is_unique[loai_nguoi_dung.ten_loai,loai_nguoi_dung_id,{loai_nguoi_dung_id}]',
            'label' => 'Tên loại người dùng'
        ],
        'mo_ta' => [
            'rules' => 'permit_empty',
            'label' => 'Mô tả'
        ],
        'status' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ten_loai' => [
            'required' => '{field} là bắt buộc',
            'min_length' => '{field} phải có ít nhất {param} ký tự',
            'max_length' => '{field} không được vượt quá {param} ký tự',
            'is_unique' => '{field} đã tồn tại, vui lòng chọn tên khác'
        ],
        'status' => [
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của bản ghi loại người dùng
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên loại người dùng
     *
     * @return string
     */
    public function getTenLoai(): string
    {
        return $this->attributes['ten_loai'] ?? '';
    }
    
    /**
     * Lấy mô tả loại người dùng
     *
     * @return string|null
     */
    public function getMoTa(): ?string
    {
        return $this->attributes['mo_ta'] ?? null;
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
     * Lấy trạng thái hiện tại
     * 
     * @return int
     */
    public function getStatus(): int
    {
        return (int)($this->attributes['status'] ?? 1);
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
     * Lấy nhãn hiển thị trạng thái
     *
     * @return string
     */
    public function getStatusLabel(): string
    {
        if ($this->isActive()) {
            return '<span class="badge bg-success">Đang hoạt động</span>';
        } else {
            return '<span class="badge bg-danger">Ngừng hoạt động</span>';
        }
    }
    
    /**
     * Lấy ngày tạo theo định dạng
     *
     * @param string $format
     * @return string
     */
    public function getCreatedAtFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        if (empty($this->attributes['created_at'])) {
            return '';
        }

        if ($this->attributes['created_at'] instanceof Time) {
            return $this->attributes['created_at']->format($format);
        }

        return (new Time($this->attributes['created_at']))->format($format);
    }
    
    /**
     * Lấy ngày cập nhật theo định dạng
     *
     * @param string $format
     * @return string
     */
    public function getUpdatedAtFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        if (empty($this->attributes['updated_at'])) {
            return '';
        }

        if ($this->attributes['updated_at'] instanceof Time) {
            return $this->attributes['updated_at']->format($format);
        }

        return (new Time($this->attributes['updated_at']))->format($format);
    }
    
    /**
     * Lấy ngày xóa theo định dạng
     *
     * @param string $format
     * @return string
     */
    public function getDeletedAtFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        if (empty($this->attributes['deleted_at'])) {
            return '';
        }

        if ($this->attributes['deleted_at'] instanceof Time) {
            return $this->attributes['deleted_at']->format($format);
        }

        return (new Time($this->attributes['deleted_at']))->format($format);
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