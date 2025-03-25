<?php

namespace App\Modules\sukien\Entities;

use CodeIgniter\Entity\Entity;

class SukienEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = [
        'bat_dau_dang_ky', 
        'ket_thuc_dang_ky', 
        'gio_bat_dau', 
        'gio_ket_thuc',
        'han_huy_dang_ky',
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];
    protected $casts   = [
        'su_kien_id'              => 'int',
        'loai_su_kien_id'           => 'int',
        'nguoi_tao_id'              => 'int',
        'so_luong_tham_gia'         => 'int',
        'so_luong_dien_gia'         => 'int',
        'so_luot_xem'               => 'int',
        'status'                    => 'int',
        'bin'                       => 'int',
        'version'                   => 'int',
        'yeu_cau_face_id'           => 'boolean',
        'cho_phep_checkin_thu_cong' => 'boolean',
        'tu_dong_xac_nhan_sv_gv'    => 'boolean',
        'yeu_cau_duyet_khach'       => 'boolean',
        'cho_phep_checkin'          => 'boolean',
        'cho_phep_checkout'         => 'boolean',
        'tong_dang_ky'              => 'int',
        'tong_check_in'             => 'int',
        'tong_check_out'            => 'int',
    ];
    
    // Trạng thái sự kiện
    const STATUS_INACTIVE = 0;      // Không hoạt động
    const STATUS_ACTIVE = 1;        // Hoạt động
    const STATUS_CANCELLED = -1;    // Đã hủy
    const STATUS_POSTPONED = 2;     // Tạm hoãn
    
    /**
     * Lấy tổng số người đăng ký
     */
    public function getTongDangKy()
    {
        return $this->attributes['tong_dang_ky'] ?? 0;
    }
    
    /**
     * Đặt tổng số người đăng ký
     */
    public function setTongDangKy(int $tongDangKy)
    {
        $this->attributes['tong_dang_ky'] = $tongDangKy;
        
        return $this;
    }
    
    /**
     * Tăng tổng số người đăng ký
     */
    public function tangTongDangKy()
    {
        $this->attributes['tong_dang_ky'] = ($this->getTongDangKy() + 1);
        
        return $this;
    }
    
    /**
     * Giảm tổng số người đăng ký
     */
    public function giamTongDangKy()
    {
        $tongDangKy = $this->getTongDangKy();
        if ($tongDangKy > 0) {
            $this->attributes['tong_dang_ky'] = ($tongDangKy - 1);
        }
        
        return $this;
    }
    
    /**
     * Lấy tổng số người check-in
     */
    public function getTongCheckIn()
    {
        return $this->attributes['tong_check_in'] ?? 0;
    }
    
    /**
     * Đặt tổng số người check-in
     */
    public function setTongCheckIn(int $tongCheckIn)
    {
        $this->attributes['tong_check_in'] = $tongCheckIn;
        
        return $this;
    }
    
    /**
     * Tăng tổng số người check-in
     */
    public function tangTongCheckIn()
    {
        $this->attributes['tong_check_in'] = ($this->getTongCheckIn() + 1);
        
        return $this;
    }
    
    /**
     * Lấy tổng số người check-out
     */
    public function getTongCheckOut()
    {
        return $this->attributes['tong_check_out'] ?? 0;
    }
    
    /**
     * Đặt tổng số người check-out
     */
    public function setTongCheckOut(int $tongCheckOut)
    {
        $this->attributes['tong_check_out'] = $tongCheckOut;
        
        return $this;
    }
    
    /**
     * Tăng tổng số người check-out
     */
    public function tangTongCheckOut()
    {
        $this->attributes['tong_check_out'] = ($this->getTongCheckOut() + 1);
        
        return $this;
    }
    
    /**
     * Kiểm tra xem có cho phép check-in không
     */
    public function choPhepCheckIn()
    {
        return isset($this->attributes['cho_phep_checkin']) && $this->attributes['cho_phep_checkin'] === true;
    }
    
    /**
     * Đặt cho phép check-in
     */
    public function setChoPhepCheckIn(bool $choPhep = true)
    {
        $this->attributes['cho_phep_checkin'] = $choPhep;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem có cho phép check-out không
     */
    public function choPhepCheckOut()
    {
        return isset($this->attributes['cho_phep_checkout']) && $this->attributes['cho_phep_checkout'] === true;
    }
    
    /**
     * Đặt cho phép check-out
     */
    public function setChoPhepCheckOut(bool $choPhep = true)
    {
        $this->attributes['cho_phep_checkout'] = $choPhep;
        
        return $this;
    }
    
    /**
     * Lấy tên sự kiện
     */
    public function getTenSuKien()
    {
        return $this->attributes['ten_su_kien'] ?? '';
    }
    
    /**
     * Đặt tên sự kiện
     */
    public function setTenSuKien(string $ten)
    {
        $this->attributes['ten_su_kien'] = $ten;
        
        return $this;
    }
    
    /**
     * Lấy mô tả sự kiện
     */
    public function getMoTaSuKien()
    {
        return $this->attributes['mo_ta_su_kien'] ?? '';
    }
    
    /**
     * Đặt mô tả sự kiện
     */
    public function setMoTaSuKien(string $moTa)
    {
        $this->attributes['mo_ta_su_kien'] = $moTa;
        
        return $this;
    }
    
    /**
     * Lấy ID người tạo sự kiện
     */
    public function getNguoiTaoId()
    {
        return $this->attributes['nguoi_tao_id'] ?? 0;
    }
    
    /**
     * Đặt ID người tạo sự kiện
     */
    public function setNguoiTaoId(int $nguoiTaoId)
    {
        $this->attributes['nguoi_tao_id'] = $nguoiTaoId;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem người dùng có phải là người tạo sự kiện không
     */
    public function isCreatedBy(int $nguoiDungId)
    {
        return $this->getNguoiTaoId() === $nguoiDungId;
    }
    
    /**
     * Kiểm tra xem người dùng có quyền chỉnh sửa sự kiện không
     * (là người tạo hoặc có quyền admin)
     */
    public function canEdit(int $nguoiDungId, bool $isAdmin = false)
    {
        // Nếu là admin, luôn có quyền chỉnh sửa
        if ($isAdmin) {
            return true;
        }
        
        // Nếu là người tạo, có quyền chỉnh sửa
        return $this->isCreatedBy($nguoiDungId);
    }
    
    /**
     * Lấy chi tiết sự kiện
     */
    public function getChiTietSuKien()
    {
        return $this->attributes['chi_tiet_su_kien'] ?? '';
    }
    
    /**
     * Đặt chi tiết sự kiện
     */
    public function setChiTietSuKien(string $chiTiet)
    {
        $this->attributes['chi_tiet_su_kien'] = $chiTiet;
        
        return $this;
    }
    
    /**
     * Lấy ID loại sự kiện
     */
    public function getLoaiSuKienId()
    {
        return $this->attributes['loai_su_kien_id'] ?? 0;
    }
    
    /**
     * Đặt ID loại sự kiện
     */
    public function setLoaiSuKienId(int $loaiSuKienId)
    {
        $this->attributes['loai_su_kien_id'] = $loaiSuKienId;
        
        return $this;
    }
    
    /**
     * Lấy thời gian bắt đầu đăng ký
     */
    public function getBatDauDangKy()
    {
        return $this->attributes['bat_dau_dang_ky'] ?? null;
    }
    
    /**
     * Đặt thời gian bắt đầu đăng ký
     */
    public function setBatDauDangKy(string $batDauDangKy)
    {
        $this->attributes['bat_dau_dang_ky'] = $batDauDangKy;
        
        return $this;
    }
    
    /**
     * Lấy thời gian kết thúc đăng ký
     */
    public function getKetThucDangKy()
    {
        return $this->attributes['ket_thuc_dang_ky'] ?? null;
    }
    
    /**
     * Đặt thời gian kết thúc đăng ký
     */
    public function setKetThucDangKy(string $ketThucDangKy)
    {
        $this->attributes['ket_thuc_dang_ky'] = $ketThucDangKy;
        
        return $this;
    }
    
    /**
     * Lấy số lượng tham gia
     */
    public function getSoLuongThamGia()
    {
        return $this->attributes['so_luong_tham_gia'] ?? 0;
    }
    
    /**
     * Đặt số lượng tham gia
     */
    public function setSoLuongThamGia(int $soLuong)
    {
        $this->attributes['so_luong_tham_gia'] = $soLuong;
        
        return $this;
    }
    
    /**
     * Lấy giờ bắt đầu sự kiện
     */
    public function getGioBatDau()
    {
        return $this->attributes['gio_bat_dau'] ?? null;
    }
    
    /**
     * Đặt giờ bắt đầu sự kiện
     */
    public function setGioBatDau(string $gioBatDau)
    {
        $this->attributes['gio_bat_dau'] = $gioBatDau;
        
        return $this;
    }
    
    /**
     * Lấy giờ kết thúc sự kiện
     */
    public function getGioKetThuc()
    {
        return $this->attributes['gio_ket_thuc'] ?? null;
    }
    
    /**
     * Đặt giờ kết thúc sự kiện
     */
    public function setGioKetThuc(string $gioKetThuc)
    {
        $this->attributes['gio_ket_thuc'] = $gioKetThuc;
        
        return $this;
    }
    
    /**
     * Lấy số lượng diễn giả
     */
    public function getSoLuongDienGia()
    {
        return $this->attributes['so_luong_dien_gia'] ?? 0;
    }
    
    /**
     * Đặt số lượng diễn giả
     */
    public function setSoLuongDienGia(int $soLuong)
    {
        $this->attributes['so_luong_dien_gia'] = $soLuong;
        
        return $this;
    }
    
    /**
     * Lấy giới hạn loại người dùng
     */
    public function getGioiHanLoaiNguoiDung()
    {
        return $this->attributes['gioi_han_loai_nguoi_dung'] ?? '';
    }
    
    /**
     * Đặt giới hạn loại người dùng
     */
    public function setGioiHanLoaiNguoiDung(string $gioiHan)
    {
        $this->attributes['gioi_han_loai_nguoi_dung'] = $gioiHan;
        
        return $this;
    }
    
    /**
     * Lấy từ khóa sự kiện
     */
    public function getTuKhoaSuKien()
    {
        return $this->attributes['tu_khoa_su_kien'] ?? '';
    }
    
    /**
     * Đặt từ khóa sự kiện
     */
    public function setTuKhoaSuKien(string $tuKhoa)
    {
        $this->attributes['tu_khoa_su_kien'] = $tuKhoa;
        
        return $this;
    }
    
    /**
     * Lấy hashtag
     */
    public function getHashtag()
    {
        return $this->attributes['hashtag'] ?? '';
    }
    
    /**
     * Đặt hashtag
     */
    public function setHashtag(string $hashtag)
    {
        $this->attributes['hashtag'] = $hashtag;
        
        return $this;
    }
    
    /**
     * Lấy slug
     */
    public function getSlug()
    {
        return $this->attributes['slug'] ?? '';
    }
    
    /**
     * Đặt slug
     */
    public function setSlug(string $slug)
    {
        $this->attributes['slug'] = $slug;
        
        return $this;
    }
    
    /**
     * Tạo slug từ tên sự kiện
     */
    public function taoSlugTuTen()
    {
        $slug = $this->convertToSlug($this->getTenSuKien());
        $this->setSlug($slug);
        
        return $this;
    }
    
    /**
     * Chuyển đổi chuỗi thành slug
     */
    private function convertToSlug($string)
    {
        // Chuyển về chữ thường và loại bỏ khoảng trắng ở đầu và cuối
        $string = mb_strtolower(trim($string));
        
        // Thay thế các ký tự tiếng Việt
        $vietnamese = array(
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
        );
        $replacements = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
        );
        $string = str_replace($vietnamese, $replacements, $string);
        
        // Thay thế các ký tự không phải chữ cái và số bằng dấu gạch ngang
        $string = preg_replace('/[^a-z0-9]/', '-', $string);
        
        // Thay thế nhiều dấu gạch ngang liên tiếp bằng một dấu gạch ngang
        $string = preg_replace('/-+/', '-', $string);
        
        // Loại bỏ dấu gạch ngang ở đầu và cuối
        $string = trim($string, '-');
        
        return $string;
    }
    
    /**
     * Lấy số lượt xem
     */
    public function getSoLuotXem()
    {
        return $this->attributes['so_luot_xem'] ?? 0;
    }
    
    /**
     * Đặt số lượt xem
     */
    public function setSoLuotXem(int $soLuotXem)
    {
        $this->attributes['so_luot_xem'] = $soLuotXem;
        
        return $this;
    }
    
    /**
     * Tăng số lượt xem
     */
    public function tangSoLuotXem()
    {
        $this->attributes['so_luot_xem'] = ($this->getSoLuotXem() + 1);
        
        return $this;
    }
    
    /**
     * Lấy lịch trình
     */
    public function getLichTrinh()
    {
        return $this->attributes['lich_trinh'] ?? '';
    }
    
    /**
     * Đặt lịch trình
     */
    public function setLichTrinh(string $lichTrinh)
    {
        $this->attributes['lich_trinh'] = $lichTrinh;
        
        return $this;
    }
    
    /**
     * Kiểm tra trạng thái của sự kiện
     */
    public function isActive()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1;
    }
    
    /**
     * Đặt trạng thái của sự kiện
     */
    public function setStatus(int $status)
    {
        $this->attributes['status'] = $status;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem sự kiện có thể đăng ký được không
     */
    public function isRegistrable()
    {
        $now = date('Y-m-d H:i:s');
        $batDauDangKy = $this->getBatDauDangKy();
        $ketThucDangKy = $this->getKetThucDangKy();
        
        return $this->isActive() && 
               $batDauDangKy && $ketThucDangKy && 
               $now >= $batDauDangKy && 
               $now <= $ketThucDangKy;
    }
    
    /**
     * Kiểm tra xem sự kiện đã bắt đầu chưa
     */
    public function hasStarted()
    {
        $now = date('Y-m-d H:i:s');
        $gioBatDau = $this->getGioBatDau();
        
        return $gioBatDau && $now >= $gioBatDau;
    }
    
    /**
     * Kiểm tra xem sự kiện đã kết thúc chưa
     */
    public function hasEnded()
    {
        $now = date('Y-m-d H:i:s');
        $gioKetThuc = $this->getGioKetThuc();
        
        return $gioKetThuc && $now > $gioKetThuc;
    }
    
    /**
     * Lấy trạng thái sự kiện dưới dạng văn bản
     */
    public function getStatusText()
    {
        if (!$this->isActive()) {
            return 'Không hoạt động';
        }
        
        if ($this->hasEnded()) {
            return 'Đã kết thúc';
        }
        
        if ($this->hasStarted()) {
            return 'Đang diễn ra';
        }
        
        if ($this->isRegistrable()) {
            return 'Đang mở đăng ký';
        }
        
        $now = date('Y-m-d H:i:s');
        $batDauDangKy = $this->getBatDauDangKy();
        
        if ($batDauDangKy && $now < $batDauDangKy) {
            return 'Sắp mở đăng ký';
        }
        
        return 'Sắp diễn ra';
    }
    
    /**
     * Lấy địa điểm sự kiện
     */
    public function getDiaDiem()
    {
        return $this->attributes['dia_diem'] ?? '';
    }
    
    /**
     * Đặt địa điểm sự kiện
     */
    public function setDiaDiem(string $diaDiem)
    {
        $this->attributes['dia_diem'] = $diaDiem;
        
        return $this;
    }
    
    /**
     * Lấy phiên bản sự kiện
     */
    public function getVersion()
    {
        return $this->attributes['version'] ?? 1;
    }
    
    /**
     * Tăng phiên bản sự kiện khi cập nhật
     */
    public function incrementVersion()
    {
        $this->attributes['version'] = ($this->getVersion() + 1);
        
        return $this;
    }
    
    /**
     * Kiểm tra xem sự kiện có yêu cầu face ID không
     */
    public function yeuCauFaceId()
    {
        return isset($this->attributes['yeu_cau_face_id']) && $this->attributes['yeu_cau_face_id'] === true;
    }
    
    /**
     * Đặt yêu cầu face ID
     */
    public function setYeuCauFaceId(bool $yeuCau = true)
    {
        $this->attributes['yeu_cau_face_id'] = $yeuCau;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem sự kiện có cho phép checkin thủ công không
     */
    public function choPhepCheckinThuCong()
    {
        return isset($this->attributes['cho_phep_checkin_thu_cong']) && $this->attributes['cho_phep_checkin_thu_cong'] === true;
    }
    
    /**
     * Đặt cho phép checkin thủ công
     */
    public function setChoPhepCheckinThuCong(bool $choPhep = true)
    {
        $this->attributes['cho_phep_checkin_thu_cong'] = $choPhep;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem sự kiện có tự động xác nhận sinh viên và giảng viên không
     */
    public function tuDongXacNhanSVGV()
    {
        return isset($this->attributes['tu_dong_xac_nhan_sv_gv']) && $this->attributes['tu_dong_xac_nhan_sv_gv'] === true;
    }
    
    /**
     * Đặt tự động xác nhận sinh viên và giảng viên
     */
    public function setTuDongXacNhanSVGV(bool $tuDong = true)
    {
        $this->attributes['tu_dong_xac_nhan_sv_gv'] = $tuDong;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem sự kiện có yêu cầu duyệt khách không
     */
    public function yeuCauDuyetKhach()
    {
        return isset($this->attributes['yeu_cau_duyet_khach']) && $this->attributes['yeu_cau_duyet_khach'] === true;
    }
    
    /**
     * Đặt yêu cầu duyệt khách
     */
    public function setYeuCauDuyetKhach(bool $yeuCau = true)
    {
        $this->attributes['yeu_cau_duyet_khach'] = $yeuCau;
        
        return $this;
    }
    
    /**
     * Lấy hạn hủy đăng ký
     */
    public function getHanHuyDangKy()
    {
        return $this->attributes['han_huy_dang_ky'] ?? null;
    }
    
    /**
     * Đặt hạn hủy đăng ký
     */
    public function setHanHuyDangKy(string $hanHuyDangKy)
    {
        $this->attributes['han_huy_dang_ky'] = $hanHuyDangKy;
        
        return $this;
    }
    
    /**
     * Tính hạn hủy đăng ký (mặc định 7 ngày trước khi sự kiện bắt đầu)
     */
    public function tinhHanHuyDangKy($soNgay = 7)
    {
        $gioBatDau = $this->getGioBatDau();
        if ($gioBatDau) {
            $this->attributes['han_huy_dang_ky'] = date('Y-m-d H:i:s', strtotime($gioBatDau . ' -' . $soNgay . ' days'));
        }
        
        return $this;
    }
    
    /**
     * Kiểm tra xem có thể hủy đăng ký được không
     */
    public function coTheHuyDangKy()
    {
        $now = date('Y-m-d H:i:s');
        $hanHuy = $this->getHanHuyDangKy();
        
        return $hanHuy && $now <= $hanHuy;
    }
    
    /**
     * Kiểm tra xem loại người dùng có được phép đăng ký không
     */
    public function kiemTraLoaiNguoiDungDuocPhep($loaiNguoiDung)
    {
        $gioiHan = $this->getGioiHanLoaiNguoiDung();
        
        if (empty($gioiHan)) {
            return true; // Không giới hạn
        }
        
        // Nếu là chuỗi JSON, chuyển thành mảng
        if (is_string($gioiHan) && strpos($gioiHan, '[') === 0) {
            $gioiHan = json_decode($gioiHan, true);
        }
        
        if (is_array($gioiHan)) {
            return in_array($loaiNguoiDung, $gioiHan);
        }
        
        return $gioiHan === $loaiNguoiDung;
    }
    
    /**
     * Lưu lịch sử thay đổi sự kiện
     */
    public function luuLichSuThayDoi(int $nguoiThayDoiId, string $moTa = '')
    {
        $history = new SukienHistoryEntity();
        $history->luuLichSuThayDoi($this, $nguoiThayDoiId, $moTa);
        
        return $history;
    }
    
    /**
     * Tạo đối tượng đăng ký sự kiện mới
     */
    public function taoDangKy(int $nguoiDungId, string $loaiNguoiDangKy = DangKySukienEntity::LOAI_KHACH)
    {
        $dangky = new DangKySukienEntity();
        $dangky->setSuKienId($this->id);
        $dangky->setNguoiDungId($nguoiDungId);
        $dangky->setLoaiNguoiDangKy($loaiNguoiDangKy);
        $dangky->xuLyDangKyMoi($this);
        
        return $dangky;
    }
    
    /**
     * Kiểm tra xem sự kiện có đầy chỗ không
     */
    public function isDayChoThamGia()
    {
        $soLuongThamGia = $this->getSoLuongThamGia();
        return $soLuongThamGia > 0 && $this->getTongDangKy() >= $soLuongThamGia;
    }
    
    /**
     * Bắt đầu cho phép check-in
     */
    public function batDauCheckIn()
    {
        $this->setChoPhepCheckIn(true);
        return $this;
    }
    
    /**
     * Kết thúc cho phép check-in
     */
    public function ketThucCheckIn()
    {
        $this->setChoPhepCheckIn(false);
        return $this;
    }
    
    /**
     * Bắt đầu cho phép check-out
     */
    public function batDauCheckOut()
    {
        $this->setChoPhepCheckOut(true);
        return $this;
    }
    
    /**
     * Kết thúc cho phép check-out
     */
    public function ketThucCheckOut()
    {
        $this->setChoPhepCheckOut(false);
        return $this;
    }
    
    /**
     * Kiểm tra xem người dùng có thể check-in không
     */
    public function coTheCheckIn()
    {
        return $this->isActive() && $this->choPhepCheckIn() && !$this->hasEnded();
    }
    
    /**
     * Kiểm tra xem người dùng có thể check-out không
     */
    public function coTheCheckOut()
    {
        return $this->isActive() && $this->choPhepCheckOut();
    }
}
