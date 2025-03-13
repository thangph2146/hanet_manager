<?php
namespace App\Modules\account\Models;

use CodeIgniter\Model;
use App\Modules\account\Entities\AccountEntity;

class AccountModel extends Model
{
    protected $table = 'nguoi_dung';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = AccountEntity::class;
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

    /**
     * Xác thực người dùng
     *
     * @param string $email
     * @param string $password
     * @return AccountEntity|null
     */
    public function authenticate($email, $password)
    {
        $user = $this->where('Email', $email)
                    ->where('deleted_at', null)
                    ->first();
        
        if ($user === null) {
            return null;
        }
        
        if (!password_verify($password, $user->PW)) {
            return null;
        }
        
        if (!$user->status) {
            return null;
        }
        
        return $user;
    }

    /**
     * Tìm người dùng theo email
     *
     * @param string $email
     * @return AccountEntity|null
     */
    public function findByEmail($email)
    {
        $result = $this->where('Email', $email)
                   ->where('deleted_at', null)
                   ->first();
        print_r($result);die;
        return $result;
    }

    /**
     * Tìm người dùng theo AccountId
     *
     * @param string $accountId
     * @return AccountEntity|null
     */
    public function findByAccountId($accountId)
    {
        $result = $this->where('AccountId', $accountId)
                   ->where('deleted_at', null)
                   ->first();
        print_r($result);die;
        return $result;
    }
}
