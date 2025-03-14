<?php

namespace App\Modules\sukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\sukien\Models\SukienModel;
use App\Modules\sukien\Models\LoaiSukienModel;
use App\Modules\sukien\Models\DangKySukienModel;
use App\Modules\sukien\Models\CheckinSukienModel;
use App\Modules\sukien\Models\CheckoutSukienModel;

class Sukien extends BaseController
{
    protected $sukienModel;
    protected $loaiSukienModel;
    protected $dangKySukienModel;
    protected $checkinModel;
    protected $checkoutModel;
    
    public function __construct()
    {
        $this->sukienModel = new SukienModel();
        $this->loaiSukienModel = new LoaiSukienModel();
        $this->dangKySukienModel = new DangKySukienModel();
        $this->checkinModel = new CheckinSukienModel();
        $this->checkoutModel = new CheckoutSukienModel();
    }
    
    public function index()
    {
        // Lấy 3 sự kiện sắp tới (hiển thị ở carousel)
        $featured_events = $this->sukienModel->getFeaturedEvents();
        
        // Lấy 6 sự kiện sắp diễn ra gần nhất (dành cho phần upcoming events)
        $upcoming_events = $this->sukienModel->getUpcomingEvents(6);
        
        // Tìm sự kiện Ngày hội việc làm cho phần countdown
        $job_fair_event = null;
        foreach ($upcoming_events as $event) {
            if (strpos(strtolower($event['ten_su_kien']), 'việc làm') !== false) {
                $job_fair_event = $event;
                break;
            }
        }
        
        // Sử dụng sự kiện đầu tiên nếu không tìm thấy sự kiện việc làm
        if (!$job_fair_event && !empty($upcoming_events)) {
            $job_fair_event = $upcoming_events[0];
        }
        
        // Lấy thông tin counter
        $stats = [
            'total_events' => $this->sukienModel->getTotalEvents(),
            'total_participants' => $this->sukienModel->getTotalParticipants(),
            'total_speakers' => $this->sukienModel->getTotalSpeakers(),
            'founding_year' => 1976 // Năm thành lập trường
        ];
        
        // Dữ liệu mẫu cho diễn giả
        $speakers = $this->sukienModel->getSpeakers();
        
        // Chuẩn bị dữ liệu SEO
        $seo_data = [
            'meta_title' => 'Sự Kiện Đại Học Ngân Hàng TP.HCM - Hub Events',
            'meta_description' => 'Khám phá các sự kiện, hội thảo, workshop tại Trường Đại học Ngân hàng TP.HCM. Tham gia các hoạt động học thuật, nghề nghiệp và phát triển bản thân.',
            'meta_keywords' => 'sự kiện hub, đại học ngân hàng, hội thảo, ngày hội việc làm, workshop, hoạt động sinh viên',
            'og_image' => base_url('public/assets/modules/sukien/images/hub-banner.jpg'),
            'canonical_url' => site_url('su-kien')
        ];
        
        return view('App\Modules\sukien\Views\welcome', array_merge(
            ['featured_events' => $featured_events, 
             'upcoming_events' => $upcoming_events,
             'job_fair_event' => $job_fair_event,
             'stats' => $stats,
             'speakers' => $speakers
            ], 
            $seo_data
        ));
    }

    public function list()
    {
        // Lấy thông tin tìm kiếm nếu có
        $search = $this->request->getGet('search');
        $page = (int)$this->request->getGet('page') ?? 1;
        $per_page = 9; // Số sự kiện mỗi trang
        
        // Chuẩn bị dữ liệu cho view
        $data = [];
        
        // Lấy danh sách loại sự kiện từ LoaiSukienModel thay vì SukienModel
        $data['event_types'] = $this->loaiSukienModel->getAllEventTypes();
        
        // Xử lý tìm kiếm
        if (!empty($search)) {
            // Tìm kiếm sự kiện theo từ khóa
            $events = $this->sukienModel->searchEvents($search);
            $data['search'] = $search;
            
            // Chuẩn bị dữ liệu SEO
            $data['meta_title'] = 'Tìm kiếm: ' . $search . ' - Sự Kiện HUB';
            $data['meta_description'] = 'Kết quả tìm kiếm cho "' . $search . '" - Sự kiện tại Trường Đại học Ngân hàng TP.HCM';
            $data['meta_keywords'] = $search . ', sự kiện hub, tìm kiếm sự kiện';
        } else {
            // Lấy tất cả sự kiện
            $events = $this->sukienModel->getAllEvents();
            
            // Chuẩn bị dữ liệu SEO
            $data['meta_title'] = 'Danh Sách Sự Kiện - Đại Học Ngân Hàng TP.HCM';
            $data['meta_description'] = 'Khám phá tất cả các sự kiện tại Trường Đại học Ngân hàng TP.HCM. Hội thảo, workshop, ngày hội việc làm và nhiều hoạt động khác.';
            $data['meta_keywords'] = 'sự kiện hub, danh sách sự kiện, đại học ngân hàng, hội thảo, workshop';
        }
        
        // Xử lý phân trang
        $total_events = count($events);
        $total_pages = ceil($total_events / $per_page);
        
        if ($page < 1) $page = 1;
        if ($page > $total_pages && $total_pages > 0) $page = $total_pages;
        
        // Phân trang thủ công
        $offset = ($page - 1) * $per_page;
        $data['events'] = array_slice($events, $offset, $per_page);
        
        // Thông tin phân trang
        $data['pager'] = [
            'total_pages' => $total_pages,
            'current_page' => $page,
            'has_previous' => $page > 1,
            'has_next' => $page < $total_pages,
            'previous_page' => $page - 1,
            'next_page' => $page + 1
        ];
        
        // Thiết lập canonical URL
        $current_url = current_url();
        $query_string = $this->request->getUri()->getQuery();
        $base_url = $query_string ? $current_url . '?' . $query_string : $current_url;
        
        // Loại bỏ tham số page từ URL canonical nếu trang là 1
        if ($page === 1 && strpos($query_string, 'page=') !== false) {
            $query_params = [];
            parse_str($query_string, $query_params);
            unset($query_params['page']);
            $base_url = $current_url;
            if (!empty($query_params)) {
                $base_url .= '?' . http_build_query($query_params);
            }
        }
        
        $data['canonical_url'] = $base_url;
        
        return view('App\Modules\sukien\Views\list', $data);
    }

    /**
     * Phương thức mới: Chuyển hướng từ ID sang slug
     */
    public function redirectToSlug($id)
    {
        // Lấy thông tin sự kiện từ ID
        $event = $this->sukienModel->getEventById($id);
        
        if (empty($event)) {
            return redirect()->to('/su-kien/list')->with('error', 'Không tìm thấy sự kiện');
        }
        
        // Chuyển hướng đến URL với slug
        return redirect()->to('/su-kien/detail/' . $event['slug'], 301);
    }

    public function detail($slug)
    {
        // Lấy thông tin sự kiện từ slug
        $event = $this->sukienModel->getEventBySlug($slug);
        
        if (empty($event)) {
            return redirect()->to('/su-kien/list')->with('error', 'Không tìm thấy sự kiện');
        }
        
        // Kiểm tra xem URL hiện tại có khớp với slug không, nếu không thì redirect
        $current_slug = $this->request->uri->getSegment(3);
        if ($current_slug !== $slug) {
            return redirect()->to('/su-kien/detail/' . $slug, 301);
        }
        
        // Lấy các sự kiện liên quan (cùng loại)
        $related_events = $this->sukienModel->getRelatedEvents($event['id_su_kien'], $event['loai_su_kien'], 3);
        
        // Chuẩn bị dữ liệu có cấu trúc cho SEO
        $structured_data = $this->generateEventStructuredData($event);
        
        // Chuẩn bị dữ liệu SEO
        $seo_data = [
            'meta_title' => $event['ten_su_kien'] . ' - Sự Kiện HUB',
            'meta_description' => $this->truncate($event['mo_ta_su_kien'], 160),
            'meta_keywords' => $event['keywords'] ?? ($event['ten_su_kien'] . ', ' . $event['loai_su_kien'] . ', sự kiện hub, đại học ngân hàng'),
            'og_image' => base_url($event['hinh_anh']),
            'structured_data' => $structured_data,
            'canonical_url' => site_url('su-kien/detail/' . $slug)
        ];
        
        return view('App\Modules\sukien\Views\detail', array_merge(
            ['event' => $event, 'related_events' => $related_events], 
            $seo_data
        ));
    }
    
    public function category($category_slug)
    {
        // Lấy thông tin loại sự kiện từ slug
        $category = $this->loaiSukienModel->getEventTypeBySlug($category_slug);
        
        if (empty($category)) {
            return redirect()->to('/su-kien/list')->with('error', 'Không tìm thấy danh mục');
        }
        
        $category_name = $category['loai_su_kien'];
        
        // Lấy sự kiện thuộc danh mục đã chọn
        $events = $this->sukienModel->getEventsByCategory($category_name);
        
        // Lấy danh sách loại sự kiện
        $event_types = $this->loaiSukienModel->getAllEventTypes();
        
        // Chuẩn bị dữ liệu SEO
        $data = [
            'events' => $events,
            'category' => $category_name,
            'event_types' => $event_types,
            'meta_title' => 'Sự Kiện ' . $category_name . ' - Đại Học Ngân Hàng TP.HCM',
            'meta_description' => 'Khám phá các sự kiện ' . $category_name . ' tại Trường Đại học Ngân hàng TP.HCM. Cập nhật các ' . strtolower($category_name) . ' mới nhất.',
            'meta_keywords' => 'sự kiện ' . strtolower($category_name) . ', ' . strtolower($category_name) . ' hub, đại học ngân hàng',
            'canonical_url' => site_url('su-kien/loai/' . $category_slug)
        ];
        
        return view('App\Modules\sukien\Views\list', $data);
    }
    
    public function register()
    {
        // Xử lý đăng ký sự kiện
        if ($this->request->getMethod() === 'post') {
            // Quy tắc validation
            $rules = [
                'id_su_kien' => 'required|numeric',
                'ho_ten' => 'required|min_length[3]|max_length[255]',
                'email' => 'required|valid_email',
                'so_dien_thoai' => 'required|numeric|min_length[10]|max_length[15]',
                'ma_sinh_vien' => 'permit_empty|alpha_numeric|min_length[5]|max_length[20]',
            ];
            
            // Thông báo lỗi tùy chỉnh
            $messages = [
                'id_su_kien' => [
                    'required' => 'Không tìm thấy thông tin sự kiện',
                    'numeric' => 'Thông tin sự kiện không hợp lệ'
                ],
                'ho_ten' => [
                    'required' => 'Vui lòng nhập họ tên',
                    'min_length' => 'Họ tên phải có ít nhất 3 ký tự',
                    'max_length' => 'Họ tên không được vượt quá 255 ký tự'
                ],
                'email' => [
                    'required' => 'Vui lòng nhập email',
                    'valid_email' => 'Email không hợp lệ'
                ],
                'so_dien_thoai' => [
                    'required' => 'Vui lòng nhập số điện thoại',
                    'numeric' => 'Số điện thoại chỉ được chứa số',
                    'min_length' => 'Số điện thoại phải có ít nhất 10 số',
                    'max_length' => 'Số điện thoại không được vượt quá 15 số'
                ],
                'ma_sinh_vien' => [
                    'alpha_numeric' => 'Mã sinh viên chỉ được chứa chữ cái và số',
                    'min_length' => 'Mã sinh viên phải có ít nhất 5 ký tự',
                    'max_length' => 'Mã sinh viên không được vượt quá 20 ký tự'
                ]
            ];
            
            if ($this->validate($rules, $messages)) {
                // Dữ liệu hợp lệ, xử lý đăng ký
                $data = [
                    'id_su_kien' => $this->request->getPost('id_su_kien'),
                    'ho_ten' => $this->request->getPost('ho_ten'),
                    'email' => $this->request->getPost('email'),
                    'so_dien_thoai' => $this->request->getPost('so_dien_thoai'),
                    'ma_sinh_vien' => $this->request->getPost('ma_sinh_vien'),
                    'thoi_gian_dang_ky' => date('Y-m-d H:i:s')
                ];
                
                // Mô phỏng đăng ký thành công
                // Trong thực tế sẽ lưu vào database
                $event = $this->sukienModel->getEvent($data['id_su_kien']);
                
                // Chuyển hướng với thông báo thành công
                $success_message = 'Bạn đã đăng ký thành công sự kiện: ' . $event['ten_su_kien'];
                return redirect()->to('/su-kien/detail/' . $event['slug'])->with('success', $success_message);
            } else {
                // Dữ liệu không hợp lệ, quay lại với thông báo lỗi
                $event_id = $this->request->getPost('id_su_kien');
                $event = $this->sukienModel->getEvent($event_id);
                
                if (!$event) {
                    return redirect()->to('/su-kien/list')->with('error', 'Không tìm thấy sự kiện');
                }
                
                return redirect()->to('/su-kien/detail/' . $event['slug'])->with('error', $this->validator->listErrors());
            }
        } else {
            // Không cho phép truy cập trực tiếp
            return redirect()->to('/su-kien/list');
        }
    }
    
    /**
     * Tạo dữ liệu có cấu trúc cho sự kiện theo schema.org
     */
    private function generateEventStructuredData($event)
    {
        $startDate = date('c', strtotime($event['ngay_to_chuc'])); // Định dạng ISO 8601
        $endDate = date('c', strtotime($event['ngay_to_chuc'] . ' +' . ($event['thoi_gian'] ?: 2) . ' hours'));
        
        $structured_data = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event['ten_su_kien'],
            'description' => $event['mo_ta_su_kien'],
            'startDate' => $startDate,
            'endDate' => $endDate,
            'location' => [
                '@type' => 'Place',
                'name' => $event['dia_diem'],
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => 'Hồ Chí Minh',
                    'addressCountry' => 'VN'
                ]
            ],
            'organizer' => [
                '@type' => 'Organization',
                'name' => 'Trường Đại học Ngân hàng TP.HCM',
                'url' => 'https://hub.edu.vn'
            ],
            'image' => base_url($event['hinh_anh']),
            'url' => site_url('su-kien/detail/' . $event['slug'])
        ];
        
        return json_encode($structured_data);
    }
    
    /**
     * Hàm cắt chuỗi theo độ dài và giữ nguyên từ
     */
    private function truncate($string, $length = 100, $append = "...")
    {
        $string = trim($string);
        
        if (strlen($string) > $length) {
            $string = wordwrap($string, $length);
            $string = explode("\n", $string, 2);
            $string = $string[0] . $append;
        }
        
        return $string;
    }
} 