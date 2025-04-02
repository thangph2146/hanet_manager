<?php

namespace App\Modules\loaisukien\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class LoaiSuKienSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho bảng loại sự kiện
        $data = [
            [
                'ten_loai_su_kien' => 'Hội thảo',
                'ma_loai_su_kien' => 'HT',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Hội nghị',
                'ma_loai_su_kien' => 'HN',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Workshop',
                'ma_loai_su_kien' => 'WS',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Seminar',
                'ma_loai_su_kien' => 'SM',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Tọa đàm',
                'ma_loai_su_kien' => 'TD',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng loai_su_kien
        if (!empty($data)) {
            $this->db->table('loai_su_kien')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi loại sự kiện.\n";
        }
        
        echo "Seeder LoaiSuKienSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho loại sự kiện.\n";
    }
} 