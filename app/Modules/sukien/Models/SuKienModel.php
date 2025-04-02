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
        // Sử dụng phương thức search từ lớp cha với các tiêu chí phù hợp
        $criteria = [
            'status' => 1,
            'featured' => 1
        ];
        
        $options = [
            'limit' => 3,
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'ASC',
            'join_loai_su_kien' => true
        ];
        
        $events = $this->search($criteria, $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta,
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem
            ];
        }
        
        return $result;
    }
    
    /**
     * Lấy các sự kiện sắp diễn ra
     */
    public function getUpcomingEvents($limit = 6)
    {
        $now = date('Y-m-d H:i:s');
        
        $criteria = [
            'status' => 1,
            'upcoming' => true
        ];
        
        $options = [
            'limit' => $limit,
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'ASC',
            'join_loai_su_kien' => true
        ];
        
        $events = $this->search($criteria, $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta,
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem
            ];
        }
        
        return $result;
    }
    
    /**
     * Lấy tất cả sự kiện cho trang danh sách
     */
    public function getAllEvents()
    {
        $options = [
            'limit' => 0, // Lấy tất cả
            'sort' => 'thoi_gian_bat_dau',
            'order' => 'DESC',
            'join_loai_su_kien' => true
        ];
        
        $events = $this->search([], $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta,
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem
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
            'mo_ta_su_kien' => $event->mo_ta,
            'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
            'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
            'dia_diem' => $event->dia_diem,
            'hinh_anh' => $event->su_kien_poster,
            'gio_bat_dau' => $event->gio_bat_dau,
            'gio_ket_thuc' => $event->gio_ket_thuc,
            'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
            'loai_su_kien_id' => $event->loai_su_kien_id,
            'slug' => $event->slug,
            'so_luot_xem' => $event->so_luot_xem,
            'lich_trinh' => $event->lich_trinh
        ];
    }
    
    /**
     * Lấy sự kiện theo slug
     */
    public function getEventBySlug($slug)
    {
        $builder = $this->builder();
        $builder->where('slug', $slug);
        $builder->where('deleted_at IS NULL');
        $builder->where('status', 1);
        
        $event = $builder->get()->getRow();
        
        if (!$event) {
            return null;
        }
        
        // Chuyển đổi sang định dạng tương thích với view
        return [
            'su_kien_id' => $event->su_kien_id,
            'ten_su_kien' => $event->ten_su_kien,
            'mo_ta_su_kien' => $event->mo_ta,
            'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
            'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
            'dia_diem' => $event->dia_diem,
            'hinh_anh' => $event->su_kien_poster,
            'gio_bat_dau' => $event->gio_bat_dau,
            'gio_ket_thuc' => $event->gio_ket_thuc,
            'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
            'loai_su_kien_id' => $event->loai_su_kien_id,
            'slug' => $event->slug,
            'so_luot_xem' => $event->so_luot_xem,
            'lich_trinh' => json_decode($event->lich_trinh, true)
        ];
    }
    
    /**
     * Tìm kiếm sự kiện
     */
    public function searchEvents($keyword)
    {
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
        
        $events = $this->search($criteria, $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view hiện tại
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta,
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
                'loai_su_kien' => $event->ten_loai_su_kien ?? '',
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem
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
    public function getRegistrations($eventId)
    {
        // Tạm thời giữ nguyên để tránh ảnh hưởng đến giao diện
        // Sẽ cập nhật sau khi tích hợp với module đăng ký sự kiện
        
        $dangKySukienModel = new \App\Modules\quanlydangkysukien\Models\DangKySuKienModel();
        
        $registrations = $dangKySukienModel->where('su_kien_id', $eventId)
                                         ->where('deleted_at IS NULL')
                                         ->findAll();
        
        return $registrations;
    }
    
    /**
     * Lấy tổng số sự kiện
     * 
     * @return int
     */
    public function getTotalEvents()
    {
        return $this->where('status', 1)
                   ->where('deleted_at IS NULL')
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
        
        $events = $this->search($criteria, $options);
        
        // Loại bỏ sự kiện hiện tại và giới hạn số lượng
        $result = [];
        $count = 0;
        
        foreach ($events as $relatedEvent) {
            if ($relatedEvent->su_kien_id != $eventId && $count < $limit) {
                $result[] = [
                    'su_kien_id' => $relatedEvent->su_kien_id,
                    'ten_su_kien' => $relatedEvent->ten_su_kien,
                    'mo_ta_su_kien' => $relatedEvent->mo_ta,
                    'chi_tiet_su_kien' => $relatedEvent->chi_tiet_su_kien,
                    'ngay_to_chuc' => date('Y-m-d', strtotime($relatedEvent->thoi_gian_bat_dau)),
                    'dia_diem' => $relatedEvent->dia_diem,
                    'hinh_anh' => $relatedEvent->su_kien_poster,
                    'gio_bat_dau' => $relatedEvent->gio_bat_dau,
                    'gio_ket_thuc' => $relatedEvent->gio_ket_thuc,
                    'thoi_gian' => date('H:i', strtotime($relatedEvent->gio_bat_dau)) . ' - ' . date('H:i', strtotime($relatedEvent->gio_ket_thuc)),
                    'loai_su_kien' => $relatedEvent->ten_loai_su_kien ?? $eventType,
                    'slug' => $relatedEvent->slug,
                    'so_luot_xem' => $relatedEvent->so_luot_xem
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
                    'mo_ta_su_kien' => $additionalEvent->mo_ta,
                    'chi_tiet_su_kien' => $additionalEvent->chi_tiet_su_kien,
                    'ngay_to_chuc' => date('Y-m-d', strtotime($additionalEvent->thoi_gian_bat_dau)),
                    'dia_diem' => $additionalEvent->dia_diem,
                    'hinh_anh' => $additionalEvent->su_kien_poster,
                    'gio_bat_dau' => $additionalEvent->gio_bat_dau,
                    'gio_ket_thuc' => $additionalEvent->gio_ket_thuc,
                    'thoi_gian' => date('H:i', strtotime($additionalEvent->gio_bat_dau)) . ' - ' . date('H:i', strtotime($additionalEvent->gio_ket_thuc)),
                    'loai_su_kien' => $additionalEvent->ten_loai_su_kien ?? '',
                    'slug' => $additionalEvent->slug,
                    'so_luot_xem' => $additionalEvent->so_luot_xem
                ];
            }
        }
        
        return $result;
    }
    
    /**
     * Phương thức alias cho getEventById
     */
    public function getEvent($id)
    {
        return $this->getEventById($id);
    }
    
    /**
     * Lấy sự kiện theo loại
     */
    public function getEventsByCategory($category)
    {
        // Tìm ID loại sự kiện từ tên
        $loaiSukienModel = new \App\Modules\quanlyloaisukien\Models\LoaiSuKienModel();
        $loaiSukien = $loaiSukienModel->where('ten_loai_su_kien', $category)
                                     ->orWhere('slug', strtolower(str_replace(' ', '-', $category)))
                                     ->first();
        
        $loaiSuKienId = $loaiSukien ? $loaiSukien->loai_su_kien_id : 0;
        
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
        
        $events = $this->search($criteria, $options);
        
        // Chuyển đổi kết quả sang định dạng mảng tương thích với view
        $result = [];
        foreach ($events as $event) {
            $result[] = [
                'su_kien_id' => $event->su_kien_id,
                'ten_su_kien' => $event->ten_su_kien,
                'mo_ta_su_kien' => $event->mo_ta,
                'chi_tiet_su_kien' => $event->chi_tiet_su_kien,
                'ngay_to_chuc' => date('Y-m-d', strtotime($event->thoi_gian_bat_dau)),
                'dia_diem' => $event->dia_diem,
                'hinh_anh' => $event->su_kien_poster,
                'gio_bat_dau' => $event->gio_bat_dau,
                'gio_ket_thuc' => $event->gio_ket_thuc,
                'thoi_gian' => date('H:i', strtotime($event->gio_bat_dau)) . ' - ' . date('H:i', strtotime($event->gio_ket_thuc)),
                'loai_su_kien' => $event->ten_loai_su_kien ?? $category,
                'slug' => $event->slug,
                'so_luot_xem' => $event->so_luot_xem
            ];
        }
        
        return $result;
    }
} 