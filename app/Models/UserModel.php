<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Models;

use App\Entities\User;
use CodeIgniter\Model;

class UserModel extends Model {

	protected $table = 'users';
	protected $primaryKey = 'u_id';

	protected $useAutoIncrement = TRUE;

	protected $returnType = User::class;
	protected $useSoftDeletes = TRUE;

	protected $allowedFields = ['u_LastName', 'u_MiddleName', 'u_FirstName',
		'u_username', 'u_password_hash', 'u_status'];

	protected $useTimestamps = TRUE;

	protected $validationRules = [
		'u_username'        	=> 'required|trim|min_length[1]|max_length[50]|is_unique[users.u_username]',
		'password'				=> 'required|min_length[6]',
		'password_confirmation' => 'required|matches[password]',
	];

	protected $createdField = 'u_created_at';
	protected $updatedField = 'u_updated_at';
	protected $deletedField = 'u_deleted_at';

	protected $beforeInsert = ['removeSpaces', 'hashPassword'];

	protected $beforeUpdate = ['removeSpaces', 'hashPassword'];

	public function removeSpaces(array $data)
	{
		if (isset($data['data']['u_LastName']))
			$data['data']['u_LastName'] = remove_spaces($data['data']['u_LastName']);

		if (isset($data['data']['u_MiddleName']))
			$data['data']['u_MiddleName'] = remove_spaces($data['data']['u_MiddleName']);

		if (isset($data['data']['u_FirstName']))
			$data['data']['u_FirstName'] = remove_spaces($data['data']['u_FirstName']);
		return $data;
	}

	protected function hashPassword(array $data)
	{
		if (isset($data['data']['password'])) {

			$data['data']['u_password_hash'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);

			unset($data['data']['password']);
			unset($data['data']['password_confirmation']);
		}

		return $data;
	}

	public function disablePasswordValidation()
	{
		unset($this->validationRules['password']);
		unset($this->validationRules['password_confirmation']);
	}

	public function getAllUsers()
	{
		return $this->select("*, CONCAT(u_LastName, ' ', u_MiddleName, ' ', u_FirstName) AS u_FullName")->orderBy('u_FirstName', 'ASC')->findAll();
	}

	public function getAllUsersDeleted()
	{
		return $this->onlyDeleted()->orderBy('u_FirstName', 'ASC')->findAll();
	}

	public function findUser($id)
	{
		return $this->where('u_id', $id)->first();
	}

	public function findUserDeleted($id)
	{
		return $this->onlyDeleted()->where('u_id', $id)->first();
	}

	public function getInnerJoinRoles($id)
	{
		$User_roles = new \App\Models\RoleUserModel();
		return $User_roles->getInnerJoinRolesUser($id);
	}

	public function findByUserName($username)
	{
		return $this->where('u_username', $username)->first();
	}

	public function findRolePermissionUserByID($id)
    {
		return $this->select("*, CONCAT(u_LastName, ' ', u_MiddleName, ' ', u_FirstName) AS u_FullName")
					->join('roles_users', 'roles_users.user_id = users.u_id', 'inner')
					->join('roles', 'roles.r_id = roles_users.role_id', 'inner')
					->join('permission_roles', 'permission_roles.role_id = roles_users.role_id', 'inner')
					->join('permissions', 'permissions.p_id = permission_roles.permission_id', 'inner')
					->where(['users.u_id' => $id, 'permissions.p_status' => 1])
					->findAll();
	}
}
