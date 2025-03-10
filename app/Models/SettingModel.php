<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Models;

use App\Entities\Setting;
use CodeIgniter\Model;

class SettingModel extends Model {

	protected $table = 'settings';
	protected $primaryKey = 'id';

	protected $useAutoIncrement = TRUE;

	protected $returnType = Setting::class;

	protected $allowedFields = [];

	protected $useTimestamps = TRUE;

	protected $validationRules = [
		'key' => 'required|trim|min_length[1]|max_length[255]|is_unique[settings.key]',
	];

	protected $createdField = 'created_at';
	protected $updatedField = 'updated_at';

	protected $beforeInsert = [];

	protected $beforeUpdate = [];

	public function getAllSettings()
	{
		return $this->orderBy('class', 'ASC')->findAll();
	}

	public function findSetting($id)
	{
		return $this->where('id', $id)->first();
	}

	public function saveSetting($key, $value, $context)
	{
		setting($key, $value, $context);
		return true;
	}

}
