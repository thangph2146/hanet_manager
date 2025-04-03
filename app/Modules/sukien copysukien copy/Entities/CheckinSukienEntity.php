<?php

namespace App\Modules\sukien\Entities;

use CodeIgniter\Entity\Entity;

class CheckinSukienEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['thoi_gian_check_in', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'                => 'int',
        'nguoi_dung_id'     => 'int',
        'su_kien_id'        => 'int',
        'nguoi_checkin_id'  => 'int',
        'face_match_score'  => 'float',
        'face_verified'     => 'boolean',
        'status'            => 'int',
        'bin'               => 'int',
        'dangky_sukien_id'=> 'int',
    ];
    
    // Các phương thức loại checkin
    const CHECKIN_FACE_ID = 'face_id';
    const CHECKIN_MANUAL = 'manual';
    const CHECKIN_QR_CODE = 'qr_code';
    
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
     * Lấy thời gian check-in
     */
    public function getThoiGianCheckIn()
    {
        return $this->attributes['thoi_gian_check_in'] ?? null;
    }
    
    /**
     * Đặt thời gian check-in
     */
    public function setThoiGianCheckIn(string $thoiGianCheckIn)
    {
        $this->attributes['thoi_gian_check_in'] = $thoiGianCheckIn;
        
        return $this;
    }
    
    /**
     * Kiểm tra trạng thái của check-in
     */
    public function isActive()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1;
    }
    
    /**
     * Đặt trạng thái của check-in
     */
    public function setStatus(int $status)
    {
        $this->attributes['status'] = $status;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem check-in đã được xác nhận hay chưa
     */
    public function isConfirmed()
    {
        return $this->isActive();
    }
    
    /**
     * Đánh dấu check-in là đã xác nhận
     */
    public function markAsConfirmed()
    {
        return $this->setStatus(1);
    }
    
    /**
     * Đánh dấu check-in là không hợp lệ
     */
    public function markAsInvalid()
    {
        return $this->setStatus(0);
    }
    
    /**
     * Lấy thời gian check-in theo định dạng dễ đọc
     */
    public function getFormattedCheckInTime($format = 'd/m/Y H:i:s')
    {
        $thoiGianCheckIn = $this->getThoiGianCheckIn();
        if (!$thoiGianCheckIn) {
            return '';
        }
        
        return date($format, strtotime($thoiGianCheckIn));
    }
    
    /**
     * Kiểm tra xem đã check-in trong thời gian hợp lệ hay không
     */
    public function isValidCheckInTime($gioBatDau, $gioKetThuc)
    {
        $thoiGianCheckIn = $this->getThoiGianCheckIn();
        if (!$thoiGianCheckIn || !$gioBatDau) {
            return false;
        }
        
        // Nếu check-in sau khi sự kiện đã bắt đầu và trước khi kết thúc (nếu có)
        return $thoiGianCheckIn >= $gioBatDau && 
               (!$gioKetThuc || $thoiGianCheckIn <= $gioKetThuc);
    }
    
    /**
     * Lấy loại checkin
     */
    public function getCheckinType()
    {
        return $this->attributes['checkin_type'] ?? '';
    }
    
    /**
     * Đặt loại checkin
     */
    public function setCheckinType(string $checkinType)
    {
        $this->attributes['checkin_type'] = $checkinType;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem có phải checkin bằng face ID không
     */
    public function isFaceIdCheckin()
    {
        return $this->getCheckinType() === self::CHECKIN_FACE_ID;
    }
    
    /**
     * Kiểm tra xem có phải checkin thủ công không
     */
    public function isManualCheckin()
    {
        return $this->getCheckinType() === self::CHECKIN_MANUAL;
    }
    
    /**
     * Kiểm tra xem có phải checkin bằng QR code không
     */
    public function isQrCodeCheckin()
    {
        return $this->getCheckinType() === self::CHECKIN_QR_CODE;
    }
    
    /**
     * Lấy ID người thực hiện checkin thủ công
     */
    public function getNguoiCheckinId()
    {
        return $this->attributes['nguoi_checkin_id'] ?? 0;
    }
    
    /**
     * Đặt ID người thực hiện checkin thủ công
     */
    public function setNguoiCheckinId(int $nguoiCheckinId)
    {
        $this->attributes['nguoi_checkin_id'] = $nguoiCheckinId;
        
        return $this;
    }
    
    /**
     * Lấy đường dẫn hình ảnh khuôn mặt khi checkin
     */
    public function getFaceImagePath()
    {
        return $this->attributes['face_image_path'] ?? '';
    }
    
    /**
     * Đặt đường dẫn hình ảnh khuôn mặt khi checkin
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
     * Lấy mã xác nhận khi checkin thủ công
     */
    public function getMaXacNhan()
    {
        return $this->attributes['ma_xac_nhan'] ?? '';
    }
    
    /**
     * Đặt mã xác nhận khi checkin thủ công
     */
    public function setMaXacNhan(string $maXacNhan)
    {
        $this->attributes['ma_xac_nhan'] = $maXacNhan;
        
        return $this;
    }
    
    /**
     * Checkin bằng face ID
     */
    public function checkinWithFaceId(string $imagePath, float $matchScore = 0)
    {
        $this->setCheckinType(self::CHECKIN_FACE_ID);
        $this->setFaceImagePath($imagePath);
        $this->setFaceMatchScore($matchScore);
        $this->attributes['face_verified'] = true;
        $this->attributes['thoi_gian_check_in'] = date('Y-m-d H:i:s');
        $this->attributes['status'] = 1;
        
        return $this;
    }
    
    /**
     * Checkin thủ công
     */
    public function checkinManual(int $nguoiCheckinId, string $maXacNhan = '')
    {
        $this->setCheckinType(self::CHECKIN_MANUAL);
        $this->setNguoiCheckinId($nguoiCheckinId);
        $this->setMaXacNhan($maXacNhan);
        $this->attributes['thoi_gian_check_in'] = date('Y-m-d H:i:s');
        $this->attributes['status'] = 1;
        
        return $this;
    }
    
    /**
     * Checkin bằng QR code
     */
    public function checkinWithQrCode(string $maXacNhan)
    {
        $this->setCheckinType(self::CHECKIN_QR_CODE);
        $this->setMaXacNhan($maXacNhan);
        $this->attributes['thoi_gian_check_in'] = date('Y-m-d H:i:s');
        $this->attributes['status'] = 1;
        
        return $this;
    }
    
    /**
     * Tạo checkout từ thông tin checkin
     */
    public function taoCheckout()
    {
        $checkout = new CheckoutSukienEntity();
        $checkout->setNguoiDungId($this->getNguoiDungId());
        $checkout->setSuKienId($this->getSuKienId());
        
        if ($this->getDangKySuKienId()) {
            $checkout->setDangKySuKienId($this->getDangKySuKienId());
        }
        
        return $checkout;
    }
} 