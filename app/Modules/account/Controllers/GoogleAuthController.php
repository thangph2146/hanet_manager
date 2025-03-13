<?php

namespace App\Modules\account\Controllers;

use App\Controllers\BaseController;
use App\Modules\account\Libraries\GoogleAuthAccount;

class GoogleAuthController extends BaseController
{
    protected $googleAuth;

    public function __construct()
    {
        $this->googleAuth = new GoogleAuthAccount();
    }

    /**
     * Chuyển hướng người dùng đến trang đăng nhập Google
     */
    public function login()
    {
        return redirect()->to($this->googleAuth->getAuthUrl());
    }

    /**
     * Xử lý callback từ Google OAuth
     */
    public function callback()
    {
        try {
            $code = $this->request->getVar('code');
            if (empty($code)) {
                return redirect()->to('/account/login')
                    ->with('error', 'Không nhận được mã xác thực từ Google');
            }

            $googleUser = $this->googleAuth->handleCallback($code);
            
            if ($googleUser && $this->googleAuth->loginWithGoogle($googleUser)) {
                return redirect()->to('/account/dashboard')
                    ->with('success', 'Đăng nhập thành công');
            }
       
            return redirect()->to('/account/login')
                ->with('error', 'Đăng nhập Google thất bại');
        } catch (\Exception $e) {
            log_message('error', '[Google Auth] ' . $e->getMessage());
            return redirect()->to('/account/login')
                ->with('error', 'Có lỗi xảy ra trong quá trình xác thực: ' . $e->getMessage());
        }
    }
} 