<?php

namespace App\Modules\account\Libraries;

use App\Modules\account\Models\AccountModel;
use App\Modules\account\Entities\AccountEntity;

class GoogleAuthAccount
{
    private $clientID;
    private $clientSecret;
    private $redirectUri;

    public function __construct()
    {
        helper('setting');
        helper('App\Modules\account\Helpers\session');
        
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

        $token = $this->getAccessToken($code);
        if ($token === null) {
            return null;
        }

        return $this->getUserInfo($token);
    }

    /**
     * Lấy access token từ Google
     *
     * @param string $code
     * @return string|null
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
        
        $response = json_decode($result, true);
        return $response['access_token'] ?? null;
    }

    /**
     * Lấy thông tin người dùng từ Google
     *
     * @param string $accessToken
     * @return array|null
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
            'given_name' => $userInfo['given_name'] ?? '',
            'family_name' => $userInfo['family_name'] ?? '',
            'picture' => $userInfo['picture'] ?? '',
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
        $model = new AccountModel();
        
        // Tìm người dùng theo email
        $user = $model->findByEmail($googleUser['email']);
        
        if ($user === null) {
            // Không cho phép tạo tài khoản mới từ Google
            log_message('error', 'Google Login Error: Email ' . $googleUser['email'] . ' không tồn tại trong hệ thống');
            return false;
        }
        
        // Kiểm tra trạng thái tài khoản
        if (!$user->isActive()) {
            log_message('error', 'Google Login Error: Tài khoản ' . $googleUser['email'] . ' đã bị vô hiệu hóa');
            return false;
        }
        
        // Sử dụng session tùy chỉnh thay vì session mặc định
        account_session()->regenerate();
        
        // Lưu thông tin người dùng vào session
        $userData = [
            'id' => $user->id,
            'email' => $user->Email,
            'fullname' => $user->FullName,
            'loai_nguoi_dung_id' => $user->loai_nguoi_dung_id,
            'logged_in' => true,
            'login_method' => 'google',
        ];
        
        account_session_set('logged_user', $userData);
        
        return true;
    }
} 