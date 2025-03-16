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

    public function deleteStudent()
    {
        service('authStudent')->logout();
        return redirect()->to('login/showlogoutmessagestudent')
                         ->withCookies();
    }

    public function showLogoutMessageStudent()
    {
        return redirect()->to('login')
                         ->with('info', 'bạn đã logout thành công!');
    }
} 