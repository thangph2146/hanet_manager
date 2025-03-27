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
        'su_kien_dien_gia_id' => 'int',
        'su_kien_id' => 'int',
        'dien_gia_id' => 'int',
        'thu_tu' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_su_kien_id' => ['su_kien_id'],
        'idx_dien_gia_id' => ['dien_gia_id']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_su_kien_dien_gia' => ['su_kien_id', 'dien_gia_id']
    ];
    
    // Các quy tắc xác thực cụ thể cho SuKienDienGia
    protected $validationRules = [
        'su_kien_dien_gia_id' => [
            'rules' => 'required|integer',
            'label' => 'ID sự kiện diễn giả'
        ],
        'su_kien_id' => [
            'rules' => 'required|integer',
            'label' => 'ID sự kiện'
        ],
        'dien_gia_id' => [
            'rules' => 'required|integer',
            'label' => 'ID diễn giả'
        ],
        'thu_tu' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Thứ tự'
        ]
    ];
    
    protected $validationMessages = [
        'su_kien_dien_gia_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên'
        ],
        'su_kien_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên'
        ],
        'dien_gia_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên'
        ],
        'thu_tu' => [
            'integer' => '{field} phải là số nguyên'
        ]
    ];
    
    /**
     * Lấy ID của sự kiện diễn giả
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes['su_kien_dien_gia_id'] ?? 0);
    }
    
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
     * Lấy tên sự kiện dựa trên su_kien_id
     *
     * @return string
     */
    public function getTenSuKien(): string
    {
        $suKienId = $this->getSuKienId();
        if (empty($suKienId)) {
            return '';
        }
        
        // Sử dụng database connection để truy vấn thông tin từ bảng su_kien
        $db = \Config\Database::connect();
        $builder = $db->table('su_kien');
        $result = $builder->select('ten_su_kien')
                         ->where('su_kien_id', $suKienId)
                         ->get()
                         ->getRow();
        
        return $result ? $result->ten_su_kien : '';
    }
    
    /**
     * Lấy tên diễn giả dựa trên dien_gia_id
     *
     * @return string
     */
    public function getTenDienGia(): string
    {
        $dienGiaId = $this->getDienGiaId();
        if (empty($dienGiaId)) {
            return '';
        }
        
        // Sử dụng database connection để truy vấn thông tin từ bảng dien_gia
        $db = \Config\Database::connect();
        $builder = $db->table('dien_gia');
        $result = $builder->select('ten_dien_gia')
                         ->where('dien_gia_id', $dienGiaId)
                         ->get()
                         ->getRow();
        
        return $result ? $result->ten_dien_gia : '';
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