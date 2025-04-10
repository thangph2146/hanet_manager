<?php
/**
 * Thư viện xử lý xác thực Google cho người dùng
 * @author	Claude AI <claude@anthropic.com>
 */

namespace App\Libraries;

class GoogleAuthenticationUser {

    private $clientID;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        // Load helper setting
        helper('setting');
        
        $this->clientID = setting('App.GOOGLE_CLIENT_ID');
        $this->clientSecret = setting('App.GOOGLE_CLIENT_SECRET');
        $this->redirectUri = setting('App.GOOGLE_REDIRECT_URI');
    }

    /**
     * Tạo URL đăng nhập Google
     * @return string URL đăng nhập Google
     */
    public function getAuthUrl()
    {
        if (empty($this->clientID)) {
            return '#';
        }
        
        // Logging để debug
        log_message('debug', 'Google Auth User - Redirect URI: ' . $this->redirectUri);
        
        $params = [
            'client_id' => $this->clientID,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'prompt' => 'select_account',
            'state' => 'student' // Luôn sử dụng 'student' cho người dùng
        ];
        
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    /**
     * Xử lý callback từ Google
     * @param string $code Code từ Google callback
     * @return array|null Thông tin người dùng Google
     */
    public function handleCallback($code)
    {
        if (empty($code)) {
            return null;
        }
        
        // Lấy state từ query string nếu có
        $request = \Config\Services::request();
        $state = $request->getGet('state');
        
        // Đảm bảo state là 'student'
        if (empty($state) || $state !== 'student') {
            $state = 'student';
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
            'email' => $userInfo['email'] ?? '',
            'name' => $userInfo['name'] ?? '',
            'id' => $userInfo['sub'] ?? '',
            'picture' => $userInfo['picture'] ?? '',
            'verified_email' => $userInfo['email_verified'] ?? false,
            'login_type' => 'student'
        ];
    }

    /**
     * Đăng nhập người dùng bằng tài khoản Google
     * @param array $googleUser Thông tin người dùng Google
     * @return bool Trạng thái đăng nhập
     */
    public function loginWithGoogle($googleUser)
    {
        if (empty($googleUser) || empty($googleUser['email'])) {
            log_message('error', 'Google Login: Thông tin người dùng không hợp lệ');
            return false;
        }
        
        // Logging để debug
        log_message('debug', 'Login with Google - Email: ' . $googleUser['email']);
    
        $nguoidungModel = new \App\Modules\quanlynguoidung\Models\NguoiDungModel();
        $nguoidung = $nguoidungModel->where('Email', $googleUser['email'])->first();
        
        // Nếu sinh viên không tồn tại
        if ($nguoidung === null) {
            log_message('error', 'Student not found with email: ' . $googleUser['email']);
            return false;
        }
        
        // Nếu tài khoản sinh viên không hoạt động
        if (!$nguoidung->status) {
            log_message('error', 'Student account is inactive: ' . $nguoidung->id);
            return false;
        }
        
        // Đăng nhập sinh viên
        $this->logInNguoiDung($nguoidung);
        log_message('info', 'Student logged in successfully via Google: ' . $nguoidung->nguoi_dung_id);
        return true;
    }
    
    private function logInNguoiDung($nguoidung)
    {
        $session = session();
        $session->regenerate();
        $session->set('nguoi_dung_id', $nguoidung->nguoi_dung_id);
    }
} 