<?php

namespace App\Modules\quanlyloainguoidung\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class LoaiNguoiDungSeeder extends Seeder
{
    public function run()
    {
        // Số lượng bản ghi loại người dùng cần tạo
        $totalRecords = 5;
        
        echo "Bắt đầu tạo $totalRecords bản ghi loại người dùng...\n";
        
        // Kiểm tra xem bảng có dữ liệu chưa
        $existingRecords = $this->db->table('loai_nguoi_dung')->countAllResults();
        
        if ($existingRecords > 0) {
            echo "Bảng loai_nguoi_dung đã có $existingRecords bản ghi.\n";
            
            // Xóa dữ liệu cũ trước khi thêm mới
            if ($this->db->tableExists('loai_nguoi_dung')) {
                // Kiểm tra xem có bản ghi nào đang được tham chiếu không
                $hasForeignKeyConstraints = false;
                try {
                    // Kiểm tra bằng cách thử truncate bảng
                    $this->db->table('loai_nguoi_dung')->truncate();
                    echo "Đã xóa dữ liệu cũ trong bảng loai_nguoi_dung.\n";
                } catch (\Exception $e) {
                    $hasForeignKeyConstraints = true;
                    echo "Không thể xóa dữ liệu vì tồn tại khóa ngoại tham chiếu đến bảng này.\n";
                    echo "Đang bỏ qua việc thêm dữ liệu mới để tránh lỗi.\n";
                    return;
                }
            }
        }
        
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
        try {
            $this->db->table('loai_nguoi_dung')->insertBatch($loaiNguoiDung);
            echo "Seeder LoaiNguoiDungSeeder đã được chạy thành công! Đã tạo $totalRecords bản ghi mẫu.\n";
        } catch (\Exception $e) {
            // Kiểm tra xem lỗi có phải do trùng lắp dữ liệu không
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                echo "Lỗi: Dữ liệu đã tồn tại trong bảng loai_nguoi_dung.\n";
                echo "Có thể bạn đã chạy seeder này trước đó hoặc dữ liệu được thêm theo cách khác.\n";
                echo "Nếu muốn tạo lại dữ liệu, hãy xóa dữ liệu cũ trước.\n";
            } else {
                // Nếu là lỗi khác, hiển thị đầy đủ
                throw $e;
            }
        }
    }
}