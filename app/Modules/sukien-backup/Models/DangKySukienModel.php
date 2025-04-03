<?php

namespace App\Modules\sukien\Models;

use CodeIgniter\Model;

class DangKySukienModel extends Model
{
    protected $table            = 'dangky_sukien';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['su_kien_id', 'nguoi_dung_id', 'ngay_dang_ky', 'noi_dung_gop_y', 'nguon_gioi_thieu', 'loai_nguoi_dung', 'status', 'bin'];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    
    // Mock Data - Dữ liệu mẫu cho đăng ký sự kiện
    private $mockRegistrations = [
        [
            'id' => 1,
            'su_kien_id' => 1,
            'nguoi_dung_id' => 101,
            'ngay_dang_ky' => '2023-05-16 10:30:00',
            'noi_dung_gop_y' => 'Tôi mong muốn được nghe nhiều hơn về blockchain và ứng dụng trong ngành ngân hàng.',
            'nguon_gioi_thieu' => 'Website trường',
            'loai_nguoi_dung' => 'Sinh viên',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-05-16 10:30:00',
            'updated_at' => '2023-05-16 10:30:00'
        ],
        [
            'id' => 2,
            'su_kien_id' => 1,
            'nguoi_dung_id' => 102,
            'ngay_dang_ky' => '2023-05-17 14:20:00',
            'noi_dung_gop_y' => 'Tôi quan tâm đến cơ hội việc làm sau khi tốt nghiệp.',
            'nguon_gioi_thieu' => 'Bạn bè',
            'loai_nguoi_dung' => 'Sinh viên',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-05-17 14:20:00',
            'updated_at' => '2023-05-17 14:20:00'
        ],
        [
            'id' => 3,
            'su_kien_id' => 2,
            'nguoi_dung_id' => 103,
            'ngay_dang_ky' => '2023-05-23 09:10:00',
            'noi_dung_gop_y' => 'Tôi muốn biết thêm về các doanh nghiệp tham gia ngày hội việc làm.',
            'nguon_gioi_thieu' => 'Email từ trường',
            'loai_nguoi_dung' => 'Cựu sinh viên',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-05-23 09:10:00',
            'updated_at' => '2023-05-23 09:10:00'
        ],
        [
            'id' => 4,
            'su_kien_id' => 3,
            'nguoi_dung_id' => 104,
            'ngay_dang_ky' => '2023-06-02 16:45:00',
            'noi_dung_gop_y' => 'Mong muốn được thực hành nhiều hơn trong workshop.',
            'nguon_gioi_thieu' => 'Facebook',
            'loai_nguoi_dung' => 'Sinh viên',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-06-02 16:45:00',
            'updated_at' => '2023-06-02 16:45:00'
        ],
        [
            'id' => 5,
            'su_kien_id' => 4,
            'nguoi_dung_id' => 105,
            'ngay_dang_ky' => '2023-06-10 11:30:00',
            'noi_dung_gop_y' => 'Tôi có ý tưởng khởi nghiệp và muốn nhận được phản hồi từ chuyên gia.',
            'nguon_gioi_thieu' => 'Website trường',
            'loai_nguoi_dung' => 'Đơn vị ngoài',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-06-10 11:30:00',
            'updated_at' => '2023-06-10 11:30:00'
        ]
    ];

    /**
     * Lấy đăng ký theo sự kiện
     */
    public function getRegistrationsByEvent($eventId)
    {
        // Trong triển khai thực tế:
        // return $this->where('su_kien_id', $eventId)
        //             ->where('status', 1)
        //             ->findAll();
        
        // Sử dụng mock data cho demo
        $registrations = [];
        foreach ($this->mockRegistrations as $registration) {
            if ($registration['su_kien_id'] == $eventId && $registration['status'] == 1) {
                $registrations[] = $registration;
            }
        }
        return $registrations;
    }
    
    /**
     * Lấy số lượng đăng ký theo sự kiện
     */
    public function countRegistrationsByEvent($eventId)
    {
        // Trong triển khai thực tế:
        // return $this->where('su_kien_id', $eventId)
        //             ->where('status', 1)
        //             ->countAllResults();
        
        // Sử dụng mock data cho demo
        $count = 0;
        foreach ($this->mockRegistrations as $registration) {
            if ($registration['su_kien_id'] == $eventId && $registration['status'] == 1) {
                $count++;
            }
        }
        return $count;
    }
    
    /**
     * Đăng ký sự kiện mới
     */
    public function registerEvent($data)
    {
        // Trong triển khai thực tế:
        // return $this->insert($data);
        
        // Trong bản demo, chỉ trả về true để giả lập thành công
        return true;
    }
    
    /**
     * Kiểm tra xem người dùng đã đăng ký sự kiện chưa
     * 
     * @param int $userId ID của người dùng
     * @param int $eventId ID của sự kiện
     * @return bool
     */
    public function isRegistered($userId, $eventId)
    {
        // Trong triển khai thực tế:
        // return $this->where('nguoi_dung_id', $userId)
        //             ->where('su_kien_id', $eventId)
        //             ->where('status', 1)
        //             ->countAllResults() > 0;
        
        // Sử dụng mock data cho demo
        foreach ($this->mockRegistrations as $registration) {
            if ($registration['nguoi_dung_id'] == $userId && $registration['su_kien_id'] == $eventId && $registration['status'] == 1) {
                return true;
            }
        }
        
        return false;
    }
} 