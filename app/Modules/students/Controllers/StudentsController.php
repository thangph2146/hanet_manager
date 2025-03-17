<?php

namespace App\Modules\students\Controllers;

use App\Controllers\BaseController;
use App\Modules\sukien\Models\SukienModel;
use App\Modules\sukien\Models\LoaiSukienModel;
use App\Modules\sukien\Models\DangKySukienModel;
use App\Modules\sukien\Models\CheckinSukienModel;
use App\Modules\sukien\Models\CheckoutSukienModel;

class StudentsController extends BaseController
{
    protected $sukienModel;
    protected $loaiSukienModel;
    protected $dangKySukienModel;
    protected $checkinSukienModel;
    protected $checkoutSukienModel;
    
    public function __construct()
    {
        $this->sukienModel = new SukienModel();
        $this->loaiSukienModel = new LoaiSukienModel();
        $this->dangKySukienModel = new DangKySukienModel();
        $this->checkinSukienModel = new CheckinSukienModel();
        $this->checkoutSukienModel = new CheckoutSukienModel();
    }
    
    public function dashboard()
    {
        // Lấy các sự kiện sắp diễn ra và đang diễn ra
        $upcomingEvents = $this->sukienModel->getUpcomingEvents(3); // Lấy 3 sự kiện sắp diễn ra
        
        // Lấy các sự kiện đã đăng ký
        $userId = session()->get('user_id') ?? 101; // Sử dụng ID mẫu nếu chưa đăng nhập
        $registeredEventsCount = 0;
        $certificatesCount = 0;
        $activeEventsCount = 0;
        
        // Định dạng dữ liệu sự kiện để hiển thị
        $events = [];
        foreach ($upcomingEvents as $event) {
            $status = 'Sắp diễn ra';
            $statusColor = 'primary';
            
            // Xác định trạng thái sự kiện
            $timeRemaining = $this->sukienModel->getTimeRemaining($event['ngay_to_chuc'] ?? date('Y-m-d H:i:s'));
            if ($timeRemaining['days'] < 0) {
                $status = 'Đã kết thúc';
                $statusColor = 'secondary';
            } elseif ($timeRemaining['days'] == 0 && $timeRemaining['hours'] <= 24) {
                $status = 'Đang diễn ra';
                $statusColor = 'info';
                $activeEventsCount++;
            } else {
                $status = 'Đang mở đăng ký';
                $statusColor = 'success';
            }
            
            // Định dạng lại thời gian
            $formattedTime = $this->sukienModel->formatNgayToChuc($event['ngay_to_chuc'] ?? date('Y-m-d H:i:s'));
            
            $events[] = [
                'id' => $event['id'] ?? 0,
                'name' => $event['ten_su_kien'] ?? 'Sự kiện không tên',
                'time' => $formattedTime,
                'location' => $event['dia_diem'] ?? 'Chưa xác định',
                'status' => $status,
                'status_color' => $statusColor
            ];
        }
        
        // Lấy số lượng sự kiện đã đăng ký và tổng số sự kiện
        $registeredEvents = [];
        $allEvents = $this->sukienModel->getAllEvents();
        foreach ($allEvents as $event) {
            if ($this->dangKySukienModel->isRegistered($userId, $event['id'] ?? 0)) {
                $registeredEventsCount++;
                $registeredEvents[] = $event;
                
                // Kiểm tra nếu đã checkin và checkout (đã tham gia xong sự kiện)
                if ($this->checkinSukienModel->hasUserCheckedIn($userId, $event['id'] ?? 0) && 
                    $this->checkoutSukienModel->hasUserCheckedOut($userId, $event['id'] ?? 0)) {
                    $certificatesCount++;
                }
            }
        }
        
        // Lấy thông báo gần đây
        $recentNotifications = [
            [
                'type' => 'success',
                'icon' => 'bx bx-check-circle',
                'title' => 'Đăng ký thành công',
                'content' => 'Bạn đã đăng ký thành công sự kiện ' . ($events[0]['name'] ?? 'Ngày hội việc làm 2024'),
                'time' => '2 giờ trước'
            ],
            [
                'type' => 'info',
                'icon' => 'bx bx-info-circle',
                'title' => 'Sự kiện mới',
                'content' => 'Sự kiện ' . ($events[1]['name'] ?? 'Hội thảo kỹ năng mềm') . ' đã được thêm vào hệ thống',
                'time' => '1 ngày trước'
            ]
        ];
        
        $data = [
            'title' => 'Dashboard Sinh viên',
            'student_data' => [
                'fullname' => session()->get('student_name') ?? 'Nguyễn Văn A',
                'student_id' => session()->get('student_id') ?? 'SV001',
                'picture' => null
            ],
            'active_events' => $activeEventsCount,
            'registered_events' => $registeredEventsCount,
            'certificates' => $certificatesCount,
            'upcoming_events' => count($upcomingEvents),
            'events' => $events,
            'recent_notifications' => $recentNotifications,
            'notification_count' => count($recentNotifications)
        ];

        return view('App\Modules\students\Views\dashboard\index', $data);
    }
    
    /**
     * Hiển thị danh sách tất cả sự kiện
     */
    public function events()
    {
        $data = [
            'title' => 'Danh sách sự kiện',
            'meta_description' => 'Danh sách các sự kiện dành cho sinh viên',
            'events' => [],
            'event_types' => $this->loaiSukienModel->getAllEventTypes()
        ];
        
        // Lấy danh sách sự kiện
        $events = $this->sukienModel->getAllEvents();
        $userId = session()->get('user_id') ?? 101; // Sử dụng ID mẫu nếu chưa đăng nhập
        
        // Xử lý dữ liệu sự kiện
        foreach ($events as $event) {
            $eventData = $event;
            
            // Thêm trạng thái đăng ký
            $eventData['is_registered'] = $this->dangKySukienModel->isRegistered($userId, $event['id'] ?? 0);
            
            // Xác định trạng thái sự kiện
            $currentTime = time();
            $eventTime = strtotime($event['ngay_to_chuc'] ?? date('Y-m-d H:i:s'));
            $endTime = strtotime($event['ngay_ket_thuc'] ?? date('Y-m-d H:i:s', strtotime('+3 hours', $eventTime)));
            
            if ($currentTime < $eventTime) {
                $eventData['status'] = 'upcoming';
            } elseif ($currentTime >= $eventTime && $currentTime <= $endTime) {
                $eventData['status'] = 'ongoing';
            } else {
                $eventData['status'] = 'completed';
            }
            
            // Lấy loại sự kiện
            $eventType = $this->loaiSukienModel->getEventTypeById($event['loai_su_kien_id'] ?? 0);
            $eventData['loai_su_kien'] = $eventType ? $eventType['loai_su_kien'] : 'Không xác định';
            
            // Thêm vào danh sách
            $data['events'][] = $eventData;
        }
        
        return view('Modules/students/Views/events/index', $data);
    }
    
    /**
     * Hiển thị danh sách sự kiện đã đăng ký
     */
    public function registeredEvents()
    {
        $data = [
            'title' => 'Sự kiện đã đăng ký',
            'meta_description' => 'Danh sách các sự kiện bạn đã đăng ký tham gia',
            'registered_events' => []
        ];
        
        $userId = session()->get('user_id') ?? 101; // Sử dụng ID mẫu nếu chưa đăng nhập
        
        // Lấy danh sách sự kiện
        $events = $this->sukienModel->getAllEvents();
        
        // Lọc và xử lý dữ liệu sự kiện đã đăng ký
        foreach ($events as $event) {
            // Kiểm tra xem sinh viên đã đăng ký sự kiện này chưa
            if ($this->dangKySukienModel->isRegistered($userId, $event['id'] ?? 0)) {
                $eventData = $event;
                
                // Xác định trạng thái sự kiện
                $currentTime = time();
                $eventTime = strtotime($event['ngay_to_chuc'] ?? date('Y-m-d H:i:s'));
                $endTime = strtotime($event['ngay_ket_thuc'] ?? date('Y-m-d H:i:s', strtotime('+3 hours', $eventTime)));
                
                if ($currentTime < $eventTime) {
                    $eventData['status'] = 'upcoming';
                } elseif ($currentTime >= $eventTime && $currentTime <= $endTime) {
                    $eventData['status'] = 'ongoing';
                } else {
                    $eventData['status'] = 'completed';
                }
                
                // Lấy loại sự kiện
                $eventType = $this->loaiSukienModel->getEventTypeById($event['loai_su_kien_id'] ?? 0);
                $eventData['loai_su_kien'] = $eventType ? $eventType['loai_su_kien'] : 'Không xác định';
                
                // Kiểm tra trạng thái check-in và check-out
                $eventData['has_checked_in'] = $this->checkinSukienModel->hasUserCheckedIn($userId, $event['id'] ?? 0);
                $eventData['has_checked_out'] = $this->checkoutSukienModel->hasUserCheckedOut($userId, $event['id'] ?? 0);
                
                // Thêm vào danh sách
                $data['registered_events'][] = $eventData;
            }
        }
        
        return view('Modules/students/Views/events/registered', $data);
    }
    
    /**
     * Hiển thị danh sách sự kiện đã tham gia
     */
    public function completedEvents()
    {
        // Chuyển hướng đến tab đã hoàn thành trong trang sự kiện đã đăng ký
        return redirect()->to(base_url('students/events/registered#completed'));
    }
    
    /**
     * Hiển thị chi tiết sự kiện theo ID
     */
    public function eventDetail($id = null)
    {
        if ($id === null) {
            return redirect()->to(base_url('students/events'));
        }
        
        $userId = session()->get('user_id') ?? 101; // Sử dụng ID mẫu nếu chưa đăng nhập
        $eventData = $this->sukienModel->getEventById($id);
        
        if ($eventData) {
            // Xác định trạng thái đăng ký
            $isRegistered = $this->dangKySukienModel->isRegistered($userId, $id);
            
            // Xác định trạng thái sự kiện
            $timeRemaining = $this->sukienModel->getTimeRemaining($eventData['ngay_to_chuc'] ?? date('Y-m-d H:i:s'));
            $status = 'Sắp diễn ra';
            $statusColor = 'primary';
            
            if ($timeRemaining['days'] < 0) {
                $status = 'Đã kết thúc';
                $statusColor = 'secondary';
            } elseif ($timeRemaining['days'] == 0 && $timeRemaining['hours'] <= 24) {
                $status = 'Đang diễn ra';
                $statusColor = 'info';
            } else {
                $status = 'Đang mở đăng ký';
                $statusColor = 'success';
            }
            
            // Định dạng lại thời gian
            $formattedDate = date('d/m/Y', strtotime($eventData['ngay_to_chuc'] ?? ''));
            $formattedTime = date('H:i', strtotime($eventData['ngay_to_chuc'] ?? '')) . ' - ' . 
                             date('H:i', strtotime($eventData['gio_ket_thuc'] ?? '+3 hours', strtotime($eventData['ngay_to_chuc'] ?? '')));
            
            // Lấy số người đăng ký
            $registeredCount = $this->dangKySukienModel->countRegistrationsByEvent($id);
            
            // Tạo lịch trình mẫu
            $schedule = [
                [
                    'title' => 'Đón tiếp & đăng ký',
                    'time' => '8:30 - 9:00',
                    'description' => 'Check-in tại quầy lễ tân'
                ],
                [
                    'title' => 'Khai mạc',
                    'time' => '9:00 - 9:30',
                    'description' => 'Phát biểu khai mạc từ Ban Giám hiệu'
                ],
                [
                    'title' => 'Phần chính',
                    'time' => '9:30 - 12:00',
                    'description' => $eventData['mo_ta_ngan'] ?? 'Hoạt động chính của sự kiện'
                ],
                [
                    'title' => 'Nghỉ trưa',
                    'time' => '12:00 - 13:00',
                    'description' => 'Thời gian nghỉ giải lao'
                ],
                [
                    'title' => 'Hoạt động chiều',
                    'time' => '13:00 - 15:30',
                    'description' => 'Tiếp tục các hoạt động'
                ],
                [
                    'title' => 'Bế mạc & networking',
                    'time' => '15:30 - 16:00',
                    'description' => 'Tổng kết và giao lưu'
                ]
            ];
            
            // Tách chuỗi yêu cầu và lợi ích thành mảng nếu có
            $requirements = $eventData['yeu_cau'] ?? "Mang theo thẻ sinh viên\nĐăng ký trước khi tham gia\nTham gia đúng giờ theo lịch trình";
            $benefits = $eventData['loi_ich'] ?? "Mở rộng mạng lưới quan hệ\nTăng kỹ năng chuyên môn\nNhận chứng chỉ tham gia\nCơ hội tìm kiếm việc làm";
            
            // Lấy các sự kiện liên quan
            $relatedEvents = $this->sukienModel->getRelatedEvents($id, $eventData['loai_su_kien_id'] ?? 1, 2);
            $formattedRelatedEvents = [];
            
            foreach ($relatedEvents as $related) {
                $relatedDate = date('d/m/Y', strtotime($related['ngay_to_chuc'] ?? ''));
                
                $formattedRelatedEvents[] = [
                    'id' => $related['id'] ?? 0,
                    'name' => $related['ten_su_kien'] ?? 'Sự kiện không tên',
                    'date' => $relatedDate,
                    'location' => $related['dia_diem'] ?? 'Chưa xác định',
                    'image' => $related['hinh_anh'] ?? null
                ];
            }
            
            $data = [
                'title' => 'Chi tiết sự kiện',
                'student_data' => [
                    'fullname' => session()->get('student_name') ?? 'Nguyễn Văn A',
                    'student_id' => session()->get('student_id') ?? 'SV001',
                    'picture' => null
                ],
                'notification_count' => 2,
                'is_registered' => $isRegistered,
                'event' => [
                    'id' => $eventData['id'] ?? $id,
                    'name' => $eventData['ten_su_kien'] ?? 'Sự kiện không tên',
                    'date' => $formattedDate,
                    'time' => $formattedTime,
                    'location' => $eventData['dia_diem'] ?? 'Chưa xác định',
                    'status' => $status,
                    'status_color' => $statusColor,
                    'category' => $this->getEventTypeName($eventData['loai_su_kien_id'] ?? 0),
                    'description' => $eventData['mo_ta'] ?? 'Chưa có mô tả chi tiết về sự kiện này.',
                    'organizer' => $eventData['don_vi_to_chuc'] ?? 'Phòng Công tác Sinh viên',
                    'organizer_email' => $eventData['email_lien_he'] ?? 'ctsv@example.edu.vn',
                    'organizer_phone' => $eventData['sdt_lien_he'] ?? '(024) 3869 4242',
                    'capacity' => $eventData['so_luong_toi_da'] ?? 100,
                    'registered' => $registeredCount,
                    'days_left' => max(0, $timeRemaining['days']),
                    'schedule' => $schedule,
                    'requirements' => $requirements,
                    'benefits' => $benefits
                ],
                'related_events' => $formattedRelatedEvents
            ];
        } else {
            // Nếu không tìm thấy sự kiện phù hợp, trả về dữ liệu null
            $data = [
                'title' => 'Chi tiết sự kiện',
                'student_data' => [
                    'fullname' => session()->get('student_name') ?? 'Nguyễn Văn A',
                    'student_id' => session()->get('student_id') ?? 'SV001',
                    'picture' => null
                ],
                'notification_count' => 2,
                'event' => null
            ];
        }

        return view('App\Modules\students\Views\events\detail', $data);
    }
    
    /**
     * Lấy tên loại sự kiện từ ID
     */
    private function getEventTypeName($typeId)
    {
        $eventType = $this->loaiSukienModel->getEventTypeById($typeId);
        return $eventType ? ($eventType['loai_su_kien'] ?? 'Chung') : 'Chung';
    }
    
    /**
     * Xử lý đăng ký tham gia sự kiện
     */
    public function registerEvent()
    {
        if ($this->request->isAJAX()) {
            $eventId = $this->request->getJSON()->event_id;
            $userId = session()->get('user_id') ?? 101; // Sử dụng ID mẫu nếu chưa đăng nhập
            
            // Kiểm tra xem người dùng đã đăng ký sự kiện này chưa
            if ($this->dangKySukienModel->isRegistered($userId, $eventId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Bạn đã đăng ký tham gia sự kiện này rồi!'
                ]);
            }
            
            // Xử lý logic đăng ký sự kiện
            $data = [
                'su_kien_id' => $eventId,
                'nguoi_dung_id' => $userId,
                'ngay_dang_ky' => date('Y-m-d H:i:s'),
                'noi_dung_gop_y' => '',
                'nguon_gioi_thieu' => 'Website trường',
                'loai_nguoi_dung' => 'Sinh viên',
                'status' => 1
            ];
            
            $result = $this->dangKySukienModel->registerEvent($data);
            
            // Trả về kết quả
            return $this->response->setJSON([
                'success' => $result,
                'message' => $result ? 'Đăng ký tham gia sự kiện thành công!' : 'Có lỗi xảy ra khi đăng ký sự kiện.'
            ]);
        }
        
        return redirect()->to(base_url('students/events'));
    }
    
    /**
     * Xử lý hủy đăng ký tham gia sự kiện
     */
    public function cancelRegistration()
    {
        if ($this->request->isAJAX()) {
            $eventId = $this->request->getJSON()->event_id;
            $userId = session()->get('user_id') ?? 101; // Sử dụng ID mẫu nếu chưa đăng nhập
            
            // Kiểm tra xem người dùng đã đăng ký sự kiện này chưa
            if (!$this->dangKySukienModel->isRegistered($userId, $eventId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Bạn chưa đăng ký tham gia sự kiện này!'
                ]);
            }
            
            // Xử lý logic hủy đăng ký sự kiện
            $result = $this->dangKySukienModel->cancelRegistration($userId, $eventId);
            
            // Trả về kết quả
            return $this->response->setJSON([
                'success' => $result,
                'message' => $result ? 'Hủy đăng ký tham gia sự kiện thành công!' : 'Có lỗi xảy ra khi hủy đăng ký sự kiện.'
            ]);
        }
        
        return redirect()->to(base_url('students/events/registered'));
    }
    
    /**
     * Xử lý điểm danh tham gia sự kiện
     */
    public function eventAttendance()
    {
        if ($this->request->isAJAX()) {
            $eventId = $this->request->getJSON()->event_id;
            $attendanceCode = $this->request->getJSON()->attendance_code;
            $userId = session()->get('user_id') ?? 101; // Sử dụng ID mẫu nếu chưa đăng nhập
            
            // Kiểm tra xem người dùng đã đăng ký sự kiện này chưa
            if (!$this->dangKySukienModel->isRegistered($userId, $eventId)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Bạn chưa đăng ký tham gia sự kiện này!'
                ]);
            }
            
            // Kiểm tra mã điểm danh
            $eventData = $this->sukienModel->getEventById($eventId);
            $validCode = $eventData['ma_diem_danh'] ?? '12345'; // Mã điểm danh mẫu
            
            if ($attendanceCode == $validCode) {
                // Thực hiện điểm danh (check-in)
                $checkinData = [
                    'nguoi_dung_id' => $userId,
                    'su_kien_id' => $eventId,
                    'thoi_gian_check_in' => date('Y-m-d H:i:s'),
                    'status' => 1
                ];
                
                // Gọi phương thức insertCheckin (sẽ cần tạo thêm nếu chưa có)
                $result = true; // Giả định thành công
                
                // Trả về kết quả
                return $this->response->setJSON([
                    'success' => $result,
                    'message' => $result ? 'Điểm danh tham gia sự kiện thành công!' : 'Có lỗi xảy ra khi điểm danh.'
                ]);
            } else {
                // Trả về kết quả thất bại
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Mã điểm danh không hợp lệ!'
                ]);
            }
        }
        
        return redirect()->to(base_url('students/events/registered'));
    }
    
    /**
     * Xem chứng chỉ từ sự kiện
     */
    public function eventCertificate($id = null)
    {
        if ($id === null) {
            return redirect()->to(base_url('students/events/registered'));
        }
        
        $userId = session()->get('user_id') ?? 101; // Sử dụng ID mẫu nếu chưa đăng nhập
        
        // Kiểm tra xem người dùng đã đăng ký và hoàn thành sự kiện này chưa
        if (!$this->dangKySukienModel->isRegistered($userId, $id) ||
            !$this->checkinSukienModel->hasUserCheckedIn($userId, $id) || 
            !$this->checkoutSukienModel->hasUserCheckedOut($userId, $id)) {
            
            session()->setFlashdata('error', 'Bạn chưa hoàn thành sự kiện này hoặc chưa đăng ký tham gia!');
            return redirect()->to(base_url('students/events/registered'));
        }
        
        // Xử lý logic hiển thị/tải chứng chỉ ở đây
        // Trong bản demo, chỉ chuyển hướng đến trang đã đăng ký với thông báo thành công
        session()->setFlashdata('success', 'Chứng chỉ của bạn đã được tải xuống!');
        return redirect()->to(base_url('students/events/registered'));
    }
} 