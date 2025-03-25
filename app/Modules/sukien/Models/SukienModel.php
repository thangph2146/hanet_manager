<?php

namespace App\Modules\sukien\Models;

use CodeIgniter\Model;

class SukienModel extends Model
{
    protected $table            = 'su_kien';
    protected $primaryKey       = 'su_kien_id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'ten_su_kien', 'loai_su_kien_id', 'chi_tiet_su_kien', 'bat_dau_dang_ky',
        'ket_thuc_dang_ky', 'so_luong_tham_gia', 'gio_bat_dau', 'gio_ket_thuc',
        'so_luong_dien_gia', 'gioi_han_loai_nguoi_dung', 'tu_khoa_su_kien',
        'mo_ta_su_kien', 'hashtag', 'status', 'bin', 'slug', 'so_luot_xem', 'lich_trinh'
    ];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
    
    // Mock Data - Dữ liệu mẫu cho sự kiện
    private $mockEvents = [
        [
            'id_su_kien' => 1,
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
            'keywords' => 'hội thảo, tài chính, ngân hàng, kỷ nguyên số, công nghệ',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-04-01 10:00:00',
            'updated_at' => '2023-04-01 10:00:00',
            'deleted_at' => null,
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
                    'thoi_gian' => '08:00 - 08:30',
                    'tieu_de' => 'Đăng ký và khai mạc',
                    'mo_ta' => 'Đón tiếp đại biểu và phát biểu khai mạc',
                    'dien_gia' => 'TS. Nguyễn Đình Thọ'
                ],
                [
                    'thoi_gian' => '08:30 - 10:00',
                    'tieu_de' => 'Phiên thảo luận 1',
                    'mo_ta' => 'Xu hướng phát triển của ngân hàng số',
                    'dien_gia' => 'TS. Lê Phan Quốc'
                ],
                [
                    'thoi_gian' => '10:00 - 10:30',
                    'tieu_de' => 'Giải lao',
                    'mo_ta' => 'Tea break và giao lưu',
                    'dien_gia' => ''
                ],
                [
                    'thoi_gian' => '10:30 - 12:00',
                    'tieu_de' => 'Phiên thảo luận 2',
                    'mo_ta' => 'Ứng dụng blockchain trong tài chính',
                    'dien_gia' => 'ThS. Trần Văn Nam'
                ],
                [
                    'thoi_gian' => '12:00 - 13:30',
                    'tieu_de' => 'Nghỉ trưa',
                    'mo_ta' => 'Tiệc trưa và giao lưu',
                    'dien_gia' => ''
                ],
                [
                    'thoi_gian' => '13:30 - 15:00',
                    'tieu_de' => 'Phiên thảo luận 3',
                    'mo_ta' => 'Bảo mật và quản lý rủi ro trong ngân hàng số',
                    'dien_gia' => 'TS. Nguyễn Thị Minh Hương'
                ],
                [
                    'thoi_gian' => '15:00 - 15:30',
                    'tieu_de' => 'Giải lao',
                    'mo_ta' => 'Tea break và giao lưu',
                    'dien_gia' => ''
                ],
                [
                    'thoi_gian' => '15:30 - 17:00',
                    'tieu_de' => 'Phiên thảo luận 4',
                    'mo_ta' => 'Đào tạo nguồn nhân lực cho ngân hàng số',
                    'dien_gia' => 'PGS.TS. Trần Hùng Sơn'
                ],
                [
                    'thoi_gian' => '17:00 - 17:30',
                    'tieu_de' => 'Bế mạc',
                    'mo_ta' => 'Tổng kết và trao chứng nhận',
                    'dien_gia' => 'TS. Nguyễn Đình Thọ'
                ]
            ]
        ],
        [
            'id_su_kien' => 2,
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
            'keywords' => 'việc làm, tuyển dụng, nghề nghiệp, sinh viên',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-04-05 11:30:00',
            'updated_at' => '2023-04-05 11:30:00',
            'deleted_at' => null,
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
            'lich_trinh' => [
                [
                    'thoi_gian' => '08:30 - 09:00',
                    'tieu_de' => 'Đón tiếp và khai mạc',
                    'mo_ta' => 'Đón tiếp doanh nghiệp và sinh viên, tổ chức khai mạc',
                    'dien_gia' => 'Ban tổ chức'
                ],
                [
                    'thoi_gian' => '09:00 - 11:30',
                    'tieu_de' => 'Phỏng vấn trực tiếp (Phiên sáng)',
                    'mo_ta' => 'Sinh viên phỏng vấn trực tiếp với doanh nghiệp',
                    'dien_gia' => 'Đại diện doanh nghiệp'
                ],
                [
                    'thoi_gian' => '11:30 - 13:00',
                    'tieu_de' => 'Nghỉ trưa',
                    'mo_ta' => 'Nghỉ trưa và giao lưu',
                    'dien_gia' => ''
                ],
                [
                    'thoi_gian' => '13:00 - 14:30',
                    'tieu_de' => 'Hội thảo định hướng nghề nghiệp',
                    'mo_ta' => 'Chia sẻ kinh nghiệm từ các chuyên gia nhân sự',
                    'dien_gia' => 'Đại diện doanh nghiệp'
                ],
                [
                    'thoi_gian' => '14:30 - 16:30',
                    'tieu_de' => 'Phỏng vấn trực tiếp (Phiên chiều)',
                    'mo_ta' => 'Sinh viên phỏng vấn trực tiếp với doanh nghiệp',
                    'dien_gia' => 'Đại diện doanh nghiệp'
                ]
            ]
        ],
        [
            'id_su_kien' => 3,
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
            'keywords' => 'workshop, kỹ năng, phân tích dữ liệu, tài chính',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-05-10 09:45:00',
            'updated_at' => '2023-05-10 09:45:00',
            'deleted_at' => null,
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
            'so_luot_xem' => 845
        ],
        [
            'id_su_kien' => 4,
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
            'keywords' => 'cuộc thi, khởi nghiệp, ý tưởng, sinh viên',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-05-15 14:20:00',
            'updated_at' => '2023-05-15 14:20:00',
            'deleted_at' => null,
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
            'so_luot_xem' => 720
        ],
        [
            'id_su_kien' => 5,
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
            'keywords' => 'hội thảo, xu hướng, công nghệ tài chính, blockchain',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-05-20 16:00:00',
            'updated_at' => '2023-05-20 16:00:00',
            'deleted_at' => null,
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
            'so_luot_xem' => 635
        ],
        [
            'id_su_kien' => 6,
            'ten_su_kien' => 'Workshop "Kỹ năng thuyết trình chuyên nghiệp"',
            'loai_su_kien_id' => 3, // Workshop
            'chi_tiet_su_kien' => '<p>Workshop "Kỹ năng thuyết trình chuyên nghiệp" trang bị cho người tham dự những kỹ năng cần thiết để thuyết trình hiệu quả và tự tin trước đám đông, một kỹ năng quan trọng trong môi trường làm việc hiện đại.</p>',
            'bat_dau_dang_ky' => '2025-06-20 00:00:00',
            'ket_thuc_dang_ky' => '2025-07-19 23:59:59',
            'so_luong_tham_gia' => 80,
            'gioi_han_loai_nguoi_dung' => 'Sinh viên, Giảng viên',
            'tu_khoa_su_kien' => 'workshop, kỹ năng, thuyết trình, chuyên nghiệp',
            'mo_ta_su_kien' => 'Học cách thuyết trình hiệu quả và tự tin trước đám đông.',
            'hashtags' => '#PresentationSkills #PublicSpeaking #SoftSkills',
            'keywords' => 'workshop, kỹ năng, thuyết trình, chuyên nghiệp',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-05-25 13:15:00',
            'updated_at' => '2023-05-25 13:15:00',
            'deleted_at' => null,
            'ngay_to_chuc' => '2025-07-20',
            'dia_diem' => 'CS Hàm Nghi',
            'hinh_anh' => 'assets/images/event-6.jpg',
            'gio_bat_dau' => '13:30:00',
            'gio_ket_thuc' => '17:00:00',
            'so_luong_dien_gia' => 1,
            'dien_gia' => 'Chuyên gia đào tạo kỹ năng mềm',
            'thoi_gian' => '13:30 - 17:00',
            'loai_su_kien' => 'Workshop',
            'slug' => 'workshop-ky-nang-thuyet-trinh-chuyen-nghiep-6.html',
            'so_luot_xem' => 510
        ]
    ];
    
    // Mock Data - Dữ liệu mẫu cho loại sự kiện
    private $mockEventTypes = [
        [
            'id' => 1,
            'loai_su_kien' => 'Hội thảo',
            'status' => 1,
            'bin' => 0
        ],
        [
            'id' => 2,
            'loai_su_kien' => 'Nghề nghiệp',
            'status' => 1,
            'bin' => 0
        ],
        [
            'id' => 3,
            'loai_su_kien' => 'Workshop',
            'status' => 1,
            'bin' => 0
        ],
        [
            'id' => 4,
            'loai_su_kien' => 'Hoạt động sinh viên',
            'status' => 1,
            'bin' => 0
        ]
    ];

    /**
     * Lấy tất cả sự kiện
     */
    public function getAllEvents()
    {
        // Trong triển khai thực tế, bạn sẽ truy vấn từ cơ sở dữ liệu
        // Ví dụ: return $this->where('status', 1)->findAll();
        
        // Sử dụng mock data cho demo
        return $this->mockEvents;
    }
    
    /**
     * Lấy sự kiện theo ID
     */
    public function getEventById($id)
    {
        // Trong triển khai thực tế:
        // return $this->find($id);
        
        // Sử dụng mock data cho demo
        foreach ($this->mockEvents as $event) {
            if ($event['id_su_kien'] == $id) {
                return $event;
            }
        }
        return null;
    }

    /**
     * Lấy sự kiện theo ID (alias của getEventById)
     */
    public function getEvent($id)
    {
        return $this->getEventById($id);
    }
    
    /**
     * Lấy sự kiện theo slug
     */
    public function getEventBySlug($slug)
    {
        // Trong triển khai thực tế:
        // return $this->where('slug', $slug)->first();
        
        // Sử dụng mock data cho demo
        foreach ($this->mockEvents as $event) {
            if ($event['slug'] == $slug) {
                return $event;
            }
        }
        return null;
    }
    
    /**
     * Lấy sự kiện theo loại
     */
    public function getEventsByCategory($category)
    {
        // Trong triển khai thực tế:
        // return $this->join('loai_su_kien', 'loai_su_kien.id = su_kien.loai_su_kien_id')
        //             ->where('loai_su_kien.loai_su_kien', $category)
        //             ->findAll();
        
        // Sử dụng mock data cho demo
        $events = [];
        
        // Lọc sự kiện theo loại
        foreach ($this->mockEvents as $event) {
            if (strtolower($event['loai_su_kien']) == strtolower($category)) {
                $events[] = $event;
            }
        }
        
        return $events;
    }

    /**
     * Lấy danh sách sự kiện nổi bật
     * 
     * @return array
     */
    public function getFeaturedEvents()
    {
        // Trong môi trường thật, bạn sẽ thêm điều kiện WHERE để lấy sự kiện nổi bật, ví dụ: WHERE featured = 1
        // Tạm thời sử dụng dữ liệu mẫu để demo
        $featuredEvents = [];
        $i = 0;
        $currentDateTime = date('Y-m-d H:i:s');
        
        foreach ($this->mockEvents as $event) {
            if ($i < 3 && strtotime($event['ngay_to_chuc']) >= strtotime($currentDateTime)) {
                $featuredEvents[] = $event;
                $i++;
            }
        }
        
        // Sắp xếp theo ngày, sự kiện gần nhất hiển thị trước
        usort($featuredEvents, function($a, $b) {
            return strtotime($a['ngay_to_chuc']) - strtotime($b['ngay_to_chuc']);
        });
        
        return $featuredEvents;
    }
    
    /**
     * Lấy các sự kiện sắp diễn ra
     */
    public function getUpcomingEvents($limit = 5)
    {
        // Trong triển khai thực tế:
        // return $this->where('status', 1)
        //             ->where('ngay_to_chuc >=', date('Y-m-d H:i:s'))
        //             ->orderBy('ngay_to_chuc', 'ASC')
        //             ->limit($limit)
        //             ->findAll();
        
        // Sử dụng mock data cho demo
        $events = $this->mockEvents;
        usort($events, function($a, $b) {
            return strtotime($a['ngay_to_chuc']) - strtotime($b['ngay_to_chuc']);
        });
        
        // Lấy số lượng sự kiện theo limit
        return array_slice($events, 0, $limit);
    }
    
    /**
     * Lấy tổng số sự kiện
     * 
     * @return int
     */
    public function getTotalEvents()
    {
        // Trong môi trường thật, đây sẽ là một lệnh COUNT() từ database
        return count($this->mockEvents);
    }

    /**
     * Lấy tổng số người tham gia
     * 
     * @return int
     */
    public function getTotalParticipants()
    {
        // Trong môi trường thật, đây sẽ là một lệnh SUM() từ database
        return 2500; // Giả định có 2500 người đã tham gia
    }

    /**
     * Lấy tổng số diễn giả
     * 
     * @return int
     */
    public function getTotalSpeakers()
    {
        // Trong môi trường thật, đây sẽ là một lệnh COUNT() từ database
        return 45; // Giả định có 45 diễn giả
    }
    
    /**
     * Lấy các sự kiện liên quan
     */
    public function getRelatedEvents($eventId, $eventType, $limit = 3)
    {
        // Trong triển khai thực tế, bạn có thể lấy các sự kiện cùng loại:
        // $event = $this->find($eventId);
        // if (!$event) return [];
        // 
        // return $this->where('loai_su_kien_id', $event['loai_su_kien_id'])
        //             ->where('id !=', $eventId)
        //             ->limit($limit)
        //             ->findAll();
        
        // Sử dụng mock data cho demo
        $relatedEvents = [];
        $count = 0;
        
        // Tìm sự kiện cùng loại
        foreach ($this->mockEvents as $event) {
            if ($event['id_su_kien'] != $eventId && $event['loai_su_kien'] == $eventType && $count < $limit) {
                $relatedEvents[] = $event;
                $count++;
            }
        }
        
        // Nếu không đủ sự kiện cùng loại, lấy thêm các sự kiện khác
        if ($count < $limit) {
            foreach ($this->mockEvents as $event) {
                if ($event['id_su_kien'] != $eventId && !in_array($event, $relatedEvents) && $count < $limit) {
                    $relatedEvents[] = $event;
                    $count++;
                }
            }
        }
        
        return $relatedEvents;
    }
    
    /**
     * Tạo slug từ tên sự kiện và ID
     */
    public function createSlug($eventName, $eventId)
    {
        // Chuyển đổi tất cả các ký tự thành chữ thường
        $slug = strtolower($eventName);
        
        // Thay thế các ký tự không phải chữ cái, số bằng dấu gạch ngang
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Thêm ID vào cuối để đảm bảo tính duy nhất và thêm .html vào cuối
        return $slug . '-' . $eventId . '.html';
    }
    
    /**
     * Cập nhật slug cho tất cả sự kiện
     */
    public function updateAllSlugs()
    {
        // Trong triển khai thực tế:
        // $events = $this->findAll();
        // foreach ($events as $event) {
        //     $slug = $this->createSlug($event['ten_su_kien'], $event['id']);
        //     $this->update($event['id'], ['slug' => $slug]);
        // }
        
        // Sử dụng mock data cho demo
        foreach ($this->mockEvents as &$event) {
            $event['slug'] = $this->createSlug($event['ten_su_kien'], $event['id_su_kien']);
        }
        
        return true;
    }

    /**
     * Tìm kiếm sự kiện theo từ khóa
     * Hỗ trợ tìm kiếm theo tên, mô tả, từ khóa và hashtag
     */
    public function searchEvents($keyword)
    {
        // Chuẩn bị từ khóa tìm kiếm
        $keyword = strtolower(trim($keyword));
        
        // Trong triển khai thực tế:
        // return $this->like('ten_su_kien', $keyword)
        //             ->orLike('mo_ta_su_kien', $keyword)
        //             ->orLike('tu_khoa_su_kien', $keyword)
        //             ->orLike('hashtag', $keyword)
        //             ->where('status', 1)
        //             ->findAll();
        
        // Sử dụng mock data cho demo
        $results = [];
        
        foreach ($this->mockEvents as $event) {
            // Tìm kiếm trong các trường: tên, mô tả, từ khóa, hashtag
            if (
                strpos(strtolower($event['ten_su_kien']), $keyword) !== false ||
                strpos(strtolower($event['mo_ta_su_kien']), $keyword) !== false ||
                strpos(strtolower($event['tu_khoa_su_kien']), $keyword) !== false ||
                (isset($event['hashtags']) && strpos(strtolower($event['hashtags']), $keyword) !== false)
            ) {
                $results[] = $event;
            }
        }
        
        return $results;
    }

    /**
     * Lấy danh sách loại sự kiện
     * 
     * @return array
     */
    public function getEventTypes()
    {
        // Trong triển khai thực tế, bạn sẽ lấy dữ liệu từ bảng loại_su_kien:
        // return $this->db->table('loai_su_kien')->where('status', 1)->get()->getResultArray();
        
        // Sử dụng mock data cho demo
        $eventTypes = $this->mockEventTypes;
        
        // Thêm trường slug cho mỗi loại sự kiện
        foreach ($eventTypes as &$type) {
            $type['slug'] = strtolower(str_replace(' ', '-', $type['loai_su_kien']));
        }
        
        return $eventTypes;
    }
    
    /**
     * Dữ liệu mẫu diễn giả sự kiện
     * 
     * @var array
     */
    private $mockSpeakers = [
        [
            'id' => 1,
            'name' => 'TS. Nguyễn Đình Thọ',
            'position' => 'Hiệu trưởng Trường ĐH Ngân hàng TP.HCM',
            'image' => 'assets/images/speaker-1.jpg',
            'bio' => 'TS. Nguyễn Đình Thọ có hơn 25 năm kinh nghiệm trong lĩnh vực giáo dục và quản lý. Ông tốt nghiệp Tiến sĩ Kinh tế tại Đại học Kinh tế TP.HCM và đã có nhiều công trình nghiên cứu về tài chính ngân hàng.'
        ],
        [
            'id' => 2,
            'name' => 'TS. Lê Phan Quốc',
            'position' => 'Giám đốc Techcombank',
            'image' => 'assets/images/speaker-2.jpg',
            'bio' => 'TS. Lê Phan Quốc là chuyên gia hàng đầu về ngân hàng số tại Việt Nam. Với hơn 20 năm kinh nghiệm trong lĩnh vực ngân hàng, ông đã góp phần quan trọng trong việc chuyển đổi số tại Techcombank.'
        ],
        [
            'id' => 3,
            'name' => 'TS. Nguyễn Thị Minh Hương',
            'position' => 'Phó Tổng giám đốc Vietcombank',
            'image' => 'assets/images/speaker-3.jpg',
            'bio' => 'TS. Nguyễn Thị Minh Hương là chuyên gia về quản trị rủi ro ngân hàng. Với nền tảng học vấn vững chắc từ Đại học Oxford và hơn 15 năm kinh nghiệm, bà đã giúp Vietcombank xây dựng hệ thống quản trị rủi ro hiện đại.'
        ],
        [
            'id' => 4,
            'name' => 'ThS. Trần Văn Nam',
            'position' => 'Chuyên gia Blockchain',
            'image' => 'assets/images/speaker-4.jpg',
            'bio' => 'ThS. Trần Văn Nam là sáng lập viên của một startup fintech thành công tại Việt Nam. Ông là chuyên gia về công nghệ blockchain và đã có nhiều bài viết, nghiên cứu về tiềm năng của blockchain trong lĩnh vực tài chính.'
        ],
        [
            'id' => 5,
            'name' => 'PGS.TS. Trần Hùng Sơn',
            'position' => 'Trưởng Khoa CNTT - ĐH Ngân hàng',
            'image' => 'assets/images/speaker-5.jpg',
            'bio' => 'PGS.TS. Trần Hùng Sơn có hơn 20 năm kinh nghiệm giảng dạy và nghiên cứu về công nghệ thông tin. Ông là tác giả của nhiều công trình nghiên cứu về ứng dụng AI trong tài chính ngân hàng.'
        ],
        [
            'id' => 6,
            'name' => 'TS. Nguyễn Thanh Bình',
            'position' => 'Giám đốc MoMo',
            'image' => 'assets/images/speaker-6.jpg',
            'bio' => 'TS. Nguyễn Thanh Bình là người có nhiều đóng góp cho sự phát triển của ví điện tử tại Việt Nam. Ông đã giúp MoMo trở thành một trong những ví điện tử hàng đầu tại Việt Nam.'
        ]
    ];
    
    /**
     * Lấy danh sách diễn giả nổi bật
     * 
     * @param int $limit Số lượng diễn giả cần lấy
     * @return array
     */
    public function getSpeakers($limit = 4)
    {
        // Trong triển khai thực tế, bạn sẽ lấy dữ liệu từ bảng dien_gia:
        // return $this->db->table('dien_gia')
        //                ->where('status', 1)
        //                ->orderBy('created_at', 'DESC')
        //                ->limit($limit)
        //                ->get()
        //                ->getResultArray();
        
        // Sử dụng mock data cho demo
        return array_slice($this->mockSpeakers, 0, $limit);
    }
    
    /**
     * Lấy thông tin diễn giả theo ID
     * 
     * @param int $id ID của diễn giả
     * @return array|null
     */
    public function getSpeakerById($id)
    {
        // Trong triển khai thực tế:
        // return $this->db->table('dien_gia')->where('id', $id)->get()->getRowArray();
        
        // Sử dụng mock data cho demo
        foreach ($this->mockSpeakers as $speaker) {
            if ($speaker['id'] == $id) {
                return $speaker;
            }
        }
        
        return null;
    }

    /**
     * Định dạng ngày tổ chức với giờ phút giây
     * 
     * @param string $ngayToChuc Ngày tổ chức dạng Y-m-d H:i:s
     * @param string $format Định dạng đầu ra
     * @return string
     */
    public function formatNgayToChuc($ngayToChuc, $format = 'd/m/Y H:i')
    {
        if (empty($ngayToChuc)) {
            return '';
        }
        
        $timestamp = strtotime($ngayToChuc);
        return date($format, $timestamp);
    }
    
    /**
     * Lấy thời gian còn lại đến sự kiện
     * 
     * @param string $ngayToChuc Ngày tổ chức dạng Y-m-d H:i:s
     * @return array Mảng chứa số ngày, giờ, phút, giây còn lại
     */
    public function getTimeRemaining($ngayToChuc)
    {
        $eventTime = strtotime($ngayToChuc);
        $currentTime = time();
        $timeRemaining = $eventTime - $currentTime;
        
        if ($timeRemaining <= 0) {
            return [
                'days' => 0,
                'hours' => 0,
                'minutes' => 0,
                'seconds' => 0,
                'total' => 0
            ];
        }
        
        $days = floor($timeRemaining / 86400);
        $hours = floor(($timeRemaining % 86400) / 3600);
        $minutes = floor(($timeRemaining % 3600) / 60);
        $seconds = $timeRemaining % 60;
        
        return [
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds,
            'total' => $timeRemaining
        ];
    }

    /**
     * Dữ liệu mẫu cho đăng ký sự kiện
     * 
     * @var array
     */
    private $mockRegistrations = [
        [
            'id' => 1,
            'su_kien_id' => 1,
            'user_id' => 101,
            'ho_ten' => 'Nguyễn Văn An',
            'email' => 'nguyenvanan@gmail.com',
            'so_dien_thoai' => '0912345678',
            'ma_sv' => '1912345',
            'khoa' => 'Khoa Ngân hàng',
            'lop' => 'NH19A',
            'trang_thai' => 1, // 1: Đã xác nhận, 0: Chưa xác nhận
            'ghi_chu' => 'Đã xác nhận tham gia',
            'ngay_dang_ky' => '2023-05-20 09:15:30',
            'da_tham_gia' => 1
        ],
        [
            'id' => 2,
            'su_kien_id' => 1,
            'user_id' => 102,
            'ho_ten' => 'Trần Thị Bình',
            'email' => 'tranthib@gmail.com',
            'so_dien_thoai' => '0923456789',
            'ma_sv' => '1923456',
            'khoa' => 'Khoa Tài chính',
            'lop' => 'TC19B',
            'trang_thai' => 1,
            'ghi_chu' => 'Xác nhận qua email',
            'ngay_dang_ky' => '2023-05-21 14:30:45',
            'da_tham_gia' => 0
        ],
        [
            'id' => 3,
            'su_kien_id' => 1,
            'user_id' => 103,
            'ho_ten' => 'Lê Văn Cường',
            'email' => 'levanc@gmail.com',
            'so_dien_thoai' => '0934567890',
            'ma_sv' => '1934567',
            'khoa' => 'Khoa Kinh tế',
            'lop' => 'KT19C',
            'trang_thai' => 0,
            'ghi_chu' => 'Chờ xác nhận',
            'ngay_dang_ky' => '2023-05-22 10:45:15',
            'da_tham_gia' => 0
        ],
        [
            'id' => 4,
            'su_kien_id' => 2,
            'user_id' => 104,
            'ho_ten' => 'Phạm Thị Dung',
            'email' => 'phamthid@gmail.com',
            'so_dien_thoai' => '0945678901',
            'ma_sv' => '1945678',
            'khoa' => 'Khoa Quản trị kinh doanh',
            'lop' => 'QT19D',
            'trang_thai' => 1,
            'ghi_chu' => 'Đã xác nhận tham gia',
            'ngay_dang_ky' => '2023-05-25 16:20:10',
            'da_tham_gia' => 1
        ],
        [
            'id' => 5,
            'su_kien_id' => 2,
            'user_id' => 105,
            'ho_ten' => 'Hoàng Văn Em',
            'email' => 'hoangvane@gmail.com',
            'so_dien_thoai' => '0956789012',
            'ma_sv' => '1956789',
            'khoa' => 'Khoa Công nghệ thông tin',
            'lop' => 'IT19E',
            'trang_thai' => 1,
            'ghi_chu' => 'Đã xác nhận qua SMS',
            'ngay_dang_ky' => '2023-05-26 08:10:05',
            'da_tham_gia' => 0
        ]
    ];

    /**
     * Tăng số lượt xem cho sự kiện
     * 
     * @param int $eventId ID của sự kiện
     * @return bool
     */
    public function incrementViews($eventId)
    {
        // Trong triển khai thực tế:
        // $event = $this->find($eventId);
        // if (!$event) return false;
        // 
        // $currentViews = $event['so_luot_xem'] ?? 0;
        // return $this->update($eventId, ['so_luot_xem' => $currentViews + 1]);
        
        // Sử dụng mock data cho demo
        foreach ($this->mockEvents as &$event) {
            if ($event['id_su_kien'] == $eventId) {
                $event['so_luot_xem'] = isset($event['so_luot_xem']) ? $event['so_luot_xem'] + 1 : 1;
                return true;
            }
        }
        
        return false;
    }

    /**
     * Lấy số lượt xem của sự kiện
     * 
     * @param int $eventId ID của sự kiện
     * @return int
     */
    public function getViews($eventId)
    {
        // Trong triển khai thực tế:
        // $event = $this->find($eventId);
        // return $event ? ($event['so_luot_xem'] ?? 0) : 0;
        
        // Sử dụng mock data cho demo
        foreach ($this->mockEvents as $event) {
            if ($event['id_su_kien'] == $eventId) {
                return $event['so_luot_xem'] ?? 0;
            }
        }
        
        return 0;
    }

    /**
     * Lấy danh sách người đăng ký tham gia sự kiện
     * 
     * @param int $eventId ID của sự kiện
     * @return array
     */
    public function getRegistrations($eventId)
    {
        // Trong triển khai thực tế:
        // return $this->db->table('dang_ky_su_kien')
        //                 ->where('su_kien_id', $eventId)
        //                 ->get()
        //                 ->getResultArray();
        
        // Sử dụng mock data cho demo
        $registrations = [];
        
        foreach ($this->mockRegistrations as $registration) {
            if ($registration['su_kien_id'] == $eventId) {
                $registrations[] = $registration;
            }
        }
        
        return $registrations;
    }

    /**
     * Đăng ký tham gia sự kiện
     * 
     * @param array $data Thông tin đăng ký
     * @return bool|int
     */
    public function registerEvent($data)
    {
        // Trong triển khai thực tế:
        // return $this->db->table('dang_ky_su_kien')->insert($data);
        
        // Sử dụng mock data cho demo
        $lastId = end($this->mockRegistrations)['id'] ?? 0;
        $newId = $lastId + 1;
        
        $data['id'] = $newId;
        $data['ngay_dang_ky'] = date('Y-m-d H:i:s');
        $data['trang_thai'] = 0; // Mặc định chưa xác nhận
        $data['da_tham_gia'] = 0; // Mặc định chưa tham gia
        
        $this->mockRegistrations[] = $data;
        
        return $newId;
    }

    /**
     * Hủy đăng ký tham gia sự kiện
     * 
     * @param int $userId ID của người dùng
     * @param int $eventId ID của sự kiện
     * @return bool
     */
    public function cancelRegistration($userId, $eventId)
    {
        // Trong triển khai thực tế:
        // return $this->db->table('dang_ky_su_kien')
        //                 ->where('user_id', $userId)
        //                 ->where('su_kien_id', $eventId)
        //                 ->delete();
        
        // Sử dụng mock data cho demo
        foreach ($this->mockRegistrations as $key => $registration) {
            if ($registration['user_id'] == $userId && $registration['su_kien_id'] == $eventId) {
                unset($this->mockRegistrations[$key]);
                return true;
            }
        }
        
        return false;
    }

    /**
     * Kiểm tra người dùng đã đăng ký sự kiện chưa
     * 
     * @param int $userId ID của người dùng
     * @param int $eventId ID của sự kiện
     * @return bool
     */
    public function isRegistered($userId, $eventId)
    {
        // Trong triển khai thực tế:
        // $count = $this->db->table('dang_ky_su_kien')
        //                   ->where('user_id', $userId)
        //                   ->where('su_kien_id', $eventId)
        //                   ->countAllResults();
        // return $count > 0;
        
        // Sử dụng mock data cho demo
        foreach ($this->mockRegistrations as $registration) {
            if ($registration['user_id'] == $userId && $registration['su_kien_id'] == $eventId) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Lấy số lượng đăng ký cho sự kiện
     * 
     * @param int $eventId ID của sự kiện
     * @return int
     */
    public function getRegistrationCount($eventId)
    {
        // Trong triển khai thực tế:
        // return $this->db->table('dang_ky_su_kien')
        //                 ->where('su_kien_id', $eventId)
        //                 ->countAllResults();
        
        // Sử dụng mock data cho demo
        $count = 0;
        
        foreach ($this->mockRegistrations as $registration) {
            if ($registration['su_kien_id'] == $eventId) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Lấy thông tin đăng ký của người dùng
     * 
     * @param int $userId ID của người dùng
     * @param int $eventId ID của sự kiện
     * @return array|null
     */
    public function getUserRegistration($userId, $eventId)
    {
        // Trong triển khai thực tế:
        // return $this->db->table('dang_ky_su_kien')
        //                 ->where('user_id', $userId)
        //                 ->where('su_kien_id', $eventId)
        //                 ->get()
        //                 ->getRowArray();
        
        // Sử dụng mock data cho demo
        foreach ($this->mockRegistrations as $registration) {
            if ($registration['user_id'] == $userId && $registration['su_kien_id'] == $eventId) {
                return $registration;
            }
        }
        
        return null;
    }

    /**
     * Cập nhật trạng thái tham gia sự kiện
     * 
     * @param int $registrationId ID của đăng ký
     * @param int $status Trạng thái tham gia (1: Đã tham gia, 0: Chưa tham gia)
     * @return bool
     */
    public function updateAttendance($registrationId, $status)
    {
        // Trong triển khai thực tế:
        // return $this->db->table('dang_ky_su_kien')
        //                 ->where('id', $registrationId)
        //                 ->update(['da_tham_gia' => $status]);
        
        // Sử dụng mock data cho demo
        foreach ($this->mockRegistrations as &$registration) {
            if ($registration['id'] == $registrationId) {
                $registration['da_tham_gia'] = $status;
                return true;
            }
        }
        
        return false;
    }

    /**
     * Lấy lịch trình của sự kiện
     * 
     * @param int $eventId ID của sự kiện
     * @return array
     */
    public function getEventSchedule($eventId)
    {
        // Trong triển khai thực tế:
        // $event = $this->find($eventId);
        // return $event && isset($event['lich_trinh']) ? json_decode($event['lich_trinh'], true) : [];
        
        // Sử dụng mock data cho demo
        foreach ($this->mockEvents as $event) {
            if ($event['id_su_kien'] == $eventId) {
                return isset($event['lich_trinh']) ? $event['lich_trinh'] : [];
            }
        }
        
        return [];
    }
} 