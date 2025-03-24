<?php

namespace App\Modules\template\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class Template extends BaseEntity
{
    protected $tableName = 'template';
    protected $primaryKey = 'template_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'template_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Trường duy nhất cần kiểm tra
    protected $uniqueFields = [
        'ma_template' => 'Mã template',
        'ten_template' => 'Tên template'
    ];
    
    // Các quy tắc xác thực cụ thể cho Template
    protected $validationRules = [
        'ten_template' => 'required|min_length[3]|max_length[200]',
        'ma_template' => 'required|max_length[20]',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_template' => [
            'required' => 'Tên template là bắt buộc',
            'min_length' => 'Tên template phải có ít nhất {param} ký tự',
            'max_length' => 'Tên template không được vượt quá {param} ký tự',
        ],
        'ma_template' => [
            'required' => 'Mã template là bắt buộc',
            'max_length' => 'Mã template không được vượt quá {param} ký tự',
        ],
    ];
    
    /**
     * Lấy ID của template
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên template
     *
     * @return string
     */
    public function getTenTemplate(): string
    {
        return $this->attributes['ten_template'] ?? '';
    }
    
    /**
     * Lấy mã template
     *
     * @return string|null
     */
    public function getMaTemplate(): ?string
    {
        return $this->attributes['ma_template'] ?? null;
    }
    
    /**
     * Kiểm tra template có đang hoạt động không
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? false);
    }
    
    /**
     * Đặt trạng thái hoạt động cho template
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
     * Kiểm tra template có đang trong thùng rác không
     *
     * @return bool
     */
    public function isInBin(): bool
    {
        return (bool)($this->attributes['bin'] ?? false);
    }
    
    /**
     * Đặt trạng thái thùng rác
     *
     * @param bool $binStatus
     * @return $this
     */
    public function setBinStatus(bool $binStatus)
    {
        $this->attributes['bin'] = (int)$binStatus;
        return $this;
    }
    
    /**
     * Lấy nhãn trạng thái hiển thị
     *
     * @return string HTML với badge status
     */
    public function getStatusLabel()
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
    public function getCreatedAtFormatted()
    {
        if (empty($this->attributes['created_at'])) {
            return '<span class="text-muted fst-italic">Chưa cập nhật</span>';
        }
        
        $time = $this->attributes['created_at'] instanceof Time ? $this->attributes['created_at'] : new Time($this->attributes['created_at']);
        return $time->format('d/m/Y H:i:s');
    }
    
    /**
     * Lấy ngày cập nhật đã định dạng
     * 
     * @return string
     */
    public function getUpdatedAtFormatted()
    {
        if (empty($this->attributes['updated_at'])) {
            return '<span class="text-muted fst-italic">Chưa cập nhật</span>';
        }
        
        $time = $this->attributes['updated_at'] instanceof Time ? $this->attributes['updated_at'] : new Time($this->attributes['updated_at']);
        return $time->format('d/m/Y H:i:s');
    }
    
    /**
     * Lấy ngày xóa đã định dạng
     *
     * @return string
     */
    public function getDeletedAtFormatted()
    {
        // Kiểm tra nếu deleted_at là null, chuỗi rỗng hoặc định dạng ngày mặc định
        if (!isset($this->attributes['deleted_at']) || 
            empty($this->attributes['deleted_at']) || 
            $this->attributes['deleted_at'] == '0000-00-00 00:00:00') {
            return '';
        }
        
        try {
            // Chuyển đổi sang đối tượng Time
            $time = $this->attributes['deleted_at'] instanceof Time ? 
                $this->attributes['deleted_at'] : 
                new Time($this->attributes['deleted_at']);
                
            return $time->format('d/m/Y H:i:s');
        } catch (\Exception $e) {
            // Trả về chuỗi rỗng nếu có lỗi
            return '';
        }
    }
    
    /**
     * Kiểm tra xem mã template có phải là duy nhất không
     *
     * @param string $code Mã template cần kiểm tra
     * @param int|null $excludeId ID template cần loại trừ (khi cập nhật)
     * @return bool
     */
    public function isUniqueCode(string $code, ?int $excludeId = null): bool
    {
        $model = model('App\Modules\template\Models\TemplateModel');
        return !$model->isCodeExists($code, $excludeId);
    }
    
    /**
     * Kiểm tra xem tên template có phải là duy nhất không
     *
     * @param string $name Tên template cần kiểm tra
     * @param int|null $excludeId ID template cần loại trừ (khi cập nhật)
     * @return bool
     */
    public function isUniqueName(string $name, ?int $excludeId = null): bool
    {
        $model = model('App\Modules\template\Models\TemplateModel');
        return !$model->isNameExists($name, $excludeId);
    }
    
    /**
     * Phương thức chung để kiểm tra trường duy nhất
     * 
     * @param string $field Tên trường cần kiểm tra
     * @param mixed $value Giá trị cần kiểm tra
     * @param int|null $exceptId ID cần loại trừ (khi cập nhật)
     * @return bool
     */
    protected function validateUniqueField(string $field, $value, ?int $exceptId = null): bool
    {
        if ($field === 'ma_template') {
            return $this->isUniqueCode($value, $exceptId);
        } elseif ($field === 'ten_template') {
            return $this->isUniqueName($value, $exceptId);
        }
        
        return true;
    }
} 