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
        // Get all events
        $events = $this->sukienModel->getAllEvents();
        $event_types = $this->loaiSukienModel->getAllEventTypes();
        
        // Enhance events data with additional information
        foreach ($events as &$event) {
            // Check if user is registered for this event
            $event['is_registered'] = $this->dangKySukienModel->isRegistered($this->user_id, $event['id']);
            
            // Get event status
            $today = date('Y-m-d H:i:s');
            if ($today < $event['ngay_to_chuc']) {
                $event['status'] = 'upcoming';
            } elseif ($today <= $event['ngay_ket_thuc']) {
                $event['status'] = 'ongoing';
            } else {
                $event['status'] = 'completed';
            }
        }
        
        $data = [
            'events' => $events,
            'event_types' => $event_types,
            // Additional data for layout
            'user_name' => 'Nguyễn Văn A',
            'user_role' => 'Sinh viên',
            'user_progress' => 65,
            'notification_count' => 3,
            'event_count' => count(array_filter($events, function($event) {
                return $event['status'] == 'upcoming' || $event['status'] == 'ongoing';
            }))
        ];
        
        return view('Modules/students/Views/events/index', $data);
    }
    
    public function detail($id)
    {
        // Get event details
        $event = $this->sukienModel->getEventById($id);
        
        if (empty($event)) {
            return redirect()->to('/students/events')->with('error', 'Sự kiện không tồn tại');
        }
        
        // Check if user is registered
        $is_registered = $this->dangKySukienModel->isRegistered($this->user_id, $id);
        
        // Get event status
        $today = date('Y-m-d H:i:s');
        if ($today < $event['ngay_to_chuc']) {
            $event['status'] = 'upcoming';
        } elseif ($today <= $event['ngay_ket_thuc']) {
            $event['status'] = 'ongoing';
        } else {
            $event['status'] = 'completed';
        }
        
        // Get check-in/check-out status
        $checked_in = $this->checkinSukienModel->hasUserCheckedIn($this->user_id, $id);
        $checked_out = $this->checkoutSukienModel->hasUserCheckedOut($this->user_id, $id);
        
        $data = [
            'event' => $event,
            'is_registered' => $is_registered,
            'checked_in' => $checked_in,
            'checked_out' => $checked_out,
            // Additional data for layout
            'user_name' => 'Nguyễn Văn A',
            'user_role' => 'Sinh viên',
            'user_progress' => 65,
            'notification_count' => 3,
            'event_count' => 5
        ];
        
        return view('Modules/students/Views/events/detail', $data);
    }
    
    public function registered()
    {
        // Get registered events for the current user
        $registered_events = [];
        $all_events = $this->sukienModel->getAllEvents();
        
        foreach ($all_events as $event) {
            if ($this->dangKySukienModel->isRegistered($this->user_id, $event['id'])) {
                // Get event status
                $today = date('Y-m-d H:i:s');
                if ($today < $event['ngay_to_chuc']) {
                    $event['status'] = 'upcoming';
                } elseif ($today <= $event['ngay_ket_thuc']) {
                    $event['status'] = 'ongoing';
                } else {
                    $event['status'] = 'completed';
                }
                
                // Get check-in/check-out status
                $event['checked_in'] = $this->checkinSukienModel->hasUserCheckedIn($this->user_id, $event['id']);
                $event['checked_out'] = $this->checkoutSukienModel->hasUserCheckedOut($this->user_id, $event['id']);
                
                $registered_events[] = $event;
            }
        }
        
        $data = [
            'registered_events' => $registered_events,
            // Additional data for layout
            'user_name' => 'Nguyễn Văn A',
            'user_role' => 'Sinh viên',
            'user_progress' => 65,
            'notification_count' => 3,
            'event_count' => count(array_filter($all_events, function($event) {
                $today = date('Y-m-d H:i:s');
                return ($today < $event['ngay_ket_thuc']);
            }))
        ];
        
        return view('Modules/students/Views/events/registered', $data);
    }
    
    public function register($id)
    {
        // Check if event exists
        $event = $this->sukienModel->getEventById($id);
        
        if (empty($event)) {
            $response = [
                'success' => false,
                'message' => 'Sự kiện không tồn tại'
            ];
            return $this->response->setJSON($response);
        }
        
        // Check if event is still open for registration
        $today = date('Y-m-d H:i:s');
        if ($today >= $event['ngay_to_chuc']) {
            $response = [
                'success' => false,
                'message' => 'Sự kiện đã diễn ra, không thể đăng ký'
            ];
            return $this->response->setJSON($response);
        }
        
        // Check if already registered
        if ($this->dangKySukienModel->isRegistered($this->user_id, $id)) {
            $response = [
                'success' => false,
                'message' => 'Bạn đã đăng ký tham gia sự kiện này'
            ];
            return $this->response->setJSON($response);
        }
        
        // Register for the event
        $registration_data = [
            'su_kien_id' => $id,
            'nguoi_dung_id' => $this->user_id,
            'ngay_dang_ky' => date('Y-m-d H:i:s'),
            'nguon_gioi_thieu' => 'Website',
            'loai_nguoi_dung' => 'Sinh viên',
            'status' => 1
        ];
        
        $result = $this->dangKySukienModel->registerEvent($registration_data);
        
        if ($result) {
            $response = [
                'success' => true,
                'message' => 'Đăng ký tham gia sự kiện thành công'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại sau'
            ];
        }
        
        return $this->response->setJSON($response);
    }
    
    public function cancel($id)
    {
        // Check if user is registered
        if (!$this->dangKySukienModel->isRegistered($this->user_id, $id)) {
            $response = [
                'success' => false,
                'message' => 'Bạn chưa đăng ký tham gia sự kiện này'
            ];
            return $this->response->setJSON($response);
        }
        
        // Check if event has started
        $event = $this->sukienModel->getEventById($id);
        $today = date('Y-m-d H:i:s');
        
        if ($today >= $event['ngay_to_chuc']) {
            $response = [
                'success' => false,
                'message' => 'Sự kiện đã diễn ra, không thể hủy đăng ký'
            ];
            return $this->response->setJSON($response);
        }
        
        // Cancel registration
        // Note: In real implementation, this would call the model's cancelRegistration method
        $response = [
            'success' => true,
            'message' => 'Hủy đăng ký thành công'
        ];
        
        return $this->response->setJSON($response);
    }
    
    public function checkin($id)
    {
        // Check if event exists and is ongoing
        $event = $this->sukienModel->getEventById($id);
        $today = date('Y-m-d H:i:s');
        
        if (empty($event)) {
            $response = [
                'success' => false,
                'message' => 'Sự kiện không tồn tại'
            ];
            return $this->response->setJSON($response);
        }
        
        if ($today < $event['ngay_to_chuc'] || $today > $event['ngay_ket_thuc']) {
            $response = [
                'success' => false,
                'message' => 'Sự kiện không đang diễn ra, không thể check-in'
            ];
            return $this->response->setJSON($response);
        }
        
        // Check if user is registered
        if (!$this->dangKySukienModel->isRegistered($this->user_id, $id)) {
            $response = [
                'success' => false,
                'message' => 'Bạn chưa đăng ký tham gia sự kiện này'
            ];
            return $this->response->setJSON($response);
        }
        
        // Check if already checked in
        if ($this->checkinSukienModel->hasUserCheckedIn($this->user_id, $id)) {
            $response = [
                'success' => false,
                'message' => 'Bạn đã check-in cho sự kiện này'
            ];
            return $this->response->setJSON($response);
        }
        
        // Get check-in code from request
        $json = $this->request->getJSON();
        $checkin_code = isset($json->code) ? $json->code : '';
        
        // Verify check-in code - in real implementation, this would validate against a stored code
        // For demo, we'll just check if code is not empty
        if (empty($checkin_code)) {
            $response = [
                'success' => false,
                'message' => 'Mã check-in không hợp lệ'
            ];
            return $this->response->setJSON($response);
        }
        
        // Process check-in
        // Note: In real implementation, this would call the model's methods
        $response = [
            'success' => true,
            'message' => 'Check-in thành công'
        ];
        
        return $this->response->setJSON($response);
    }
    
    public function checkout($id)
    {
        // Check if event exists and is ongoing
        $event = $this->sukienModel->getEventById($id);
        $today = date('Y-m-d H:i:s');
        
        if (empty($event)) {
            $response = [
                'success' => false,
                'message' => 'Sự kiện không tồn tại'
            ];
            return $this->response->setJSON($response);
        }
        
        if ($today < $event['ngay_to_chuc'] || $today > $event['ngay_ket_thuc']) {
            $response = [
                'success' => false,
                'message' => 'Sự kiện không đang diễn ra, không thể check-out'
            ];
            return $this->response->setJSON($response);
        }
        
        // Check if user is registered
        if (!$this->dangKySukienModel->isRegistered($this->user_id, $id)) {
            $response = [
                'success' => false,
                'message' => 'Bạn chưa đăng ký tham gia sự kiện này'
            ];
            return $this->response->setJSON($response);
        }
        
        // Check if already checked out
        if ($this->checkoutSukienModel->hasUserCheckedOut($this->user_id, $id)) {
            $response = [
                'success' => false,
                'message' => 'Bạn đã check-out khỏi sự kiện này'
            ];
            return $this->response->setJSON($response);
        }
        
        // Check if user has checked in
        if (!$this->checkinSukienModel->hasUserCheckedIn($this->user_id, $id)) {
            $response = [
                'success' => false,
                'message' => 'Bạn chưa check-in vào sự kiện này'
            ];
            return $this->response->setJSON($response);
        }
        
        // Get check-out code from request
        $json = $this->request->getJSON();
        $checkout_code = isset($json->code) ? $json->code : '';
        
        // Verify check-out code - in real implementation, this would validate against a stored code
        // For demo, we'll just check if code is not empty
        if (empty($checkout_code)) {
            $response = [
                'success' => false,
                'message' => 'Mã check-out không hợp lệ'
            ];
            return $this->response->setJSON($response);
        }
        
        // Process check-out
        // Note: In real implementation, this would call the model's methods
        $response = [
            'success' => true,
            'message' => 'Check-out thành công'
        ];
        
        return $this->response->setJSON($response);
    }
} 