<?php

namespace App\Modules\nganh\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class NganhSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'ma_nganh' => 'CNTT',
                'ten_nganh' => 'Công nghệ thông tin',
                'phong_khoa_id' => null,
                'status' => 1,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_nganh' => 'MARKETING',
                'ten_nganh' => 'Marketing',
                'phong_khoa_id' => null,
                'status' => 1,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_nganh' => 'QTKINHDOANH',
                'ten_nganh' => 'Quản trị kinh doanh',
                'phong_khoa_id' => null,
                'status' => 1,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_nganh' => 'KINHTE',
                'ten_nganh' => 'Kinh tế',
                'phong_khoa_id' => null,
                'status' => 1,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_nganh' => 'KTOAN',
                'ten_nganh' => 'Kế toán',
                'phong_khoa_id' => null,
                'status' => 1,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
        ];

        // Thêm dữ liệu vào bảng nganh
        $this->db->table('nganh')->insertBatch($data);
        
        echo "Seeder NganhSeeder đã được chạy thành công!\n";
    }
} 