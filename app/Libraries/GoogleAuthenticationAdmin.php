<?php
/**
 * Thư viện xử lý xác thực Google cho admin
 * @author	Claude AI <claude@anthropic.com>
 */

namespace App\Libraries;

class GoogleAuthenticationAdmin {

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
        log_message('debug', 'Google Auth Admin - Redirect URI: ' . $this->redirectUri);
        
        $params = [
            'client_id' => $this->clientID,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => 'email profile',
            'access_type' => 'online',
            'prompt' => 'select_account',
            'state' => 'admin' // Luôn sử dụng 'admin' cho admin
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
        
        // Đảm bảo state là 'admin'
        if (empty($state) || $state !== 'admin') {
            $state = 'admin';
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
            'login_type' => 'admin'
        ];
    }

    /**
     * Đăng nhập admin bằng tài khoản Google
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
    
        $userModel = new \App\Models\UserModel();
        $user = $userModel->where('u_email', $googleUser['email'])->first();
        
        // Nếu người dùng không tồn tại
        if ($user === null) {
            log_message('error', 'Admin user not found with email: ' . $googleUser['email']);
            return false;
        }
        
        // Nếu tài khoản bị vô hiệu hóa
        if (!$user->u_status) {
            log_message('error', 'Admin account is inactive: ' . $user->u_id);
            return false;
        }
        
        // Đăng nhập người dùng
        $this->logInUser($user);
        log_message('info', 'Admin logged in successfully via Google: ' . $user->u_id);
        return true;
    }
    
    /**
     * Đăng nhập người dùng
     * @param object $user Đối tượng người dùng
     */
    private function logInUser($user)
    {
        $session = session();
        $session->regenerate();
        $session->set('user_id', $user->u_id);
    }
} 