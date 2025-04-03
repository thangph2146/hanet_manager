<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CheckOutSuKienSeeder extends Seeder
{
    public function run()
    {
        // Check if check-in data exists before proceeding
        $checkInCount = $this->db->table('checkin_sukien')->countAllResults();
        if ($checkInCount <= 0) {
            echo "No check-in data found. Run CheckInSuKienSeeder first.\n";
            return;
        }
        
        // Retrieve check-in data to link check-out properly
        $checkInList = $this->db->table('checkin_sukien')
            ->where('deleted_at IS NULL')
            ->get()
            ->getResultArray();
        
        // Empty table before insertion
        $this->db->table('checkout_sukien')->emptyTable();
        
        $data = [];
        $now = Time::now();
        
        // Generate check-out records for approximately 70% of check-ins
        $numCheckouts = (int) ceil($checkInCount * 0.7);
        
        // Shuffle check-ins to randomly select some for check-out
        shuffle($checkInList);
        $selectedCheckIns = array_slice($checkInList, 0, $numCheckouts);
        
        foreach ($selectedCheckIns as $index => $checkIn) {
            // Get check-in time and calculate checkout time (30min to 4 hours later)
            $thoiGianCheckIn = new Time($checkIn['thoi_gian_check_in']);
            $attendanceDurationMinutes = rand(30, 240);
            $thoiGianCheckOut = clone $thoiGianCheckIn;
            $thoiGianCheckOut->addMinutes($attendanceDurationMinutes);
            
            // Checkout method - try to match check-in method when sensible
            $checkoutType = $checkIn['checkin_type'];
            if ($checkoutType == 'auto') {
                $checkoutType = ['qr_code', 'manual', 'auto'][array_rand(['qr_code', 'manual', 'auto'])];
            }
            
            // Face verification for face_id checkout
            $faceImagePath = null;
            $faceMatchScore = null;
            $faceVerified = false;
            
            if ($checkoutType === 'face_id') {
                $faceImagePath = '/uploads/faces/checkout_' . $index . '.jpg';
                $faceMatchScore = rand(75, 99) / 100; // 0.75 - 0.99
                $faceVerified = $faceMatchScore >= 0.8;
            }
            
            // Rating and feedback
            $hasRating = rand(0, 2) == 0; // 1/3 chance to have rating
            $danhGia = $hasRating ? rand(1, 5) : null;
            $noiDungDanhGia = $hasRating ? $this->getRandomFeedback($danhGia) : null;
            
            $data[] = [
                'su_kien_id' => $checkIn['su_kien_id'],
                'email' => $checkIn['email'],
                'ho_ten' => $checkIn['ho_ten'],
                'dangky_sukien_id' => $checkIn['dangky_sukien_id'],
                'checkin_sukien_id' => $checkIn['checkin_sukien_id'],
                'thoi_gian_check_out' => $thoiGianCheckOut->toDateTimeString(),
                'checkout_type' => $checkoutType,
                'face_image_path' => $faceImagePath,
                'face_match_score' => $faceMatchScore,
                'face_verified' => $faceVerified,
                'ma_xac_nhan' => $checkIn['ma_xac_nhan'],
                'status' => 1,
                'location_data' => $checkIn['location_data'], // Use same location data as check-in
                'device_info' => $checkIn['device_info'], // Use same device as check-in
                'attendance_duration_minutes' => $attendanceDurationMinutes,
                'hinh_thuc_tham_gia' => $checkIn['hinh_thuc_tham_gia'],
                'ip_address' => $checkIn['ip_address'],
                'thong_tin_bo_sung' => json_encode(['check_out_note' => 'Auto-generated checkout']),
                'ghi_chu' => rand(0, 2) == 0 ? 'Ghi chú check-out ' . ($index + 1) : null, // 1/3 chance to have a note
                'danh_gia' => $danhGia,
                'noi_dung_danh_gia' => $noiDungDanhGia,
                'created_at' => $thoiGianCheckOut->toDateTimeString(),
                'updated_at' => $thoiGianCheckOut->toDateTimeString()
            ];
        }
        
        // Insert the data
        if (!empty($data)) {
            $this->db->table('checkout_sukien')->insertBatch($data);
            echo "Created " . count($data) . " check-out records for " . $checkInCount . " check-ins.\n";
        }
    }
    
    private function getRandomFeedback($rating)
    {
        $feedbacks = [
            1 => [
                'Không đáp ứng kỳ vọng, cần cải thiện nhiều',
                'Nội dung không hữu ích, thất vọng',
                'Tổ chức kém, không chuyên nghiệp'
            ],
            2 => [
                'Còn nhiều hạn chế, chưa đạt yêu cầu',
                'Nội dung chưa phong phú, nhàm chán',
                'Thời gian không hợp lý, kéo dài'
            ],
            3 => [
                'Tạm ổn, đáp ứng được nhu cầu cơ bản',
                'Nội dung khá, cần thêm ví dụ thực tế',
                'Tổ chức tương đối tốt, còn một số thiếu sót'
            ],
            4 => [
                'Tốt, đáp ứng hầu hết kỳ vọng',
                'Nội dung hữu ích, trình bày rõ ràng',
                'Tổ chức chuyên nghiệp, khoa học'
            ],
            5 => [
                'Rất tốt, vượt mong đợi',
                'Nội dung xuất sắc, bổ ích và thực tế',
                'Tổ chức hoàn hảo, sẽ tham gia lần sau'
            ]
        ];
        
        return $feedbacks[$rating][array_rand($feedbacks[$rating])];
    }
}