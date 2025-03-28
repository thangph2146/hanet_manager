<?php

namespace App\Modules\checkinsukien\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;
use App\Modules\sukien\Entities\SuKien;
use App\Modules\dangkysukien\Entities\DangKySuKien;

class CheckInSuKien extends BaseEntity
{
    protected $tableName = 'checkin_sukien';
    protected $primaryKey = 'checkin_sukien_id';
    
    protected $dates = [
        'thoi_gian_check_in',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'checkin_sukien_id' => 'int',
        'sukien_id' => 'int',
        'dangky_sukien_id' => 'int',
        'face_match_score' => 'float',
        'face_verified' => 'boolean',
        'status' => 'int',
        'thong_tin_bo_sung' => 'json',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    protected $jsonFields = ['thong_tin_bo_sung'];
    
    // Các quy tắc xác thực cho CheckInSuKien
    protected $validationRules = [
        'sukien_id' => [
            'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id]',
            'label' => 'ID sự kiện'
        ],
        'email' => [
            'rules' => 'required|valid_email',
            'label' => 'Email'
        ],
        'ho_ten' => [
            'rules' => 'required|min_length[3]|max_length[255]',
            'label' => 'Họ tên'
        ],
        'dangky_sukien_id' => [
            'rules' => 'permit_empty|integer|is_not_unique[dangky_sukien.dangky_sukien_id,dangky_sukien.deleted_at,null]',
            'label' => 'ID đăng ký'
        ],
        'thoi_gian_check_in' => [
            'rules' => 'required|valid_date',
            'label' => 'Thời gian check-in'
        ],
        'checkin_type' => [
            'rules' => 'required|in_list[face_id,manual,qr_code,online]',
            'label' => 'Loại check-in'
        ],
        'face_match_score' => [
            'rules' => 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[1]',
            'label' => 'Điểm số khớp khuôn mặt'
        ],
        'status' => [
            'rules' => 'permit_empty|integer',
            'label' => 'Trạng thái'
        ],
        'hinh_thuc_tham_gia' => [
            'rules' => 'required|in_list[offline,online]',
            'label' => 'Hình thức tham gia'
        ]
    ];
    
    protected $validationMessages = [
        'sukien_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên',
            'is_not_unique' => '{field} không tồn tại trong hệ thống'
        ],
        'email' => [
            'required' => '{field} là bắt buộc',
            'valid_email' => '{field} không hợp lệ'
        ],
        'ho_ten' => [
            'required' => '{field} là bắt buộc',
            'min_length' => '{field} phải có ít nhất {param} ký tự',
            'max_length' => '{field} không được vượt quá {param} ký tự'
        ],
        'dangky_sukien_id' => [
            'integer' => '{field} phải là số nguyên',
            'is_not_unique' => '{field} không tồn tại trong hệ thống'
        ],
        'thoi_gian_check_in' => [
            'required' => '{field} là bắt buộc',
            'valid_date' => '{field} phải là ngày hợp lệ'
        ],
        'checkin_type' => [
            'required' => '{field} là bắt buộc',
            'in_list' => '{field} phải là một trong các giá trị: nhận diện khuôn mặt, thủ công, mã QR, trực tuyến'
        ],
        'face_match_score' => [
            'numeric' => '{field} phải là số',
            'greater_than_equal_to' => '{field} phải lớn hơn hoặc bằng {param}',
            'less_than_equal_to' => '{field} phải nhỏ hơn hoặc bằng {param}'
        ],
        'status' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'hinh_thuc_tham_gia' => [
            'required' => '{field} là bắt buộc',
            'in_list' => '{field} phải là một trong các giá trị: trực tiếp, trực tuyến'
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
     * Lấy ID sự kiện
     *
     * @return int
     */
    public function getSuKienId(): int
    {
        return (int)($this->attributes['sukien_id'] ?? 0);
    }
    
    /**
     * Lấy email người check-in
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->attributes['email'] ?? null;
    }
    
    /**
     * Lấy họ tên người check-in
     *
     * @return string|null
     */
    public function getHoTen(): ?string
    {
        return $this->attributes['ho_ten'] ?? null;
    }
    
    /**
     * Lấy ID đăng ký sự kiện
     *
     * @return int|null
     */
    public function getDangKySuKienId(): ?int
    {
        return isset($this->attributes['dangky_sukien_id']) ? (int)$this->attributes['dangky_sukien_id'] : null;
    }
    
    /**
     * Lấy thời gian check-in
     *
     * @return Time|null
     */
    public function getThoiGianCheckIn(): ?Time
    {
        if (empty($this->attributes['thoi_gian_check_in'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_check_in'] instanceof Time 
            ? $this->attributes['thoi_gian_check_in'] 
            : new Time($this->attributes['thoi_gian_check_in']);
    }
    
    /**
     * Lấy loại check-in
     *
     * @return string|null
     */
    public function getCheckinType(): ?string
    {
        return $this->attributes['checkin_type'] ?? null;
    }
    
    /**
     * Lấy đường dẫn ảnh khuôn mặt
     *
     * @return string|null
     */
    public function getFaceImagePath(): ?string
    {
        return $this->attributes['face_image_path'] ?? null;
    }
    
    /**
     * Lấy điểm số khớp khuôn mặt
     *
     * @return float|null
     */
    public function getFaceMatchScore(): ?float
    {
        return isset($this->attributes['face_match_score']) ? (float)$this->attributes['face_match_score'] : null;
    }
    
    /**
     * Kiểm tra xác thực khuôn mặt
     *
     * @return bool
     */
    public function isFaceVerified(): bool
    {
        return (bool)($this->attributes['face_verified'] ?? false);
    }
    
    /**
     * Lấy mã xác nhận
     *
     * @return string|null
     */
    public function getMaXacNhan(): ?string
    {
        return $this->attributes['ma_xac_nhan'] ?? null;
    }
    
    /**
     * Lấy trạng thái check-in
     *
     * @return int
     */
    public function getStatus(): int
    {
        return (int)($this->attributes['status'] ?? 1);
    }
    
    /**
     * Lấy dữ liệu vị trí
     *
     * @return string|null
     */
    public function getLocationData(): ?string
    {
        return $this->attributes['location_data'] ?? null;
    }
    
    /**
     * Lấy thông tin thiết bị
     *
     * @return string|null
     */
    public function getDeviceInfo(): ?string
    {
        return $this->attributes['device_info'] ?? null;
    }
    
    /**
     * Lấy hình thức tham gia
     *
     * @return string
     */
    public function getHinhThucThamGia(): string
    {
        return $this->attributes['hinh_thuc_tham_gia'] ?? 'offline';
    }
    
    /**
     * Lấy địa chỉ IP
     *
     * @return string|null
     */
    public function getIpAddress(): ?string
    {
        return $this->attributes['ip_address'] ?? null;
    }
    
    /**
     * Lấy thông tin bổ sung
     *
     * @return array|null
     */
    public function getThongTinBoSung(): ?array
    {
        $thongTin = $this->attributes['thong_tin_bo_sung'] ?? null;
        
        if (is_string($thongTin)) {
            return json_decode($thongTin, true);
        }
        
        return $thongTin;
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
     * Lấy thời gian check-in đã định dạng
     *
     * @param string $format Định dạng thời gian
     * @return string|null
     */
    public function getThoiGianCheckInFormatted(string $format = 'd/m/Y H:i'): ?string
    {
        $time = $this->getThoiGianCheckIn();
        return $time ? $time->format($format) : null;
    }
    
    /**
     * Lấy tên trạng thái check-in để hiển thị
     *
     * @return string
     */
    public function getStatusText(): string
    {
        $status = $this->getStatus();
        
        switch ($status) {
            case 0:
                return 'Vô hiệu';
            case 1:
                return 'Hoạt động';
            case 2:
                return 'Đang xử lý';
            default:
                return 'Không xác định';
        }
    }
    
    /**
     * Lấy tên loại check-in để hiển thị
     *
     * @return string
     */
    public function getCheckinTypeText(): string
    {
        $type = $this->getCheckinType();
        
        switch ($type) {
            case 'face_id':
                return 'Nhận diện khuôn mặt';
            case 'manual':
                return 'Thủ công';
            case 'qr_code':
                return 'Mã QR';
            case 'online':
                return 'Trực tuyến';
            default:
                return 'Không xác định';
        }
    }
    
    /**
     * Lấy tên hình thức tham gia để hiển thị
     *
     * @return string
     */
    public function getHinhThucThamGiaText(): string
    {
        $hinhThuc = $this->getHinhThucThamGia();
        
        switch ($hinhThuc) {
            case 'offline':
                return 'Trực tiếp';
            case 'online':
                return 'Trực tuyến';
            default:
                return 'Không xác định';
        }
    }
    
    /**
     * Lấy thông tin sự kiện
     *
     * @return SuKien|null
     */
    public function getSuKien(): ?SuKien
    {
        $suKienId = $this->getSuKienId();
        
        if ($suKienId <= 0) {
            return null;
        }
        
        $suKienModel = new \App\Modules\sukien\Models\SuKienModel();
        return $suKienModel->find($suKienId);
    }
    
    /**
     * Lấy thông tin đăng ký sự kiện
     *
     * @return DangKySuKien|null
     */
    public function getDangKySuKien(): ?DangKySuKien
    {
        $dangKySuKienId = $this->getDangKySuKienId();
        
        if ($dangKySuKienId === null || $dangKySuKienId <= 0) {
            return null;
        }
        
        $dangKySuKienModel = new \App\Modules\dangkysukien\Models\DangKySuKienModel();
        return $dangKySuKienModel->find($dangKySuKienId);
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