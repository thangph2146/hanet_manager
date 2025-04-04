<?php

namespace App\Modules\nguoidung\Controllers;

use App\Controllers\BaseController;
use App\Modules\quanlynguoidung\Models\NguoiDungModel;
use App\Modules\quanlydangkysukien\Models\DangKySuKienModel;
use App\Modules\quanlysukien\Models\SuKienModel;
use DateTime;

class NguoiDung extends BaseController
{
    protected $nguoidungModel;
    protected $dangkysukienModel;
    protected $sukienModel;
    
    public function __construct()
    {
        // Khởi tạo model
        $this->nguoidungModel = new NguoiDungModel();
        $this->dangkysukienModel = new DangKySuKienModel();
        $this->sukienModel = new SuKienModel();
        
        // Kiểm tra session người dùng
        if (!service('authStudent')->isLoggedInStudent()) {
            // Lưu URL hiện tại vào session để sau khi đăng nhập thì quay lại
            $uri = service('uri');
            $currentUrl = (string)$uri;
            if (!empty($currentUrl)) {
                $_SESSION['redirect_url'] = $currentUrl;
            }
            
            // Chuyển hướng về trang chủ
            header('Location: /');
            exit;
        }
    }
    
    /**
     * Chuyển đổi định dạng datetime sang dd/mm/yyyy h:i:s
     * 
     * @param string $datetime Chuỗi ngày giờ cần chuyển đổi
     * @param bool $includeSeconds Có hiển thị giây hay không
     * @return string
     */
    protected function formatDateTime($datetime, $includeSeconds = true)
    {
        if (empty($datetime)) {
            return '';
        }
        
        try {
            $date = new DateTime($datetime);
            $format = $includeSeconds ? 'd/m/Y H:i:s' : 'd/m/Y H:i';
            return $date->format($format);
        } catch (\Exception $e) {
            return $datetime;
        }
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
    
    public function eventsHistoryRegister()
    {
        // Load text helper
        helper('text');
        
        // Lấy thông tin người dùng hiện tại
        $profile = getInfoStudent();
        $email = $profile->Email;
        
        // Lấy các tham số lọc thời gian
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $search = $this->request->getGet('search');
        
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
        
        // Áp dụng bộ lọc tìm kiếm theo từ khóa
        if (!empty($search)) {
            $search = strtolower($search);
            $attendedEvents = array_filter($attendedEvents, function($event) use ($search) {
                return (
                    stripos(strtolower($event->ten_sukien ?? ''), $search) !== false ||
                    stripos(strtolower($event->dia_diem ?? ''), $search) !== false ||
                    stripos(strtolower($event->to_chuc ?? ''), $search) !== false
                );
            });
        }
        
        // Áp dụng bộ lọc thời gian nếu có trong eventsCheckin
        if (!empty($startDate) || !empty($endDate)) {
            $attendedEvents = array_filter($attendedEvents, function($event) use ($startDate, $endDate) {
                $eventDate = isset($event->ngay_to_chuc) ? new DateTime($event->ngay_to_chuc) : null;
                
                if (!$eventDate) {
                    return true; // Giữ lại sự kiện nếu không có ngày
                }
                
                // Lọc theo ngày giờ bắt đầu
                if (!empty($startDate)) {
                    $startDateTime = new DateTime($startDate);
                    if ($eventDate < $startDateTime) {
                        return false;
                    }
                }
                
                // Lọc theo ngày giờ kết thúc
                if (!empty($endDate)) {
                    $endDateTime = new DateTime($endDate);
                    if ($eventDate > $endDateTime) {
                        return false;
                    }
                }
                
                return true;
            });
        }
        
        $data = [
            'title' => 'Sự kiện đã tham gia',
            'active_menu' => 'events_checkin',
            'profile' => $profile,
            'attendedEvents' => $attendedEvents,
            'current_filter' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'search' => $search
            ],
            'formatted_filter' => [
                'start_date_formatted' => $this->formatDateTime($startDate, true),
                'end_date_formatted' => $this->formatDateTime($endDate, true)
            ]
        ];
        
        return view('App\Modules\nguoidung\Views\eventscheckin', $data);
    }

    public function eventsCheckin()
    {
        // Load text helper
        helper('text');
        
        // Lấy thông tin người dùng hiện tại
        $profile = getInfoStudent();
        $email = $profile->Email;
        
        // Lấy các tham số lọc thời gian
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $search = $this->request->getGet('search');
        
        // Lấy các sự kiện đã đăng ký (tất cả trạng thái)
        $registeredEvents = $this->dangkysukienModel->getRegistrationsByEmail($email, [
            'join_event_info' => true,
            'order' => [
                'su_kien.thoi_gian_bat_dau' => 'DESC'
            ]
        ]);
        
        // Áp dụng bộ lọc tìm kiếm theo từ khóa
        if (!empty($search)) {
            $search = strtolower($search);
            $registeredEvents = array_filter($registeredEvents, function($event) use ($search) {
                return (
                    stripos(strtolower($event->ten_sukien ?? ''), $search) !== false ||
                    stripos(strtolower($event->dia_diem ?? ''), $search) !== false ||
                    stripos(strtolower($event->to_chuc ?? ''), $search) !== false
                );
            });
        }
        
        // Áp dụng bộ lọc thời gian nếu có trong eventsHistoryRegister
        if (!empty($startDate) || !empty($endDate)) {
            $registeredEvents = array_filter($registeredEvents, function($event) use ($startDate, $endDate) {
                $eventDate = isset($event->ngay_to_chuc) ? new DateTime($event->ngay_to_chuc) : null;
                
                if (!$eventDate) {
                    return true; // Giữ lại sự kiện nếu không có ngày
                }
                
                // Lọc theo ngày giờ bắt đầu
                if (!empty($startDate)) {
                    $startDateTime = new DateTime($startDate);
                    if ($eventDate < $startDateTime) {
                        return false;
                    }
                }
                
                // Lọc theo ngày giờ kết thúc
                if (!empty($endDate)) {
                    $endDateTime = new DateTime($endDate);
                    if ($eventDate > $endDateTime) {
                        return false;
                    }
                }
                
                return true;
            });
        }
        
        // Đếm số lượng mỗi loại trạng thái
        $attendedEvents = 0;
        $pendingEvents = 0;
        $cancelledEvents = 0;
        
        foreach ($registeredEvents as $event) {
            if ($event->trang_thai_dang_ky == 0) {
                $cancelledEvents++;
            } else if ($event->da_check_in == 1) {
                $attendedEvents++;
            } else {
                $pendingEvents++;
            }
        }
        
        $data = [
            'title' => 'Lịch sử đăng ký sự kiện',
            'active_menu' => 'events_history_register',
            'profile' => $profile,
            'registeredEvents' => $registeredEvents,
            'attendedEvents' => $attendedEvents,
            'pendingEvents' => $pendingEvents,
            'cancelledEvents' => $cancelledEvents,
            'current_filter' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'search' => $search
            ],
            'formatted_filter' => [
                'start_date_formatted' => $this->formatDateTime($startDate, true),
                'end_date_formatted' => $this->formatDateTime($endDate, true)
            ]
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
        
        // Lấy thông tin người dùng hiện tại
        $profile = getInfoStudent();
        $email = $profile->Email;
        
        // Lấy các tham số lọc thời gian
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $search = $this->request->getGet('search');
        
        // Lấy danh sách sự kiện từ model
        $events = $this->sukienModel->getEvents([
            'status' => 'published',
            'order' => [
                'thoi_gian_bat_dau' => 'ASC'
            ]
        ]);
        
        // Áp dụng bộ lọc tìm kiếm theo từ khóa
        if (!empty($search)) {
            $search = strtolower($search);
            $events = array_filter($events, function($event) use ($search) {
                return (
                    stripos(strtolower($event->ten_sukien ?? ''), $search) !== false ||
                    stripos(strtolower($event->dia_diem ?? ''), $search) !== false ||
                    stripos(strtolower($event->to_chuc ?? ''), $search) !== false ||
                    stripos(strtolower($event->mo_ta ?? ''), $search) !== false
                );
            });
        }
        
        // Lấy danh sách phân loại sự kiện
        $categories = $this->sukienModel->getCategories() ?? [];
        
        // Lấy các bộ lọc hiện tại
        $current_filter = [
            'search' => $search,
            'category' => $this->request->getGet('category'),
            'status' => $this->request->getGet('status'),
            'sort' => $this->request->getGet('sort') ?: 'upcoming',
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        // Format hiển thị ngày giờ theo định dạng dd/mm/yyyy h:i:s
        $formatted_filter = [
            'start_date_formatted' => $this->formatDateTime($startDate, true),
            'end_date_formatted' => $this->formatDateTime($endDate, true)
        ];
        
        // Áp dụng bộ lọc thời gian nếu có
        if (!empty($current_filter['start_date']) || !empty($current_filter['end_date'])) {
            $events = array_filter($events, function($event) use ($current_filter) {
                $eventDate = isset($event->thoi_gian_bat_dau) ? new DateTime($event->thoi_gian_bat_dau) : null;
                
                if (!$eventDate) {
                    return true; // Giữ lại sự kiện nếu không có ngày
                }
                
                // Lọc theo ngày và giờ bắt đầu
                if (!empty($current_filter['start_date'])) {
                    $startDate = new DateTime($current_filter['start_date']);
                    if ($eventDate < $startDate) {
                        return false;
                    }
                }
                
                // Lọc theo ngày và giờ kết thúc
                if (!empty($current_filter['end_date'])) {
                    $endDate = new DateTime($current_filter['end_date']);
                    if ($eventDate > $endDate) {
                        return false;
                    }
                }
                
                return true;
            });
        }
        
        // Truyền dữ liệu đến view
        $data = [
            'title' => 'Danh sách sự kiện',
            'active_menu' => 'events',
            'profile' => $profile,
            'events' => $events,
            'categories' => $categories,
            'current_filter' => $current_filter,
            'formatted_filter' => $formatted_filter
        ];
        
        // Hiển thị view
        return view('App\Modules\nguoidung\Views\eventslist', $data);
    }
   
}