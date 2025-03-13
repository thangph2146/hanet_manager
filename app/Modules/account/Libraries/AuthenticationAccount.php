<?php
/**
 * 9/23/2022
 * AUTHOR:PDV-PC
 */

namespace App\Modules\account\Libraries;

use App\Modules\account\Models\AccountModel;
use App\Modules\account\Entities\AccountEntity;

class AuthenticationAccount {

	/**
	 * @var AccountEntity|null
	 */
	private $user;

	/**
	 * Xác thực người dùng
	 *
	 * @param string $email
	 * @param string $password
	 * @return boolean
	 */
	public function login($email, $password)
	{
		$model = new AccountModel();

		// Sử dụng phương thức authenticate từ model
		$user = $model->authenticate($email, $password);

		if ($user === null) {
			return false;
		}

		$this->logInUser($user);

		return true;
	}

	/**
	 * Lưu thông tin người dùng vào session
	 *
	 * @param AccountEntity $user
	 * @return void
	 */
	private function logInUser($user)
	{
		// Tải helper session
		helper('App\Modules\account\Helpers\session');
		
		// Khởi tạo session
		session()->start();
		
		// Sử dụng session tùy chỉnh thay vì session mặc định
		account_session()->regenerate();
		
		// Lưu thông tin người dùng vào session
		$userData = [
            'id' => $user->id,
            'email' => $user->Email,
            'fullname' => $user->FullName,
            'loai_nguoi_dung_id' => $user->loai_nguoi_dung_id,
            'logged_in' => true,
        ];
		
		account_session_set('logged_user', $userData);
	}

	/**
	 * Lấy thông tin người dùng từ session
	 *
	 * @return AccountEntity|null
	 */
	private function getUserFromSession()
	{
		// Tải helper session
		helper('App\Modules\account\Helpers\session');
		
		// Kiểm tra xem người dùng đã đăng nhập chưa
		if (!account_session_has('logged_user')) {
			return null;
		}

		$model = new AccountModel();
		$userData = account_session_get('logged_user');
		
		// Tìm người dùng theo ID
		$user = $model->find($userData['id']);

		if ($user) {
			return $user;
		}
		
		return null;
	}

	/**
	 * Lấy thông tin người dùng hiện tại
	 *
	 * @return AccountEntity|null
	 */
	public function getCurrentUser()
	{
		if ($this->user === null) {
			$this->user = $this->getUserFromSession();
		}

		return $this->user;
	}

	/**
	 * Lấy tên đầy đủ của người dùng
	 *
	 * @return string|null
	 */
	public function getFullName()
	{
		$user = $this->getCurrentUser();
		if ($user) {
			return $user->getFullName();
		}
		return null;
	}

	/**
	 * Lấy email của người dùng
	 *
	 * @return string|null
	 */
	public function getEmail()
	{
		$user = $this->getCurrentUser();
		if ($user) {
			return $user->getEmail();
		}
		return null;
	}

	/**
	 * Lấy loại người dùng
	 *
	 * @return integer|null
	 */
	public function getLoaiNguoiDung()
	{
		$user = $this->getCurrentUser();
		if ($user) {
			return $user->getLoaiNguoiDung();
		}
		return null;
	}

	/**
	 * Đăng xuất người dùng
	 *
	 * @return void
	 */
	public function logout()
	{
		// Tải helper session
		helper('App\Modules\account\Helpers\session');
		
		// Sử dụng hàm đăng xuất từ helper
		\account_logout();
	}

	/**
	 * Kiểm tra xem người dùng đã đăng nhập chưa
	 *
	 * @return boolean
	 */
	public function isLoggedInUser()
	{
		// Tải helper session
		helper('App\Modules\account\Helpers\session');
		
		// Sử dụng hàm kiểm tra đăng nhập từ helper
		return account_is_logged_in();
	}
}
