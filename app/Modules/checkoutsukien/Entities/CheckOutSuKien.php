<?php

namespace App\Modules\checkoutsukien\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;
use App\Modules\sukien\Entities\SuKien;
use App\Modules\dangkysukien\Entities\DangKySuKien;
use App\Modules\checkinsukien\Entities\CheckInSuKien;

class CheckOutSuKien extends BaseEntity
{
    protected $tableName = 'checkout_sukien';
    protected $primaryKey = 'checkout_sukien_id';
    
    protected $dates = [
        'thoi_gian_check_out',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'checkout_sukien_id' => 'int',
        'su_kien_id' => 'int',
        'dangky_sukien_id' => 'int',
        'checkin_sukien_id' => 'int',
        'face_match_score' => 'float',
        'face_verified' => 'boolean',
        'status' => 'int',
        'attendance_duration_minutes' => 'int',
        'danh_gia' => 'int',
        'thong_tin_bo_sung' => 'json',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    protected $jsonFields = ['thong_tin_bo_sung'];
    
    // Các quy tắc xác thực cho CheckOutSuKien
    protected $validationRules = [
        'su_kien_id' => [
            'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id,su_kien.deleted_at,null]',
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
        'checkin_sukien_id' => [
            'rules' => 'permit_empty|integer|is_not_unique[checkin_sukien.checkin_sukien_id,checkin_sukien.deleted_at,null]',
            'label' => 'ID check-in'
        ],
        'thoi_gian_check_out' => [
            'rules' => 'required|valid_date',
            'label' => 'Thời gian check-out'
        ],
        'checkout_type' => [
            'rules' => 'required|in_list[face_id,manual,qr_code,auto,online]',
            'label' => 'Loại check-out'
        ],
        'face_image_path' => [
            'rules' => 'permit_empty|string|max_length[255]',
            'label' => 'Đường dẫn ảnh khuôn mặt'
        ],
        'face_match_score' => [
            'rules' => 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[1]',
            'label' => 'Điểm số khớp khuôn mặt'
        ],
        'face_verified' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'label' => 'Xác minh khuôn mặt'
        ],
        'ma_xac_nhan' => [
            'rules' => 'permit_empty|string|max_length[20]',
            'label' => 'Mã xác nhận'
        ],
        'status' => [
            'rules' => 'permit_empty|integer|in_list[0,1,2]',
            'label' => 'Trạng thái'
        ],
        'location_data' => [
            'rules' => 'permit_empty|string|max_length[255]',
            'label' => 'Dữ liệu vị trí'
        ],
        'device_info' => [
            'rules' => 'permit_empty|string|max_length[255]',
            'label' => 'Thông tin thiết bị'
        ],
        'attendance_duration_minutes' => [
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]',
            'label' => 'Thời gian tham dự (phút)'
        ],
        'hinh_thuc_tham_gia' => [
            'rules' => 'required|in_list[offline,online]',
            'label' => 'Hình thức tham gia'
        ],
        'ip_address' => [
            'rules' => 'permit_empty|string|max_length[45]',
            'label' => 'Địa chỉ IP'
        ],
        'thong_tin_bo_sung' => [
            'rules' => 'permit_empty',
            'label' => 'Thông tin bổ sung'
        ],
        'ghi_chu' => [
            'rules' => 'permit_empty',
            'label' => 'Ghi chú'
        ],
        'feedback' => [
            'rules' => 'permit_empty',
            'label' => 'Phản hồi'
        ],
        'danh_gia' => [
            'rules' => 'permit_empty|integer|greater_than_equal_to[1]|less_than_equal_to[5]',
            'label' => 'Đánh giá'
        ],
        'noi_dung_danh_gia' => [
            'rules' => 'permit_empty',
            'label' => 'Nội dung đánh giá'
        ],
        'created_at' => [
            'rules' => 'permit_empty|valid_date',
            'label' => 'Ngày tạo'
        ],
        'updated_at' => [
            'rules' => 'permit_empty|valid_date',
            'label' => 'Ngày cập nhật'
        ],
        'deleted_at' => [
            'rules' => 'permit_empty|valid_date',
            'label' => 'Ngày xóa'
        ]
    ];
    
    protected $validationMessages = [
        'su_kien_id' => [
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
        'checkin_sukien_id' => [
            'integer' => '{field} phải là số nguyên',
            'is_not_unique' => '{field} không tồn tại trong hệ thống'
        ],
        'thoi_gian_check_out' => [
            'required' => '{field} là bắt buộc',
            'valid_date' => '{field} phải là ngày hợp lệ'
        ],
        'checkout_type' => [
            'required' => '{field} là bắt buộc',
            'in_list' => '{field} phải là một trong các giá trị: nhận diện khuôn mặt, thủ công, mã QR, tự động, trực tuyến'
        ],
        'face_match_score' => [
            'numeric' => '{field} phải là số',
            'greater_than_equal_to' => '{field} phải lớn hơn hoặc bằng {param}',
            'less_than_equal_to' => '{field} phải nhỏ hơn hoặc bằng {param}'
        ],
        'attendance_duration_minutes' => [
            'integer' => '{field} phải là số nguyên',
            'greater_than_equal_to' => '{field} phải lớn hơn hoặc bằng {param}'
        ],
        'status' => [
            'integer' => '{field} phải là số nguyên'
        ],
        'hinh_thuc_tham_gia' => [
            'required' => '{field} là bắt buộc',
            'in_list' => '{field} phải là một trong các giá trị: trực tiếp, trực tuyến'
        ],
        'danh_gia' => [
            'integer' => '{field} phải là số nguyên',
            'greater_than_equal_to' => '{field} phải lớn hơn hoặc bằng {param}',
            'less_than_equal_to' => '{field} phải nhỏ hơn hoặc bằng {param}'
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
        return (int)($this->attributes['su_kien_id'] ?? 0);
    }
    
    /**
     * Lấy email người check-out
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->attributes['email'] ?? null;
    }
    
    /**
     * Lấy họ tên người check-out
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
     * Lấy ID check-in sự kiện
     *
     * @return int|null
     */
    public function getCheckInSuKienId(): ?int
    {
        return isset($this->attributes['checkin_sukien_id']) ? (int)$this->attributes['checkin_sukien_id'] : null;
    }
    
    /**
     * Lấy thời gian check-out
     *
     * @return Time|null
     */
    public function getThoiGianCheckOut(): ?Time
    {
        if (empty($this->attributes['thoi_gian_check_out'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_check_out'] instanceof Time 
            ? $this->attributes['thoi_gian_check_out'] 
            : new Time($this->attributes['thoi_gian_check_out']);
    }
    
    /**
     * Lấy loại check-out
     *
     * @return string|null
     */
    public function getCheckoutType(): ?string
    {
        return $this->attributes['checkout_type'] ?? null;
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
     * Lấy trạng thái check-out
     *
     * @return int
     */
    public function getStatus(): int
    {
        return (int)($this->attributes['status'] ?? 0);
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
     * Lấy thời gian tham dự (phút)
     *
     * @return int|null
     */
    public function getAttendanceDurationMinutes(): ?int
    {
        return isset($this->attributes['attendance_duration_minutes']) ? (int)$this->attributes['attendance_duration_minutes'] : null;
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
        if (empty($this->attributes['thong_tin_bo_sung'])) {
            return null;
        }
        
        if (is_string($this->attributes['thong_tin_bo_sung'])) {
            return json_decode($this->attributes['thong_tin_bo_sung'], true);
        }
        
        return $this->attributes['thong_tin_bo_sung'];
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
     * Lấy phản hồi
     *
     * @return string|null
     */
    public function getFeedback(): ?string
    {
        return $this->attributes['feedback'] ?? null;
    }
    
    /**
     * Lấy điểm đánh giá
     *
     * @return int|null
     */
    public function getDanhGia(): ?int
    {
        return isset($this->attributes['danh_gia']) ? (int)$this->attributes['danh_gia'] : null;
    }
    
    /**
     * Lấy nội dung đánh giá
     *
     * @return string|null
     */
    public function getNoiDungDanhGia(): ?string
    {
        return $this->attributes['noi_dung_danh_gia'] ?? null;
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
     * Lấy thời gian check-out đã định dạng
     *
     * @param string $format Định dạng thời gian
     * @return string|null
     */
    public function getThoiGianCheckOutFormatted(string $format = 'd/m/Y H:i'): ?string
    {
        $time = $this->getThoiGianCheckOut();
        return $time ? $time->format($format) : null;
    }
    
    /**
     * Lấy thời gian tham dự đã định dạng
     *
     * @return string|null
     */
    public function getAttendanceDurationFormatted(): ?string
    {
        $minutes = $this->getAttendanceDurationMinutes();
        
        if ($minutes === null) {
            return null;
        }
        
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return $hours . ' giờ ' . ($mins > 0 ? $mins . ' phút' : '');
        }
        
        return $mins . ' phút';
    }
    
    /**
     * Lấy tên trạng thái check-out để hiển thị
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
     * Lấy tên loại check-out để hiển thị
     *
     * @return string
     */
    public function getCheckoutTypeText(): string
    {
        $type = $this->getCheckoutType();
        
        switch ($type) {
            case 'face_id':
                return 'Nhận diện khuôn mặt';
            case 'manual':
                return 'Thủ công';
            case 'qr_code':
                return 'Mã QR';
            case 'auto':
                return 'Tự động';
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
     * Lấy số sao đánh giá để hiển thị
     *
     * @return string
     */
    public function getDanhGiaStars(): string
    {
        $danhGia = $this->getDanhGia();
        
        if ($danhGia === null) {
            return 'Chưa đánh giá';
        }
        
        return str_repeat('★', $danhGia) . str_repeat('☆', 5 - $danhGia);
    }
    
    /**
     * Lấy thông tin sự kiện
     *
     * @return SuKien|null
     */
    public function getSuKien(): ?SuKien
    {
        // Tạo thuộc tính tạm thời trước để tránh lỗi
        if (!property_exists($this, 'instance_suKien')) {
            $this->instance_suKien = null;
        }
        
        // Kiểm tra xem đã lấy dữ liệu chưa
        if ($this->instance_suKien !== null) {
            return $this->instance_suKien;
        }
        
        if (!$this->getSuKienId()) {
            return null;
        }
        
        $suKienModel = model('App\Modules\sukien\Models\SuKienModel');
        $this->instance_suKien = $suKienModel->find($this->getSuKienId());
        
        return $this->instance_suKien;
    }
    
    /**
     * Lấy thông tin đăng ký sự kiện
     *
     * @return DangKySuKien|null
     */
    public function getDangKySuKien(): ?DangKySuKien
    {
        // Tạo thuộc tính tạm thời trước để tránh lỗi
        if (!property_exists($this, 'instance_dangKySuKien')) {
            $this->instance_dangKySuKien = null;
        }
        
        // Kiểm tra xem đã lấy dữ liệu chưa
        if ($this->instance_dangKySuKien !== null) {
            return $this->instance_dangKySuKien;
        }
        
        if (!$this->getDangKySuKienId()) {
            return null;
        }
        
        $dangKySuKienModel = model('App\Modules\dangkysukien\Models\DangKySuKienModel');
        $this->instance_dangKySuKien = $dangKySuKienModel->find($this->getDangKySuKienId());
        
        return $this->instance_dangKySuKien;
    }
    
    /**
     * Lấy thông tin check-in sự kiện
     *
     * @return CheckInSuKien|null
     */
    public function getCheckInSuKien(): ?CheckInSuKien
    {
        // Tạo thuộc tính tạm thời trước để tránh lỗi
        if (!property_exists($this, 'instance_checkInSuKien')) {
            $this->instance_checkInSuKien = null;
        }
        
        // Kiểm tra xem đã lấy dữ liệu chưa
        if ($this->instance_checkInSuKien !== null) {
            return $this->instance_checkInSuKien;
        }
        
        if (!$this->getCheckInSuKienId()) {
            return null;
        }
        
        $checkInSuKienModel = model('App\Modules\checkinsukien\Models\CheckInSuKienModel');
        $this->instance_checkInSuKien = $checkInSuKienModel->find($this->getCheckInSuKienId());
        
        return $this->instance_checkInSuKien;
    }
    
    /**
     * Lấy các quy tắc xác thực
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            'su_kien_id' => [
                'label' => 'Sự kiện',
                'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id,su_kien.deleted_at,null]'
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email'
            ],
            'ho_ten' => [
                'label' => 'Họ tên',
                'rules' => 'required|min_length[3]|max_length[255]'
            ],
            'dangky_sukien_id' => [
                'label' => 'ID đăng ký sự kiện',
                'rules' => 'permit_empty|integer|is_not_unique[dangky_sukien.dangky_sukien_id,dangky_sukien.deleted_at,null]'
            ],
            'checkin_sukien_id' => [
                'label' => 'ID check-in sự kiện',
                'rules' => 'permit_empty|integer|is_not_unique[checkin_sukien.checkin_sukien_id,checkin_sukien.deleted_at,null]'
            ],
            'thoi_gian_check_out' => [
                'label' => 'Thời gian check-out',
                'rules' => 'required|valid_date'
            ],
            'checkout_type' => [
                'label' => 'Loại check-out',
                'rules' => 'required|in_list[face_id,manual,qr_code,auto,online]'
            ],
            'face_image_path' => [
                'label' => 'Đường dẫn ảnh khuôn mặt',
                'rules' => 'permit_empty|string|max_length[255]'
            ],
            'face_match_score' => [
                'label' => 'Điểm khớp khuôn mặt',
                'rules' => 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[1]'
            ],
            'face_verified' => [
                'label' => 'Xác minh khuôn mặt',
                'rules' => 'permit_empty|in_list[0,1]'
            ],
            'ma_xac_nhan' => [
                'label' => 'Mã xác nhận',
                'rules' => 'permit_empty|string|max_length[100]'
            ],
            'status' => [
                'label' => 'Trạng thái',
                'rules' => 'required|integer|in_list[0,1,2]'
            ],
            'location_data' => [
                'label' => 'Dữ liệu vị trí',
                'rules' => 'permit_empty|string'
            ],
            'device_info' => [
                'label' => 'Thông tin thiết bị',
                'rules' => 'permit_empty|string'
            ],
            'attendance_duration_minutes' => [
                'label' => 'Thời lượng tham dự (phút)',
                'rules' => 'permit_empty|integer|greater_than_equal_to[0]'
            ],
            'hinh_thuc_tham_gia' => [
                'label' => 'Hình thức tham gia',
                'rules' => 'required|in_list[offline,online]'
            ],
            'ip_address' => [
                'label' => 'Địa chỉ IP',
                'rules' => 'permit_empty|string|max_length[50]'
            ],
            'thong_tin_bo_sung' => [
                'label' => 'Thông tin bổ sung',
                'rules' => 'permit_empty'
            ],
            'ghi_chu' => [
                'label' => 'Ghi chú',
                'rules' => 'permit_empty|string'
            ],
            'feedback' => [
                'label' => 'Phản hồi',
                'rules' => 'permit_empty|string'
            ],
            'danh_gia' => [
                'label' => 'Đánh giá',
                'rules' => 'permit_empty|integer|greater_than_equal_to[1]|less_than_equal_to[5]'
            ],
            'noi_dung_danh_gia' => [
                'label' => 'Nội dung đánh giá',
                'rules' => 'permit_empty|string'
            ],
            'created_at' => [
                'label' => 'Ngày tạo',
                'rules' => 'permit_empty|valid_date'
            ],
            'updated_at' => [
                'label' => 'Ngày cập nhật',
                'rules' => 'permit_empty|valid_date'
            ],
            'deleted_at' => [
                'label' => 'Ngày xóa',
                'rules' => 'permit_empty|valid_date'
            ]
        ];
    }
    
    /**
     * Lấy các thông báo xác thực
     *
     * @return array
     */
    public function getValidationMessages(): array
    {
        return [
            'su_kien_id' => [
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
            'checkin_sukien_id' => [
                'integer' => '{field} phải là số nguyên',
                'is_not_unique' => '{field} không tồn tại trong hệ thống'
            ],
            'thoi_gian_check_out' => [
                'required' => '{field} là bắt buộc',
                'valid_date' => '{field} phải là ngày hợp lệ'
            ],
            'checkout_type' => [
                'required' => '{field} là bắt buộc',
                'in_list' => '{field} phải là một trong các giá trị: nhận diện khuôn mặt, thủ công, mã QR, tự động, trực tuyến'
            ],
            'face_match_score' => [
                'numeric' => '{field} phải là số',
                'greater_than_equal_to' => '{field} phải lớn hơn hoặc bằng {param}',
                'less_than_equal_to' => '{field} phải nhỏ hơn hoặc bằng {param}'
            ],
            'attendance_duration_minutes' => [
                'integer' => '{field} phải là số nguyên',
                'greater_than_equal_to' => '{field} phải lớn hơn hoặc bằng {param}'
            ],
            'status' => [
                'integer' => '{field} phải là số nguyên',
                'in_list' => '{field} phải có giá trị hợp lệ'
            ],
            'hinh_thuc_tham_gia' => [
                'required' => '{field} là bắt buộc',
                'in_list' => '{field} phải là một trong các giá trị: trực tiếp, trực tuyến'
            ],
            'danh_gia' => [
                'integer' => '{field} phải là số nguyên',
                'greater_than_equal_to' => '{field} phải lớn hơn hoặc bằng {param}',
                'less_than_equal_to' => '{field} phải nhỏ hơn hoặc bằng {param}'
            ]
        ];
    }
}