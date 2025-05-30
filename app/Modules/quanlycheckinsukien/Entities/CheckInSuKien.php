<?php

namespace App\Modules\quanlycheckinsukien\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;
use App\Modules\quanlysukien\Entities\SuKien;
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
        'su_kien_id' => 'int',
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
        'su_kien_id' => [
            'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id]',
            'label' => 'ID sự kiện'
        ],
        'email' => [
            'rules' => 'required|valid_email|max_length[100]',
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
        'face_image_path' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Đường dẫn ảnh khuôn mặt'
        ],
        'face_match_score' => [
            'rules' => 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[1]',
            'label' => 'Điểm số khớp khuôn mặt'
        ],
        'face_verified' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'label' => 'Xác thực khuôn mặt'
        ],
        'ma_xac_nhan' => [
            'rules' => 'permit_empty|max_length[20]',
            'label' => 'Mã xác nhận'
        ],
        'status' => [
            'rules' => 'permit_empty|integer|in_list[0,1,2]',
            'label' => 'Trạng thái'
        ],
        'location_data' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Dữ liệu vị trí'
        ],
        'device_info' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Thông tin thiết bị'
        ],
        'hinh_thuc_tham_gia' => [
            'rules' => 'required|in_list[offline,online]',
            'label' => 'Hình thức tham gia'
        ],
        'ip_address' => [
            'rules' => 'permit_empty|max_length[45]',
            'label' => 'Địa chỉ IP'
        ],
        'thong_tin_bo_sung' => [
            'rules' => 'permit_empty|valid_json',
            'label' => 'Thông tin bổ sung'
        ],
        'ghi_chu' => [
            'rules' => 'permit_empty',
            'label' => 'Ghi chú'
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
            'valid_email' => '{field} không hợp lệ',
            'max_length' => '{field} không được vượt quá {param} ký tự'
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
        'face_image_path' => [
            'max_length' => '{field} không được vượt quá {param} ký tự'
        ],
        'face_match_score' => [
            'numeric' => '{field} phải là số',
            'greater_than_equal_to' => '{field} phải lớn hơn hoặc bằng {param}',
            'less_than_equal_to' => '{field} phải nhỏ hơn hoặc bằng {param}'
        ],
        'face_verified' => [
            'in_list' => '{field} phải là giá trị boolean'
        ],
        'ma_xac_nhan' => [
            'max_length' => '{field} không được vượt quá {param} ký tự'
        ],
        'status' => [
            'integer' => '{field} phải là số nguyên',
            'in_list' => '{field} phải là một trong các giá trị: 0, 1, 2'
        ],
        'location_data' => [
            'max_length' => '{field} không được vượt quá {param} ký tự'
        ],
        'device_info' => [
            'max_length' => '{field} không được vượt quá {param} ký tự'
        ],
        'hinh_thuc_tham_gia' => [
            'required' => '{field} là bắt buộc',
            'in_list' => '{field} phải là một trong các giá trị: trực tiếp, trực tuyến'
        ],
        'ip_address' => [
            'max_length' => '{field} không được vượt quá {param} ký tự'
        ],
        'thong_tin_bo_sung' => [
            'valid_json' => '{field} phải là một chuỗi JSON hợp lệ'
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
     * Lấy thời gian check-in đã định dạng
     *
     * @param string $format Định dạng thời gian
     * @return string|null
     */
    public function getThoiGianCheckInFormatted(string $format = 'd/m/Y H:i:s'): ?string
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
        
        $suKienModel = model('App\Modules\quanlysukien\Models\SuKienModel');
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
     * Lấy các quy tắc xác thực
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            'su_kien_id' => [
                'label' => 'Sự kiện',
                'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id,su_kien.deleted_at,null]',
                'errors' => [
                    'required' => '{field} là bắt buộc',
                    'integer' => '{field} phải là số nguyên',
                    'is_not_unique' => '{field} không tồn tại trong hệ thống hoặc đã bị xóa'
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => '{field} là bắt buộc',
                    'valid_email' => '{field} không hợp lệ'
                ]
            ],
            'ho_ten' => [
                'label' => 'Họ tên',
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => '{field} là bắt buộc',
                    'min_length' => '{field} phải có ít nhất {param} ký tự',
                    'max_length' => '{field} không được vượt quá {param} ký tự'
                ]
            ],
            'dangky_sukien_id' => [
                'label' => 'ID đăng ký sự kiện',
                'rules' => 'permit_empty|integer|is_not_unique[dangky_sukien.dangky_sukien_id]',
                'errors' => [
                    'integer' => '{field} phải là số nguyên',
                    'is_not_unique' => '{field} không tồn tại trong hệ thống hoặc đã bị xóa'
                ]
            ],
            'thoi_gian_check_in' => [
                'label' => 'Thời gian check-in',
                'rules' => 'required|valid_date',
                'errors' => [
                    'required' => '{field} là bắt buộc',
                    'valid_date' => '{field} phải có định dạng ngày giờ hợp lệ'
                ]
            ],
            'checkin_type' => [
                'label' => 'Loại check-in',
                'rules' => 'required|in_list[face_id,manual,qr_code,online]',
                'errors' => [
                    'required' => '{field} là bắt buộc',
                    'in_list' => '{field} phải là một trong các giá trị: nhận diện khuôn mặt, thủ công, mã QR, trực tuyến'
                ]
            ],
            'face_image_path' => [
                'label' => 'Đường dẫn ảnh khuôn mặt',
                'rules' => 'permit_empty|string|max_length[255]'
            ],
            'face_match_score' => [
                'label' => 'Điểm khớp khuôn mặt',
                'rules' => 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[1]',
                'errors' => [
                    'numeric' => '{field} phải là số',
                    'greater_than_equal_to' => '{field} phải lớn hơn hoặc bằng {param}',
                    'less_than_equal_to' => '{field} phải nhỏ hơn hoặc bằng {param}'
                ]
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
                'rules' => 'required|integer|in_list[0,1,2]',
                'errors' => [
                    'required' => '{field} là bắt buộc',
                    'integer' => '{field} phải là số nguyên',
                    'in_list' => '{field} phải có giá trị hợp lệ'
                ]
            ],
            'location_data' => [
                'label' => 'Dữ liệu vị trí',
                'rules' => 'permit_empty|string'
            ],
            'device_info' => [
                'label' => 'Thông tin thiết bị',
                'rules' => 'permit_empty|string'
            ],
            'hinh_thuc_tham_gia' => [
                'label' => 'Hình thức tham gia',
                'rules' => 'required|in_list[offline,online]',
                'errors' => [
                    'required' => '{field} là bắt buộc',
                    'in_list' => '{field} phải là một trong các giá trị: trực tiếp, trực tuyến'
                ]
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
                'is_not_unique' => '{field} không tồn tại trong hệ thống hoặc đã bị xóa'
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
                'is_not_unique' => '{field} không tồn tại trong hệ thống hoặc đã bị xóa'
            ],
            'thoi_gian_check_in' => [
                'required' => '{field} là bắt buộc',
                'valid_date' => '{field} phải có định dạng ngày giờ hợp lệ'
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
                'integer' => '{field} phải là số nguyên',
                'in_list' => '{field} phải có giá trị hợp lệ'
            ],
            'hinh_thuc_tham_gia' => [
                'required' => '{field} là bắt buộc',
                'in_list' => '{field} phải là một trong các giá trị: trực tiếp, trực tuyến'
            ]
        ];
    }
    
    /**
     * Lấy tên sự kiện (nếu có được load từ join)
     *
     * @return string|null
     */
    public function getTenSuKien(): ?string
    {
        return $this->attributes['ten_su_kien'] ?? null;
    }
    
    /**
     * Lấy điểm số khớp khuôn mặt theo tỷ lệ phần trăm
     *
     * @return string|null
     */
    public function getFaceMatchScorePercent(): ?string
    {
        $score = $this->getFaceMatchScore();
        if ($score === null) {
            return null;
        }
        
        return number_format($score * 100, 2) . '%';
    }
    
    /**
     * Tạo HTML cho trạng thái
     *
     * @return string
     */
    public function getStatusHtml(): string
    {
        $status = $this->getStatus();
        
        switch ($status) {
            case 0:
                return '<span class="badge bg-danger">Vô hiệu</span>';
            case 1:
                return '<span class="badge bg-success">Hoạt động</span>';
            case 2:
                return '<span class="badge bg-warning text-dark">Đang xử lý</span>';
            default:
                return '<span class="badge bg-secondary">Không xác định</span>';
        }
    }
    
    /**
     * Tạo HTML cho loại check-in
     *
     * @return string
     */
    public function getCheckinTypeHtml(): string
    {
        $type = $this->getCheckinType();
        
        switch ($type) {
            case 'face_id':
                return '<span class="badge bg-info">Nhận diện khuôn mặt</span>';
            case 'manual':
                return '<span class="badge bg-primary">Thủ công</span>';
            case 'qr_code':
                return '<span class="badge bg-success">Mã QR</span>';
            case 'online':
                return '<span class="badge bg-warning text-dark">Trực tuyến</span>';
            default:
                return '<span class="badge bg-secondary">Không xác định</span>';
        }
    }
    
    /**
     * Tạo HTML cho hình thức tham gia
     *
     * @return string
     */
    public function getHinhThucThamGiaHtml(): string
    {
        $hinhThuc = $this->getHinhThucThamGia();
        
        switch ($hinhThuc) {
            case 'offline':
                return '<span class="badge bg-primary">Trực tiếp</span>';
            case 'online':
                return '<span class="badge bg-success">Trực tuyến</span>';
            default:
                return '<span class="badge bg-secondary">Không xác định</span>';
        }
    }
    
    /**
     * Tạo HTML cho kết quả xác minh khuôn mặt
     *
     * @return string
     */
    public function getFaceVerifiedHtml(): string
    {
        if ($this->isFaceVerified()) {
            return '<span class="badge bg-success">Đã xác minh</span>';
        }
        
        return '<span class="badge bg-warning text-dark">Chưa xác minh</span>';
    }
    
    /**
     * Kiểm tra nếu check-in bằng khuôn mặt
     *
     * @return bool
     */
    public function isFaceCheckIn(): bool
    {
        return $this->getCheckinType() === 'face_id';
    }
    
    /**
     * Kiểm tra nếu check-in bằng QR code
     *
     * @return bool
     */
    public function isQrCheckIn(): bool
    {
        return $this->getCheckinType() === 'qr_code';
    }
    
    /**
     * Kiểm tra nếu check-in thủ công
     *
     * @return bool
     */
    public function isManualCheckIn(): bool
    {
        return $this->getCheckinType() === 'manual';
    }
    
    /**
     * Kiểm tra nếu check-in trực tuyến
     *
     * @return bool
     */
    public function isOnlineCheckIn(): bool
    {
        return $this->getCheckinType() === 'online';
    }
    
    /**
     * Kiểm tra nếu tham gia trực tiếp (offline)
     *
     * @return bool
     */
    public function isOfflineParticipation(): bool
    {
        return $this->getHinhThucThamGia() === 'offline';
    }
    
    /**
     * Kiểm tra nếu tham gia trực tuyến (online)
     *
     * @return bool
     */
    public function isOnlineParticipation(): bool
    {
        return $this->getHinhThucThamGia() === 'online';
    }
    
    /**
     * Lấy URL ảnh khuôn mặt đầy đủ
     *
     * @return string|null
     */
    public function getFaceImageUrl(): ?string
    {
        $path = $this->getFaceImagePath();
        if (empty($path)) {
            return null;
        }
        
        // Kiểm tra nếu path là URL đầy đủ
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }
        
        // Nếu là đường dẫn tương đối, thêm base URL
        if (strpos($path, '/') === 0) {
            return base_url(ltrim($path, '/'));
        }
        
        return base_url($path);
    }
    
    /**
     * Lấy thông tin bổ sung dưới dạng mảng có định dạng
     *
     * @return array
     */
    public function getFormattedThongTinBoSung(): array
    {
        $thongTin = $this->getThongTinBoSung();
        if (empty($thongTin)) {
            return [];
        }
        
        // Chuyển đổi các chìa khóa từ snake_case sang tiếng Việt có dấu
        $labels = [
            'dien_thoai' => 'Điện thoại',
            'dia_chi' => 'Địa chỉ',
            'don_vi' => 'Đơn vị',
            'chuc_vu' => 'Chức vụ',
            'ghi_chu' => 'Ghi chú',
            'ngay_sinh' => 'Ngày sinh',
            'gioi_tinh' => 'Giới tính',
            'so_cmnd' => 'Số CMND/CCCD',
            'to_chuc' => 'Tổ chức',
            'ghi_chu_them' => 'Ghi chú thêm',
        ];
        
        $formatted = [];
        foreach ($thongTin as $key => $value) {
            $label = $labels[$key] ?? ucfirst(str_replace('_', ' ', $key));
            $formatted[$label] = $value;
        }
        
        return $formatted;
    }

    /**
     * Lấy chuỗi JSON thông tin bổ sung
     *
     * @return string|null
     */
    public function getThongTinBoSungJson(): ?string
    {
        $thongTin = $this->attributes['thong_tin_bo_sung'] ?? null;
        
        if (is_array($thongTin)) {
            return json_encode($thongTin);
        }
        
        return $thongTin;
    }

    /**
     * Kiểm tra xem check-in có tồn tại ảnh khuôn mặt không
     *
     * @return bool
     */
    public function hasFaceImage(): bool
    {
        return !empty($this->getFaceImagePath());
    }

    /**
     * Lấy định dạng ngày tạo 
     *
     * @return string
     */
    public function getCreatedAtFormatted(): string
    {
        $time = $this->getCreatedAt();
        return $time ? $time->format('d/m/Y H:i:s') : '';
    }

    /**
     * Lấy định dạng ngày cập nhật
     *
     * @return string
     */
    public function getUpdatedAtFormatted(): string
    {
        $time = $this->getUpdatedAt();
        return $time ? $time->format('d/m/Y H:i:s') : '';
    }

    /**
     * Lấy định dạng ngày cập nhật với format tùy chọn
     *
     * @param string $format Định dạng thời gian
     * @return string
     */
    public function getUpdatedAtFormattedCustom(string $format = 'd/m/Y H:i:s'): string
    {
        $time = $this->getUpdatedAt();
        return $time ? $time->format($format) : '';
    }

    /**
     * Lấy định dạng ngày xóa
     *
     * @return string
     */
    public function getDeletedAtFormatted(): string
    {
        $time = $this->getDeletedAt();
        return $time ? $time->format('d/m/Y H:i:s') : '';
    }

    /**
     * Lấy định dạng ngày xóa với format tùy chọn
     *
     * @param string $format Định dạng thời gian
     * @return string
     */
    public function getDeletedAtFormattedCustom(string $format = 'd/m/Y H:i:s'): string
    {
        $time = $this->getDeletedAt();
        return $time ? $time->format($format) : '';
    }

    /**
     * Lấy thông tin thiết bị đã định dạng
     *
     * @return string|null
     */
    public function getFormattedDeviceInfo(): ?string
    {
        $deviceInfo = $this->getDeviceInfo();
        if (empty($deviceInfo)) {
            return null;
        }
        
        // Cố gắng phân tích thông tin thiết bị từ chuỗi User-Agent
        $browser = 'Không xác định';
        $os = 'Không xác định';
        
        if (strpos($deviceInfo, 'Chrome') !== false) {
            $browser = 'Chrome';
        } elseif (strpos($deviceInfo, 'Firefox') !== false) {
            $browser = 'Firefox';
        } elseif (strpos($deviceInfo, 'Safari') !== false) {
            $browser = 'Safari';
        } elseif (strpos($deviceInfo, 'Edge') !== false) {
            $browser = 'Edge';
        }
        
        if (strpos($deviceInfo, 'Windows') !== false) {
            $os = 'Windows';
        } elseif (strpos($deviceInfo, 'Mac OS') !== false) {
            $os = 'macOS';
        } elseif (strpos($deviceInfo, 'iPhone') !== false || strpos($deviceInfo, 'iOS') !== false) {
            $os = 'iOS';
        } elseif (strpos($deviceInfo, 'Android') !== false) {
            $os = 'Android';
        } elseif (strpos($deviceInfo, 'Linux') !== false) {
            $os = 'Linux';
        }
        
        return "{$browser} trên {$os}";
    }

    /**
     * Lấy trạng thái check-in bằng văn bản
     * 
     * @return string
     */
    public function getStatusLabel(): string
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
     * Lấy tên loại check-in
     * 
     * @return string
     */
    public function getCheckinTypeLabel(): string
    {
        return $this->getCheckinTypeText();
    }

    /**
     * Lấy tên hình thức tham gia
     * 
     * @return string
     */
    public function getHinhThucThamGiaLabel(): string
    {
        return $this->getHinhThucThamGiaText();
    }
}