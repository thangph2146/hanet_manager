<?php

namespace App\Modules\hedaotao\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class HeDaoTaoSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho bảng hệ đào tạo
        $data = [
            [
                'ten_he_dao_tao' => 'Chính quy',
                'ma_he_dao_tao' => 'CQ',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_he_dao_tao' => 'Vừa làm vừa học',
                'ma_he_dao_tao' => 'VLVH',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_he_dao_tao' => 'Từ xa',
                'ma_he_dao_tao' => 'TX',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_he_dao_tao' => 'Liên thông',
                'ma_he_dao_tao' => 'LT',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_he_dao_tao' => 'Văn bằng 2',
                'ma_he_dao_tao' => 'VB2',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng he_dao_tao
        if (!empty($data)) {
            $this->db->table('he_dao_tao')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi hệ đào tạo.\n";
        }
        
        echo "Seeder HeDaoTaoSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho hệ đào tạo.\n";
    }
} 