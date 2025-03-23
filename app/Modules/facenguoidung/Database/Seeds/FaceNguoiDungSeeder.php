<?php

namespace App\Modules\facenguoidung\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class FaceNguoiDungSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nguoi_dung_id'  => 1,
                'duong_dan_anh'  => 'public/data/images/2025/03/22/user1.jpg',
                'ngay_cap_nhat'  => Time::now(),
                'status'         => 1,
                'bin'            => 0,
                'created_at'     => Time::now()
            ],
            [
                'nguoi_dung_id'  => 2,
                'duong_dan_anh'  => 'public/data/images/2025/03/22/user2.jpg',
                'ngay_cap_nhat'  => Time::now(),
                'status'         => 1,
                'bin'            => 0,
                'created_at'     => Time::now()
            ],
            [
                'nguoi_dung_id'  => 3,
                'duong_dan_anh'  => 'public/data/images/2025/03/22/user3.jpg',
                'ngay_cap_nhat'  => Time::now(),
                'status'         => 1,
                'bin'            => 0,
                'created_at'     => Time::now()
            ],
        ];

        // Insert data to table
        $this->db->table('face_nguoi_dung')->insertBatch($data);
    }
} 