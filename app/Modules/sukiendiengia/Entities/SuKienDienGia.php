<?php

namespace App\Modules\sukiendiengia\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class SuKienDienGia extends BaseEntity
{
    protected $tableName = 'su_kien_dien_gia';
    protected $primaryKey = ['su_kien_id', 'dien_gia_id'];
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'su_kien_id' => 'int',
        'dien_gia_id' => 'int',
        'thu_tu' => 'int'
    ];
    
    // Định nghĩa các trường là khóa ngoại
    protected $foreignKeys = [
        'su_kien_id' => 'su_kien.su_kien_id',
        'dien_gia_id' => 'dien_gia.dien_gia_id'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_dien_gia_id' => ['dien_gia_id'],
        'idx_su_kien_id' => ['su_kien_id']
    ];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Các quy tắc xác thực cụ thể cho SuKienDienGia
    protected $validationRules = [
        'su_kien_id' => 'required',
        'dien_gia_id' => 'required',
        'thu_tu' => 'permit_empty|integer'
    ];
    
    protected $validationMessages = [
        'su_kien_id' => [
            'required' => 'ID sự kiện là bắt buộc',
        ],
        'dien_gia_id' => [
            'required' => 'ID diễn giả là bắt buộc',
        ],
        'thu_tu' => [
            'integer' => 'Thứ tự phải là số nguyên'
        ]
    ];
    
    /**
     * Lấy ID sự kiện
     *
     * @return int
     */
    public function getSuKienId(): int
    {
        return (int)($this->attributes['su_kien_id'] ?? 0);
    }
    
    /**
     * Lấy ID diễn giả
     *
     * @return int
     */
    public function getDienGiaId(): int
    {
        return (int)($this->attributes['dien_gia_id'] ?? 0);
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
     * Đặt thứ tự
     *
     * @param int $thuTu
     * @return $this
     */
    public function setThuTu(int $thuTu)
    {
        $this->attributes['thu_tu'] = $thuTu;
        return $this;
    }
    
    /**
     * Kiểm tra xem đối tượng đã bị xóa chưa
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return isset($this->attributes['deleted_at']) && !empty($this->attributes['deleted_at']);
    }
    
    /**
     * Lấy thời gian tạo được định dạng
     *
     * @param string $format Định dạng ngày tháng
     * @return string
     */
    public function getCreatedAtFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        if (empty($this->attributes['created_at'])) {
            return '<span class="text-muted fst-italic">Chưa cập nhật</span>';
        }
        
        if ($this->attributes['created_at'] instanceof Time) {
            return $this->attributes['created_at']->format($format);
        }
        
        try {
            $time = new Time($this->attributes['created_at']);
            return $time->format($format);
        } catch (\Exception $e) {
            return '<span class="text-muted fst-italic">Lỗi định dạng</span>';
        }
    }
    
    /**
     * Lấy thời gian cập nhật được định dạng
     *
     * @param string $format Định dạng ngày tháng
     * @return string
     */
    public function getUpdatedAtFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        if (empty($this->attributes['updated_at'])) {
            return '<span class="text-muted fst-italic">Chưa cập nhật</span>';
        }
        
        if ($this->attributes['updated_at'] instanceof Time) {
            return $this->attributes['updated_at']->format($format);
        }
        
        try {
            $time = new Time($this->attributes['updated_at']);
            return $time->format($format);
        } catch (\Exception $e) {
            return '<span class="text-muted fst-italic">Lỗi định dạng</span>';
        }
    }
    
    /**
     * Lấy thời gian xóa được định dạng
     *
     * @param string $format Định dạng ngày tháng
     * @return string
     */
    public function getDeletedAtFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        if (empty($this->attributes['deleted_at'])) {
            return '<span class="text-muted fst-italic">Chưa xóa</span>';
        }
        
        if ($this->attributes['deleted_at'] instanceof Time) {
            return $this->attributes['deleted_at']->format($format);
        }
        
        try {
            $time = new Time($this->attributes['deleted_at']);
            return $time->format($format);
        } catch (\Exception $e) {
            return '<span class="text-muted fst-italic">Lỗi định dạng</span>';
        }
    }
    
    /**
     * Lấy quy tắc xác thực
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->validationRules;
    }
    
    /**
     * Lấy thông báo xác thực
     *
     * @return array
     */
    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }
    
    /**
     * Cập nhật thuộc tính từ một mảng dữ liệu
     *
     * @param array $data
     * @return $this
     */
    public function setAttributes(array $data)
    {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->attributes) || in_array($key, array_keys($this->casts))) {
                $this->attributes[$key] = $value;
            }
        }
        
        return $this;
    }
}