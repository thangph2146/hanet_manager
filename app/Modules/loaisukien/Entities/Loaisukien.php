<?php

namespace App\Modules\loaisukien\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class Loaisukien extends BaseEntity
{
    protected $tableName = 'loai_su_kien';
    protected $primaryKey = 'loai_su_kien_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'loai_su_kien_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Trường duy nhất cần kiểm tra
    protected $uniqueFields = [
        'ma_loai_su_kien' => 'Mã loại sự kiện',
        'ten_loai_su_kien' => 'Tên loại sự kiện'
    ];
    
    // Các quy tắc xác thực cụ thể cho Loaisukien
    protected $validationRules = [
        'ten_loai_su_kien' => 'required|min_length[3]|max_length[100]',
        'ma_loai_su_kien' => 'permit_empty|max_length[20]',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_loai_su_kien' => [
            'required' => 'Tên loại sự kiện là bắt buộc',
            'min_length' => 'Tên loại sự kiện phải có ít nhất {param} ký tự',
            'max_length' => 'Tên loại sự kiện không được vượt quá {param} ký tự',
        ],
        'ma_loai_su_kien' => [
            'max_length' => 'Mã loại sự kiện không được vượt quá {param} ký tự',
        ],
        'status' => [
            'in_list' => 'Trạng thái không hợp lệ',
        ],
        'bin' => [
            'in_list' => 'Trạng thái thùng rác không hợp lệ',
        ],
    ];
    
    /**
     * Lấy ID
     */
    public function getId(): int
    {
        return (int)$this->attributes[$this->primaryKey];
    }
    
    /**
     * Lấy tên loại sự kiện
     */
    public function getTenLoaiSuKien(): string
    {
        return $this->attributes['ten_loai_su_kien'] ?? '';
    }
    
    /**
     * Lấy mã loại sự kiện
     */
    public function getMaLoaiSuKien(): ?string
    {
        return $this->attributes['ma_loai_su_kien'] ?? null;
    }
    
    /**
     * Kiểm tra xem loại sự kiện có đang hoạt động hay không
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? false);
    }
    
    /**
     * Thiết lập trạng thái hoạt động
     */
    public function setStatus(bool $status)
    {
        $this->attributes['status'] = $status ? 1 : 0;
        return $this;
    }
    
    /**
     * Kiểm tra xem loại sự kiện có đang trong thùng rác hay không
     */
    public function isInBin(): bool
    {
        return (bool)($this->attributes['bin'] ?? false);
    }
    
    /**
     * Thiết lập trạng thái thùng rác
     */
    public function setBinStatus(bool $binStatus)
    {
        $this->attributes['bin'] = $binStatus ? 1 : 0;
        return $this;
    }
    
    /**
     * Lấy nhãn trạng thái
     */
    public function getStatusLabel()
    {
        $status = $this->isActive();
        
        if ($status) {
            return '<span class="badge bg-success">Hoạt động</span>';
        } else {
            return '<span class="badge bg-danger">Không hoạt động</span>';
        }
    }
    
    /**
     * Lấy thời gian tạo đã được định dạng
     */
    public function getCreatedAtFormatted()
    {
        if (isset($this->attributes['created_at'])) {
            if ($this->attributes['created_at'] instanceof Time) {
                return $this->attributes['created_at']->format('d/m/Y H:i:s');
            } else {
                return (new Time($this->attributes['created_at']))->format('d/m/Y H:i:s');
            }
        }
        
        return '';
    }
    
    /**
     * Lấy thời gian cập nhật đã được định dạng
     */
    public function getUpdatedAtFormatted()
    {
        if (isset($this->attributes['updated_at']) && $this->attributes['updated_at']) {
            if ($this->attributes['updated_at'] instanceof Time) {
                return $this->attributes['updated_at']->format('d/m/Y H:i:s');
            } else {
                return (new Time($this->attributes['updated_at']))->format('d/m/Y H:i:s');
            }
        }
        
        return '';
    }
    
    /**
     * Lấy thời gian xóa đã được định dạng
     */
    public function getDeletedAtFormatted()
    {
        if (isset($this->attributes['deleted_at']) && $this->attributes['deleted_at']) {
            if ($this->attributes['deleted_at'] instanceof Time) {
                return $this->attributes['deleted_at']->format('d/m/Y H:i:s');
            } else {
                return (new Time($this->attributes['deleted_at']))->format('d/m/Y H:i:s');
            }
        }
        
        return '';
    }
    
    /**
     * Kiểm tra mã là duy nhất
     */
    public function isUniqueCode(string $code, ?int $excludeId = null): bool
    {
        if (empty($code)) {
            return true;
        }
        
        return $this->validateUniqueField('ma_loai_su_kien', $code, $excludeId);
    }
    
    /**
     * Kiểm tra tên là duy nhất
     */
    public function isUniqueName(string $name, ?int $excludeId = null): bool
    {
        if (empty($name)) {
            return true;
        }
        
        return $this->validateUniqueField('ten_loai_su_kien', $name, $excludeId);
    }
    
    /**
     * Xác thực một trường là duy nhất
     */
    protected function validateUniqueField(string $field, $value, ?int $exceptId = null): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->tableName);
        
        $builder->where($field, $value);
        $builder->where('bin', 0);
        
        if ($exceptId !== null) {
            $builder->where("{$this->primaryKey} !=", $exceptId);
        }
        
        $count = $builder->countAllResults();
        
        return $count === 0;
    }
} 