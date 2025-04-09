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
        return $this->dashboard();
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
            'LastName' => [
                'label' => 'Họ',
                'rules' => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required' => 'Họ không được để trống',
                    'min_length' => 'Họ phải có ít nhất {param} ký tự',
                    'max_length' => 'Họ không được quá {param} ký tự'
                ]
            ],
            'FirstName' => [
                'label' => 'Tên',
                'rules' => 'required|min_length[2]|max_length[100]',
                'errors' => [
                    'required' => 'Tên không được để trống',
                    'min_length' => 'Tên phải có ít nhất {param} ký tự',
                    'max_length' => 'Tên không được quá {param} ký tự'
                ]
            ],
            'MiddleName' => [
                'label' => 'Tên đệm',
                'rules' => 'permit_empty|max_length[100]',
                'errors' => [
                    'max_length' => 'Tên đệm không được quá {param} ký tự'
                ]
            ],
            'FullName' => [
                'label' => 'Họ và tên',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Họ và tên không được để trống',
                    'min_length' => 'Họ và tên phải có ít nhất {param} ký tự',
                    'max_length' => 'Họ và tên không được quá {param} ký tự'
                ]
            ],
            'MobilePhone' => [
                'label' => 'Số điện thoại',
                'rules' => 'permit_empty|min_length[10]|max_length[20]',
                'errors' => [
                    'min_length' => 'Số điện thoại phải có ít nhất {param} ký tự',
                    'max_length' => 'Số điện thoại không được quá {param} ký tự'
                ]
            ],
            'HomePhone' => [
                'label' => 'Số điện thoại nhà',
                'rules' => 'permit_empty|min_length[10]|max_length[20]',
                'errors' => [
                    'min_length' => 'Số điện thoại nhà phải có ít nhất {param} ký tự',
                    'max_length' => 'Số điện thoại nhà không được quá {param} ký tự'
                ]
            ],
            'HomePhone1' => [
                'label' => 'Số điện thoại nhà (2)',
                'rules' => 'permit_empty|min_length[10]|max_length[20]',
                'errors' => [
                    'min_length' => 'Số điện thoại nhà (2) phải có ít nhất {param} ký tự',
                    'max_length' => 'Số điện thoại nhà (2) không được quá {param} ký tự'
                ]
            ],
            'avatar' => [
                'label' => 'Ảnh đại diện',
                'rules' => 'permit_empty|is_image[avatar,true]|max_size[avatar,2048]',
                'errors' => [
                    'is_image' => 'Ảnh đại diện phải là định dạng hình ảnh',
                    'max_size' => 'Ảnh đại diện không được quá 2MB'
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
            $lastName = $this->request->getPost('LastName');
            $middleName = $this->request->getPost('MiddleName');
            $firstName = $this->request->getPost('FirstName');
            $fullName = $this->request->getPost('FullName');
            $mobilePhone = $this->request->getPost('MobilePhone');
            $homePhone = $this->request->getPost('HomePhone');
            $homePhone1 = $this->request->getPost('HomePhone1');
            
            log_message('debug', 'Dữ liệu gửi đến: LastName=' . $lastName . ', MiddleName=' . $middleName . ', FirstName=' . $firstName . ', FullName=' . $fullName . ', MobilePhone=' . $mobilePhone);
            
            // Tạo dữ liệu cập nhật
            $updateData = [
                'LastName' => $lastName,
                'MiddleName' => $middleName,
                'FirstName' => $firstName,
                'FullName' => $fullName,
                'MobilePhone' => $mobilePhone,
                'HomePhone' => $homePhone,
                'HomePhone1' => $homePhone1,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Xử lý tải lên ảnh đại diện
            $avatar = $this->request->getFile('avatar');
            if ($avatar && $avatar->isValid() && !$avatar->hasMoved() && $avatar->getSize() > 0) {
                log_message('debug', 'Xử lý tải lên ảnh đại diện: ' . $avatar->getName() . ', size: ' . $avatar->getSize() . ', type: ' . $avatar->getClientMimeType());
                
                try {
                    // Tạo tên tệp tin đích
                    $newName = $nguoi_dung_id . '_' . strtolower($this->convertToNonAccent(str_replace(' ', '_', $fullName))) . '.' . $avatar->getExtension();
                    
                    // Kiểm tra và tạo thư mục nếu chưa tồn tại
                    $uploadPath = ROOTPATH . 'public/uploads/avatars';
                    if (!is_dir($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    
                    // Di chuyển tệp tin vào thư mục uploads/avatars
                    $avatar->move($uploadPath, $newName);
                    
                    // Thêm tên tệp tin avatar vào dữ liệu cập nhật
                    $updateData['avatar'] = 'uploads/avatars/' . $newName;
                    
                    // Xóa avatar cũ nếu có
                    if (!empty($profile->avatar)) {
                        $oldAvatarPath = ROOTPATH . $profile->avatar;
                        if (file_exists($oldAvatarPath)) {
                            unlink($oldAvatarPath);
                            log_message('debug', 'Đã xóa avatar cũ: ' . $oldAvatarPath);
                        } else {
                            log_message('debug', 'Không tìm thấy file avatar cũ: ' . $oldAvatarPath);
                        }
                    }
                    
                    log_message('debug', 'Đã tải lên ảnh đại diện mới: ' . $newName);
                } catch (\Exception $e) {
                    log_message('error', 'Lỗi khi xử lý avatar: ' . $e->getMessage());
                    // Không dừng quá trình cập nhật nếu có lỗi với avatar
                }
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
                        'LastName' => $lastName,
                        'MiddleName' => $middleName,
                        'FirstName' => $firstName,
                        'FullName' => $fullName,
                        'MobilePhone' => $mobilePhone,
                        'HomePhone' => $homePhone,
                        'HomePhone1' => $homePhone1,
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
        
        // Xác thực và lấy dữ liệu người dùng từ model nếu có
        if (isset($profile->nguoi_dung_id)) {
            $userInfo = $this->nguoidungModel->find($profile->nguoi_dung_id);
            if ($userInfo) {
                $profile = $userInfo;
            }
        }
        
        $email = $profile->Email;
        
        try {
            // Lấy các sự kiện đã đăng ký (tất cả trạng thái)
            $registeredEvents = $this->dangkysukienModel->getRegistrationsByEmail($email, [
                'join_event_info' => true,
                'order' => [
                    'dangky_sukien.created_at' => 'DESC'
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
                    'dangky_sukien.created_at' => 'DESC'
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
            
            // Chuẩn hóa tên trường trong dữ liệu sự kiện
            $this->normalizeEventData($registeredEvents);
            $this->normalizeEventData($attendedEvents);
            $this->normalizeEventData($upcomingEvents);
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi lấy dữ liệu sự kiện: ' . $e->getMessage());
            
            // Khởi tạo mảng trống nếu có lỗi
            $registeredEvents = [];
            $attendedEvents = [];
            $registeredCount = 0;
            $attendedCount = 0;
            $upcomingEvents = [];
        }
        
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
    
    /**
     * Chuẩn hóa tên trường trong dữ liệu sự kiện
     *
     * @param array $events Danh sách sự kiện cần chuẩn hóa
     * @return void
     */
    protected function normalizeEventData(&$events)
    {
        if (empty($events)) {
            return;
        }
        
        // Đảm bảo $events là một mảng
        if (!is_array($events)) {
            $events = [];
            return;
        }
        
        foreach ($events as $key => $event) {
            // Kiểm tra xem $event có phải là object không
            if (!is_object($event)) {
                continue;
            }
            
            // Chuẩn hóa tên sự kiện
            if (!isset($event->ten_su_kien) && isset($event->ten_sukien)) {
                $events[$key]->ten_su_kien = $event->ten_sukien;
            }
            
            // Đảm bảo các trường dữ liệu cơ bản luôn tồn tại
            if (!isset($event->ten_su_kien) && !isset($event->ten_sukien)) {
                $events[$key]->ten_su_kien = 'Sự kiện không xác định';
            }
            
            // Đảm bảo có trường slug cho các liên kết
            if (!isset($event->slug) && isset($event->su_kien_id)) {
                $events[$key]->slug = $event->su_kien_id;
            }
            
            // Chuẩn hóa trường ngày tổ chức
            if (!isset($event->ngay_to_chuc) && isset($event->thoi_gian_bat_dau)) {
                $events[$key]->ngay_to_chuc = $event->thoi_gian_bat_dau;
            }
            
            // Chuẩn hóa trường trạng thái đăng ký
            if (!isset($event->trang_thai) && isset($event->status)) {
                $events[$key]->trang_thai = $event->status;
            }
            
            // Chuẩn hóa các trường thời gian
            if (!isset($event->gio_bat_dau) && isset($event->thoi_gian_bat_dau)) {
                $events[$key]->gio_bat_dau = $event->thoi_gian_bat_dau;
            }
            
            if (!isset($event->gio_ket_thuc) && isset($event->thoi_gian_ket_thuc)) {
                $events[$key]->gio_ket_thuc = $event->thoi_gian_ket_thuc;
            }
            
            // Chuẩn hóa trường địa điểm
            if (!isset($event->dia_diem) && isset($event->venue)) {
                $events[$key]->dia_diem = $event->venue;
            }
            
            // Chuẩn hóa trường đơn vị tổ chức
            if (!isset($event->don_vi_to_chuc) && isset($event->to_chuc)) {
                $events[$key]->don_vi_to_chuc = $event->to_chuc;
            } elseif (!isset($event->don_vi_to_chuc) && isset($event->ban_to_chuc)) {
                $events[$key]->don_vi_to_chuc = $event->ban_to_chuc;
            }
            
            // Đảm bảo hình ảnh luôn có giá trị mặc định
            if (!isset($event->hinh_anh) || empty($event->hinh_anh)) {
                $events[$key]->hinh_anh = 'default.jpg';
            }
        }
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
        
        try {
            // Lấy các sự kiện đã tham gia
            $attendedEvents = $this->dangkysukienModel->getRegistrationsByEmail($email, [
                'join_event_info' => true,
                'where' => [
                    'da_check_in' => 1
                ],
                'order' => [
                    'dangky_sukien.created_at' => 'DESC'  // Sửa lại tên bảng đúng
                ]
            ]);
            
            // Áp dụng bộ lọc tìm kiếm theo từ khóa
            if (!empty($search)) {
                $search = strtolower($search);
                $attendedEvents = array_filter($attendedEvents, function($event) use ($search) {
                    return (
                        stripos(strtolower($event->ten_su_kien ?? ($event->ten_sukien ?? '')), $search) !== false ||
                        stripos(strtolower($event->dia_diem ?? ($event->venue ?? '')), $search) !== false ||
                        stripos(strtolower($event->don_vi_to_chuc ?? ($event->to_chuc ?? '')), $search) !== false
                    );
                });
            }
            
            // Áp dụng bộ lọc thời gian nếu có
            if (!empty($startDate) || !empty($endDate)) {
                $attendedEvents = array_filter($attendedEvents, function($event) use ($startDate, $endDate) {
                    // Tìm thời gian sự kiện từ các trường khác nhau có thể có
                    $eventDateStr = $event->ngay_to_chuc ?? ($event->thoi_gian_bat_dau ?? null);
                    if (!$eventDateStr) {
                        return true; // Giữ lại sự kiện nếu không có ngày
                    }
                    
                    try {
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
                    } catch (\Exception $e) {
                        return true; // Giữ lại sự kiện nếu không phân tích được thời gian
                    }
                });
            }
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi lấy dữ liệu lịch sử sự kiện: ' . $e->getMessage());
            $attendedEvents = [];
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
        
        try {
            // Lấy các sự kiện đã đăng ký (tất cả trạng thái)
            $registeredEvents = $this->dangkysukienModel->getRegistrationsByEmail($email, [
                'join_event_info' => true,
                'order' => [
                    'dangky_sukien.created_at' => 'DESC'  // Sửa lại tên bảng đúng
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
                        stripos(strtolower($event->ten_su_kien ?? ($event->ten_sukien ?? '')), $search) !== false ||
                        stripos(strtolower($event->dia_diem ?? ($event->venue ?? '')), $search) !== false ||
                        stripos(strtolower($event->don_vi_to_chuc ?? ($event->ban_to_chuc ?? '')), $search) !== false
                    );
                });
            }
            
            // Áp dụng bộ lọc thời gian nếu có
            if (!empty($startDate) || !empty($endDate)) {
                $registeredEvents = array_filter($registeredEvents, function($event) use ($startDate, $endDate) {
                    // Kiểm tra các trường ngày có thể có
                    $eventDateStr = $event->ngay_to_chuc ?? ($event->thoi_gian_bat_dau ?? null);
                    if (!$eventDateStr) {
                        return true; // Giữ lại sự kiện nếu không có ngày
                    }
                    
                    try {
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
                    } catch (\Exception $e) {
                        return true; // Giữ lại sự kiện nếu không phân tích được thời gian
                    }
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
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi lấy dữ liệu đăng ký sự kiện: ' . $e->getMessage());
            $registeredEvents = [];
            $attendedEvents = 0;
            $pendingEvents = 0;
            $cancelledEvents = 0;
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
        $data = [];
        $data['title'] = 'Danh sách sự kiện';
        $data['active_menu'] = 'events-list';
        
        // Phần 1: Lấy thông tin người dùng và cấu hình bộ lọc
        // Lấy thông tin người dùng hiện tại từ session
        $userEmail = session()->get('email');
        $data['user'] = $this->nguoidungModel->getUserByEmail($userEmail);
        
        // Các tham số tìm kiếm và phân trang
        $search = $this->request->getGet('search');
        $page = $this->request->getGet('page') ?: 1;
        $perPage = 10;
        $filters = [
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
        ];
        
        $data['current_filter'] = $filters;
        if (!empty($filters['start_date'])) {
            $data['formatted_filter']['start_date_formatted'] = date('d/m/Y H:i:s', strtotime($filters['start_date']));
        }
        
        if (!empty($filters['end_date'])) {
            $data['formatted_filter']['end_date_formatted'] = date('d/m/Y H:i:s', strtotime($filters['end_date']));
        }
        
        try {
            // Phần 2: Lấy danh sách sự kiện
            $sukienModel = model('App\Modules\quanlysukien\Models\SukienModel');
            $events = $sukienModel->search([
                'keyword' => $search,
                'start_date' => $filters['start_date'] ?? null,
                'end_date' => $filters['end_date'] ?? null,
                'status' => 1, // Chỉ lấy sự kiện đang hoạt động
            ], [
                'limit' => $perPage,
                'offset' => ($page - 1) * $perPage,
                'sort' => 'thoi_gian_bat_dau',
                'order' => 'DESC'
            ]);
            
            // Phần 3: Lấy thông tin đăng ký sự kiện của người dùng
            $userEvents = [];
            $attendedEvents = [];
            $upcomingCount = 0;
            $registeredCount = 0;
            $attendedCount = 0;
            
            if ($userEmail) {
                // Chỉ khi có email mới lấy thông tin đăng ký
                $dangKySuKienModel = model('App\Modules\quanlydangkysukien\Models\DangKySuKienModel');
                $userRegistrations = $dangKySuKienModel->getRegistrationsByEmail($userEmail);
                
                // Lấy danh sách ID sự kiện đã đăng ký
                foreach ($userRegistrations as $registration) {
                    $userEvents[] = $registration->getSuKienId();
                    if ($registration->isDaCheckIn()) {
                        $attendedEvents[] = $registration->getSuKienId();
                    }
                }
                
                $registeredCount = count($userEvents);
                $attendedCount = count($attendedEvents);
            }
            
            // Phần 4: Tính toán số lượng sự kiện sắp diễn ra
            $now = new \DateTime();
            foreach ($events as $event) {
                $startDate = new \DateTime($event->thoi_gian_bat_dau ?? date('Y-m-d H:i:s'));
                if ($startDate > $now) {
                    $upcomingCount++;
                }
            }
            
            // Phần 5: Thiết lập dữ liệu kết quả
            $data['events'] = $events;
            $data['userEvents'] = $userEvents;
            $data['attendedEvents'] = $attendedEvents;
            $data['upcomingCount'] = $upcomingCount;
            $data['registeredCount'] = $registeredCount;
            $data['attendedCount'] = $attendedCount;
            $data['pager'] = $sukienModel->getPager();
            
        } catch (\Exception $e) {
            log_message('error', 'Lỗi lấy danh sách sự kiện: ' . $e->getMessage());
            $data['error'] = 'Có lỗi xảy ra khi lấy danh sách sự kiện. Vui lòng thử lại sau.';
            $data['events'] = [];
            $data['userEvents'] = [];
            $data['attendedEvents'] = [];
            $data['upcomingCount'] = 0;
            $data['registeredCount'] = 0;
            $data['attendedCount'] = 0;
        }
        
        return view('App\Modules\nguoidung\Views\eventslist', $data);
    }

   
    public function huyDangKySuKien($su_kien_id)
    {
        // Load text helper
        helper('text');
        
        // Lấy thông tin người dùng hiện tại
        $profile = getInfoNguoiDung();
        $email = $profile->Email;

        // Gọi model để hủy đăng ký sự kiện
        $result = $this->dangkysukienModel->huyDangKySuKien($su_kien_id, $email);   
        
        if ($result) {
            return redirect()->to(site_url('nguoi-dung/su-kien-da-dang-ky'))->with('success', 'Đã hủy đăng ký sự kiện thành công.');
        } else {
            return redirect()->to(site_url('nguoi-dung/su-kien-da-dang-ky'))->with('error', 'Không thể hủy đăng ký sự kiện.');
        }
    }

    /**
     * Chuyển đổi chuỗi có dấu thành không dấu
     * 
     * @param string $str Chuỗi cần chuyển đổi
     * @return string
     */
    protected function convertToNonAccent($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        $str = str_replace(" ", "-", str_replace("&*#39;", "", $str));
        return $str;
    }
}