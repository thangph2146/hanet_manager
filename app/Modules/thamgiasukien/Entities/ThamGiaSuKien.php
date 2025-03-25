<?php

namespace App\Modules\thamgiasukien\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class ThamGiaSuKien extends BaseEntity
{
    protected $tableName = 'tham_gia_su_kien';
    protected $primaryKey = 'tham_gia_su_kien_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'thoi_gian_diem_danh'
    ];
    
    protected $casts = [
        'tham_gia_su_kien_id' => 'int',
        'nguoi_dung_id' => 'int',
        'su_kien_id' => 'int',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
        'thoi_gian_diem_danh' => 'timestamp'
    ];
    
    // Định nghĩa các trường là khóa ngoại
    protected $foreignKeys = [
        'nguoi_dung_id' => 'nguoi_dung.nguoi_dung_id',
        'su_kien_id' => 'su_kien.su_kien_id'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_nguoi_dung_id' => ['nguoi_dung_id'],
        'idx_su_kien_id' => ['su_kien_id'],
        'idx_thoi_gian_diem_danh' => ['thoi_gian_diem_danh'],
        'idx_status_nguoidung_sukien' => ['status', 'nguoi_dung_id', 'su_kien_id']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_nguoi_dung_event' => ['nguoi_dung_id', 'su_kien_id']
    ];
    
    // Các quy tắc xác thực cụ thể cho ThamGiaSuKien
    protected $validationRules = [
        'nguoi_dung_id' => [
            'rules' => 'required|integer|greater_than[0]',
            'label' => 'ID người dùng'
        ],
        'su_kien_id' => [
            'rules' => 'required|integer|greater_than[0]',
            'label' => 'ID sự kiện'
        ],
        'thoi_gian_diem_danh' => [
            'rules' => 'permit_empty|valid_date',
            'label' => 'Thời gian điểm danh'
        ],
        'phuong_thuc_diem_danh' => [
            'rules' => 'required|in_list[qr_code,face_id,manual]',
            'label' => 'Phương thức điểm danh'
        ],
        'ghi_chu' => [
            'rules' => 'permit_empty|string',
            'label' => 'Ghi chú'
        ],
        'status' => [
            'rules' => 'required|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'nguoi_dung_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên',
            'greater_than' => '{field} phải lớn hơn 0'
        ],
        'su_kien_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên',
            'greater_than' => '{field} phải lớn hơn 0'
        ],
        'thoi_gian_diem_danh' => [
            'valid_date' => '{field} không hợp lệ.'
        ],
        'phuong_thuc_diem_danh' => [
            'required' => '{field} là bắt buộc',
            'in_list' => '{field} không hợp lệ'
        ],
        'status' => [
            'required' => '{field} không được để trống',
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của bản ghi tham gia sự kiện
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
     * Lấy ID sự kiện
     *
     * @return int
     */
    public function getSuKienId(): int
    {
        return (int)($this->attributes['su_kien_id'] ?? 0);
    }
    
    /**
     * Lấy ngày kết thúc dưới dạng chuỗi với định dạng cụ thể
     *
     * @param string $format
     * @return string
     */
    public function getThoiGianDiemDanhFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        return $this->thoi_gian_diem_danh instanceof Time 
            ? $this->thoi_gian_diem_danh->format($format) 
            : '';
    }
    
    /**
     * Lấy thời gian điểm danh
     *
     * @return Time|null
     */
    public function getThoiGianDiemDanh(): ?Time
    {
        if (empty($this->attributes['thoi_gian_diem_danh'])) {
            return null;
        }

        if ($this->attributes['thoi_gian_diem_danh'] instanceof Time) {
            return $this->attributes['thoi_gian_diem_danh'];
        }

        return new Time($this->attributes['thoi_gian_diem_danh']);
    }
    
    /**
     * Lấy phương thức điểm danh
     *
     * @return string
     */
    public function getPhuongThucDiemDanh(): string
    {
        return $this->attributes['phuong_thuc_diem_danh'] ?? 'manual';
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
     * Lấy nhãn phương thức điểm danh
     *
     * @return string HTML với badge phương thức điểm danh
     */
    public function getPhuongThucDiemDanhLabel(): string
    {
        switch ($this->phuong_thuc_diem_danh) {
            case 'qr_code':
                return '<span class="badge bg-primary">QR Code</span>';
            case 'face_id':
                return '<span class="badge bg-info">Face ID</span>';
            default:
                return '<span class="badge bg-secondary">Thủ công</span>';
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
    
    /**
     * Kiểm tra xem người dùng đã tham gia sự kiện chưa
     *
     * @param int $nguoiDungId
     * @param int $suKienId
     * @return bool
     */
    public function isUserJoinedEvent(int $nguoiDungId, int $suKienId): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->tableName);
        
        return $builder->where([
            'nguoi_dung_id' => $nguoiDungId,
            'su_kien_id' => $suKienId,
            'deleted_at IS NULL' => null
        ])->countAllResults() > 0;
    }
} 