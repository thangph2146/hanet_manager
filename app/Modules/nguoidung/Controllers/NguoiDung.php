<?php

namespace App\Modules\nguoidung\Controllers;

use App\Controllers\BaseController;
use App\Modules\nguoidung\Models\NguoiDungModel;

class NguoiDung extends BaseController
{
    protected $nguoidungModel;
    
    public function __construct()
    {
        // Khởi tạo model nếu cần
        // $this->nguoidungModel = new NguoiDungModel();
    }
    
    /**
     * Hiển thị trang thông tin cá nhân
     */
    public function profile()
    {
        $data = [
            'title' => 'Thông tin cá nhân',
            'active_menu' => 'profile'
        ];
        return view('App\Modules\nguoidung\Views\profile', $data);
    }
    
    /**
     * Hiển thị trang dashboard
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard',
            'active_menu' => 'dashboard'
        ];
        return view('App\Modules\nguoidung\Views\dashboard', $data);
    }
    
    /**
     * Hiển thị trang quản lý sự kiện chính
     */
    public function events()
    {
        return redirect()->to(site_url('nguoi-dung/events/current'));
    }
    
    /**
     * Hiển thị trang sự kiện đang diễn ra
     */
    public function currentEvents()
    {
        // Logic để lấy dữ liệu sự kiện đang diễn ra
        $data = [
            'title' => 'Sự kiện đang diễn ra',
            'active_menu' => 'current_events'
            // Các dữ liệu khác nếu cần
        ];
        
        return view('frontend/components/nguoidung/current_events', $data);
    }
    
    /**
     * Hiển thị trang danh sách sự kiện
     */
    public function eventsList()
    {
        $data = [
            'title' => 'Danh sách sự kiện',
            'active_menu' => 'events_list'
            // Các dữ liệu khác nếu cần
        ];
        
        return view('frontend/components/nguoidung/events_list', $data);
    }
    
    /**
     * Hiển thị trang lịch sử đăng ký sự kiện
     */
    public function eventsHistory()
    {
        $data = [
            'title' => 'Lịch sử đăng ký sự kiện',
            'active_menu' => 'events_history'
            // Các dữ liệu khác nếu cần
        ];
        
        return view('frontend/components/nguoidung/event_history', $data);
    }
    
    /**
     * Tham gia sự kiện
     */
    public function joinEvent($eventId = null)
    {
        if ($eventId === null) {
            return redirect()->to(site_url('nguoi-dung/events/current'))->with('error', 'Không tìm thấy sự kiện');
        }
        
        // Logic xử lý tham gia sự kiện
        // ...
        
        return redirect()->to(site_url('nguoi-dung/events/current'))->with('success', 'Tham gia sự kiện thành công');
    }
    
    /**
     * Đăng ký sự kiện
     */
    public function registerEvent()
    {
        // Xử lý AJAX request đăng ký sự kiện
        if ($this->request->isAJAX()) {
            $eventId = $this->request->getPost('event_id');
            
            // Logic xử lý đăng ký
            // ...
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Đăng ký sự kiện thành công'
            ]);
        }
        
        return redirect()->to(site_url('nguoi-dung/events/current'));
    }
    
    /**
     * Hủy đăng ký sự kiện
     */
    public function cancelRegistration()
    {
        // Xử lý AJAX request hủy đăng ký
        if ($this->request->isAJAX()) {
            $eventId = $this->request->getPost('event_id');
            $reason = $this->request->getPost('reason');
            
            // Logic xử lý hủy đăng ký
            // ...
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Hủy đăng ký sự kiện thành công'
            ]);
        }
        
        return redirect()->to(site_url('nguoi-dung/events/history'));
    }
    
    /**
     * Đăng ký lại sự kiện
     */
    public function registerAgain()
    {
        // Xử lý AJAX request đăng ký lại
        if ($this->request->isAJAX()) {
            $eventId = $this->request->getPost('event_id');
            
            // Logic xử lý đăng ký lại
            // ...
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Đăng ký lại sự kiện thành công'
            ]);
        }
        
        return redirect()->to(site_url('nguoi-dung/events/history'));
    }
    
    /**
     * Tải chứng chỉ sự kiện
     */
    public function downloadCertificate($certificateId = null)
    {
        if ($certificateId === null) {
            return redirect()->to(site_url('nguoi-dung/events/history'))->with('error', 'Không tìm thấy chứng chỉ');
        }
        
        // Logic xử lý tải chứng chỉ
        // ...
        
        // Giả lập tải file
        return $this->response->download('path/to/certificate.pdf', null);
    }
    
    /**
     * Hiển thị chi tiết sự kiện
     */
    public function eventDetails($eventId = null)
    {
        if ($eventId === null) {
            return redirect()->to(site_url('nguoi-dung/events/current'))->with('error', 'Không tìm thấy sự kiện');
        }
        
        // Logic lấy chi tiết sự kiện
        // ...
        
        $data = [
            'title' => 'Chi tiết sự kiện',
            'active_menu' => 'events',
            'event' => [
                'id' => $eventId,
                'title' => 'Tên sự kiện '.$eventId,
                // Các thông tin khác về sự kiện
            ]
        ];
        
        return view('frontend/components/nguoidung/event_details', $data);
    }
} 