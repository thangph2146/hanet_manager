<?php

namespace App\Modules\account\Controllers;

use App\Controllers\BaseController;
use App\Modules\account\Models\AccountModel;
use App\Modules\account\Libraries\AuthenticationAccount;
use App\Modules\account\Libraries\GoogleAuthAccount;
use CodeIgniter\HTTP\ResponseInterface;

class Login extends BaseController
{
    protected $loginModel;
    protected $auth;
    protected $googleAuth;

    public function __construct()
    {
        $this->loginModel = new AccountModel();
        $this->auth = new AuthenticationAccount();
        $this->googleAuth = new GoogleAuthAccount();
        
        // Tải helper session
        helper('App\Modules\account\Helpers\session');
        
        // Khởi tạo session
        session()->start();
    }

    /**
     * Hiển thị trang đăng nhập
     */
    public function index()
    {
        // Nếu đã đăng nhập, chuyển hướng đến trang chủ
        if (account_is_logged_in()) {
            return redirect()->to(base_url('account/dashboard'));
        }

        // Lấy URL đăng nhập Google
        $googleAuthUrl = $this->googleAuth->getAuthUrl();

        return view('App\Modules\account\Views\login', [
            'googleAuthUrl' => $googleAuthUrl
        ]);
    }

    /**
     * Xử lý đăng nhập
     */
    public function authenticate()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ];

        $messages = [
            'email' => [
                'required' => 'Email không được để trống',
                'valid_email' => 'Email không hợp lệ',
            ],
            'password' => [
                'required' => 'Mật khẩu không được để trống',
                'min_length' => 'Mật khẩu phải có ít nhất 6 ký tự',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            account_session_set('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $remember = (bool) $this->request->getPost('remember');

        if ($this->auth->login($email, $password)) {
            // Nếu chọn "Nhớ mật khẩu", lưu cookie
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                account_set_remember_cookie($this->auth->getCurrentUser()->id, $token);
            }

            // Chuyển hướng đến trang chủ hoặc trang được yêu cầu trước đó
            $redirect_url = account_session_get('redirect_url') ?? base_url('account/dashboard');
            account_session_remove('redirect_url');

            account_session_set('success', 'Đăng nhập thành công!');
            
            return redirect()->to($redirect_url)
                           ->withCookies();
        }

        account_session_set('error', 'Email hoặc mật khẩu không đúng');
        return redirect()->back()->withInput();
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        // Xóa cookie nhớ mật khẩu nếu có
        account_clear_remember_cookie();
        
        // Đăng xuất người dùng
        $this->auth->logout();
        
        account_session_set('success', 'Đăng xuất thành công!');
        
        // Chuyển hướng đến trang đăng nhập
        return redirect()->to(base_url('account/login'));
    }
} 