<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DangKySuKienSeeder extends Seeder
{
    public function run()
    {
        // Check if events exist before proceeding
        $suKienCount = $this->db->table('su_kien')->where('deleted_at IS NULL')->countAllResults();
        if ($suKienCount <= 0) {
            echo "No events found. Run SuKienSeeder first.\n";
            return;
        }
        
        // Retrieve event data
        $suKienList = $this->db->table('su_kien')
            ->where('deleted_at IS NULL')
            ->limit(10) // Limit to avoid too many records
            ->get()
            ->getResultArray();
        
        // Check if check-in and check-out tables have been populated
        $checkinExists = $this->db->tableExists('checkin_sukien');
        $checkoutExists = $this->db->tableExists('checkout_sukien');
        
        // Get check-in and check-out data if available
        $checkinList = $checkinExists ? $this->db->table('checkin_sukien')->get()->getResultArray() : [];
        $checkoutList = $checkoutExists ? $this->db->table('checkout_sukien')->get()->getResultArray() : [];
        
        // Empty table before insertion
        $this->db->table('dangky_sukien')->emptyTable();
        
        $data = [];
        $now = Time::now();
        
        // Generate 50 event registrations
        for ($i = 0; $i < 50; $i++) {
            // Select random event
            $suKien = $suKienList[array_rand($suKienList)];
            $suKienId = $suKien['su_kien_id'];
            
            // Generate attendee information
            $firstName = $this->getRandomFirstName();
            $lastName = $this->getRandomLastName();
            $hoTen = $lastName . ' ' . $firstName;
            $email = strtolower(str_replace(' ', '', $firstName)) . '.' . strtolower(str_replace(' ', '', $lastName)) . rand(100, 999) . '@example.com';
            
            // Registration status: -1 (cancelled), 0 (pending), 1 (confirmed)
            $status = [1, 1, 1, 0, -1][array_rand([1, 1, 1, 0, -1])]; // 60% confirmed, 20% pending, 20% cancelled
            
            // Check-in status based on registration status
            $daCheckIn = ($status == 1) ? (rand(0, 10) > 3) : false; // 70% chance of check-in for confirmed registrations
            $daCheckOut = $daCheckIn ? (rand(0, 10) > 3) : false; // 70% chance of check-out for checked-in attendees
            
            // Registration timing
            $ngayDangKy = clone $now;
            $ngayDangKy->subDays(rand(5, 20));
            
            // Confirmation or cancellation timing
            $thoiGianDuyet = ($status == 1) ? $ngayDangKy->addDays(rand(1, 3))->toDateTimeString() : null;
            $thoiGianHuy = ($status == -1) ? $ngayDangKy->addDays(rand(1, 5))->toDateTimeString() : null;
            $lyDoHuy = ($status == -1) ? $this->getRandomLyDoHuy() : null;
            
            // Attendance information
            $attendanceStatus = 'not_attended';
            $attendanceMinutes = 0;
            
            if ($daCheckIn) {
                if ($daCheckOut) {
                    $attendanceStatus = rand(0, 1) == 1 ? 'full' : 'partial';
                    $attendanceMinutes = ($attendanceStatus == 'full') ? rand(90, 180) : rand(30, 89);
                } else {
                    $attendanceStatus = 'partial';
                    $attendanceMinutes = rand(10, 60);
                }
            }
            
            // Participation method
            $hinhThucThamGia = ['offline', 'online', 'hybrid'][array_rand(['offline', 'online', 'hybrid'])];
            
            // Check-in method
            $diemDanhBang = 'none';
            if ($daCheckIn) {
                $diemDanhBang = ['qr_code', 'face_id', 'manual'][array_rand(['qr_code', 'face_id', 'manual'])];
            }
            
            // Additional information
            $thongTinDangKy = json_encode([
                'dietary_restrictions' => rand(0, 1) == 1 ? ['Không', 'Ăn chay', 'Dị ứng'][array_rand(['Không', 'Ăn chay', 'Dị ứng'])] : null,
                'special_needs' => rand(0, 1) == 1 ? $this->getRandomSpecialNeeds() : null,
                'accommodation_needed' => rand(0, 1) == 1
            ]);
            
            // Link with check-in and check-out if appropriate
            $checkinSukienId = null;
            $checkoutSukienId = null;
            
            // If this is a checked-in registration, and we have check-ins available, link to a random one
            if ($daCheckIn && !empty($checkinList)) {
                $randomCheckin = $checkinList[array_rand($checkinList)];
                $checkinSukienId = $randomCheckin['checkin_sukien_id'];
                
                // If this is also checked-out and we have check-outs available, link to a random one
                if ($daCheckOut && !empty($checkoutList)) {
                    $randomCheckout = $checkoutList[array_rand($checkoutList)];
                    $checkoutSukienId = $randomCheckout['checkout_sukien_id'];
                }
            }
            
            $data[] = [
                'su_kien_id' => $suKienId,
                'email' => $email,
                'ho_ten' => $hoTen,
                'dien_thoai' => '0' . rand(900000000, 999999999),
                'loai_nguoi_dang_ky' => ['khach', 'sinh_vien', 'giang_vien'][array_rand(['khach', 'sinh_vien', 'giang_vien'])],
                'ngay_dang_ky' => $ngayDangKy->toDateTimeString(),
                'ma_xac_nhan' => strtoupper(substr(md5(uniqid()), 0, 8)),
                'status' => $status,
                'noi_dung_gop_y' => rand(0, 1) == 1 ? $this->getRandomNoiDungGopY() : null,
                'nguon_gioi_thieu' => $this->getRandomNguonGioiThieu(),
                'don_vi_to_chuc' => rand(0, 1) == 1 ? $this->getRandomDonViToChuc() : null,
                'face_image_path' => $daCheckIn && $diemDanhBang == 'face_id' ? '/uploads/faces/face_' . $i . '.jpg' : null,
                'face_verified' => $daCheckIn && $diemDanhBang == 'face_id',
                'da_check_in' => $daCheckIn,
                'da_check_out' => $daCheckOut,
                'checkin_sukien_id' => $checkinSukienId,
                'checkout_sukien_id' => $checkoutSukienId,
                'thoi_gian_duyet' => $thoiGianDuyet,
                'thoi_gian_huy' => $thoiGianHuy,
                'ly_do_huy' => $lyDoHuy,
                'hinh_thuc_tham_gia' => $hinhThucThamGia,
                'attendance_status' => $attendanceStatus,
                'attendance_minutes' => $attendanceMinutes,
                'diem_danh_bang' => $diemDanhBang,
                'thong_tin_dang_ky' => $thongTinDangKy,
                'ly_do_tham_du' => $this->getRandomLyDoThamDu(),
                'created_at' => $ngayDangKy->toDateTimeString(),
                'updated_at' => $now->subDays(rand(1, 4))->toDateTimeString(),
                'deleted_at' => null
            ];
        }
        
        // Insert the data
        if (!empty($data)) {
            try {
                $this->db->table('dangky_sukien')->insertBatch($data);
                echo "Created " . count($data) . " event registration records.\n";
            } catch (\Exception $e) {
                echo "Error adding data: " . $e->getMessage() . "\n";
            }
        }
        
        echo "DangKySuKienSeeder completed successfully!\n";
    }
    
    // Helper methods
    private function getRandomFirstName() {
        $names = ['An', 'Bình', 'Cường', 'Dũng', 'Hà', 'Hải', 'Hiếu', 'Hoàng', 'Hùng', 'Hương', 'Lan', 'Linh', 'Mai', 'Minh', 'Nam', 'Nga', 'Phương', 'Quân', 'Thành', 'Thảo'];
        return $names[array_rand($names)];
    }
    
    private function getRandomLastName() {
        $names = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương'];
        return $names[array_rand($names)];
    }
    
    private function getRandomSpecialNeeds() {
        $needs = [
            'Cần hỗ trợ người khuyết tật',
            'Cần phiên dịch ngôn ngữ',
            'Cần chỗ đỗ xe gần địa điểm',
            'Cần hỗ trợ đi lại',
            'Cần chế độ ăn đặc biệt'
        ];
        return $needs[array_rand($needs)];
    }
    
    private function getRandomNguonGioiThieu() {
        $nguon = ['Facebook', 'Website trường', 'Email', 'Bạn bè', 'Giảng viên', 'Poster', 'Zalo', 'LinkedIn', 'Báo chí', 'Khác'];
        return $nguon[array_rand($nguon)];
    }
    
    private function getRandomDonViToChuc() {
        $donVi = ['Công ty ABC', 'Tập đoàn XYZ', 'Viện nghiên cứu DEF', 'Tổ chức MNO', 'Trường đại học UVW', 'Cơ quan nhà nước', 'Freelancer', 'Doanh nghiệp tư nhân', 'Startup', 'NGO'];
        return $donVi[array_rand($donVi)];
    }
    
    private function getRandomLyDoHuy() {
        $lyDo = [
            'Bận lịch công việc đột xuất',
            'Vấn đề sức khỏe',
            'Trùng lịch với sự kiện khác',
            'Không thể tham gia vì lý do cá nhân',
            'Đăng ký nhầm sự kiện',
            'Không thể di chuyển đến địa điểm',
            'Phải đi công tác',
            'Lịch học thay đổi'
        ];
        return $lyDo[array_rand($lyDo)];
    }
    
    private function getRandomNoiDungGopY() {
        $gopY = [
            'Cần cung cấp thông tin chi tiết hơn về sự kiện',
            'Nên gửi tài liệu trước cho người tham dự',
            'Thời gian tổ chức phù hợp hơn vào cuối tuần',
            'Cần thêm thông tin về diễn giả',
            'Mong muốn có phiên hỏi đáp dài hơn',
            'Nên có hình thức tham gia trực tuyến',
            'Cần có thông tin về chỗ để xe',
            'Mong muốn nhận được ghi nhận tham dự chính thức'
        ];
        return $gopY[array_rand($gopY)];
    }
    
    private function getRandomLyDoThamDu() {
        $lyDo = [
            'Muốn cập nhật kiến thức mới trong lĩnh vực',
            'Quan tâm đến chủ đề của sự kiện',
            'Muốn gặp gỡ chuyên gia trong ngành',
            'Phục vụ cho nghiên cứu cá nhân',
            'Cần tìm hiểu cơ hội việc làm/hợp tác',
            'Giảng viên giới thiệu tham dự',
            'Đang làm đồ án/luận văn liên quan đến chủ đề',
            'Muốn mở rộng mạng lưới quan hệ'
        ];
        return $lyDo[array_rand($lyDo)];
    }
}