<?php

namespace App\Entities;

use App\Entities\BaseEntity;

class NguoiDungEntity extends BaseEntity
{
    protected $tableName = 'nguoi_dung';

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

    public function setPW(string $password)
    {
        $this->attributes['PW'] = password_hash($password, PASSWORD_DEFAULT);
        return $this;
    }

    public function getPW(): string
    {
        return $this->attributes['PW'];
    }
}
