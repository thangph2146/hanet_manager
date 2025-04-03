<?php

namespace App\Modules\nguoidung\Controllers;

use App\Controllers\BaseController;
use App\Modules\nguoidung\Models\NguoiDungModel;
use App\Modules\quanlydangkysukien\Models\DangKySuKienModel;
use App\Modules\quanlysukien\Models\SuKienModel;

class NguoiDung extends BaseController
{
    protected $nguoidungModel;
    protected $dangkysukienModel;
    protected $sukienModel;
    
    public function __construct()
    {
        // Khởi tạo model
        $this->dangkysukienModel = new DangKySuKienModel();
        $this->sukienModel = new SuKienModel();
    }
    
    /**
     * Hiển thị trang thông tin cá nhân
     */
    public function profile()
    {
        $data = [
            'title' => 'Thông tin cá nhân',
            'active_menu' => 'profile',
            'profile' => getInfoStudent()
        ];
        return view('App\Modules\nguoidung\Views\profile', $data);
    }
    
    /**
     * Hiển thị trang dashboard
     */
    public function dashboard()
    {
        // Lấy thông tin người dùng hiện tại
        $profile = getInfoStudent();
        $email = $profile->Email;
        
        // Lấy các sự kiện đã đăng ký (tất cả trạng thái)
        $registeredEvents = $this->dangkysukienModel->getRegistrationsByEmail($email, [
            'join_event_info' => true,
            'order' => [
                'su_kien.thoi_gian_bat_dau' => 'DESC'
            ],
            'limit' => 5
        ]);
        
        // Lấy các sự kiện đã tham gia
        $attendedEvents = $this->dangkysukienModel->getRegistrationsByEmail($email, [
            'join_event_info' => true,
            'where' => [
                'da_check_in' => 1
            ],
            'order' => [
                'su_kien.thoi_gian_bat_dau' => 'DESC'
            ],
            'limit' => 5
        ]);
        
        // Lấy số lượng thống kê
        $registeredCount = $this->dangkysukienModel->countRegistrationsByEmail($email);
        $attendedCount = $this->dangkysukienModel->countRegistrationsByEmail($email, [
            'where' => ['da_check_in' => 1]
        ]);
        
        // Lấy các sự kiện sắp diễn ra
        $upcomingEvents = $this->sukienModel->getUpcomingEvents(5);
        
        $data = [
            'title' => 'Dashboard',
            'active_menu' => 'dashboard',
            'profile' => $profile,
            'registeredEvents' => $registeredEvents,
            'attendedEvents' => $attendedEvents,
            'upcomingEvents' => $upcomingEvents,
            'stats' => [
                'registered' => $registeredCount,
                'attended' => $attendedCount,
                'completion_rate' => $registeredCount > 0 ? round(($attendedCount / $registeredCount) * 100) : 0
            ]
        ];
        
        return view('App\Modules\nguoidung\Views\dashboard', $data);
    }
    
   
} 