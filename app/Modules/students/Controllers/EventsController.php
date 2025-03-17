<?php

namespace App\Modules\students\Controllers;

use App\Controllers\BaseController;

class EventsController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Danh sách sự kiện',
            'events' => [
                [
                    'id' => 1,
                    'name' => 'Ngày hội việc làm 2024',
                    'time' => '20/03/2024, 9:00 - 16:00',
                    'location' => 'Hội trường A',
                    'status' => 'Sắp diễn ra',
                    'status_color' => 'primary',
                    'description' => 'Sự kiện kết nối sinh viên với các doanh nghiệp hàng đầu.',
                    'banner' => 'banner1.jpg'
                ],
                [
                    'id' => 2,
                    'name' => 'Hội thảo kỹ năng mềm',
                    'time' => '25/03/2024, 14:00 - 17:00',
                    'location' => 'Hội trường B',
                    'status' => 'Sắp diễn ra',
                    'status_color' => 'primary',
                    'description' => 'Hội thảo đào tạo kỹ năng mềm cho sinh viên.',
                    'banner' => 'banner2.jpg'
                ],
                [
                    'id' => 3,
                    'name' => 'Workshop Blockchain cơ bản',
                    'time' => '30/03/2024, 8:30 - 11:30',
                    'location' => 'Phòng Lab A1',
                    'status' => 'Đang mở đăng ký',
                    'status_color' => 'success',
                    'description' => 'Workshop giới thiệu về công nghệ Blockchain.',
                    'banner' => 'banner3.jpg'
                ]
            ]
        ];

        return view('App\Modules\students\Views\events\index', $data);
    }

    public function view($id)
    {
        // Mô phỏng dữ liệu từ database
        $events = [
            1 => [
                'id' => 1,
                'name' => 'Ngày hội việc làm 2024',
                'time' => '20/03/2024, 9:00 - 16:00',
                'location' => 'Hội trường A',
                'status' => 'Sắp diễn ra',
                'status_color' => 'primary',
                'description' => 'Sự kiện kết nối sinh viên với các doanh nghiệp hàng đầu.',
                'banner' => 'banner1.jpg',
                'details' => 'Ngày hội việc làm 2024 là sự kiện thường niên của Trường Đại học Ngân hàng TP.HCM, kết nối sinh viên với hơn 50 doanh nghiệp hàng đầu trong các lĩnh vực tài chính, ngân hàng, kế toán, kiểm toán, công nghệ thông tin, v.v.',
                'companies' => ['VPBank', 'Vietcombank', 'KPMG', 'PwC', 'FPT', 'VNG']
            ],
            2 => [
                'id' => 2,
                'name' => 'Hội thảo kỹ năng mềm',
                'time' => '25/03/2024, 14:00 - 17:00',
                'location' => 'Hội trường B',
                'status' => 'Sắp diễn ra',
                'status_color' => 'primary',
                'description' => 'Hội thảo đào tạo kỹ năng mềm cho sinh viên.',
                'banner' => 'banner2.jpg',
                'details' => 'Hội thảo kỹ năng mềm là sự kiện giúp sinh viên rèn luyện các kỹ năng cần thiết cho công việc sau khi tốt nghiệp, như kỹ năng giao tiếp, kỹ năng thuyết trình, kỹ năng làm việc nhóm, kỹ năng giải quyết vấn đề, v.v.',
                'speakers' => ['TS. Nguyễn Văn A', 'ThS. Trần Thị B', 'Chuyên gia Lê Văn C']
            ],
            3 => [
                'id' => 3,
                'name' => 'Workshop Blockchain cơ bản',
                'time' => '30/03/2024, 8:30 - 11:30',
                'location' => 'Phòng Lab A1',
                'status' => 'Đang mở đăng ký',
                'status_color' => 'success',
                'description' => 'Workshop giới thiệu về công nghệ Blockchain.',
                'banner' => 'banner3.jpg',
                'details' => 'Workshop Blockchain cơ bản giúp sinh viên hiểu về công nghệ Blockchain, các ứng dụng của Blockchain trong lĩnh vực tài chính, ngân hàng, và các lĩnh vực khác.',
                'speakers' => ['TS. Phạm Văn X', 'KS. Nguyễn Thị Y', 'Chuyên gia Trần Văn Z']
            ]
        ];

        $data = [
            'title' => 'Chi tiết sự kiện',
            'event' => $events[$id] ?? null
        ];

        if ($data['event'] === null) {
            return redirect()->to(base_url('students/events'))->with('error', 'Sự kiện không tồn tại');
        }

        return view('App\Modules\students\Views\events\view', $data);
    }

    public function register($id)
    {
        // Lấy thông tin sự kiện
        $event = $this->getEvent($id);
        
        if ($event === null) {
            return redirect()->to(base_url('students/events'))->with('error', 'Sự kiện không tồn tại');
        }
        
        $data = [
            'title' => 'Đăng ký sự kiện',
            'event' => $event
        ];
        
        return view('App\Modules\students\Views\events\register', $data);
    }
    
    public function saveRegistration($id)
    {
        // Lấy thông tin sự kiện
        $event = $this->getEvent($id);
        
        if ($event === null) {
            return redirect()->to(base_url('students/events'))->with('error', 'Sự kiện không tồn tại');
        }
        
        // Xử lý form đăng ký
        // Trong thực tế, bạn sẽ lưu thông tin đăng ký vào database
        
        return redirect()->to(base_url('students/registrations'))->with('success', 'Đăng ký sự kiện thành công');
    }
    
    /**
     * Hàm lấy thông tin sự kiện theo ID
     * Trong thực tế, bạn sẽ lấy thông tin từ model
     */
    private function getEvent($id)
    {
        $events = [
            1 => [
                'id' => 1,
                'name' => 'Ngày hội việc làm 2024',
                'time' => '20/03/2024, 9:00 - 16:00',
                'location' => 'Hội trường A',
                'status' => 'Sắp diễn ra',
                'status_color' => 'primary',
                'description' => 'Sự kiện kết nối sinh viên với các doanh nghiệp hàng đầu.',
                'banner' => 'banner1.jpg',
                'details' => 'Ngày hội việc làm 2024 là sự kiện thường niên của Trường Đại học Ngân hàng TP.HCM.'
            ],
            2 => [
                'id' => 2,
                'name' => 'Hội thảo kỹ năng mềm',
                'time' => '25/03/2024, 14:00 - 17:00',
                'location' => 'Hội trường B',
                'status' => 'Sắp diễn ra',
                'status_color' => 'primary',
                'description' => 'Hội thảo đào tạo kỹ năng mềm cho sinh viên.',
                'banner' => 'banner2.jpg',
                'details' => 'Hội thảo kỹ năng mềm là sự kiện giúp sinh viên rèn luyện các kỹ năng cần thiết.'
            ],
            3 => [
                'id' => 3,
                'name' => 'Workshop Blockchain cơ bản',
                'time' => '30/03/2024, 8:30 - 11:30',
                'location' => 'Phòng Lab A1',
                'status' => 'Đang mở đăng ký',
                'status_color' => 'success',
                'description' => 'Workshop giới thiệu về công nghệ Blockchain.',
                'banner' => 'banner3.jpg',
                'details' => 'Workshop Blockchain cơ bản giúp sinh viên hiểu về công nghệ Blockchain.'
            ]
        ];
        
        return $events[$id] ?? null;
    }
} 