<?php

namespace App\Modules\template\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class TemplateSeeder extends Seeder
{
    public function run()
    {
        // Số lượng template cần tạo
        $totalTemplates = 100;
        
        // Kích thước lô để tránh quá tải bộ nhớ
        $batchSize = 20;
        
        // Chuẩn bị danh sách mẫu đa dạng hơn
        $templateTypes = [
            'Mẫu báo cáo', 'Mẫu đơn', 'Mẫu thông báo', 'Mẫu hợp đồng', 
            'Mẫu biên bản', 'Mẫu kế hoạch', 'Mẫu quyết định', 'Mẫu công văn',
            'Mẫu phiếu', 'Mẫu bảng', 'Mẫu tờ trình', 'Mẫu giấy mời',
            'Mẫu giấy giới thiệu', 'Mẫu giấy đề nghị', 'Mẫu giấy xác nhận'
        ];
        
        $departments = [
            'Phòng Hành chính', 'Phòng Nhân sự', 'Phòng Kế toán', 'Phòng Kinh doanh',
            'Phòng Kỹ thuật', 'Phòng IT', 'Phòng Marketing', 'Ban Giám đốc',
            'Phòng Dự án', 'Phòng Chất lượng', 'Phòng R&D', 'Phòng Sản xuất'
        ];
        
        echo "Bắt đầu tạo $totalTemplates bản ghi template...\n";
        
        // Tạo template theo từng lô
        for ($batch = 0; $batch < ceil($totalTemplates / $batchSize); $batch++) {
            $data = [];
            $startIdx = $batch * $batchSize + 1;
            $endIdx = min(($batch + 1) * $batchSize, $totalTemplates);
            
            for ($i = $startIdx; $i <= $endIdx; $i++) {
                // Tạo các giá trị mẫu
                $index = str_pad($i, 3, '0', STR_PAD_LEFT);
                $type = $templateTypes[array_rand($templateTypes)];
                $department = $departments[array_rand($departments)];
                
                // Tạo ngày tạo ngẫu nhiên trong 6 tháng gần đây
                $randomDays = rand(0, 180);
                $createdAt = Time::now()->subDays($randomDays);
                
                // Tạo bản ghi
                $data[] = [
                    'ma_template' => 'TPL' . $index,
                    'ten_template' => $type . ' - ' . $department . ' #' . $i,
                    'status' => rand(0, 1),
                    'bin' => 0,
                    'created_at' => $createdAt->toDateTimeString(),
                    'updated_at' => $createdAt->toDateTimeString(),
                    'deleted_at' => null
                ];
            }

            // Thêm dữ liệu vào bảng template theo lô
            $this->db->table('template')->insertBatch($data);
            
            echo "Đã tạo " . count($data) . " bản ghi (từ $startIdx đến $endIdx)...\n";
        }
        
        echo "Seeder TemplateSeeder đã được chạy thành công! Đã tạo $totalTemplates template mẫu.\n";
    }
} 