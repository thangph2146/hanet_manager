<?php

namespace App\Modules\diengia\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DienGiaSeeder extends Seeder
{
    public function run()
    {
        // Số lượng diễn giả cần tạo
        $totalDienGia = 50;
        
        // Kích thước lô để tránh quá tải bộ nhớ
        $batchSize = 10;
        
        // Chuẩn bị danh sách mẫu đa dạng
        $chucDanhs = [
            'Giáo sư', 'Phó Giáo sư', 'Tiến sĩ', 'Thạc sĩ', 'Chuyên gia', 
            'Nhà nghiên cứu', 'Giảng viên', 'Cố vấn', 'Chuyên gia tư vấn', 'Trưởng phòng',
            'Giám đốc', 'CEO', 'Founder', 'Co-founder', 'CTO', 'CFO', 'COO'
        ];
        
        // Tổ chức mẫu
        $toChuc = [
            'Đại học Quốc gia Hà Nội', 'Đại học Bách Khoa Hà Nội', 'Đại học Kinh tế Quốc dân',
            'Viện Hàn lâm Khoa học và Công nghệ Việt Nam', 'Bộ Giáo dục và Đào tạo',
            'Bộ Khoa học và Công nghệ', 'Tập đoàn FPT', 'Tập đoàn Viettel', 'Ngân hàng Techcombank',
            'Công ty VNG', 'Nền tảng Momo', 'Trung tâm Đổi mới sáng tạo Quốc gia',
            'Quỹ Khởi nghiệp Doanh nghiệp KH&CN Việt Nam', 'Google Việt Nam', 'Microsoft Việt Nam'
        ];
        
        // Giới thiệu mẫu
        $gioiThieuTemplates = [
            'Có hơn %d năm kinh nghiệm trong lĩnh vực %s. Đã tham gia nhiều dự án lớn và có nhiều công trình nghiên cứu được công bố quốc tế.',
            'Chuyên gia hàng đầu về %s với hơn %d năm kinh nghiệm. Tốt nghiệp %s, đã có nhiều đóng góp quan trọng trong ngành.',
            'Nhà nghiên cứu xuất sắc trong lĩnh vực %s. Đã có %d công trình nghiên cứu được công bố trên các tạp chí uy tín quốc tế.',
            'Tốt nghiệp %s, có %d năm kinh nghiệm trong lĩnh vực %s. Đã tham gia và dẫn dắt nhiều dự án lớn.',
            'Chuyên gia tư vấn về %s với kinh nghiệm %d năm. Đã giúp nhiều tổ chức cải thiện hiệu quả hoạt động.'
        ];
        
        // Các lĩnh vực chuyên môn
        $linhVucs = [
            'công nghệ thông tin', 'trí tuệ nhân tạo', 'khoa học dữ liệu', 'an ninh mạng',
            'blockchain', 'IoT', 'điện toán đám mây', 'phát triển phần mềm', 'tự động hóa',
            'quản lý dự án', 'tiếp thị số', 'thương mại điện tử', 'tài chính', 'giáo dục',
            'y tế', 'năng lượng tái tạo', 'môi trường', 'công nghệ sinh học'
        ];
        
        // Các trường đại học
        $truongDH = [
            'Đại học Harvard', 'Đại học Stanford', 'Đại học Oxford', 'Đại học Cambridge',
            'MIT', 'Đại học Quốc gia Singapore', 'Đại học Tokyo', 'Đại học Tsinghua',
            'Đại học Quốc gia Hà Nội', 'Đại học Bách Khoa Hà Nội'
        ];
        
        echo "Bắt đầu tạo $totalDienGia bản ghi diễn giả...\n";
        
        // Tạo diễn giả theo từng lô
        for ($batch = 0; $batch < ceil($totalDienGia / $batchSize); $batch++) {
            $data = [];
            $startIdx = $batch * $batchSize + 1;
            $endIdx = min(($batch + 1) * $batchSize, $totalDienGia);
            
            for ($i = $startIdx; $i <= $endIdx; $i++) {
                // Tạo các giá trị mẫu
                $linhVuc = $linhVucs[array_rand($linhVucs)];
                $namKinhNghiem = rand(5, 30);
                $truong = $truongDH[array_rand($truongDH)];
                
                // Tạo giới thiệu
                $gioiThieuTemplate = $gioiThieuTemplates[array_rand($gioiThieuTemplates)];
                if (strpos($gioiThieuTemplate, '%s') !== false && strpos($gioiThieuTemplate, '%d') !== false) {
                    if (substr_count($gioiThieuTemplate, '%s') == 1 && substr_count($gioiThieuTemplate, '%d') == 1) {
                        $gioiThieu = sprintf($gioiThieuTemplate, $namKinhNghiem, $linhVuc);
                    } elseif (substr_count($gioiThieuTemplate, '%s') == 2 && substr_count($gioiThieuTemplate, '%d') == 1) {
                        $gioiThieu = sprintf($gioiThieuTemplate, $linhVuc, $namKinhNghiem, $linhVuc);
                    } else {
                        $gioiThieu = sprintf($gioiThieuTemplate, $truong, $namKinhNghiem, $linhVuc);
                    }
                } else {
                    $gioiThieu = "Chuyên gia trong lĩnh vực $linhVuc với $namKinhNghiem năm kinh nghiệm.";
                }
                
                // Tạo ngày tạo ngẫu nhiên trong 6 tháng gần đây
                $randomDays = rand(0, 180);
                $createdAt = Time::now()->subDays($randomDays);
                
                // Tạo bản ghi
                $data[] = [
                    'ten_dien_gia' => 'Diễn giả ' . $i,
                    'chuc_danh' => $chucDanhs[array_rand($chucDanhs)],
                    'to_chuc' => $toChuc[array_rand($toChuc)],
                    'gioi_thieu' => $gioiThieu,
                    'avatar' => 'diengia_' . $i . '.jpg',
                    'thu_tu' => $i,
                    'status' => 1,
                    'created_at' => $createdAt->toDateTimeString(),
                    'updated_at' => $createdAt->toDateTimeString(),
                    'deleted_at' => null
                ];
            }

            // Thêm dữ liệu vào bảng dien_gia theo lô
            $this->db->table('dien_gia')->insertBatch($data);
            
            echo "Đã tạo " . count($data) . " bản ghi (từ $startIdx đến $endIdx)...\n";
        }
        
        echo "Seeder DienGiaSeeder đã được chạy thành công! Đã tạo $totalDienGia diễn giả mẫu.\n";
    }
} 