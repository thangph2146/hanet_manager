<?php

namespace App\Modules\sukien\Models;

use App\Modules\quanlysukien\Models\SuKienModel as BaseModel;

class SukienModel extends BaseModel
{
    // Kế thừa tất cả các thuộc tính và phương thức từ SuKienModel của module quanlysukien
    
    /**
     * Constructor để đảm bảo kế thừa đúng
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Phương thức đặc thù của module front-end
     * Lấy các sự kiện nổi bật để hiển thị trên trang chủ
     */
    public function getFeaturedEvents()
    {
        // Lấy thời gian hiện tại
        $now = date('Y-m-d H:i:s');
        
        // Log để debug
        log_message('debug', 'getFeaturedEvents: Current time = ' . $now);
        
        // Thiết lập builder
        $this->builder = $this->db->table($this->table);
        $this->builder->select($this->table . '.*, loai_su_kien.ten_loai_su_kien');
        $this->builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = ' . $this->table . '.loai_su_kien_id', 'left');
        
        // Chỉ lấy sự kiện có trạng thái hoạt động
        $this->builder->where($this->table . '.status', 1);
        $this->builder->where($this->table . '.deleted_at IS NULL');
        
        // Thử lấy sự kiện sắp diễn ra trước
        $this->builder->where($this->table . '.thoi_gian_bat_dau_su_kien >=', $now);
        
        // Sắp xếp theo thời gian bắt đầu (để lấy sự kiện gần nhất)
        $this->builder->orderBy($this->table . '.thoi_gian_bat_dau_su_kien', 'ASC');
        
        // Giới hạn số lượng kết quả
        $this->builder->limit(3);
        
        // Thực hiện truy vấn
        $events = $this->builder->get()->getResult($this->returnType);
        
        // Nếu không có sự kiện sắp diễn ra, lấy cả sự kiện đã diễn ra (để đảm bảo luôn có sự kiện hiển thị)
        if (empty($events)) {
            log_message('debug', 'No upcoming events found, looking for recent past events');
            
            $this->builder = $this->db->table($this->table);
            $this->builder->select($this->table . '.*, loai_su_kien.ten_loai_su_kien');
            $this->builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = ' . $this->table . '.loai_su_kien_id', 'left');
            
            $this->builder->where($this->table . '.status', 1);
            $this->builder->where($this->table . '.deleted_at IS NULL');
            
            // Sắp xếp theo thời gian bắt đầu giảm dần (lấy sự kiện gần đây nhất)
            $this->builder->orderBy($this->table . '.thoi_gian_bat_dau_su_kien', 'DESC');
            
            $this->builder->limit(3);
            
            $events = $this->builder->get()->getResult($this->returnType);
        }
        
        log_message('debug', 'getFeaturedEvents: Found ' . count($events) . ' events');
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta ?? $event->mo_ta_su_kien ?? '',
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau_su_kien)),
                'dia_diem' => $event->dia_diem,
                'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)),
                'gio_ket_thuc' => date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
                'thoi_gian_bat_dau_su_kien' => $event->thoi_gian_bat_dau_su_kien,
                'thoi_gian_ket_thuc_su_kien' => $event->thoi_gian_ket_thuc_su_kien,
                'thoi_gian' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)) . ' - ' . date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
                'loai_su_kien_id' => $event->loai_su_kien_id,
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem,
                'hinh_thuc' => $event->hinh_thuc ?? 'Offline',
                'tong_dang_ky' => $event->tong_dang_ky ?? 0,
            ];
        }
        
        return $result;
    }
    
    /**
     * Lấy các sự kiện sắp diễn ra
     */
    public function getUpcomingEvents($limit = 6)
    {
        // Lấy thời gian hiện tại
        $now = date('Y-m-d H:i:s');
        
        // Log để debug
        log_message('debug', 'getUpcomingEvents: Current time = ' . $now);
        
        // Thiết lập builder
        $this->builder = $this->db->table($this->table);
        $this->builder->select($this->table . '.*, loai_su_kien.ten_loai_su_kien');
        $this->builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = ' . $this->table . '.loai_su_kien_id', 'left');
        
        // Chỉ lấy sự kiện có trạng thái hoạt động
        $this->builder->where($this->table . '.status', 1);
        $this->builder->where($this->table . '.deleted_at IS NULL');
        
        // Thử lấy sự kiện sắp diễn ra trước
        $this->builder->where($this->table . '.thoi_gian_bat_dau_su_kien >=', $now);
        
        // Sắp xếp theo thời gian bắt đầu (để lấy sự kiện gần nhất)
        $this->builder->orderBy($this->table . '.thoi_gian_bat_dau_su_kien', 'ASC');
        
        // Giới hạn số lượng kết quả
        $this->builder->limit($limit);
        
        // Thực hiện truy vấn
        $events = $this->builder->get()->getResult($this->returnType);
        
        // Nếu không có sự kiện sắp diễn ra, lấy cả sự kiện đã diễn ra (để đảm bảo luôn có sự kiện hiển thị)
        if (empty($events)) {
            log_message('debug', 'No upcoming events found, looking for recent past events');
            
            $this->builder = $this->db->table($this->table);
            $this->builder->select($this->table . '.*, loai_su_kien.ten_loai_su_kien');
            $this->builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = ' . $this->table . '.loai_su_kien_id', 'left');
            
            $this->builder->where($this->table . '.status', 1);
            $this->builder->where($this->table . '.deleted_at IS NULL');
            
            // Sắp xếp theo thời gian bắt đầu giảm dần (lấy sự kiện gần đây nhất)
            $this->builder->orderBy($this->table . '.thoi_gian_bat_dau_su_kien', 'DESC');
            
            $this->builder->limit($limit);
            
            $events = $this->builder->get()->getResult($this->returnType);
        }
        
        log_message('debug', 'getUpcomingEvents: Found ' . count($events) . ' events');
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta ?? $event->mo_ta_su_kien ?? '',
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau_su_kien)),
                'dia_diem' => $event->dia_diem,
                'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)),
                'gio_ket_thuc' => date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
                'thoi_gian_bat_dau_su_kien' => $event->thoi_gian_bat_dau_su_kien,
                'thoi_gian_ket_thuc_su_kien' => $event->thoi_gian_ket_thuc_su_kien,
                'thoi_gian' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)) . ' - ' . date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
                'loai_su_kien_id' => $event->loai_su_kien_id,
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem,
                'hinh_thuc' => $event->hinh_thuc ?? 'Offline',
                'tong_dang_ky' => $event->tong_dang_ky ?? 0,
            ];
        }
        
        return $result;
    }
    
    /**
     * Lấy tất cả sự kiện với các tùy chọn lọc
     */
    public function getAllEvents(array $options = [])
    {
        // Thiết lập các giá trị mặc định cho các tùy chọn
        $defaultOptions = [
            'category' => null,        // Loại sự kiện
            'status' => 'all',         // Trạng thái: all, upcoming, ongoing, past
            'limit' => 0,              // Giới hạn số lượng kết quả (0 = không giới hạn)
            'offset' => 0,             // Vị trí bắt đầu
            'sort' => 'thoi_gian_bat_dau_su_kien',  // Trường sắp xếp
            'order' => 'DESC',         // Thứ tự sắp xếp
            'keyword' => '',           // Từ khóa tìm kiếm
            'fields' => '*',           // Các trường cần lấy
            'random' => false,         // Lấy ngẫu nhiên
            'year' => null,            // Năm tổ chức
            'month' => null,           // Tháng tổ chức
        ];
        
        // Hợp nhất các tùy chọn người dùng cung cấp với các giá trị mặc định
        $options = array_merge($defaultOptions, $options);
        
        // Lấy thời gian hiện tại
        $now = date('Y-m-d H:i:s');
        
        // Thiết lập builder
        $this->builder = $this->db->table($this->table);
        
        // Lựa chọn các trường
        if ($options['fields'] !== '*') {
            $this->builder->select($options['fields']);
        } else {
            $this->builder->select($this->table . '.*, loai_su_kien.ten_loai_su_kien');
        }
        
        // Join với bảng loại sự kiện
        $this->builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = ' . $this->table . '.loai_su_kien_id', 'left');
        
        // Lọc theo loại sự kiện
        if (!empty($options['category'])) {
            if (is_numeric($options['category'])) {
                $this->builder->where($this->table . '.loai_su_kien_id', $options['category']);
            } else {
                $this->builder->where('loai_su_kien.ten_loai_su_kien', $options['category']);
            }
        }
        
        // Lọc theo trạng thái
        if ($options['status'] !== 'all') {
            switch ($options['status']) {
                case 'upcoming':
                    $this->builder->where($this->table . '.thoi_gian_bat_dau_su_kien >', $now);
                    break;
                case 'ongoing':
                    $this->builder->where($this->table . '.thoi_gian_bat_dau_su_kien <=', $now);
                    $this->builder->where($this->table . '.thoi_gian_ket_thuc_su_kien >=', $now);
                    break;
                case 'past':
                    $this->builder->where($this->table . '.thoi_gian_ket_thuc_su_kien <', $now);
                    break;
            }
        }
        
        // Lọc theo năm
        if (!empty($options['year'])) {
            $this->builder->where('YEAR(' . $this->table . '.thoi_gian_bat_dau_su_kien)', $options['year']);
        }
        
        // Lọc theo tháng
        if (!empty($options['month'])) {
            $this->builder->where('MONTH(' . $this->table . '.thoi_gian_bat_dau_su_kien)', $options['month']);
        }
        
        // Tìm kiếm theo từ khóa
        if (!empty($options['keyword'])) {
            $this->builder->groupStart()
                    ->like($this->table . '.ten_su_kien', $options['keyword'])
                    ->orLike($this->table . '.mo_ta', $options['keyword'])
                    ->orLike($this->table . '.mo_ta_su_kien', $options['keyword'])
                    ->orLike($this->table . '.chi_tiet_su_kien', $options['keyword'])
                    ->orLike($this->table . '.tu_khoa_su_kien', $options['keyword'])
                    ->orLike($this->table . '.hashtag', $options['keyword'])
                    ->orLike($this->table . '.dia_diem', $options['keyword'])
                    ->orLike('loai_su_kien.ten_loai_su_kien', $options['keyword'])
                    ->groupEnd();
        }
        
        // Chỉ lấy sự kiện có trạng thái hoạt động
        $this->builder->where($this->table . '.status', 1);
        $this->builder->where($this->table . '.deleted_at IS NULL');
        
        // Sắp xếp kết quả
        if ($options['random']) {
            $this->builder->orderBy('RAND()');
        } else {
            $this->builder->orderBy($this->table . '.' . $options['sort'], $options['order']);
        }
        
        // Giới hạn kết quả
        if ($options['limit'] > 0) {
            $this->builder->limit($options['limit'], $options['offset']);
        }
        
        // Thực hiện truy vấn
        $events = $this->builder->get()->getResult($this->returnType);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta ?? $event->mo_ta_su_kien ?? '',
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau_su_kien)),
                'dia_diem' => $event->dia_diem,
                'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)),
                'gio_ket_thuc' => date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
                'thoi_gian_bat_dau_su_kien' => $event->thoi_gian_bat_dau_su_kien,
                'thoi_gian_ket_thuc_su_kien' => $event->thoi_gian_ket_thuc_su_kien,
                'thoi_gian' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)) . ' - ' . date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
                'loai_su_kien_id' => $event->loai_su_kien_id,
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem,
                'hinh_thuc' => $event->hinh_thuc ?? 'Offline',
                'tong_dang_ky' => $event->tong_dang_ky ?? 0,
            ];
        }
        
        return $result;
    }
    
    /**
     * Lấy sự kiện theo ID
     */
    public function getEventById($id)
    {
        $event = parent::find($id);
        
        if (!$event) {
            return null;
        }
        
        // Chuyển đổi sang định dạng tương thích với view
        return [
            'su_kien_id' => $event->su_kien_id,
            'ten_su_kien' => $event->ten_su_kien,
            'mo_ta_su_kien' => $event->mo_ta ?? $event->mo_ta_su_kien ?? '',
            'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
            'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau_su_kien)),
            'dia_diem' => $event->dia_diem,
            'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
            'hinh_anh' => $event->su_kien_poster,
            'gio_bat_dau' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)),
            'gio_ket_thuc' => date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
            'thoi_gian_bat_dau_su_kien' => $event->thoi_gian_bat_dau_su_kien,
            'thoi_gian_ket_thuc_su_kien' => $event->thoi_gian_ket_thuc_su_kien,
            'thoi_gian_bat_dau_dang_ky' => $event->thoi_gian_bat_dau_dang_ky,
            'thoi_gian_ket_thuc_dang_ky' => $event->thoi_gian_ket_thuc_dang_ky,
            'thoi_gian' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)) . ' - ' . date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
            'loai_su_kien_id' => $event->loai_su_kien_id,
            'loai_su_kien' => $event->ten_loai_su_kien ?? '',
            'slug' => $event->slug,
            'so_luot_xem' => $event->so_luot_xem,
            'hinh_thuc' => $event->hinh_thuc ?? 'Offline',
            'link_online' => $event->link_online ?? '',
            'tong_dang_ky' => $event->tong_dang_ky ?? 0,
            'tong_check_in' => $event->tong_check_in ?? 0,
            'thoi_gian_checkin_bat_dau' => $event->thoi_gian_checkin_bat_dau,
            'thoi_gian_checkin_ket_thuc' => $event->thoi_gian_checkin_ket_thuc,
            'thoi_gian_checkout_bat_dau' => $event->thoi_gian_checkout_bat_dau,
            'thoi_gian_checkout_ket_thuc' => $event->thoi_gian_checkout_ket_thuc,
            'created_at' => $event->created_at,
            'updated_at' => $event->updated_at
        ];
    }
    
    /**
     * Chuẩn hóa slug
     */
    protected function normalizeSlug($slug)
    {
        // Chuyển dấu cách thành dấu gạch ngang (hoặc ngược lại nếu cần)
        $slug = str_replace(' ', '-', $slug);
        
        // Loại bỏ ký tự đặc biệt
        $slug = preg_replace('/[^a-zA-Z0-9\-]/', '', $slug);
        
        // Chuyển đổi sang chữ thường
        $slug = strtolower($slug);
        
        // Loại bỏ các dấu gạch ngang ở đầu và cuối
        $slug = trim($slug, '-');
        
        // Loại bỏ các dấu gạch ngang liên tiếp
        $slug = preg_replace('/-+/', '-', $slug);
        
        return $slug;
    }
    
    /**
     * Tìm kiếm sự kiện
     */
    public function searchEvents($keyword)
    {
        // Lấy thời gian hiện tại
        $now = date('Y-m-d H:i:s');
        
        // Thiết lập builder
        $this->builder = $this->db->table($this->table);
        $this->builder->select($this->table . '.*, loai_su_kien.ten_loai_su_kien');
        $this->builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = ' . $this->table . '.loai_su_kien_id', 'left');
        
        // Chỉ lấy sự kiện có trạng thái hoạt động
        $this->builder->where($this->table . '.status', 1);
        $this->builder->where($this->table . '.deleted_at IS NULL');
        
        // Tìm kiếm trong tên, mô tả, chi tiết, từ khóa
        if ($keyword) {
            $this->builder->groupStart()
                    ->like($this->table . '.ten_su_kien', $keyword)
                    ->orLike($this->table . '.mo_ta', $keyword)
                    ->orLike($this->table . '.mo_ta_su_kien', $keyword)
                    ->orLike($this->table . '.chi_tiet_su_kien', $keyword)
                    ->orLike($this->table . '.tu_khoa_su_kien', $keyword)
                    ->orLike($this->table . '.hashtag', $keyword)
                    ->orLike($this->table . '.dia_diem', $keyword)
                    ->orLike('loai_su_kien.ten_loai_su_kien', $keyword)
                    ->groupEnd();
        }
        
        // Thực hiện truy vấn
        $events = $this->builder->get()->getResult($this->returnType);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta ?? $event->mo_ta_su_kien ?? '',
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau_su_kien)),
                'dia_diem' => $event->dia_diem,
                'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)),
                'gio_ket_thuc' => date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
                'thoi_gian_bat_dau_su_kien' => $event->thoi_gian_bat_dau_su_kien,
                'thoi_gian_ket_thuc_su_kien' => $event->thoi_gian_ket_thuc_su_kien,
                'thoi_gian' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)) . ' - ' . date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
                'loai_su_kien_id' => $event->loai_su_kien_id,
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem,
                'hinh_thuc' => $event->hinh_thuc ?? 'Offline',
                'tong_dang_ky' => $event->tong_dang_ky ?? 0,
            ];
        }
        
        return $result;
    }
    
    /**
     * Lấy thông tin diễn giả
     */
    public function getSpeakers($limit = 4)
    {
        // Kết nối với model diengia từ module diengia
        $dienGiaModel = new \App\Modules\quanlydiengia\Models\DienGiaModel();
        
        $speakers = $dienGiaModel->findAll($limit);
        
        $result = [];
        foreach ($speakers as $speaker) {
            $result[] = [
                'id' => $speaker->id ?? $speaker->dien_gia_id ?? 0,
                'name' => $speaker->ho_ten ?? $speaker->ten_dien_gia ?? '',
                'position' => $speaker->chuc_vu ?? $speaker->vi_tri ?? '',
                'image' => $speaker->hinh_anh ?? $speaker->avatar ?? 'assets/modules/sukien/images/speakers/speaker-default.jpg'
            ];
        }
        
        return $result;
    }
    
    /**
     * Lấy đăng ký sự kiện
     */
    public function getRegistrations($eventId, array $options = [])
    {
        // Tạm thời giữ nguyên để tránh ảnh hưởng đến giao diện
        // Sẽ cập nhật sau khi tích hợp với module đăng ký sự kiện
        
        $dangKySukienModel = new \App\Modules\quanlydangkysukien\Models\DangKySuKienModel();
        
        $builder = $dangKySukienModel->where('su_kien_id', $eventId)
                                   ->where('deleted_at IS NULL');
        
        // Xử lý tham số options
        $limit = $options['limit'] ?? null;
        $offset = $options['offset'] ?? 0;
        
        return $builder->findAll($limit, $offset);
    }
    
    /**
     * Lấy tổng số sự kiện
     * 
     * @return int
     */
    public function getTotalEvents()
    {
        return $this->where('su_kien.status', 1)
                   ->where('su_kien.deleted_at IS NULL')
                   ->countAllResults();
    }
    
    /**
     * Lấy tổng số người tham gia
     * 
     * @return int
     */
    public function getTotalParticipants()
    {
        $dangKySukienModel = new \App\Modules\quanlydangkysukien\Models\DangKySuKienModel();
        return $dangKySukienModel->where('deleted_at IS NULL')
                                ->countAllResults();
    }
    
    /**
     * Lấy tổng số diễn giả
     * 
     * @return int
     */
    public function getTotalSpeakers()
    {
        $dienGiaModel = new \App\Modules\quanlydiengia\Models\DienGiaModel();
        return $dienGiaModel->where('deleted_at IS NULL')
                           ->countAllResults();
    }
    
    /**
     * Tăng số lượt xem cho sự kiện
     * 
     * @param int $eventId ID của sự kiện
     * @return bool
     */
    public function incrementViews($eventId)
    {
        $event = $this->find($eventId);
        if (!$event) {
            return false;
        }
        
        $currentViews = $event->so_luot_xem ?? 0;
        return $this->update($eventId, ['so_luot_xem' => $currentViews + 1]);
    }
    
    /**
     * Lấy lịch trình của sự kiện
     * 
     * @param int $eventId ID của sự kiện
     * @return array
     */
    public function getEventSchedule($eventId)
    {
        $event = $this->find($eventId);
        if (!$event || empty($event->lich_trinh)) {
            return [];
        }
        
        if (is_string($event->lich_trinh)) {
            return json_decode($event->lich_trinh, true) ?? [];
        }
        
        return (array)$event->lich_trinh;
    }
    
    /**
     * Lấy các sự kiện liên quan
     */
    public function getRelatedEvents($eventId, $eventType, $limit = 3)
    {
        // Nếu không có eventId hoặc eventType, trả về rỗng
        if (empty($eventId) || empty($eventType)) {
            return [];
        }
        
        // Lấy thời gian hiện tại
        $now = date('Y-m-d H:i:s');
        
        // Thiết lập builder
        $this->builder = $this->db->table($this->table);
        $this->builder->select($this->table . '.*, loai_su_kien.ten_loai_su_kien');
        $this->builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = ' . $this->table . '.loai_su_kien_id', 'left');
        
        // Chỉ lấy sự kiện có trạng thái hoạt động và khác với sự kiện hiện tại
        $this->builder->where($this->table . '.status', 1);
        $this->builder->where($this->table . '.deleted_at IS NULL');
        $this->builder->where($this->table . '.su_kien_id !=', $eventId);
        
        // Lọc theo loại sự kiện
        if (is_numeric($eventType)) {
            $this->builder->where($this->table . '.loai_su_kien_id', $eventType);
        } else {
            $this->builder->where('loai_su_kien.ten_loai_su_kien', $eventType);
        }
        
        // Ưu tiên sự kiện sắp diễn ra
        $this->builder->where($this->table . '.thoi_gian_bat_dau_su_kien >=', $now);
        $this->builder->orderBy($this->table . '.thoi_gian_bat_dau_su_kien', 'ASC');
        
        // Giới hạn số lượng kết quả
        $this->builder->limit($limit);
        
        // Thực hiện truy vấn
        $events = $this->builder->get()->getResult($this->returnType);
        
        // Nếu không có đủ sự kiện sắp diễn ra, lấy thêm cả sự kiện đã diễn ra
        if (count($events) < $limit) {
            $needed = $limit - count($events);
            
            // Lấy ID của các sự kiện đã có để loại trừ
            $existingIds = array_map(function($event) {
                return $event->su_kien_id;
            }, $events);
            $existingIds[] = $eventId; // Thêm ID sự kiện hiện tại để loại trừ
            
            $this->builder = $this->db->table($this->table);
            $this->builder->select($this->table . '.*, loai_su_kien.ten_loai_su_kien');
            $this->builder->join('loai_su_kien', 'loai_su_kien.loai_su_kien_id = ' . $this->table . '.loai_su_kien_id', 'left');
            
            $this->builder->where($this->table . '.status', 1);
            $this->builder->where($this->table . '.deleted_at IS NULL');
            $this->builder->whereNotIn($this->table . '.su_kien_id', $existingIds);
            
            // Lọc theo loại sự kiện
            if (is_numeric($eventType)) {
                $this->builder->where($this->table . '.loai_su_kien_id', $eventType);
            } else {
                $this->builder->where('loai_su_kien.ten_loai_su_kien', $eventType);
            }
            
            $this->builder->where($this->table . '.thoi_gian_bat_dau_su_kien <', $now);
            $this->builder->orderBy($this->table . '.thoi_gian_bat_dau_su_kien', 'DESC');
            $this->builder->limit($needed);
            
            $pastEvents = $this->builder->get()->getResult($this->returnType);
            
            // Kết hợp với những sự kiện sắp diễn ra
            $events = array_merge($events, $pastEvents);
        }
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta ?? $event->mo_ta_su_kien ?? '',
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau_su_kien)),
                'dia_diem' => $event->dia_diem,
                'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)),
                'gio_ket_thuc' => date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
                'thoi_gian_bat_dau_su_kien' => $event->thoi_gian_bat_dau_su_kien,
                'thoi_gian_ket_thuc_su_kien' => $event->thoi_gian_ket_thuc_su_kien,
                'thoi_gian' => date('H:i', strtotime($event->thoi_gian_bat_dau_su_kien)) . ' - ' . date('H:i', strtotime($event->thoi_gian_ket_thuc_su_kien)),
                'loai_su_kien_id' => $event->loai_su_kien_id,
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem,
                'hinh_thuc' => $event->hinh_thuc ?? 'Offline',
                'tong_dang_ky' => $event->tong_dang_ky ?? 0,
            ];
        }
        
        return $result;
    }
    
    /**
     * Lấy sự kiện theo slug hoặc ID
     */
    public function getEvent($identifier)
    {
        if (is_numeric($identifier)) {
            return $this->getEventById($identifier);
        } else {
            return $this->getEventBySlug($identifier);
        }
    }
    
    /**
     * Lấy sự kiện theo loại
     */
    public function getEventsByCategory($category)
    {
        // Tìm ID loại sự kiện từ tên
        $loaiSukienModel = new \App\Modules\quanlyloaisukien\Models\LoaiSuKienModel();
        $loaiSukien = $loaiSukienModel->where('ten_loai_su_kien', $category)
                                     ->first();
        
        $loaiSuKienId = $loaiSukien ? $loaiSukien->loai_su_kien_id : 0;
        
        // Lấy thời gian hiện tại
        $now = date('Y-m-d H:i:s');
        
        $criteria = [
            'status' => 1,
            'loai_su_kien_id' => $loaiSuKienId
        ];
        
        $options = [
            'limit' => 0, // Lấy tất cả
            'sort' => 'thoi_gian_bat_dau_su_kien',
            'order' => 'DESC',
            'join_loai_su_kien' => true
        ];
        
        // Thêm điều kiện thời gian bắt đầu lớn hơn hoặc bằng thời gian hiện tại
        $this->where('su_kien.thoi_gian_bat_dau_su_kien >=', $now);
        
        $events = $this->search($criteria, $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta ?? $event->mo_ta_su_kien ?? '',
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau_su_kien)),
                'dia_diem' => $event->dia_diem,
                'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian_bat_dau_su_kien' => $event->thoi_gian_bat_dau_su_kien,
                'thoi_gian_ket_thuc_su_kien' => $event->thoi_gian_ket_thuc_su_kien,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
                'loai_su_kien_id' => $event->loai_su_kien_id,
                'loai_su_kien' => $event->ten_loai_su_kien ?? $category,
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem,
                'hinh_thuc' => $event->hinh_thuc ?? 'Offline',
                'tong_dang_ky' => $event->tong_dang_ky ?? 0,
            ];
        }
        
        return $result;
    }
    
    /**
     * Lấy thời gian còn lại đến ngày tổ chức
     */
    public function getTimeRemaining($ngayToChuc)
    {
        if (!$ngayToChuc) {
            return null;
        }
        
        if (is_string($ngayToChuc)) {
            $ngayToChuc = strtotime($ngayToChuc);
        }
        
        $now = time();
        $diff = $ngayToChuc - $now;
        
        if ($diff <= 0) {
            return 'Đã diễn ra';
        }
        
        $days = floor($diff / (60 * 60 * 24));
        $hours = floor(($diff - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = floor(($diff - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        
        if ($days > 0) {
            return $days . ' ngày ' . $hours . ' giờ';
        } else if ($hours > 0) {
            return $hours . ' giờ ' . $minutes . ' phút';
        } else {
            return $minutes . ' phút';
        }
    }
    
    /**
     * Định dạng ngày tổ chức sự kiện
     * 
     * @param string $ngayToChuc Ngày tổ chức
     * @param string $format Định dạng chuỗi ngày tháng
     * @return string Ngày tổ chức đã được định dạng
     */
    public function formatNgayToChuc($ngayToChuc, $format = 'd/m/Y H:i')
    {
        $timestamp = strtotime($ngayToChuc);
        return date($format, $timestamp);
    }

    /**
     * Kiểm tra xem hiện tại có đang trong thời gian đăng ký không
     *
     * @param int $eventId
     * @return bool
     */
    public function isInRegistrationPeriod($eventId)
    {
        $event = $this->find($eventId);
        
        if (!$event) {
            return false;
        }
        
        $now = date('Y-m-d H:i:s');
        
        if (empty($event->thoi_gian_bat_dau_dang_ky) || empty($event->thoi_gian_ket_thuc_dang_ky)) {
            return false;
        }
        
        return ($now >= $event->thoi_gian_bat_dau_dang_ky && $now <= $event->thoi_gian_ket_thuc_dang_ky);
    }

    /**
     * Lấy thời gian đếm ngược cho sự kiện
     */
    public function getCountdownTime($eventId)
    {
        $event = $this->find($eventId);
        
        if (!$event) {
            return null;
        }
        
        $now = time();
        $startTime = strtotime($event->thoi_gian_bat_dau_su_kien);
        
        if ($startTime <= $now) {
            // Sự kiện đã bắt đầu, kiểm tra xem đã kết thúc chưa
            $endTime = strtotime($event->thoi_gian_ket_thuc_su_kien);
            
            if ($endTime <= $now) {
                // Sự kiện đã kết thúc
                return [
                    'status' => 'finished',
                    'message' => 'Sự kiện đã kết thúc'
                ];
            } else {
                // Sự kiện đang diễn ra
                $remainingTime = $endTime - $now;
                return [
                    'status' => 'ongoing',
                    'message' => 'Sự kiện đang diễn ra',
                    'remaining' => $this->formatRemainingTime($remainingTime)
                ];
            }
        } else {
            // Sự kiện chưa bắt đầu
            $remainingTime = $startTime - $now;
            return [
                'status' => 'upcoming',
                'message' => 'Sự kiện sẽ diễn ra trong',
                'remaining' => $this->formatRemainingTime($remainingTime)
            ];
        }
    }
} 