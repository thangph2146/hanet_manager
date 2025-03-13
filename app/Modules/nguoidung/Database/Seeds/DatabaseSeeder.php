<?php

namespace App\Modules\nguoidung\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('App\Modules\nguoidung\Database\Seeds\NguoiDungSeeder');
    }
} 