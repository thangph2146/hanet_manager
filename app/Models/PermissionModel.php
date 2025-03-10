<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Models;

use App\Entities\Permission;
use CodeIgniter\Model;

class PermissionModel extends Model {

	protected $table = 'permissions';
	protected $primaryKey = 'p_id';

	protected $useAutoIncrement = TRUE;

	protected $returnType = Permission::class;
	protected $useSoftDeletes = TRUE;

	protected $allowedFields = ['p_name', 'p_display_name', 'p_description', 'p_status'];

	protected $useTimestamps = TRUE;

	protected $validationRules = [
		'p_name'         => 'required|max_length[128]|is_unique[permissions.p_name]',
		'p_display_name' => 'required|max_length[128]'
	];

	protected $createdField = 'p_created_at';
	protected $updatedField = 'p_updated_at';
	protected $deletedField = 'p_deleted_at';

	protected $beforeInsert = ['removeSpaces'];

	protected $beforeUpdate = ['removeSpaces'];

	public function removeSpaces(array $data)
	{
		if (isset($data['data']['p_name']))
			$data['data']['p_name'] = remove_spaces($data['data']['p_name']);

		if (isset($data['data']['p_display_name']))
			$data['data']['p_display_name'] = remove_spaces($data['data']['p_display_name']);

		if (isset($data['data']['p_description']))
			$data['data']['p_description'] = remove_spaces($data['data']['p_description']);

		return $data;
	}

	public function getAllPermissions($status = null)
	{
		return $status ?
			$this->where('p_status ', 1)->orderBy('p_name', 'ASC')->findAll()
			:
			$this->orderBy('p_name', 'ASC')->findAll();
	}

	public function getAllPermissionsDeleted()
	{
		return $this->onlyDeleted()->orderBy('p_name', 'ASC')->findAll();
	}

	public function findPermission($id)
	{
		return $this->where('p_id', $id)->first();
	}

	public function findPermissionDeleted($id)
	{
		return $this->onlyDeleted()->where('p_id', $id)->first();
	}
}
