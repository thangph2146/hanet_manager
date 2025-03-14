<?php

namespace App\Modules\sukien\Models;

use CodeIgniter\Model;

class CheckinSukienModel extends Model
{
    protected $table            = 'checkin_su_kien';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['nguoi_dung_id', 'su_kien_id', 'thoi_gian_check_in', 'status', 'bin'];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Mock Data - Dữ liệu mẫu cho check-in sự kiện
    private $mockCheckins = [
        [
            'id' => 1,
            'nguoi_dung_id' => 101,
            'su_kien_id' => 1,
            'thoi_gian_check_in' => '2023-06-15 07:45:00',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-06-15 07:45:00',
            'updated_at' => '2023-06-15 07:45:00',
            'deleted_at' => null
        ],
        [
            'id' => 2,
            'nguoi_dung_id' => 102,
            'su_kien_id' => 1,
            'thoi_gian_check_in' => '2023-06-15 07:50:00',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-06-15 07:50:00',
            'updated_at' => '2023-06-15 07:50:00',
            'deleted_at' => null
        ],
        [
            'id' => 3,
            'nguoi_dung_id' => 103,
            'su_kien_id' => 2,
            'thoi_gian_check_in' => '2023-06-22 08:20:00',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-06-22 08:20:00',
            'updated_at' => '2023-06-22 08:20:00',
            'deleted_at' => null
        ]
    ];

    /**
     * Lấy danh sách check-in theo sự kiện
     */
    public function getCheckinsByEvent($eventId)
    {
        // Trong triển khai thực tế:
        // return $this->where('su_kien_id', $eventId)
        //             ->where('status', 1)
        //             ->findAll();
        
        // Sử dụng mock data cho demo
        $checkins = [];
        foreach ($this->mockCheckins as $checkin) {
            if ($checkin['su_kien_id'] == $eventId && $checkin['status'] == 1) {
                $checkins[] = $checkin;
            }
        }
        return $checkins;
    }
    
    /**
     * Đếm số lượng check-in theo sự kiện
     */
    public function countCheckinsByEvent($eventId)
    {
        // Trong triển khai thực tế:
        // return $this->where('su_kien_id', $eventId)
        //             ->where('status', 1)
        //             ->countAllResults();
        
        // Sử dụng mock data cho demo
        $count = 0;
        foreach ($this->mockCheckins as $checkin) {
            if ($checkin['su_kien_id'] == $eventId && $checkin['status'] == 1) {
                $count++;
            }
        }
        return $count;
    }
    
    /**
     * Kiểm tra xem người dùng đã check-in cho sự kiện chưa
     */
    public function hasUserCheckedIn($userId, $eventId)
    {
        // Trong triển khai thực tế:
        // return $this->where('nguoi_dung_id', $userId)
        //             ->where('su_kien_id', $eventId)
        //             ->where('status', 1)
        //             ->countAllResults() > 0;
        
        // Sử dụng mock data cho demo
        foreach ($this->mockCheckins as $checkin) {
            if ($checkin['nguoi_dung_id'] == $userId && 
                $checkin['su_kien_id'] == $eventId && 
                $checkin['status'] == 1) {
                return true;
            }
        }
        return false;
    }
} 