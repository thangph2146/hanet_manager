<?php

namespace App\Modules\sukien\Controllers;

use App\Controllers\BaseController;

class Sukien extends BaseController
{
    public function index()
    {
        return view('App\Modules\sukien\Views\welcome');
    }
    
    public function checkin()
    {
        // Hàm xử lý checkin sẽ được cài đặt sau
        return view('App\Modules\sukien\Views\checkin');
    }
    
    public function register()
    {
        // Hàm xử lý đăng ký tham gia sự kiện sẽ được cài đặt sau
        return view('App\Modules\sukien\Views\register');
    }
} 