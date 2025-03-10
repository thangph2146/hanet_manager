<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Models;

use App\Entities\BriefCase;
use CodeIgniter\Model;

class BriefCaseModel extends Model {

	protected $table = 'briefcase';
	protected $primaryKey = 'bc_id';

	protected $useAutoIncrement = TRUE;

	protected $returnType = BriefCase::class;

	protected $allowedFields = ['bc_id', 'bc_name', 'bc_created_at', 'bc_updated_at', 'bc_deleted_at', 'bc_status'];

	protected $useTimestamps = TRUE;

	protected $validationRules = [
		'bc_name'        	=> 'required|trim|min_length[1]|max_length[255]',
	];

	protected $createdField = 'bc_created_at';
	protected $updatedField = 'bc_updated_at';
	protected $deletedField = 'bc_deleted_at';

	protected $beforeInsert = ['removeSpaces'];
	protected $afterInsert = [];

	protected $beforeUpdate = ['removeSpaces'];

	public function removeSpaces(array $data)
	{
		if (isset($data['data']['bc_name']))
			$data['data']['bc_name'] = remove_spaces($data['data']['bc_name']);
		return $data;
	}

	public function findBriefCase($id)
	{
		return $this->where('bc_id', $id)->first();
	}
}
