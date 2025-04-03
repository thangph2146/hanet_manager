<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SuKienDienGiaSeeder extends Seeder
{
    public function run()
    {
        // Check if events and speakers exist before proceeding
        $suKienCount = $this->db->table('su_kien')->where('deleted_at IS NULL')->countAllResults();
        $dienGiaCount = $this->db->table('dien_gia')->where('deleted_at IS NULL')->countAllResults();
        
        if ($suKienCount <= 0 || $dienGiaCount <= 0) {
            echo "Both events and speakers must exist to run this seeder.\n";
            if ($suKienCount <= 0) echo "Run SuKienSeeder first.\n";
            if ($dienGiaCount <= 0) echo "Run DienGiaSeeder first.\n";
            return;
        }
        
        // Get events and speakers
        $suKienList = $this->db->table('su_kien')
            ->where('deleted_at IS NULL')
            ->limit(5)
            ->get()
            ->getResultArray();
            
        $dienGiaList = $this->db->table('dien_gia')
            ->where('deleted_at IS NULL')
            ->get()
            ->getResultArray();
        
        // Empty the table before inserting new data
        $this->db->table('su_kien_dien_gia')->emptyTable();
        
        $data = [];
        $now = Time::now();
        
        // For each event, assign 3-5 speakers
        foreach ($suKienList as $suKienIndex => $suKien) {
            $suKienId = $suKien['su_kien_id'];
            
            // Number of speakers for this event
            $numDienGia = rand(3, 5);
            
            // Shuffle the speakers list to get random speakers each time
            shuffle($dienGiaList);
            $dienGiaForEvent = array_slice($dienGiaList, 0, $numDienGia);
            
            // Base time for the event - use the event's start time if available
            $baseTime = isset($suKien['thoi_gian_bat_dau']) && !empty($suKien['thoi_gian_bat_dau']) 
                ? new Time($suKien['thoi_gian_bat_dau']) 
                : (new Time($now))->addDays($suKienIndex)->setHour(9)->setMinute(0)->setSecond(0);
            
            foreach ($dienGiaForEvent as $index => $dienGia) {
                // Calculate presentation time (each speaker gets a slot after the previous one)
                $startTime = clone $baseTime;
                $startTime->addHours($index);
                
                // Presentation duration
                $duration = rand(30, 60);
                
                // End time
                $endTime = clone $startTime;
                $endTime->addMinutes($duration);
                
                // Generate role based on speaker order
                $vaiTro = $this->getRandomVaiTro($index);
                
                // Participation status
                $trangThaiOptions = ['xac_nhan', 'cho_xac_nhan', 'tu_choi', 'khong_lien_he_duoc'];
                $trangThai = $trangThaiOptions[array_rand($trangThaiOptions)];
                
                // Update speaker's event count
                $dienGia['so_su_kien_tham_gia']++;
                $this->db->table('dien_gia')
                    ->where('dien_gia_id', $dienGia['dien_gia_id'])
                    ->update(['so_su_kien_tham_gia' => $dienGia['so_su_kien_tham_gia']]);
                
                $data[] = [
                    'su_kien_id' => $suKienId,
                    'dien_gia_id' => $dienGia['dien_gia_id'],
                    'thu_tu' => $index + 1,
                    'vai_tro' => $vaiTro,
                    'mo_ta' => 'Mô tả về vai trò của ' . $dienGia['ten_dien_gia'] . ' trong sự kiện ' . $suKien['ten_su_kien'],
                    'thoi_gian_trinh_bay' => $startTime->toDateTimeString(),
                    'thoi_gian_ket_thuc' => $endTime->toDateTimeString(),
                    'thoi_luong_phut' => $duration,
                    'tieu_de_trinh_bay' => $this->getRandomPresentationTitle($dienGia['chuyen_mon']),
                    'tai_lieu_dinh_kem' => json_encode([
                        [
                            'ten_file' => 'presentation_' . strtolower(str_replace(' ', '_', $dienGia['ten_dien_gia'])) . '.pdf',
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
        
        // Insert the data
        if (!empty($data)) {
            $this->db->table('su_kien_dien_gia')->insertBatch($data);
            echo "Created " . count($data) . " relationships between events and speakers.\n";
        }
        
        echo "SuKienDienGiaSeeder completed successfully!\n";
    }
    
    /**
     * Generate a random role based on speaker order
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
        
        // First speaker is typically the host
        if ($index === 0) {
            return 'Chủ tọa';
        }
        
        // Second speaker is typically the main speaker
        if ($index === 1) {
            return 'Diễn giả chính';
        }
        
        // Other speakers get random roles
        return $vaiTroList[array_rand(array_slice($vaiTroList, 2))];
    }
    
    /**
     * Generate a random presentation title based on speaker expertise
     */
    private function getRandomPresentationTitle($expertise)
    {
        $prefixes = [
            'Xu hướng mới trong',
            'Tương lai của',
            'Ứng dụng thực tiễn của',
            'Thách thức và cơ hội trong',
            'Phân tích chuyên sâu về',
            'Tổng quan về',
            'Nghiên cứu mới nhất về',
            'Tiến bộ gần đây trong'
        ];
        
        $suffix = $expertise ?: 'lĩnh vực chuyên môn';
        
        return $prefixes[array_rand($prefixes)] . ' ' . $suffix;
    }
}