<?php

namespace App\Modules\students\Controllers;

use App\Controllers\BaseController;
use App\Modules\sukien\Models\SukienModel;
use App\Modules\sukien\Models\LoaiSukienModel;
use App\Modules\sukien\Models\DangKySukienModel;
use App\Modules\sukien\Models\CheckinSukienModel;
use App\Modules\sukien\Models\CheckoutSukienModel;

class Events extends BaseController
{
    protected $sukienModel;
    protected $loaiSukienModel;
    protected $dangKySukienModel;
    protected $checkinSukienModel;
    protected $checkoutSukienModel;
    protected $user_id = 101; // Mock user ID for demo
    
    public function __construct()
    {
        $this->sukienModel = new SukienModel();
        $this->loaiSukienModel = new LoaiSukienModel();
        $this->dangKySukienModel = new DangKySukienModel();
        $this->checkinSukienModel = new CheckinSukienModel();
        $this->checkoutSukienModel = new CheckoutSukienModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Danh sách sự kiện',
            'events' => $this->getEvents(),
            'event_types' => $this->loaiSukienModel->findAll(),
            'registered_events' => $this->getRegisteredEvents()
        ];
        
        return view('Modules/students/Views/events/index', $data);
    }
    
    private function getEvents()
    {
        // Lấy danh sách sự kiện với phân trang
        $events = $this->sukienModel
            ->select('sukien.*, loai_su_kien.loai_su_kien')
            ->join('loai_su_kien', 'loai_su_kien.id = sukien.loai_su_kien_id')
            ->where('status !=', 'draft')
            ->orderBy('ngay_to_chuc', 'DESC')
            ->paginate(9);
            
        // Kiểm tra xem người dùng đã đăng ký sự kiện chưa
        $userId = session()->get('user_id');
        foreach ($events as &$event) {
            $event['is_registered'] = $this->dangKySukienModel
                ->where('su_kien_id', $event['id'])
                ->where('user_id', $userId)
                ->countAllResults() > 0;
                
            // Xác định trạng thái sự kiện
            $event['status'] = $this->determineEventStatus($event);
        }
        
        return $events;
    }
    
    private function getRegisteredEvents()
    {
        $userId = session()->get('user_id');
        
        // Lấy danh sách sự kiện đã đăng ký
        return $this->dangKySukienModel
            ->select('dangky_sukien.id as registration_id, sukien.*, loai_su_kien.loai_su_kien')
            ->join('sukien', 'sukien.id = dangky_sukien.su_kien_id')
            ->join('loai_su_kien', 'loai_su_kien.id = sukien.loai_su_kien_id')
            ->where('dangky_sukien.user_id', $userId)
            ->orderBy('sukien.ngay_to_chuc', 'ASC')
            ->findAll();
    }
    
    private function determineEventStatus($event)
    {
        $now = time();
        $eventDate = strtotime($event['ngay_to_chuc']);
        $eventEndDate = strtotime($event['ngay_ket_thuc'] ?? $event['ngay_to_chuc']);
        
        if ($now < $eventDate) {
            return 'upcoming';
        } else if ($now >= $eventDate && $now <= $eventEndDate) {
            return 'ongoing';
        } else {
            return 'completed';
        }
    }
    
    // API endpoint để đăng ký sự kiện
    public function register()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $eventId = $this->request->getJSON()->event_id ?? null;
        $userId = session()->get('user_id');
        
        if (!$eventId || !$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Thông tin không hợp lệ'
            ]);
        }
        
        // Kiểm tra xem sự kiện có tồn tại không
        $event = $this->sukienModel->find($eventId);
        if (!$event) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sự kiện không tồn tại'
            ]);
        }
        
        // Kiểm tra xem người dùng đã đăng ký chưa
        $registered = $this->dangKySukienModel
            ->where('su_kien_id', $eventId)
            ->where('user_id', $userId)
            ->countAllResults() > 0;
            
        if ($registered) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Bạn đã đăng ký sự kiện này rồi'
            ]);
        }
        
        // Đăng ký sự kiện
        $data = [
            'su_kien_id' => $eventId,
            'user_id' => $userId,
            'ngay_dang_ky' => date('Y-m-d H:i:s'),
            'trang_thai' => 'registered'
        ];
        
        $result = $this->dangKySukienModel->insert($data);
        
        if ($result) {
            // Cập nhật số người tham gia
            $this->sukienModel->incrementParticipants($eventId);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Đăng ký sự kiện thành công'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Đăng ký sự kiện thất bại'
            ]);
        }
    }
    
    // API endpoint để hủy đăng ký
    public function cancel()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $data = $this->request->getJSON();
        $registrationId = $data->registration_id ?? null;
        $eventId = $data->event_id ?? null;
        $userId = session()->get('user_id');
        
        if (!$registrationId || !$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Thông tin không hợp lệ'
            ]);
        }
        
        // Kiểm tra xem đăng ký có tồn tại không
        $registration = $this->dangKySukienModel->find($registrationId);
        if (!$registration || $registration['user_id'] != $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Không tìm thấy thông tin đăng ký'
            ]);
        }
        
        // Kiểm tra xem đã check-in chưa
        $hasCheckin = $this->checkinSukienModel
            ->where('dang_ky_id', $registrationId)
            ->countAllResults() > 0;
            
        if ($hasCheckin) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Không thể hủy đăng ký vì bạn đã check-in tham gia sự kiện'
            ]);
        }
        
        // Xóa đăng ký
        $result = $this->dangKySukienModel->delete($registrationId);
        
        if ($result) {
            // Giảm số người tham gia
            $this->sukienModel->decrementParticipants($eventId);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Hủy đăng ký thành công'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Hủy đăng ký thất bại'
            ]);
        }
    }
    
    // API endpoint để lấy danh sách sự kiện đã đăng ký qua AJAX
    public function registeredEvents()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }
        
        $registeredEvents = $this->getRegisteredEvents();
        
        // Render partial view
        $html = view('Modules/students/Views/events/partials/registered_list', [
            'registered_events' => $registeredEvents
        ]);
        
        return $this->response->setJSON([
            'success' => true,
            'html' => $html
        ]);
    }
    
    // Tạo QR code
    public function qrcode($eventId = null)
    {
        if (!$eventId) {
            return $this->response->setStatusCode(404);
        }
        
        $userId = session()->get('user_id');
        
        // Kiểm tra xem người dùng đã đăng ký sự kiện chưa
        $registration = $this->dangKySukienModel
            ->where('su_kien_id', $eventId)
            ->where('user_id', $userId)
            ->first();
            
        if (!$registration) {
            return $this->response->setStatusCode(403);
        }
        
        // Tạo nội dung QR code
        $qrData = json_encode([
            'event_id' => $eventId,
            'user_id' => $userId,
            'registration_id' => $registration['id'],
            'timestamp' => time()
        ]);
        
        // Sử dụng thư viện QR code để tạo QR
        $this->response->setContentType('image/png');
        
        // Sử dụng thư viện QR code (ví dụ: endroid/qr-code)
        $qrCode = new \Endroid\QrCode\QrCode($qrData);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);
        
        return $qrCode->writeString();
    }
} 