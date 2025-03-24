<?php

namespace App\Modules\manhinh\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class ManHinh extends BaseEntity
{
    protected $tableName = 'man_hinh';
    protected $primaryKey = 'man_hinh_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'man_hinh_id' => 'int',
        'camera_id' => 'int',
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
        'ten_man_hinh' => 'Tên màn hình'
    ];
    
    // Các quy tắc xác thực cụ thể cho ManHinh
    protected $validationRules = [
        'ten_man_hinh' => 'required|min_length[3]|max_length[255]|is_unique[man_hinh.ten_man_hinh,man_hinh_id,{man_hinh_id}]',
        'ma_man_hinh' => 'permit_empty|max_length[20]',
        'camera_id' => 'permit_empty|integer',
        'template_id' => 'permit_empty|integer',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_man_hinh' => [
            'required' => 'Tên màn hình là bắt buộc',
            'min_length' => 'Tên màn hình phải có ít nhất {param} ký tự',
            'max_length' => 'Tên màn hình không được vượt quá {param} ký tự',
            'is_unique' => 'Tên màn hình đã tồn tại, vui lòng chọn tên khác',
        ],
        'ma_man_hinh' => [
            'max_length' => 'Mã màn hình không được vượt quá {param} ký tự',
        ],
        'camera_id' => [
            'integer' => 'Camera ID phải là số nguyên',
        ],
        'template_id' => [
            'integer' => 'Template ID phải là số nguyên',
        ],
        'status' => [
            'in_list' => 'Trạng thái không hợp lệ',
        ],
        'bin' => [
            'in_list' => 'Trạng thái thùng rác không hợp lệ',
        ],
    ];
    
    /**
     * Lấy ID của màn hình
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên màn hình
     *
     * @return string
     */
    public function getTenManHinh(): string
    {
        return $this->attributes['ten_man_hinh'] ?? '';
    }
    
    /**
     * Lấy mã màn hình
     *
     * @return string|null
     */
    public function getMaManHinh(): ?string
    {
        return $this->attributes['ma_man_hinh'] ?? null;
    }
    
    /**
     * Lấy camera ID
     *
     * @return int|null
     */
    public function getCameraId(): ?int
    {
        $cameraId = $this->attributes['camera_id'] ?? null;
        return $cameraId !== null ? (int)$cameraId : null;
    }
    
    /**
     * Lấy template ID
     *
     * @return int|null
     */
    public function getTemplateId(): ?int
    {
        $templateId = $this->attributes['template_id'] ?? null;
        return $templateId !== null ? (int)$templateId : null;
    }
    
    /**
     * Kiểm tra màn hình có đang hoạt động không
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? false);
    }
    
    /**
     * Đặt trạng thái hoạt động cho màn hình
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
     * Kiểm tra màn hình có đang trong thùng rác không
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
     * Lấy thông tin camera
     * 
     * @return string
     */
    public function getCameraInfo()
    {
        if (empty($this->attributes['ten_camera'])) {
            return '<span class="text-muted">Chưa gán camera</span>';
        }
        
        return '<span class="badge bg-info">' . esc($this->attributes['ten_camera']) . '</span>';
    }
    
    /**
     * Lấy thông tin template
     * 
     * @return string
     */
    public function getTemplateInfo()
    {
        if (empty($this->attributes['ten_template'])) {
            return '<span class="text-muted">Chưa gán template</span>';
        }
        
        return '<span class="badge bg-secondary">' . esc($this->attributes['ten_template']) . '</span>';
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
            return '';
        }
        
        if (is_string($this->attributes['created_at'])) {
            $time = Time::parse($this->attributes['created_at']);
        } else {
            $time = $this->attributes['created_at'];
        }
        
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
            return '';
        }
        
        if (is_string($this->attributes['updated_at'])) {
            $time = Time::parse($this->attributes['updated_at']);
        } else {
            $time = $this->attributes['updated_at'];
        }
        
        return $time->format('d/m/Y H:i:s');
    }
    
    /**
     * Lấy ngày xóa đã định dạng
     * 
     * @return string
     */
    public function getDeletedAtFormatted()
    {
        if (empty($this->attributes['deleted_at'])) {
            return '';
        }
        
        if (is_string($this->attributes['deleted_at'])) {
            $time = Time::parse($this->attributes['deleted_at']);
        } else {
            $time = $this->attributes['deleted_at'];
        }
        
        return $time->format('d/m/Y H:i:s');
    }
    
    /**
     * Lấy các quy tắc xác thực
     * 
     * @return array
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }
    
    /**
     * Lấy thông báo xác thực
     * 
     * @return array
     */
    public function getValidationMessages()
    {
        return $this->validationMessages;
    }
    
    /**
     * Kiểm tra mã màn hình có là duy nhất không
     *
     * @param string $code Mã cần kiểm tra
     * @param int|null $excludeId ID cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isUniqueCode(string $code, ?int $excludeId = null): bool
    {
        return $this->validateUniqueField('ma_man_hinh', $code, $excludeId);
    }
    
    /**
     * Kiểm tra tên màn hình có là duy nhất không
     *
     * @param string $name Tên cần kiểm tra
     * @param int|null $excludeId ID cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isUniqueName(string $name, ?int $excludeId = null): bool
    {
        return $this->validateUniqueField('ten_man_hinh', $name, $excludeId);
    }
    
    /**
     * Phương thức trợ giúp để kiểm tra tính duy nhất của một trường
     *
     * @param string $field Tên trường cần kiểm tra
     * @param mixed $value Giá trị cần kiểm tra
     * @param int|null $exceptId ID cần loại trừ
     * @return bool
     */
    protected function validateUniqueField(string $field, $value, ?int $exceptId = null): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->tableName);
        
        $builder->where($field, $value);
        
        if ($exceptId !== null) {
            $builder->where("{$this->primaryKey} !=", $exceptId);
        }
        
        // Trả về true nếu không tìm thấy bản ghi nào (tức là giá trị là duy nhất)
        return $builder->countAllResults() === 0;
    }
} 