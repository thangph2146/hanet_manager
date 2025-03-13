<?php
/**
 * Thư viện xử lý xác thực Google
 * @author	Claude AI <claude@anthropic.com>
 */

namespace App\Modules\account\Libraries;

use Exception;

interface GoogleAuthInterface {
    public function getAuthUrl(): string;
    public function handleCallback(string $code): ?array;
    public function loginWithGoogle(array $googleUser): bool;
}

interface GoogleHttpClientInterface {
    public function post(string $url, array $data): ?array;
    public function get(string $url, string $accessToken): ?array;
}

class GoogleHttpClient implements GoogleHttpClientInterface {
    public function post(string $url, array $data): ?array {
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        return ($result === FALSE) ? null : json_decode($result, true);
    }
    
    public function get(string $url, string $accessToken): ?array {
        $options = [
            'http' => [
                'header' => "Authorization: Bearer $accessToken\r\n",
                'method' => 'GET'
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        return ($result === FALSE) ? null : json_decode($result, true);
    }
}

class GoogleAuthAccount implements GoogleAuthInterface {
    private const GOOGLE_AUTH_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
    private const GOOGLE_TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private const GOOGLE_USERINFO_URL = 'https://www.googleapis.com/oauth2/v3/userinfo';
    private const GOOGLE_SCOPE = 'email profile';

    private string $clientID;
    private string $clientSecret;
    private string $redirectUri;
    private GoogleHttpClientInterface $httpClient;

    public function __construct(GoogleHttpClientInterface $httpClient = null) 
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
    public function getAuthUrl(): string
    {
        if (empty($this->clientID)) {
            return '#';
        }
        
        $params = [
            'client_id' => $this->clientID,
            'redirect_uri' => $this->redirectUri,
            'response_type' => 'code',
            'scope' => self::GOOGLE_SCOPE,
            'access_type' => 'online',
            'prompt' => 'select_account'
        ];
        
        return self::GOOGLE_AUTH_URL . '?' . http_build_query($params);
    }

    /**
     * Xử lý callback từ Google
     * @param string $code Code từ Google callback
     * @return array|null Thông tin người dùng Google
     * @throws Exception Khi có lỗi xử lý
     */
    public function handleCallback(string $code): ?array
    {
        if (empty($code)) {
            return null;
        }

        try {
            $token = $this->getAccessToken($code);
            if (empty($token['access_token'])) {
                throw new Exception('Invalid access token');
            }
            
            $userInfo = $this->getUserInfo($token['access_token']);
            if (empty($userInfo)) {
                throw new Exception('Failed to get user info');
            }
            
            return $userInfo;
        } catch (Exception $e) {
            log_message('error', 'Google Authentication Error: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Lấy access token từ code
     * @param string $code Code từ Google callback
     * @return array Token từ Google
     * @throws Exception Khi không lấy được token
     */
    private function getAccessToken(string $code): array
    {
        $data = [
            'code' => $code,
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        ];
        
        $response = $this->httpClient->post(self::GOOGLE_TOKEN_URL, $data);
        
        if (!$response) {
            throw new Exception('Failed to get access token');
        }
        
        return $response;
    }
    
    /**
     * Lấy thông tin người dùng từ access token
     * @param string $accessToken Access token từ Google
     * @return array Thông tin người dùng Google
     * @throws Exception Khi không lấy được thông tin người dùng
     */
    private function getUserInfo(string $accessToken): array
    {
        $accountInfo = $this->httpClient->get(self::GOOGLE_USERINFO_URL, $accessToken);
        
        if (!$accountInfo) {
            throw new Exception('Failed to get user info');
        }
        
        return [
            'Email' => $accountInfo['email'] ?? '',
            'FullName' => $accountInfo['name'] ?? '',
            'id' => $accountInfo['sub'] ?? '',
        ];
    }

    /**
     * Đăng nhập người dùng bằng tài khoản Google
     * @param array $googleUser Thông tin người dùng Google
     * @return bool Trạng thái đăng nhập
     */
    public function loginWithGoogle(array $googleUser): bool
    {
        if (empty($googleUser['email'])) {
            return false;
        }

        $accountModel = new \App\Models\Account\AccountModel();
        $account = $accountModel->where('Email', $googleUser['email'])->first();
        
        if ($account === null) {
            return false;
        }
        
        if (!$account->status) {
            return false;
        }
        
        $this->logInUser($account);
        return true;
    }
    
    /**
     * Tạo người dùng mới từ tài khoản Google
     * @param array $googleUser Thông tin người dùng Google
     * @return bool Trạng thái tạo người dùng
     */
    private function createUserFromGoogle(array $googleUser): bool
    {
        $userModel = new \App\Models\UserModel();
        
        $userData = [
            'u_username' => explode('@', $googleUser['email'])[0],
            'u_email' => $googleUser['email'],
            'u_FullName' => $googleUser['name'],
            'u_status' => 1,
            'u_google_id' => $googleUser['id']
        ];
        
        $userId = $userModel->insert($userData);
        
        if ($userId) {
            $user = $userModel->find($userId);
            $this->logInUser($user);
            return true;
        }
        
        return false;
    }
    
    /**
     * Đăng nhập người dùng
     * @param object $user Đối tượng người dùng
     */
    private function logInUser(object $user): void
    {
        $session = session();
        $session->regenerate();
        $session->set('user_id', $user->u_id);
    }
} 