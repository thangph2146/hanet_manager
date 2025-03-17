<?php

namespace App\Modules\students\Controllers;

use CodeIgniter\Controller;
use App\Modules\students\Models\SukienModel;
use App\Modules\students\Models\LoaiSukienModel;
use App\Modules\students\Models\DangKySukienModel;
use App\Modules\students\Models\CheckinSukienModel;
use App\Modules\students\Models\CheckoutSukienModel;

class Dashboard extends Controller
{
    protected $sukienModel;
    protected $loaiSukienModel;
    protected $dangKySukienModel;
    protected $checkinSukienModel;
    protected $checkoutSukienModel;
    
    public function __construct()
    {
        // Khởi tạo các model
        $this->sukienModel = new SukienModel();
        $this->loaiSukienModel = new LoaiSukienModel();
        $this->dangKySukienModel = new DangKySukienModel();
        $this->checkinSukienModel = new CheckinSukienModel();
        $this->checkoutSukienModel = new CheckoutSukienModel();
    }
    
    public function index()
    {
        // Lấy thông tin sinh viên hiện tại (giả sử đã có session với user_id)
        $studentId = session()->get('user_id');
        
        if (!$studentId) {
            return redirect()->to(base_url('login'));
        }
        
        // Chuẩn bị dữ liệu cho dashboard
        $data = [
            'title' => 'Dashboard Sinh viên',
            'page_css' => ['dashboard'],
            'page_js' => ['dashboard'],
        ];
        
        // Thống kê
        $data['stats'] = [
            'registered_events' => $this->dangKySukienModel->where('user_id', $studentId)->countAllResults(),
            'completed_events' => $this->checkoutSukienModel->where('user_id', $studentId)->countAllResults(),
            'upcoming_events' => $this->sukienModel->where('ngay_to_chuc >=', date('Y-m-d H:i:s'))->countAllResults(),
            'total_points' => $this->calculateTotalPoints($studentId),
        ];
        
        // Tính phần trăm hoàn thành
        if ($data['stats']['registered_events'] > 0) {
            $data['stats']['completed_percentage'] = round(($data['stats']['completed_events'] / $data['stats']['registered_events']) * 100);
        }
        
        // Lấy danh sách sự kiện sắp diễn ra
        $data['upcoming_events'] = $this->getUpcomingEvents($studentId, 5);
        
        // Lấy danh sách thông báo
        $data['announcements'] = $this->getRecentAnnouncements(3);
        
        // Lấy ngày có sự kiện cho lịch
        $data['event_days'] = $this->getEventDays();
        
        return view('Modules/students/Views/dashboard/index', $data);
    }
    
    /**
     * Lấy danh sách sự kiện sắp diễn ra
     *
     * @param int $studentId ID của sinh viên
     * @param int $limit Số lượng sự kiện muốn lấy
     * @return array Danh sách sự kiện
     */
    private function getUpcomingEvents($studentId, $limit = 5)
    {
        // Lấy danh sách sự kiện sắp diễn ra
        $events = $this->sukienModel
            ->select('sukien.*, loai_sukien.loai_su_kien')
            ->join('loai_sukien', 'loai_sukien.id = sukien.loai_sukien_id', 'left')
            ->where('ngay_to_chuc >=', date('Y-m-d H:i:s'))
            ->orderBy('ngay_to_chuc', 'ASC')
            ->limit($limit)
            ->findAll();
        
        // Nếu không có sự kiện, trả về mảng rỗng
        if (empty($events)) {
            return [];
        }
        
        // Kiểm tra xem sinh viên đã đăng ký các sự kiện này chưa
        foreach ($events as &$event) {
            $event['is_registered'] = $this->dangKySukienModel
                ->where('user_id', $studentId)
                ->where('su_kien_id', $event['id'])
                ->countAllResults() > 0;
        }
        
        return $events;
    }
    
    /**
     * Lấy danh sách thông báo gần đây
     *
     * @param int $limit Số lượng thông báo muốn lấy
     * @return array Danh sách thông báo
     */
    private function getRecentAnnouncements($limit = 3)
    {
        // Đây là dữ liệu mẫu, bạn cần thay thế bằng dữ liệu thực từ cơ sở dữ liệu
        $announcements = [
            [
                'title' => 'Thông báo đăng ký học phần học kỳ 2',
                'content' => 'Thông báo về việc đăng ký học phần học kỳ 2 năm học 2023-2024. Sinh viên đăng ký từ ngày 01/12/2023.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'author_name' => 'Phòng Đào tạo',
                'author_avatar' => 'assets/img/avatar-default.png'
            ],
            [
                'title' => 'Lịch thi học kỳ 1 năm học 2023-2024',
                'content' => 'Phòng Đào tạo thông báo lịch thi học kỳ 1 năm học 2023-2024. Sinh viên xem lịch thi từ ngày 15/11/2023.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 day')),
                'author_name' => 'Phòng Khảo thí',
                'author_avatar' => 'assets/img/avatar-default.png'
            ],
            [
                'title' => 'Thông báo về việc nghỉ lễ 20/11',
                'content' => 'Thông báo về việc nghỉ lễ 20/11/2023. Sinh viên nghỉ học từ ngày 20/11/2023 đến hết ngày 20/11/2023.',
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 day')),
                'author_name' => 'Phòng Công tác sinh viên',
                'author_avatar' => 'assets/img/avatar-default.png'
            ]
        ];
        
        return $announcements;
    }
    
    /**
     * Lấy danh sách ngày có sự kiện cho lịch
     *
     * @return array Danh sách ngày có sự kiện
     */
    private function getEventDays()
    {
        $events = $this->sukienModel
            ->select('DATE(ngay_to_chuc) as event_date')
            ->findAll();
        
        $eventDays = [];
        foreach ($events as $event) {
            if (isset($event['event_date'])) {
                $eventDays[] = $event['event_date'];
            }
        }
        
        return array_unique($eventDays);
    }
    
    /**
     * Tính tổng điểm hoạt động của sinh viên
     *
     * @param int $studentId ID của sinh viên
     * @return int Tổng điểm
     */
    private function calculateTotalPoints($studentId)
    {
        // Đây là logic mẫu, bạn cần thay thế bằng logic thực tế của ứng dụng
        // Ví dụ: Mỗi sự kiện hoàn thành được 5 điểm
        $completedEvents = $this->checkoutSukienModel
            ->where('user_id', $studentId)
            ->countAllResults();
        
        return $completedEvents * 5;
    }
} 