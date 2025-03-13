<?php

namespace App\Modules\nguoidung\Libraries;

use App\Modules\nguoidung\Models\NguoiDungModel;
use App\Modules\nguoidung\Entities\NguoiDungEntity;

class GoogleAuthNguoiDung
{
    private $clientID;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        // Tải helper setting
        helper('setting');
        
        // Tải helper session cho module nguoidung
        helper('App\Modules\nguoidung\Helpers\session');
        
        $this->clientID = setting('App.GOOGLE_CLIENT_ID');
        $this->clientSecret = setting('App.GOOGLE_CLIENT_SECRET');
        $this->redirectUri = setting('App.GOOGLE_REDIRECT_URI');
    }

    /**
     * Lấy URL xác thực Google
     *
     * @return string
     */
    public function getAuthUrl()
    {
        if (empty($this->clientID)) {
            return '#';
        }
        
        $params = [
            'client_id' => $this->clientID,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'prompt' => 'select_account'
        ];
        
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    /**
     * Xử lý callback từ Google
     *
     * @param string $code
     * @return array|null
     */
    public function handleCallback($code)
    {
        if (empty($code)) {
            return null;
        }

        try {
            // Lấy token từ code
            $token = $this->getAccessToken($code);
            
            if (empty($token) || empty($token['access_token'])) {
                return null;
            }
            
            // Lấy thông tin người dùng từ token
            $userInfo = $this->getUserInfo($token['access_token']);
            
            if (empty($userInfo)) {
                return null;
            }
            
            return $userInfo;
        } catch (\Exception $e) {
            log_message('error', 'Google Authentication Error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Lấy access token từ code
     * @param string $code Code từ Google callback
     * @return array|null Token từ Google
     */
    private function getAccessToken($code)
    {
        $url = 'https://oauth2.googleapis.com/token';
        
        $data = [
            'code' => $code,
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        if ($result === FALSE) {
            return null;
        }
        
        return json_decode($result, true);
    }
    
    /**
     * Lấy thông tin người dùng từ access token
     * @param string $accessToken Access token từ Google
     * @return array|null Thông tin người dùng Google
     */
    private function getUserInfo($accessToken)
    {
        $url = 'https://www.googleapis.com/oauth2/v3/userinfo';
        
        $options = [
            'http' => [
                'header' => "Authorization: Bearer $accessToken\r\n",
                'method' => 'GET'
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        if ($result === FALSE) {
            return null;
        }
        
        $userInfo = json_decode($result, true);
        
        // Chuyển đổi định dạng để phù hợp với cấu trúc cũ
        return [
            'id' => $userInfo['sub'] ?? '',
            'email' => $userInfo['email'] ?? '',
            'name' => $userInfo['name'] ?? '',
            'picture' => $userInfo['picture'] ?? '',
            'given_name' => $userInfo['given_name'] ?? '',
            'family_name' => $userInfo['family_name'] ?? '',
        ];
    }

    /**
     * Đăng nhập bằng Google
     *
     * @param array $googleUser
     * @return boolean
     */
    public function loginWithGoogle($googleUser)
    {
        $model = new NguoiDungModel();
        
        // Tìm người dùng theo email
        $user = $model->where('Email', $googleUser['email'])->first();
        
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
                $userId = $model->insert($userData);
                
                if (!$userId) {
                    return false;
                }
                
                // Lấy thông tin người dùng vừa tạo
                $user = $model->find($userId);
            } catch (\Exception $e) {
                log_message('error', 'Google Login Error: ' . $e->getMessage());
                return false;
            }
        }
        
        // Sử dụng session tùy chỉnh thay vì session mặc định
        nguoidung_session()->regenerate();
        
        // Lưu thông tin người dùng vào session
        $userData = [
            'id' => $user->id,
            'email' => $user->Email,
            'fullname' => $user->FullName,
            'loai_nguoi_dung_id' => $user->loai_nguoi_dung_id,
            'logged_in' => true,
            'login_method' => 'google',
        ];
        
        nguoidung_session_set('logged_user', $userData);
        
        return true;
    }
} 