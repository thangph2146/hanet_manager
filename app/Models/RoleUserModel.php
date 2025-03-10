<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Models;

use CodeIgniter\Model;

class RoleUserModel extends Model {

	protected $table = 'roles_users';
	protected $primaryKey = 'ru_id';
	protected $allowedFields = ['user_id', 'role_id'];
	protected $validationRules = [
		'role_id'     		=> 'required',
		'user_id'     => 'required',
	];

	public function getInnerJoinRolesUser($id)
	{
		return $this->join('roles', 'roles_users.role_id = roles.r_id', 'inner')
				 ->orderBy('r_name', 'asc')
				 ->where('roles_users.user_id', $id)
				 ->Where('roles.r_status', 1)
				 ->findAll();
	}

}
