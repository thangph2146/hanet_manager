<?php

namespace App\Modules\login\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AdminController extends BaseController
{
    public function index()
    {
        // Lấy URL đăng nhập Google
        $googleAuth = service('googleAuth');
        $googleAuthUrl = $googleAuth->getAuthUrl('admin');
        
        return view('Modules/login/admin/Views/login', ['googleAuthUrl' => $googleAuthUrl]);
    }

    public function create()
    {
        $u_email = $this->request->getPost('u_email');
        $password = $this->request->getPost('password');
        $auth = service('auth');

        if ($auth->login($u_email, $password)) {
            $redirect_url = session('redirect_url') ?? 'users/dashboard';
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirect_url)
                            ->with('info', 'Bạn đã đăng nhập thành công!')
                            ->withCookies();
        } else {
            return redirect()->back()
                            ->withInput()
                            ->with('warning', 'Đăng nhập đã xảy ra lỗi!');
        }
    }

    public function logout()
    {
        service('auth')->logout();
        return redirect()->to('login/showlogoutmessage')
                        ->withCookies();
    }

    public function showLogoutMessage()
    {
        return redirect()->to('login/admin')
                        ->with('info', 'Bạn đã đăng xuất thành công!');
    }

    public function googleCallback($type = null)
    {
        // Lấy code từ callback URL
        $code = $this->request->getGet('code');
        
        // Lấy state từ query string (chứa login_type)
        $state = $this->request->getGet('state');
        
        if (!empty($state)) {
            $type = $state;
        }
        
        if (empty($type)) {
            $type = 'admin';
        }
        
        if (empty($code)) {
            return redirect()->to('login/admin')
                            ->with('warning', 'Không thể xác thực với Google!');
        }
        
        // Xử lý code để lấy thông tin người dùng
        $googleAuth = service('googleAuth');
        $googleUser = $googleAuth->handleCallback($code, $type);
        
        if (empty($googleUser)) {
            return redirect()->to('login/admin')
                            ->with('error', 'Đăng nhập bằng Google thất bại!');
        }
        
        // Xử lý thông tin và đăng nhập người dùng
        if ($googleAuth->loginWithGoogle($googleUser, $type)) {
            $redirect_url = session('redirect_url') ?? 'users/dashboard';
            unset($_SESSION['redirect_url']);
            
            return redirect()->to($redirect_url)
                            ->with('info', 'Đăng nhập thành công với Google!')
                            ->withCookies();
        }
        
        return redirect()->to('login/admin')
                        ->with('error', 'Tài khoản Google không được phép đăng nhập!');
    }
} 