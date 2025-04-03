<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class LoaiSuKienSeeder extends Seeder
{
    public function run()
    {
        echo "Bắt đầu tạo dữ liệu cho bảng loại sự kiện...\n";
        
        // Kiểm tra xem bảng tồn tại không
        if (!$this->db->tableExists('loai_su_kien')) {
            echo "Bảng loai_su_kien không tồn tại. Hãy chạy migration trước.\n";
            return;
        }
        
        // Kiểm tra cấu trúc bảng
        $fields = $this->db->getFieldData('loai_su_kien');
        $tableColumns = [];
        foreach ($fields as $field) {
            $tableColumns[] = $field->name;
        }
        
        echo "Cấu trúc bảng loai_su_kien: " . implode(', ', $tableColumns) . "\n";
        
        // Kiểm tra xem bảng có dữ liệu chưa
        $existingRecords = $this->db->table('loai_su_kien')->countAllResults();
        
        if ($existingRecords > 0) {
            echo "Bảng loai_su_kien đã có $existingRecords bản ghi.\n";
            
            // Xóa dữ liệu cũ trước khi thêm mới
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
        
        // Dữ liệu cơ bản cho bảng loại sự kiện
        $baseData = [
            [
                'ten_loai_su_kien' => 'Hội thảo',
                'ma_loai_su_kien' => 'HT',
                'mo_ta' => 'Hội thảo',
                'mota' => 'Hội thảo', // Thêm trường thay thế trong trường hợp tên cột khác
                'description' => 'Hội thảo', // Thêm trường thay thế trong trường hợp tên cột khác
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Hội nghị',
                'ma_loai_su_kien' => 'HN',
                'mo_ta' => 'Hội nghị',
                'mota' => 'Hội nghị',
                'description' => 'Hội nghị',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Workshop',
                'ma_loai_su_kien' => 'WS',
                'mo_ta' => 'Workshop',
                'mota' => 'Workshop',
                'description' => 'Workshop',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Seminar',
                'ma_loai_su_kien' => 'SM',
                'mo_ta' => 'Seminar',
                'mota' => 'Seminar',
                'description' => 'Seminar', 
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'ten_loai_su_kien' => 'Tọa đàm',
                'ma_loai_su_kien' => 'TD',
                'mo_ta' => 'Tọa đàm',
                'mota' => 'Tọa đàm',
                'description' => 'Tọa đàm',
                'status' => 1,
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Điều chỉnh dữ liệu để phù hợp với cấu trúc bảng
        $data = [];
        foreach ($baseData as $item) {
            $filteredItem = [];
            foreach ($item as $key => $value) {
                if (in_array($key, $tableColumns)) {
                    $filteredItem[$key] = $value;
                }
            }
            $data[] = $filteredItem;
        }
        
        // Thêm dữ liệu vào bảng loai_su_kien
        try {
            if (!empty($data)) {
                $this->db->table('loai_su_kien')->insertBatch($data);
                echo "Đã tạo " . count($data) . " bản ghi loại sự kiện.\n";
            }
            
            echo "Seeder LoaiSuKienSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho loại sự kiện.\n";
        } catch (\Exception $e) {
            echo "Lỗi: " . $e->getMessage() . "\n";
            
            // Hiển thị thêm thông tin để debug
            echo "Chi tiết dữ liệu đang cố gắng thêm vào:\n";
            print_r($data);
            
            // Thử chạy thêm từng bản ghi một để xác định bản ghi nào gây lỗi
            echo "Đang thử thêm từng bản ghi một để xác định lỗi:\n";
            foreach ($data as $index => $record) {
                try {
                    $this->db->table('loai_su_kien')->insert($record);
                    echo "Bản ghi $index đã được thêm thành công.\n";
                } catch (\Exception $e) {
                    echo "Lỗi ở bản ghi $index: " . $e->getMessage() . "\n";
                }
            }
        }
    }
}