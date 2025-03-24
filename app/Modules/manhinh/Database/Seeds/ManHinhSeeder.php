<?php

namespace App\Modules\camera\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CameraSeeder extends Seeder
{
    public function run()
    {
        // Số lượng camera cần tạo
        $totalCameras = 5000;
        
        // Kích thước lô để tránh quá tải bộ nhớ
        $batchSize = 500;
        
        // Chuẩn bị danh sách mẫu đa dạng hơn
        $locations = [
            'Cổng chính', 'Cổng phụ', 'Cổng sau', 'Cổng bảo vệ', 
            'Sảnh A', 'Sảnh B', 'Sảnh C', 'Sảnh chính', 'Sảnh VIP',
            'Hành lang tầng 1', 'Hành lang tầng 2', 'Hành lang tầng 3', 'Hành lang tầng 4', 'Hành lang tầng 5',
            'Phòng họp lớn', 'Phòng họp nhỏ', 'Phòng họp A', 'Phòng họp B', 'Phòng họp C', 'Phòng họp ban giám đốc',
            'Phòng làm việc A', 'Phòng làm việc B', 'Phòng làm việc C', 'Phòng làm việc D', 'Phòng làm việc E',
            'Khu vực đậu xe A', 'Khu vực đậu xe B', 'Khu vực đậu xe C', 'Bãi xe máy', 'Bãi xe ô tô',
            'Căn tin', 'Khu ăn uống', 'Nhà ăn', 'Phòng ăn nhân viên', 
            'Kho A', 'Kho B', 'Kho C', 'Kho dụng cụ', 'Kho vật tư', 'Kho lưu trữ',
            'Thư viện', 'Phòng đọc', 'Khu vực sách', 'Phòng tài liệu',
            'Phòng máy chủ', 'Phòng máy tính', 'Phòng kỹ thuật', 'Phòng thiết bị',
            'Khu vực sản xuất', 'Xưởng', 'Dây chuyền', 'Khu lắp ráp',
            'Sân trước', 'Sân sau', 'Vườn', 'Khu vực xanh'
        ];
        
        // Các nhà sản xuất camera phổ biến
        $manufacturers = ['Hikvision', 'Dahua', 'Axis', 'Bosch', 'Sony', 'Samsung', 'Panasonic', 'Vivotek', 'Hanwha', 'CP Plus'];
        
        // Mẫu IP khác nhau
        $ipPatterns = [
            '192.168.%d.%d',
            '192.169.%d.%d',
            '10.0.%d.%d',
            '10.10.%d.%d',
            '172.16.%d.%d',
            '172.17.%d.%d',
            '172.18.%d.%d'
        ];
        
        echo "Bắt đầu tạo $totalCameras bản ghi camera...\n";
        
        // Tạo camera theo từng lô
        for ($batch = 0; $batch < ceil($totalCameras / $batchSize); $batch++) {
            $data = [];
            $startIdx = $batch * $batchSize + 1;
            $endIdx = min(($batch + 1) * $batchSize, $totalCameras);
            
            for ($i = $startIdx; $i <= $endIdx; $i++) {
                // Tạo các giá trị mẫu
                $index = str_pad($i, 4, '0', STR_PAD_LEFT);
                $location = $locations[array_rand($locations)];
                $manufacturer = $manufacturers[array_rand($manufacturers)];
                
                // Tạo địa chỉ IP ngẫu nhiên
                $ipPattern = $ipPatterns[array_rand($ipPatterns)];
                $ip = sprintf($ipPattern, rand(0, 255), rand(2, 254));
                
                // Tạo port ngẫu nhiên trong khoảng hợp lý
                $port = rand(8000, 9999);
                
                // Tạo ngày tạo ngẫu nhiên trong 6 tháng gần đây
                $randomDays = rand(0, 180);
                $createdAt = Time::now()->subDays($randomDays);
                
                // Tạo số model ngẫu nhiên
                $model = $manufacturer . '-' . chr(rand(65, 90)) . rand(10, 99);
                
                // Tạo bản ghi
                $data[] = [
                    'ma_camera' => 'CAM' . $index,
                    'ten_camera' => $location . ' ' . $model . ' #' . $i,
                    'ip_camera' => $ip,
                    'port' => $port,
                    'username' => 'admin',
                    'password' => 'camera@' . $index,
                    'status' => rand(0, 1),
                    'bin' => 0,
                    'created_at' => $createdAt->toDateTimeString(),
                    'updated_at' => $createdAt->toDateTimeString(),
                    'deleted_at' => null
                ];
            }

            // Thêm dữ liệu vào bảng camera theo lô
            $this->db->table('camera')->insertBatch($data);
            
            echo "Đã tạo " . count($data) . " bản ghi (từ $startIdx đến $endIdx)...\n";
        }
        
        echo "Seeder CameraSeeder đã được chạy thành công! Đã tạo $totalCameras camera mẫu.\n";
    }
} 