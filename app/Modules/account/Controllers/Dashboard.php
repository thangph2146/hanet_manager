<?php

namespace App\Modules\account\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function __construct()
    {
        // Tải helper session
        helper('App\Modules\account\Helpers\session');
        
        // Khởi tạo session
        session()->start();
        
        // Kiểm tra đăng nhập
        if (!account_is_logged_in()) {
            // Lưu URL hiện tại để chuyển hướng sau khi đăng nhập
            account_session_set('redirect_url', current_url());
            
            // Chuyển hướng đến trang đăng nhập
            return redirect()->to(base_url('account/login'))
                             ->with('warning', 'Vui lòng đăng nhập để tiếp tục!');
        }
    }

    /**
     * Hiển thị trang dashboard
     */
    public function index()
    {
        // Lấy thông tin người dùng đăng nhập
        $userData = account_session_get('logged_user');
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'Dashboard',
            'userData' => $userData
        ];
        
        return view('App\Modules\account\Views\dashboard', $data);
    }
} 