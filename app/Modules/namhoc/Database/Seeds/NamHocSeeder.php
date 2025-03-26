<?php

namespace App\Modules\namhoc\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class NamHocSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu của các năm học
        $data = [];
        
        // Năm học hiện tại và tương lai
        $startYear = 2025;
        $endYear = 2030;
        
        echo "Bắt đầu tạo dữ liệu năm học từ $startYear-" . ($startYear + 1) . " đến $endYear-" . ($endYear + 1) . "...\n";
        
        // Tạo dữ liệu cho các năm học từ 2020-2021 đến 2030-2031
        for ($year = $startYear; $year <= $endYear; $year++) {
            $nextYear = $year + 1;
            $tenNamHoc = "$year-$nextYear";
            
            // Thời gian bắt đầu năm học: 01/09/year
            $ngayBatDau = "$year-09-01";
            
            // Thời gian kết thúc năm học: 31/05/nextYear
            $ngayKetThuc = "$nextYear-05-31";
            
            // Status: 1 cho năm hiện tại, 0 cho các năm trong quá khứ
            $currentYear = date('Y');
            $status = ($year >= $currentYear) ? 1 : 0;
            
            // Thời gian tạo bản ghi
            $now = Time::now()->toDateTimeString();
            
            $data[] = [
                'ten_nam_hoc' => $tenNamHoc,
                'ngay_bat_dau' => $ngayBatDau,
                'ngay_ket_thuc' => $ngayKetThuc,
                'status' => $status,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null
            ];
        }
        
        // Thêm dữ liệu vào bảng nam_hoc
        if (!empty($data)) {
            $this->db->table('nam_hoc')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi năm học.\n";
        }
        
        echo "Seeder NamHocSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho năm học.\n";
    }
} 