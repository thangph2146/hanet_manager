<?php

namespace App\Modules\sukien\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class SuKien extends BaseEntity
{
    protected $tableName = 'su_kien';
    protected $primaryKey = 'su_kien_id';
    
    protected $dates = [
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'bat_dau_dang_ky',
        'ket_thuc_dang_ky',
        'han_huy_dang_ky',
        'gio_bat_dau',
        'gio_ket_thuc',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'su_kien_id' => 'int',
        'loai_su_kien_id' => 'int',
        'su_kien_poster' => 'json',
        'status' => 'boolean',
        'tong_dang_ky' => 'int',
        'tong_check_in' => 'int',
        'tong_check_out' => 'int',
        'cho_phep_check_in' => 'boolean',
        'cho_phep_check_out' => 'boolean',
        'yeu_cau_face_id' => 'boolean',
        'cho_phep_checkin_thu_cong' => 'boolean',
        'so_luong_tham_gia' => 'int',
        'so_luong_dien_gia' => 'int',
        'so_luot_xem' => 'int',
        'lich_trinh' => 'json',
        'version' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_sukien_slug' => ['slug']
    ];
    
    // Các quy tắc xác thực
    protected $validationRules = [
        'ten_su_kien' => [
            'rules' => 'required|max_length[255]',
            'label' => 'Tên sự kiện'
        ],
        'thoi_gian_bat_dau' => [
            'rules' => 'required|valid_date',
            'label' => 'Thời gian bắt đầu'
        ],
        'thoi_gian_ket_thuc' => [
            'rules' => 'required|valid_date',
            'label' => 'Thời gian kết thúc'
        ],
        'loai_su_kien_id' => [
            'rules' => 'required|integer|is_not_unique[loai_su_kien.loai_su_kien_id]',
            'label' => 'Loại sự kiện'
        ],
        'dia_diem' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Địa điểm'
        ],
        'dia_chi_cu_the' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Địa chỉ cụ thể'
        ],
        'toa_do_gps' => [
            'rules' => 'permit_empty|max_length[100]',
            'label' => 'Tọa độ GPS'
        ],
        'ma_qr_code' => [
            'rules' => 'permit_empty|max_length[100]',
            'label' => 'Mã QR code'
        ],
        'status' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'label' => 'Trạng thái'
        ],
        'slug' => [
            'rules' => 'permit_empty|alpha_dash|max_length[255]|is_unique[su_kien.slug,su_kien_id,{su_kien_id}]',
            'label' => 'Slug'
        ],
        'hinh_thuc' => [
            'rules' => 'permit_empty|in_list[offline,online,hybrid]',
            'label' => 'Hình thức'
        ],
        'link_online' => [
            'rules' => 'permit_empty|max_length[255]|valid_url_strict',
            'label' => 'Link tham gia online'
        ],
        'mat_khau_online' => [
            'rules' => 'permit_empty|max_length[100]',
            'label' => 'Mật khẩu tham gia online'
        ]
    ];
    
    protected $validationMessages = [
        'ten_su_kien' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'thoi_gian_bat_dau' => [
            'required' => '{field} là bắt buộc',
            'valid_date' => '{field} không hợp lệ'
        ],
        'thoi_gian_ket_thuc' => [
            'required' => '{field} là bắt buộc',
            'valid_date' => '{field} không hợp lệ'
        ],
        'loai_su_kien_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên',
            'is_not_unique' => '{field} không tồn tại trong hệ thống'
        ],
        'dia_diem' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'dia_chi_cu_the' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'toa_do_gps' => [
            'max_length' => '{field} không được vượt quá 100 ký tự'
        ],
        'ma_qr_code' => [
            'max_length' => '{field} không được vượt quá 100 ký tự'
        ],
        'status' => [
            'in_list' => '{field} không hợp lệ'
        ],
        'slug' => [
            'alpha_dash' => '{field} chỉ được chứa ký tự chữ cái, số, gạch ngang và gạch dưới',
            'max_length' => '{field} không được vượt quá 255 ký tự',
            'is_unique' => '{field} đã tồn tại'
        ],
        'hinh_thuc' => [
            'in_list' => '{field} không hợp lệ'
        ],
        'link_online' => [
            'max_length' => '{field} không được vượt quá 255 ký tự',
            'valid_url_strict' => '{field} phải là URL hợp lệ'
        ],
        'mat_khau_online' => [
            'max_length' => '{field} không được vượt quá 100 ký tự'
        ]
    ];
    
    /**
     * Lấy ID sự kiện
     * 
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên sự kiện
     * 
     * @return string
     */
    public function getTenSuKien(): string
    {
        return $this->attributes['ten_su_kien'] ?? '';
    }
    
    /**
     * Lấy thông tin poster sự kiện
     * 
     * @return array|null
     */
    public function getSuKienPoster(): ?array
    {
        $poster = $this->attributes['su_kien_poster'] ?? null;
        
        if (is_string($poster)) {
            return json_decode($poster, true);
        }
        
        return $poster;
    }
    
    /**
     * Lấy mô tả ngắn
     * 
     * @return string|null
     */
    public function getMoTa(): ?string
    {
        return $this->attributes['mo_ta'] ?? null;
    }
    
    /**
     * Lấy mô tả chi tiết
     * 
     * @return string|null
     */
    public function getMoTaSuKien(): ?string
    {
        return $this->attributes['mo_ta_su_kien'] ?? null;
    }
    
    /**
     * Lấy chi tiết sự kiện
     * 
     * @return string|null
     */
    public function getChiTietSuKien(): ?string
    {
        return $this->attributes['chi_tiet_su_kien'] ?? null;
    }
    
    /**
     * Lấy thời gian bắt đầu
     * 
     * @return Time|null
     */
    public function getThoiGianBatDau(): ?Time
    {
        if (empty($this->attributes['thoi_gian_bat_dau'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_bat_dau'] instanceof Time 
            ? $this->attributes['thoi_gian_bat_dau'] 
            : new Time($this->attributes['thoi_gian_bat_dau']);
    }
    
    /**
     * Lấy thời gian kết thúc
     * 
     * @return Time|null
     */
    public function getThoiGianKetThuc(): ?Time
    {
        if (empty($this->attributes['thoi_gian_ket_thuc'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_ket_thuc'] instanceof Time 
            ? $this->attributes['thoi_gian_ket_thuc'] 
            : new Time($this->attributes['thoi_gian_ket_thuc']);
    }
    
    /**
     * Lấy địa điểm tổ chức sự kiện
     * 
     * @return string|null
     */
    public function getDiaDiem(): ?string
    {
        return $this->attributes['dia_diem'] ?? null;
    }
    
    /**
     * Lấy địa chỉ cụ thể
     * 
     * @return string|null
     */
    public function getDiaChiCuThe(): ?string
    {
        return $this->attributes['dia_chi_cu_the'] ?? null;
    }
    
    /**
     * Lấy tọa độ GPS
     * 
     * @return string|null
     */
    public function getToaDoGPS(): ?string
    {
        return $this->attributes['toa_do_gps'] ?? null;
    }
    
    /**
     * Lấy ID loại sự kiện
     * 
     * @return int
     */
    public function getLoaiSuKienId(): int
    {
        return (int)($this->attributes['loai_su_kien_id'] ?? 0);
    }
    
    /**
     * Lấy mã QR code
     * 
     * @return string|null
     */
    public function getMaQRCode(): ?string
    {
        return $this->attributes['ma_qr_code'] ?? null;
    }
    
    /**
     * Lấy trạng thái sự kiện
     * 
     * @return bool
     */
    public function getStatus(): bool
    {
        return (bool)($this->attributes['status'] ?? true);
    }
    
    /**
     * Lấy tổng số người đăng ký
     * 
     * @return int
     */
    public function getTongDangKy(): int
    {
        return (int)($this->attributes['tong_dang_ky'] ?? 0);
    }
    
    /**
     * Lấy tổng số người đã check-in
     * 
     * @return int
     */
    public function getTongCheckIn(): int
    {
        return (int)($this->attributes['tong_check_in'] ?? 0);
    }
    
    /**
     * Lấy tổng số người đã check-out
     * 
     * @return int
     */
    public function getTongCheckOut(): int
    {
        return (int)($this->attributes['tong_check_out'] ?? 0);
    }
    
    /**
     * Kiểm tra cho phép check-in
     * 
     * @return bool
     */
    public function getChoPhepCheckIn(): bool
    {
        return (bool)($this->attributes['cho_phep_check_in'] ?? true);
    }
    
    /**
     * Kiểm tra cho phép check-out
     * 
     * @return bool
     */
    public function getChoPhepCheckOut(): bool
    {
        return (bool)($this->attributes['cho_phep_check_out'] ?? true);
    }
    
    /**
     * Kiểm tra yêu cầu xác thực khuôn mặt
     * 
     * @return bool
     */
    public function getYeuCauFaceId(): bool
    {
        return (bool)($this->attributes['yeu_cau_face_id'] ?? false);
    }
    
    /**
     * Kiểm tra cho phép check-in thủ công
     * 
     * @return bool
     */
    public function getChoPhepCheckinThuCong(): bool
    {
        return (bool)($this->attributes['cho_phep_checkin_thu_cong'] ?? true);
    }
    
    /**
     * Lấy thời gian bắt đầu đăng ký
     * 
     * @return Time|null
     */
    public function getBatDauDangKy(): ?Time
    {
        if (empty($this->attributes['bat_dau_dang_ky'])) {
            return null;
        }
        
        return $this->attributes['bat_dau_dang_ky'] instanceof Time 
            ? $this->attributes['bat_dau_dang_ky'] 
            : new Time($this->attributes['bat_dau_dang_ky']);
    }
    
    /**
     * Lấy thời gian kết thúc đăng ký
     * 
     * @return Time|null
     */
    public function getKetThucDangKy(): ?Time
    {
        if (empty($this->attributes['ket_thuc_dang_ky'])) {
            return null;
        }
        
        return $this->attributes['ket_thuc_dang_ky'] instanceof Time 
            ? $this->attributes['ket_thuc_dang_ky'] 
            : new Time($this->attributes['ket_thuc_dang_ky']);
    }
    
    /**
     * Lấy hạn chót hủy đăng ký
     * 
     * @return Time|null
     */
    public function getHanHuyDangKy(): ?Time
    {
        if (empty($this->attributes['han_huy_dang_ky'])) {
            return null;
        }
        
        return $this->attributes['han_huy_dang_ky'] instanceof Time 
            ? $this->attributes['han_huy_dang_ky'] 
            : new Time($this->attributes['han_huy_dang_ky']);
    }
    
    /**
     * Lấy giờ bắt đầu
     * 
     * @return Time|null
     */
    public function getGioBatDau(): ?Time
    {
        if (empty($this->attributes['gio_bat_dau'])) {
            return null;
        }
        
        return $this->attributes['gio_bat_dau'] instanceof Time 
            ? $this->attributes['gio_bat_dau'] 
            : new Time($this->attributes['gio_bat_dau']);
    }
    
    /**
     * Lấy giờ kết thúc
     * 
     * @return Time|null
     */
    public function getGioKetThuc(): ?Time
    {
        if (empty($this->attributes['gio_ket_thuc'])) {
            return null;
        }
        
        return $this->attributes['gio_ket_thuc'] instanceof Time 
            ? $this->attributes['gio_ket_thuc'] 
            : new Time($this->attributes['gio_ket_thuc']);
    }
    
    /**
     * Lấy số lượng tham gia
     * 
     * @return int
     */
    public function getSoLuongThamGia(): int
    {
        return (int)($this->attributes['so_luong_tham_gia'] ?? 0);
    }
    
    /**
     * Lấy số lượng diễn giả
     * 
     * @return int
     */
    public function getSoLuongDienGia(): int
    {
        return (int)($this->attributes['so_luong_dien_gia'] ?? 0);
    }
    
    /**
     * Lấy giới hạn loại người dùng
     * 
     * @return string|null
     */
    public function getGioiHanLoaiNguoiDung(): ?string
    {
        return $this->attributes['gioi_han_loai_nguoi_dung'] ?? null;
    }
    
    /**
     * Lấy từ khóa sự kiện
     * 
     * @return string|null
     */
    public function getTuKhoaSuKien(): ?string
    {
        return $this->attributes['tu_khoa_su_kien'] ?? null;
    }
    
    /**
     * Lấy hashtag
     * 
     * @return string|null
     */
    public function getHashtag(): ?string
    {
        return $this->attributes['hashtag'] ?? null;
    }
    
    /**
     * Lấy slug
     * 
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->attributes['slug'] ?? null;
    }
    
    /**
     * Lấy số lượt xem
     * 
     * @return int
     */
    public function getSoLuotXem(): int
    {
        return (int)($this->attributes['so_luot_xem'] ?? 0);
    }
    
    /**
     * Lấy lịch trình
     * 
     * @return array|null
     */
    public function getLichTrinh(): ?array
    {
        $lichTrinh = $this->attributes['lich_trinh'] ?? null;
        
        if (is_string($lichTrinh)) {
            return json_decode($lichTrinh, true);
        }
        
        return $lichTrinh;
    }
    
    /**
     * Lấy hình thức sự kiện
     * 
     * @return string
     */
    public function getHinhThuc(): string
    {
        return $this->attributes['hinh_thuc'] ?? 'offline';
    }
    
    /**
     * Kiểm tra sự kiện có trực tuyến không
     * 
     * @return bool
     */
    public function isOnline(): bool
    {
        return in_array($this->getHinhThuc(), ['online', 'hybrid']);
    }
    
    /**
     * Kiểm tra sự kiện có trực tiếp không
     * 
     * @return bool
     */
    public function isOffline(): bool
    {
        return in_array($this->getHinhThuc(), ['offline', 'hybrid']);
    }
    
    /**
     * Lấy link tham gia online
     * 
     * @return string|null
     */
    public function getLinkOnline(): ?string
    {
        return $this->attributes['link_online'] ?? null;
    }
    
    /**
     * Lấy mật khẩu tham gia online
     * 
     * @return string|null
     */
    public function getMatKhauOnline(): ?string
    {
        return $this->attributes['mat_khau_online'] ?? null;
    }
    
    /**
     * Lấy phiên bản
     * 
     * @return int
     */
    public function getVersion(): int
    {
        return (int)($this->attributes['version'] ?? 1);
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
     * Kiểm tra xem sự kiện đã bị xóa chưa
     * 
     * @return bool
     */
    public function isDeleted(): bool
    {
        return !empty($this->attributes['deleted_at']);
    }
    
    /**
     * Lấy định dạng thời gian bắt đầu
     * 
     * @param string $format Định dạng thời gian
     * @return string|null
     */
    public function getThoiGianBatDauFormatted(string $format = 'd/m/Y H:i'): ?string
    {
        $time = $this->getThoiGianBatDau();
        return $time ? $time->format($format) : null;
    }
    
    /**
     * Lấy định dạng thời gian kết thúc
     * 
     * @param string $format Định dạng thời gian
     * @return string|null
     */
    public function getThoiGianKetThucFormatted(string $format = 'd/m/Y H:i'): ?string
    {
        $time = $this->getThoiGianKetThuc();
        return $time ? $time->format($format) : null;
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
     * Lấy tên hiển thị của hình thức sự kiện
     * 
     * @return string
     */
    public function getHinhThucText(): string
    {
        $hinhThucMap = [
            'offline' => 'Trực tiếp',
            'online' => 'Trực tuyến',
            'hybrid' => 'Kết hợp'
        ];
        
        return $hinhThucMap[$this->getHinhThuc()] ?? 'Trực tiếp';
    }
    
    /**
     * Lấy trạng thái hiển thị
     * 
     * @return string
     */
    public function getStatusText(): string
    {
        return $this->getStatus() ? 'Hoạt động' : 'Không hoạt động';
    }
    
    /**
     * Kiểm tra sự kiện có đang diễn ra
     * 
     * @return bool
     */
    public function isOngoing(): bool
    {
        $now = Time::now();
        $start = $this->getThoiGianBatDau();
        $end = $this->getThoiGianKetThuc();
        
        return $start && $end && $now->isAfter($start) && $now->isBefore($end);
    }
    
    /**
     * Kiểm tra sự kiện sắp diễn ra
     * 
     * @return bool
     */
    public function isUpcoming(): bool
    {
        $now = Time::now();
        $start = $this->getThoiGianBatDau();
        
        return $start && $now->isBefore($start);
    }
    
    /**
     * Kiểm tra sự kiện đã kết thúc
     * 
     * @return bool
     */
    public function isEnded(): bool
    {
        $now = Time::now();
        $end = $this->getThoiGianKetThuc();
        
        return $end && $now->isAfter($end);
    }
} 