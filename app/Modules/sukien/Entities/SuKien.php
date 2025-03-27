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
        'nguoi_tao_id' => 'int',
        'status' => 'int',
        'tong_dang_ky' => 'int',
        'tong_check_in' => 'int',
        'tong_check_out' => 'int',
        'cho_phep_check_in' => 'boolean',
        'cho_phep_check_out' => 'boolean',
        'yeu_cau_face_id' => 'boolean',
        'cho_phep_checkin_thu_cong' => 'boolean',
        'tu_dong_xac_nhan_svgv' => 'boolean',
        'yeu_cau_duyet_khach' => 'boolean',
        'so_luong_tham_gia' => 'int',
        'so_luong_dien_gia' => 'int',
        'so_luot_xem' => 'int',
        'version' => 'int',
        'su_kien_poster' => 'json',
        'lich_trinh' => 'json'
    ];
    
    // Định nghĩa các chỉ mục
    protected $indexes = [
        'idx_ten_su_kien' => ['ten_su_kien'],
        'idx_thoi_gian_bat_dau' => ['thoi_gian_bat_dau'],
        'idx_thoi_gian_ket_thuc' => ['thoi_gian_ket_thuc'],
        'idx_loai_su_kien_id' => ['loai_su_kien_id'],
        'idx_nguoi_tao_id' => ['nguoi_tao_id'],
        'idx_sukien_slug' => ['slug'],
        'idx_sukien_bat_dau' => ['gio_bat_dau']
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_ma_nganh' => ['ma_nganh']
    ];
    
    // Các quy tắc xác thực cụ thể cho SuKien
    protected $validationRules = [
        'ten_su_kien' => [
            'rules' => 'required|max_length[255]',
            'label' => 'Tên sự kiện'
        ],
        'thoi_gian_bat_dau' => [
            'rules' => 'required',
            'label' => 'Thời gian bắt đầu'
        ],
        'thoi_gian_ket_thuc' => [
            'rules' => 'required',
            'label' => 'Thời gian kết thúc'
        ],
        'loai_su_kien_id' => [
            'rules' => 'required|integer',
            'label' => 'Loại sự kiện'
        ],
        'nguoi_tao_id' => [
            'rules' => 'required|integer',
            'label' => 'Người tạo'
        ],
        'status' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'label' => 'Trạng thái'
        ],
        'slug' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Slug'
        ]
    ];
    
    protected $validationMessages = [
        'ten_su_kien' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'thoi_gian_bat_dau' => [
            'required' => '{field} là bắt buộc'
        ],
        'thoi_gian_ket_thuc' => [
            'required' => '{field} là bắt buộc'
        ],
        'loai_su_kien_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên'
        ],
        'nguoi_tao_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên'
        ],
        'status' => [
            'in_list' => '{field} không hợp lệ'
        ],
        'slug' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ]
    ];
    
    /**
     * Lấy ID của sự kiện
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
     * Đặt tên sự kiện
     *
     * @param string $tenSuKien
     * @return $this
     */
    public function setTenSuKien(string $tenSuKien)
    {
        $this->attributes['ten_su_kien'] = $tenSuKien;
        return $this;
    }
    
    /**
     * Lấy poster sự kiện
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
     * Đặt poster sự kiện
     *
     * @param array|string|null $suKienPoster
     * @return $this
     */
    public function setSuKienPoster($suKienPoster)
    {
        if (is_array($suKienPoster)) {
            $suKienPoster = json_encode($suKienPoster);
        }
        
        $this->attributes['su_kien_poster'] = $suKienPoster;
        return $this;
    }
    
    /**
     * Lấy mô tả
     *
     * @return string|null
     */
    public function getMoTa(): ?string
    {
        return $this->attributes['mo_ta'] ?? null;
    }
    
    /**
     * Đặt mô tả
     *
     * @param string|null $moTa
     * @return $this
     */
    public function setMoTa(?string $moTa)
    {
        $this->attributes['mo_ta'] = $moTa;
        return $this;
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
     * Đặt mô tả sự kiện
     *
     * @param string|null $moTaSuKien
     * @return $this
     */
    public function setMoTaSuKien(?string $moTaSuKien)
    {
        $this->attributes['mo_ta_su_kien'] = $moTaSuKien;
        return $this;
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
     * Đặt chi tiết sự kiện
     *
     * @param string|null $chiTietSuKien
     * @return $this
     */
    public function setChiTietSuKien(?string $chiTietSuKien)
    {
        $this->attributes['chi_tiet_su_kien'] = $chiTietSuKien;
        return $this;
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
     * Đặt thời gian bắt đầu
     *
     * @param string|Time $thoiGianBatDau
     * @return $this
     */
    public function setThoiGianBatDau($thoiGianBatDau)
    {
        $this->attributes['thoi_gian_bat_dau'] = $thoiGianBatDau;
        return $this;
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
     * Đặt thời gian kết thúc
     *
     * @param string|Time $thoiGianKetThuc
     * @return $this
     */
    public function setThoiGianKetThuc($thoiGianKetThuc)
    {
        $this->attributes['thoi_gian_ket_thuc'] = $thoiGianKetThuc;
        return $this;
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
     * Đặt địa điểm
     *
     * @param string|null $diaDiem
     * @return $this
     */
    public function setDiaDiem(?string $diaDiem)
    {
        $this->attributes['dia_diem'] = $diaDiem;
        return $this;
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
     * Đặt địa chỉ cụ thể
     *
     * @param string|null $diaChiCuThe
     * @return $this
     */
    public function setDiaChiCuThe(?string $diaChiCuThe)
    {
        $this->attributes['dia_chi_cu_the'] = $diaChiCuThe;
        return $this;
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
     * Đặt tọa độ GPS
     *
     * @param string|null $toaDoGPS
     * @return $this
     */
    public function setToaDoGPS(?string $toaDoGPS)
    {
        $this->attributes['toa_do_gps'] = $toaDoGPS;
        return $this;
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
     * Đặt ID loại sự kiện
     *
     * @param int $loaiSuKienId
     * @return $this
     */
    public function setLoaiSuKienId(int $loaiSuKienId)
    {
        $this->attributes['loai_su_kien_id'] = $loaiSuKienId;
        return $this;
    }
    
    /**
     * Lấy ID người tạo
     *
     * @return int
     */
    public function getNguoiTaoId(): int
    {
        return (int)($this->attributes['nguoi_tao_id'] ?? 0);
    }
    
    /**
     * Đặt ID người tạo
     *
     * @param int $nguoiTaoId
     * @return $this
     */
    public function setNguoiTaoId(int $nguoiTaoId)
    {
        $this->attributes['nguoi_tao_id'] = $nguoiTaoId;
        return $this;
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
     * Đặt mã QR code
     *
     * @param string|null $maQRCode
     * @return $this
     */
    public function setMaQRCode(?string $maQRCode)
    {
        $this->attributes['ma_qr_code'] = $maQRCode;
        return $this;
    }
    
    /**
     * Kiểm tra trạng thái
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? true);
    }
    
    /**
     * Đặt trạng thái
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
     * Lấy tổng số đăng ký
     *
     * @return int
     */
    public function getTongDangKy(): int
    {
        return (int)($this->attributes['tong_dang_ky'] ?? 0);
    }
    
    /**
     * Đặt tổng số đăng ký
     *
     * @param int $tongDangKy
     * @return $this
     */
    public function setTongDangKy(int $tongDangKy)
    {
        $this->attributes['tong_dang_ky'] = $tongDangKy;
        return $this;
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
     * Đặt tổng số check-in
     *
     * @param int $tongCheckIn
     * @return $this
     */
    public function setTongCheckIn(int $tongCheckIn)
    {
        $this->attributes['tong_check_in'] = $tongCheckIn;
        return $this;
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
     * Đặt tổng số check-out
     *
     * @param int $tongCheckOut
     * @return $this
     */
    public function setTongCheckOut(int $tongCheckOut)
    {
        $this->attributes['tong_check_out'] = $tongCheckOut;
        return $this;
    }
    
    /**
     * Kiểm tra cho phép check-in
     *
     * @return bool
     */
    public function isChoPhepCheckIn(): bool
    {
        return (bool)($this->attributes['cho_phep_check_in'] ?? false);
    }
    
    /**
     * Đặt cho phép check-in
     *
     * @param bool $choPhepCheckIn
     * @return $this
     */
    public function setChoPhepCheckIn(bool $choPhepCheckIn)
    {
        $this->attributes['cho_phep_check_in'] = $choPhepCheckIn;
        return $this;
    }
    
    /**
     * Kiểm tra cho phép check-out
     *
     * @return bool
     */
    public function isChoPhepCheckOut(): bool
    {
        return (bool)($this->attributes['cho_phep_check_out'] ?? false);
    }
    
    /**
     * Đặt cho phép check-out
     *
     * @param bool $choPhepCheckOut
     * @return $this
     */
    public function setChoPhepCheckOut(bool $choPhepCheckOut)
    {
        $this->attributes['cho_phep_check_out'] = $choPhepCheckOut;
        return $this;
    }
    
    /**
     * Kiểm tra yêu cầu face ID
     *
     * @return bool
     */
    public function isYeuCauFaceId(): bool
    {
        return (bool)($this->attributes['yeu_cau_face_id'] ?? false);
    }
    
    /**
     * Đặt yêu cầu face ID
     *
     * @param bool $yeuCauFaceId
     * @return $this
     */
    public function setYeuCauFaceId(bool $yeuCauFaceId)
    {
        $this->attributes['yeu_cau_face_id'] = $yeuCauFaceId;
        return $this;
    }
    
    /**
     * Kiểm tra cho phép check-in thủ công
     *
     * @return bool
     */
    public function isChoPhepCheckinThuCong(): bool
    {
        return (bool)($this->attributes['cho_phep_checkin_thu_cong'] ?? false);
    }
    
    /**
     * Đặt cho phép check-in thủ công
     *
     * @param bool $choPhepCheckinThuCong
     * @return $this
     */
    public function setChoPhepCheckinThuCong(bool $choPhepCheckinThuCong)
    {
        $this->attributes['cho_phep_checkin_thu_cong'] = $choPhepCheckinThuCong;
        return $this;
    }
    
    /**
     * Kiểm tra tự động xác nhận sinh viên/giảng viên
     *
     * @return bool
     */
    public function isTuDongXacNhanSVGV(): bool
    {
        return (bool)($this->attributes['tu_dong_xac_nhan_svgv'] ?? true);
    }
    
    /**
     * Đặt tự động xác nhận sinh viên/giảng viên
     *
     * @param bool $tuDongXacNhanSVGV
     * @return $this
     */
    public function setTuDongXacNhanSVGV(bool $tuDongXacNhanSVGV)
    {
        $this->attributes['tu_dong_xac_nhan_svgv'] = $tuDongXacNhanSVGV;
        return $this;
    }
    
    /**
     * Kiểm tra yêu cầu duyệt khách
     *
     * @return bool
     */
    public function isYeuCauDuyetKhach(): bool
    {
        return (bool)($this->attributes['yeu_cau_duyet_khach'] ?? true);
    }
    
    /**
     * Đặt yêu cầu duyệt khách
     *
     * @param bool $yeuCauDuyetKhach
     * @return $this
     */
    public function setYeuCauDuyetKhach(bool $yeuCauDuyetKhach)
    {
        $this->attributes['yeu_cau_duyet_khach'] = $yeuCauDuyetKhach;
        return $this;
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
     * Đặt thời gian bắt đầu đăng ký
     *
     * @param string|Time|null $batDauDangKy
     * @return $this
     */
    public function setBatDauDangKy($batDauDangKy)
    {
        $this->attributes['bat_dau_dang_ky'] = $batDauDangKy;
        return $this;
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
     * Đặt thời gian kết thúc đăng ký
     *
     * @param string|Time|null $ketThucDangKy
     * @return $this
     */
    public function setKetThucDangKy($ketThucDangKy)
    {
        $this->attributes['ket_thuc_dang_ky'] = $ketThucDangKy;
        return $this;
    }
    
    /**
     * Lấy hạn hủy đăng ký
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
     * Đặt hạn hủy đăng ký
     *
     * @param string|Time|null $hanHuyDangKy
     * @return $this
     */
    public function setHanHuyDangKy($hanHuyDangKy)
    {
        $this->attributes['han_huy_dang_ky'] = $hanHuyDangKy;
        return $this;
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
     * Đặt giờ bắt đầu
     *
     * @param string|Time|null $gioBatDau
     * @return $this
     */
    public function setGioBatDau($gioBatDau)
    {
        $this->attributes['gio_bat_dau'] = $gioBatDau;
        return $this;
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
     * Đặt giờ kết thúc
     *
     * @param string|Time|null $gioKetThuc
     * @return $this
     */
    public function setGioKetThuc($gioKetThuc)
    {
        $this->attributes['gio_ket_thuc'] = $gioKetThuc;
        return $this;
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
     * Đặt số lượng tham gia
     *
     * @param int $soLuongThamGia
     * @return $this
     */
    public function setSoLuongThamGia(int $soLuongThamGia)
    {
        $this->attributes['so_luong_tham_gia'] = $soLuongThamGia;
        return $this;
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
     * Đặt số lượng diễn giả
     *
     * @param int $soLuongDienGia
     * @return $this
     */
    public function setSoLuongDienGia(int $soLuongDienGia)
    {
        $this->attributes['so_luong_dien_gia'] = $soLuongDienGia;
        return $this;
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
     * Đặt giới hạn loại người dùng
     *
     * @param string|null $gioiHanLoaiNguoiDung
     * @return $this
     */
    public function setGioiHanLoaiNguoiDung(?string $gioiHanLoaiNguoiDung)
    {
        $this->attributes['gioi_han_loai_nguoi_dung'] = $gioiHanLoaiNguoiDung;
        return $this;
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
     * Đặt từ khóa sự kiện
     *
     * @param string|null $tuKhoaSuKien
     * @return $this
     */
    public function setTuKhoaSuKien(?string $tuKhoaSuKien)
    {
        $this->attributes['tu_khoa_su_kien'] = $tuKhoaSuKien;
        return $this;
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
     * Đặt hashtag
     *
     * @param string|null $hashtag
     * @return $this
     */
    public function setHashtag(?string $hashtag)
    {
        $this->attributes['hashtag'] = $hashtag;
        return $this;
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
     * Đặt slug
     *
     * @param string|null $slug
     * @return $this
     */
    public function setSlug(?string $slug)
    {
        $this->attributes['slug'] = $slug;
        return $this;
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
     * Đặt số lượt xem
     *
     * @param int $soLuotXem
     * @return $this
     */
    public function setSoLuotXem(int $soLuotXem)
    {
        $this->attributes['so_luot_xem'] = $soLuotXem;
        return $this;
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
     * Đặt lịch trình
     *
     * @param array|string|null $lichTrinh
     * @return $this
     */
    public function setLichTrinh($lichTrinh)
    {
        if (is_array($lichTrinh)) {
            $lichTrinh = json_encode($lichTrinh);
        }
        
        $this->attributes['lich_trinh'] = $lichTrinh;
        return $this;
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
     * Đặt phiên bản
     *
     * @param int $version
     * @return $this
     */
    public function setVersion(int $version)
    {
        $this->attributes['version'] = $version;
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