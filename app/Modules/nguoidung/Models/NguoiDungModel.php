<?php

namespace App\Modules\nguoidung\Models;

use App\Models\BaseModel;

class NguoiDungModel extends BaseModel
{
    protected $table = 'nguoi_dung';
    protected $allowedFields = ['username', 'email', 'password'];
    protected $returnType = \App\Modules\nguoidung\Entities\NguoiDung::class;
    protected $useTimestamps = true;
}
