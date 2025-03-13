<?php

namespace App\Modules\nguoidung\Controllers;

use App\Controllers\BaseController;
use App\Modules\nguoidung\Models\NguoiDungModel;
use App\Modules\nguoidung\Libraries\GoogleAuthNguoiDung;
use CodeIgniter\HTTP\ResponseInterface;

class LoginGoogle extends BaseController
{
    protected $googleAuth;
    protected $nguoiDungModel;

    public function __construct()
    {
        $this->googleAuth = new GoogleAuthNguoiDung();
        $this->nguoiDungModel = new NguoiDungModel();
        
        // Tải helper session
        helper('App\Modules\nguoidung\Helpers\session');
    }

    /**
     * Hiển thị trang đăng nhập với Google
     */
    public function index()
    {
        // Nếu đã đăng nhập, chuyển hướng đến trang chủ
        if (nguoidung_is_logged_in()) {
            return redirect()->to(base_url('nguoidung/dashboard'));
        }

        // Lấy URL đăng nhập Google
        $googleAuthUrl = $this->googleAuth->getAuthUrl();
        
        return view('App\Modules\nguoidung\Views\login_google', [
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
            nguoidung_session_set('warning', 'Không thể xác thực với Google!');
            return redirect()->to('nguoidung/login');
        }
        
        // Xử lý code để lấy thông tin người dùng
        $googleUser = $this->googleAuth->handleCallback($code);
        
        if (empty($googleUser)) {
            nguoidung_session_set('warning', 'Không thể lấy thông tin từ Google!');
            return redirect()->to('nguoidung/login');
        }
        
        // Kiểm tra xem người dùng đã tồn tại trong hệ thống chưa
        $user = $this->nguoiDungModel->findByEmail($googleUser['email']);
        
        // Nếu người dùng chưa tồn tại, tạo mới
        if ($user === null) {
            // Tạo người dùng mới từ thông tin Google
            $userData = [
                'AccountId' => explode('@', $googleUser['email'])[0], // Tạo AccountId từ email
                'Email' => $googleUser['email'],
                'FullName' => $googleUser['name'],
                'FirstName' => $googleUser['given_name'],
                'status' => 1, // Kích hoạt tài khoản
                'PW' => password_hash(bin2hex(random_bytes(8)), PASSWORD_DEFAULT), // Tạo mật khẩu ngẫu nhiên
                'loai_nguoi_dung_id' => 2, // Loại người dùng mặc định
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Thử tạo người dùng mới
            try {
                $userId = $this->nguoiDungModel->insert($userData);
                
                if (!$userId) {
                    nguoidung_session_set('warning', 'Không thể tạo tài khoản mới!');
                    return redirect()->to('nguoidung/login');
                }
                
                // Lấy thông tin người dùng vừa tạo
                $user = $this->nguoiDungModel->find($userId);
            } catch (\Exception $e) {
                log_message('error', 'Google Login Error: ' . $e->getMessage());
                nguoidung_session_set('warning', 'Không thể tạo tài khoản mới: ' . $e->getMessage());
                return redirect()->to('nguoidung/login');
            }
        }
        
        // Đăng nhập người dùng
        if ($this->googleAuth->loginWithGoogle($googleUser)) {
            $redirect_url = nguoidung_session_get('redirect_url') ?? 'nguoidung/dashboard';
            nguoidung_session_remove('redirect_url');
            
            nguoidung_session_set('info', 'Bạn đã đăng nhập thành công với Google!');
            return redirect()->to($redirect_url);
        } else {
            nguoidung_session_set('warning', 'Đăng nhập với Google không thành công!');
            return redirect()->to('nguoidung/login');
        }
    }
} 