<?php

namespace App\Modules\quanlybachoc\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class QuanLyBacHocSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho bảng bậc học
        $data = [
            // Giáo dục mầm non
            [
                'ten_bac_hoc' => 'Nhà trẻ',
                'ma_bac_hoc' => 'NT',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_bac_hoc' => 'Mẫu giáo',
                'ma_bac_hoc' => 'MG',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            // Giáo dục phổ thông
            [
                'ten_bac_hoc' => 'Tiểu học',
                'ma_bac_hoc' => 'TH',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_bac_hoc' => 'Trung học cơ sở',
                'ma_bac_hoc' => 'THCS',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_bac_hoc' => 'Trung học phổ thông',
                'ma_bac_hoc' => 'THPT',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            // Giáo dục nghề nghiệp
            [
                'ten_bac_hoc' => 'Sơ cấp',
                'ma_bac_hoc' => 'SC',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_bac_hoc' => 'Trung cấp',
                'ma_bac_hoc' => 'TC',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_bac_hoc' => 'Cao đẳng',
                'ma_bac_hoc' => 'CD',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            // Giáo dục đại học
            [
                'ten_bac_hoc' => 'Đại học',
                'ma_bac_hoc' => 'DH',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            // Giáo dục sau đại học
            [
                'ten_bac_hoc' => 'Thạc sĩ',
                'ma_bac_hoc' => 'TS',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_bac_hoc' => 'Tiến sĩ',
                'ma_bac_hoc' => 'TS',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            // Giáo dục thường xuyên
            [
                'ten_bac_hoc' => 'Xóa mù chữ',
                'ma_bac_hoc' => 'XMC',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_bac_hoc' => 'Giáo dục tiếp tục sau khi biết chữ',
                'ma_bac_hoc' => 'GDTTSBC',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_bac_hoc' => 'Giáo dục phổ thông thường xuyên',
                'ma_bac_hoc' => 'GDTT',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng bac_hoc
        if (!empty($data)) {
            $this->db->table('bac_hoc')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi bậc học.\n";
        }
        
        echo "Seeder QuanLyBacHocSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho bậc học.\n";
    }
} 