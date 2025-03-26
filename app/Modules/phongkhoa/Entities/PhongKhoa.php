<?php

namespace App\Modules\phongkhoa\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class PhongKhoa extends BaseEntity
{
    protected $tableName = 'phong_khoa';
    protected $primaryKey = 'phong_khoa_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'phong_khoa_id' => 'int',
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
        'ma_phong_khoa' => 'Mã phòng khoa'
    ];
    
    // Các quy tắc xác thực cụ thể cho PhongKhoa
    protected $validationRules = [
        'ma_phong_khoa' => [
            'rules' => 'required|min_length[2]|max_length[20]|is_unique[phong_khoa.ma_phong_khoa,phong_khoa_id,{phong_khoa_id}]',
            'label' => 'Mã phòng khoa'
        ],
        'ten_phong_khoa' => [
            'rules' => 'required|min_length[2]|max_length[100]',
            'label' => 'Tên phòng khoa'
        ],
        'ghi_chu' => [
            'rules' => 'permit_empty',
            'label' => 'Ghi chú'
        ],
        'status' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ma_phong_khoa' => [
            'required' => '{field} là bắt buộc',
            'min_length' => '{field} phải có ít nhất {param} ký tự',
            'max_length' => '{field} không được vượt quá {param} ký tự',
            'is_unique' => '{field} đã tồn tại, vui lòng chọn mã khác'
        ],
        'ten_phong_khoa' => [
            'required' => '{field} là bắt buộc',
            'min_length' => '{field} phải có ít nhất {param} ký tự',
            'max_length' => '{field} không được vượt quá {param} ký tự'
        ],
        'status' => [
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của bản ghi phòng khoa
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy mã phòng khoa
     *
     * @return string
     */
    public function getMaPhongKhoa(): string
    {
        return $this->attributes['ma_phong_khoa'] ?? '';
    }
    
    /**
     * Lấy tên phòng khoa
     *
     * @return string
     */
    public function getTenPhongKhoa(): string
    {
        return $this->attributes['ten_phong_khoa'] ?? '';
    }
    
    /**
     * Lấy ghi chú
     *
     * @return string|null
     */
    public function getGhiChu(): ?string
    {
        return $this->attributes['ghi_chu'] ?? null;
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