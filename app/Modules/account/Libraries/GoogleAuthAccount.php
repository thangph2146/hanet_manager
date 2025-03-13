<?php

namespace App\Modules\account\Libraries;

use App\Libraries\GoogleAuthentication;
use App\Modules\account\Models\AccountModel;
use App\Modules\account\Entities\AccountEntity;

class GoogleAuthAccount extends GoogleAuthentication
{
    protected $accountModel;

    public function __construct()
    {
        // Gọi constructor của lớp cha để khởi tạo các thuộc tính Google OAuth
        parent::__construct();
        
        // Khởi tạo model và helper cho module account
        $this->accountModel = new AccountModel();
        helper('App\Modules\account\Helpers\session');
    }

    /**
     * Đăng nhập bằng Google
     * Override phương thức này để sử dụng bảng nguoi_dung thay vì users
     *
     * @param array $googleUser
     * @return boolean
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
        
        // Đăng nhập người dùng bằng helper session của @account
        account_session_set('user_id', $user->id);
        
        return true;
    }
} 