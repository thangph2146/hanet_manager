<?php

namespace App\Modules\manhinh\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ManHinhSeeder extends Seeder
{
    public function run()
    {
        // Số lượng màn hình cần tạo
        $totalManHinh = 1000;
        
        // Kích thước lô để tránh quá tải bộ nhớ
        $batchSize = 100;
        
        // Lấy số lượng template và camera thực tế
        $templateCount = $this->db->table('template')->countAllResults();
        $cameraCount = $this->db->table('camera')->countAllResults();
        
        echo "Số lượng template hiện có: $templateCount\n";
        echo "Số lượng camera hiện có: $cameraCount\n";
        
        // Chuẩn bị danh sách mẫu đa dạng hơn
        $locations = [
            'Phòng điều khiển', 'Phòng giám sát', 'Phòng an ninh', 'Phòng bảo vệ',
            'Sảnh chính', 'Sảnh phụ', 'Sảnh VIP', 'Sảnh đa năng',
            'Phòng họp lớn', 'Phòng họp nhỏ', 'Phòng họp ban giám đốc',
            'Phòng làm việc', 'Phòng kỹ thuật', 'Phòng máy chủ',
            'Khu vực đậu xe', 'Bãi xe', 'Khu vực giao nhận',
            'Căn tin', 'Khu ăn uống', 'Nhà ăn',
            'Kho', 'Kho dụng cụ', 'Kho vật tư',
            'Thư viện', 'Phòng đọc', 'Phòng tài liệu',
            'Khu vực sản xuất', 'Xưởng', 'Dây chuyền',
            'Sân', 'Vườn', 'Khu vực xanh'
        ];
        
        // Các loại màn hình phổ biến
        $screenTypes = [
            'LCD', 'LED', 'OLED', 'QLED', 'Smart TV',
            'Màn hình chuyên dụng', 'Màn hình giám sát',
            'Màn hình điều khiển', 'Màn hình hiển thị'
        ];
        
        // Các nhà sản xuất màn hình
        $manufacturers = [
            'Samsung', 'LG', 'Sony', 'Panasonic', 'TCL',
            'Philips', 'Sharp', 'AOC', 'Dell', 'HP'
        ];
        
        echo "Bắt đầu tạo $totalManHinh bản ghi màn hình...\n";
        
        // Tạo màn hình theo từng lô
        for ($batch = 0; $batch < ceil($totalManHinh / $batchSize); $batch++) {
            $data = [];
            $startIdx = $batch * $batchSize + 1;
            $endIdx = min(($batch + 1) * $batchSize, $totalManHinh);
            
            for ($i = $startIdx; $i <= $endIdx; $i++) {
                // Tạo các giá trị mẫu
                $index = str_pad($i, 4, '0', STR_PAD_LEFT);
                $location = $locations[array_rand($locations)];
                $screenType = $screenTypes[array_rand($screenTypes)];
                $manufacturer = $manufacturers[array_rand($manufacturers)];
                
                // Tạo ngày tạo ngẫu nhiên trong 6 tháng gần đây
                $randomDays = rand(0, 180);
                $createdAt = Time::now()->subDays($randomDays);
                
                // Tạo số model ngẫu nhiên
                $model = $manufacturer . '-' . chr(rand(65, 90)) . rand(10, 99);
                
                // Tạo camera_id ngẫu nhiên (có thể null)
                $cameraId = null;
                if ($cameraCount > 0 && rand(0, 100) < 80) { // 80% khả năng có camera
                    $cameraId = rand(1, $cameraCount);
                }
                
                // Tạo template_id ngẫu nhiên (có thể null)
                $templateId = null;
                if ($templateCount > 0 && rand(0, 100) < 70) { // 70% khả năng có template
                    $templateId = rand(1, $templateCount);
                }
                
                // Tạo bản ghi
                $data[] = [
                    'ma_man_hinh' => 'MH' . $index,
                    'ten_man_hinh' => $location . ' ' . $screenType . ' ' . $model . ' #' . $i,
                    'camera_id' => $cameraId,
                    'template_id' => $templateId,
                    'status' => rand(0, 1),
                    'bin' => 0,
                    'created_at' => $createdAt->toDateTimeString(),
                    'updated_at' => $createdAt->toDateTimeString(),
                    'deleted_at' => null
                ];
            }

            // Thêm dữ liệu vào bảng man_hinh theo lô
            $this->db->table('man_hinh')->insertBatch($data);
            
            echo "Đã tạo " . count($data) . " bản ghi (từ $startIdx đến $endIdx)...\n";
        }
        
        echo "Seeder ManHinhSeeder đã được chạy thành công! Đã tạo $totalManHinh màn hình mẫu.\n";
    }
} 