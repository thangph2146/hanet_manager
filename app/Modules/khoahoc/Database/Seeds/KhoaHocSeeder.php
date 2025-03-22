<?php

namespace App\Modules\khoahoc\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KhoaHocSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'ten_khoa_hoc' => 'Khoa học máy tính',
                'nam_bat_dau' => 2020,
                'nam_ket_thuc' => 2021,
                'phong_khoa_id' => 1,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'ten_khoa_hoc' => 'Khoa học dữ liệu',
                'nam_bat_dau' => 2021,
                'nam_ket_thuc' => 2022,
                'phong_khoa_id' => 2,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'ten_khoa_hoc' => 'Khoa học máy tính',
                'nam_bat_dau' => 2022,
                'nam_ket_thuc' => 2023,
                'phong_khoa_id' => 3,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('khoa_hoc')->insertBatch($data);
    }
} 