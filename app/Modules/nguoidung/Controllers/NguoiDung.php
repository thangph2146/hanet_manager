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
        
       
    }
    
    public function index()
    {   
        
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
            'profile' => getInfoNguoiDung()
        ];
        return view('App\Modules\nguoidung\Views\profile', $data);
    }
    
    /**
     * Cập nhật thông tin cá nhân
     */
    public function updateProfile()
    {
        // Lấy thông tin người dùng hiện tại
        $profile = getInfoNguoiDung();
        $nguoi_dung_id = $profile->nguoi_dung_id;
        
        // Ghi log yêu cầu cập nhật
        log_message('debug', 'Yêu cầu cập nhật thông tin người dùng ID: ' . $nguoi_dung_id);
        
        // Kiểm tra xem yêu cầu có phải là AJAX không
        if (!$this->request->isAJAX()) {
            log_message('error', 'Yêu cầu không phải AJAX');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Yêu cầu không hợp lệ. Vui lòng thử lại.'
            ]);
        }
        
        // Xác thực đầu vào
        $rules = [
            'FullName' => [
                'label' => 'Họ và tên',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => '{field} không được để trống',
                    'min_length' => '{field} phải có ít nhất {param} ký tự',
                    'max_length' => '{field} không được quá {param} ký tự'
                ]
            ],
            'MobilePhone' => [
                'label' => 'Số điện thoại',
                'rules' => 'required|min_length[10]|max_length[20]',
                'errors' => [
                    'required' => '{field} không được để trống',
                    'min_length' => '{field} phải có ít nhất {param} ký tự',
                    'max_length' => '{field} không được quá {param} ký tự'
                ]
            ],
            'avatar' => [
                'label' => 'Ảnh đại diện',
                'rules' => 'permit_empty|is_image[avatar,true]|max_size[avatar,2048]',
                'errors' => [
                    'is_image' => '{field} phải là định dạng hình ảnh',
                    'max_size' => '{field} không được quá 2MB'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('error', 'Lỗi xác thực: ' . json_encode($errors));
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $errors
            ]);
        }
        
        try {
            // Lấy dữ liệu từ form
            $fullName = $this->request->getPost('FullName');
            $mobilePhone = $this->request->getPost('MobilePhone');
            
            log_message('debug', 'Dữ liệu gửi đến: FullName=' . $fullName . ', MobilePhone=' . $mobilePhone);
            
            // Tạo dữ liệu cập nhật
            $updateData = [
                'FullName' => $fullName,
                'MobilePhone' => $mobilePhone,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Xử lý tải lên ảnh đại diện
            $avatar = $this->request->getFile('avatar');
            if ($avatar && $avatar->isValid() && !$avatar->hasMoved() && $avatar->getSize() > 0) {
                log_message('debug', 'Xử lý tải lên ảnh đại diện: ' . $avatar->getName() . ', size: ' . $avatar->getSize() . ', type: ' . $avatar->getClientMimeType());
                
                // Tạo tên tệp tin đích
                $newName = $nguoi_dung_id . '_' . time() . '.' . $avatar->getExtension();
                
                // Kiểm tra và tạo thư mục nếu chưa tồn tại
                $uploadPath = ROOTPATH . 'public/uploads/avatars';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Di chuyển tệp tin vào thư mục uploads/avatars
                $avatar->move($uploadPath, $newName);
                
                // Thêm tên tệp tin avatar vào dữ liệu cập nhật
                $updateData['avatar'] = 'public/uploads/avatars/' . $newName;
                
                // Xóa avatar cũ nếu có
                if (!empty($profile->avatar) && file_exists(ROOTPATH . 'public/uploads/avatars/' . $profile->avatar)) {
                    unlink(ROOTPATH . 'public/uploads/avatars/' . $profile->avatar);
                }
                
                log_message('debug', 'Đã tải lên ảnh đại diện mới: ' . $newName);
            } else {
                log_message('debug', 'Không có ảnh đại diện mới hoặc ảnh không hợp lệ');
            }
            
            // Cập nhật thông tin người dùng
            $updated = $this->nguoidungModel->update($nguoi_dung_id, $updateData);
            
            if ($updated) {
                log_message('debug', 'Cập nhật thông tin người dùng thành công: ID=' . $nguoi_dung_id);
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Cập nhật thông tin cá nhân thành công',
                    'data' => [
                        'FullName' => $fullName,
                        'MobilePhone' => $mobilePhone,
                        'avatar' => $updateData['avatar'] ?? $profile->avatar
                    ]
                ]);
            } else {
                $errors = $this->nguoidungModel->errors();
                log_message('error', 'Cập nhật thông tin người dùng thất bại: ID=' . $nguoi_dung_id . ', Lỗi: ' . json_encode($errors));
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cập nhật thông tin cá nhân thất bại',
                    'errors' => $errors
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Lỗi ngoại lệ: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi cập nhật thông tin: ' . $e->getMessage(),
                'error_code' => $e->getCode()
            ]);
        }
    }
    
    /**
     * Hiển thị trang dashboard
     */
    public function dashboard()
    {
        // Load text helper
        helper('text');
        
        // Lấy thông tin người dùng hiện tại
        $profile = getInfoNguoiDung();
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
        $profile = getInfoNguoiDung();
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
        $profile = getInfoNguoiDung();
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
        
        // Xử lý dữ liệu và đảm bảo các trường cần thiết
        foreach ($registeredEvents as $key => $event) {
            // Chuẩn hóa trường ngày tổ chức
            if (!isset($event->ngay_to_chuc) && isset($event->thoi_gian_bat_dau)) {
                $registeredEvents[$key]->ngay_to_chuc = $event->thoi_gian_bat_dau;
            }
            
            // Chuẩn hóa trường trạng thái đăng ký
            if (!isset($event->status) && isset($event->trang_thai_dang_ky)) {
                $registeredEvents[$key]->status = $event->trang_thai_dang_ky;
            }
        }
        
        // Áp dụng bộ lọc tìm kiếm theo từ khóa
        if (!empty($search)) {
            $search = strtolower($search);
            $registeredEvents = array_filter($registeredEvents, function($event) use ($search) {
                return (
                    stripos(strtolower($event->ten_sukien ?? ($event->tieu_de ?? '')), $search) !== false ||
                    stripos(strtolower($event->dia_diem ?? ($event->venue ?? '')), $search) !== false ||
                    stripos(strtolower($event->to_chuc ?? ($event->ban_to_chuc ?? '')), $search) !== false
                );
            });
        }
        
        // Áp dụng bộ lọc thời gian nếu có trong eventsHistoryRegister
        if (!empty($startDate) || !empty($endDate)) {
            $registeredEvents = array_filter($registeredEvents, function($event) use ($startDate, $endDate) {
                // Kiểm tra các trường ngày có thể có
                $eventDateStr = $event->ngay_to_chuc ?? ($event->thoi_gian_bat_dau ?? null);
                if (!$eventDateStr) {
                    return true; // Giữ lại sự kiện nếu không có ngày
                }
                
                $eventDate = new DateTime($eventDateStr);
                
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
            if (isset($event->status) && $event->status == 0) {
                $cancelledEvents++;
            } else if (isset($event->da_check_in) && $event->da_check_in == 1) {
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
        $profile = getInfoNguoiDung();
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