<?php

namespace App\Modules\bachoc\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BacHocSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'ten_bac_hoc' => 'Mầm non',
                'ma_bac_hoc' => 'MN',
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'ten_bac_hoc' => 'Tiểu học',
                'ma_bac_hoc' => 'TH',
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'ten_bac_hoc' => 'Trung học cơ sở',
                'ma_bac_hoc' => 'THCS',
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'ten_bac_hoc' => 'Trung học phổ thông',
                'ma_bac_hoc' => 'THPT',
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('bac_hoc')->insertBatch($data);
    }
}