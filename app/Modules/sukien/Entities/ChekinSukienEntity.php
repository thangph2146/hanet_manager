<?php

namespace App\Modules\sukien\Entities;

use CodeIgniter\Entity\Entity;

class CheckinSukienEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['thoi_gian_check_in', 'created_at', 'updated_at', 'deleted_at'];
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
}
