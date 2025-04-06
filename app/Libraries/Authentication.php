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

	public function getLastName()
	{
		$user = $this->getCurrentUser();
		if ($user === null) {
			return '';
		}
		
		return $user->LastName ?? '';
	}

	public function getMiddleName()
	{
		$user = $this->getCurrentUser();
		if ($user === null) {
			return '';
		}
		
		return $user->MiddleName ?? '';
	}

	public function getFirstName()
	{
		$user = $this->getCurrentUser();
		if ($user === null) {
			return '';
		}
		
		return $user->FirstName ?? '';
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
