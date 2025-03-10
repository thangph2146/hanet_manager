<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Models;

use App\Entities\Role;
use CodeIgniter\Model;

class RoleModel extends Model {

	protected $table = 'roles';
	protected $primaryKey = 'r_id';

	protected $useAutoIncrement = TRUE;

	protected $returnType = Role::class;
	protected $useSoftDeletes = TRUE;

	protected $allowedFields = ['r_name', 'r_description', 'r_status'];

	protected $useTimestamps = TRUE;

	protected $validationRules = [
		'r_name'         => 'required|max_length[128]|is_unique[roles.r_name]'
	];

	protected $createdField = 'r_created_at';
	protected $updatedField = 'r_updated_at';
	protected $deletedField = 'r_deleted_at';

	protected $beforeInsert = ['removeSpaces'];

	protected $beforeUpdate = ['removeSpaces'];

	public function removeSpaces(array $data)
	{
		if (isset($data['data']['r_name']))
			$data['data']['r_name'] = remove_spaces($data['data']['r_name']);

		if (isset($data['data']['r_description']))
			$data['data']['r_description'] = remove_spaces($data['data']['r_description']);

		return $data;
	}

	public function getAllRoles($r_status = null)
	{
		return $r_status ? $this->where('r_status', 1)->orderBy('r_name')->findAll() :
			$this->orderBy('r_name', 'ASC')->findAll();
	}

	public function getAllRolesDeleted()
	{
		return $this->onlyDeleted()->orderBy('r_name', 'ASC')->findAll();
	}

	public function findRole($id)
	{
		return $this->where('r_id', $id)->first();
	}

	public function findRoleDeleted($id)
	{
		return $this->onlyDeleted()->where('r_id', $id)->first();
	}

	public function getInnerJoinPermissions($id)
	{
		$role_permissions = new \App\Models\PermissionRoleModel();
		return $role_permissions->getInnerJoinPermissionsRole($id);
	}
}
