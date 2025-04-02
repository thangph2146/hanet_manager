<?php

namespace App\Controllers;

use App\Modules\sukien\Models\SuKienModel;

class Home extends BaseController
{
    public function index()
    {
        // Khởi tạo SukienModel để lấy dữ liệu
        $sukienModel = new SuKienModel();
        
        // Lấy 6 sự kiện sắp diễn ra gần nhất (dành cho phần upcoming events)
        $upcoming_events = $sukienModel->getUpcomingEvents(6);
        
        // Tìm sự kiện sắp diễn ra gần nhất
        $job_fair_event = null;
        $current_time = time();
        
        // Lọc các sự kiện chưa kết thúc và sắp xếp theo thời gian bắt đầu
        $valid_events = array_filter($upcoming_events, function($event) use ($current_time) {
            $event_start_time = strtotime($event['ngay_to_chuc'] . ' ' . $event['gio_bat_dau']);
            $event_end_time = strtotime($event['ngay_to_chuc'] . ' ' . $event['gio_ket_thuc']);
            return $event_end_time > $current_time;
        });
        
        // Sắp xếp theo thời gian bắt đầu
        usort($valid_events, function($a, $b) {
            $time_a = strtotime($a['ngay_to_chuc'] . ' ' . $a['gio_bat_dau']);
            $time_b = strtotime($b['ngay_to_chuc'] . ' ' . $b['gio_bat_dau']);
            return $time_a - $time_b;
        });
        
        // Lấy sự kiện gần nhất
        if (!empty($valid_events)) {
            $job_fair_event = reset($valid_events);
            
            // Kiểm tra xem sự kiện đã bắt đầu chưa
            $event_start_time = strtotime($job_fair_event['ngay_to_chuc'] . ' ' . $job_fair_event['gio_bat_dau']);
            
            // Nếu sự kiện đã bắt đầu, tìm sự kiện tiếp theo
            if ($current_time > $event_start_time) {
                // Bỏ qua sự kiện hiện tại và lấy sự kiện tiếp theo
                next($valid_events);
                $next_event = current($valid_events);
                if ($next_event) {
                    $job_fair_event = $next_event;
                }
            }
            
            // Thêm thông tin đăng ký cho sự kiện nổi bật
            if ($job_fair_event && isset($job_fair_event['su_kien_id'])) {
                $registrations = $sukienModel->getRegistrations($job_fair_event['su_kien_id']);
                $job_fair_event['registration_count'] = count($registrations);
            }
        }
        
        // Lấy thông tin counter
        $stats = [
            'total_events' => $sukienModel->getTotalEvents(),
            'total_participants' => $sukienModel->getTotalParticipants(),
            'total_speakers' => $sukienModel->getTotalSpeakers(),
            'founding_year' => 1976 // Năm thành lập trường
        ];
        
        // Dữ liệu mẫu cho diễn giả
        $speakers = $sukienModel->getSpeakers();
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'upcoming_events' => $upcoming_events,
            'job_fair_event' => $job_fair_event,
            'stats' => $stats,
            'speakers' => $speakers
        ];

        return view('welcome_message', $data);
    }
}
