<?php

namespace App\Modules\sukien\Entities;

use CodeIgniter\Entity\Entity;

class DangKySukienEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = [
        'ngay_dang_ky', 
        'thoi_gian_duyet', 
        'thoi_gian_huy', 
        'created_at', 
        'updated_at'
    ];
    protected $casts   = [
        'id'               => 'int',
        'su_kien_id'       => 'int',
        'nguoi_dung_id'    => 'int',
        'nguoi_duyet_id'   => 'int',
        'status'           => 'int',
        'bin'              => 'int',
        'face_verified'    => 'boolean',
        'da_check_in'      => 'boolean',
        'da_check_out'     => 'boolean',
        'checkin_id'       => 'int',
        'checkout_id'      => 'int',
    ];
    
    // Các loại người đăng ký
    const LOAI_KHACH = 'khach';
    const LOAI_SINH_VIEN = 'sinh_vien';
    const LOAI_GIANG_VIEN = 'giang_vien';
    
    // Các trạng thái đăng ký
    const STATUS_PENDING = 0;     // Chờ xác nhận
    const STATUS_CONFIRMED = 1;   // Đã xác nhận
    const STATUS_CANCELLED = -1;  // Đã hủy
    
    /**
     * Kiểm tra đã check-in hay chưa
     */
    public function isDaCheckIn()
    {
        return isset($this->attributes['da_check_in']) && $this->attributes['da_check_in'] === true;
    }
    
    /**
     * Đánh dấu đã check-in
     */
    public function setDaCheckIn(bool $daCheckIn = true)
    {
        $this->attributes['da_check_in'] = $daCheckIn;
        
        return $this;
    }
    
    /**
     * Lấy ID check-in
     */
    public function getCheckinId()
    {
        return $this->attributes['checkin_id'] ?? 0;
    }
    
    /**
     * Đặt ID check-in
     */
    public function setCheckinId(int $checkinId)
    {
        $this->attributes['checkin_id'] = $checkinId;
        
        return $this;
    }
    
    /**
     * Kiểm tra đã check-out hay chưa
     */
    public function isDaCheckOut()
    {
        return isset($this->attributes['da_check_out']) && $this->attributes['da_check_out'] === true;
    }
    
    /**
     * Đánh dấu đã check-out
     */
    public function setDaCheckOut(bool $daCheckOut = true)
    {
        $this->attributes['da_check_out'] = $daCheckOut;
        
        return $this;
    }
    
    /**
     * Lấy ID check-out
     */
    public function getCheckoutId()
    {
        return $this->attributes['checkout_id'] ?? 0;
    }
    
    /**
     * Đặt ID check-out
     */
    public function setCheckoutId(int $checkoutId)
    {
        $this->attributes['checkout_id'] = $checkoutId;
        
        return $this;
    }
    
    /**
     * Lấy ID sự kiện
     */
    public function getSuKienId()
    {
        return $this->attributes['su_kien_id'] ?? 0;
    }
    
    /**
     * Đặt ID sự kiện
     */
    public function setSuKienId(int $suKienId)
    {
        $this->attributes['su_kien_id'] = $suKienId;
        
        return $this;
    }
    
    /**
     * Lấy ID người dùng
     */
    public function getNguoiDungId()
    {
        return $this->attributes['nguoi_dung_id'] ?? 0;
    }
    
    /**
     * Đặt ID người dùng
     */
    public function setNguoiDungId(int $nguoiDungId)
    {
        $this->attributes['nguoi_dung_id'] = $nguoiDungId;
        
        return $this;
    }
    
    /**
     * Lấy ngày đăng ký
     */
    public function getNgayDangKy()
    {
        return $this->attributes['ngay_dang_ky'] ?? null;
    }
    
    /**
     * Đặt ngày đăng ký
     */
    public function setNgayDangKy(string $ngayDangKy)
    {
        $this->attributes['ngay_dang_ky'] = $ngayDangKy;
        
        return $this;
    }
    
    /**
     * Lấy nội dung góp ý
     */
    public function getNoiDungGopY()
    {
        return $this->attributes['noi_dung_gop_y'] ?? '';
    }
    
    /**
     * Đặt nội dung góp ý
     */
    public function setNoiDungGopY(string $noiDungGopY)
    {
        $this->attributes['noi_dung_gop_y'] = $noiDungGopY;
        
        return $this;
    }
    
    /**
     * Lấy nguồn giới thiệu
     */
    public function getNguonGioiThieu()
    {
        return $this->attributes['nguon_gioi_thieu'] ?? '';
    }
    
    /**
     * Đặt nguồn giới thiệu
     */
    public function setNguonGioiThieu(string $nguonGioiThieu)
    {
        $this->attributes['nguon_gioi_thieu'] = $nguonGioiThieu;
        
        return $this;
    }
    
    /**
     * Lấy loại người dùng
     */
    public function getLoaiNguoiDung()
    {
        return $this->attributes['loai_nguoi_dung'] ?? '';
    }
    
    /**
     * Đặt loại người dùng
     */
    public function setLoaiNguoiDung(string $loaiNguoiDung)
    {
        $this->attributes['loai_nguoi_dung'] = $loaiNguoiDung;
        
        return $this;
    }
    
    /**
     * Lấy phân loại người đăng ký (khách, sinh viên, giảng viên)
     */
    public function getLoaiNguoiDangKy()
    {
        return $this->attributes['loai_nguoi_dang_ky'] ?? '';
    }
    
    /**
     * Đặt phân loại người đăng ký (khách, sinh viên, giảng viên)
     */
    public function setLoaiNguoiDangKy(string $loaiNguoiDangKy)
    {
        $this->attributes['loai_nguoi_dang_ky'] = $loaiNguoiDangKy;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem người đăng ký có phải là khách không
     */
    public function isKhach()
    {
        return $this->getLoaiNguoiDangKy() === self::LOAI_KHACH;
    }
    
    /**
     * Kiểm tra xem người đăng ký có phải là sinh viên không
     */
    public function isSinhVien()
    {
        return $this->getLoaiNguoiDangKy() === self::LOAI_SINH_VIEN;
    }
    
    /**
     * Kiểm tra xem người đăng ký có phải là giảng viên không
     */
    public function isGiangVien()
    {
        return $this->getLoaiNguoiDangKy() === self::LOAI_GIANG_VIEN;
    }
    
    /**
     * Lấy trình độ học vấn
     */
    public function getTrinhDoHocVan()
    {
        return $this->attributes['trinh_do_hoc_van'] ?? '';
    }
    
    /**
     * Đặt trình độ học vấn
     */
    public function setTrinhDoHocVan(string $trinhDoHocVan)
    {
        $this->attributes['trinh_do_hoc_van'] = $trinhDoHocVan;
        
        return $this;
    }
    
    /**
     * Lấy đường dẫn hình ảnh khuôn mặt
     */
    public function getFaceImagePath()
    {
        return $this->attributes['face_image_path'] ?? '';
    }
    
    /**
     * Đặt đường dẫn hình ảnh khuôn mặt
     */
    public function setFaceImagePath(string $imagePath)
    {
        $this->attributes['face_image_path'] = $imagePath;
        
        return $this;
    }
    
    /**
     * Lấy dữ liệu Face ID
     */
    public function getFaceId()
    {
        return $this->attributes['face_id'] ?? '';
    }
    
    /**
     * Đặt dữ liệu Face ID
     */
    public function setFaceId(string $faceId)
    {
        $this->attributes['face_id'] = $faceId;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem khuôn mặt đã được xác minh chưa
     */
    public function isFaceVerified()
    {
        return isset($this->attributes['face_verified']) && $this->attributes['face_verified'] === true;
    }
    
    /**
     * Đánh dấu khuôn mặt đã được xác minh
     */
    public function setFaceVerified(bool $verified = true)
    {
        $this->attributes['face_verified'] = $verified;
        
        return $this;
    }
    
    /**
     * Kiểm tra trạng thái của đăng ký
     */
    public function isActive()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1;
    }
    
    /**
     * Đặt trạng thái của đăng ký
     */
    public function setStatus(int $status)
    {
        $this->attributes['status'] = $status;
        
        return $this;
    }
    
    /**
     * Lấy trạng thái đăng ký dưới dạng văn bản
     */
    public function getStatusText()
    {
        if (!isset($this->attributes['status'])) {
            return 'Không xác định';
        }
        
        switch ($this->attributes['status']) {
            case 1:
                return 'Đã xác nhận';
            case 0:
                return 'Chờ xác nhận';
            case -1:
                return 'Đã hủy';
            default:
                return 'Không xác định';
        }
    }
    
    /**
     * Kiểm tra xem đăng ký đã được xác nhận chưa
     */
    public function isConfirmed()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1;
    }
    
    /**
     * Kiểm tra xem đăng ký đã bị hủy chưa
     */
    public function isCancelled()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == -1;
    }
    
    /**
     * Kiểm tra xem đăng ký đang chờ xác nhận không
     */
    public function isPending()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 0;
    }
    
    /**
     * Lấy mã xác nhận đăng ký
     */
    public function getMaXacNhan()
    {
        return $this->attributes['ma_xac_nhan'] ?? '';
    }
    
    /**
     * Đặt mã xác nhận đăng ký
     */
    public function setMaXacNhan(string $maXacNhan)
    {
        $this->attributes['ma_xac_nhan'] = $maXacNhan;
        
        return $this;
    }
    
    /**
     * Tạo mã xác nhận đăng ký
     */
    public function taoMaXacNhan()
    {
        $this->attributes['ma_xac_nhan'] = strtoupper(bin2hex(random_bytes(4)));
        
        return $this;
    }
    
    /**
     * Lấy ID người duyệt
     */
    public function getNguoiDuyetId()
    {
        return $this->attributes['nguoi_duyet_id'] ?? 0;
    }
    
    /**
     * Đặt ID người duyệt
     */
    public function setNguoiDuyetId(int $nguoiDuyetId)
    {
        $this->attributes['nguoi_duyet_id'] = $nguoiDuyetId;
        
        return $this;
    }
    
    /**
     * Lấy thời gian duyệt
     */
    public function getThoiGianDuyet()
    {
        return $this->attributes['thoi_gian_duyet'] ?? null;
    }
    
    /**
     * Lấy thời gian hủy
     */
    public function getThoiGianHuy()
    {
        return $this->attributes['thoi_gian_huy'] ?? null;
    }
    
    /**
     * Lấy lý do hủy
     */
    public function getLyDoHuy()
    {
        return $this->attributes['ly_do_huy'] ?? '';
    }
    
    /**
     * Duyệt đăng ký
     */
    public function duyet(int $nguoiDuyetId)
    {
        $this->attributes['status'] = self::STATUS_CONFIRMED;
        $this->attributes['thoi_gian_duyet'] = date('Y-m-d H:i:s');
        $this->attributes['nguoi_duyet_id'] = $nguoiDuyetId;
        
        return $this;
    }
    
    /**
     * Tự động duyệt đăng ký (cho sinh viên và giảng viên)
     */
    public function tuDongDuyet()
    {
        $this->attributes['status'] = self::STATUS_CONFIRMED;
        $this->attributes['thoi_gian_duyet'] = date('Y-m-d H:i:s');
        
        return $this;
    }
    
    /**
     * Hủy đăng ký
     */
    public function huy(string $lyDo = '')
    {
        $this->attributes['status'] = self::STATUS_CANCELLED;
        $this->attributes['thoi_gian_huy'] = date('Y-m-d H:i:s');
        $this->attributes['ly_do_huy'] = $lyDo;
        
        return $this;
    }
    
    /**
     * Kiểm tra có thể hủy đăng ký không
     */
    public function coTheHuy(SukienEntity $sukien)
    {
        // Nếu đã hủy rồi thì không thể hủy nữa
        if ($this->isCancelled()) {
            return false;
        }
        
        // Kiểm tra thời hạn hủy đăng ký
        return $sukien->coTheHuyDangKy();
    }
    
    /**
     * Xử lý đăng ký mới
     */
    public function xuLyDangKyMoi(SukienEntity $sukien)
    {
        // Tạo mã xác nhận
        $this->taoMaXacNhan();
        
        // Đặt ngày đăng ký
        $this->attributes['ngay_dang_ky'] = date('Y-m-d H:i:s');
        
        // Xác định trạng thái ban đầu dựa trên loại người đăng ký
        $loaiNguoiDangKy = $this->getLoaiNguoiDangKy();
        
        // Nếu là sinh viên hoặc giảng viên và sự kiện cho phép tự động xác nhận
        if (($loaiNguoiDangKy === self::LOAI_SINH_VIEN || $loaiNguoiDangKy === self::LOAI_GIANG_VIEN) 
            && $sukien->tuDongXacNhanSVGV()) {
            $this->tuDongDuyet();
        } 
        // Nếu là khách và sự kiện yêu cầu duyệt
        else if ($loaiNguoiDangKy === self::LOAI_KHACH && $sukien->yeuCauDuyetKhach()) {
            $this->attributes['status'] = self::STATUS_PENDING;
        }
        // Mặc định xác nhận luôn
        else {
            $this->tuDongDuyet();
        }
        
        return $this;
    }
    
    /**
     * Tạo đối tượng check-in từ đăng ký
     */
    public function taoCheckin()
    {
        $checkin = new CheckinSukienEntity();
        $checkin->setNguoiDungId($this->getNguoiDungId());
        $checkin->setSuKienId($this->getSuKienId());
        $checkin->setDangKySuKienId($this->id);
        
        // Nếu đã có Face ID, sử dụng lại
        if ($this->isFaceVerified() && $this->getFaceId()) {
            $checkin->setFaceVerified(true);
        }
        
        return $checkin;
    }
    
    /**
     * Tạo đối tượng check-out từ đăng ký
     */
    public function taoCheckout()
    {
        $checkout = new CheckoutSukienEntity();
        $checkout->setNguoiDungId($this->getNguoiDungId());
        $checkout->setSuKienId($this->getSuKienId());
        $checkout->setDangKySuKienId($this->id);
        
        return $checkout;
    }
    
    /**
     * Cập nhật thông tin check-in
     */
    public function capNhatCheckin(CheckinSukienEntity $checkin)
    {
        $this->setDaCheckIn(true);
        $this->setCheckinId($checkin->id);
        
        return $this;
    }
    
    /**
     * Cập nhật thông tin check-out
     */
    public function capNhatCheckout(CheckoutSukienEntity $checkout)
    {
        $this->setDaCheckOut(true);
        $this->setCheckoutId($checkout->id);
        
        return $this;
    }
}
