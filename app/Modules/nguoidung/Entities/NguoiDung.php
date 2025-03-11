<?php

namespace App\Modules\nguoidung\Entities;

use App\Entities\BaseEntity;

class NguoiDung extends BaseEntity
{
    protected $datamap = [
        'account_id' => 'AccountId',
        'account_type' => 'AccountType',
        'ho_ten' => 'FullName',
        'ho' => 'FirstName',
        'so_dien_thoai' => 'MobilePhone',
        'dien_thoai_nha' => 'HomePhone',
        'dien_thoai_nha1' => 'HomePhone1',
        'mat_khau' => 'PW',
        'trang_thai' => 'status',
    ];
    
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id' => 'integer',
        'u_id' => 'integer',
        'loai_nguoi_dung_id' => 'integer',
        'nam_hoc_id' => 'integer',
        'bac_hoc_id' => 'integer',
        'he_dao_tao_id' => 'integer',
        'nganh_id' => 'integer',
        'phong_khoa_id' => 'integer',
        'status' => 'boolean',
        'bin' => 'boolean',
    ];
    
    protected $tableName = 'nguoi_dung';
    
    // Các thuộc tính có thể truy cập
    protected $attributes = [
        'id' => null,
        'AccountId' => null,
        'u_id' => null,
        'FirstName' => null,
        'AccountType' => null,
        'FullName' => null,
        'MobilePhone' => null,
        'Email' => null,
        'HomePhone1' => null,
        'PW' => null,
        'HomePhone' => null,
        'loai_nguoi_dung_id' => null,
        'mat_khau_local' => null,
        'nam_hoc_id' => null,
        'bac_hoc_id' => null,
        'he_dao_tao_id' => null,
        'nganh_id' => null,
        'phong_khoa_id' => null,
        'status' => 1,
        'bin' => 0,
        'created_at' => null,
        'updated_at' => null,
        'deleted_at' => null,
    ];
    
    // Các quy tắc xác thực bổ sung
    protected $validationRules = [
        'AccountId' => 'required|min_length[3]|max_length[50]',
        'AccountType' => 'required|min_length[1]|max_length[20]',
        'FullName' => 'required|min_length[3]|max_length[100]',
        'Email' => 'required|valid_email',
        'MobilePhone' => 'required|min_length[10]|max_length[20]',
        'PW' => 'required|min_length[6]',
        'status' => 'in_list[0,1]',
    ];
    
    // Thông báo lỗi tùy chỉnh
    protected $validationMessages = [
        'AccountId' => [
            'required' => 'Mã tài khoản không được để trống',
            'min_length' => 'Mã tài khoản phải có ít nhất {param} ký tự',
            'max_length' => 'Mã tài khoản không được vượt quá {param} ký tự'
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
            'valid_email' => 'Email không đúng định dạng'
        ],
        'MobilePhone' => [
            'required' => 'Số điện thoại không được để trống',
            'min_length' => 'Số điện thoại phải có ít nhất {param} ký tự',
            'max_length' => 'Số điện thoại không được vượt quá {param} ký tự'
        ],
        'PW' => [
            'required' => 'Mật khẩu không được để trống',
            'min_length' => 'Mật khẩu phải có ít nhất {param} ký tự'
        ],
        'status' => [
            'in_list' => 'Trạng thái phải là 0 hoặc 1'
        ]
    ];
    
    /**
     * Kiểm tra mật khẩu
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->attributes['PW']);
    }
    
    /**
     * Kiểm tra mật khẩu local
     */
    public function verifyLocalPassword(string $password): bool
    {
        return password_verify($password, $this->attributes['mat_khau_local']);
    }
    
    /**
     * Kiểm tra xem người dùng có đang hoạt động không
     */
    public function isActive(): bool
    {
        return (bool) $this->attributes['status'];
    }
    
    /**
     * Lấy trạng thái người dùng dưới dạng văn bản
     */
    public function getStatusText(): string
    {
        return $this->attributes['status'] ? 'Đang hoạt động' : 'Không hoạt động';
    }
    
    /**
     * Lấy loại tài khoản dưới dạng văn bản
     */
    public function getAccountTypeText(): string
    {
        switch ($this->attributes['AccountType']) {
            case 'admin':
                return 'Quản trị viên';
            case 'giangvien':
                return 'Giảng viên';
            case 'sinhvien':
                return 'Sinh viên';
            case 'nhanvien':
                return 'Nhân viên';
            default:
                return $this->attributes['AccountType'];
        }
    }
} 