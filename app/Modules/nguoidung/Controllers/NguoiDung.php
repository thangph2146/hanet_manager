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
    
    public function eventsCheckin()
    {
        // Lấy thông tin người dùng hiện tại
        $profile = getInfoStudent();
        $email = $profile->Email;
        
        // Lấy các sự kiện đã tham gia
        $attendedEvents = $this->dangkysukienModel->getRegistrationsByEmail($email, [
            'join_event_info' => true,
            'where' => [
                'da_check_in' => 1
            ],
            'order' => [
                'su_kien.thoi_gian_bat_dau' => 'DESC'
            ]
        ]);
        
        $data = [
            'title' => 'Sự kiện đã tham gia',
            'active_menu' => 'events_checkin',
            'profile' => $profile,
            'attendedEvents' => $attendedEvents
        ];
        
        return view('App\Modules\nguoidung\Views\eventscheckin', $data);
    }

    public function eventsHistoryRegister()
    {
        // Lấy thông tin người dùng hiện tại
        $profile = getInfoStudent();
        $email = $profile->Email;
        
        // Lấy các sự kiện đã đăng ký (tất cả trạng thái)
        $registeredEvents = $this->dangkysukienModel->getRegistrationsByEmail($email, [
            'join_event_info' => true,
            'order' => [
                'su_kien.thoi_gian_bat_dau' => 'DESC'
            ]
        ]);
        
        $data = [
            'title' => 'Lịch sử đăng ký sự kiện',
            'active_menu' => 'events_history_register',
            'profile' => $profile,
            'registeredEvents' => $registeredEvents
        ];
        
        return view('App\Modules\nguoidung\Views\eventshistoryregister', $data);
    }
    
    /**
     * Hiển thị trang danh sách sự kiện
     */
    public function eventsList()
    {
        // Load text helper
        helper('text');
        
        // Lấy model sự kiện
        $sukienModel = $this->sukienModel;
        $dangkyModel = $this->dangkysukienModel;
        
        // Lấy thông tin người dùng hiện tại
        $userData = session()->get('userData') ?? null;
        $userEmail = $userData ? ($userData['email'] ?? '') : '';
        
        // Khởi tạo các biến cần thiết
        $events = [];
        $upcomingCount = 0;
        $registeredCount = 0;
        $attendedCount = 0;
        $userEvents = [];
        $attendedEvents = [];
        
        // Lấy danh sách sự kiện
        $events = $sukienModel->findAll() ?? [];
        
        // Đếm số sự kiện sắp diễn ra
        $now = date('Y-m-d H:i:s');
        $upcomingCount = count(array_filter($events, function($event) use ($now) {
            return isset($event->thoi_gian_bat_dau) && $event->thoi_gian_bat_dau > $now;
        }));
        
        // Nếu người dùng đã đăng nhập, lấy danh sách sự kiện đã đăng ký và đã tham gia
        if (!empty($userEmail)) {
            // Lấy danh sách sự kiện đã đăng ký
            $registered = $dangkyModel->where('email', $userEmail)->findAll() ?? [];
            
            // Lọc ra các ID sự kiện đã đăng ký
            $userEvents = array_map(function($regEvent) {
                return $regEvent->ma_su_kien;
            }, $registered);
            
            // Đếm số sự kiện đã đăng ký
            $registeredCount = count($userEvents);
            
            // Lọc ra các ID sự kiện đã tham gia (đã check-in)
            $attendedEvents = array_map(function($regEvent) {
                return $regEvent->ma_su_kien;
            }, array_filter($registered, function($regEvent) {
                return isset($regEvent->da_check_in) && $regEvent->da_check_in == 1;
            }));
            
            // Đếm số sự kiện đã tham gia
            $attendedCount = count($attendedEvents);
        }
        
        // Lấy danh sách phân loại sự kiện
        $categories = $sukienModel->getCategories() ?? [];
        
        // Lấy các bộ lọc hiện tại
        $current_filter = [
            'search' => $this->request->getGet('search'),
            'category' => $this->request->getGet('category'),
            'status' => $this->request->getGet('status'),
            'sort' => $this->request->getGet('sort') ?: 'upcoming'
        ];
        
        // Truyền dữ liệu đến view
        $data = [
            'title' => 'Danh sách sự kiện',
            'events' => $events,
            'categories' => $categories,
            'upcomingCount' => $upcomingCount,
            'registeredCount' => $registeredCount,
            'attendedCount' => $attendedCount,
            'userEvents' => $userEvents,
            'attendedEvents' => $attendedEvents,
            'current_filter' => $current_filter
        ];
        
        // Hiển thị view
        return view('App\Modules\nguoidung\Views\eventslist', $data);
    }
   
} 