<?php

namespace App\Modules\sukien\Entities;

use CodeIgniter\Entity\Entity;

class CheckoutSukienEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['thoi_gian_check_out', 'created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'               => 'int',
        'nguoi_dung_id'    => 'int',
        'su_kien_id'       => 'int',
        'status'           => 'int',
        'bin'              => 'int',
    ];
    
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
}
