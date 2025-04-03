<?php

namespace App\Modules\sukien\Entities;

use CodeIgniter\Entity\Entity;

class CheckoutSukienEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['thoi_gian_check_out', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'                => 'int',
        'nguoi_dung_id'     => 'int',
        'su_kien_id'        => 'int',
        'nguoi_checkout_id' => 'int',
        'face_match_score'  => 'float',
        'face_verified'     => 'boolean',
        'status'            => 'int',
        'bin'               => 'int',
        'dangky_sukien_id'=> 'int',
        'checkin_su_kien_id'=> 'int',
    ];
    
    // Các phương thức loại checkout
    const CHECKOUT_FACE_ID = 'face_id';
    const CHECKOUT_MANUAL = 'manual';
    const CHECKOUT_QR_CODE = 'qr_code';
    const CHECKOUT_AUTO = 'auto';  // Tự động checkout khi kết thúc sự kiện
    
    /**
     * Lấy ID đăng ký sự kiện liên quan
     */
    public function getDangKySuKienId()
    {
        return $this->attributes['dangky_sukien_id'] ?? 0;
    }
    
    /**
     * Đặt ID đăng ký sự kiện liên quan
     */
    public function setDangKySuKienId(int $dangKySuKienId)
    {
        $this->attributes['dangky_sukien_id'] = $dangKySuKienId;
        
        return $this;
    }
    
    /**
     * Lấy ID check-in sự kiện liên quan
     */
    public function getCheckinSuKienId()
    {
        return $this->attributes['checkin_su_kien_id'] ?? 0;
    }
    
    /**
     * Đặt ID check-in sự kiện liên quan
     */
    public function setCheckinSuKienId(int $checkinSuKienId)
    {
        $this->attributes['checkin_su_kien_id'] = $checkinSuKienId;
        
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
     * Lấy thời gian check-out
     */
    public function getThoiGianCheckOut()
    {
        return $this->attributes['thoi_gian_check_out'] ?? null;
    }
    
    /**
     * Đặt thời gian check-out
     */
    public function setThoiGianCheckOut(string $thoiGianCheckOut)
    {
        $this->attributes['thoi_gian_check_out'] = $thoiGianCheckOut;
        
        return $this;
    }
    
    /**
     * Kiểm tra trạng thái của check-out
     */
    public function isActive()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1;
    }
    
    /**
     * Đặt trạng thái của check-out
     */
    public function setStatus(int $status)
    {
        $this->attributes['status'] = $status;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem check-out đã được xác nhận hay chưa
     */
    public function isConfirmed()
    {
        return $this->isActive();
    }
    
    /**
     * Đánh dấu check-out là đã xác nhận
     */
    public function markAsConfirmed()
    {
        return $this->setStatus(1);
    }
    
    /**
     * Đánh dấu check-out là không hợp lệ
     */
    public function markAsInvalid()
    {
        return $this->setStatus(0);
    }
    
    /**
     * Lấy thời gian check-out theo định dạng dễ đọc
     */
    public function getFormattedCheckOutTime($format = 'd/m/Y H:i:s')
    {
        $thoiGianCheckOut = $this->getThoiGianCheckOut();
        if (!$thoiGianCheckOut) {
            return '';
        }
        
        return date($format, strtotime($thoiGianCheckOut));
    }
    
    /**
     * Kiểm tra xem đã check-out trong thời gian hợp lệ hay không
     */
    public function isValidCheckOutTime($gioBatDau, $gioKetThuc)
    {
        $thoiGianCheckOut = $this->getThoiGianCheckOut();
        if (!$thoiGianCheckOut || !$gioBatDau) {
            return false;
        }
        
        // Nếu check-out sau khi sự kiện đã bắt đầu và trước/vào lúc kết thúc (nếu có)
        return $thoiGianCheckOut >= $gioBatDau && 
               (!$gioKetThuc || $thoiGianCheckOut <= $gioKetThuc);
    }
    
    /**
     * Tính thời gian tham gia sự kiện (giữa check-in và check-out)
     */
    public function calculateAttendanceTime($thoiGianCheckIn)
    {
        $thoiGianCheckOut = $this->getThoiGianCheckOut();
        if (!$thoiGianCheckOut || !$thoiGianCheckIn) {
            return 0;
        }
        
        $checkInTime = strtotime($thoiGianCheckIn);
        $checkOutTime = strtotime($thoiGianCheckOut);
        
        // Trả về số giây tham gia
        return $checkOutTime - $checkInTime;
    }
    
    /**
     * Định dạng thời gian tham gia thành chuỗi dễ đọc
     */
    public function getFormattedAttendanceTime($thoiGianCheckIn)
    {
        $seconds = $this->calculateAttendanceTime($thoiGianCheckIn);
        
        if ($seconds <= 0) {
            return 'Không xác định';
        }
        
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        
        if ($hours > 0) {
            return sprintf('%d giờ %d phút', $hours, $minutes);
        } else {
            return sprintf('%d phút', $minutes);
        }
    }
    
    /**
     * Lấy loại checkout
     */
    public function getCheckoutType()
    {
        return $this->attributes['checkout_type'] ?? '';
    }
    
    /**
     * Đặt loại checkout
     */
    public function setCheckoutType(string $checkoutType)
    {
        $this->attributes['checkout_type'] = $checkoutType;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem có phải checkout bằng face ID không
     */
    public function isFaceIdCheckout()
    {
        return $this->getCheckoutType() === self::CHECKOUT_FACE_ID;
    }
    
    /**
     * Kiểm tra xem có phải checkout thủ công không
     */
    public function isManualCheckout()
    {
        return $this->getCheckoutType() === self::CHECKOUT_MANUAL;
    }
    
    /**
     * Kiểm tra xem có phải checkout bằng QR code không
     */
    public function isQrCodeCheckout()
    {
        return $this->getCheckoutType() === self::CHECKOUT_QR_CODE;
    }
    
    /**
     * Kiểm tra xem có phải tự động checkout không
     */
    public function isAutoCheckout()
    {
        return $this->getCheckoutType() === self::CHECKOUT_AUTO;
    }
    
    /**
     * Lấy ID người thực hiện checkout thủ công
     */
    public function getNguoiCheckoutId()
    {
        return $this->attributes['nguoi_checkout_id'] ?? 0;
    }
    
    /**
     * Đặt ID người thực hiện checkout thủ công
     */
    public function setNguoiCheckoutId(int $nguoiCheckoutId)
    {
        $this->attributes['nguoi_checkout_id'] = $nguoiCheckoutId;
        
        return $this;
    }
    
    /**
     * Lấy đường dẫn hình ảnh khuôn mặt khi checkout
     */
    public function getFaceImagePath()
    {
        return $this->attributes['face_image_path'] ?? '';
    }
    
    /**
     * Đặt đường dẫn hình ảnh khuôn mặt khi checkout
     */
    public function setFaceImagePath(string $imagePath)
    {
        $this->attributes['face_image_path'] = $imagePath;
        
        return $this;
    }
    
    /**
     * Lấy điểm số đối chiếu khuôn mặt
     */
    public function getFaceMatchScore()
    {
        return $this->attributes['face_match_score'] ?? 0;
    }
    
    /**
     * Đặt điểm số đối chiếu khuôn mặt
     */
    public function setFaceMatchScore(float $score)
    {
        $this->attributes['face_match_score'] = $score;
        
        return $this;
    }
    
    /**
     * Lấy mã xác nhận khi checkout thủ công
     */
    public function getMaXacNhan()
    {
        return $this->attributes['ma_xac_nhan'] ?? '';
    }
    
    /**
     * Đặt mã xác nhận khi checkout thủ công
     */
    public function setMaXacNhan(string $maXacNhan)
    {
        $this->attributes['ma_xac_nhan'] = $maXacNhan;
        
        return $this;
    }
    
    /**
     * Checkout bằng face ID
     */
    public function checkoutWithFaceId(string $imagePath, float $matchScore = 0)
    {
        $this->setCheckoutType(self::CHECKOUT_FACE_ID);
        $this->setFaceImagePath($imagePath);
        $this->setFaceMatchScore($matchScore);
        $this->attributes['face_verified'] = true;
        $this->attributes['thoi_gian_check_out'] = date('Y-m-d H:i:s');
        $this->attributes['status'] = 1;
        
        return $this;
    }
    
    /**
     * Checkout thủ công
     */
    public function checkoutManual(int $nguoiCheckoutId, string $maXacNhan = '')
    {
        $this->setCheckoutType(self::CHECKOUT_MANUAL);
        $this->setNguoiCheckoutId($nguoiCheckoutId);
        $this->setMaXacNhan($maXacNhan);
        $this->attributes['thoi_gian_check_out'] = date('Y-m-d H:i:s');
        $this->attributes['status'] = 1;
        
        return $this;
    }
    
    /**
     * Checkout bằng QR code
     */
    public function checkoutWithQrCode(string $maXacNhan)
    {
        $this->setCheckoutType(self::CHECKOUT_QR_CODE);
        $this->setMaXacNhan($maXacNhan);
        $this->attributes['thoi_gian_check_out'] = date('Y-m-d H:i:s');
        $this->attributes['status'] = 1;
        
        return $this;
    }
    
    /**
     * Tự động checkout khi kết thúc sự kiện
     */
    public function autoCheckout()
    {
        $this->setCheckoutType(self::CHECKOUT_AUTO);
        $this->attributes['thoi_gian_check_out'] = date('Y-m-d H:i:s');
        $this->attributes['status'] = 1;
        
        return $this;
    }
    
    /**
     * Checkout từ check-in
     */
    public function checkoutFromCheckin(CheckinSukienEntity $checkin, string $loaiCheckout = self::CHECKOUT_AUTO)
    {
        $this->setCheckinSuKienId($checkin->id);
        $this->setSuKienId($checkin->getSuKienId());
        $this->setNguoiDungId($checkin->getNguoiDungId());
        
        if ($checkin->getDangKySuKienId()) {
            $this->setDangKySuKienId($checkin->getDangKySuKienId());
        }
        
        switch ($loaiCheckout) {
            case self::CHECKOUT_FACE_ID:
                // Cần thêm thông tin face ID
                break;
            case self::CHECKOUT_MANUAL:
                // Cần thêm thông tin người checkout
                break;
            case self::CHECKOUT_QR_CODE:
                // Cần thêm mã xác nhận
                break;
            default:
                $this->autoCheckout();
                break;
        }
        
        return $this;
    }
}
