<?php
/**
 * Helper session cho module nguoidung
 */

if (!function_exists('account_session')) {
    /**
     * Lấy đối tượng session
     *
     * @return \CodeIgniter\Session\Session
     */
    function account_session()
    {
        return session();
    }
}

    if (!function_exists('account_session_set')) {
    /**
     * Đặt giá trị vào session
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    function account_session_set($key, $value)
    {
        account_session()->set($key, $value);
    }
}

if (!function_exists('account_session_get')) {
    /**
     * Lấy giá trị từ session
     *
     * @param string $key
     * @return mixed
     */
    function account_session_get($key)
    {
        return account_session()->get($key);
    }
}

if (!function_exists('account_session_has')) {
    /**
     * Kiểm tra xem session có chứa key không
     *
     * @param string $key
     * @return boolean
     */
    function account_session_has($key)
    {
        return account_session()->has($key);
    }
}

if (!function_exists('account_session_remove')) {
    /**
     * Xóa giá trị khỏi session
     *
     * @param string $key
     * @return void
     */
    function account_session_remove($key)
    {
        account_session()->remove($key);
    }
}

if (!function_exists('account_is_logged_in')) {
    /**
     * Kiểm tra xem người dùng đã đăng nhập chưa
     *
     * @return boolean
     */
    function account_is_logged_in()
    {
        $userData = account_session_get('logged_user');
        return !empty($userData) && isset($userData['logged_in']) && $userData['logged_in'] === true;
    }
}

if (!function_exists('account_logout')) {
    /**
     * Đăng xuất người dùng
     *
     * @return void
     */
    function nguoidung_logout()
    {
        account_session_remove('logged_user');
        account_session()->destroy();
    }
}

if (!function_exists('account_set_remember_cookie')) {
    /**
     * Đặt cookie nhớ đăng nhập
     *
     * @param int $userId
     * @param string $token
     * @return void
     */
    function account_set_remember_cookie($userId, $token)
    {
        $expiration = time() + (86400 * 30); // 30 ngày
        set_cookie('account_remember', $userId . ':' . $token, $expiration);
    }
}

if (!function_exists('account_get_remember_cookie')) {
    /**
     * Lấy thông tin từ cookie nhớ đăng nhập
     *
     * @return array|null
     */
    function nguoidung_get_remember_cookie()
    {
        $cookie = get_cookie('account_remember');
        
        if (empty($cookie)) {
            return null;
        }
        
        $parts = explode(':', $cookie);
        
        if (count($parts) !== 2) {
            return null;
        }
        
        return [
            'user_id' => $parts[0],
            'token' => $parts[1]
        ];
    }
}

if (!function_exists('account_clear_remember_cookie')) {
    /**
     * Xóa cookie nhớ đăng nhập
     *
     * @return void
     */
    function nguoidung_clear_remember_cookie()
    {
        delete_cookie('account_remember');
    }
} 