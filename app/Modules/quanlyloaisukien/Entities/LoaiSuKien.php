<?php

namespace App\Modules\quanlyloaisukien\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;
use App\Modules\sukien\Entities\SuKien;
use App\Modules\dangkysukien\Entities\DangKySuKien;
use App\Modules\checkinsukien\Entities\CheckInSuKien;

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
        'ten_loai_su_kien' => 'string',
        'ma_loai_su_kien' => 'string',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    protected $jsonFields = [];
    
    // Các quy tắc xác thực cho LoaiSuKien
    protected $validationRules = [
        'ten_loai_su_kien' => [
            'rules' => 'required|string|max_length[100]',
            'label' => 'Tên loại sự kiện'
        ],
        'ma_loai_su_kien' => [
            'rules' => 'required|string|max_length[20]',
            'label' => 'Mã loại sự kiện'
        ],
        'status' => [
            'rules' => 'required|integer|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ten_loai_su_kien' => [
            'required' => '{field} là bắt buộc',
            'string' => '{field} phải là chuỗi',
            'max_length' => '{field} không được vượt quá {param} ký tự'
        ],
        'ma_loai_su_kien' => [
            'required' => '{field} là bắt buộc',
            'string' => '{field} phải là chuỗi',
            'max_length' => '{field} không được vượt quá {param} ký tự'
        ],
        'status' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên',
            'in_list' => '{field} phải có giá trị hợp lệ'
        ]
    ];

    /**
     * Lấy ID
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
     * @return string|null
     */
    public function getTenLoaiSuKien(): ?string
    {
        return $this->attributes['ten_loai_su_kien'] ?? null;
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
     * Lấy trạng thái
     *
     * @return int
     */
    public function getStatus(): int
    {
        return (int)($this->attributes['status'] ?? 0);
    }
   
    /**
     * Lấy ngày tạo
     *
     * @return Time|null
     */
    public function getCreatedAt(): ?Time
    {
        if (empty($this->attributes['created_at'])) {
            return null;
        }
        
        return $this->attributes['created_at'] instanceof Time 
            ? $this->attributes['created_at'] 
            : new Time($this->attributes['created_at']);
    }
    
    /**
     * Lấy ngày cập nhật
     *
     * @return Time|null
     */
    public function getUpdatedAt(): ?Time
    {
        if (empty($this->attributes['updated_at'])) {
            return null;
        }
        
        return $this->attributes['updated_at'] instanceof Time 
            ? $this->attributes['updated_at'] 
            : new Time($this->attributes['updated_at']);
    }
    
    /**
     * Lấy ngày xóa
     *
     * @return Time|null
     */
    public function getDeletedAt(): ?Time
    {
        if (empty($this->attributes['deleted_at'])) {
            return null;
        }
        
        return $this->attributes['deleted_at'] instanceof Time 
            ? $this->attributes['deleted_at'] 
            : new Time($this->attributes['deleted_at']);
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
     * Lấy trạng thái dưới dạng văn bản
     *
     * @return string
     */
    public function getStatusText(): string
    {
        return (int)($this->attributes['status'] ?? 0) === 1 ? 'Hoạt động' : 'Không hoạt động';
    }
    
    /**
     * Lấy HTML hiển thị trạng thái
     *
     * @return string
     */
    public function getStatusHtml(): string
    {
        if ((int)($this->attributes['status'] ?? 0) === 1) {
            return '<span class="badge badge-success">Hoạt động</span>';
        }
        return '<span class="badge badge-danger">Không hoạt động</span>';
    }
    
    /**
     * Lấy ngày tạo dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getCreatedAtFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $createdAt = $this->getCreatedAt();
        if (empty($createdAt)) {
            return '';
        }
        
        return $createdAt->format($format);
    }
    
    /**
     * Lấy ngày cập nhật dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getUpdatedAtFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $updatedAt = $this->getUpdatedAt();
        if (empty($updatedAt)) {
            return '';
        }
        
        return $updatedAt->format($format);
    }
    
    /**
     * Lấy ngày xóa dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getDeletedAtFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $deletedAt = $this->getDeletedAt();
        if (empty($deletedAt)) {
            return '';
        }
        
        return $deletedAt->format($format);
    }
    
    /**
     * Lấy tên trường chính
     *
     * @return string
     */
    public function getPrimaryKeyField(): string
    {
        return $this->primaryKey;
    }
    
    /**
     * Lấy tên bảng
     *
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
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