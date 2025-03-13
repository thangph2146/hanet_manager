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
    }

    /**
     * Hiển thị trang đăng nhập
     */
    public function index()
    {
        // Nếu đã đăng nhập, chuyển hướng đến trang chủ
        if (account_is_logged_in()) {
            return redirect()->to(base_url('account'));
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
                // Lưu token vào cơ sở dữ liệu hoặc cache (nếu cần)
                // $this->loginModel->saveRememberToken($user->id, $token);
                account_set_remember_cookie($this->auth->getCurrentUser()->id, $token);
            }

            // Chuyển hướng đến trang chủ hoặc trang được yêu cầu trước đó
            $redirect_url = account_session_get('redirect_url') ?? base_url('account/dashboard');
            account_session_remove('redirect_url');

            account_session_set('info', 'Bạn đã đăng nhập thành công!');
            
            return redirect()->to($redirect_url)
                             ->withCookies();
        } else {
            account_session_set('warning', 'Email hoặc mật khẩu không đúng');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        // Đăng xuất người dùng
        $this->auth->logout();
        
        // Sử dụng session tùy chỉnh thay vì session mặc định
        account_session_set('info', 'Bạn đã đăng xuất thành công!');
        
        // Chuyển hướng đến trang đăng nhập
        return redirect()->to(base_url('account/login'));
    }

    /**
     * Hiển thị trang đăng nhập với Google
     */
    public function google()
    {
        // Nếu đã đăng nhập, chuyển hướng đến trang chủ
        if (account_is_logged_in()) {
            return redirect()->to(base_url('account/dashboard'));
        }

        // Lấy URL đăng nhập Google
        $googleAuthUrl = $this->googleAuth->getAuthUrl();
        
        return view('App\Modules\account\Views\login_google', [
            'googleAuthUrl' => $googleAuthUrl
        ]);
    }

    /**
     * Xử lý callback từ Google sau khi người dùng đăng nhập
     */
    public function googleCallback()
    {
        // Lấy code từ callback URL
        $code = $this->request->getGet('code');
        
        if (empty($code)) {
            account_session_set('warning', 'Không thể xác thực với Google!');
            return redirect()->to('account/login');
        }
        
        // Xử lý code để lấy thông tin người dùng
        $googleUser = $this->googleAuth->handleCallback($code);
        
        if (empty($googleUser)) {
            account_session_set('warning', 'Không thể lấy thông tin từ Google!');
            return redirect()->to('account/login');
        }
        
        // Đăng nhập người dùng
        if ($this->googleAuth->loginWithGoogle($googleUser)) {
            $redirect_url = account_session_get('redirect_url') ?? 'account/dashboard';
            account_session_remove('redirect_url');
            
            account_session_set('info', 'Bạn đã đăng nhập thành công với Google!');
            return redirect()->to($redirect_url);
        } else {
            account_session_set('warning', 'Đăng nhập với Google không thành công!');
            return redirect()->to('account/login');
        }
    }
} 