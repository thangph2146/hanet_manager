<?php

namespace App\Modules\students\Models;

use CodeIgniter\Model;

class CheckoutSukienModel extends Model
{
    protected $table = 'checkout_sukien';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'user_id', 'su_kien_id', 'thoi_gian_checkout', 'diem_danh_gia', 'nhan_xet', 'ghi_chu'
    ];
    
    protected $useTimestamps = false;
    
    // Kiểm tra sinh viên đã check-out sự kiện chưa
    public function isCheckedOut($studentId, $eventId)
    {
        return $this->where('user_id', $studentId)
                    ->where('su_kien_id', $eventId)
                    ->countAllResults() > 0;
    }
    
    // Check-out sự kiện
    public function checkOutEvent($studentId, $eventId, $rating = null, $comment = '', $note = '')
    {
        // Kiểm tra xem sinh viên đã check-out sự kiện này chưa
        if ($this->isCheckedOut($studentId, $eventId)) {
            return false;
        }
        
        // Kiểm tra xem sinh viên đã check-in sự kiện chưa
        $checkinSukienModel = new CheckinSukienModel();
        if (!$checkinSukienModel->isCheckedIn($studentId, $eventId)) {
            return false;
        }
        
        // Check-out sự kiện
        $data = [
            'user_id' => $studentId,
            'su_kien_id' => $eventId,
            'thoi_gian_checkout' => date('Y-m-d H:i:s'),
            'diem_danh_gia' => $rating,
            'nhan_xet' => $comment,
            'ghi_chu' => $note
        ];
        
        $result = $this->insert($data);
        
        // Cập nhật trạng thái đăng ký
        if ($result) {
            $dangKySukienModel = new DangKySukienModel();
            $dangKySukienModel->update(
                $dangKySukienModel->where('user_id', $studentId)
                                ->where('su_kien_id', $eventId)
                                ->first()['id'],
                ['trang_thai' => 'checked-out']
            );
        }
        
        return $result;
    }
    
    // Lấy thời gian check-out của sinh viên
    public function getCheckOutTime($studentId, $eventId)
    {
        $result = $this->where('user_id', $studentId)
                      ->where('su_kien_id', $eventId)
                      ->first();
                      
        return $result ? $result['thoi_gian_checkout'] : null;
    }
    
    // Lấy danh sách sự kiện đã check-out của sinh viên
    public function getCompletedEvents($studentId)
    {
        return $this->select('checkout_sukien.*, sukien.ten_su_kien, sukien.ngay_to_chuc, sukien.dia_diem, sukien.hinh_anh, loai_sukien.loai_su_kien')
                    ->join('sukien', 'sukien.id = checkout_sukien.su_kien_id', 'left')
                    ->join('loai_sukien', 'loai_sukien.id = sukien.loai_sukien_id', 'left')
                    ->where('checkout_sukien.user_id', $studentId)
                    ->orderBy('checkout_sukien.thoi_gian_checkout', 'DESC')
                    ->findAll();
    }
} 