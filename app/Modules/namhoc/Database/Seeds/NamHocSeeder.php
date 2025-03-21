<?php

namespace App\Modules\namhoc\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class NamHocSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'ten_nam_hoc'   => 'Năm học 2023-2024',
                'ngay_bat_dau'  => '2023-09-01',
                'ngay_ket_thuc' => '2024-05-31',
                'status'        => 1,
                'bin'           => 0,
                'created_at'    => Time::now()
            ],
            [
                'ten_nam_hoc'   => 'Năm học 2024-2025',
                'ngay_bat_dau'  => '2024-09-01',
                'ngay_ket_thuc' => '2025-05-31',
                'status'        => 1,
                'bin'           => 0,
                'created_at'    => Time::now()
            ],
            [
                'ten_nam_hoc'   => 'Năm học 2025-2026',
                'ngay_bat_dau'  => '2025-09-01',
                'ngay_ket_thuc' => '2026-05-31',
                'status'        => 0,
                'bin'           => 0,
                'created_at'    => Time::now()
            ],
        ];

        $this->db->table('nam_hoc')->insertBatch($data);
    }
} 