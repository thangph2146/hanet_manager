<?php

namespace App\Modules\sukien\Models;

use CodeIgniter\Model;

class CheckoutSukienModel extends Model
{
    protected $table            = 'checkout_su_kien';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['nguoi_dung_id', 'su_kien_id', 'thoi_gian_check_out', 'status', 'bin'];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Mock Data - Dữ liệu mẫu cho check-out sự kiện
    private $mockCheckouts = [
        [
            'id' => 1,
            'nguoi_dung_id' => 101,
            'su_kien_id' => 1,
            'thoi_gian_check_out' => '2023-06-15 16:30:00',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-06-15 16:30:00',
            'updated_at' => '2023-06-15 16:30:00',
            'deleted_at' => null
        ],
        [
            'id' => 2,
            'nguoi_dung_id' => 102,
            'su_kien_id' => 1,
            'thoi_gian_check_out' => '2023-06-15 16:45:00',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-06-15 16:45:00',
            'updated_at' => '2023-06-15 16:45:00',
            'deleted_at' => null
        ]
    ];

    /**
     * Lấy danh sách check-out theo sự kiện
     */
    public function getCheckoutsByEvent($eventId)
    {
        // Trong triển khai thực tế:
        // return $this->where('su_kien_id', $eventId)
        //             ->where('status', 1)
        //             ->findAll();
        
        // Sử dụng mock data cho demo
        $checkouts = [];
        foreach ($this->mockCheckouts as $checkout) {
            if ($checkout['su_kien_id'] == $eventId && $checkout['status'] == 1) {
                $checkouts[] = $checkout;
            }
        }
        return $checkouts;
    }
    
    /**
     * Đếm số lượng check-out theo sự kiện
     */
    public function countCheckoutsByEvent($eventId)
    {
        // Trong triển khai thực tế:
        // return $this->where('su_kien_id', $eventId)
        //             ->where('status', 1)
        //             ->countAllResults();
        
        // Sử dụng mock data cho demo
        $count = 0;
        foreach ($this->mockCheckouts as $checkout) {
            if ($checkout['su_kien_id'] == $eventId && $checkout['status'] == 1) {
                $count++;
            }
        }
        return $count;
    }
    
    /**
     * Kiểm tra xem người dùng đã check-out khỏi sự kiện chưa
     */
    public function hasUserCheckedOut($userId, $eventId)
    {
        // Trong triển khai thực tế:
        // return $this->where('nguoi_dung_id', $userId)
        //             ->where('su_kien_id', $eventId)
        //             ->where('status', 1)
        //             ->countAllResults() > 0;
        
        // Sử dụng mock data cho demo
        foreach ($this->mockCheckouts as $checkout) {
            if ($checkout['nguoi_dung_id'] == $userId && 
                $checkout['su_kien_id'] == $eventId && 
                $checkout['status'] == 1) {
                return true;
            }
        }
        return false;
    }
} 