<?php

namespace App\Modules\template\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class TemplateSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'ma_template' => 'TRUONGHOC',
                'ten_template' => 'Template trường học',
                'status' => 1,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_template' => 'CONGTY',
                'ten_template' => 'Template công ty',
                'status' => 1,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_template' => 'SUKIEN',
                'ten_template' => 'Template sự kiện',
                'status' => 1,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_template' => 'HOINGHI',
                'ten_template' => 'Template hội nghị',
                'status' => 1,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ma_template' => 'DOANHNGHIEP',
                'ten_template' => 'Template doanh nghiệp',
                'status' => 1,
                'bin' => 0,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
        ];

        // Thêm dữ liệu vào bảng template
        $this->db->table('template')->insertBatch($data);
        
        echo "Seeder TemplateSeeder đã được chạy thành công!\n";
    }
} 