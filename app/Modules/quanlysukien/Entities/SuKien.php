<?php

namespace App\Modules\quanlysukien\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;
use App\Modules\quanlyloaisukien\Entities\LoaiSuKien;
use App\Modules\dangkysukien\Entities\DangKySuKien;
use App\Modules\checkinsukien\Entities\CheckInSuKien;

class SuKien extends BaseEntity
{
    protected $tableName = 'su_kien';
    protected $primaryKey = 'su_kien_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'thoi_gian_bat_dau_su_kien',
        'thoi_gian_ket_thuc_su_kien',
        'thoi_gian_bat_dau_dang_ky',
        'thoi_gian_ket_thuc_dang_ky',
        'thoi_gian_checkin_bat_dau',
        'thoi_gian_checkin_ket_thuc',
        'thoi_gian_checkout_bat_dau',
        'thoi_gian_checkout_ket_thuc',
        'han_huy_dang_ky'
    ];
    
    protected $casts = [
        'su_kien_id' => 'int',
        'ten_su_kien' => 'string',
        'mo_ta' => 'string',
        'mo_ta_su_kien' => 'string',
        'chi_tiet_su_kien' => 'string',
        'don_vi_to_chuc' => 'string',
        'don_vi_phoi_hop' => 'string',
        'doi_tuong_tham_gia' => 'string',
        'dia_diem' => 'string',
        'dia_chi_cu_the' => 'string',
        'toa_do_gps' => 'string',
        'loai_su_kien_id' => 'int',
        'ma_qr_code' => 'string',
        'status' => 'int',
        'tong_dang_ky' => 'int',
        'tong_check_in' => 'int',
        'tong_check_out' => 'int',
        'cho_phep_check_in' => 'bool',
        'cho_phep_check_out' => 'bool',
        'yeu_cau_face_id' => 'bool',
        'cho_phep_checkin_thu_cong' => 'bool',
        'so_luong_tham_gia' => 'int',
        'so_luong_dien_gia' => 'int',
        'gioi_han_loai_nguoi_dung' => 'string',
        'tu_khoa_su_kien' => 'string',
        'hashtag' => 'string',
        'slug' => 'string',
        'so_luot_xem' => 'int',
        'hinh_thuc' => 'string',
        'link_online' => 'string',
        'mat_khau_online' => 'string',
        'version' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    protected $jsonFields = [
        'su_kien_poster',
        'lich_trinh'
    ];
    
    // Quy tắc xác thực cho SuKien
    protected $validationRules = [
        'ten_su_kien' => [
            'rules' => 'required|string|max_length[255]',
            'label' => 'Tên sự kiện'
        ],
        'thoi_gian_bat_dau_su_kien' => [
            'rules' => 'required',
            'label' => 'Thời gian bắt đầu sự kiện'
        ],
        'thoi_gian_ket_thuc_su_kien' => [
            'rules' => 'required',
            'label' => 'Thời gian kết thúc sự kiện'
        ],
        'thoi_gian_bat_dau_dang_ky' => [
            'rules' => 'permit_empty',
            'label' => 'Thời gian bắt đầu đăng ký'
        ],
        'thoi_gian_ket_thuc_dang_ky' => [
            'rules' => 'permit_empty',
            'label' => 'Thời gian kết thúc đăng ký'
        ],
        'loai_su_kien_id' => [
            'rules' => 'required|integer',
            'label' => 'Loại sự kiện'
        ],
        'status' => [
            'rules' => 'required|integer|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ten_su_kien' => [
            'required' => '{field} là bắt buộc',
            'string' => '{field} phải là chuỗi',
            'max_length' => '{field} không được vượt quá {param} ký tự'
        ],
        'thoi_gian_bat_dau_su_kien' => [
            'required' => '{field} là bắt buộc'
        ],
        'thoi_gian_ket_thuc_su_kien' => [
            'required' => '{field} là bắt buộc'
        ],
        'thoi_gian_bat_dau_dang_ky' => [
            'permit_empty' => '{field} có thể để trống'
        ],
        'thoi_gian_ket_thuc_dang_ky' => [
            'permit_empty' => '{field} có thể để trống'
        ],
        'loai_su_kien_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên'
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
     * Lấy tên sự kiện
     *
     * @return string|null
     */
    public function getTenSuKien(): ?string
    {
        return $this->attributes['ten_su_kien'] ?? null;
    }

    /**
     * Lấy thông tin poster sự kiện
     *
     * @return array|null
     */
    public function getSuKienPoster(): ?array
    {
        if (empty($this->attributes['su_kien_poster'])) {
            return null;
        }
        
        if (is_string($this->attributes['su_kien_poster'])) {
            return json_decode($this->attributes['su_kien_poster'], true);
        }
        
        return $this->attributes['su_kien_poster'];
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
     * Lấy mô tả sự kiện
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
     * Lấy thời gian bắt đầu sự kiện
     *
     * @return Time|null
     */
    public function getThoiGianBatDauSuKien(): ?Time
    {
        if (empty($this->attributes['thoi_gian_bat_dau_su_kien'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_bat_dau_su_kien'] instanceof Time 
            ? $this->attributes['thoi_gian_bat_dau_su_kien'] 
            : new Time($this->attributes['thoi_gian_bat_dau_su_kien']);
    }
    
    /**
     * Lấy thời gian kết thúc sự kiện
     *
     * @return Time|null
     */
    public function getThoiGianKetThucSuKien(): ?Time
    {
        if (empty($this->attributes['thoi_gian_ket_thuc_su_kien'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_ket_thuc_su_kien'] instanceof Time 
            ? $this->attributes['thoi_gian_ket_thuc_su_kien'] 
            : new Time($this->attributes['thoi_gian_ket_thuc_su_kien']);
    }
    
    /**
     * Lấy thời gian bắt đầu đăng ký
     *
     * @return Time|null
     */
    public function getThoiGianBatDauDangKy(): ?Time
    {
        if (empty($this->attributes['thoi_gian_bat_dau_dang_ky'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_bat_dau_dang_ky'] instanceof Time 
            ? $this->attributes['thoi_gian_bat_dau_dang_ky'] 
            : new Time($this->attributes['thoi_gian_bat_dau_dang_ky']);
    }
    
    /**
     * Lấy thời gian kết thúc đăng ký
     *
     * @return Time|null
     */
    public function getThoiGianKetThucDangKy(): ?Time
    {
        if (empty($this->attributes['thoi_gian_ket_thuc_dang_ky'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_ket_thuc_dang_ky'] instanceof Time 
            ? $this->attributes['thoi_gian_ket_thuc_dang_ky'] 
            : new Time($this->attributes['thoi_gian_ket_thuc_dang_ky']);
    }
    
    /**
     * Lấy thời gian bắt đầu check-in
     *
     * @return Time|null
     */
    public function getThoiGianCheckinBatDau(): ?Time
    {
        if (empty($this->attributes['thoi_gian_checkin_bat_dau'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_checkin_bat_dau'] instanceof Time 
            ? $this->attributes['thoi_gian_checkin_bat_dau'] 
            : new Time($this->attributes['thoi_gian_checkin_bat_dau']);
    }
    
    /**
     * Lấy thời gian kết thúc check-in
     *
     * @return Time|null
     */
    public function getThoiGianCheckinKetThuc(): ?Time
    {
        if (empty($this->attributes['thoi_gian_checkin_ket_thuc'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_checkin_ket_thuc'] instanceof Time 
            ? $this->attributes['thoi_gian_checkin_ket_thuc'] 
            : new Time($this->attributes['thoi_gian_checkin_ket_thuc']);
    }
    
    /**
     * Lấy thời gian bắt đầu check-out
     *
     * @return Time|null
     */
    public function getThoiGianCheckoutBatDau(): ?Time
    {
        if (empty($this->attributes['thoi_gian_checkout_bat_dau'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_checkout_bat_dau'] instanceof Time 
            ? $this->attributes['thoi_gian_checkout_bat_dau'] 
            : new Time($this->attributes['thoi_gian_checkout_bat_dau']);
    }
    
    /**
     * Lấy thời gian kết thúc check-out
     *
     * @return Time|null
     */
    public function getThoiGianCheckoutKetThuc(): ?Time
    {
        if (empty($this->attributes['thoi_gian_checkout_ket_thuc'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_checkout_ket_thuc'] instanceof Time 
            ? $this->attributes['thoi_gian_checkout_ket_thuc'] 
            : new Time($this->attributes['thoi_gian_checkout_ket_thuc']);
    }
    
    /**
     * Lấy đơn vị tổ chức
     *
     * @return string|null
     */
    public function getDonViToChuc(): ?string
    {
        return $this->attributes['don_vi_to_chuc'] ?? null;
    }
    
    /**
     * Lấy đơn vị phối hợp
     *
     * @return string|null
     */
    public function getDonViPhoiHop(): ?string
    {
        return $this->attributes['don_vi_phoi_hop'] ?? null;
    }
    
    /**
     * Lấy đối tượng tham gia
     *
     * @return string|null
     */
    public function getDoiTuongThamGia(): ?string
    {
        return $this->attributes['doi_tuong_tham_gia'] ?? null;
    }
    
    /**
     * Kiểm tra xem hiện tại có đang trong thời gian cho phép check-in không
     *
     * @return bool
     */
    public function isCheckinTime(): bool
    {
        $now = Time::now();
        
        // Nếu không có thời gian check-in cụ thể, sử dụng thời gian diễn ra sự kiện
        if (empty($this->attributes['thoi_gian_checkin_bat_dau']) || empty($this->attributes['thoi_gian_checkin_ket_thuc'])) {
            return (
                $now >= $this->getThoiGianBatDauSuKien() && 
                $now <= $this->getThoiGianKetThucSuKien() && 
                (bool)($this->attributes['cho_phep_check_in'] ?? false)
            );
        }
        
        return (
            $now >= $this->getThoiGianCheckinBatDau() && 
            $now <= $this->getThoiGianCheckinKetThuc() && 
            (bool)($this->attributes['cho_phep_check_in'] ?? false)
        );
    }
    
    /**
     * Kiểm tra xem hiện tại có đang trong thời gian cho phép check-out không
     *
     * @return bool
     */
    public function isCheckoutTime(): bool
    {
        $now = Time::now();
        
        // Nếu không có thời gian check-out cụ thể, sử dụng thời gian diễn ra sự kiện
        if (empty($this->attributes['thoi_gian_checkout_bat_dau']) || empty($this->attributes['thoi_gian_checkout_ket_thuc'])) {
            return (
                $now >= $this->getThoiGianBatDauSuKien() && 
                $now <= $this->getThoiGianKetThucSuKien() && 
                (bool)($this->attributes['cho_phep_check_out'] ?? false)
            );
        }
        
        return (
            $now >= $this->getThoiGianCheckoutBatDau() && 
            $now <= $this->getThoiGianCheckoutKetThuc() && 
            (bool)($this->attributes['cho_phep_check_out'] ?? false)
        );
    }
    
    /**
     * Kiểm tra xem hiện tại có đang trong thời gian cho phép đăng ký không
     *
     * @return bool
     */
    public function isRegistrationTime(): bool
    {
        $now = Time::now();
        
        if (empty($this->attributes['thoi_gian_bat_dau_dang_ky']) || empty($this->attributes['thoi_gian_ket_thuc_dang_ky'])) {
            return false;
        }
        
        return (
            $now >= $this->getThoiGianBatDauDangKy() && 
            $now <= $this->getThoiGianKetThucDangKy()
        );
    }
    
    /**
     * Kiểm tra xem đã quá hạn hủy đăng ký chưa
     *
     * @return bool
     */
    public function isPassedCancellationDeadline(): bool
    {
        $now = Time::now();
        $hanHuyDangKy = $this->getHanHuyDangKy();
        
        if (empty($hanHuyDangKy)) {
            // Nếu không có hạn hủy, sử dụng thời gian bắt đầu sự kiện
            return $now >= $this->getThoiGianBatDauSuKien();
        }
        
        return $now > $hanHuyDangKy;
    }
    
    /**
     * Lấy thời gian bắt đầu sự kiện dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getThoiGianBatDauSuKienFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $thoiGianBatDau = $this->getThoiGianBatDauSuKien();
        if (empty($thoiGianBatDau)) {
            return '';
        }
        
        return $thoiGianBatDau->format($format);
    }
    
    /**
     * Lấy thời gian kết thúc sự kiện dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getThoiGianKetThucSuKienFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $thoiGianKetThuc = $this->getThoiGianKetThucSuKien();
        if (empty($thoiGianKetThuc)) {
            return '';
        }
        
        return $thoiGianKetThuc->format($format);
    }
    
    /**
     * Lấy thời gian bắt đầu đăng ký dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getThoiGianBatDauDangKyFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $thoiGianBatDauDangKy = $this->getThoiGianBatDauDangKy();
        if (empty($thoiGianBatDauDangKy)) {
            return '';
        }
        
        return $thoiGianBatDauDangKy->format($format);
    }
    
    /**
     * Lấy thời gian kết thúc đăng ký dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getThoiGianKetThucDangKyFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $thoiGianKetThucDangKy = $this->getThoiGianKetThucDangKy();
        if (empty($thoiGianKetThucDangKy)) {
            return '';
        }
        
        return $thoiGianKetThucDangKy->format($format);
    }
    
    /**
     * Lấy thời gian bắt đầu check-out dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getThoiGianCheckoutBatDauFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $thoiGianCheckoutBatDau = $this->getThoiGianCheckoutBatDau();
        if (empty($thoiGianCheckoutBatDau)) {
            return '';
        }
        
        return $thoiGianCheckoutBatDau->format($format);
    }
    
    /**
     * Lấy thời gian kết thúc check-out dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getThoiGianCheckoutKetThucFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $thoiGianCheckoutKetThuc = $this->getThoiGianCheckoutKetThuc();
        if (empty($thoiGianCheckoutKetThuc)) {
            return '';
        }
        
        return $thoiGianCheckoutKetThuc->format($format);
    }
    
    /**
     * Lấy thời gian bắt đầu check-in dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getThoiGianCheckinBatDauFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $thoiGianCheckinBatDau = $this->getThoiGianCheckinBatDau();
        if (empty($thoiGianCheckinBatDau)) {
            return '';
        }
        
        return $thoiGianCheckinBatDau->format($format);
    }
    
    /**
     * Lấy thời gian kết thúc check-in dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getThoiGianCheckinKetThucFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $thoiGianCheckinKetThuc = $this->getThoiGianCheckinKetThuc();
        if (empty($thoiGianCheckinKetThuc)) {
            return '';
        }
        
        return $thoiGianCheckinKetThuc->format($format);
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
     * Lấy hạn chót hủy đăng ký dưới dạng định dạng chuỗi
     *
     * @param string $format Định dạng ngày
     * @return string
     */
    public function getHanHuyDangKyFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        $hanHuyDangKy = $this->getHanHuyDangKy();
        if (empty($hanHuyDangKy)) {
            return '';
        }
        
        return $hanHuyDangKy->format($format);
    }
    
    /**
     * Lấy địa điểm
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
     * Lấy mã sự kiện
     *
     * @return string|null
     */
    public function getMaSuKien(): ?string
    {
        return $this->attributes['ma_su_kien'] ?? null;
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
     * Lấy trạng thái
     *
     * @return int
     */
    public function getStatus(): int
    {
        return (int)($this->attributes['status'] ?? 0);
    }
    
    /**
     * Lấy tổng số đăng ký
     *
     * @return int
     */
    public function getTongDangKy(): int
    {
        return (int)($this->attributes['tong_dang_ky'] ?? 0);
    }
    
    /**
     * Lấy tổng số check-in
     *
     * @return int
     */
    public function getTongCheckIn(): int
    {
        return (int)($this->attributes['tong_check_in'] ?? 0);
    }
    
    /**
     * Lấy tổng số check-out
     *
     * @return int
     */
    public function getTongCheckOut(): int
    {
        return (int)($this->attributes['tong_check_out'] ?? 0);
    }
    
    /**
     * Kiểm tra có cho phép check-in không
     *
     * @return bool
     */
    public function isAllowCheckIn(): bool
    {
        return (bool)($this->attributes['cho_phep_check_in'] ?? false);
    }
    
    /**
     * Kiểm tra có cho phép check-out không
     *
     * @return bool
     */
    public function isAllowCheckOut(): bool
    {
        return (bool)($this->attributes['cho_phep_check_out'] ?? false);
    }
    
    /**
     * Kiểm tra có yêu cầu face ID không
     *
     * @return bool
     */
    public function isRequireFaceId(): bool
    {
        return (bool)($this->attributes['yeu_cau_face_id'] ?? false);
    }
    
    /**
     * Kiểm tra có cho phép check-in thủ công không
     *
     * @return bool
     */
    public function isAllowManualCheckin(): bool
    {
        return (bool)($this->attributes['cho_phep_checkin_thu_cong'] ?? false);
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
        if (empty($this->attributes['lich_trinh'])) {
            return null;
        }
        
        if (is_string($this->attributes['lich_trinh'])) {
            return json_decode($this->attributes['lich_trinh'], true);
        }
        
        return $this->attributes['lich_trinh'];
    }
    
    /**
     * Lấy hình thức sự kiện
     *
     * @return string|null
     */
    public function getHinhThuc(): ?string
    {
        return $this->attributes['hinh_thuc'] ?? 'offline';
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
            return '<span class="badge badge-success" style="color: green;">Hoạt động</span>';
        }
        return '<span class="badge badge-danger" style="color: red;">Không hoạt động</span>';
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
    
    /**
     * Lấy thông tin loại sự kiện
     *
     * @return LoaiSuKien|null
     */
    public function getLoaiSuKien(): ?LoaiSuKien
    {
        $loaiSuKienModel = model('App\Modules\quanlyloaisukien\Models\LoaiSuKienModel');
        return $loaiSuKienModel->find($this->getLoaiSuKienId());
    }
    
    /**
     * Lấy URL chỉnh sửa
     *
     * @return string
     */
    public function getEditUrl(): string
    {
        return site_url('quanlysukien/edit/' . $this->getId());
    }

    /**
     * Lấy URL xem chi tiết
     *
     * @return string
     */
    public function getDetailUrl(): string
    {
        return site_url('quanlysukien/detail/' . $this->getId());
    }

    /**
     * Lấy URL xóa
     *
     * @return string
     */
    public function getDeleteUrl(): string
    {
        return site_url('quanlysukien/delete/' . $this->getId());
    }

    /**
     * Lấy URL khôi phục
     *
     * @return string
     */
    public function getRestoreUrl(): string
    {
        return site_url('quanlysukien/restore/' . $this->getId());
    }

    /**
     * Lấy URL xóa vĩnh viễn
     *
     * @return string
     */
    public function getPermanentDeleteUrl(): string
    {
        return site_url('quanlysukien/permanentDelete/' . $this->getId());
    }

    /**
     * Lấy tên loại sự kiện dựa trên ID loại sự kiện
     * 
     * @return string|null
     */
    public function getTenLoaiSuKien(): ?string
    {
        if (empty($this->attributes['loai_su_kien_id'])) {
            return null;
        }
        
        try {
            $loaiSuKienModel = model('App\Modules\quanlyloaisukien\Models\LoaiSuKienModel');
            $loaiSuKien = $loaiSuKienModel->find($this->attributes['loai_su_kien_id']);
            
            if ($loaiSuKien) {
                return $loaiSuKien->getTenLoaiSuKien();
            }
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi lấy tên loại sự kiện: ' . $e->getMessage());
        }
        
        return null;
    }
}