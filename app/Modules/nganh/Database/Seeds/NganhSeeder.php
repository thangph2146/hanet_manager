<?php

namespace App\Modules\nganh\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class NganhSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho các ngành
        $data = [
            [
                'ten_nganh' => 'Công nghệ thông tin',
                'ma_nganh' => 'CNTT',
                'phong_khoa_id' => 7,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_nganh' => 'Kỹ thuật phần mềm',
                'ma_nganh' => 'KTPM',
                'phong_khoa_id' => 7,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_nganh' => 'Mạng máy tính và truyền thông dữ liệu',
                'ma_nganh' => 'MMT',
                'phong_khoa_id' => 7,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_nganh' => 'Khoa học máy tính',
                'ma_nganh' => 'KHMT',
                'phong_khoa_id' => 7,
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng nganh
        if (!empty($data)) {
            $this->db->table('nganh')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi ngành.\n";
        }
        
        echo "Seeder NganhSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho ngành.\n";
    }
} 