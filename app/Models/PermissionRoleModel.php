<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Models;

use App\Entities\Permission;
use CodeIgniter\Model;

class PermissionRoleModel extends Model {

	protected $table = 'permission_roles';
	protected $primaryKey = 'pr_id';
	protected $allowedFields = ['role_id', 'permission_id'];
	protected $validationRules = [
		'role_id'     		=> 'required',
		'permission_id'     => 'required',
	];

	public function getInnerJoinPermissionsRole($id)
	{
		return is_array($id) ?
			$this->join('permissions', 'permission_roles.permission_id = permissions.p_id', 'inner')
					->orderBy('p_name', 'desc')
					->whereIn('permission_roles.role_id', $id)
					->Where('permissions.p_status', 1)
					->findAll()
			:
			$this->join('permissions', 'permission_roles.permission_id = permissions.p_id', 'inner')
				 ->orderBy('p_name', 'desc')
				 ->where('permission_roles.role_id', $id)
				 ->Where('permissions.p_status', 1)
				 ->findAll();
	}
}
