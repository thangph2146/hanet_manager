<?php

namespace App\Modules\account\Controllers;

use App\Controllers\BaseController;
use App\Modules\account\Libraries\GoogleAuthAccount;
use App\Modules\account\Models\AccountModel;
use CodeIgniter\HTTP\ResponseInterface;

class LoginGoogle extends BaseController
{
    protected $googleAuth;
    protected $accountModel;

    public function __construct()
    {
        parent::__construct();
        $this->googleAuth = new GoogleAuthAccount();
        $this->accountModel = new AccountModel();
        
        // Tải helper session
        helper('App\Modules\account\Helpers\session');
    }

    /**
     * Hiển thị trang đăng nhập với Google
     */
    public function index()
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
            account_session_set('error', 'Không thể xác thực với Google!');
            return redirect()->to('account/login');
        }
        
        // Xử lý code để lấy thông tin người dùng
        $googleUser = $this->googleAuth->handleCallback($code);
        
        if (empty($googleUser)) {
            account_session_set('error', 'Không thể lấy thông tin từ Google!');
            return redirect()->to('account/login');
        }
        
        // Đăng nhập người dùng
        if ($this->googleAuth->loginWithGoogle($googleUser)) {
            $redirect_url = account_session_get('redirect_url') ?? base_url('account/dashboard');
            account_session_remove('redirect_url');
            
            account_session_set('success', 'Đăng nhập thành công với Google!');
            return redirect()->to($redirect_url);
        }

        account_session_set('error', 'Email này chưa được đăng ký trong hệ thống!');
        return redirect()->to('account/login');
    }
} 