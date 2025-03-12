<?php

namespace App\Modules\nguoidung\Models;

use CodeIgniter\Model;

class NguoiDungModel extends Model
{
    protected $table = 'nguoi_dung';
    protected $allowedFields = ['username', 'email', 'password'];
    protected $returnType = \App\Modules\nguoidung\Entities\NguoiDung::class;
    protected $useTimestamps = true;
}
