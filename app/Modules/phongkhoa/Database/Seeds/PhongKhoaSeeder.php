<?php

namespace App\Modules\phongkhoa\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class PhongKhoaSeeder extends Seeder
{
    public function run()
    {
        // Số lượng bản ghi phòng khoa cần tạo
        $totalRecords = 12;
        
        echo "Bắt đầu tạo $totalRecords bản ghi phòng khoa của trường Đại học Ngân hàng TP.HCM...\n";
        
        // Danh sách các phòng khoa của trường Đại học Ngân hàng TP.HCM
        $phongKhoa = [
            [
                'ma_phong_khoa' => 'KTC',
                'ten_phong_khoa' => 'Khoa Tài chính',
                'ghi_chu' => 'Đào tạo chuyên ngành Tài chính',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_phong_khoa' => 'KNH',
                'ten_phong_khoa' => 'Khoa Ngân hàng',
                'ghi_chu' => 'Đào tạo chuyên ngành Ngân hàng',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_phong_khoa' => 'KQTKD',
                'ten_phong_khoa' => 'Khoa Quản trị kinh doanh',
                'ghi_chu' => 'Đào tạo chuyên ngành Quản trị kinh doanh',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_phong_khoa' => 'KKT',
                'ten_phong_khoa' => 'Khoa Kế toán',
                'ghi_chu' => 'Đào tạo chuyên ngành Kế toán - Kiểm toán',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_phong_khoa' => 'KKTQT',
                'ten_phong_khoa' => 'Khoa Kinh tế quốc tế',
                'ghi_chu' => 'Đào tạo chuyên ngành Kinh tế quốc tế',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_phong_khoa' => 'KHTTTQL',
                'ten_phong_khoa' => 'Khoa Hệ thống thông tin quản lý',
                'ghi_chu' => 'Đào tạo chuyên ngành Hệ thống thông tin quản lý',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_phong_khoa' => 'KLKT',
                'ten_phong_khoa' => 'Khoa Luật kinh tế',
                'ghi_chu' => 'Đào tạo chuyên ngành Luật kinh tế',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_phong_khoa' => 'KNN',
                'ten_phong_khoa' => 'Khoa Ngoại ngữ',
                'ghi_chu' => 'Đào tạo chuyên ngành Ngoại ngữ',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_phong_khoa' => 'KKHXH',
                'ten_phong_khoa' => 'Khoa Khoa học xã hội',
                'ghi_chu' => 'Đào tạo các môn học về khoa học xã hội',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_phong_khoa' => 'KSDH',
                'ten_phong_khoa' => 'Khoa Sau Đại học',
                'ghi_chu' => 'Quản lý và đào tạo các chương trình sau đại học',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_phong_khoa' => 'KKHDL',
                'ten_phong_khoa' => 'Khoa Khoa học dữ liệu trong kinh doanh',
                'ghi_chu' => 'Đào tạo chuyên ngành Khoa học dữ liệu trong kinh doanh',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_phong_khoa' => 'KGDTC',
                'ten_phong_khoa' => 'Khoa Giáo dục Thể chất và Quốc phòng',
                'ghi_chu' => 'Đào tạo các môn học về giáo dục thể chất và quốc phòng',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng phong_khoa
        $this->db->table('phong_khoa')->insertBatch($phongKhoa);
        
        echo "Seeder PhongKhoaSeeder đã được chạy thành công! Đã tạo $totalRecords bản ghi mẫu.\n";
    }
} 