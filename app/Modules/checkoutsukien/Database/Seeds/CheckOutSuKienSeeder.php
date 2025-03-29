<?php

namespace App\Modules\checkoutsukien\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CheckOutSuKienSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách sự kiện từ bảng su_kien
        $suKienList = $this->db->table('su_kien')->limit(5)->get()->getResultArray();
        
        if (empty($suKienList)) {
            echo "Cần phải có dữ liệu trong bảng su_kien trước khi chạy seeder này.\n";
            return;
        }
        
        // Lấy danh sách đăng ký sự kiện từ bảng dangky_sukien
        $dangKyList = $this->db->table('dangky_sukien')->limit(30)->get()->getResultArray();
        
        // Lấy danh sách check-in sự kiện từ bảng checkin_sukien
        $checkInList = $this->db->table('checkin_sukien')->limit(40)->get()->getResultArray();
        
        if (empty($checkInList)) {
            echo "Bạn nên chạy CheckInSuKienSeeder trước khi chạy seeder này để có dữ liệu check-in.\n";
        }
        
        $data = [];
        $now = Time::now();
        
        // Tạo 50 bản ghi check-out sự kiện mẫu
        for ($i = 0; $i < 50; $i++) {
            // Xác định xem có sử dụng dữ liệu check-in có sẵn hay không
            $useCheckIn = !empty($checkInList) && rand(0, 1) == 1;
            
            if ($useCheckIn) {
                // Sử dụng dữ liệu từ check-in có sẵn
                $checkIn = $checkInList[array_rand($checkInList)];
                $suKienId = $checkIn['su_kien_id'];
                $hoTen = $checkIn['ho_ten'];
                $email = $checkIn['email'];
                $dangKySukienId = $checkIn['dangky_sukien_id'];
                $checkInSukienId = $checkIn['checkin_sukien_id'];
                $thoiGianCheckIn = new Time($checkIn['thoi_gian_check_in']);
                $hinhThucThamGia = $checkIn['hinh_thuc_tham_gia'];
                
                // Tính thời gian tham dự
                $thoiGianCheckOut = $thoiGianCheckIn->addMinutes(rand(30, 240));
                $attendanceDurationMinutes = rand(30, 240);
            } else {
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
                    // Tạo thông tin người check-out mới
                    $firstName = $this->getRandomFirstName();
                    $lastName = $this->getRandomLastName();
                    $hoTen = $lastName . ' ' . $firstName;
                    $email = strtolower(str_replace(' ', '', $firstName)) . '.' . strtolower(str_replace(' ', '', $lastName)) . rand(100, 999) . '@example.com';
                    $dangKySukienId = null;
                }
                
                $checkInSukienId = null;
                $thoiGianCheckOut = $now->subMinutes(rand(5, 300));
                $attendanceDurationMinutes = rand(30, 240);
                $hinhThucThamGia = ['offline', 'online'][array_rand(['offline', 'online'])];
            }
            
            // Loại check-out
            $checkoutTypes = ['face_id', 'manual', 'qr_code', 'auto', 'online'];
            $checkoutType = $checkoutTypes[array_rand($checkoutTypes)];
            
            // Dữ liệu khuôn mặt (nếu là face_id)
            $faceImagePath = null;
            $faceMatchScore = null;
            $faceVerified = false;
            
            if ($checkoutType === 'face_id') {
                $faceImagePath = '/uploads/checkout/face_' . $i . '.jpg';
                $faceMatchScore = rand(70, 100) / 100; // 0.7 - 1.0
                $faceVerified = $faceMatchScore >= 0.8;
            }
            
            // Mã xác nhận (nếu là qr_code)
            $maXacNhan = $checkoutType === 'qr_code' ? strtoupper(substr(md5(uniqid()), 0, 8)) : null;
            
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
            
            // Feedback và đánh giá (mới)
            $hasFeedback = rand(0, 2) == 0; // 1/3 cơ hội có feedback
            $feedback = $hasFeedback ? $this->getRandomFeedback() : null;
            $danhGia = $hasFeedback ? rand(1, 5) : null;
            $noiDungDanhGia = $hasFeedback && $danhGia ? $this->getRandomNoiDungDanhGia($danhGia) : null;
            
            $data[] = [
                'su_kien_id' => $suKienId,
                'email' => $email,
                'ho_ten' => $hoTen,
                'dangky_sukien_id' => $dangKySukienId,
                'checkin_sukien_id' => $checkInSukienId,
                'thoi_gian_check_out' => $thoiGianCheckOut->toDateTimeString(),
                'checkout_type' => $checkoutType,
                'face_image_path' => $faceImagePath,
                'face_match_score' => $faceMatchScore,
                'face_verified' => $faceVerified,
                'ma_xac_nhan' => $maXacNhan,
                'status' => 1,
                'location_data' => $locationData,
                'device_info' => $deviceInfo,
                'attendance_duration_minutes' => $attendanceDurationMinutes,
                'hinh_thuc_tham_gia' => $hinhThucThamGia,
                'ip_address' => $ipAddress,
                'thong_tin_bo_sung' => $thongTinBoSung,
                'ghi_chu' => $ghiChu,
                'feedback' => $feedback,
                'danh_gia' => $danhGia,
                'noi_dung_danh_gia' => $noiDungDanhGia,
                'created_at' => $thoiGianCheckOut->toDateTimeString(),
                'updated_at' => $now->toDateTimeString()
            ];
        }
        
        // Thêm dữ liệu vào bảng checkout_sukien
        if (!empty($data)) {
            // Xóa dữ liệu cũ (nếu có)
            $this->db->table('checkout_sukien')->emptyTable();
            
            // Thêm dữ liệu mới
            $this->db->table('checkout_sukien')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi check-out sự kiện.\n";
        }
        
        echo "Seeder CheckOutSuKienSeeder đã được chạy thành công!\n";
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
            'Người tham dự rời sớm vì có việc gấp',
            'Đã xác nhận đã tham dự đầy đủ',
            'Đã trả lại thẻ tham dự và tài liệu',
            'Có yêu cầu được nhận tài liệu điện tử sau sự kiện',
            'Đã nhận quà lưu niệm khi check-out',
            'Có đề xuất cho các sự kiện tiếp theo',
            'Đã trao đổi thêm với diễn giả sau sự kiện',
            'Xin phép ra về sớm do sức khỏe'
        ];
        return $ghiChu[array_rand($ghiChu)];
    }
    
    private function getRandomFeedback() {
        $feedback = [
            'Rất hài lòng với nội dung sự kiện, sẽ tham gia lần sau',
            'Sự kiện cung cấp nhiều thông tin hữu ích cho công việc của tôi',
            'Diễn giả trình bày rất cuốn hút và dễ hiểu',
            'Tổ chức tốt nhưng thời gian chờ đợi hơi lâu',
            'Cần cải thiện hệ thống âm thanh của hội trường',
            'Không gian hơi chật, nên tổ chức ở địa điểm rộng hơn',
            'Thức ăn và đồ uống rất ngon',
            'Tài liệu được cung cấp đầy đủ và hữu ích',
            'Nên có thêm thời gian cho phần hỏi đáp',
            'Wifi không ổn định, gây khó khăn khi theo dõi phần trình bày online'
        ];
        return $feedback[array_rand($feedback)];
    }
    
    private function getRandomNoiDungDanhGia($danhGia) {
        $noiDungDanhGia = [
            1 => [
                'Không hài lòng với cách tổ chức sự kiện',
                'Nội dung không đúng như mô tả ban đầu',
                'Diễn giả trình bày không rõ ràng',
                'Thời gian chờ đợi quá lâu, tổ chức kém'
            ],
            2 => [
                'Còn nhiều điểm cần cải thiện',
                'Nội dung còn hời hợt, chưa đi sâu vào chuyên môn',
                'Không gian quá chật, khó chịu',
                'Âm thanh và hình ảnh còn nhiều lỗi'
            ],
            3 => [
                'Sự kiện tổ chức tạm ổn',
                'Nội dung có ích nhưng chưa đáp ứng đủ kỳ vọng',
                'Cần cải thiện phần hậu cần và đón tiếp',
                'Diễn giả có kiến thức nhưng cách truyền đạt chưa tốt'
            ],
            4 => [
                'Sự kiện được tổ chức khá tốt',
                'Nội dung phong phú và hữu ích',
                'Diễn giả có kiến thức chuyên sâu và trình bày rõ ràng',
                'Không gian thoải mái, âm thanh tốt'
            ],
            5 => [
                'Sự kiện được tổ chức xuất sắc',
                'Nội dung rất hữu ích và đúng như mong đợi',
                'Diễn giả trình bày cuốn hút và đầy cảm hứng',
                'Rất hài lòng với mọi khía cạnh của sự kiện'
            ]
        ];
        
        return $noiDungDanhGia[$danhGia][array_rand($noiDungDanhGia[$danhGia])];
    }
} 