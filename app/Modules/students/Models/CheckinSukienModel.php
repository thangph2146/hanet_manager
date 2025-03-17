<?php

namespace App\Modules\students\Models;

use CodeIgniter\Model;

class CheckinSukienModel extends Model
{
    protected $table = 'checkin_sukien';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'user_id', 'su_kien_id', 'thoi_gian_checkin', 'ghi_chu'
    ];
    
    protected $useTimestamps = false;
    
    // Kiểm tra sinh viên đã check-in sự kiện chưa
    public function isCheckedIn($studentId, $eventId)
    {
        return $this->where('user_id', $studentId)
                    ->where('su_kien_id', $eventId)
                    ->countAllResults() > 0;
    }
    
    // Check-in sự kiện
    public function checkInEvent($studentId, $eventId, $note = '')
    {
        // Kiểm tra xem sinh viên đã check-in sự kiện này chưa
        if ($this->isCheckedIn($studentId, $eventId)) {
            return false;
        }
        
        // Kiểm tra xem sinh viên đã đăng ký sự kiện chưa
        $dangKySukienModel = new DangKySukienModel();
        if (!$dangKySukienModel->isRegistered($studentId, $eventId)) {
            return false;
        }
        
        // Check-in sự kiện
        $data = [
            'user_id' => $studentId,
            'su_kien_id' => $eventId,
            'thoi_gian_checkin' => date('Y-m-d H:i:s'),
            'ghi_chu' => $note
        ];
        
        $result = $this->insert($data);
        
        // Cập nhật trạng thái đăng ký
        if ($result) {
            $dangKySukienModel->update(
                $dangKySukienModel->where('user_id', $studentId)
                                ->where('su_kien_id', $eventId)
                                ->first()['id'],
                ['trang_thai' => 'checked-in']
            );
        }
        
        return $result;
    }
    
    // Lấy thời gian check-in của sinh viên
    public function getCheckInTime($studentId, $eventId)
    {
        $result = $this->where('user_id', $studentId)
                      ->where('su_kien_id', $eventId)
                      ->first();
                      
        return $result ? $result['thoi_gian_checkin'] : null;
    }
} 