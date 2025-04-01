<?php

namespace App\Modules\quanlycamera\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CameraSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho bảng camera
        $data = [
            [
                'ten_camera' => 'Camera cổng chính',
                'ma_camera' => 'CAM-001',
                'ip_address' => '192.168.1.101',
                'vi_tri' => 'Cổng chính của trường',
                'mo_ta' => 'Camera quan sát khu vực cổng chính ra vào',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera sân trường',
                'ma_camera' => 'CAM-002',
                'ip_address' => '192.168.1.102',
                'vi_tri' => 'Sân trường khu A',
                'mo_ta' => 'Camera quan sát toàn cảnh sân trường khu A',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera hành lang A1',
                'ma_camera' => 'CAM-003',
                'ip_address' => '192.168.1.103',
                'vi_tri' => 'Hành lang tầng 1 khu A',
                'mo_ta' => 'Camera quan sát hành lang tầng 1 khu A',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera hành lang A2',
                'ma_camera' => 'CAM-004',
                'ip_address' => '192.168.1.104',
                'vi_tri' => 'Hành lang tầng 2 khu A',
                'mo_ta' => 'Camera quan sát hành lang tầng 2 khu A',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera hành lang B1',
                'ma_camera' => 'CAM-005',
                'ip_address' => '192.168.1.105',
                'vi_tri' => 'Hành lang tầng 1 khu B',
                'mo_ta' => 'Camera quan sát hành lang tầng 1 khu B',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera hành lang B2',
                'ma_camera' => 'CAM-006',
                'ip_address' => '192.168.1.106',
                'vi_tri' => 'Hành lang tầng 2 khu B',
                'mo_ta' => 'Camera quan sát hành lang tầng 2 khu B',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera cổng phụ',
                'ma_camera' => 'CAM-007',
                'ip_address' => '192.168.1.107',
                'vi_tri' => 'Cổng phụ phía sau trường',
                'mo_ta' => 'Camera quan sát khu vực cổng phụ',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera nhà xe',
                'ma_camera' => 'CAM-008',
                'ip_address' => '192.168.1.108',
                'vi_tri' => 'Khu vực nhà xe',
                'mo_ta' => 'Camera quan sát khu vực để xe của học sinh và giáo viên',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera căn tin',
                'ma_camera' => 'CAM-009',
                'ip_address' => '192.168.1.109',
                'vi_tri' => 'Khu vực căn tin trường',
                'mo_ta' => 'Camera quan sát khu vực căn tin',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_camera' => 'Camera thư viện',
                'ma_camera' => 'CAM-010',
                'ip_address' => '192.168.1.110',
                'vi_tri' => 'Khu vực thư viện',
                'mo_ta' => 'Camera quan sát khu vực thư viện trường',
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
        
        echo "Seeder QuanLyCameraSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho camera.\n";
    }
} 