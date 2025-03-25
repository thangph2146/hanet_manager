<?php

namespace App\Modules\thamgiasukien\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class ThamGiaSuKienSeeder extends Seeder
{
    public function run()
    {
        // Số lượng bản ghi tham gia sự kiện cần tạo
        $totalRecords = 100;
        
        // Kích thước lô để tránh quá tải bộ nhớ
        $batchSize = 20;
        
        // Chuẩn bị danh sách phương thức điểm danh
        $phuongThucDiemDanh = ['qr_code', 'face_id', 'manual'];
        
        // Giả lập danh sách người dùng (thực tế nên lấy từ DB)
        $nguoiDungIds = range(1, 20);
        
        // Giả lập danh sách sự kiện (thực tế nên lấy từ DB)
        $suKienIds = range(1, 10);
        
        echo "Bắt đầu tạo $totalRecords bản ghi tham gia sự kiện...\n";
        
        // Tạo bản ghi theo từng lô
        for ($batch = 0; $batch < ceil($totalRecords / $batchSize); $batch++) {
            $data = [];
            $startIdx = $batch * $batchSize + 1;
            $endIdx = min(($batch + 1) * $batchSize, $totalRecords);
            
            for ($i = $startIdx; $i <= $endIdx; $i++) {
                // Tạo người dùng và sự kiện ngẫu nhiên, đảm bảo không trùng lặp
                $nguoiDungId = $nguoiDungIds[array_rand($nguoiDungIds)];
                $suKienId = $suKienIds[array_rand($suKienIds)];
                
                // Kiểm tra xem cặp nguoi_dung_id và su_kien_id đã được sử dụng chưa
                $isDuplicate = false;
                foreach ($data as $record) {
                    if ($record['nguoi_dung_id'] == $nguoiDungId && $record['su_kien_id'] == $suKienId) {
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
                
                // Ngẫu nhiên thời gian điểm danh (có thể null)
                $thoiGianDiemDanh = rand(0, 1) ? $createdAt->addMinutes(rand(30, 180))->toDateTimeString() : null;
                
                // Tạo bản ghi
                $data[] = [
                    'nguoi_dung_id' => $nguoiDungId,
                    'su_kien_id' => $suKienId,
                    'thoi_gian_diem_danh' => $thoiGianDiemDanh,
                    'phuong_thuc_diem_danh' => $phuongThucDiemDanh[array_rand($phuongThucDiemDanh)],
                    'ghi_chu' => rand(0, 1) ? 'Ghi chú cho tham gia sự kiện #' . $i : null,
                    'status' => rand(0, 1),
                    'bin' => 0,
                    'created_at' => $createdAt->toDateTimeString(),
                    'updated_at' => $createdAt->toDateTimeString(),
                    'deleted_at' => null
                ];
            }

            // Thêm dữ liệu vào bảng tham_gia_su_kien theo lô
            if (!empty($data)) {
                $this->db->table('tham_gia_su_kien')->insertBatch($data);
                echo "Đã tạo " . count($data) . " bản ghi (từ $startIdx đến $endIdx)...\n";
            }
        }
        
        echo "Seeder ThamGiaSuKienSeeder đã được chạy thành công! Đã tạo $totalRecords bản ghi mẫu.\n";
    }
} 