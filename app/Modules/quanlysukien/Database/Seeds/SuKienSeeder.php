<?php

namespace App\Modules\sukien\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SuKienSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách loại sự kiện từ bảng loai_su_kien
        $loaiSuKienList = $this->db->table('loai_su_kien')->limit(5)->get()->getResultArray();
        
        if (empty($loaiSuKienList)) {
            echo "Cần phải có dữ liệu trong bảng loai_su_kien trước khi chạy seeder này.\n";
            return;
        }
        
        $data = [];
        $now = Time::now();
        
        // Các địa điểm mẫu cho sự kiện
        $diaDiemList = [
            'Trung tâm Hội nghị Quốc tế',
            'Khách sạn Grand Plaza',
            'Đại học Quốc gia Hà Nội',
            'Cung Văn hóa Hữu nghị Việt Xô',
            'Trung tâm Hội nghị White Palace',
            'Nhà Văn hóa Thanh niên',
            'Nhà Văn hóa Lao động',
            'Trung tâm Triển lãm và Hội nghị Sài Gòn',
            'Trung tâm Hội chợ và Triển lãm Tân Bình',
        ];
        
        // Tạo dữ liệu mẫu cho 10 sự kiện
        for ($i = 1; $i <= 10; $i++) {
            // Chọn ngẫu nhiên loại sự kiện
            $loaiSuKien = $loaiSuKienList[array_rand($loaiSuKienList)];
            
            // Thời gian bắt đầu và kết thúc
            $startDate = clone $now;
            $startDate->addDays(rand(-30, 60)); // Sự kiện trong khoảng -30 đến 60 ngày so với hiện tại
            $startDate->setHour(rand(8, 18))->setMinute(0)->setSecond(0);
            
            $endDate = clone $startDate;
            $endDate->addHours(rand(2, 8)); // Sự kiện kéo dài từ 2 đến 8 giờ
            
            // Thời gian đăng ký
            $registerStart = clone $startDate;
            $registerStart->subDays(rand(14, 30)); // Bắt đầu đăng ký trước 14-30 ngày
            
            $registerEnd = clone $startDate;
            $registerEnd->subDays(rand(1, 5)); // Kết thúc đăng ký trước 1-5 ngày
            
            $cancelDeadline = clone $startDate;
            $cancelDeadline->subDays(rand(1, 3)); // Hạn hủy đăng ký trước 1-3 ngày
            
            // Chọn ngẫu nhiên địa điểm
            $diaDiem = $diaDiemList[array_rand($diaDiemList)];
            
            // Chọn ngẫu nhiên hình thức
            $hinhThucList = ['offline', 'online', 'hybrid'];
            $hinhThuc = $hinhThucList[array_rand($hinhThucList)];
            
            // Poster mẫu
            $poster = [
                'original' => 'uploads/su_kien/poster_original_' . $i . '.jpg',
                'thumbnail' => 'uploads/su_kien/poster_thumb_' . $i . '.jpg',
                'alt_text' => 'Poster sự kiện ' . $i
            ];
            
            // Lịch trình mẫu
            $lichTrinh = [];
            $numSessions = rand(3, 8);
            $sessionTime = clone $startDate;
            
            for ($j = 1; $j <= $numSessions; $j++) {
                $sessionDuration = rand(30, 90); // 30-90 phút mỗi phiên
                
                $sessionEnd = clone $sessionTime;
                $sessionEnd->addMinutes($sessionDuration);
                
                $lichTrinh[] = [
                    'tieu_de' => 'Phiên ' . $j . ': ' . $this->getRandomSessionTitle(),
                    'mo_ta' => 'Mô tả chi tiết cho phiên ' . $j,
                    'thoi_gian_bat_dau' => $sessionTime->toDateTimeString(),
                    'thoi_gian_ket_thuc' => $sessionEnd->toDateTimeString(),
                    'nguoi_phu_trach' => 'Người phụ trách ' . $j
                ];
                
                // Cập nhật thời gian cho phiên tiếp theo, thêm 15 phút nghỉ giữa các phiên
                $sessionTime = clone $sessionEnd;
                $sessionTime->addMinutes(15);
            }
            
            $slug = 'su-kien-' . $i . '-' . strtolower(str_replace(' ', '-', substr('Ten su kien mau ' . $i, 0, 20)));
            
            $data[] = [
                'ten_su_kien' => 'Sự kiện mẫu ' . $i . ': ' . $this->getRandomEventName(),
                'su_kien_poster' => json_encode($poster),
                'mo_ta' => 'Mô tả ngắn gọn về sự kiện mẫu số ' . $i,
                'mo_ta_su_kien' => 'Mô tả chi tiết về sự kiện mẫu số ' . $i . '. Đây là sự kiện được tạo tự động để làm dữ liệu mẫu cho hệ thống.',
                'chi_tiet_su_kien' => '<h2>Chi tiết sự kiện</h2><p>Sự kiện này được tổ chức nhằm mục đích chia sẻ kiến thức và kết nối cộng đồng.</p><ul><li>Nội dung 1</li><li>Nội dung 2</li><li>Nội dung 3</li></ul>',
                'thoi_gian_bat_dau' => $startDate->toDateTimeString(),
                'thoi_gian_ket_thuc' => $endDate->toDateTimeString(),
                'dia_diem' => $diaDiem,
                'dia_chi_cu_the' => 'Số ' . rand(1, 100) . ', Đường ' . $this->getRandomStreetName() . ', Thành phố ' . $this->getRandomCity(),
                'toa_do_gps' => rand(10, 11) . '.' . rand(100000, 999999) . ',' . rand(106, 107) . '.' . rand(100000, 999999),
                'loai_su_kien_id' => $loaiSuKien['loai_su_kien_id'],
                'ma_qr_code' => 'QR_EVENT_' . $i . '_' . rand(10000, 99999),
                'status' => rand(0, 1),
                'tong_dang_ky' => rand(10, 200),
                'tong_check_in' => rand(5, 150),
                'tong_check_out' => rand(0, 100),
                'cho_phep_check_in' => rand(0, 1),
                'cho_phep_check_out' => rand(0, 1),
                'yeu_cau_face_id' => rand(0, 1),
                'cho_phep_checkin_thu_cong' => rand(0, 1),
                'bat_dau_dang_ky' => $registerStart->toDateTimeString(),
                'ket_thuc_dang_ky' => $registerEnd->toDateTimeString(),
                'han_huy_dang_ky' => $cancelDeadline->toDateTimeString(),
                'gio_bat_dau' => $startDate->toDateTimeString(),
                'gio_ket_thuc' => $endDate->toDateTimeString(),
                'so_luong_tham_gia' => rand(50, 500),
                'so_luong_dien_gia' => rand(1, 10),
                'gioi_han_loai_nguoi_dung' => rand(0, 1) ? 'all' : 'member,vip',
                'tu_khoa_su_kien' => 'sự kiện, mẫu, test, ' . $loaiSuKien['ten_loai_su_kien'],
                'hashtag' => '#SuKien' . $i . ' #Event' . $i,
                'slug' => $slug,
                'so_luot_xem' => rand(50, 5000),
                'lich_trinh' => json_encode($lichTrinh),
                'hinh_thuc' => $hinhThuc,
                'link_online' => $hinhThuc != 'offline' ? 'https://meet.example.com/event-' . $i : null,
                'mat_khau_online' => $hinhThuc != 'offline' ? 'password' . rand(100, 999) : null,
                'version' => 1,
                'created_at' => $now->subDays(rand(5, 60))->toDateTimeString(),
                'updated_at' => $now->toDateTimeString()
            ];
        }
        
        // Thêm dữ liệu vào bảng su_kien
        if (!empty($data)) {
            // Xóa dữ liệu cũ (nếu cần)
            // $this->db->table('su_kien')->emptyTable();
            
            // Thêm dữ liệu mới
            $this->db->table('su_kien')->insertBatch($data);
            echo "Đã tạo thành công " . count($data) . " bản ghi sự kiện mẫu.\n";
        }
        
        echo "Seeder SuKienSeeder đã được chạy thành công!\n";
    }
    
    /**
     * Lấy tên sự kiện ngẫu nhiên
     * 
     * @return string
     */
    private function getRandomEventName()
    {
        $eventNames = [
            'Hội thảo Chuyển đổi số',
            'Triển lãm Công nghệ 4.0',
            'Workshop AI và Machine Learning',
            'Ngày hội Việc làm IT',
            'Tọa đàm Khởi nghiệp Công nghệ',
            'Tech Summit',
            'Digital Marketing Conference',
            'Blockchain và NFT Expo',
            'Fintech Forum',
            'Smart City Conference',
            'Data Science Workshop',
            'Game Developer Meetup',
            'E-commerce Summit',
            'Cyber Security Conference',
            'Innovation Day'
        ];
        
        return $eventNames[array_rand($eventNames)];
    }
    
    /**
     * Lấy tên đường ngẫu nhiên
     * 
     * @return string
     */
    private function getRandomStreetName()
    {
        $streetNames = [
            'Nguyễn Huệ', 'Lê Lợi', 'Trần Hưng Đạo', 'Phan Đình Phùng',
            'Lê Thánh Tôn', 'Võ Văn Tần', 'Đinh Tiên Hoàng', 'Nguyễn Thị Minh Khai',
            'Trần Phú', 'Cách Mạng Tháng Tám', 'Lý Tự Trọng', 'Phạm Ngọc Thạch',
            'Huỳnh Thúc Kháng', 'Nguyễn Văn Linh', 'Võ Nguyên Giáp', 'Lê Duẩn'
        ];
        
        return $streetNames[array_rand($streetNames)];
    }
    
    /**
     * Lấy tên thành phố ngẫu nhiên
     * 
     * @return string
     */
    private function getRandomCity()
    {
        $cities = [
            'Hà Nội', 'Hồ Chí Minh', 'Đà Nẵng', 'Hải Phòng', 'Cần Thơ',
            'Nha Trang', 'Đà Lạt', 'Vũng Tàu', 'Huế', 'Vinh', 'Quy Nhơn'
        ];
        
        return $cities[array_rand($cities)];
    }
    
    /**
     * Lấy tiêu đề phiên họp ngẫu nhiên
     * 
     * @return string
     */
    private function getRandomSessionTitle()
    {
        $sessionTitles = [
            'Giới thiệu và Khai mạc',
            'Phát biểu Định hướng',
            'Chuyên đề Chuyển đổi Số',
            'Tương lai của AI trong Doanh nghiệp',
            'Quản trị Dữ liệu trong Kỷ nguyên Số',
            'Bảo mật Thông tin trong Thời đại 4.0',
            'Tăng trưởng Doanh nghiệp với E-commerce',
            'Tối ưu hoá Marketing Online',
            'Phát triển Nhân sự trong Kỷ nguyên Số',
            'Đổi mới Sáng tạo và Ứng dụng',
            'Hỏi đáp và Thảo luận',
            'Tổng kết và Bế mạc'
        ];
        
        return $sessionTitles[array_rand($sessionTitles)];
    }
} 