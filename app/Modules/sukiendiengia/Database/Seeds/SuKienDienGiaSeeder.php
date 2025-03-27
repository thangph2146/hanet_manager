<?php

namespace App\Modules\sukiendiengia\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SuKienDienGiaSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho sự kiện diễn giả
        $data = [
            [
                'su_kien_dien_gia_id' => 1,
                'su_kien_id' => 1,
                'dien_gia_id' => 1,
                'thu_tu' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'su_kien_dien_gia_id' => 2,
                'su_kien_id' => 1,
                'dien_gia_id' => 2,
                'thu_tu' => 2,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'su_kien_dien_gia_id' => 3,
                'su_kien_id' => 2,
                'dien_gia_id' => 1,
                'thu_tu' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'su_kien_dien_gia_id' => 4,
                'su_kien_id' => 2,
                'dien_gia_id' => 3,
                'thu_tu' => 2,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng su_kien_dien_gia
        if (!empty($data)) {
            $this->db->table('su_kien_dien_gia')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi liên kết sự kiện và diễn giả.\n";
        }
        
        echo "Seeder SuKienDienGiaSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho liên kết sự kiện và diễn giả.\n";
    }
} 