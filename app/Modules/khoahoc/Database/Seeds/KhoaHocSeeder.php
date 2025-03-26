<?php

namespace App\Modules\khoahoc\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class KhoaHocSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu của các khóa học
        $data = [];
        
        // Năm bắt đầu và kết thúc cho các khóa học
        $startYear = 2020;
        $endYear = 2030;
        
        echo "Bắt đầu tạo dữ liệu khóa học từ năm $startYear đến $endYear...\n";
        
        // Danh sách các tên khóa học mẫu
        $tenKhoaHoc = [
            'Khóa học Đại học',
            'Khóa học Cao đẳng',
            'Khóa học Trung cấp',
            'Khóa học Ngắn hạn',
            'Khóa học Chứng chỉ'
        ];
        
        // Tạo dữ liệu cho các khóa học
        $khoaHocId = 1;
        for ($i = 0; $i < count($tenKhoaHoc); $i++) {
            // Tính toán năm bắt đầu và kết thúc cho mỗi khóa học
            $namBatDau = $startYear + $i;
            $namKetThuc = $namBatDau + 4; // Giả sử mỗi khóa học kéo dài 4 năm
            
            // Phòng khoa ID ngẫu nhiên từ 1-5
            $phongKhoaId = rand(1, 5);
            
            // Thời gian tạo bản ghi
            $now = Time::now()->toDateTimeString();
            
            $data[] = [
                'ten_khoa_hoc' => $tenKhoaHoc[$i],
                'nam_bat_dau' => $namBatDau,
                'nam_ket_thuc' => $namKetThuc,
                'phong_khoa_id' => $phongKhoaId,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null
            ];
        }
        
        // Thêm dữ liệu vào bảng khoa_hoc
        if (!empty($data)) {
            $this->db->table('khoa_hoc')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi khóa học.\n";
        }
        
        echo "Seeder KhoaHocSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho khóa học.\n";
    }
} 