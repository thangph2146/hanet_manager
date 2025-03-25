<?php

namespace App\Modules\sukiendiengia\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SuKienDienGiaSeeder extends Seeder
{
    public function run()
    {
        // Số lượng bản ghi liên kết sự kiện và diễn giả cần tạo
        $totalRecords = 50;
        
        // Kích thước lô để tránh quá tải bộ nhớ
        $batchSize = 20;
        
        // Giả lập danh sách diễn giả (thực tế nên lấy từ DB)
        $dienGiaIds = range(1, 10);
        
        // Giả lập danh sách sự kiện (thực tế nên lấy từ DB)
        $suKienIds = range(1, 5);
        
        echo "Bắt đầu tạo $totalRecords bản ghi liên kết sự kiện và diễn giả...\n";
        
        // Tạo bản ghi theo từng lô
        for ($batch = 0; $batch < ceil($totalRecords / $batchSize); $batch++) {
            $data = [];
            $startIdx = $batch * $batchSize + 1;
            $endIdx = min(($batch + 1) * $batchSize, $totalRecords);
            
            for ($i = $startIdx; $i <= $endIdx; $i++) {
                // Tạo sự kiện và diễn giả ngẫu nhiên, đảm bảo không trùng lặp
                $suKienId = $suKienIds[array_rand($suKienIds)];
                $dienGiaId = $dienGiaIds[array_rand($dienGiaIds)];
                
                // Kiểm tra xem cặp su_kien_id và dien_gia_id đã được sử dụng chưa
                $isDuplicate = false;
                foreach ($data as $record) {
                    if ($record['su_kien_id'] == $suKienId && $record['dien_gia_id'] == $dienGiaId) {
                        $isDuplicate = true;
                        break;
                    }
                }
                
                // Nếu đã có cặp này, thử lại với cặp khác
                if ($isDuplicate) {
                    $i--; // Giảm i để tạo lại bản ghi này
                    continue;
                }
                
                // Tạo ngày tạo ngẫu nhiên trong 30 ngày gần đây
                $randomDays = rand(0, 30);
                $createdAt = Time::now()->subDays($randomDays);
                
                // Tạo bản ghi
                $data[] = [
                    'su_kien_id' => $suKienId,
                    'dien_gia_id' => $dienGiaId,
                    'thu_tu' => rand(0, 10),
                    'created_at' => $createdAt->toDateTimeString(),
                    'updated_at' => $createdAt->toDateTimeString(),
                    'deleted_at' => null
                ];
            }

            // Thêm dữ liệu vào bảng su_kien_dien_gia theo lô
            if (!empty($data)) {
                $this->db->table('su_kien_dien_gia')->insertBatch($data);
                echo "Đã tạo " . count($data) . " bản ghi (từ $startIdx đến $endIdx)...\n";
            }
        }
        
        echo "Seeder SuKienDienGiaSeeder đã được chạy thành công! Đã tạo $totalRecords bản ghi mẫu.\n";
    }
} 