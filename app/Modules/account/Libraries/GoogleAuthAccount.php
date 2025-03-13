<?php

namespace App\Modules\account\Libraries;

use App\Modules\account\Models\AccountModel;
use App\Modules\account\Entities\AccountEntity;

class GoogleAuthAccount
{
    protected $accountModel;
    private $clientID;
    private $clientSecret; 
    private $redirectUri;
    private $authUrl;

    public function __construct()
    {
        helper('setting');
        helper('App\Modules\account\Helpers\session');
        
        $this->accountModel = new AccountModel();
        
        // Cấu hình riêng cho module account
        $this->clientID = setting('App.GOOGLE_CLIENT_ID');
        $this->clientSecret = setting('App.GOOGLE_CLIENT_SECRET');
        
        // Đặt URL cho module account
        $this->authUrl = base_url('account/login/google');
        $this->redirectUri = base_url('account/login/google/callback');
    }

    /**
     * Lấy URL xác thực Google
     * @return string URL đăng nhập Google
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
            'prompt' => 'select_account',
            'state' => base64_encode(json_encode([
                'auth_url' => $this->authUrl,
                'redirect_uri' => $this->redirectUri
            ]))
        ];
        
        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    /**
     * Xử lý callback từ Google
     * @param string $code Code từ Google callback
     * @param string $state State từ Google callback
     * @return array|null Thông tin người dùng Google
     */
    public function handleCallback($code, $state = null)
    {
        if (empty($code)) {
            return null;
        }

        // Kiểm tra state để đảm bảo callback đến từ request của chúng ta
        if ($state) {
            $stateData = json_decode(base64_decode($state), true);
            if ($stateData && 
                $stateData['auth_url'] === $this->authUrl && 
                $stateData['redirect_uri'] === $this->redirectUri) {
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
        }
        return null;
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
        
        return [
            'email' => $userInfo['email'] ?? '',
            'name' => $userInfo['name'] ?? '',
            'id' => $userInfo['sub'] ?? '',
            'picture' => $userInfo['picture'] ?? '',
            'verified_email' => $userInfo['email_verified'] ?? false
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
            return false;
        }

        // Tìm người dùng trong bảng nguoi_dung theo email
        $user = $this->accountModel->where('email', $googleUser['email'])->first();
        
        if ($user === null) {
            return false;
        }
        
        // Kiểm tra trạng thái tài khoản
        if (!$user->status) {
            return false;
        }
        
        // Cập nhật thông tin Google
        $this->accountModel->update($user->id, [
            'google_id' => $googleUser['id'],
            'avatar' => $googleUser['picture'] ?? null,
            'last_login' => date('Y-m-d H:i:s')
        ]);

        // Tạo session cho người dùng đã đăng nhập
        account_session()->regenerate();
        $userData = [
            'id' => $user->id,
            'email' => $user->email,
            'fullname' => $user->fullname,
            'logged_in' => true,
            'login_method' => 'google'
        ];
        account_session_set('logged_user', $userData);
        account_session_set('user_id', $user->id);
        
        // Xóa session của admin nếu có
        if (session()->has('user_id')) {
            session()->remove('user_id');
        }
        
        return true;
    }
} 