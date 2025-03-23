<?php
/**
 * 9/16/2022
 * AUTHOR:PDV-PC
 */

namespace App\Models;

use App\Entities\Student;
use CodeIgniter\Model;

class StudentModel extends Model {

	protected $table = 'nguoi_dung';
	protected $primaryKey = 'nguoi_dung_id';

	protected $returnType = Student::class;

	protected $allowedFields = [
        'AccountId', 
        'FullName', 
        'Email', 
        'PW',
        'FirstName',
        'AccountType',
        'MobilePhone',
        'HomePhone1',
        'HomePhone',
        'loai_nguoi_dung_id',
        'mat_khau_local',
        'nam_hoc_id',
        'bac_hoc_id',
        'he_dao_tao_id',
        'nganh_id',
        'phong_khoa_id',
        'status',
        'bin'
    ];

	protected $useTimestamps = TRUE;

 	// Validation
    protected $validationRules = [
        'AccountId' => 'required|min_length[3]|is_unique[students.AccountId,id,{id}]',
        'FullName'  => 'required|min_length[3]',
        'Email'     => 'permit_empty|valid_email|is_unique[students.Email,id,{id}]'
    ];

    protected $validationMessages = [
        'AccountId' => [
            'required' => 'Account ID là bắt buộc',
            'min_length' => 'Account ID phải có ít nhất 3 ký tự',
            'is_unique' => 'Account ID đã tồn tại'
        ],
        'FullName' => [
            'required' => 'Họ và tên là bắt buộc',
            'min_length' => 'Họ và tên phải có ít nhất 3 ký tự'
        ],
        'Email' => [
            'valid_email' => 'Email không hợp lệ',
            'is_unique' => 'Email đã tồn tại'
        ]
    ];

    protected $skipValidation = false;

	protected $updatedField = 'updated_at';

	protected $beforeInsert = ['removeSpaces'];

	protected $beforeUpdate = ['removeSpaces'];

	public function removeSpaces(array $data)
	{
		if (isset($data['data']['FullName']))
			$data['data']['FullName'] = remove_spaces($data['data']['FullName']);

		if (isset($data['data']['FirstName']))
			$data['data']['FirstName'] = remove_spaces($data['data']['FirstName']);
		return $data;
	}

	public function getAllStudents()
	{
		return $this->orderBy('FirstName', 'ASC')->findAll();
	}


	public function findStudentByID($id)
	{
		return $this->find($id);
	}
}
