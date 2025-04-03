<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class CheckInSuKienSeeder extends Seeder
{
    public function run()
    {
        // Check if table exists before seeding
        if (!$this->db->tableExists('checkin_sukien')) {
            echo "Table checkin_sukien does not exist. Run migrations first.\n";
            return;
        }

        // Check if su_kien table has data
        $suKienList = $this->db->table('su_kien')->where('deleted_at IS NULL')->get()->getResultArray();
        if (empty($suKienList)) {
            echo "No events found. Run SuKienSeeder first.\n";
            return;
        }
        
        // Get dangky_sukien data if available
        $dangKyList = $this->db->table('dangky_sukien')->where('deleted_at IS NULL')->get()->getResultArray();
        
        // Empty table before insertion
        $this->db->table('checkin_sukien')->emptyTable();
        
        $data = [];
        $now = Time::now();
        
        // Generate 30 check-in records
        for ($i = 0; $i < 30; $i++) {
            // Select random event
            $suKien = $suKienList[array_rand($suKienList)];
            $suKienId = $suKien['su_kien_id'];
            
            // Determine if we use existing registration or create new data
            $useDangKy = !empty($dangKyList) && rand(0, 2) < 2; // 2/3 chance to use existing registration
            
            if ($useDangKy) {
                // Use data from existing registration
                $dangKy = $dangKyList[array_rand($dangKyList)];
                $hoTen = $dangKy['ho_ten'];
                $email = $dangKy['email'];
                $dangKySukienId = $dangKy['dangky_sukien_id'];
            } else {
                // Generate new attendee information
                $firstName = $this->getRandomName('first');
                $lastName = $this->getRandomName('last');
                $hoTen = $lastName . ' ' . $firstName;
                $email = strtolower(str_replace(' ', '', $firstName)) . '.' . strtolower(str_replace(' ', '', $lastName)) . rand(100, 999) . '@example.com';
                $dangKySukienId = null;
            }
            
            // Determine check-in method
            $checkinTypes = ['qr_code', 'face_id', 'manual', 'auto'];
            $checkinType = $checkinTypes[array_rand($checkinTypes)];
            
            // Face verification data (for face_id check-in)
            $faceImagePath = null;
            $faceMatchScore = null;
            $faceVerified = false;
            
            if ($checkinType === 'face_id') {
                $faceImagePath = '/uploads/faces/checkin_' . $i . '.jpg';
                $faceMatchScore = rand(70, 99) / 100; // 0.7 - 0.99
                $faceVerified = $faceMatchScore >= 0.8;
            }
            
            // Generate QR code for qr_code check-in
            $maXacNhan = $checkinType === 'qr_code' ? strtoupper(substr(md5($email . $suKienId), 0, 8)) : null;
            
            // Device info
            $deviceInfo = $this->getRandomDeviceInfo();
            
            // Location data
            $locationData = json_encode([
                'latitude' => rand(10500000, 10600000) / 1000000,
                'longitude' => rand(106600000, 106800000) / 1000000,
                'accuracy' => rand(5, 20)
            ]);
            
            // IP address
            $ipAddress = rand(1, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
            
            // Event-specific data
            $thanhPhanHoiNghi = ['Chủ tọa', 'Khách mời', 'Người tham dự', 'Ban tổ chức', 'Diễn giả'][array_rand(['Chủ tọa', 'Khách mời', 'Người tham dự', 'Ban tổ chức', 'Diễn giả'])];
            $hinhThucThamGia = ['offline', 'online', 'hybrid'][array_rand(['offline', 'online', 'hybrid'])];
            
            // Generate check-in timestamp (between event start and end)
            $thoiGianCheckIn = $now->subDays(rand(0, 7))->subHours(rand(0, 12));
            
            $data[] = [
                'su_kien_id' => $suKienId,
                'email' => $email,
                'ho_ten' => $hoTen,
                'dangky_sukien_id' => $dangKySukienId,
                'thoi_gian_check_in' => $thoiGianCheckIn->toDateTimeString(),
                'checkin_type' => $checkinType,
                'face_image_path' => $faceImagePath,
                'face_match_score' => $faceMatchScore,
                'face_verified' => $faceVerified,
                'ma_xac_nhan' => $maXacNhan,
                'status' => 1,
                'location_data' => $locationData,
                'device_info' => $deviceInfo,
                'thanh_phan_hoi_nghi' => $thanhPhanHoiNghi,
                'hinh_thuc_tham_gia' => $hinhThucThamGia,
                'ip_address' => $ipAddress,
                'ghi_chu' => rand(0, 2) == 0 ? 'Ghi chú check-in ' . ($i + 1) : null, // 1/3 chance to have a note
                'created_at' => $thoiGianCheckIn->toDateTimeString(),
                'updated_at' => $thoiGianCheckIn->toDateTimeString()
            ];
        }
        
        // Insert the data
        if (!empty($data)) {
            $this->db->table('checkin_sukien')->insertBatch($data);
            echo "Created " . count($data) . " check-in records successfully.\n";
        }
    }
    
    private function getRandomName($type = 'first') 
    {
        $firstNames = ['An', 'Bình', 'Cường', 'Dũng', 'Hà', 'Hải', 'Hiếu', 'Hoàng', 'Hùng', 'Hương', 'Lan', 'Linh', 'Mai', 'Minh'];
        $lastNames = ['Nguyễn', 'Trần', 'Lê', 'Phạm', 'Hoàng', 'Huỳnh', 'Phan', 'Vũ', 'Võ', 'Đặng', 'Bùi', 'Đỗ', 'Hồ', 'Ngô'];
        
        return $type === 'first' ? $firstNames[array_rand($firstNames)] : $lastNames[array_rand($lastNames)];
    }
    
    private function getRandomDeviceInfo() 
    {
        $devices = [
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_4 like Mac OS X)',
            'Mozilla/5.0 (Android 10; Mobile)',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)',
            'Mozilla/5.0 (iPad; CPU OS 14_4 like Mac OS X)'
        ];
        
        return $devices[array_rand($devices)];
    }
}