<?php

namespace App\Modules\sukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\sukien\Models\SukienModel;
use App\Modules\quanlyloaisukien\Models\LoaiSuKienModel;
use App\Modules\quanlydangkysukien\Models\DangKySuKienModel;
use App\Modules\quanlycheckinsukien\Models\CheckinSukienModel;
use App\Modules\quanlycheckoutsukien\Models\CheckoutSukienModel;
use App\Modules\quanlydiengia\Models\DienGiaModel;

class SuKien extends BaseController
{
    protected $sukienModel;
    protected $loaiSukienModel;
    protected $dangKySukienModel;
    protected $checkinModel;
    protected $checkoutModel;
    protected $dienGiaModel;
    
    public function __construct()
    {
        $this->sukienModel = new SukienModel();
        $this->loaiSukienModel = new LoaiSuKienModel();
        $this->dangKySukienModel = new DangKySuKienModel();
        $this->checkinModel = new CheckinSukienModel();
        $this->checkoutModel = new CheckoutSukienModel();
        $this->dienGiaModel = new DienGiaModel();
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
        
        // Sử dụng model từ module sukien để lấy được các phương thức mở rộng
        $sukienModel = new \App\Modules\sukien\Models\SukienModel();
        
        // Xử lý tìm kiếm
        if (!empty($search)) {
            // Tìm kiếm sự kiện theo từ khóa
            $events = $sukienModel->searchEvents($search);
            $data['search'] = $search;
            
            // Chuẩn bị dữ liệu SEO
            $data['meta_title'] = 'Tìm kiếm: ' . $search . ' - Sự Kiện HUB';
            $data['meta_description'] = 'Kết quả tìm kiếm cho "' . $search . '" - Sự kiện tại Trường Đại học Ngân hàng TP.HCM';
            $data['meta_keywords'] = $search . ', sự kiện hub, tìm kiếm sự kiện';
        } else {
            // Lấy tất cả sự kiện có status = 1 và thời gian bắt đầu >= thời gian hiện tại
            $events = $sukienModel->getAllEvents();
            
            // Chuẩn bị dữ liệu SEO
            $data['meta_title'] = 'Danh Sách Sự Kiện - Đại Học Ngân Hàng TP.HCM';
            $data['meta_description'] = 'Khám phá tất cả các sự kiện tại Trường Đại học Ngân hàng TP.HCM. Hội thảo, workshop, ngày hội việc làm và nhiều hoạt động khác.';
            $data['meta_keywords'] = 'sự kiện hub, danh sách sự kiện, đại học ngân hàng, hội thảo, workshop';
        }
        
        // Chuyển đổi các đối tượng sự kiện thành mảng để dễ dàng xử lý
        $eventsArray = [];
        foreach ($events as $event) {
            if (is_object($event)) {
                // Chuyển đối tượng thành mảng
                $eventArray = [
                    'su_kien_id' => $event->su_kien_id ?? null,
                    'ten_su_kien' => $event->ten_su_kien ?? '',
                    'mo_ta_su_kien' => $event->mo_ta ?? '',
                    'chi_tiet_su_kien' => $event->chi_tiet_su_kien ?? '',
                    'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau ?? 'now')),
                    'dia_diem' => $event->dia_diem ?? '',
                    'hinh_anh' => $event->su_kien_poster ?? '',
                    'gio_bat_dau' => $event->gio_bat_dau ?? '',
                    'gio_ket_thuc' => $event->gio_ket_thuc ?? '',
                    'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau ?? 'now')) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc ?? 'now')),
                    'loai_su_kien' => $event->loai_su_kien ?? '',
                    'slug' => $event->slug ?? '',
                    'so_luot_xem' => $event->so_luot_xem ?? 0,
                    'thoi_gian_bat_dau' => $event->thoi_gian_bat_dau ?? null,
                    'thoi_gian_ket_thuc' => $event->thoi_gian_ket_thuc ?? null
                ];
                $eventsArray[] = $eventArray;
            } else {
                $eventsArray[] = $event;
            }
        }
        
        // Thêm số lượng đăng ký cho mỗi sự kiện
        foreach ($eventsArray as &$event) {
            if (isset($event['su_kien_id'])) {
                $registrations = $this->sukienModel->getRegistrations($event['su_kien_id']);
                $event['registration_count'] = count($registrations);
            } else {
                $event['registration_count'] = 0;
            }
        }
        
        // Xử lý phân trang
        $total_events = count($eventsArray);
        $total_pages = ceil($total_events / $per_page);
        
        if ($page < 1) $page = 1;
        if ($page > $total_pages && $total_pages > 0) $page = $total_pages;
        
        // Phân trang thủ công
        $offset = ($page - 1) * $per_page;
        $data['events'] = array_slice($eventsArray, $offset, $per_page);
        
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
     * Chuyển hướng từ ID sang slug
     * 
     * @param int $id ID của sự kiện
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function redirectToSlug($id)
    {
        log_message('debug', 'Đang chuyển hướng từ ID ' . $id . ' sang slug');
        
        // Lấy thông tin sự kiện từ ID
        $event = $this->sukienModel->find($id);
        
        if (!$event) {
            log_message('debug', 'Không tìm thấy sự kiện với ID ' . $id);
            
            // Thử tìm kiếm bằng getEventById
            $event = $this->sukienModel->getEventById($id);
            
            if (!$event) {
                log_message('error', 'Không thể tìm thấy sự kiện với ID ' . $id . ' bằng cả hai phương thức');
                return redirect()->to('/su-kien')->with('error', 'Không tìm thấy sự kiện');
            }
        }
        
        // Lấy slug từ sự kiện
        $slug = is_object($event) ? $event->slug : $event['slug'];
        
        if (empty($slug)) {
            log_message('error', 'Sự kiện ID ' . $id . ' không có slug');
            return redirect()->to('/su-kien')->with('error', 'Thông tin sự kiện không hợp lệ');
        }
        
        log_message('debug', 'Chuyển hướng thành công từ ID ' . $id . ' sang slug ' . $slug);
        
        // Chuyển hướng sang URL có chứa slug
        return redirect()->to('/su-kien/chi-tiet/' . $slug, 301);
    }

    /**
     * Hiển thị chi tiết sự kiện
     */
    public function detail($slug)
    {
        // Log slug được truyền vào
        log_message('debug', 'Xử lý chi tiết sự kiện với slug: ' . $slug);
        
        // Chuẩn hóa slug để đảm bảo định dạng đúng
        $sukienModel = new \App\Modules\sukien\Models\SukienModel();
        
        // Lấy thông tin sự kiện từ slug
        $event = $sukienModel->getEventBySlug($slug);
        
        // Xử lý trường hợp redirect nếu có slug chính xác trong session
        if (session()->has('correct_event_slug') && session()->get('correct_event_slug') !== $slug) {
            $correctSlug = session()->get('correct_event_slug');
            log_message('debug', 'Chuyển hướng đến slug chính xác: ' . $correctSlug);
            
            // Xóa session sau khi sử dụng
            session()->remove('correct_event_slug');
            
            // Chuyển hướng đến URL đúng
            return redirect()->to(site_url('su-kien/chi-tiet/' . $correctSlug));
        }
        
        // Nếu không tìm thấy sự kiện
        if ($event === null) {
            // Lấy lý do lỗi từ session
            $errorReason = session()->get('event_error_reason');
            $searchTerm = $slug;
            
            // Thông báo lỗi dựa trên lý do
            $errorMessage = '';
            
            switch ($errorReason) {
                case 'not_found':
                    $errorMessage = 'Không tìm thấy sự kiện "' . str_replace('-', ' ', $searchTerm) . '". Đường dẫn có thể đã thay đổi hoặc không chính xác. Vui lòng kiểm tra lại đường dẫn hoặc tìm kiếm sự kiện.';
                    break;
                case 'not_found_similar_exists':
                    $errorMessage = 'Không tìm thấy sự kiện "' . str_replace('-', ' ', $searchTerm) . '". Dưới đây là một số sự kiện tương tự mà bạn có thể quan tâm.';
                    break;
                case 'deleted':
                    $errorMessage = 'Sự kiện "' . str_replace('-', ' ', $searchTerm) . '" đã bị xóa hoặc không còn khả dụng. Vui lòng xem các sự kiện khác.';
                    break;
                case 'inactive':
                    $errorMessage = 'Sự kiện "' . str_replace('-', ' ', $searchTerm) . '" hiện không hoạt động. Vui lòng xem các sự kiện khác hoặc quay lại sau.';
                    break;
                default:
                    $errorMessage = 'Không tìm thấy sự kiện. Đường dẫn có thể đã thay đổi hoặc không chính xác. Vui lòng kiểm tra lại đường dẫn hoặc tìm kiếm sự kiện.';
            }
            
            // Lưu thông báo lỗi vào flashdata
            session()->setFlashdata('error', $errorMessage);
            
            // Lưu từ khóa tìm kiếm để hiển thị
            session()->setFlashdata('search_term', str_replace('-', ' ', $searchTerm));
            
            // Kiểm tra xem có sự kiện tương tự không
            if (session()->has('similar_events_list')) {
                // Lấy danh sách sự kiện tương tự từ session
                $similarEventsList = session()->get('similar_events_list');
                
                // Chuyển đổi sang đúng định dạng để hiển thị
                $similarEvents = [];
                
                foreach ($similarEventsList as $simEvent) {
                    // Lấy thông tin đầy đủ cho sự kiện tương tự
                    $fullEvent = $sukienModel->getEventById($simEvent['su_kien_id']);
                    if ($fullEvent) {
                        $similarEvents[] = $fullEvent;
                    }
                }
                
                // Lưu danh sách sự kiện tương tự vào flash data
                if (!empty($similarEvents)) {
                    session()->setFlashdata('similar_events', $similarEvents);
                }
                
                // Xóa session sau khi sử dụng
                session()->remove('similar_events_list');
            }
            
            // Xóa lý do lỗi sau khi sử dụng
            session()->remove('event_error_reason');
            
            // Chuyển hướng về trang danh sách sự kiện với thông báo lỗi
            return redirect()->to(site_url('su-kien/list'));
        }

        // Nếu tìm thấy sự kiện, hiển thị trang chi tiết
        
        // Tăng số lượt xem cho sự kiện
        $sukienModel->incrementViews($event['su_kien_id']);
        
        // Lấy danh sách sự kiện liên quan
        $relatedEvents = $sukienModel->getRelatedEvents($event['su_kien_id'], $event['loai_su_kien'], 3);
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'event' => $event,
            'related_events' => $relatedEvents,
            'meta_title' => $event['ten_su_kien'] . ' - Sự Kiện Đại Học Ngân Hàng TP.HCM',
            'meta_description' => $this->truncate($event['mo_ta_su_kien'], 160),
            'meta_keywords' => $event['tu_khoa_su_kien'] ?? $event['ten_su_kien'] . ', ' . $event['loai_su_kien'] . ', sự kiện hub, đại học ngân hàng',
            'canonical_url' => site_url('su-kien/chi-tiet/' . $event['slug']),
            'event_types' => $this->getEventTypes(),
            'og_image' => base_url($event['hinh_anh']),
            'structured_data' => $this->generateEventStructuredData($event)
        ];
        
        // Trả về view chi tiết sự kiện
        return $this->render('detail', $data);
    }
    
    /**
     * Tìm các sự kiện tương tự với slug đã cho
     *
     * @param string $slug Slug cần tìm sự kiện tương tự
     * @param int $limit Số lượng sự kiện tối đa muốn lấy
     * @return array Mảng chứa các sự kiện tương tự
     */
    private function findSimilarEvents($slug, $limit = 5)
    {
        // Chuẩn hóa slug - loại bỏ dấu gạch ngang và chuyển thành từ khóa tìm kiếm
        $searchTerm = str_replace('-', ' ', $slug);
        
        // Log thông tin tìm kiếm
        log_message('debug', 'Tìm sự kiện tương tự với slug: ' . $slug . ', searchTerm: ' . $searchTerm);
        
        // Tạo builder query
        $builder = $this->sukienModel->builder();
        $builder->select('su_kien.*, loai_su_kien.ten_loai_su_kien');
        $builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = su_kien.loai_su_kien_id', 'left');
        
        // Chia từ khóa tìm kiếm thành các phần nhỏ (mỗi từ)
        $searchParts = explode(' ', $searchTerm);
        
        // Lọc bỏ các từ quá ngắn
        $searchParts = array_filter($searchParts, function($part) {
            return strlen($part) >= 3;  // Chỉ lấy các từ có ít nhất 3 ký tự
        });
        
        // Tìm kiếm các sự kiện có slug hoặc tên tương tự
        if (!empty($searchParts)) {
            $builder->groupStart();
            
            // Tìm kiếm theo từng phần của từ khóa
            foreach ($searchParts as $part) {
                $builder->orLike('su_kien.ten_su_kien', $part);
                $builder->orLike('su_kien.slug', $part);
                $builder->orLike('su_kien.mo_ta', $part);
                $builder->orLike('su_kien.mo_ta_su_kien', $part);
                $builder->orLike('su_kien.tu_khoa_su_kien', $part);
            }
            
            // Tìm kiếm theo toàn bộ cụm từ
            $builder->orLike('su_kien.ten_su_kien', $searchTerm);
            $builder->orLike('su_kien.slug', str_replace(' ', '-', $searchTerm));
            
            $builder->groupEnd();
            } else {
            // Nếu từ khóa quá ngắn, tìm sự kiện gần đây
            $builder->orderBy('su_kien.created_at', 'DESC');
        }
        
        // Chỉ lấy các sự kiện đang hoạt động và chưa bị xóa
        $builder->where('su_kien.status', 1);
        $builder->where('su_kien.deleted_at IS NULL');
        
        // Giới hạn số lượng kết quả trả về
        $builder->limit($limit);
        
        // Thực hiện truy vấn
        $results = $builder->get()->getResult();
        
        // Log số lượng kết quả tìm thấy
        log_message('debug', 'Tìm thấy ' . count($results) . ' sự kiện tương tự');
        
        return $results;
    }
    
    /**
     * Lấy danh sách loại sự kiện
     *
     * @return array Mảng chứa các loại sự kiện
     */
    private function getEventTypes()
    {
        return $this->loaiSukienModel->getAllEventTypes();
    }
    
    public function category($category_slug)
    {
        // Lấy thông tin loại sự kiện từ slug
        $category = $this->loaiSukienModel->getEventTypeBySlug($category_slug);
        
        if (empty($category)) {
            return redirect()->to('/su-kien')->with('error', 'Không tìm thấy danh mục');
        }
        
        $category_name = $category['loai_su_kien'];
        
        // Sử dụng model từ module sukien để lấy được các phương thức mở rộng
        $sukienModel = new \App\Modules\sukien\Models\SukienModel();
        
        // Lấy sự kiện thuộc danh mục đã chọn
        $events = $sukienModel->getEventsByCategory($category_name);
        
        // Thêm số lượng đăng ký cho mỗi sự kiện
        foreach ($events as &$event) {
            if (isset($event['su_kien_id'])) {
                $registrations = $sukienModel->getRegistrations($event['su_kien_id']);
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
                'so_dien_thoai' => 'required|regex_match[/^[0-9]{10,15}$/]',
                'ma_sinh_vien' => 'permit_empty|alpha_numeric|min_length[5]|max_length[20]',
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
                    'regex_match' => 'Số điện thoại chỉ được chứa số và có độ dài từ 10-15 số'
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
                    'su_kien_id' => $this->request->getPost('su_kien_id'),
                    'ho_ten' => $this->request->getPost('ho_ten'),
                    'email' => $this->request->getPost('email'),
                    'so_dien_thoai' => $this->request->getPost('so_dien_thoai'),
                    'ma_sinh_vien' => $this->request->getPost('ma_sinh_vien'),
                    'thoi_gian_dang_ky' => date('Y-m-d H:i:s')
                ];
                
                // Thêm nguoi_dung_id nếu có trong form hoặc từ session
                if ($this->request->getPost('nguoi_dung_id')) {
                    $data['nguoi_dung_id'] = $this->request->getPost('nguoi_dung_id');
                } elseif (service('authstudent')->isLoggedInStudent()) {
                    $data['nguoi_dung_id'] = service('authstudent')->getStudentData()->nguoi_dung_id;
                }
                
                // Kiểm tra xem đã đăng ký trước đó chưa
                $existingRegistration = $this->dangKySukienModel->where('su_kien_id', $data['su_kien_id'])
                    ->where('email', $data['email'])
                    ->first();
                    
                if ($existingRegistration) {
                    return redirect()->to('/su-kien/chi-tiet/' . $this->sukienModel->getEventById($data['su_kien_id'])['slug'])
                        ->with('warning', 'Bạn đã đăng ký sự kiện này rồi!');
                }
                
                // Lưu thông tin đăng ký
                $saved = $this->dangKySukienModel->insert($data);
                
                if ($saved) {
                    // Lấy thông tin sự kiện
                    $event = $this->sukienModel->getEventById($data['su_kien_id']);
                    
                    // Chuyển hướng với thông báo thành công
                    $success_message = 'Bạn đã đăng ký thành công sự kiện: ' . $event['ten_su_kien'];
                    return redirect()->to('/su-kien/chi-tiet/' . $event['slug'])->with('success', $success_message);
                } else {
                    return redirect()->back()->with('error', 'Có lỗi xảy ra khi đăng ký sự kiện. Vui lòng thử lại sau.');
                }
            } else {
                // Dữ liệu không hợp lệ, quay lại với thông báo lỗi
                $event_id = $this->request->getPost('su_kien_id');
                $event = $this->sukienModel->getEventById($event_id);
                
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
     * @return mixed Hiển thị view check-in
     */
    public function displayCheckin($eventId = null) 
    {
        // Nếu không có eventId từ route parameter, lấy từ query string
        if (empty($eventId)) {
            $eventId = $this->request->getGet('eventId');
        }
        
        // Vẫn không có eventId, trả về lỗi
        if (empty($eventId)) {
            return $this->response->setStatusCode(400)->setBody('Thiếu thông tin sự kiện. Vui lòng cung cấp eventId.');
        }
        
        // Lấy thông tin sự kiện
        $suKienModel = model('App\Modules\quanlysukien\Models\SuKienModel');
        $suKien = $suKienModel->find($eventId);
        
        if (!$suKien) {
            log_message('error', 'displayCheckin: Không tìm thấy sự kiện với ID: ' . $eventId);
            return $this->response->setStatusCode(404)->setBody('Không tìm thấy sự kiện với ID: ' . $eventId);
        }
        
        // Lấy các tham số từ query string hoặc sử dụng giá trị mặc định
        $title = $this->request->getGet('title') ?? '';
        $personName = $this->request->getGet('personName') ?? '';
        $avatar = $this->request->getGet('avatar') ?? 'assets/images/default-avatar.jpg';
        $date = $this->request->getGet('date') ?? date('Y-m-d');
        $placeID = $this->request->getGet('placeID') ?? '';
        $place = $this->request->getGet('place') ?? $suKien->dia_diem ?? '';
        $checkinTime = $this->request->getGet('checkinTime') ?? (time() * 1000);
        $text1 = $this->request->getGet('text1') ?? 'Chào mừng đến với sự kiện';
        $text2 = $this->request->getGet('text2') ?? $suKien->ten_su_kien ?? 'Welcome';
        $bgType = $this->request->getGet('bgType') ?? '1';
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'eventId' => $eventId,
            'title' => $title,
            'personName' => $personName,
            'avatar' => $avatar,
            'date' => $date,
            'placeID' => $placeID,
            'place' => $place,
            'checkinTime' => $checkinTime,
            'text1' => $text1,
            'text2' => $text2,
            'bgType' => $bgType,
            'suKien' => $suKien,
            'websocketUrl' => config('App')->websocketUrl ?? 'ws://localhost:8080'
        ];
        
        // Trả về view hiển thị màn hình check-in
        return view('App\Modules\sukien\Views\checkin_display', $data);
    }

    /**
     * Trang xem trước màn hình check-in không cần event ID
     */
    public function previewCheckinDisplay()
    {
        // Dữ liệu mẫu cho preview
        $data = [
            'eventId' => 'preview',
            'title' => $this->request->getGet('title') ?? 'Khách mời',
            'personName' => $this->request->getGet('personName') ?? 'Nguyễn Văn A',
            'avatar' => $this->request->getGet('avatar') ?? 'assets/images/default-avatar.jpg',
            'date' => date('Y-m-d'),
            'placeID' => 'DEMO',
            'place' => $this->request->getGet('place') ?? 'Hội trường A',
            'checkinTime' => time() * 1000,
            'text1' => $this->request->getGet('text1') ?? 'Chào mừng đến với sự kiện',
            'text2' => $this->request->getGet('text2') ?? 'Demo Màn hình Check-in',
            'bgType' => $this->request->getGet('bgType') ?? '1',
            'websocketUrl' => config('App')->websocketUrl ?? 'ws://localhost:8080'
        ];
        
        // Trả về view hiển thị màn hình check-in với dữ liệu mẫu
        return view('App\Modules\sukien\Views\checkin_display', $data);
    }

    /**
     * Xử lý webhook từ HANET qua URL https://muster.vn/su-kien/hanet-webhook
     */
    public function hanetWebhook()
    {
        // Ghi log dữ liệu nhận được từ Hanet
        $rawData = file_get_contents('php://input');
        $requestMethod = $this->request->getMethod();
        $headers = $this->request->headers();
        $queryParams = $this->request->getGet();
        
        // Ghi log chi tiết để debug
        log_message('info', 'HANET WEBHOOK RECEIVED:');
        log_message('info', 'Method: ' . $requestMethod);
        log_message('info', 'Headers: ' . json_encode($headers->toArray()));
        log_message('info', 'Query params: ' . json_encode($queryParams));
        log_message('info', 'Raw data: ' . $rawData);
        
        // Cố gắng parse dữ liệu nhận được
        try {
            $payload = json_decode($rawData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $payload = $this->request->getJSON(true);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error parsing JSON: ' . $e->getMessage());
            $payload = [];
        }
        
        log_message('info', 'Parsed payload: ' . json_encode($payload));
        
        // Nếu không có dữ liệu hợp lệ
        if (empty($payload)) {
            log_message('warning', 'No valid data received from Hanet');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ hoặc thiếu',
                'error' => 'empty_payload'
            ]);
        }
        
        // Lấy eventId từ query hoặc payload
        $eventId = $this->request->getGet('eventId') ?? $payload['eventId'] ?? $payload['event_id'] ?? null;
        
        if (empty($eventId)) {
            log_message('warning', 'Missing eventId in Hanet webhook');
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Thiếu thông tin sự kiện (eventId)',
                'error' => 'missing_event_id'
            ]);
        }
        
        // Chuẩn bị dữ liệu check-in từ payload Hanet
        $checkinData = [
            'type' => 'checkin',
            'eventId' => $eventId,
            'personId' => $payload['personId'] ?? $payload['person_id'] ?? $payload['uid'] ?? uniqid(),
            'title' => $payload['title'] ?? $payload['role'] ?? '',
            'personName' => $payload['personName'] ?? $payload['name'] ?? $payload['person_name'] ?? $payload['fullName'] ?? 'Người dùng',
            'avatar' => $payload['avatar'] ?? $payload['image'] ?? $payload['face_image'] ?? $payload['faceImage'] ?? 'assets/images/default-avatar.jpg',
            'date' => date('Y-m-d'),
            'placeID' => $payload['placeID'] ?? $payload['place_id'] ?? $payload['deviceId'] ?? '',
            'place' => $payload['place'] ?? $payload['location'] ?? $payload['deviceName'] ?? '',
            'checkinTime' => $payload['checkinTime'] ?? $payload['check_time'] ?? $payload['timestamp'] ?? (time() * 1000),
            'text1' => $payload['text1'] ?? 'Chào mừng đến với sự kiện',
            'text2' => $payload['text2'] ?? 'Welcome to the event',
            'bgType' => $payload['bgType'] ?? '1',
            'raw_data' => $payload // Lưu toàn bộ dữ liệu thô để phân tích
        ];
        
        // Thử lưu thông tin check-in vào database
        $saveResult = $this->saveHanetCheckinData($checkinData);
        if (!$saveResult) {
            log_message('error', 'Failed to save Hanet check-in data to database for event ID: ' . $eventId);
        }
        
        // Gửi dữ liệu qua WebSocket để cập nhật màn hình hiển thị
        $wsResult = $this->sendWebSocketData($checkinData);
        if (!$wsResult) {
            log_message('error', 'Failed to send data to WebSocket for event ID: ' . $eventId);
        }
        
        // Trả về response để Hanet biết webhook đã được nhận
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Dữ liệu check-in đã được xử lý',
            'data' => $checkinData,
            'saved_to_db' => $saveResult,
            'sent_to_websocket' => $wsResult
        ]);
    }

    /**
     * Lưu thông tin check-in từ Hanet vào database
     */
    private function saveHanetCheckinData($data)
    {
        try {
            $eventId = $data['eventId'];
            
            // Lấy thông tin sự kiện
            $suKienModel = model('App\Modules\quanlysukien\Models\SuKienModel');
            $suKien = $suKienModel->find($eventId);
            
            if (!$suKien) {
                log_message('error', 'Không tìm thấy sự kiện với ID: ' . $eventId);
                return false;
            }
            
            // Tìm người dùng theo thông tin khuôn mặt hoặc tên
            $personName = $data['personName'];
            $email = '';
            
            // Kiểm tra đăng ký sự kiện
            $dangKySuKienModel = model('App\Modules\dangkysukien\Models\DangKySuKienModel');
            if (!$dangKySuKienModel) {
                log_message('error', 'Không thể tải DangKySuKienModel');
                $dangKySuKienModel = model('App\Modules\quanlydangkysukien\Models\DangKySuKienModel');
            }
            
            // Đảm bảo các trường thời gian luôn tồn tại
            if (empty($suKien['thoi_gian_bat_dau']) && !empty($suKien['ngay_to_chuc']) && !empty($suKien['gio_bat_dau'])) {
                $suKien['thoi_gian_bat_dau'] = $suKien['ngay_to_chuc'] . ' ' . $suKien['gio_bat_dau'];
            }
            
            if (empty($suKien['thoi_gian_ket_thuc']) && !empty($suKien['ngay_to_chuc']) && !empty($suKien['gio_ket_thuc'])) {
                $suKien['thoi_gian_ket_thuc'] = $suKien['ngay_to_chuc'] . ' ' . $suKien['gio_ket_thuc'];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error saving Hanet check-in data: ' . $e->getMessage());
                return false;
                }
                
                return true;
    }

    /**
     * Gửi dữ liệu qua WebSocket để cập nhật màn hình hiển thị
     */
    private function sendWebSocketData($data)
    {
        // Implementation of sending data to WebSocket
        // This is a placeholder and should be replaced with the actual implementation
        return true; // Placeholder return, actual implementation needed
    }

    /**
     * Helper method to render a view with data
     * 
     * @param string $view The view file to render
     * @param array $data Data to pass to the view
     * @return string The rendered view
     */
    protected function render($view, $data = [])
    {
        return view('App\Modules\sukien\Views\\' . $view, $data);
    }

    /**
     * Xử lý đăng ký ngay sự kiện cho người dùng đã đăng nhập
     *
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function registerNow()
    {
        $method = $this->request->getMethod();
        log_message('debug', 'Registration method: ' . $method);
        log_message('debug', 'POST data: ' . json_encode($this->request->getPost()));
        log_message('debug', 'URI: ' . $this->request->getUri());

        if ($method !== 'POST') {
            log_message('error', 'Method not allowed: ' . $method);
            return redirect()->back()->with('error', 'Method not allowed');
        }

        $isLoggedIn = service('authstudent')->isLoggedInStudent();
        $userData = $isLoggedIn ? service('authstudent')->getUserData() : null;
        $userId = $userData ? $userData->nguoi_dung_id ?? null : null;

        log_message('debug', 'User info: ' . json_encode([
            'isLoggedIn' => $isLoggedIn,
            'userId' => $userId,
            'userData' => $userData ? get_object_vars($userData) : null
        ]));

        $sukienId = $this->request->getPost('su_kien_id');
        if (!$sukienId) {
            log_message('error', 'Event ID not provided');
            return redirect()->back()->with('error', 'Event ID not provided');
        }

        $sukienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
        $sukien = $sukienModel->find($sukienId);
        
        if (!$sukien) {
            log_message('error', 'Event not found: ' . $sukienId);
            return redirect()->back()->with('error', 'Event not found');
        }

        // Kiểm tra bảng dangky_sukien có column nguoi_dung_id không
        $dangKySuKienModel = new \App\Modules\quanlydangkysukien\Models\DangKySuKienModel();
        $db = \Config\Database::connect();
        
        $checkColumnQuery = "SHOW COLUMNS FROM " . $dangKySuKienModel->table . " LIKE 'nguoi_dung_id'";
        $columnExists = $db->query($checkColumnQuery)->getNumRows() > 0;
        log_message('debug', 'Column nguoi_dung_id exists: ' . ($columnExists ? 'Yes' : 'No'));

        // Kiểm tra đã đăng ký chưa (dựa trên email)
        $email = $this->request->getPost('email');
        $checkRegistration = $dangKySuKienModel->where('su_kien_id', $sukienId)
                                               ->where('email', $email)
                                               ->first();
        
        // Ghi log SQL truy vấn kiểm tra đăng ký
        log_message('debug', 'Check registration SQL: ' . $dangKySuKienModel->getLastQuery());
        
        if ($checkRegistration) {
            log_message('notice', 'User already registered: ' . $email . ' for event: ' . $sukienId);
            return redirect()->back()->with('warning', 'Bạn đã đăng ký tham gia sự kiện này rồi!');
        }

        // Chuẩn bị dữ liệu đăng ký
        $registrationData = [
            'su_kien_id' => $sukienId,
            'ho_ten' => $this->request->getPost('ho_ten'),
            'email' => $email,
        ];

        // Kiểm tra và sử dụng trường điện thoại phù hợp
        if ($this->request->getPost('so_dien_thoai')) {
            $registrationData['so_dien_thoai'] = $this->request->getPost('so_dien_thoai');
        } elseif ($this->request->getPost('dien_thoai')) {
            $registrationData['dien_thoai'] = $this->request->getPost('dien_thoai');
        }

        // Thêm trường nguoi_dung_id nếu người dùng đã đăng nhập
        if ($userId && $columnExists) {
            $registrationData['nguoi_dung_id'] = $userId;
        }

        // Thêm trường mã sinh viên nếu có
        if ($userData && isset($userData->ma_sinh_vien)) {
            $registrationData['ma_sinh_vien'] = $userData->ma_sinh_vien;
        } else if ($userData && isset($userData->AccountId)) {
            $registrationData['ma_sinh_vien'] = $userData->AccountId;
        }

        // Ghi log dữ liệu đăng ký
        log_message('debug', 'Registration data: ' . json_encode($registrationData));

        // Thêm dữ liệu vào bảng đăng ký sự kiện
        try {
            if ($dangKySuKienModel->insert($registrationData)) {
                log_message('info', 'Registration successful: ' . $email . ' for event: ' . $sukienId);
                return redirect()->to('/su-kien/detail/' . $sukienId)->with('success', 'Đăng ký tham gia sự kiện thành công!');
            } else {
                $validationErrors = $dangKySuKienModel->errors();
                log_message('error', 'Registration failed: ' . json_encode($validationErrors) . ' - SQL: ' . $dangKySuKienModel->getLastQuery());
                return redirect()->back()->withInput()->with('errors', $validationErrors);
            }
        } catch (\Exception $e) {
            log_message('error', 'Registration exception: ' . $e->getMessage() . ' - SQL: ' . $dangKySuKienModel->getLastQuery());
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    /**
     * Tạo mã xác nhận ngẫu nhiên
     * 
     * @param int $length Độ dài mã xác nhận
     * @return string Mã xác nhận
     */
    private function generateConfirmationCode($length = 8) 
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $code;
    }

    /**
     * Debug đăng ký sự kiện - có thể gọi trực tiếp qua URL
     */
    public function debugRegisterNow($sukienId = null)
    {
        // Hiển thị thông tin debug
        echo '<h2>Debug Đăng Ký Sự Kiện</h2>';
        
        echo '<h3>Thông tin Request:</h3>';
        echo '<pre>';
        echo 'Method (getMethod): ' . $this->request->getMethod() . "\n";
        echo 'Method (SERVER): ' . ($_SERVER['REQUEST_METHOD'] ?? 'N/A') . "\n";
        echo 'URI: ' . current_url() . "\n";
        echo '</pre>';
        
        echo '<h3>Thông tin POST:</h3>';
        echo '<pre>';
        print_r($this->request->getPost());
        echo '</pre>';
        
        echo '<h3>Thông tin GET:</h3>';
        echo '<pre>';
        print_r($this->request->getGet());
        echo '</pre>';
        
        // Kiểm tra người dùng
        echo '<h3>Thông tin người dùng:</h3>';
        echo '<pre>';
        $isLoggedIn = service('authstudent')->isLoggedInStudent();
        echo 'Đã đăng nhập: ' . ($isLoggedIn ? 'Có' : 'Không') . "\n";
        
        if ($isLoggedIn) {
            $userData = service('authstudent')->getUserData();
            if ($userData) {
                echo "ID: " . ($userData->nguoi_dung_id ?? 'N/A') . "\n";
                echo "Tên: " . ($userData->FullName ?? 'N/A') . "\n";
                echo "Email: " . ($userData->Email ?? 'N/A') . "\n";
            } else {
                echo "Không thể lấy thông tin người dùng\n";
            }
        }
        echo '</pre>';
        
        // Kiểm tra sự kiện
        if ($sukienId) {
            echo '<h3>Thông tin sự kiện (ID: ' . $sukienId . '):</h3>';
            echo '<pre>';
            $event = $this->sukienModel->find($sukienId);
            if (!$event) {
                echo "Không tìm thấy sự kiện bằng find()\n";
                $event = $this->sukienModel->getEventById($sukienId);
            }
            
            if ($event) {
                if (is_object($event)) {
                    echo "ID: " . ($event->su_kien_id ?? 'N/A') . "\n";
                    echo "Tên: " . ($event->ten_su_kien ?? 'N/A') . "\n";
                    echo "Slug: " . ($event->slug ?? 'N/A') . "\n";
                } else {
                    echo "ID: " . ($event['su_kien_id'] ?? 'N/A') . "\n";
                    echo "Tên: " . ($event['ten_su_kien'] ?? 'N/A') . "\n";
                    echo "Slug: " . ($event['slug'] ?? 'N/A') . "\n";
                }
            } else {
                echo "Không tìm thấy sự kiện\n";
            }
            echo '</pre>';
        }
        
        // Form test
        echo '<h3>Form Test:</h3>';
        echo '<form method="post" action="' . base_url('/su-kien/register-now') . '">';
        echo csrf_field();
        echo '<input type="hidden" name="su_kien_id" value="' . ($sukienId ?? '') . '">';
        echo '<button type="submit">Test Đăng Ký</button>';
        echo '</form>';
        
        exit;
    }
}