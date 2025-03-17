<?php

namespace App\Modules\students\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\API\ResponseTrait;

class NotificationsController extends Controller
{
    use ResponseTrait;

    /**
     * Danh sách tất cả thông báo
     */
    public function index()
    {
        // Mô phỏng lấy dữ liệu thông báo từ database
        $notifications = $this->getMockNotifications();
        $unreadCount = count(array_filter($notifications, function($item) {
            return !$item['read'];
        }));

        $data = [
            'title' => 'Thông báo',
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ];

        return view('App\Modules\students\Views\notifications\index', $data);
    }

    /**
     * API lấy số lượng thông báo chưa đọc
     */
    public function getCount()
    {
        // Kiểm tra xem request có phải là AJAX không
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Chỉ chấp nhận AJAX request');
        }

        // Lấy số lượng thông báo chưa đọc
        $notifications = $this->getMockNotifications();
        $unreadCount = count(array_filter($notifications, function($item) {
            return !$item['read'];
        }));

        // Lấy giá trị từ session nếu đã lưu
        $sessionCount = session()->get('notification_count');
        if ($sessionCount !== null) {
            $unreadCount = $sessionCount;
        }

        return $this->respond([
            'success' => true,
            'count' => $unreadCount
        ]);
    }

    /**
     * API lấy danh sách 5 thông báo mới nhất
     */
    public function getLatest()
    {
        // Kiểm tra xem request có phải là AJAX không
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Chỉ chấp nhận AJAX request');
        }

        // Lấy 5 thông báo mới nhất
        $notifications = $this->getMockNotifications();
        $latestNotifications = array_slice($notifications, 0, 5);

        return $this->respond([
            'success' => true,
            'notifications' => $latestNotifications
        ]);
    }

    /**
     * API đánh dấu một thông báo đã đọc
     */
    public function markAsRead($id = null)
    {
        // Kiểm tra xem request có phải là AJAX không
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Chỉ chấp nhận AJAX request');
        }

        if ($id === null) {
            return $this->fail('Thiếu ID thông báo', 400);
        }

        // Trong môi trường thực tế, đây sẽ là code cập nhật database
        // Hiện tại chỉ giảm số lượng thông báo trong session để demo
        $count = session()->get('notification_count', 0);
        if ($count > 0) {
            session()->set('notification_count', $count - 1);
        }

        return $this->respond([
            'success' => true,
            'message' => 'Đã đánh dấu thông báo đã đọc',
            'remaining' => session()->get('notification_count', 0)
        ]);
    }

    /**
     * API đánh dấu tất cả thông báo đã đọc
     */
    public function markAllAsRead()
    {
        // Kiểm tra xem request có phải là AJAX không
        if (!$this->request->isAJAX()) {
            return $this->failUnauthorized('Chỉ chấp nhận AJAX request');
        }

        // Trong môi trường thực tế, đây sẽ là code cập nhật database
        // Hiện tại chỉ đặt số lượng thông báo trong session về 0 để demo
        session()->set('notification_count', 0);

        return $this->respond([
            'success' => true,
            'message' => 'Đã đánh dấu tất cả thông báo đã đọc'
        ]);
    }

    /**
     * Dữ liệu mẫu cho thông báo
     * Trong thực tế, dữ liệu này sẽ được lấy từ database
     */
    private function getMockNotifications()
    {
        return [
            [
                'id' => 1,
                'type' => 'success',
                'icon' => 'bx bx-check-circle',
                'title' => 'Đăng ký thành công',
                'content' => 'Bạn đã đăng ký thành công sự kiện Ngày hội việc làm 2024',
                'time' => '2 giờ trước',
                'read' => false,
                'link' => base_url('students/events/view/1')
            ],
            [
                'id' => 2,
                'type' => 'info',
                'icon' => 'bx bx-info-circle',
                'title' => 'Sự kiện mới',
                'content' => 'Hội thảo kỹ năng mềm đã được thêm vào hệ thống',
                'time' => '1 ngày trước',
                'read' => false,
                'link' => base_url('students/events/view/2')
            ],
            [
                'id' => 3,
                'type' => 'warning',
                'icon' => 'bx bx-error',
                'title' => 'Sắp diễn ra',
                'content' => 'Sự kiện "Tham quan doanh nghiệp" sẽ diễn ra trong 2 ngày tới',
                'time' => '2 ngày trước',
                'read' => false,
                'link' => base_url('students/events/view/3')
            ],
            [
                'id' => 4,
                'type' => 'danger',
                'icon' => 'bx bx-x-circle',
                'title' => 'Sự kiện bị hủy',
                'content' => 'Sự kiện "Workshop UI/UX" đã bị hủy do diễn giả đột xuất không thể tham dự',
                'time' => '3 ngày trước',
                'read' => true,
                'link' => base_url('students/events')
            ],
            [
                'id' => 5,
                'type' => 'primary',
                'icon' => 'bx bx-bell',
                'title' => 'Nhắc nhở',
                'content' => 'Đừng quên xác nhận tham gia sự kiện "Giao lưu doanh nghiệp"',
                'time' => '5 ngày trước',
                'read' => true,
                'link' => base_url('students/events/view/5')
            ],
            [
                'id' => 6,
                'type' => 'secondary',
                'icon' => 'bx bx-calendar',
                'title' => 'Lịch sự kiện tháng',
                'content' => 'Lịch sự kiện tháng 5/2024 đã được cập nhật',
                'time' => '1 tuần trước',
                'read' => true,
                'link' => base_url('students/events')
            ],
            [
                'id' => 7,
                'type' => 'success',
                'icon' => 'bx bx-badge-check',
                'title' => 'Chứng chỉ đã sẵn sàng',
                'content' => 'Chứng chỉ tham gia "Workshop Digital Marketing" đã sẵn sàng để tải xuống',
                'time' => '2 tuần trước',
                'read' => true,
                'link' => base_url('students/certificates')
            ]
        ];
    }
} 