<?php

namespace App\Modules\facenguoidung\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class FaceNguoiDungSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho khuôn mặt người dùng
        $data = [
            [
                'nguoi_dung_id' => 1,
                'duong_dan_anh' => 'faces/user1.jpg',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'nguoi_dung_id' => 2,
                'duong_dan_anh' => 'faces/user2.jpg',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng face_nguoi_dung
        if (!empty($data)) {
            $this->db->table('face_nguoi_dung')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi khuôn mặt người dùng.\n";
        }
        
        echo "Seeder FaceNguoiDungSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho khuôn mặt người dùng.\n";
    }
} 