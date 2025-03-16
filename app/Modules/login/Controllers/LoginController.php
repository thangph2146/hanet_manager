<?php

namespace App\Modules\login\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\StudentInfoModel;
use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index()
    {
        $googleAuth = service('googleAuth');
        $googleAuthUrl = $googleAuth->getAuthUrl('student');    
        return view('App\Modules\login\student\Views\login', ['googleAuthUrl' => $googleAuthUrl]);
    }

    public function create_student()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');    
        $remember_me = (bool) $this->request->getPost('remember_me');

        $authStudent = service('authStudent');

        if ($authStudent->login($email, $password, $remember_me)) {
            $redirect_url = session('redirect_url') ?? 'students/dashboard';
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirect_url)
                             ->with('info', 'Bạn đã login thành công!')
                             ->withCookies();
        }

        return redirect()->back()
                         ->withInput()
                         ->with('warning', 'Login đã xảy ra lỗi!');
    }

    /**
     * Xử lý callback từ Google sau khi sinh viên đăng nhập
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function googleCallback($login_type = null)
    {
        // Lấy code từ callback URL
        $code = $this->request->getGet('code');
        
        // Lấy state từ query string (chứa login_type)
        $state = $this->request->getGet('state');
        
        // In ra thông tin để debug (có thể xóa sau khi đã hoạt động)
        log_message('debug', 'Google Callback Student - State: ' . $state . ', Login Type param: ' . $login_type);
        
        // Đảm bảo login_type là 'student'
        $login_type = 'student';
        
        if (empty($code)) {
            return redirect()->to('login')
                            ->with('warning', 'Không thể xác thực với Google!');
        }
        
        // Xử lý code để lấy thông tin người dùng
        $googleAuth = service('googleAuth');
        $googleUser = $googleAuth->handleCallback($code, $login_type);
        
        if (empty($googleUser)) {
            return redirect()->to('login')
                            ->with('warning', 'Không thể lấy thông tin từ Google!');
        }
        
        // Tìm sinh viên theo email
        $model = new StudentModel();
        $user = $model->where('Email', $googleUser['email'])->first();
        
        // Hiển thị thông báo phù hợp nếu không tìm thấy người dùng
        if ($user === null) {
            return redirect()->to('login')
                            ->with('warning', 'Không tìm thấy tài khoản sinh viên với email: ' . $googleUser['email']);
        }
        
        // Đăng nhập sinh viên
        if ($googleAuth->loginWithGoogle($googleUser, $login_type)) {
            $redirect_url = session('redirect_url') ?? 'students/dashboard';
            unset($_SESSION['redirect_url']);
            
            return redirect()->to($redirect_url)
                            ->with('info', 'Bạn đã đăng nhập thành công với Google!')
                            ->withCookies();
        } else {
            return redirect()->to('login')
                            ->with('warning', 'Đăng nhập với Google không thành công!');
        }
    }

    public function deleteStudent()
    {
        service('authStudent')->logout();
        return redirect()->to('login/showlogoutmessagestudent')
                         ->withCookies();
    }

    public function logoutStudent()
    {
        service('authStudent')->logout();
        return redirect()->to('login/showlogoutmessagestudent')
                         ->withCookies();
    }

    public function showLogoutMessageStudent()
    {
        return redirect()->to('login')
                         ->with('info', 'Bạn đã đăng xuất thành công!');
    }
} 