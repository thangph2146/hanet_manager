<?php

namespace App\Modules\sukien\Models;

use CodeIgniter\Model;

class LoaiSukienModel extends Model
{
    protected $table            = 'loai_su_kien';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['loai_su_kien', 'slug', 'status', 'bin'];
    
    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Mock Data - Dữ liệu mẫu cho loại sự kiện
    private $mockEventTypes = [
        [
            'id' => 1,
            'loai_su_kien' => 'Hội thảo',
            'slug' => 'hoi-thao',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-01-01 00:00:00',
            'updated_at' => '2023-01-01 00:00:00',
            'deleted_at' => null
        ],
        [
            'id' => 2,
            'loai_su_kien' => 'Nghề nghiệp',
            'slug' => 'nghe-nghiep',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-01-01 00:00:00',
            'updated_at' => '2023-01-01 00:00:00',
            'deleted_at' => null
        ],
        [
            'id' => 3,
            'loai_su_kien' => 'Workshop',
            'slug' => 'workshop',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-01-01 00:00:00',
            'updated_at' => '2023-01-01 00:00:00',
            'deleted_at' => null
        ],
        [
            'id' => 4,
            'loai_su_kien' => 'Hoạt động sinh viên',
            'slug' => 'hoat-dong-sinh-vien',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-01-01 00:00:00',
            'updated_at' => '2023-01-01 00:00:00',
            'deleted_at' => null
        ]
    ];

    /**
     * Lấy tất cả loại sự kiện
     */
    public function getAllEventTypes()
    {
        // Trong triển khai thực tế, bạn sẽ truy vấn từ cơ sở dữ liệu
        // Ví dụ: return $this->where('status', 1)->findAll();
        
        // Sử dụng mock data cho demo
        return $this->mockEventTypes;
    }
    
    /**
     * Lấy loại sự kiện theo ID
     */
    public function getEventTypeById($id)
    {
        // Trong triển khai thực tế:
        // return $this->find($id);
        
        // Sử dụng mock data cho demo
        foreach ($this->mockEventTypes as $type) {
            if ($type['id'] == $id) {
                return $type;
            }
        }
        return null;
    }

    /**
     * Lấy loại sự kiện theo slug
     */
    public function getEventTypeBySlug($slug)
    {
        // Trong triển khai thực tế:
        // return $this->where('slug', $slug)->first();
        
        // Sử dụng mock data cho demo
        foreach ($this->mockEventTypes as $type) {
            if ($type['slug'] == $slug) {
                return $type;
            }
        }
        return null;
    }

    /**
     * Tạo slug từ tên loại sự kiện
     */
    public function createSlug($name)
    {
        // Chuyển đổi tiếng Việt sang không dấu
        $slug = $this->convertToSlug($name);
        
        // Kiểm tra xem slug đã tồn tại chưa
        // Trong triển khai thực tế:
        // $count = $this->where('slug', $slug)->countAllResults();
        
        $count = 0;
        foreach ($this->mockEventTypes as $type) {
            if ($type['slug'] == $slug) {
                $count++;
            }
        }
        
        // Nếu slug đã tồn tại, thêm số vào cuối
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        return $slug;
    }
    
    /**
     * Chuyển đổi chuỗi tiếng Việt thành slug
     */
    private function convertToSlug($string)
    {
        $vietnamese = array(
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
            'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ',
            'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ',
            'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ',
            'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ',
            'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ',
            'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ',
            'Đ'
        );
        
        $latin = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd'
        );
        
        // Thay thế ký tự tiếng Việt
        $string = str_replace($vietnamese, $latin, $string);
        
        // Chuyển đổi sang chữ thường
        $string = strtolower($string);
        
        // Thay thế các ký tự không phải chữ cái và số bằng dấu gạch ngang
        $string = preg_replace('/[^a-z0-9]/', '-', $string);
        
        // Loại bỏ các dấu gạch ngang liên tiếp
        $string = preg_replace('/-+/', '-', $string);
        
        // Xóa dấu gạch ngang ở đầu và cuối
        $string = trim($string, '-');
        
        return $string;
    }

    /**
     * Ghi đè phương thức insert để tự động tạo slug
     */
    public function insert($data = null, bool $returnID = true)
    {
        // Tạo slug nếu chưa có
        if (!isset($data['slug']) && isset($data['loai_su_kien'])) {
            $data['slug'] = $this->createSlug($data['loai_su_kien']);
        }
        
        return parent::insert($data, $returnID);
    }
    
    /**
     * Ghi đè phương thức update để tự động cập nhật slug
     */
    public function update($id = null, $data = null): bool
    {
        // Cập nhật slug nếu tên loại sự kiện thay đổi
        if (!isset($data['slug']) && isset($data['loai_su_kien'])) {
            $data['slug'] = $this->createSlug($data['loai_su_kien']);
        }
        
        return parent::update($id, $data);
    }
} 