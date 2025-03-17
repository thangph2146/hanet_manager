<?php

namespace App\Modules\students\Models;

use CodeIgniter\Model;

class DangKySukienModel extends Model
{
    protected $table = 'dangky_sukien';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'user_id', 'su_kien_id', 'ngay_dang_ky', 'trang_thai', 'ghi_chu'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'ngay_dang_ky';
    protected $updatedField = '';
    
    // Lấy danh sách sự kiện đã đăng ký của một sinh viên
    public function getRegisteredEvents($studentId)
    {
        return $this->select('dangky_sukien.*, sukien.ten_su_kien, sukien.ngay_to_chuc, sukien.dia_diem, sukien.hinh_anh, loai_sukien.loai_su_kien')
                    ->join('sukien', 'sukien.id = dangky_sukien.su_kien_id', 'left')
                    ->join('loai_sukien', 'loai_sukien.id = sukien.loai_sukien_id', 'left')
                    ->where('dangky_sukien.user_id', $studentId)
                    ->orderBy('sukien.ngay_to_chuc', 'ASC')
                    ->findAll();
    }
    
    // Kiểm tra sinh viên đã đăng ký sự kiện chưa
    public function isRegistered($studentId, $eventId)
    {
        return $this->where('user_id', $studentId)
                    ->where('su_kien_id', $eventId)
                    ->countAllResults() > 0;
    }
    
    // Đăng ký sự kiện
    public function registerEvent($studentId, $eventId)
    {
        // Kiểm tra xem sinh viên đã đăng ký sự kiện này chưa
        if ($this->isRegistered($studentId, $eventId)) {
            return false;
        }
        
        // Kiểm tra xem sự kiện còn chỗ không
        $sukienModel = new SukienModel();
        $event = $sukienModel->find($eventId);
        
        if (!$event) {
            return false;
        }
        
        $currentRegistrations = $this->where('su_kien_id', $eventId)->countAllResults();
        
        if ($event['so_luong'] > 0 && $currentRegistrations >= $event['so_luong']) {
            return false;
        }
        
        // Đăng ký sự kiện
        $data = [
            'user_id' => $studentId,
            'su_kien_id' => $eventId,
            'trang_thai' => 'registered',
            'ngay_dang_ky' => date('Y-m-d H:i:s')
        ];
        
        return $this->insert($data);
    }
    
    // Hủy đăng ký sự kiện
    public function cancelRegistration($studentId, $eventId)
    {
        return $this->where('user_id', $studentId)
                    ->where('su_kien_id', $eventId)
                    ->delete();
    }
} 