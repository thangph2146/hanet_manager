<?php

namespace App\Modules\checkinsukien\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CheckInSuKienSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách sự kiện từ bảng su_kien (giả định có ít nhất 3 sự kiện)
        $suKienList = $this->db->table('su_kien')->limit(5)->get()->getResultArray();
        
        if (empty($suKienList)) {
            echo "Cần phải có dữ liệu trong bảng su_kien trước khi chạy seeder này.\n";
            return;
        }
        
        // Lấy danh sách đăng ký sự kiện từ bảng dangky_sukien
        $dangKyList = $this->db->table('dangky_sukien')->limit(30)->get()->getResultArray();
        
        $data = [];
        $now = Time::now();
        
        // Tạo 50 bản ghi check-in sự kiện mẫu
        for ($i = 0; $i < 50; $i++) {
            // Chọn ngẫu nhiên một sự kiện
            $suKien = $suKienList[array_rand($suKienList)];
            $suKienId = $suKien['su_kien_id'];
            
            // Xác định xem có sử dụng đăng ký sẵn có hay tạo dữ liệu mới
            $useDangKy = !empty($dangKyList) && rand(0, 1) == 1;
            
            if ($useDangKy) {
                // Sử dụng dữ liệu từ đăng ký có sẵn
                $dangKy = $dangKyList[array_rand($dangKyList)];
                $hoTen = $dangKy['ho_ten'];
                $email = $dangKy['email'];
                $dangKySukienId = $dangKy['dangky_sukien_id'];
            } else {
                // Tạo thông tin người check-in mới
                $firstName = $this->getRandomFirstName();
                $lastName = $this->getRandomLastName();
                $hoTen = $lastName . ' ' . $firstName;
                $email = strtolower(str_replace(' ', '', $firstName)) . '.' . strtolower(str_replace(' ', '', $lastName)) . rand(100, 999) . '@example.com';
                $dangKySukienId = null;
            }
            
            // Loại check-in
            $checkinTypes = ['face_id', 'manual', 'qr_code', 'online'];
            $checkinType = $checkinTypes[array_rand($checkinTypes)];
            
            // Thời gian check-in
            $thoiGianCheckIn = $now->subMinutes(rand(5, 300))->toDateTimeString();
            
            // Dữ liệu khuôn mặt (nếu là face_id)
            $faceImagePath = null;
            $faceMatchScore = null;
            $faceVerified = false;
            
            if ($checkinType === 'face_id') {
                $faceImagePath = '/uploads/checkin/face_' . $i . '.jpg';
                $faceMatchScore = rand(70, 100) / 100; // 0.7 - 1.0
                $faceVerified = $faceMatchScore >= 0.8;
            }
            
            // Mã xác nhận (nếu là qr_code)
            $maXacNhan = $checkinType === 'qr_code' ? strtoupper(substr(md5(uniqid()), 0, 8)) : null;
            
            // Thông tin thiết bị và vị trí
            $deviceInfoList = [
                'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X)',
                'Mozilla/5.0 (Android 10; Mobile)',
                'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
                'Mozilla/5.0 (iPad; CPU OS 14_4 like Mac OS X)'
            ];
            
            $deviceInfo = $deviceInfoList[array_rand($deviceInfoList)];
            
            $locationData = json_encode([
                'latitude' => rand(10500000, 10600000) / 1000000,
                'longitude' => rand(106600000, 106800000) / 1000000,
                'accuracy' => rand(5, 50)
            ]);
            
            // Hình thức tham gia
            $hinhThucThamGia = ['offline', 'online'][array_rand(['offline', 'online'])];
            
            // IP address
            $ipAddress = rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
            
            // Thông tin bổ sung
            $thongTinBoSung = json_encode([
                'browser' => ['Chrome', 'Firefox', 'Safari', 'Edge'][array_rand(['Chrome', 'Firefox', 'Safari', 'Edge'])],
                'os' => ['Windows', 'macOS', 'iOS', 'Android', 'Linux'][array_rand(['Windows', 'macOS', 'iOS', 'Android', 'Linux'])],
                'screen_resolution' => ['1920x1080', '1366x768', '2560x1440', '1440x900'][array_rand(['1920x1080', '1366x768', '2560x1440', '1440x900'])]
            ]);
            
            // Ghi chú (nếu có)
            $ghiChu = rand(0, 3) == 0 ? $this->getRandomGhiChu() : null;
            
            // Trạng thái
            $status = rand(0, 10) > 2 ? 1 : (rand(0, 1) ? 0 : 2); // 80% hoạt động, 10% vô hiệu, 10% đang xử lý
            
            $data[] = [
                'su_kien_id' => $suKienId,
                'email' => $email,
                'ho_ten' => $hoTen,
                'dangky_sukien_id' => $dangKySukienId,
                'thoi_gian_check_in' => $thoiGianCheckIn,
                'checkin_type' => $checkinType,
                'face_image_path' => $faceImagePath,
                'face_match_score' => $faceMatchScore,
                'face_verified' => $faceVerified,
                'ma_xac_nhan' => $maXacNhan,
                'status' => $status,
                'location_data' => $locationData,
                'device_info' => $deviceInfo,
                'hinh_thuc_tham_gia' => $hinhThucThamGia,
                'ip_address' => $ipAddress,
                'thong_tin_bo_sung' => $thongTinBoSung,
                'ghi_chu' => $ghiChu,
                'created_at' => $thoiGianCheckIn,
                'updated_at' => $now->toDateTimeString()
            ];
        }
        
        // Thêm dữ liệu vào bảng checkin_sukien
        if (!empty($data)) {
            // Xóa dữ liệu cũ (nếu có)
            $this->db->table('checkin_sukien')->emptyTable();
            
            // Thêm dữ liệu mới
            $this->db->table('checkin_sukien')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi check-in sự kiện.\n";
        }
        
        echo "Seeder CheckInSuKienSeeder đã được chạy thành công!\n";
    }
    
    // Các hàm helper để tạo dữ liệu ngẫu nhiên
    private function getRandomFirstName() {
        $names = ['An', 'Bình', 'Cường', 'Dũng', 'Hà', 'Hải', 'Hiếu', 'Hoàng', 'Hùng', 'Hương', 'Lan', 'Linh', 'Mai', 'Minh', 'Nam', 'Nga', 'Phương', 'Quân', 'Thành', 'Thảo', 'Trang', 'Trung', 'Tuấn', 'Uyên', 'Việt'];
        return $names[array_rand($names)];
    }
    
    private function getRandomLastName() {
        $names = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô', 'Dương', 'Lý'];
        return $names[array_rand($names)];
    }
    
    private function getRandomGhiChu() {
        $ghiChu = [
            'Người tham dự đến sớm 30 phút',
            'Đã kiểm tra thông tin đăng ký',
            'Không mang theo thẻ sinh viên/nhân viên',
            'Cần hỗ trợ thêm về chỗ ngồi',
            'Đã cấp tài liệu sự kiện',
            'Yêu cầu vị trí gần lối ra',
            'Đăng ký tham gia phiên hỏi đáp',
            'Có nhu cầu gặp diễn giả sau sự kiện'
        ];
        return $ghiChu[array_rand($ghiChu)];
    }
} 