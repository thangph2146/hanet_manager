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
        $this->builder->where($this->table . '.thoi_gian_bat_dau >=', $now);
        
        // Sắp xếp theo thời gian bắt đầu (để lấy sự kiện gần nhất)
        $this->builder->orderBy($this->table . '.thoi_gian_bat_dau', 'ASC');
        
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
            $this->builder->orderBy($this->table . '.thoi_gian_bat_dau', 'DESC');
            
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
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau ?? date('H:i', strtotime($event->thoi_gian_bat_dau)),
                'gio_ket_thuc' => $event->gio_ket_thuc ?? date('H:i', strtotime($event->thoi_gian_ket_thuc)),
                'thoi_gian_bat_dau' => $event->thoi_gian_bat_dau,
                'thoi_gian_ket_thuc' => $event->thoi_gian_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->thoi_gian_bat_dau)) . ' - ' . date('H:i', strtotime($event->thoi_gian_ket_thuc)),
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
        $this->builder->where($this->table . '.thoi_gian_bat_dau >=', $now);
        
        // Sắp xếp theo thời gian bắt đầu (để lấy sự kiện gần nhất)
        $this->builder->orderBy($this->table . '.thoi_gian_bat_dau', 'ASC');
        
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
            $this->builder->orderBy($this->table . '.thoi_gian_bat_dau', 'DESC');
            
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
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau ?? date('H:i', strtotime($event->thoi_gian_bat_dau)),
                'gio_ket_thuc' => $event->gio_ket_thuc ?? date('H:i', strtotime($event->thoi_gian_ket_thuc)),
                'thoi_gian_bat_dau' => $event->thoi_gian_bat_dau,
                'thoi_gian_ket_thuc' => $event->thoi_gian_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->thoi_gian_bat_dau)) . ' - ' . date('H:i', strtotime($event->thoi_gian_ket_thuc)),
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
     * Lấy tất cả sự kiện cho trang danh sách
     */
    public function getAllEvents(array $options = [])
    {
        $defaultOptions = [
            'limit' => 0, // Lấy tất cả
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'ASC',
            'join_loai_su_kien' => true,
        ];
        
        // Kết hợp options mặc định với options được truyền vào
        $options = array_merge($defaultOptions, $options);
        
        // Lấy thời gian hiện tại
        $now = date('Y-m-d H:i:s');
        
        // Lấy các sự kiện có status = 1 và thời gian bắt đầu lớn hơn hoặc bằng thời gian hiện tại
        $this->where('su_kien.status', 1)
             ->where('su_kien.thoi_gian_bat_dau >=', $now);
        $events = $this->search([], $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta ?? $event->mo_ta_su_kien ?? '',
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian_bat_dau' => $event->thoi_gian_bat_dau,
                'thoi_gian_ket_thuc' => $event->thoi_gian_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
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
            'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
            'dia_diem' => $event->dia_diem,
            'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
            'hinh_anh' => $event->su_kien_poster,
            'gio_bat_dau' => $event->gio_bat_dau,
            'gio_ket_thuc' => $event->gio_ket_thuc,
            'thoi_gian_bat_dau' => $event->thoi_gian_bat_dau,
            'thoi_gian_ket_thuc' => $event->thoi_gian_ket_thuc,
            'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
            'loai_su_kien_id' => $event->loai_su_kien_id,
            'slug' => $event->slug,
            'so_luot_xem' => $event->so_luot_xem,
            'lich_trinh' => $event->lich_trinh,
            'hinh_thuc' => $event->hinh_thuc ?? 'Offline',
            'link_online' => $event->link_online ?? '',
            'mat_khau_online' => $event->mat_khau_online ?? '',
            'so_luong_tham_gia' => $event->so_luong_tham_gia ?? 0,
            'tong_dang_ky' => $event->tong_dang_ky ?? 0,
            'tong_check_in' => $event->tong_check_in ?? 0,
            'tong_check_out' => $event->tong_check_out ?? 0,
            'tu_khoa_su_kien' => $event->tu_khoa_su_kien ?? '',
            'hashtag' => $event->hashtag ?? '',
            'bat_dau_dang_ky' => $event->bat_dau_dang_ky ?? null,
            'ket_thuc_dang_ky' => $event->ket_thuc_dang_ky ?? null,
            'han_huy_dang_ky' => $event->han_huy_dang_ky ?? null,
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
        
        $criteria = [
            'keyword' => $keyword,
            'status' => 1
        ];
        
        $options = [
            'limit' => 0,
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'DESC',
            'join_loai_su_kien' => true
        ];
        
        // Thêm điều kiện thời gian bắt đầu lớn hơn hoặc bằng thời gian hiện tại
        $this->where('su_kien.thoi_gian_bat_dau >=', $now);
        
        $events = $this->search($criteria, $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta ?? $event->mo_ta_su_kien ?? '',
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian_bat_dau' => $event->thoi_gian_bat_dau,
                'thoi_gian_ket_thuc' => $event->thoi_gian_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
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
     * Lấy sự kiện liên quan (cùng loại)
     * 
     * @param int $eventId ID sự kiện cần loại trừ
     * @param string $eventType Loại sự kiện
     * @param int $limit Số lượng kết quả
     * @return array
     */
    public function getRelatedEvents($eventId, $eventType, $limit = 3)
    {
        $event = $this->find($eventId);
        if (!$event) {
            return [];
        }
        
        // Lấy ID loại sự kiện từ tên loại (nếu cần)
        $loaiSuKienId = $event->loai_su_kien_id;
        
        // Tìm các sự kiện cùng loại
        $criteria = [
            'status' => 1,
            'loai_su_kien_id' => $loaiSuKienId
        ];
        
        $options = [
            'limit' => $limit + 1, // Lấy thêm 1 để loại trừ sự kiện hiện tại
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'DESC',
            'join_loai_su_kien' => true
        ];
        
        // Lấy thời gian hiện tại
        $now = date('Y-m-d H:i:s');
        
        // Thêm điều kiện thời gian bắt đầu lớn hơn hoặc bằng thời gian hiện tại
        $this->where('su_kien.thoi_gian_bat_dau >=', $now);
        
        $events = $this->search($criteria, $options);
        
        // Loại bỏ sự kiện hiện tại và giới hạn số lượng
        $result = [];
        $count = 0;
        
        foreach ($events as $relatedEvent) {
            if ($relatedEvent->su_kien_id != $eventId && $count < $limit) {
                $result[] = [
                    'su_kien_id' => $relatedEvent->su_kien_id,
                    'ten_su_kien' => $relatedEvent->ten_su_kien,
                    'mo_ta_su_kien' => $relatedEvent->mo_ta ?? $relatedEvent->mo_ta_su_kien ?? '',
                    'chi_tiet_su_kien' => $relatedEvent->chi_tiet_su_kien,
                    'ngay_to_chuc' => date('Y-m-d', strtotime($relatedEvent->thoi_gian_bat_dau)),
                    'dia_diem' => $relatedEvent->dia_diem,
                    'dia_chi_cu_the' => $relatedEvent->dia_chi_cu_the ?? '',
                    'hinh_anh' => $relatedEvent->su_kien_poster,
                    'gio_bat_dau' => $relatedEvent->gio_bat_dau,
                    'gio_ket_thuc' => $relatedEvent->gio_ket_thuc,
                    'thoi_gian_bat_dau' => $relatedEvent->thoi_gian_bat_dau,
                    'thoi_gian_ket_thuc' => $relatedEvent->thoi_gian_ket_thuc,
                    'thoi_gian' => date('H:i', strtotime($relatedEvent->gio_bat_dau)) . ' - ' . date('H:i', strtotime($relatedEvent->gio_ket_thuc)),
                    'loai_su_kien_id' => $relatedEvent->loai_su_kien_id,
                    'loai_su_kien' => $relatedEvent->ten_loai_su_kien ?? $eventType,
                    'slug' => $relatedEvent->slug,
                    'so_luot_xem' => $relatedEvent->so_luot_xem,
                    'hinh_thuc' => $relatedEvent->hinh_thuc ?? 'Offline',
                    'tong_dang_ky' => $relatedEvent->tong_dang_ky ?? 0,
                ];
                $count++;
            }
        }
        
        // Nếu không đủ sự kiện cùng loại, lấy thêm các sự kiện khác
        if ($count < $limit) {
            $additionalCriteria = [
                'status' => 1,
                'not_in_ids' => [$eventId]
            ];
            
            if (!empty($result)) {
                $additionalCriteria['not_in_ids'] = array_merge(
                    $additionalCriteria['not_in_ids'],
                    array_column($result, 'su_kien_id')
                );
            }
            
            $additionalOptions = [
                'limit' => $limit - $count,
                'sort' => 'thoi_gian_bat_dau',
                'order' => 'DESC',
                'join_loai_su_kien' => true
            ];
            
            $additionalEvents = $this->search($additionalCriteria, $additionalOptions);
            
            foreach ($additionalEvents as $additionalEvent) {
                $result[] = [
                    'su_kien_id' => $additionalEvent->su_kien_id,
                    'ten_su_kien' => $additionalEvent->ten_su_kien,
                    'mo_ta_su_kien' => $additionalEvent->mo_ta ?? $additionalEvent->mo_ta_su_kien ?? '',
                    'chi_tiet_su_kien' => $additionalEvent->chi_tiet_su_kien,
                    'ngay_to_chuc' => date('Y-m-d', strtotime($additionalEvent->thoi_gian_bat_dau)),
                    'dia_diem' => $additionalEvent->dia_diem,
                    'dia_chi_cu_the' => $additionalEvent->dia_chi_cu_the ?? '',
                    'hinh_anh' => $additionalEvent->su_kien_poster,
                    'gio_bat_dau' => $additionalEvent->gio_bat_dau,
                    'gio_ket_thuc' => $additionalEvent->gio_ket_thuc,
                    'thoi_gian_bat_dau' => $additionalEvent->thoi_gian_bat_dau,
                    'thoi_gian_ket_thuc' => $additionalEvent->thoi_gian_ket_thuc,
                    'thoi_gian' => date('H:i', strtotime($additionalEvent->gio_bat_dau)) . ' - ' . date('H:i', strtotime($additionalEvent->gio_ket_thuc)),
                    'loai_su_kien_id' => $additionalEvent->loai_su_kien_id,
                    'loai_su_kien' => $additionalEvent->ten_loai_su_kien ?? '',
                    'slug' => $additionalEvent->slug,
                    'so_luot_xem' => $additionalEvent->so_luot_xem,
                    'hinh_thuc' => $additionalEvent->hinh_thuc ?? 'Offline',
                    'tong_dang_ky' => $additionalEvent->tong_dang_ky ?? 0,
                ];
            }
        }
        
        return $result;
    }
    
    /**
     * Phương thức đa năng để lấy sự kiện theo ID hoặc slug
     */
    public function getEvent($identifier)
    {
        // Kiểm tra xem identifier là ID hay slug
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
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'DESC',
            'join_loai_su_kien' => true
        ];
        
        // Thêm điều kiện thời gian bắt đầu lớn hơn hoặc bằng thời gian hiện tại
        $this->where('su_kien.thoi_gian_bat_dau >=', $now);
        
        $events = $this->search($criteria, $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta ?? $event->mo_ta_su_kien ?? '',
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'dia_chi_cu_the' => $event->dia_chi_cu_the ?? '',
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian_bat_dau' => $event->thoi_gian_bat_dau,
                'thoi_gian_ket_thuc' => $event->thoi_gian_ket_thuc,
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
                'days' => -1,
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
} 