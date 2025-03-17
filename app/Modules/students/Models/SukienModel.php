<?php

namespace App\Modules\students\Models;

use CodeIgniter\Model;

class SukienModel extends Model
{
    protected $table = 'sukien';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'loai_sukien_id', 'ten_su_kien', 'mo_ta_ngan', 'mo_ta', 
        'ngay_to_chuc', 'dia_diem', 'so_luong', 'diem', 
        'trang_thai', 'hinh_anh', 'ngay_tao', 'ngay_cap_nhat'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'ngay_tao';
    protected $updatedField = 'ngay_cap_nhat';
    
    // Nhận danh sách sự kiện sắp diễn ra
    public function getUpcomingEvents($limit = null)
    {
        $query = $this->select('sukien.*, loai_sukien.loai_su_kien')
                    ->join('loai_sukien', 'loai_sukien.id = sukien.loai_sukien_id', 'left')
                    ->where('ngay_to_chuc >=', date('Y-m-d H:i:s'))
                    ->orderBy('ngay_to_chuc', 'ASC');
                    
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->findAll();
    }
    
    // Nhận danh sách sự kiện đang diễn ra
    public function getOngoingEvents($limit = null)
    {
        $now = date('Y-m-d H:i:s');
        $query = $this->select('sukien.*, loai_sukien.loai_su_kien')
                    ->join('loai_sukien', 'loai_sukien.id = sukien.loai_sukien_id', 'left')
                    ->where('ngay_to_chuc <=', $now)
                    ->where('DATE_ADD(ngay_to_chuc, INTERVAL 24 HOUR) >=', $now)
                    ->orderBy('ngay_to_chuc', 'ASC');
                    
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->findAll();
    }
    
    // Nhận danh sách sự kiện đã kết thúc
    public function getCompletedEvents($limit = null)
    {
        $query = $this->select('sukien.*, loai_sukien.loai_su_kien')
                    ->join('loai_sukien', 'loai_sukien.id = sukien.loai_sukien_id', 'left')
                    ->where('DATE_ADD(ngay_to_chuc, INTERVAL 24 HOUR) <', date('Y-m-d H:i:s'))
                    ->orderBy('ngay_to_chuc', 'DESC');
                    
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->findAll();
    }
    
    // Tìm kiếm sự kiện
    public function searchEvents($keyword = null, $category = null, $status = null, $date = null)
    {
        $query = $this->select('sukien.*, loai_sukien.loai_su_kien')
                    ->join('loai_sukien', 'loai_sukien.id = sukien.loai_sukien_id', 'left');
                    
        // Tìm kiếm theo từ khóa
        if (!empty($keyword)) {
            $query->groupStart()
                ->like('ten_su_kien', $keyword)
                ->orLike('mo_ta_ngan', $keyword)
                ->orLike('dia_diem', $keyword)
                ->groupEnd();
        }
        
        // Lọc theo loại sự kiện
        if (!empty($category)) {
            $query->where('loai_sukien_id', $category);
        }
        
        // Lọc theo trạng thái
        if (!empty($status)) {
            $now = date('Y-m-d H:i:s');
            
            if ($status == 'upcoming') {
                $query->where('ngay_to_chuc >', $now);
            } elseif ($status == 'ongoing') {
                $query->where('ngay_to_chuc <=', $now)
                    ->where('DATE_ADD(ngay_to_chuc, INTERVAL 24 HOUR) >=', $now);
            } elseif ($status == 'completed') {
                $query->where('DATE_ADD(ngay_to_chuc, INTERVAL 24 HOUR) <', $now);
            }
        }
        
        // Lọc theo ngày
        if (!empty($date)) {
            $query->where('DATE(ngay_to_chuc)', $date);
        }
        
        // Sắp xếp kết quả
        $query->orderBy('ngay_to_chuc', 'ASC');
        
        return $query->findAll();
    }
    
    // Lấy chi tiết sự kiện
    public function getEventDetail($id)
    {
        return $this->select('sukien.*, loai_sukien.loai_su_kien')
                    ->join('loai_sukien', 'loai_sukien.id = sukien.loai_sukien_id', 'left')
                    ->where('sukien.id', $id)
                    ->first();
    }
    
    // Đếm số người tham gia sự kiện
    public function countParticipants($eventId)
    {
        $db = \Config\Database::connect();
        $query = $db->table('dangky_sukien')
                    ->where('su_kien_id', $eventId)
                    ->countAllResults();
                    
        return $query;
    }
} 