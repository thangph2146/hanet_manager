<?php

namespace App\Modules\nguoidung\Models;

use CodeIgniter\Model;
use App\Modules\nguoidung\Entities\NguoiDungEntity;

class NguoiDungModel extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    protected $useAutoIncrement = true;
    protected $returnType = NguoiDungEntity::class;
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
        'AccountId' => 'required|min_length[3]|is_unique[students.AccountId,student_id,{student_id}]',
        'FullName'  => 'required|min_length[3]',
        'Email'     => 'permit_empty|valid_email|is_unique[students.Email,student_id,{student_id}]'
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

    /**
     * Lấy tất cả người dùng
     *
     * @return array
     */
    public function getAll()
    {
        return $this->where('deleted_at', null)
                   ->findAll();
    }

    /**
     * Lấy người dùng theo ID
     *
     * @param integer $id
     * @return NguoiDungEntity|null
     */
    public function getById($student_id)
    {
        return $this->find($student_id);
    }

    /**
     * Tìm người dùng theo email
     *
     * @param string $email
     * @return NguoiDungEntity|null
     */
    public function findByEmail($email)
    {
        return $this->where('Email', $email)
                   ->where('deleted_at', null)
                   ->first();
    }

    /**
     * Tìm người dùng theo AccountId
     *
     * @param string $accountId
     * @return NguoiDungEntity|null
     */
    public function findByAccountId($accountId)
    {
        return $this->where('AccountId', $accountId)
                   ->where('deleted_at', null)
                   ->first();
    }

    /**
     * Xác thực người dùng
     *
     * @param string $email
     * @param string $password
     * @return NguoiDungEntity|null
     */
    public function authenticate($email, $password)
    {
        $user = $this->findByEmail($email);
        
        if ($user === null) {
            return null;
        }
        
        if (!$user->verifyPassword($password)) {
            return null;
        }
        
        if (!$user->isActive()) {
            return null;
        }
        
        return $user;
    }

    /**
     * Thêm người dùng mới
     *
     * @param array $data
     * @return integer|false
     */
    public function insertRecord($data)
    {
        return $this->insert($data);
    }

    /**
     * Cập nhật người dùng
     *
     * @param integer $id
     * @param array $data
     * @return boolean
     */
    public function updateRecord($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Xóa mềm nhiều người dùng
     *
     * @param array $ids
     * @return boolean
     */
    public function softDeleteMultiple($ids)
    {
        if (!is_array($ids)) {
            return false;
        }
        return $this->delete($ids);
    }

    /**
     * Khôi phục nhiều người dùng
     *
     * @param array $ids
     * @return boolean
     */
    public function restoreMultipleRecords($ids)
    {
        if (!is_array($ids)) {
            return false;
        }
        return $this->update($ids, ['deleted_at' => null]);
    }

    /**
     * Xóa vĩnh viễn nhiều người dùng
     *
     * @param array $ids
     * @return boolean
     */
    public function forceDeleteMultiple($ids)
    {
        if (!is_array($ids)) {
            return false;
        }
        return $this->delete($ids, true);
    }

    /**
     * Lấy danh sách người dùng theo loại
     *
     * @param integer $loaiNguoiDungId
     * @return array
     */
    public function getByLoaiNguoiDung($loaiNguoiDungId)
    {
        return $this->where('loai_nguoi_dung_id', $loaiNguoiDungId)
                   ->where('deleted_at', null)
                   ->findAll();
    }

    /**
     * Tìm kiếm người dùng
     *
     * @param string $keyword
     * @return array
     */
    public function search($keyword)
    {
        return $this->like('FullName', $keyword)
                   ->orLike('Email', $keyword)
                   ->orLike('AccountId', $keyword)
                   ->where('deleted_at', null)
                   ->findAll();
    }

    /**
     * Đếm số lượng người dùng
     *
     * @return integer
     */
    public function countAll()
    {
        return $this->where('deleted_at', null)
                   ->countAllResults();
    }

    /**
     * Đếm số lượng người dùng theo loại
     *
     * @param integer $loaiNguoiDungId
     * @return integer
     */
    public function countByLoaiNguoiDung($loaiNguoiDungId)
    {
        return $this->where('loai_nguoi_dung_id', $loaiNguoiDungId)
                   ->where('deleted_at', null)
                   ->countAllResults();
    }
}
