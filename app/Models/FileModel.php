<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Models;

use App\Entities\File;
use CodeIgniter\Model;

class FileModel extends Model {

	protected $table = 'files';
	protected $primaryKey = 'f_id';

	protected $useAutoIncrement = TRUE;

	protected $returnType = File::class;

	protected $allowedFields = ['f_id', 'f_name', 'f_created_at', 'f_filepath', 'f_order', 'f_SizeByUnit', 'briefcase_id'];

	protected $useTimestamps = TRUE;

	protected $createdField = 'f_created_at';
	protected $updatedField = 'f_updated_at';

	protected $beforeInsert = [];
	protected $afterInsert = [];

	protected $beforeUpdate = [];

	public function getFileByBriefcaseId($briefcase_id)
	{
		return $this->where('briefcase_id', $briefcase_id)->findAll();
	}

	public function deleteFile($briefcase_id)
	{
		return $this->where('f_id', $briefcase_id)->delete();
	}

	public function getFileAPI($f_id)
	{
		return $this->where('f_id', $f_id)->first();
	}
}
