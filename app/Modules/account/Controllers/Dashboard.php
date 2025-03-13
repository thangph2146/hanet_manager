<?php

namespace App\Modules\account\Controllers;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{

    /**
     * Hiển thị trang đăng nhập
     */
    public function index()
    {
        return view('App\Modules\account\Views\dashboard');
    }

   
} 