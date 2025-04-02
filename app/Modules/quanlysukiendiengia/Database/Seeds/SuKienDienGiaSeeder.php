<?php

namespace App\Modules\sukiendiengia\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SuKienDienGiaSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách sự kiện từ bảng su_kien (giả định có ít nhất 3 sự kiện)
        $suKienList = $this->db->table('su_kien')->limit(3)->get()->getResultArray();
        
        // Lấy danh sách diễn giả từ bảng dien_gia (giả định có ít nhất 10 diễn giả)
        $dienGiaList = $this->db->table('dien_gia')->limit(10)->get()->getResultArray();
        
        if (empty($suKienList) || empty($dienGiaList)) {
            echo "Cần phải có dữ liệu trong bảng su_kien và dien_gia trước khi chạy seeder này.\n";
            return;
        }
        
        $data = [];
        $now = Time::now();
        
        // Tạo dữ liệu mẫu cho mối quan hệ giữa sự kiện và diễn giả
        foreach ($suKienList as $suKienIndex => $suKien) {
            $suKienId = $suKien['su_kien_id'];
            
            // Mỗi sự kiện có 3-5 diễn giả
            $numDienGia = rand(3, 5);
            $dienGiaForEvent = array_slice($dienGiaList, 0, $numDienGia);
            
            // Thời gian cơ sở cho sự kiện, giả định sự kiện diễn ra trong 1 ngày
            $baseTime = new Time($now);
            $baseTime->addDays($suKienIndex); // Mỗi sự kiện cách nhau 1 ngày
            $baseTime->setHour(9)->setMinute(0)->setSecond(0); // Bắt đầu lúc 9h sáng
            
            foreach ($dienGiaForEvent as $index => $dienGia) {
                // Thời gian trình bày (mỗi diễn giả cách nhau 1 giờ)
                $startTime = clone $baseTime;
                $startTime->addHours($index);
                
                // Thời lượng trình bày (30-60 phút)
                $duration = rand(30, 60);
                
                // Thời gian kết thúc
                $endTime = clone $startTime;
                $endTime->addMinutes($duration);
                
                // Vai trò cho diễn giả
                $vaiTro = $this->getRandomVaiTro($index);
                
                // Trạng thái tham gia
                $trangThaiOptions = ['xac_nhan', 'cho_xac_nhan', 'tu_choi', 'khong_lien_he_duoc'];
                $trangThai = $trangThaiOptions[array_rand($trangThaiOptions)];
                
                $data[] = [
                    'su_kien_id' => $suKienId,
                    'dien_gia_id' => $dienGia['dien_gia_id'],
                    'thu_tu' => $index + 1,
                    'vai_tro' => $vaiTro,
                    'mo_ta' => 'Mô tả chi tiết về vai trò và nội dung trình bày của diễn giả ' . $dienGia['ten_dien_gia'] . ' trong sự kiện.',
                    'thoi_gian_trinh_bay' => $startTime->toDateTimeString(),
                    'thoi_gian_ket_thuc' => $endTime->toDateTimeString(),
                    'thoi_luong_phut' => $duration,
                    'tieu_de_trinh_bay' => 'Bài trình bày của ' . $dienGia['ten_dien_gia'],
                    'tai_lieu_dinh_kem' => json_encode([
                        [
                            'ten_file' => 'slide_' . strtolower(str_replace(' ', '_', $dienGia['ten_dien_gia'])) . '.pdf',
                            'duong_dan' => '/uploads/tai_lieu/' . strtolower(str_replace(' ', '_', $dienGia['ten_dien_gia'])) . '_' . rand(1000, 9999) . '.pdf',
                            'mo_ta' => 'Slide bài trình bày',
                            'kich_thuoc' => rand(1, 10) . ' MB'
                        ]
                    ]),
                    'trang_thai_tham_gia' => $trangThai,
                    'hien_thi_cong_khai' => rand(0, 1),
                    'ghi_chu' => 'Ghi chú về diễn giả ' . $dienGia['ten_dien_gia'] . ' trong sự kiện này.',
                    'created_at' => $now->toDateTimeString(),
                    'updated_at' => $now->toDateTimeString()
                ];
            }
        }
        
        // Thêm dữ liệu vào bảng su_kien_dien_gia
        if (!empty($data)) {
            // Xóa dữ liệu cũ (nếu có)
            $this->db->table('su_kien_dien_gia')->emptyTable();
            
            // Thêm dữ liệu mới
            $this->db->table('su_kien_dien_gia')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi mối quan hệ giữa sự kiện và diễn giả.\n";
        }
        
        echo "Seeder SuKienDienGiaSeeder đã được chạy thành công!\n";
    }
    
    /**
     * Lấy vai trò ngẫu nhiên cho diễn giả
     * 
     * @param int $index Thứ tự của diễn giả trong sự kiện
     * @return string
     */
    private function getRandomVaiTro($index)
    {
        $vaiTroList = [
            'Chủ tọa',
            'Diễn giả chính',
            'Người thuyết trình',
            'Khách mời đặc biệt',
            'Chuyên gia tham luận',
            'Điều phối viên',
            'Đại biểu',
            'Thành viên tham luận'
        ];
        
        // Diễn giả đầu tiên thường là chủ tọa
        if ($index === 0) {
            return 'Chủ tọa';
        }
        
        // Diễn giả thứ hai thường là diễn giả chính
        if ($index === 1) {
            return 'Diễn giả chính';
        }
        
        // Các diễn giả còn lại lấy ngẫu nhiên
        return $vaiTroList[array_rand(array_slice($vaiTroList, 2))];
    }
} 