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
    protected $allowedFields    = ['loai_su_kien', 'status', 'bin'];
    
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
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-01-01 00:00:00',
            'updated_at' => '2023-01-01 00:00:00',
            'deleted_at' => null
        ],
        [
            'id' => 2,
            'loai_su_kien' => 'Nghề nghiệp',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-01-01 00:00:00',
            'updated_at' => '2023-01-01 00:00:00',
            'deleted_at' => null
        ],
        [
            'id' => 3,
            'loai_su_kien' => 'Workshop',
            'status' => 1,
            'bin' => 0,
            'created_at' => '2023-01-01 00:00:00',
            'updated_at' => '2023-01-01 00:00:00',
            'deleted_at' => null
        ],
        [
            'id' => 4,
            'loai_su_kien' => 'Hoạt động sinh viên',
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
} 