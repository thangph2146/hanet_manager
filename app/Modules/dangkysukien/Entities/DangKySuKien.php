<?php

namespace App\Modules\dangkysukien\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;
use App\Modules\sukien\Entities\SuKien;

class DangKySuKien extends BaseEntity
{
    protected $tableName = 'dangky_sukien';
    protected $primaryKey = 'dangky_sukien_id';
    
    protected $dates = [
        'ngay_dang_ky',
        'thoi_gian_duyet',
        'thoi_gian_huy',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'dangky_sukien_id' => 'int',
        'su_kien_id' => 'int',
        'status' => 'int',
        'face_verified' => 'boolean',
        'da_check_in' => 'boolean',
        'da_check_out' => 'boolean',
        'checkin_sukien_id' => 'int',
        'checkout_sukien_id' => 'int',
        'attendance_minutes' => 'int',
        'thong_tin_dang_ky' => 'json',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    protected $jsonFields = ['thong_tin_dang_ky'];
    
    // Các quy tắc xác thực cho DangKySuKien
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
        'dien_thoai' => [
            'rules' => 'permit_empty|min_length[10]|max_length[20]',
            'label' => 'Điện thoại'
        ],
        'loai_nguoi_dang_ky' => [
            'rules' => 'required|in_list[khach,sinh_vien,giang_vien]',
            'label' => 'Loại người đăng ký'
        ],
        'status' => [
            'rules' => 'permit_empty|in_list[-1,0,1]',
            'label' => 'Trạng thái'
        ],
        'hinh_thuc_tham_gia' => [
            'rules' => 'required|in_list[offline,online,hybrid]',
            'label' => 'Hình thức tham gia'
        ],
        'attendance_status' => [
            'rules' => 'permit_empty|in_list[not_attended,partial,full]',
            'label' => 'Trạng thái tham dự'
        ],
        'attendance_minutes' => [
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]',
            'label' => 'Số phút tham dự'
        ],
        'diem_danh_bang' => [
            'rules' => 'permit_empty|in_list[qr_code,face_id,manual,none]',
            'label' => 'Phương thức điểm danh'
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
        'dien_thoai' => [
            'min_length' => '{field} phải có ít nhất {param} ký tự',
            'max_length' => '{field} không được vượt quá {param} ký tự'
        ],
        'loai_nguoi_dang_ky' => [
            'required' => '{field} là bắt buộc',
            'in_list' => '{field} không hợp lệ'
        ],
        'status' => [
            'in_list' => '{field} không hợp lệ'
        ],
        'hinh_thuc_tham_gia' => [
            'required' => '{field} là bắt buộc',
            'in_list' => '{field} không hợp lệ'
        ],
        'attendance_status' => [
            'in_list' => '{field} không hợp lệ'
        ],
        'attendance_minutes' => [
            'integer' => '{field} phải là số nguyên',
            'greater_than_equal_to' => '{field} không được nhỏ hơn {param}'
        ],
        'diem_danh_bang' => [
            'in_list' => '{field} không hợp lệ'
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
     * Lấy email đăng ký
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->attributes['email'] ?? null;
    }
    
    /**
     * Lấy họ tên người đăng ký
     *
     * @return string|null
     */
    public function getHoTen(): ?string
    {
        return $this->attributes['ho_ten'] ?? null;
    }
    
    /**
     * Lấy số điện thoại người đăng ký
     *
     * @return string|null
     */
    public function getDienThoai(): ?string
    {
        return $this->attributes['dien_thoai'] ?? null;
    }
    
    /**
     * Lấy loại người đăng ký
     *
     * @return string
     */
    public function getLoaiNguoiDangKy(): string
    {
        return $this->attributes['loai_nguoi_dang_ky'] ?? 'khach';
    }
    
    /**
     * Lấy ngày đăng ký
     *
     * @return Time|null
     */
    public function getNgayDangKy(): ?Time
    {
        if (empty($this->attributes['ngay_dang_ky'])) {
            return null;
        }
        
        return $this->attributes['ngay_dang_ky'] instanceof Time 
            ? $this->attributes['ngay_dang_ky'] 
            : new Time($this->attributes['ngay_dang_ky']);
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
     * Lấy trạng thái đăng ký
     *
     * @return int
     */
    public function getStatus(): int
    {
        return (int)($this->attributes['status'] ?? 0);
    }
    
    /**
     * Lấy nội dung góp ý
     *
     * @return string|null
     */
    public function getNoiDungGopY(): ?string
    {
        return $this->attributes['noi_dung_gop_y'] ?? null;
    }
    
    /**
     * Lấy nguồn giới thiệu
     *
     * @return string|null
     */
    public function getNguonGioiThieu(): ?string
    {
        return $this->attributes['nguon_gioi_thieu'] ?? null;
    }
    
    /**
     * Lấy thông tin đơn vị tổ chức
     *
     * @return string|null
     */
    public function getDonViToChuc(): ?string
    {
        return $this->attributes['don_vi_to_chuc'] ?? null;
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
     * Kiểm tra xác thực khuôn mặt
     *
     * @return bool
     */
    public function isFaceVerified(): bool
    {
        return (bool)($this->attributes['face_verified'] ?? false);
    }
    
    /**
     * Kiểm tra đã check-in
     *
     * @return bool
     */
    public function isDaCheckIn(): bool
    {
        return (bool)($this->attributes['da_check_in'] ?? false);
    }
    
    /**
     * Kiểm tra đã check-out
     *
     * @return bool
     */
    public function isDaCheckOut(): bool
    {
        return (bool)($this->attributes['da_check_out'] ?? false);
    }
    
    /**
     * Lấy ID check-in sự kiện
     *
     * @return int|null
     */
    public function getCheckinSuKienId(): ?int
    {
        return isset($this->attributes['checkin_sukien_id']) ? (int)$this->attributes['checkin_sukien_id'] : null;
    }
    
    /**
     * Lấy ID check-out sự kiện
     *
     * @return int|null
     */
    public function getCheckoutSuKienId(): ?int
    {
        return isset($this->attributes['checkout_sukien_id']) ? (int)$this->attributes['checkout_sukien_id'] : null;
    }
    
    /**
     * Lấy thời gian duyệt
     *
     * @return Time|null
     */
    public function getThoiGianDuyet(): ?Time
    {
        if (empty($this->attributes['thoi_gian_duyet'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_duyet'] instanceof Time 
            ? $this->attributes['thoi_gian_duyet'] 
            : new Time($this->attributes['thoi_gian_duyet']);
    }
    
    /**
     * Lấy thời gian hủy
     *
     * @return Time|null
     */
    public function getThoiGianHuy(): ?Time
    {
        if (empty($this->attributes['thoi_gian_huy'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_huy'] instanceof Time 
            ? $this->attributes['thoi_gian_huy'] 
            : new Time($this->attributes['thoi_gian_huy']);
    }
    
    /**
     * Lấy lý do hủy
     *
     * @return string|null
     */
    public function getLyDoHuy(): ?string
    {
        return $this->attributes['ly_do_huy'] ?? null;
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
     * Lấy trạng thái tham dự
     *
     * @return string
     */
    public function getAttendanceStatus(): string
    {
        return $this->attributes['attendance_status'] ?? 'not_attended';
    }
    
    /**
     * Lấy số phút tham dự
     *
     * @return int
     */
    public function getAttendanceMinutes(): int
    {
        return (int)($this->attributes['attendance_minutes'] ?? 0);
    }
    
    /**
     * Lấy phương thức điểm danh
     *
     * @return string
     */
    public function getDiemDanhBang(): string
    {
        return $this->attributes['diem_danh_bang'] ?? 'none';
    }
    
    /**
     * Lấy thông tin đăng ký bổ sung
     *
     * @return array|null
     */
    public function getThongTinDangKy(): ?array
    {
        $thongTin = $this->attributes['thong_tin_dang_ky'] ?? null;
        
        if (is_string($thongTin)) {
            return json_decode($thongTin, true);
        }
        
        return $thongTin;
    }
    
    /**
     * Lấy lý do tham dự
     *
     * @return string|null
     */
    public function getLyDoThamDu(): ?string
    {
        return $this->attributes['ly_do_tham_du'] ?? null;
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
     * Lấy ngày đăng ký đã định dạng
     *
     * @param string $format Định dạng thời gian
     * @return string|null
     */
    public function getNgayDangKyFormatted(string $format = 'd/m/Y H:i'): ?string
    {
        $time = $this->getNgayDangKy();
        return $time ? $time->format($format) : null;
    }
    
    /**
     * Lấy thời gian duyệt đã định dạng
     *
     * @param string $format Định dạng thời gian
     * @return string|null
     */
    public function getThoiGianDuyetFormatted(string $format = 'd/m/Y H:i'): ?string
    {
        $time = $this->getThoiGianDuyet();
        return $time ? $time->format($format) : null;
    }
    
    /**
     * Lấy thời gian hủy đã định dạng
     *
     * @param string $format Định dạng thời gian
     * @return string|null
     */
    public function getThoiGianHuyFormatted(string $format = 'd/m/Y H:i'): ?string
    {
        $time = $this->getThoiGianHuy();
        return $time ? $time->format($format) : null;
    }
    
    /**
     * Lấy tên trạng thái đăng ký để hiển thị
     *
     * @return string
     */
    public function getStatusText(): string
    {
        $status = $this->getStatus();
        
        switch ($status) {
            case -1:
                return 'Đã hủy';
            case 0:
                return 'Chờ xác nhận';
            case 1:
                return 'Đã xác nhận';
            default:
                return 'Không xác định';
        }
    }
    
    /**
     * Lấy tên loại người đăng ký để hiển thị
     *
     * @return string
     */
    public function getLoaiNguoiDangKyText(): string
    {
        $loai = $this->getLoaiNguoiDangKy();
        
        switch ($loai) {
            case 'khach':
                return 'Khách mời';
            case 'sinh_vien':
                return 'Sinh viên';
            case 'giang_vien':
                return 'Giảng viên';
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
            case 'hybrid':
                return 'Kết hợp';
            default:
                return 'Không xác định';
        }
    }
    
    /**
     * Lấy tên phương thức điểm danh để hiển thị
     *
     * @return string
     */
    public function getDiemDanhBangText(): string
    {
        $diemDanh = $this->getDiemDanhBang();
        
        switch ($diemDanh) {
            case 'qr_code':
                return 'Mã QR';
            case 'face_id':
                return 'Nhận diện khuôn mặt';
            case 'manual':
                return 'Điểm danh thủ công';
            case 'none':
                return 'Chưa điểm danh';
            default:
                return 'Không xác định';
        }
    }
    
    /**
     * Lấy tên trạng thái tham dự để hiển thị
     *
     * @return string
     */
    public function getAttendanceStatusText(): string
    {
        $status = $this->getAttendanceStatus();
        
        switch ($status) {
            case 'not_attended':
                return 'Chưa tham dự';
            case 'partial':
                return 'Tham dự một phần';
            case 'full':
                return 'Tham dự đầy đủ';
            default:
                return 'Không xác định';
        }
    }
    
    /**
     * Lấy thông tin thời gian tham dự đã định dạng
     *
     * @return string
     */
    public function getAttendanceTimeFormatted(): string
    {
        $minutes = $this->getAttendanceMinutes();
        
        if ($minutes == 0) {
            return 'Chưa tham dự';
        }
        
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        
        if ($hours > 0) {
            return $hours . ' giờ ' . ($mins > 0 ? $mins . ' phút' : '');
        }
        
        return $mins . ' phút';
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