<?php

namespace App\Modules\sukien\Models;

use App\Modules\quanlycheckoutsukien\Models\CheckoutSuKienModel as BaseModel;

class CheckoutSukienModel extends BaseModel
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Kiểm tra xem người dùng đã check-out khỏi sự kiện chưa
     * 
     * @param int $userId ID của người dùng
     * @param int $eventId ID của sự kiện
     * @return bool
     */
    public function hasUserCheckedOut($userId, $eventId)
    {
        return $this->where('nguoi_dung_id', $userId)
                    ->where('su_kien_id', $eventId)
                    ->where('deleted_at IS NULL')
                    ->countAllResults() > 0;
    }
} 