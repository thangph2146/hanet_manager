<?php

namespace App\Modules\manhinh\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ManHinhSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho các màn hình
        $data = [
            [
                'ten_man_hinh' => 'Màn hình chính',
                'ma_man_hinh' => 'MAIN',
                'camera_id' => 1,
                'template_id' => 1,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_man_hinh' => 'Màn hình phụ 1',
                'ma_man_hinh' => 'SUB1',
                'camera_id' => 2,
                'template_id' => 2,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_man_hinh' => 'Màn hình phụ 2',
                'ma_man_hinh' => 'SUB2',
                'camera_id' => 3,
                'template_id' => 3,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng man_hinh
        if (!empty($data)) {
            $this->db->table('man_hinh')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi màn hình.\n";
        }
        
        echo "Seeder ManHinhSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho màn hình.\n";
    }
} 