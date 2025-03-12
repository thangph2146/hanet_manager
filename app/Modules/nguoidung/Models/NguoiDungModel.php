<?php

namespace App\Modules\nguoidung\Models;

use App\Models\BaseModel;
use App\Modules\nguoidung\Entities\NguoiDung;

class NguoiDungModel extends BaseModel
{
    protected $table = 'nguoi_dung';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = NguoiDung::class;
    protected $useSoftDeletes = true;
    protected $allowedFields = [
        'AccountId',
        'u_id',
        'FirstName',
        'AccountType',
        'FullName',
        'MobilePhone',
        'Email',
        'HomePhone1',
        'PW',
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
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    protected $validationRules = [
        'AccountId' => 'required|min_length[3]|max_length[50]|is_unique[nguoi_dung.AccountId,id,{id}]',
        'AccountType' => 'required|min_length[1]|max_length[20]',
        'FullName' => 'required|min_length[3]|max_length[100]',
        'Email' => 'required|valid_email|is_unique[nguoi_dung.Email,id,{id}]',
        'MobilePhone' => 'required|min_length[10]|max_length[20]|is_unique[nguoi_dung.MobilePhone,id,{id}]',
        'PW' => 'required|min_length[6]',
    ];
    
    protected $validationMessages = [
        'AccountId' => [
            'required' => 'Mã tài khoản không được để trống',
            'min_length' => 'Mã tài khoản phải có ít nhất {param} ký tự',
            'max_length' => 'Mã tài khoản không được vượt quá {param} ký tự',
            'is_unique' => 'Mã tài khoản đã tồn tại'
        ],
        'AccountType' => [
            'required' => 'Loại tài khoản không được để trống',
            'min_length' => 'Loại tài khoản phải có ít nhất {param} ký tự',
            'max_length' => 'Loại tài khoản không được vượt quá {param} ký tự'
        ],
        'FullName' => [
            'required' => 'Họ tên không được để trống',
            'min_length' => 'Họ tên phải có ít nhất {param} ký tự',
            'max_length' => 'Họ tên không được vượt quá {param} ký tự'
        ],
        'Email' => [
            'required' => 'Email không được để trống',
            'valid_email' => 'Email không đúng định dạng',
            'is_unique' => 'Email đã tồn tại'
        ],
        'MobilePhone' => [
            'required' => 'Số điện thoại không được để trống',
            'min_length' => 'Số điện thoại phải có ít nhất {param} ký tự',
            'max_length' => 'Số điện thoại không được vượt quá {param} ký tự',
            'is_unique' => 'Số điện thoại đã tồn tại'
        ],
        'PW' => [
            'required' => 'Mật khẩu không được để trống',
            'min_length' => 'Mật khẩu phải có ít nhất {param} ký tự'
        ]
    ];
    
    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
    
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['PW'])) {
            $data['data']['PW'] = password_hash($data['data']['PW'], PASSWORD_DEFAULT);
        }
        
        if (isset($data['data']['mat_khau_local'])) {
            $data['data']['mat_khau_local'] = password_hash($data['data']['mat_khau_local'], PASSWORD_DEFAULT);
        }
        
        return $data;
    }
    
    public function disablePasswordValidation()
    {
        unset($this->validationRules['PW']);
    }
    
    public function getAllNguoiDung()
    {
        return $this->where('bin', 0)->orderBy('FullName', 'ASC')->findAll();
    }
    
    public function getAllNguoiDungDeleted()
    {
        return $this->where('bin', 1)->orderBy('FullName', 'ASC')->findAll();
    }
    
    public function findNguoiDung($id)
    {
        return $this->where('id', $id)->first();
    }
    
    public function findByAccountId($accountId)
    {
        return $this->where('AccountId', $accountId)->first();
    }
    
    public function findByEmail($email)
    {
        return $this->where('Email', $email)->first();
    }
    
    public function findByMobilePhone($mobilePhone)
    {
        return $this->where('MobilePhone', $mobilePhone)->first();
    }
    
    public function searchNguoiDung($keyword)
    {
        return $this->like('FullName', $keyword)
                    ->orLike('Email', $keyword)
                    ->orLike('MobilePhone', $keyword)
                    ->orLike('AccountId', $keyword)
                    ->where('bin', 0)
                    ->findAll();
    }
    
    public function getNguoiDungByType($accountType)
    {
        return $this->where('AccountType', $accountType)
                    ->where('bin', 0)
                    ->orderBy('FullName', 'ASC')
                    ->findAll();
    }
    
    public function getNguoiDungByLoai($loaiNguoiDungId)
    {
        return $this->where('loai_nguoi_dung_id', $loaiNguoiDungId)
                    ->where('bin', 0)
                    ->orderBy('FullName', 'ASC')
                    ->findAll();
    }
} 