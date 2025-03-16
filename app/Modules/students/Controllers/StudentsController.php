<?php

namespace App\Modules\students\Controllers;

use App\Controllers\BaseController;

class StudentsController extends BaseController
{
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard Sinh viên',
            'student_data' => [
                'fullname' => session()->get('student_name'),
                'student_id' => session()->get('student_id'),
                'picture' => null
            ],
            'active_events' => 5,
            'registered_events' => 3,
            'certificates' => 2,
            'upcoming_events' => 8,
            'events' => [
                [
                    'id' => 1,
                    'name' => 'Ngày hội việc làm 2024',
                    'time' => '20/03/2024, 9:00 - 16:00',
                    'location' => 'Hội trường A',
                    'status' => 'Sắp diễn ra',
                    'status_color' => 'primary'
                ],
                [
                    'id' => 2,
                    'name' => 'Hội thảo kỹ năng mềm',
                    'time' => '25/03/2024, 14:00 - 17:00',
                    'location' => 'Hội trường B',
                    'status' => 'Sắp diễn ra',
                    'status_color' => 'primary'
                ],
                [
                    'id' => 3,
                    'name' => 'Workshop Blockchain cơ bản',
                    'time' => '30/03/2024, 8:30 - 11:30',
                    'location' => 'Phòng Lab A1',
                    'status' => 'Đang mở đăng ký',
                    'status_color' => 'success'
                ]
            ],
            'recent_notifications' => [
                [
                    'type' => 'success',
                    'icon' => 'bx bx-check-circle',
                    'title' => 'Đăng ký thành công',
                    'content' => 'Bạn đã đăng ký thành công sự kiện Ngày hội việc làm 2024',
                    'time' => '2 giờ trước'
                ],
                [
                    'type' => 'info',
                    'icon' => 'bx bx-info-circle',
                    'title' => 'Sự kiện mới',
                    'content' => 'Hội thảo kỹ năng mềm đã được thêm vào hệ thống',
                    'time' => '1 ngày trước'
                ]
            ],
            'notification_count' => 2
        ];

        return view('App\Modules\students\Views\dashboard', $data);
    }
} 