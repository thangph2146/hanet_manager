<?php

namespace App\Modules\diengia\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class DienGia extends BaseEntity
{
    protected $tableName = 'dien_gia';
    protected $primaryKey = 'dien_gia_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'dien_gia_id' => 'int',
        'status' => 'boolean',
        'mang_xa_hoi' => 'json',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_ten_dien_gia' => ['ten_dien_gia'],
        'idx_to_chuc' => ['to_chuc'],
        'idx_email' => ['email'],
        'idx_status' => ['status']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_email' => ['email']
    ];
    
    // Các quy tắc xác thực cụ thể cho DienGia
    protected $validationRules = [
        'ten_dien_gia' => [
            'rules' => 'required|max_length[255]',
            'label' => 'Tên diễn giả'
        ],
        'chuc_danh' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Chức danh'
        ],
        'to_chuc' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Tổ chức'
        ],
        'gioi_thieu' => [
            'rules' => 'permit_empty',
            'label' => 'Giới thiệu'
        ],
        'email' => [
            'rules' => 'permit_empty|valid_email|max_length[100]|is_unique[dien_gia.email,dien_gia_id,{dien_gia_id}]',
            'label' => 'Email'
        ],
        'dien_thoai' => [
            'rules' => 'permit_empty|max_length[20]',
            'label' => 'Số điện thoại'
        ],
        'website' => [
            'rules' => 'permit_empty|valid_url|max_length[255]',
            'label' => 'Website'
        ],
        'chuyen_mon' => [
            'rules' => 'permit_empty',
            'label' => 'Chuyên môn'
        ],
        'thanh_tuu' => [
            'rules' => 'permit_empty',
            'label' => 'Thành tựu'
        ],
        'mang_xa_hoi' => [
            'rules' => 'permit_empty',
            'label' => 'Mạng xã hội'
        ],
        'status' => [
            'rules' => 'required|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ten_dien_gia' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'chuc_danh' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'to_chuc' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'email' => [
            'valid_email' => '{field} không hợp lệ',
            'max_length' => '{field} không được vượt quá 100 ký tự',
            'is_unique' => '{field} đã tồn tại trong hệ thống'
        ],
        'dien_thoai' => [
            'max_length' => '{field} không được vượt quá 20 ký tự'
        ],
        'website' => [
            'valid_url' => '{field} không hợp lệ',
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'status' => [
            'required' => '{field} là bắt buộc',
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của diễn giả
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên diễn giả
     *
     * @return string
     */
    public function getTenDienGia(): string
    {
        return $this->attributes['ten_dien_gia'] ?? '';
    }
    
    /**
     * Lấy chức danh
     *
     * @return string|null
     */
    public function getChucDanh(): ?string
    {
        return $this->attributes['chuc_danh'] ?? null;
    }
    
    /**
     * Lấy tổ chức
     *
     * @return string|null
     */
    public function getToChuc(): ?string
    {
        return $this->attributes['to_chuc'] ?? null;
    }
    
    /**
     * Lấy giới thiệu
     *
     * @return string|null
     */
    public function getGioiThieu(): ?string
    {
        return $this->attributes['gioi_thieu'] ?? null;
    }
    
    /**
     * Lấy ảnh đại diện
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->attributes['avatar'] ?? null;
    }
    
    /**
     * Lấy email
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->attributes['email'] ?? null;
    }
    
    /**
     * Lấy số điện thoại
     *
     * @return string|null
     */
    public function getDienThoai(): ?string
    {
        return $this->attributes['dien_thoai'] ?? null;
    }
    
    /**
     * Lấy website
     *
     * @return string|null
     */
    public function getWebsite(): ?string
    {
        return $this->attributes['website'] ?? null;
    }
    
    /**
     * Lấy chuyên môn
     *
     * @return string|null
     */
    public function getChuyenMon(): ?string
    {
        return $this->attributes['chuyen_mon'] ?? null;
    }
    
    /**
     * Lấy thành tựu
     *
     * @return string|null
     */
    public function getThanhTuu(): ?string
    {
        return $this->attributes['thanh_tuu'] ?? null;
    }
    
    /**
     * Lấy thông tin mạng xã hội
     *
     * @return array|null
     */
    public function getMangXaHoi(): ?array
    {
        $mangXaHoi = $this->attributes['mang_xa_hoi'] ?? null;
        
        if (is_string($mangXaHoi)) {
            return json_decode($mangXaHoi, true);
        }
        
        return $mangXaHoi;
    }
    
    /**
     * Lấy trạng thái
     *
     * @return bool
     */
    public function getStatus(): bool
    {
        return (bool)($this->attributes['status'] ?? 1);
    }
    
    /**
     * Lấy số sự kiện tham gia
     *
     * @return int
     */
    public function getSoSuKienThamGia(): int
    {
        return (int)($this->attributes['so_su_kien_tham_gia'] ?? 0);
    }
    
    /**
     * Lấy ngày tạo
     *
     * @return Time|null
     */
    public function getCreatedAt(): ?Time
    {
        $created = $this->attributes['created_at'] ?? null;
        
        if (empty($created)) {
            return null;
        }
        
        if ($created instanceof Time) {
            return $created;
        }
        
        return new Time($created);
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
     * Lấy ngày tạo đã định dạng
     *
     * @return string|null
     */
    public function getCreatedAtFormatted(): ?string
    {
        $createdAt = $this->getCreatedAt();
        return $createdAt ? $createdAt->format('d/m/Y H:i:s') : null;
    }
    
    /**
     * Lấy ngày cập nhật đã định dạng
     *
     * @return string|null
     */
    public function getUpdatedAtFormatted(): ?string
    {
        $updatedAt = $this->getUpdatedAt();
        return $updatedAt ? $updatedAt->format('d/m/Y H:i:s') : null;
    }
    
    /**
     * Lấy ngày xóa đã định dạng
     *
     * @return string|null
     */
    public function getDeletedAtFormatted(): ?string
    {
        $deletedAt = $this->getDeletedAt();
        return $deletedAt ? $deletedAt->format('d/m/Y H:i:s') : null;
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