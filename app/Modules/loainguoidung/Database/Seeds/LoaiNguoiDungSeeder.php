<?php

namespace App\Modules\loainguoidung\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class LoaiNguoiDungSeeder extends Seeder
{
    public function run()
    {
        // Số lượng bản ghi loại người dùng cần tạo
        $totalRecords = 5;
        
        echo "Bắt đầu tạo $totalRecords bản ghi loại người dùng...\n";
        
        // Danh sách các loại người dùng cơ bản
        $loaiNguoiDung = [
            [
                'ten_loai' => 'Admin',
                'mo_ta' => 'Người quản trị hệ thống với toàn quyền',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai' => 'Nhân viên',
                'mo_ta' => 'Nhân viên với quyền hạn chế',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai' => 'Khách hàng',
                'mo_ta' => 'Người dùng đã đăng ký tài khoản',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai' => 'Đối tác',
                'mo_ta' => 'Đối tác kinh doanh',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai' => 'Cộng tác viên',
                'mo_ta' => 'Cộng tác viên tạm thời',
                'status' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng loai_nguoi_dung
        $this->db->table('loai_nguoi_dung')->insertBatch($loaiNguoiDung);
        
        echo "Seeder LoaiNguoiDungSeeder đã được chạy thành công! Đã tạo $totalRecords bản ghi mẫu.\n";
    }
} 