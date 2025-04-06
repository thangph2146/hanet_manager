<?php
/**
 * 9/23/2022
 * AUTHOR:PDV-PC
 */

namespace App\Libraries;

class Authentication {

	private $user;

	public function login($u_email, $password)
	{
		$model = new \App\Models\UserModel();

		$user = $model->findByEmail($u_email);

		if ($user === null) {

			return false;

		}

		if ( ! $user->verifyPassword($password)) {

			return false;

		}

		if ( ! $user->u_status) {

			return false;

		}

		$this->logInUser($user);

		return true;
	}

	private function logInUser($user)
	{
		$session = session();
		$session->regenerate();
		$session->set('user_id', $user->u_id);
	}

	private function getUserFromSession()
	{
		if ( ! session()->has('user_id')) {

			return null;

		}

		$model = new \App\Models\UserModel;

		$user = $model->findRolePermissionUserByID(session()->get('user_id'));

		if ($user) {

			return $user;
		}
	}

	public function getCurrentUser()
	{
		if ($this->user === null) {

			$this->user = $this->getUserFromSession();
		}

		return $this->user;
	}

	public function has_role($role)
	{
		return in_array($role, array_column($this->getCurrentUser(), 'r_name'));
	}

	public function has_permission($permission)
	{
		$user = $this->getCurrentUser();
		if ($user === null) {
			return false;
		}
		return in_array($permission, array_column($user, 'p_name'));
	}

	public function getFullName()
	{
		$user = $this->getCurrentUser();
		if ($user === null) {
			return '';
		}
		
		// Kiểm tra nếu $user là một mảng
		if (is_array($user)) {
			// Nếu là mảng, tìm kết quả đầu tiên
			if (!empty($user)) {
				$firstUser = $user[0] ?? null;
				
				// Kiểm tra nếu các trường LastName, MiddleName, FirstName trong mảng
				if (isset($firstUser) && is_object($firstUser)) {
					$nameParts = [];
					
					if (!empty($firstUser->LastName)) {
						$nameParts[] = $firstUser->LastName;
					}
					
					if (!empty($firstUser->MiddleName)) {
						$nameParts[] = $firstUser->MiddleName;
					}
					
					if (!empty($firstUser->FirstName)) {
						$nameParts[] = $firstUser->FirstName;
					}
					
					if (!empty($nameParts)) {
						return implode(' ', $nameParts);
					}
					
					// Nếu không có các trường tên, thử dùng FullName
					if (!empty($firstUser->FullName)) {
						return $firstUser->FullName;
					}
					
					return $firstUser->name ?? '';
				}
				
				// Nếu không thể lấy thông tin từ đối tượng, thử tìm trong mảng
				// Kiểm tra nếu có các key tương ứng trong mảng
				if (isset($firstUser['LastName']) || isset($firstUser['MiddleName']) || isset($firstUser['FirstName'])) {
					$nameParts = [];
					
					if (!empty($firstUser['LastName'])) {
						$nameParts[] = $firstUser['LastName'];
					}
					
					if (!empty($firstUser['MiddleName'])) {
						$nameParts[] = $firstUser['MiddleName'];
					}
					
					if (!empty($firstUser['FirstName'])) {
						$nameParts[] = $firstUser['FirstName'];
					}
					
					if (!empty($nameParts)) {
						return implode(' ', $nameParts);
					}
				}
				
				// Nếu không có các trường tên, thử dùng FullName hoặc u_FullName
				if (!empty($firstUser['FullName'])) {
					return $firstUser['FullName'];
				}
				
				if (!empty($firstUser['u_FullName'])) {
					return $firstUser['u_FullName'];
				}
				
				return $firstUser['name'] ?? $firstUser['u_name'] ?? '';
			}
			
			return '';
		}
		
		// Xử lý khi $user là một đối tượng
		if (is_object($user)) {
			// Ưu tiên sử dụng các trường LastName, MiddleName và FirstName nếu có
			if (property_exists($user, 'LastName') || property_exists($user, 'MiddleName') || property_exists($user, 'FirstName')) {
				$nameParts = [];
				
				if (!empty($user->LastName)) {
					$nameParts[] = $user->LastName;
				}
				
				if (!empty($user->MiddleName)) {
					$nameParts[] = $user->MiddleName;
				}
				
				if (!empty($user->FirstName)) {
					$nameParts[] = $user->FirstName;
				}
				
				if (!empty($nameParts)) {
					return implode(' ', $nameParts);
				}
			}
			
			// Nếu không có các trường mới hoặc chúng đều trống, sử dụng FullName
			if (property_exists($user, 'FullName') && !empty($user->FullName)) {
				return $user->FullName;
			}
			
			return $user->name ?? '';
		}
		
		// Trường hợp không phải mảng cũng không phải đối tượng
		return '';
	}

	public function getLastName()
	{
		$user = $this->getCurrentUser();
		if ($user === null) {
			return '';
		}
		
		// Kiểm tra nếu $user là một mảng
		if (is_array($user)) {
			if (!empty($user)) {
				$firstUser = $user[0] ?? null;
				
				// Kiểm tra nếu $firstUser là đối tượng
				if (isset($firstUser) && is_object($firstUser)) {
					return $firstUser->LastName ?? '';
				}
				
				// Nếu $firstUser là mảng
				return $firstUser['LastName'] ?? '';
			}
			return '';
		}
		
		// Trường hợp $user là đối tượng
		if (is_object($user)) {
			return $user->LastName ?? '';
		}
		
		return '';
	}

	public function getMiddleName()
	{
		$user = $this->getCurrentUser();
		if ($user === null) {
			return '';
		}
		
		// Kiểm tra nếu $user là một mảng
		if (is_array($user)) {
			if (!empty($user)) {
				$firstUser = $user[0] ?? null;
				
				// Kiểm tra nếu $firstUser là đối tượng
				if (isset($firstUser) && is_object($firstUser)) {
					return $firstUser->MiddleName ?? '';
				}
				
				// Nếu $firstUser là mảng
				return $firstUser['MiddleName'] ?? '';
			}
			return '';
		}
		
		// Trường hợp $user là đối tượng
		if (is_object($user)) {
			return $user->MiddleName ?? '';
		}
		
		return '';
	}

	public function getFirstName()
	{
		$user = $this->getCurrentUser();
		if ($user === null) {
			return '';
		}
		
		// Kiểm tra nếu $user là một mảng
		if (is_array($user)) {
			if (!empty($user)) {
				$firstUser = $user[0] ?? null;
				
				// Kiểm tra nếu $firstUser là đối tượng
				if (isset($firstUser) && is_object($firstUser)) {
					return $firstUser->FirstName ?? '';
				}
				
				// Nếu $firstUser là mảng
				return $firstUser['FirstName'] ?? '';
			}
			return '';
		}
		
		// Trường hợp $user là đối tượng
		if (is_object($user)) {
			return $user->FirstName ?? '';
		}
		
		return '';
	}

	public function getFullRole()
	{
		$user = $this->getCurrentUser();
		if ($user === null) {
			return '';
		}
		$fullRole = array_unique(array_column($user, 'r_name'));
		$role = '';
		foreach ($fullRole as $item)
		{
			$role .= ' | ' . $item;
		}
		return $role;
	}

	public function logout()
	{
		session()->destroy();
	}

	public function isLoggedInUser()
	{
		return $this->getCurrentUser() !== null;
	}

}
