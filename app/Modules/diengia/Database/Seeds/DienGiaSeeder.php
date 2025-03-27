<?php

namespace App\Modules\diengia\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DienGiaSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho bảng diễn giả
        $data = [
            [
                'ten_dien_gia' => 'Nguyễn Văn A',
                'chuc_danh' => 'Giáo sư',
                'to_chuc' => 'Đại học Quốc gia Hà Nội',
                'gioi_thieu' => 'Chuyên gia hàng đầu về khoa học máy tính và trí tuệ nhân tạo.',
                'avatar' => 'nguyen-van-a.jpg',
                'thu_tu' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Trần Thị B',
                'chuc_danh' => 'Tiến sĩ',
                'to_chuc' => 'Đại học Bách Khoa Hà Nội',
                'gioi_thieu' => 'Nhà nghiên cứu và giảng viên xuất sắc trong lĩnh vực khoa học dữ liệu.',
                'avatar' => 'tran-thi-b.jpg',
                'thu_tu' => 2,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Lê Văn C',
                'chuc_danh' => 'Phó Giáo sư',
                'to_chuc' => 'Viện Khoa học và Công nghệ Việt Nam',
                'gioi_thieu' => 'Chuyên gia về công nghệ thông tin và an ninh mạng.',
                'avatar' => 'le-van-c.jpg', 
                'thu_tu' => 3,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Phạm Văn D',
                'chuc_danh' => 'Chuyên gia',
                'to_chuc' => 'Tập đoàn FPT',
                'gioi_thieu' => 'Chuyên gia về blockchain và fintech với hơn 15 năm kinh nghiệm.',
                'avatar' => 'pham-van-d.jpg',
                'thu_tu' => 4,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_dien_gia' => 'Hoàng Thị E',
                'chuc_danh' => 'CEO',
                'to_chuc' => 'Công ty ABC Technology',
                'gioi_thieu' => 'Nhà sáng lập và điều hành doanh nghiệp công nghệ hàng đầu Việt Nam.',
                'avatar' => 'hoang-thi-e.jpg',
                'thu_tu' => 5,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng dien_gia
        if (!empty($data)) {
            $this->db->table('dien_gia')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi diễn giả.\n";
        }
        
        echo "Seeder DienGiaSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho diễn giả.\n";
    }
} 