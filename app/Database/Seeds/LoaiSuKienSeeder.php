<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class LoaiSuKienSeeder extends Seeder
{
    public function run()
    {
        echo "Bắt đầu tạo dữ liệu cho bảng loại sự kiện...\n";
        
        // Kiểm tra xem bảng có dữ liệu chưa
        $existingRecords = $this->db->table('loai_su_kien')->countAllResults();
        
        if ($existingRecords > 0) {
            echo "Bảng loai_su_kien đã có $existingRecords bản ghi.\n";
            
            // Xóa dữ liệu cũ trước khi thêm mới
            if ($this->db->tableExists('loai_su_kien')) {
                try {
                    // Kiểm tra bằng cách thử truncate bảng
                    $this->db->table('loai_su_kien')->truncate();
                    echo "Đã xóa dữ liệu cũ trong bảng loai_su_kien.\n";
                } catch (\Exception $e) {
                    echo "Không thể xóa dữ liệu vì tồn tại khóa ngoại tham chiếu đến bảng này.\n";
                    
                    // Kiểm tra xem có sự kiện nào đang sử dụng loại sự kiện
                    if ($this->db->tableExists('su_kien')) {
                        $eventCount = $this->db->table('su_kien')->countAllResults();
                        if ($eventCount > 0) {
                            echo "Có $eventCount sự kiện đang có tham chiếu đến loại sự kiện.\n";
                            echo "Đang bỏ qua việc thêm dữ liệu mới để tránh lỗi.\n";
                            return;
                        }
                    }
                    
                    // Nếu không có tham chiếu, thử xóa thủ công
                    try {
                        $this->db->table('loai_su_kien')->emptyTable();
                        echo "Đã xóa dữ liệu cũ trong bảng loai_su_kien bằng phương thức an toàn.\n";
                    } catch (\Exception $e) {
                        echo "Không thể xóa dữ liệu. Bỏ qua seeder này.\n";
                        return;
                    }
                }
            }
        }
        
        // Dữ liệu mẫu cho bảng loại sự kiện
        $data = [
            [
                'ten_loai_su_kien' => 'Hội thảo',
                'ma_loai_su_kien' => 'HT',
                'mo_ta' => 'Hội thảo',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Hội nghị',
                'ma_loai_su_kien' => 'HN',
                'mo_ta' => 'Hội nghị',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Workshop',
                'ma_loai_su_kien' => 'WS',
                'mo_ta' => 'Workshop',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Seminar',
                'ma_loai_su_kien' => 'SM',
                'mo_ta' => 'Seminar',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Tọa đàm',
                'ma_loai_su_kien' => 'TD',
                'mo_ta' => 'Tọa đàm',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng loai_su_kien
        try {
            if (!empty($data)) {
                $this->db->table('loai_su_kien')->insertBatch($data);
                echo "Đã tạo " . count($data) . " bản ghi loại sự kiện.\n";
            }
            
            echo "Seeder LoaiSuKienSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho loại sự kiện.\n";
        } catch (\Exception $e) {
            // Kiểm tra xem lỗi có phải do trùng lắp dữ liệu không
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                echo "Lỗi: Dữ liệu đã tồn tại trong bảng loai_su_kien.\n";
                echo "Có thể bạn đã chạy seeder này trước đó hoặc dữ liệu được thêm theo cách khác.\n";
            } else {
                // Nếu là lỗi khác, hiển thị đầy đủ
                throw $e;
            }
        }
    }
}