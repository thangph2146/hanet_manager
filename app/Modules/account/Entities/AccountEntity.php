<?php

namespace App\Modules\account\Entities;

use App\Entities\BaseEntity;

class AccountEntity extends BaseEntity
{
    protected $tableName = 'nguoi_dung';
    protected $datamap = [];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts = [
        'id' => 'integer',
        'AccountId' => 'string',
        'AccountType' => 'string',
        'FullName' => 'string',
        'MobilePhone' => 'string',
        'Email' => 'string',
        'HomePhone1' => 'string',
        'HomePhone' => 'string',
        'loai_nguoi_dung_id' => 'integer',
        'nam_hoc_id' => 'integer',
        'bac_hoc_id' => 'integer',
        'he_dao_tao_id' => 'integer',
        'nganh_id' => 'integer',
        'phong_khoa_id' => 'integer',
        'status' => 'boolean',
        'bin' => 'boolean'
    ];

    /**
     * Đặt mật khẩu và mã hóa
     *
     * @param string $password
     * @return $this
     */
    public function setPW(string $password)
    {
        $this->attributes['PW'] = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    /**
     * Lấy mật khẩu đã mã hóa
     *
     * @return string
     */
    public function getPW()
    {
        return $this->attributes['PW'] ?? '';
    }

    /**
     * Xác minh mật khẩu
     *
     * @param string $password
     * @return boolean
     */
    public function verifyPassword(string $password)
    {
        return password_verify($password, $this->attributes['PW']);
    }

    /**
     * Lấy tên đầy đủ
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->attributes['FullName'] ?? '';
    }

    /**
     * Lấy email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->attributes['Email'] ?? '';
    }

    /**
     * Kiểm tra trạng thái người dùng
     *
     * @return boolean
     */
    public function isActive()
    {
        return (bool)($this->attributes['status'] ?? false);
    }

    /**
     * Lấy loại người dùng
     *
     * @return integer
     */
    public function getLoaiNguoiDung()
    {
        return $this->attributes['loai_nguoi_dung_id'] ?? 0;
    }
}
