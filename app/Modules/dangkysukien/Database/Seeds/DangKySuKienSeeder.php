<?php

namespace App\Modules\dangkysukien\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class DangKySuKienSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách sự kiện từ bảng su_kien (giả định có ít nhất 3 sự kiện)
        $suKienList = $this->db->table('su_kien')->limit(5)->get()->getResultArray();
        
        if (empty($suKienList)) {
            echo "Cần phải có dữ liệu trong bảng su_kien trước khi chạy seeder này.\n";
            return;
        }
        
        $data = [];
        $now = Time::now();
        
        // Tạo 50 bản ghi đăng ký sự kiện mẫu
        for ($i = 0; $i < 50; $i++) {
            // Chọn ngẫu nhiên một sự kiện
            $suKien = $suKienList[array_rand($suKienList)];
            $suKienId = $suKien['su_kien_id'];
            
            // Tạo thông tin người đăng ký
            $firstName = $this->getRandomFirstName();
            $lastName = $this->getRandomLastName();
            $hoTen = $lastName . ' ' . $firstName;
            $email = strtolower(str_replace(' ', '', $firstName)) . '.' . strtolower(str_replace(' ', '', $lastName)) . '@example.com';
            
            // Đảm bảo không trùng lặp email cho cùng một sự kiện
            $exists = $this->db->table('dangky_sukien')
                ->where('sukien_id', $suKienId)
                ->where('email', $email)
                ->countAllResults();
            
            if ($exists > 0) {
                $email = strtolower(str_replace(' ', '', $firstName)) . '.' . strtolower(str_replace(' ', '', $lastName)) . rand(100, 999) . '@example.com';
            }
            
            // Tạo thông tin trạng thái đăng ký
            $status = rand(-1, 1); // -1: đã hủy, 0: chờ xác nhận, 1: đã xác nhận
            
            // Trạng thái điểm danh
            $daCheckIn = ($status == 1) ? (rand(0, 1) == 1) : false;
            $daCheckOut = ($daCheckIn) ? (rand(0, 1) == 1) : false;
            
            // Trạng thái duyệt hoặc hủy
            $thoiGianDuyet = ($status == 1) ? $now->subDays(rand(1, 5))->toDateTimeString() : null;
            $thoiGianHuy = ($status == -1) ? $now->subDays(rand(1, 3))->toDateTimeString() : null;
            $lyDoHuy = ($status == -1) ? $this->getRandomLyDoHuy() : null;
            
            // Thông tin điểm danh
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
            
            // Hình thức tham gia
            $hinhThucThamGia = ['offline', 'online', 'hybrid'][array_rand(['offline', 'online', 'hybrid'])];
            
            // Phương thức điểm danh
            $diemDanhBang = 'none';
            if ($daCheckIn) {
                $diemDanhBang = ['qr_code', 'face_id', 'manual'][array_rand(['qr_code', 'face_id', 'manual'])];
            }
            
            // Loại người đăng ký
            $loaiNguoiDangKy = ['khach', 'sinh_vien', 'giang_vien'][array_rand(['khach', 'sinh_vien', 'giang_vien'])];
            
            // Các thông tin bổ sung
            $thongTinDangKy = json_encode([
                'nganh_hoc' => $loaiNguoiDangKy == 'sinh_vien' ? $this->getRandomNganhHoc() : null,
                'khoa' => $loaiNguoiDangKy == 'sinh_vien' ? rand(1, 5) : null,
                'ma_sinh_vien' => $loaiNguoiDangKy == 'sinh_vien' ? 'SV' . rand(10000, 99999) : null,
                'bo_mon' => $loaiNguoiDangKy == 'giang_vien' ? $this->getRandomBoMon() : null,
                'quan_tam' => $this->getRandomQuanTam()
            ]);
            
            $data[] = [
                'sukien_id' => $suKienId,
                'email' => $email,
                'ho_ten' => $hoTen,
                'dien_thoai' => '0' . rand(900000000, 999999999),
                'loai_nguoi_dang_ky' => $loaiNguoiDangKy,
                'ngay_dang_ky' => $now->subDays(rand(5, 15))->toDateTimeString(),
                'ma_xac_nhan' => strtoupper(substr(md5(uniqid()), 0, 8)),
                'status' => $status,
                'noi_dung_gop_y' => rand(0, 1) == 1 ? $this->getRandomNoiDungGopY() : null,
                'nguon_gioi_thieu' => $this->getRandomNguonGioiThieu(),
                'don_vi_to_chuc' => $loaiNguoiDangKy == 'khach' ? $this->getRandomDonViToChuc() : null,
                'face_image_path' => $daCheckIn && $diemDanhBang == 'face_id' ? '/uploads/faces/face_' . $i . '.jpg' : null,
                'face_verified' => $daCheckIn && $diemDanhBang == 'face_id',
                'da_check_in' => $daCheckIn,
                'da_check_out' => $daCheckOut,
                'checkin_sukien_id' => $daCheckIn ? rand(1, 100) : null,
                'checkout_sukien_id' => $daCheckOut ? rand(1, 100) : null,
                'thoi_gian_duyet' => $thoiGianDuyet,
                'thoi_gian_huy' => $thoiGianHuy,
                'ly_do_huy' => $lyDoHuy,
                'hinh_thuc_tham_gia' => $hinhThucThamGia,
                'attendance_status' => $attendanceStatus,
                'attendance_minutes' => $attendanceMinutes,
                'diem_danh_bang' => $diemDanhBang,
                'thong_tin_dang_ky' => $thongTinDangKy,
                'ly_do_tham_du' => $this->getRandomLyDoThamDu(),
                'created_at' => $now->subDays(rand(5, 15))->toDateTimeString(),
                'updated_at' => $now->subDays(rand(1, 4))->toDateTimeString()
            ];
        }
        
        // Thêm dữ liệu vào bảng dangky_sukien
        if (!empty($data)) {
            // Xóa dữ liệu cũ (nếu có)
            $this->db->table('dangky_sukien')->emptyTable();
            
            // Thêm dữ liệu mới
            $this->db->table('dangky_sukien')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi đăng ký sự kiện.\n";
        }
        
        echo "Seeder DangKySuKienSeeder đã được chạy thành công!\n";
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
    
    private function getRandomNganhHoc() {
        $nganh = ['Công nghệ thông tin', 'Quản trị kinh doanh', 'Kế toán', 'Ngôn ngữ Anh', 'Marketing', 'Luật', 'Kinh tế', 'Xây dựng', 'Y khoa', 'Điện tử viễn thông'];
        return $nganh[array_rand($nganh)];
    }
    
    private function getRandomBoMon() {
        $boMon = ['Toán ứng dụng', 'Khoa học máy tính', 'Kinh tế học', 'Ngôn ngữ học', 'Vật lý', 'Hóa học', 'Sinh học', 'Lịch sử', 'Địa lý', 'Văn học'];
        return $boMon[array_rand($boMon)];
    }
    
    private function getRandomQuanTam() {
        $quanTam = ['Công nghệ', 'Khởi nghiệp', 'Tài chính', 'Giáo dục', 'Môi trường', 'Nghệ thuật', 'Thể thao', 'Du lịch', 'Sức khỏe', 'Truyền thông'];
        $count = rand(1, 3);
        $result = [];
        for ($i = 0; $i < $count; $i++) {
            $result[] = $quanTam[array_rand($quanTam)];
        }
        return array_unique($result);
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