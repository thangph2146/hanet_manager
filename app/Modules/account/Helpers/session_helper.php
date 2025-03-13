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
     * Lưu giá trị vào session
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
     * Kiểm tra key có tồn tại trong session
     *
     * @param string $key
     * @return bool
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

if (!function_exists('account_session_destroy')) {
    /**
     * Xóa toàn bộ session
     *
     * @return void
     */
    function account_session_destroy()
    {
        account_session()->destroy();
    }
}

if (!function_exists('account_is_logged_in')) {
    /**
     * Kiểm tra người dùng đã đăng nhập chưa
     *
     * @return bool
     */
    function account_is_logged_in()
    {
        return account_session_has('user_id');
    }
}

if (!function_exists('account_logout')) {
    /**
     * Đăng xuất người dùng
     *
     * @return void
     */
    function account_logout()
    {
        account_session_remove('logged_user');
        account_session()->destroy();
    }
}

if (!function_exists('account_set_remember_cookie')) {
    /**
     * Lưu cookie nhớ đăng nhập
     *
     * @param int $user_id
     * @param string $token
     * @return void
     */
    function account_set_remember_cookie($user_id, $token)
    {
        // Load helper cookie
        helper('cookie');
        
        $cookie = [
            'name'   => 'account_remember',
            'value'  => $user_id . ':' . $token,
            'expire' => 30 * 24 * 60 * 60, // 30 ngày
            'secure' => true
        ];

        set_cookie($cookie);
    }
}

if (!function_exists('account_get_remember_cookie')) {
    /**
     * Lấy thông tin từ cookie nhớ đăng nhập
     *
     * @return array|null
     */
    function account_get_remember_cookie()
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
    function account_clear_remember_cookie()
    {
        // Load helper cookie
        helper('cookie');
        
        delete_cookie('account_remember');
    }
} 