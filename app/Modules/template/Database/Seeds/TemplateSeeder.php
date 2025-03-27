<?php

namespace App\Modules\template\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class TemplateSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho bảng template
        $data = [
            [
                'ten_template' => 'Template 1',
                'ma_template' => 'TPL1',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_template' => 'Template 2',
                'ma_template' => 'TPL2',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_template' => 'Template 3',
                'ma_template' => 'TPL3',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_template' => 'Template 4',
                'ma_template' => 'TPL4',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_template' => 'Template 5',
                'ma_template' => 'TPL5',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng template
        if (!empty($data)) {
            $this->db->table('template')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi template.\n";
        }
        
        echo "Seeder TemplateSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho template.\n";
    }
} 