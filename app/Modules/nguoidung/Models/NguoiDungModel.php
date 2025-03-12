<?php

namespace App\Modules\nguoidung\Models;

use CodeIgniter\Model;

class NguoiDungModel extends Model
{
    protected $table = 'nguoi_dung';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = true;
    
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

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'AccountId' => 'required|min_length[3]|is_unique[nguoi_dung.AccountId,id,{id}]',
        'FullName'  => 'required|min_length[3]',
        'Email'     => 'permit_empty|valid_email|is_unique[nguoi_dung.Email,id,{id}]'
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

    // Lấy tất cả người dùng
    public function getAll()
    {
        return $this->where('deleted_at', null)
                   ->findAll();
    }

    // Lấy người dùng theo ID
    public function getById($id)
    {
        return $this->find($id);
    }

    // Thêm người dùng mới
    public function insertRecord($data)
    {
        return $this->insert($data);
    }

    // Cập nhật người dùng
    public function updateRecord($id, $data)
    {
        return $this->update($id, $data);
    }

    // Xóa mềm nhiều người dùng
    public function softDeleteMultiple($ids)
    {
        if (!is_array($ids)) {
            return false;
        }
        return $this->delete($ids);
    }

    // Khôi phục nhiều người dùng
    public function restoreMultipleRecords($ids)
    {
        if (!is_array($ids)) {
            return false;
        }
        return $this->update($ids, ['deleted_at' => null]);
    }

    // Xóa vĩnh viễn nhiều người dùng
    public function forceDeleteMultiple($ids)
    {
        if (!is_array($ids)) {
            return false;
        }
        return $this->delete($ids, true);
    }
}
