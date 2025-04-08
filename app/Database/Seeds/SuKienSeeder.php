<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class SuKienSeeder extends Seeder
{
    public function run()
    {
        // Temporarily disable foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            // Check if loai_su_kien exists and has data
            if (!$this->checkDependency('loai_su_kien', 'LoaiSuKienSeeder')) {
                // Re-enable foreign key checks before exiting
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
                return;
            }
            
            // Clear existing data to avoid duplicates
            $this->clearTable('su_kien');
            
            // Load Text Helper để sử dụng các hàm xử lý text
            helper('text');
            
            // Lấy danh sách loại sự kiện từ bảng loai_su_kien
            $loaiSuKienList = $this->db->table('loai_su_kien')->get()->getResultArray();
            
            if (empty($loaiSuKienList)) {
                echo "Cần phải có dữ liệu trong bảng loai_su_kien trước khi chạy seeder này.\n";
                
                // Re-enable foreign key checks before exiting
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
                return;
            }
            
            // Display available loai_su_kien records for debugging
            echo "Danh sách loại sự kiện hiện có trong cơ sở dữ liệu:\n";
            foreach ($loaiSuKienList as $loai) {
                echo "- ID: " . ($loai['loai_su_kien_id'] ?? 'N/A') . 
                     ", Tên: " . ($loai['ten_loai_su_kien'] ?? 'N/A') . 
                     ", Mã: " . ($loai['ma_loai_su_kien'] ?? 'N/A') . "\n";
            }
            
            // Create a mapping array of loai_su_kien_id's that actually exist
            $validLoaiSuKienIds = array_column($loaiSuKienList, 'loai_su_kien_id');
            
            // Tạo dữ liệu mẫu dựa trên mockEvents
            $mockEvents = $this->getMockEvents();
            if (empty($mockEvents)) {
                echo "Không có dữ liệu mockEvents để xử lý.\n";
                
                // Re-enable foreign key checks before exiting
                $this->db->query('SET FOREIGN_KEY_CHECKS=1');
                return;
            }

            $now = Time::now();
            $data = [];
            
            foreach ($mockEvents as $index => $event) {
                // Choose a random event type from the valid IDs
                $loaiSuKienId = $validLoaiSuKienIds[array_rand($validLoaiSuKienIds)];
                
                // Override the loai_su_kien_id from the mock data with a valid one
                // Only use the event's loai_su_kien_id if it exists in the database
                $eventLoaiSuKienId = $event['loai_su_kien_id'] ?? null;
                if (!in_array($eventLoaiSuKienId, $validLoaiSuKienIds)) {
                    $eventLoaiSuKienId = $loaiSuKienId;
                }
                
                // Tạo thời gian ngẫu nhiên
                $startDate = clone $now;
                $startDate->addDays(rand(-30, 60));
                $startDate->setHour(rand(8, 18))->setMinute(0)->setSecond(0);
                
                $endDate = clone $startDate;
                $endDate->addHours(rand(2, 8));
                
                // Thời gian đăng ký
                $registerStart = clone $startDate;
                $registerStart->subDays(rand(14, 30));
                
                $registerEnd = clone $startDate;
                $registerEnd->subDays(rand(1, 5));
                
                // Tạo lịch trình từ mockEvents nếu có
                $lichTrinh = !empty($event['lich_trinh']) ? $event['lich_trinh'] : $this->generateSchedule($startDate);
                
                // Tạo slug từ tên sự kiện
                $slug = url_title(convert_accented_characters($event['ten_su_kien'] ?? 'su-kien-' . ($index + 1)), '-', true);
                
                // Dữ liệu cho một sự kiện
                $data[] = [
                    'ten_su_kien' => $event['ten_su_kien'] ?? 'Sự kiện mẫu ' . ($index + 1),
                    'su_kien_poster' => json_encode([
                        'original' => $event['hinh_anh'] ?? 'uploads/sukien/default.jpg',
                        'thumbnail' => str_replace('.jpg', '_thumb.jpg', $event['hinh_anh'] ?? 'uploads/sukien/default.jpg'),
                        'alt_text' => 'Poster ' . ($event['ten_su_kien'] ?? 'Sự kiện mẫu ' . ($index + 1))
                    ]),
                    'mo_ta' => $event['mo_ta_su_kien'] ?? 'Mô tả ngắn gọn về sự kiện mẫu số ' . ($index + 1),
                    'mo_ta_su_kien' => $event['mo_ta_su_kien'] ?? 'Mô tả chi tiết về sự kiện mẫu số ' . ($index + 1),
                    'chi_tiet_su_kien' => $event['chi_tiet_su_kien'] ?? '<p>Chi tiết về sự kiện mẫu số ' . ($index + 1) . '</p>',
                    'thoi_gian_bat_dau_su_kien' => !empty($event['thoi_gian_bat_dau_su_kien']) ? $event['thoi_gian_bat_dau_su_kien'] : (!empty($event['ngay_to_chuc']) ? $event['ngay_to_chuc'] . ' ' . ($event['gio_bat_dau'] ?? '08:00:00') : $startDate->toDateTimeString()),
                    'thoi_gian_ket_thuc_su_kien' => !empty($event['thoi_gian_ket_thuc_su_kien']) ? $event['thoi_gian_ket_thuc_su_kien'] : (!empty($event['ngay_to_chuc']) ? $event['ngay_to_chuc'] . ' ' . ($event['gio_ket_thuc'] ?? '17:00:00') : $endDate->toDateTimeString()),
                    'thoi_gian_bat_dau_dang_ky' => !empty($event['thoi_gian_bat_dau_dang_ky']) ? $event['thoi_gian_bat_dau_dang_ky'] : $registerStart->toDateTimeString(),
                    'thoi_gian_ket_thuc_dang_ky' => !empty($event['thoi_gian_ket_thuc_dang_ky']) ? $event['thoi_gian_ket_thuc_dang_ky'] : $registerEnd->toDateTimeString(),
                    'thoi_gian_checkin_bat_dau' => $event['thoi_gian_checkin_bat_dau'] ?? $startDate->toDateTimeString(),
                    'thoi_gian_checkin_ket_thuc' => $event['thoi_gian_checkin_ket_thuc'] ?? $endDate->toDateTimeString(),
                    'don_vi_to_chuc' => $event['don_vi_to_chuc'] ?? 'Trường Đại học Ngân hàng TP.HCM',
                    'don_vi_phoi_hop' => $event['don_vi_phoi_hop'] ?? 'Trường Đại học Ngân hàng TP.HCM',
                    'doi_tuong_tham_gia' => $event['doi_tuong_tham_gia'] ?? 'Tất cả',
                    'dia_diem' => $event['dia_diem'] ?? 'Địa điểm sự kiện ' . ($index + 1),
                    'dia_chi_cu_the' => $event['dia_chi_cu_the'] ?? 'Địa chỉ cụ thể ' . ($index + 1),
                    'toa_do_gps' => $event['toa_do_gps'] ?? rand(10, 11) . '.' . rand(100000, 999999) . ',' . rand(106, 107) . '.' . rand(100000, 999999),
                    'loai_su_kien_id' => $eventLoaiSuKienId,
                    'ma_qr_code' => $event['ma_qr_code'] ?? 'QR_EVENT_' . ($index + 1) . '_' . rand(10000, 99999),
                    'status' => $event['status'] ?? rand(0, 1),
                    'tong_dang_ky' => $event['tong_dang_ky'] ?? rand(10, 200),
                    'tong_check_in' => $event['tong_check_in'] ?? rand(5, 150),
                    'tong_check_out' => $event['tong_check_out'] ?? rand(0, 100),
                    'cho_phep_check_in' => 1,
                    'cho_phep_check_out' => 1,
                    'yeu_cau_face_id' => rand(0, 1),
                    'cho_phep_checkin_thu_cong' => 1,
                    'bat_dau_dang_ky' => !empty($event['thoi_gian_bat_dau_dang_ky']) ? $event['thoi_gian_bat_dau_dang_ky'] : $registerStart->toDateTimeString(),
                    'ket_thuc_dang_ky' => !empty($event['thoi_gian_ket_thuc_dang_ky']) ? $event['thoi_gian_ket_thuc_dang_ky'] : $registerEnd->toDateTimeString(),
                    'gio_bat_dau' => !empty($event['thoi_gian_bat_dau_su_kien']) ? date('H:i:s', strtotime($event['thoi_gian_bat_dau_su_kien'])) : ($event['gio_bat_dau'] ?? '08:00:00'),
                    'gio_ket_thuc' => !empty($event['thoi_gian_ket_thuc_su_kien']) ? date('H:i:s', strtotime($event['thoi_gian_ket_thuc_su_kien'])) : ($event['gio_ket_thuc'] ?? '17:00:00'),
                    'ngay_to_chuc' => !empty($event['thoi_gian_bat_dau_su_kien']) ? date('Y-m-d', strtotime($event['thoi_gian_bat_dau_su_kien'])) : date('Y-m-d', strtotime($startDate->toDateTimeString())),
                    'so_luong_tham_gia' => $event['so_luong_tham_gia'] ?? rand(50, 500),
                    'so_luong_dien_gia' => $event['so_luong_dien_gia'] ?? rand(1, 10),
                    'gioi_han_loai_nguoi_dung' => $event['gioi_han_loai_nguoi_dung'] ?? 'all',
                    'tu_khoa_su_kien' => $event['tu_khoa_su_kien'] ?? 'sự kiện, mẫu, test',
                    'hashtag' => $event['hashtags'] ?? '#SuKien' . ($index + 1),
                    'slug' => $event['slug'] ?? $slug,
                    'so_luot_xem' => $event['so_luot_xem'] ?? rand(50, 5000),
                    'lich_trinh' => json_encode($lichTrinh),
                    'hinh_thuc' => $event['hinh_thuc'] ?? 'offline',
                    'link_online' => $event['link_online'] ?? null,
                    'mat_khau_online' => $event['mat_khau_online'] ?? null,
                    'version' => 1,
                    'created_at' => $event['created_at'] ?? $now->subDays(rand(1, 30))->toDateTimeString(),
                    'updated_at' => $event['updated_at'] ?? $now->toDateTimeString()
                ];
            }
            
            // Thêm dữ liệu vào bảng su_kien
            if (!empty($data)) {
                try {
                    $this->db->table('su_kien')->insertBatch($data);
                    echo "Đã tạo thành công " . count($data) . " bản ghi sự kiện mẫu.\n";
                } catch (\Exception $e) {
                    echo "Lỗi khi thêm dữ liệu sự kiện: " . $e->getMessage() . "\n";
                    
                    // Try inserting one by one to see which record fails
                    echo "Đang thử thêm từng bản ghi một để xác định lỗi:\n";
                    $successCount = 0;
                    
                    foreach ($data as $index => $record) {
                        try {
                            $this->db->table('su_kien')->insert($record);
                            $successCount++;
                            echo "Bản ghi $index đã được thêm thành công.\n";
                        } catch (\Exception $e) {
                            echo "Lỗi ở bản ghi $index: " . $e->getMessage() . "\n";
                            echo "Loại sự kiện ID: " . $record['loai_su_kien_id'] . "\n";
                        }
                    }
                    
                    echo "Đã thêm thành công $successCount/" . count($data) . " bản ghi.\n";
                }
            }
            
            echo "Seeder SuKienSeeder đã được chạy thành công!\n";
        } finally {
            // Always re-enable foreign key checks at the end
            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
            echo "Foreign key checks đã được bật lại.\n";
        }
    }
    
    /**
     * Check if a dependency table exists and has data
     */
    private function checkDependency($table, $seeder)
    {
        if (!$this->db->tableExists($table)) {
            echo "Table $table doesn't exist. Run migrations first.\n";
            return false;
        }
        
        $count = $this->db->table($table)->countAllResults();
        if ($count <= 0) {
            echo "Table $table has no data. Run $seeder first.\n";
            return false;
        }
        
        return true;
    }
    
    /**
     * Clear a table safely
     */
    private function clearTable($table)
    {
        if ($this->db->tableExists($table)) {
            try {
                // Try to truncate first
                $this->db->table($table)->truncate();
                echo "Cleared table $table with truncate.\n";
            } catch (\Exception $e) {
                try {
                    // If truncate fails due to FK constraints, try emptying
                    $this->db->table($table)->emptyTable();
                    echo "Cleared table $table with emptyTable.\n";
                } catch (\Exception $e2) {
                    echo "Could not clear $table: " . $e2->getMessage() . "\n";
                    echo "This may lead to duplicate data errors.\n";
                }
            }
        }
    }
    
    /**
     * Lấy mockEvents từ SukienModel
     */
    private function getMockEvents()
    {
        return [
            [
                'ten_su_kien' => 'Hội thảo khoa học "Tài chính và Ngân hàng trong kỷ nguyên số"',
                'loai_su_kien_id' => 1, // Hội thảo
                'chi_tiet_su_kien' => '<p>Hội thảo khoa học "Tài chính và Ngân hàng trong kỷ nguyên số" là sự kiện thường niên của Trường Đại học Ngân hàng TP.HCM, nhằm tạo diễn đàn trao đổi học thuật và chia sẻ kinh nghiệm giữa các chuyên gia, nhà nghiên cứu và sinh viên trong lĩnh vực tài chính - ngân hàng.</p>',
                'bat_dau_dang_ky' => '2025-05-15 00:00:00',
                'ket_thuc_dang_ky' => '2025-06-14 23:59:59',
                'so_luong_tham_gia' => 200,
                'gioi_han_loai_nguoi_dung' => 'Sinh viên, Giảng viên, Cựu sinh viên',
                'tu_khoa_su_kien' => 'hội thảo, tài chính, ngân hàng, kỷ nguyên số, công nghệ',
                'mo_ta_su_kien' => 'Hội thảo tập trung vào những thách thức và cơ hội của ngành tài chính ngân hàng trong thời đại công nghệ số.',
                'hashtags' => '#HUB2023 #FinTech #DigitalBanking',
                'status' => 1,
                'created_at' => '2023-04-01 10:00:00',
                'updated_at' => '2023-04-01 10:00:00',
                'ngay_to_chuc' => '2025-06-15',
                'dia_diem' => 'CS Tôn Thất Đạm',
                'hinh_anh' => 'assets/images/event-1.jpg',
                'gio_bat_dau' => '08:00:00',
                'gio_ket_thuc' => '17:00:00',
                'so_luong_dien_gia' => 5,
                'dien_gia' => 'TS. Nguyễn Đình Thọ, TS. Lê Phan Quốc, TS. Nguyễn Thị Minh Hương',
                'thoi_gian' => '08:00 - 17:00',
                'loai_su_kien' => 'Hội thảo',
                'slug' => 'hoi-thao-khoa-hoc-tai-chinh-va-ngan-hang-trong-ky-nguyen-so-1.html',
                'so_luot_xem' => 1250,
                'lich_trinh' => [
                    [
                        'tieu_de' => 'Đăng ký và khai mạc',
                        'mo_ta' => 'Đón tiếp đại biểu và phát biểu khai mạc',
                        'thoi_gian_bat_dau' => '2025-06-15 08:00:00',
                        'thoi_gian_ket_thuc' => '2025-06-15 08:30:00',
                        'nguoi_phu_trach' => 'TS. Nguyễn Đình Thọ'
                    ],
                    [
                        'tieu_de' => 'Phiên thảo luận 1',
                        'mo_ta' => 'Xu hướng phát triển của ngân hàng số',
                        'thoi_gian_bat_dau' => '2025-06-15 08:30:00',
                        'thoi_gian_ket_thuc' => '2025-06-15 10:00:00',
                        'nguoi_phu_trach' => 'TS. Lê Phan Quốc'
                    ],
                    [
                        'tieu_de' => 'Giải lao',
                        'mo_ta' => 'Tea break và giao lưu',
                        'thoi_gian_bat_dau' => '2025-06-15 10:00:00',
                        'thoi_gian_ket_thuc' => '2025-06-15 10:30:00',
                        'nguoi_phu_trach' => ''
                    ],
                    [
                        'tieu_de' => 'Phiên thảo luận 2',
                        'mo_ta' => 'Ứng dụng blockchain trong tài chính',
                        'thoi_gian_bat_dau' => '2025-06-15 10:30:00',
                        'thoi_gian_ket_thuc' => '2025-06-15 12:00:00',
                        'nguoi_phu_trach' => 'ThS. Trần Văn Nam'
                    ],
                    [
                        'tieu_de' => 'Nghỉ trưa',
                        'mo_ta' => 'Tiệc trưa và giao lưu',
                        'thoi_gian_bat_dau' => '2025-06-15 12:00:00',
                        'thoi_gian_ket_thuc' => '2025-06-15 13:30:00',
                        'nguoi_phu_trach' => ''
                    ]
                ]
            ],
            [
                'ten_su_kien' => 'Ngày hội việc làm HUB lần thứ 13 - Năm 2023',
                'loai_su_kien_id' => 2, // Nghề nghiệp
                'chi_tiet_su_kien' => '<p>Ngày hội việc làm HUB là cầu nối quan trọng giữa sinh viên và doanh nghiệp, tạo cơ hội để sinh viên tiếp cận với các nhà tuyển dụng hàng đầu trong lĩnh vực tài chính, ngân hàng và công nghệ.</p>',
                'bat_dau_dang_ky' => '2025-05-22 00:00:00',
                'ket_thuc_dang_ky' => '2025-06-21 23:59:59',
                'so_luong_tham_gia' => 1000,
                'gioi_han_loai_nguoi_dung' => 'Sinh viên, Cựu sinh viên',
                'tu_khoa_su_kien' => 'việc làm, tuyển dụng, nghề nghiệp, sinh viên',
                'mo_ta_su_kien' => 'Cơ hội kết nối với hơn 50 doanh nghiệp hàng đầu trong lĩnh vực tài chính, ngân hàng và công nghệ.',
                'hashtags' => '#HUBJobFair2023 #CareerDay #JobOpportunities',
                'status' => 1,
                'created_at' => '2023-04-05 11:30:00',
                'updated_at' => '2023-04-05 11:30:00',
                'ngay_to_chuc' => '2025-06-22',
                'dia_diem' => 'CS Hoàng Diệu',
                'hinh_anh' => 'assets/images/event-2.jpg',
                'gio_bat_dau' => '08:30:00',
                'gio_ket_thuc' => '16:30:00',
                'so_luong_dien_gia' => 3,
                'dien_gia' => 'Đại diện các doanh nghiệp lớn',
                'thoi_gian' => '08:30 - 16:30',
                'loai_su_kien' => 'Nghề nghiệp',
                'slug' => 'ngay-hoi-viec-lam-hub-lan-thu-13-nam-2023-2.html',
                'so_luot_xem' => 980,
                'hinh_thuc' => 'offline'
            ],
            [
                'ten_su_kien' => 'Workshop "Kỹ năng phân tích dữ liệu trong lĩnh vực tài chính"',
                'loai_su_kien_id' => 3, // Workshop
                'chi_tiet_su_kien' => '<p>Workshop "Kỹ năng phân tích dữ liệu trong lĩnh vực tài chính" được tổ chức nhằm giúp sinh viên và những người làm việc trong ngành tài chính nắm bắt được các kỹ năng phân tích dữ liệu cơ bản và nâng cao, từ đó có thể áp dụng vào công việc thực tế.</p>',
                'bat_dau_dang_ky' => '2025-06-01 00:00:00',
                'ket_thuc_dang_ky' => '2025-06-29 23:59:59',
                'so_luong_tham_gia' => 100,
                'gioi_han_loai_nguoi_dung' => 'Sinh viên, Giảng viên, Cựu sinh viên, Đơn vị ngoài',
                'tu_khoa_su_kien' => 'workshop, kỹ năng, phân tích dữ liệu, tài chính',
                'mo_ta_su_kien' => 'Học hỏi các kỹ năng phân tích dữ liệu cơ bản và nâng cao, ứng dụng thực tế trong ngành tài chính.',
                'hashtags' => '#DataAnalytics #FinancialData #SkillDevelopment',
                'status' => 1,
                'created_at' => '2023-05-10 09:45:00',
                'updated_at' => '2023-05-10 09:45:00',
                'ngay_to_chuc' => '2025-06-30',
                'dia_diem' => 'CS Hàm Nghi',
                'hinh_anh' => 'assets/images/event-3.jpg',
                'gio_bat_dau' => '13:30:00',
                'gio_ket_thuc' => '17:00:00',
                'so_luong_dien_gia' => 2,
                'dien_gia' => 'ThS. Trần Văn Nam, PGS.TS. Trần Hùng Sơn',
                'thoi_gian' => '13:30 - 17:00',
                'loai_su_kien' => 'Workshop',
                'slug' => 'workshop-ky-nang-phan-tich-du-lieu-trong-linh-vuc-tai-chinh-3.html',
                'so_luot_xem' => 845,
                'hinh_thuc' => 'hybrid'
            ],
            [
                'ten_su_kien' => 'Cuộc thi "Sinh viên với ý tưởng khởi nghiệp" 2023',
                'loai_su_kien_id' => 4, // Hoạt động sinh viên
                'chi_tiet_su_kien' => '<p>Cuộc thi "Sinh viên với ý tưởng khởi nghiệp" là sân chơi bổ ích dành cho sinh viên có đam mê khởi nghiệp, mong muốn thử sức với những ý tưởng kinh doanh sáng tạo và khả thi.</p>',
                'bat_dau_dang_ky' => '2025-06-05 00:00:00',
                'ket_thuc_dang_ky' => '2025-07-04 23:59:59',
                'so_luong_tham_gia' => 300,
                'gioi_han_loai_nguoi_dung' => 'Sinh viên',
                'tu_khoa_su_kien' => 'cuộc thi, khởi nghiệp, ý tưởng, sinh viên',
                'mo_ta_su_kien' => 'Cơ hội cho sinh viên thể hiện tài năng và sáng tạo trong lĩnh vực khởi nghiệp.',
                'hashtags' => '#StartupIdeas #StudentEntrepreneurs #Innovation',
                'status' => 1,
                'created_at' => '2023-05-15 14:20:00',
                'updated_at' => '2023-05-15 14:20:00',
                'ngay_to_chuc' => '2025-07-05',
                'dia_diem' => 'CS Tôn Thất Đạm',
                'hinh_anh' => 'assets/images/event-4.jpg',
                'gio_bat_dau' => '08:00:00',
                'gio_ket_thuc' => '17:00:00',
                'so_luong_dien_gia' => 4,
                'dien_gia' => 'Ban giám khảo cuộc thi',
                'thoi_gian' => '08:00 - 17:00',
                'loai_su_kien' => 'Hoạt động sinh viên',
                'slug' => 'cuoc-thi-sinh-vien-voi-y-tuong-khoi-nghiep-2023-4.html',
                'so_luot_xem' => 720,
                'hinh_thuc' => 'offline'
            ],
            [
                'ten_su_kien' => 'Hội thảo "Xu hướng công nghệ tài chính 2023"',
                'loai_su_kien_id' => 1, // Hội thảo
                'chi_tiet_su_kien' => '<p>Hội thảo "Xu hướng công nghệ tài chính 2023" giúp người tham dự cập nhật những xu hướng mới nhất trong lĩnh vực công nghệ tài chính và blockchain, từ đó có cái nhìn tổng quan về sự phát triển của ngành trong thời gian tới.</p>',
                'bat_dau_dang_ky' => '2025-06-12 00:00:00',
                'ket_thuc_dang_ky' => '2025-07-11 23:59:59',
                'so_luong_tham_gia' => 250,
                'gioi_han_loai_nguoi_dung' => 'Sinh viên, Giảng viên, Cựu sinh viên, Đơn vị ngoài',
                'tu_khoa_su_kien' => 'hội thảo, xu hướng, công nghệ tài chính, blockchain',
                'mo_ta_su_kien' => 'Cập nhật những xu hướng mới nhất trong lĩnh vực công nghệ tài chính và blockchain.',
                'hashtags' => '#FinTech2023 #Blockchain #TechnologyTrends',
                'status' => 1,
                'created_at' => '2023-05-20 16:00:00',
                'updated_at' => '2023-05-20 16:00:00',
                'ngay_to_chuc' => '2025-07-12',
                'dia_diem' => 'CS Hoàng Diệu',
                'hinh_anh' => 'assets/images/event-5.jpg',
                'gio_bat_dau' => '09:00:00',
                'gio_ket_thuc' => '16:00:00',
                'so_luong_dien_gia' => 6,
                'dien_gia' => 'TS. Nguyễn Thanh Bình, ThS. Trần Văn Nam, Chuyên gia nước ngoài',
                'thoi_gian' => '09:00 - 16:00',
                'loai_su_kien' => 'Hội thảo',
                'slug' => 'hoi-thao-xu-huong-cong-nghe-tai-chinh-2023-5.html',
                'so_luot_xem' => 635,
                'hinh_thuc' => 'online',
                'link_online' => 'https://meet.google.com/fintech-conference-2023'
            ]
        ];
    }

    /**
     * Tạo lịch trình ngẫu nhiên
     * 
     * @param Time $startDate Ngày bắt đầu sự kiện
     * @return array Mảng lịch trình
     */
    private function generateSchedule($startDate)
    {
        $schedule = [];
        $sessionCount = rand(3, 6);
        $currentTime = clone $startDate;

        // Tiêu đề phiên mặc định
        $sessionTitles = [
            'Đăng ký và khai mạc',
            'Phiên thảo luận chính',
            'Giải lao',
            'Phát biểu của khách mời',
            'Workshop thực hành',
            'Thảo luận nhóm',
            'Hỏi đáp tương tác',
            'Tổng kết và bế mạc'
        ];

        // Tạo các phiên
        for ($i = 0; $i < $sessionCount; $i++) {
            $duration = rand(30, 90); // 30-90 phút mỗi phiên
            $endTime = clone $currentTime;
            $endTime->addMinutes($duration);

            $schedule[] = [
                'tieu_de' => $sessionTitles[$i % count($sessionTitles)],
                'mo_ta' => 'Mô tả chi tiết cho phiên ' . ($i + 1),
                'thoi_gian_bat_dau' => $currentTime->toDateTimeString(),
                'thoi_gian_ket_thuc' => $endTime->toDateTimeString(),
                'nguoi_phu_trach' => $i % 3 == 0 ? 'Ban tổ chức' : 'Diễn giả ' . ($i + 1)
            ];

            // Cập nhật thời gian cho phiên tiếp theo
            $currentTime = clone $endTime;
            $currentTime->addMinutes(15); // Nghỉ giữa các phiên
        }

        return $schedule;
    }
}