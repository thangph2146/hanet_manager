<?php

namespace App\Modules\sukien\Models;

use App\Modules\quanlydangkysukien\Models\DangKySuKienModel as BaseModel;

class DangKySukienModel extends BaseModel
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Kiểm tra người dùng đã đăng ký sự kiện hay chưa
     * 
     * @param int $suKienId ID sự kiện
     * @param mixed $userId ID người dùng hoặc email
     * @return bool
     */
    public function isRegistered($suKienId, $userId)
    {
        $email = $userId;
        
        // Nếu $userId không phải email, lấy email từ bảng nguoi_dung
        if (!filter_var($userId, FILTER_VALIDATE_EMAIL)) {
            $db = \Config\Database::connect();
            $builder = $db->table('nguoi_dung');
            $user = $builder->select('email')
                            ->where('nguoi_dung_id', $userId)
                            ->get()
                            ->getRow();
            
            if ($user) {
                $email = $user->email;
            } else {
                return false;
            }
        }
        
        // Kiểm tra trong bảng đăng ký sự kiện
        return $this->where('su_kien_id', $suKienId)
                   ->where('email', $email)
                   ->where('deleted_at IS NULL')
                   ->countAllResults() > 0;
    }
    
    /**
     * Đăng ký tham gia sự kiện (phương thức phía front-end)
     * 
     * @param array $data Dữ liệu đăng ký
     * @return bool|int
     */
    public function registerEvent($data)
    {
        // Xử lý dữ liệu đầu vào
        $insertData = [
            'su_kien_id' => $data['su_kien_id'],
            'ho_ten' => $data['ho_ten'],
            'email' => $data['email'],
            'dien_thoai' => $data['dien_thoai'],
            'nguoi_dung_id' => $data['nguoi_dung_id'] ?? null,
            'noi_dung_gop_y' => $data['noi_dung_gop_y'] ?? null,
            'nguon_gioi_thieu' => $data['nguon_gioi_thieu'] ?? null,
            'ngay_dang_ky' => date('Y-m-d H:i:s'),
            'status' => 0, // Mặc định là chưa xác nhận
            'loai_nguoi_dang_ky' => $data['loai_nguoi_dang_ky'] ?? 'Sinh viên',
            'hinh_thuc_tham_gia' => $data['hinh_thuc_tham_gia'] ?? 'Offline',
            'ma_xac_nhan' => $this->generateConfirmationCode(),
        ];
        
        // Sử dụng phương thức insert từ lớp cha
        $result = $this->insert($insertData);
        
        // Cập nhật số lượng đăng ký trong bảng su_kien nếu cần
        if ($result && !empty($data['su_kien_id'])) {
            $this->updateEventRegistrationCount($data['su_kien_id']);
        }
        
        return $result;
    }
    
    /**
     * Tạo mã xác nhận ngẫu nhiên
     * 
     * @param int $length Độ dài mã xác nhận
     * @return string
     */
    public function generateConfirmationCode($length = 8): string
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        $max = strlen($characters) - 1;
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, $max)];
        }
        
        return $code;
    }
    
    /**
     * Cập nhật số lượng đăng ký cho sự kiện
     * 
     * @param int $eventId ID sự kiện
     * @return bool
     */
    protected function updateEventRegistrationCount($eventId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('su_kien');
        
        // Đếm số lượng đăng ký
        $count = $this->where('su_kien_id', $eventId)
                     ->where('deleted_at IS NULL')
                     ->countAllResults();
        
        // Cập nhật vào bảng sự kiện
        return $builder->where('su_kien_id', $eventId)
                      ->update(['tong_dang_ky' => $count]);
    }
} 