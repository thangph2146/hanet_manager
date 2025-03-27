<?php

namespace App\Modules\camera\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CameraSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho bảng camera
        $data = [
            [
                'ten_camera' => 'Camera Hành Lang 1',
                'ma_camera' => 'HL001',
                'ip_camera' => '192.168.1.100',
                'port' => 554,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera Sảnh Chính',
                'ma_camera' => 'SC001',
                'ip_camera' => '192.168.1.101',
                'port' => 554,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera Bãi Đỗ Xe',
                'ma_camera' => 'BX001',
                'ip_camera' => '192.168.1.102',
                'port' => 554,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera Cổng Chính',
                'ma_camera' => 'CC001',
                'ip_camera' => '192.168.1.103',
                'port' => 554,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera Hành Lang 2',
                'ma_camera' => 'HL002',
                'ip_camera' => '192.168.1.104',
                'port' => 554,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng camera
        if (!empty($data)) {
            $this->db->table('camera')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi camera.\n";
        }
        
        echo "Seeder CameraSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho camera.\n";
    }
} 