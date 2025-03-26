<?php

namespace App\Modules\namhoc\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class NamHoc extends BaseEntity
{
    protected $tableName = 'nam_hoc';
    protected $primaryKey = 'nam_hoc_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'ngay_bat_dau',
        'ngay_ket_thuc'
    ];
    
    protected $casts = [
        'nam_hoc_id' => 'int',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
        'ngay_bat_dau' => 'date',
        'ngay_ket_thuc' => 'date'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_ten_nam_hoc' => ['ten_nam_hoc']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_ten_nam_hoc' => ['ten_nam_hoc']
    ];
    
    // Các quy tắc xác thực cụ thể cho NamHoc
    protected $validationRules = [
        'ten_nam_hoc' => [
            'rules' => 'required|max_length[50]|is_unique[nam_hoc.ten_nam_hoc,nam_hoc_id,{nam_hoc_id}]',
            'label' => 'Tên năm học'
        ],
        'ngay_bat_dau' => [
            'rules' => 'permit_empty|valid_date',
            'label' => 'Ngày bắt đầu'
        ],
        'ngay_ket_thuc' => [
            'rules' => 'permit_empty|valid_date',
            'label' => 'Ngày kết thúc'
        ],
        'status' => [
            'rules' => 'required|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ten_nam_hoc' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 50 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'ngay_bat_dau' => [
            'valid_date' => '{field} không hợp lệ'
        ],
        'ngay_ket_thuc' => [
            'valid_date' => '{field} không hợp lệ'
        ],
        'status' => [
            'required' => '{field} không được để trống',
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của năm học
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên năm học
     *
     * @return string
     */
    public function getTenNamHoc(): string
    {
        return $this->attributes['ten_nam_hoc'] ?? '';
    }
    
    /**
     * Lấy ngày bắt đầu
     *
     * @return Time|null
     */
    public function getNgayBatDau(): ?Time
    {
        if (empty($this->attributes['ngay_bat_dau'])) {
            return null;
        }

        if ($this->attributes['ngay_bat_dau'] instanceof Time) {
            return $this->attributes['ngay_bat_dau'];
        }

        return new Time($this->attributes['ngay_bat_dau']);
    }
    
    /**
     * Lấy ngày kết thúc
     *
     * @return Time|null
     */
    public function getNgayKetThuc(): ?Time
    {
        if (empty($this->attributes['ngay_ket_thuc'])) {
            return null;
        }

        if ($this->attributes['ngay_ket_thuc'] instanceof Time) {
            return $this->attributes['ngay_ket_thuc'];
        }

        return new Time($this->attributes['ngay_ket_thuc']);
    }
    
    /**
     * Lấy ngày bắt đầu dưới dạng chuỗi với định dạng cụ thể
     *
     * @param string $format
     * @return string
     */
    public function getNgayBatDauFormatted(string $format = 'd/m/Y'): string
    {
        return $this->ngay_bat_dau instanceof Time 
            ? $this->ngay_bat_dau->format($format) 
            : '';
    }
    
    /**
     * Lấy ngày kết thúc dưới dạng chuỗi với định dạng cụ thể
     *
     * @param string $format
     * @return string
     */
    public function getNgayKetThucFormatted(string $format = 'd/m/Y'): string
    {
        return $this->ngay_ket_thuc instanceof Time 
            ? $this->ngay_ket_thuc->format($format) 
            : '';
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