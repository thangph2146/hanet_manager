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
        
        // Thêm số lượng đăng ký cho mỗi sự kiện
        foreach ($events as &$event) {
            if (isset($event['su_kien_id'])) {
                $registrations = $this->sukienModel->getRegistrations($event['su_kien_id']);
                $event['registration_count'] = count($registrations);
            } else {
                $event['registration_count'] = 0;
            }
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
            return redirect()->to('/su-kien')->with('error', 'Không tìm thấy sự kiện');
        }
        
        // Chuyển hướng đến URL với slug
        return redirect()->to('/su-kien/chi-tiet/' . $event['slug'], 301);
    }

    public function detail($slug)
    {
        // Lấy thông tin sự kiện từ slug
        $event = $this->sukienModel->getEventBySlug($slug);
        
        if (empty($event)) {
            return redirect()->to('/su-kien')->with('error', 'Không tìm thấy sự kiện');
        }
        
        // Kiểm tra xem URL hiện tại có khớp với slug không, nếu không thì redirect
        $current_slug = $this->request->getUri()->getSegment(3);
        if ($current_slug !== $slug) {
            return redirect()->to('/su-kien/chi-tiet/' . $slug, 301);
        }
        
        // Tăng số lượt xem cho sự kiện
        $this->sukienModel->incrementViews($event['su_kien_id']);
        
        // Lấy danh sách người đăng ký sự kiện
        $registrations = $this->sukienModel->getRegistrations($event['su_kien_id']);
        
        // Lấy số lượng người đăng ký   
        $registrationCount = count($registrations);
        
        // Lấy số lượng người đã tham gia
        $attendedCount = 0;
        foreach ($registrations as $reg) {
            if ($reg['da_tham_gia'] == 1) {
                $attendedCount++;
            }
        }
        
        // Lấy các sự kiện liên quan (cùng loại)
        $related_events = $this->sukienModel->getRelatedEvents($event['su_kien_id'], $event['loai_su_kien'], 3);
        
        // Thêm số lượng đăng ký cho sự kiện liên quan
        foreach ($related_events as &$related) {
            if (isset($related['su_kien_id'])) {
                $relatedRegistrations = $this->sukienModel->getRegistrations($related['su_kien_id']);
                $related['registration_count'] = count($relatedRegistrations);
            } else {
                $related['registration_count'] = 0;
            }
        }
        
        // Lấy lịch trình sự kiện
        $event_schedule = $this->sukienModel->getEventSchedule($event['su_kien_id']);
        
        // Chuẩn bị dữ liệu có cấu trúc cho SEO
        $structured_data = $this->generateEventStructuredData($event);
        
        // Chuẩn bị dữ liệu SEO
        $seo_data = [
            'meta_title' => $event['ten_su_kien'] . ' - Sự Kiện HUB',
            'meta_description' => $this->truncate($event['mo_ta_su_kien'], 160),
            'meta_keywords' => $event['keywords'] ?? ($event['ten_su_kien'] . ', ' . $event['loai_su_kien'] . ', sự kiện hub, đại học ngân hàng'),
            'og_image' => base_url($event['hinh_anh']),
            'structured_data' => $structured_data,
            'canonical_url' => site_url('su-kien/chi-tiet/' . $slug)
        ];
        
        return view('App\Modules\sukien\Views\detail', array_merge(
            [
                'event' => $event, 
                'related_events' => $related_events,
                'registrations' => $registrations,
                'registrationCount' => $registrationCount,
                'attendedCount' => $attendedCount,
                'participants' => $registrations,
                'event_schedule' => $event_schedule
            ], 
            $seo_data
        ));
    }
    
    public function category($category_slug)
    {
        // Lấy thông tin loại sự kiện từ slug
        $category = $this->loaiSukienModel->getEventTypeBySlug($category_slug);
        
        if (empty($category)) {
            return redirect()->to('/su-kien')->with('error', 'Không tìm thấy danh mục');
        }
        
        $category_name = $category['loai_su_kien'];
        
        // Lấy sự kiện thuộc danh mục đã chọn
        $events = $this->sukienModel->getEventsByCategory($category_name);
        
        // Thêm số lượng đăng ký cho mỗi sự kiện
        foreach ($events as &$event) {
            if (isset($event['su_kien_id'])) {
                $registrations = $this->sukienModel->getRegistrations($event['su_kien_id']);
                $event['registration_count'] = count($registrations);
            } else {
                $event['registration_count'] = 0;
            }
        }
        
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
                'su_kien_id' => 'required|numeric',
                'ho_ten' => 'required|min_length[3]|max_length[255]',
                'email' => 'required|valid_email',
                'so_dien_thoai' => 'required|numeric|min_length[10]|max_length[15]',
                'nguoi_dung_id' => 'permit_empty|alpha_numeric|min_length[5]|max_length[20]',
            ];
            
            // Thông báo lỗi tùy chỉnh
            $messages = [
                'su_kien_id' => [
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
                'nguoi_dung_id' => [
                    'alpha_numeric' => 'Mã sinh viên chỉ được chứa chữ cái và số',
                    'min_length' => 'Mã sinh viên phải có ít nhất 5 ký tự',
                    'max_length' => 'Mã sinh viên không được vượt quá 20 ký tự'
                ]
            ];
            
            if ($this->validate($rules, $messages)) {
                // Dữ liệu hợp lệ, xử lý đăng ký
                $data = [
                    'su_kien_id' => $this->request->getPost('su_kien_id'),
                    'ho_ten' => $this->request->getPost('ho_ten'),
                    'email' => $this->request->getPost('email'),
                    'so_dien_thoai' => $this->request->getPost('so_dien_thoai'),
                    'nguoi_dung_id' => $this->request->getPost('nguoi_dung_id'),
                    'thoi_gian_dang_ky' => date('Y-m-d H:i:s')
                ];
                
                // Mô phỏng đăng ký thành công
                // Trong thực tế sẽ lưu vào database
                $event = $this->sukienModel->getEvent($data['su_kien_id']);
                
                // Chuyển hướng với thông báo thành công
                $success_message = 'Bạn đã đăng ký thành công sự kiện: ' . $event['ten_su_kien'];
                return redirect()->to('/su-kien/chi-tiet/' . $event['slug'])->with('success', $success_message);
            } else {
                // Dữ liệu không hợp lệ, quay lại với thông báo lỗi
                $event_id = $this->request->getPost('su_kien_id');
                $event = $this->sukienModel->getEvent($event_id);
                
                if (!$event) {
                    return redirect()->to('/su-kien')->with('error', 'Không tìm thấy sự kiện');
                }
                
                return redirect()->to('/su-kien/chi-tiet/' . $event['slug'])->with('error', $this->validator->listErrors());
            }
        } else {
            // Không cho phép truy cập trực tiếp
            return redirect()->to('/su-kien');
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
            'url' => site_url('su-kien/chi-tiet/' . $event['slug'])
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

    public function checkout()
    {
        // Xử lý logic check-out
        if ($this->request->isAJAX()) {
            $data = $this->request->getPost();
            
            // Thực hiện check-out
            $success = $this->checkoutModel->save($data);
            
            // Trả về kết quả dưới dạng JSON
            return $this->response->setJSON([
                'success' => $success,
                'message' => $success ? 'Check-out thành công' : 'Có lỗi xảy ra khi check-out'
            ]);
        }
        
        return $this->response->setStatusCode(404);
    }
    
    /**
     * Hiển thị màn hình check-in với thông tin người tham gia
     * 
     * @param string $token Token xác thực người dùng (nếu có)
     * @return mixed Hiển thị view check-in
     */
    public function displayCheckin($token = null)
    {
        // Nếu không có token, có thể chuyển hướng hoặc trả về lỗi
        if (empty($token)) {
            // Đọc thông tin từ query string nếu không có token
            $title = $this->request->getGet('title') ?? 'PGS.TS';
            $personName = $this->request->getGet('personName') ?? 'Nguyen Van A';
            $avatar = $this->request->getGet('avatar') ?? 'default-avatar.jpg';
            $date = $this->request->getGet('date') ?? date('Y-m-d');
            $placeID = $this->request->getGet('placeID') ?? 'Hoi truong A';
            $place = $this->request->getGet('place') ?? 'HUB - 56 Hoàng Diệu 2';
            $checkinTime = $this->request->getGet('checkinTime') ?? time() * 1000;
            $text1 = $this->request->getGet('text1') ?? 'Chao mung den voi su kien';
            $text2 = $this->request->getGet('text2') ?? 'Welcome';
            $bgType = $this->request->getGet('bgType') ?? '1'; // Loại background (1-4)
        } else {
            // Trong thực tế, bạn sẽ truy vấn thông tin người dùng từ token
            // Ví dụ: $userData = $this->userModel->getUserByToken($token);
            
            // Ở đây chúng ta sử dụng dữ liệu mẫu
            $title = 'PGS.TS';
            $personName = 'Nguyen Van A';
            $avatar = 'default-avatar.jpg';
            $date = date('Y-m-d');
            $placeID = 'Hoi truong A';
            $place = 'HUB - 56 Hoàng Diệu 2';
            $checkinTime = time() * 1000; // JavaScript sử dụng timestamp tính bằng mili giây
            $text1 = 'Chao mung den voi su kien';
            $text2 = 'Welcome';
            $bgType = '1'; // Mặc định sử dụng background loại 1
        }
        
        // Chuẩn bị dữ liệu để truyền vào view
        $data = [
            'title' => $title,
            'personName' => $personName,
            'avatar' => $avatar,
            'date' => $date,
            'placeID' => $placeID,
            'place' => $place,
            'checkinTime' => $checkinTime,
            'text1' => $text1,
            'text2' => $text2,
            'bgType' => $bgType
        ];
        
        // Trả về view hiển thị màn hình check-in
        return view('App\Modules\sukien\Views\checkin_display', $data);
    }
    
    /**
     * Hiển thị màn hình check-in thông qua API - Phương thức GET
     * 
     * @return \CodeIgniter\HTTP\Response
     */
    public function getCheckinDisplay()
    {
        // Đây là endpoint API để lấy thông tin hiển thị check-in
        $title = $this->request->getGet('title') ?? 'PGS.TS';
        $personName = $this->request->getGet('personName') ?? 'Nguyen Van A';
        $avatar = $this->request->getGet('avatar') ?? 'default-avatar.jpg';
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $placeID = $this->request->getGet('placeID') ?? 'Hoi truong A';
        $place = $this->request->getGet('place') ?? 'HUB - 56 Hoàng Diệu 2';
        $checkinTime = $this->request->getGet('checkinTime') ?? time() * 1000;
        $text1 = $this->request->getGet('text1') ?? 'Chao mung den voi su kien';
        $text2 = $this->request->getGet('text2') ?? 'Welcome';
        $bgType = $this->request->getGet('bgType') ?? '1'; // Loại background (1-4)
        
        // Tạo dữ liệu phản hồi
        $response = [
            'success' => true,
            'data' => [
                'title' => $title,
                'personName' => $personName,
                'avatar' => $avatar,
                'date' => $date,
                'placeID' => $placeID,
                'place' => $place,
                'checkinTime' => $checkinTime,
                'text1' => $text1,
                'text2' => $text2,
                'bgType' => $bgType
            ]
        ];
        
        return $this->response->setJSON($response);
    }

    /**
     * Xử lý webhook từ HANET qua URL https://checkin.hub.edu.vn/hook
     */
    public function processHanetWebhook()
    {
        // Ghi log để kiểm tra dữ liệu webhook
        log_message('info', 'Nhận webhook từ HANET: ' . json_encode($this->request->getJSON(true)));
        
        // Nhận dữ liệu từ HANET
        $payload = $this->request->getJSON(true);
        
        if (empty($payload)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ'
            ]);
        }
        
        // Chuẩn bị dữ liệu để gửi qua WebSocket
        $checkinData = [
            'type' => 'checkin',
            'title' => $payload['title'] ?? 'PGS.TS',
            'personName' => $payload['personName'] ?? $payload['name'] ?? 'Nguyen Van A',
            'avatar' => $payload['avatar'] ?? $payload['image'] ?? 'default-avatar.jpg',
            'date' => date('Y-m-d'),
            'placeID' => $payload['placeID'] ?? $payload['locationId'] ?? 'Hoi truong A',
            'place' => $payload['place'] ?? $payload['location'] ?? 'HUB - 56 Hoàng Diệu 2',
            'checkinTime' => $payload['checkinTime'] ?? (time() * 1000),
            'text1' => $payload['welcomeText'] ?? 'Chao mung den voi su kien',
            'text2' => $payload['welcomeTextEn'] ?? 'Welcome',
            'eventId' => $payload['eventId'] ?? $payload['event_id'] ?? '0'
        ];
        
        // Lưu thông tin check-in vào database nếu cần
        $this->saveCheckinData($checkinData);
        
        // Gửi dữ liệu qua WebSocket server
        $this->pushToWebSocketServer($checkinData);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Dữ liệu check-in đã được xử lý'
        ]);
    }

    /**
     * Lưu thông tin check-in vào database
     */
    private function saveCheckinData($data)
    {
        try {
            // Chuẩn bị dữ liệu để lưu vào database
            $dbData = [
                'person_id' => $data['personId'] ?? uniqid(),
                'event_id' => $data['eventId'],
                'checkin_time' => $data['checkinTime'],
                'title' => $data['title'],
                'full_name' => $data['personName'],
                'place_id' => $data['placeID'],
                'place' => $data['place'],
                'img_path' => $data['avatar'],
                'status' => 1,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            // Lưu vào database sử dụng model
            $this->checkinModel->insert($dbData);
            log_message('info', 'Đã lưu dữ liệu check-in: ' . $data['personName']);
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi lưu dữ liệu check-in: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Gửi dữ liệu qua WebSocket server
     */
    private function pushToWebSocketServer($data)
    {
        try {
            // Kết nối đến WebSocket server
            $client = stream_socket_client('tcp://127.0.0.1:8080', $errno, $errstr, 30);
            
            if (!$client) {
                log_message('error', "Không thể kết nối đến WebSocket server: $errstr ($errno)");
                return false;
            }
            
            // Gửi dữ liệu JSON
            $jsonData = json_encode($data);
            fwrite($client, $jsonData);
            fclose($client);
            
            log_message('info', 'Đã gửi dữ liệu đến WebSocket server thành công');
            return true;
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi gửi dữ liệu qua WebSocket: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Proxy webhook từ HANET (https://checkin.hub.edu.vn/hook)
     */
    public function webhookProxy()
    {
        // Nhận dữ liệu từ webhook
        $payload = $this->request->getJSON(true);
        
        if (empty($payload)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ'
            ]);
        }
        
        // Gửi request đến webhook chính
        $client = \Config\Services::curlrequest();
        
        try {
            $response = $client->post('https://checkin.hub.edu.vn/hook', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ],
                'json' => $payload
            ]);
            
            // Lấy kết quả từ webhook chính
            $result = json_decode($response->getBody(), true);
            
            // Xử lý dữ liệu check-in và gửi qua WebSocket
            $this->processHanetData($payload);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Dữ liệu đã được chuyển tiếp và xử lý',
                'webhook_response' => $result
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi gửi dữ liệu đến webhook: ' . $e->getMessage());
            
            // Vẫn xử lý dữ liệu check-in ngay cả khi webhook chính gặp lỗi
            $this->processHanetData($payload);
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Có lỗi khi gửi đến webhook chính, nhưng dữ liệu đã được xử lý cục bộ'
            ]);
        }
    }

    /**
     * Xử lý dữ liệu từ HANET và gửi qua WebSocket
     */
    private function processHanetData($payload)
    {
        // Chuẩn bị dữ liệu để gửi qua WebSocket
        $checkinData = [
            'type' => 'checkin',
            'title' => $payload['title'] ?? 'PGS.TS',
            'personName' => $payload['personName'] ?? $payload['name'] ?? 'Nguyen Van A',
            'avatar' => $payload['avatar'] ?? $payload['image'] ?? 'default-avatar.jpg',
            'date' => date('Y-m-d'),
            'placeID' => $payload['placeID'] ?? $payload['locationId'] ?? 'Hoi truong A',
            'place' => $payload['place'] ?? $payload['location'] ?? 'HUB - 56 Hoàng Diệu 2',
            'checkinTime' => $payload['checkinTime'] ?? (time() * 1000),
            'text1' => $payload['welcomeText'] ?? 'Chao mung den voi su kien',
            'text2' => $payload['welcomeTextEn'] ?? 'Welcome',
            'eventId' => $payload['eventId'] ?? $payload['event_id'] ?? '0'
        ];
        
        // Lưu thông tin check-in vào database
        $this->saveCheckinData($checkinData);
        
        // Gửi dữ liệu qua WebSocket server
        $this->pushToWebSocketServer($checkinData);
    }
} 