<?php

namespace App\Modules\facenguoidung\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class FaceNguoiDung extends BaseEntity
{
    protected $tableName = 'face_nguoi_dung';
    protected $primaryKey = 'face_nguoi_dung_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'face_nguoi_dung_id' => 'int',
        'nguoi_dung_id' => 'int',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_nguoi_dung_id' => ['nguoi_dung_id']
    ];
    
    // Các quy tắc xác thực cụ thể cho FaceNguoiDung
    protected $validationRules = [
        'nguoi_dung_id' => [
            'rules' => 'required|integer',
            'label' => 'Người dùng'
        ],
        'duong_dan_anh' => [
            'rules' => 'required|max_length[255]',
            'label' => 'Đường dẫn ảnh'
        ],
        'status' => [
            'rules' => 'required|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'nguoi_dung_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên'
        ],
        'duong_dan_anh' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'status' => [
            'required' => '{field} không được để trống',
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của khuôn mặt người dùng
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy ID người dùng
     *
     * @return int
     */
    public function getNguoiDungId(): int
    {
        return (int)($this->attributes['nguoi_dung_id'] ?? 0);
    }
    
    /**
     * Lấy đường dẫn ảnh
     *
     * @return string
     */
    public function getDuongDanAnh(): string
    {
        return $this->attributes['duong_dan_anh'] ?? '';
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