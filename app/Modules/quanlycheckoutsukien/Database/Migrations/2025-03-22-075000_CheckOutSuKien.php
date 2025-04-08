<?php

namespace App\Modules\quanlycheckoutsukien\Database\Migrations;

use CodeIgniter\Database\Migration;

class CheckOutSuKien extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'checkout_sukien_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'su_kien_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'Email người check-out'
            ],
            'ho_ten' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Họ tên người check-out'
            ],
            'dangky_sukien_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'checkin_sukien_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'thoi_gian_check_out' => [
                'type' => 'DATETIME',
                'null' => false,
            ],
            'checkout_type' => [
                'type' => 'ENUM',
                'constraint' => ['face_id', 'manual', 'qr_code', 'auto', 'online'],
                'null' => false
            ],
            'face_image_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'face_match_score' => [
                'type' => 'FLOAT',
                'null' => true,
                'comment' => 'Điểm số khớp khuôn mặt 0-1'
            ],
            'face_verified' => [
                'type' => 'BOOLEAN',
                'default' => false
            ],
            'ma_xac_nhan' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'status' => [
                'type' => 'TINYINT',
                'default' => 1
            ],
            'location_data' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Dữ liệu vị trí khi check-out'
            ],
            'device_info' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Thiết bị dùng để check-out'
            ],
            'attendance_duration_minutes' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'Thời gian tham dự tính bằng phút'
            ],
            'hinh_thuc_tham_gia' => [
                'type' => 'ENUM',
                'constraint' => ['offline', 'online'],
                'default' => 'offline',
                'comment' => 'Hình thức tham gia'
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
                'comment' => 'Địa chỉ IP khi check-out'
            ],
            'thong_tin_bo_sung' => [
                'type' => 'JSON',
                'null' => true
            ],
            'ghi_chu' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'feedback' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Phản hồi của người tham gia'
            ],
            'danh_gia' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'Điểm đánh giá 1-5 sao'
            ],
            'noi_dung_danh_gia' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Nội dung đánh giá chi tiết'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        // Thêm khóa chính
        $this->forge->addKey('checkout_sukien_id', true);
        
        // Thêm các chỉ mục (index)
        $this->forge->addKey('su_kien_id', false, false, 'idx_su_kien_id');
        $this->forge->addKey('email', false, false, 'idx_email');
        $this->forge->addKey('dangky_sukien_id', false, false, 'idx_dangky_sukien_id');
        $this->forge->addKey('checkin_sukien_id', false, false, 'idx_checkin_sukien_id');
        $this->forge->addKey('thoi_gian_check_out', false, false, 'idx_thoi_gian_check_out');
        $this->forge->addKey('checkout_type', false, false, 'idx_checkout_type');
        $this->forge->addKey('hinh_thuc_tham_gia', false, false, 'idx_hinh_thuc_tham_gia');
        
        // Tạo bảng trước
        $this->forge->createTable('checkout_sukien', true, [
            'ENGINE' => 'InnoDB',
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'COMMENT' => 'Bảng lưu trữ thông tin check-out sự kiện'
        ]);
        
        // Thiết lập khóa ngoại sau khi đã tạo bảng
        $hasForeignKeys = true;
        
        // Thêm khóa ngoại nếu bảng su_kien tồn tại
        if ($this->db->tableExists('su_kien')) {
            $this->db->query('ALTER TABLE `checkout_sukien` ADD CONSTRAINT `checkout_sukien_su_kien_id_foreign` 
                              FOREIGN KEY (`su_kien_id`) REFERENCES `su_kien`(`su_kien_id`) 
                              ON DELETE CASCADE ON UPDATE CASCADE');
        } else {
            $hasForeignKeys = false;
            log_message('warning', 'Bảng su_kien không tồn tại, không thể tạo khóa ngoại su_kien_id');
        }
        
        // Thêm khóa ngoại nếu bảng dangky_sukien tồn tại
        if ($this->db->tableExists('dangky_sukien')) {
            $this->db->query('ALTER TABLE `checkout_sukien` ADD CONSTRAINT `checkout_sukien_dangky_sukien_id_foreign` 
                              FOREIGN KEY (`dangky_sukien_id`) REFERENCES `dangky_sukien`(`dangky_sukien_id`) 
                              ON DELETE SET NULL ON UPDATE CASCADE');
        } else {
            $hasForeignKeys = false;
            log_message('warning', 'Bảng dangky_sukien không tồn tại, không thể tạo khóa ngoại dangky_sukien_id');
        }
        
        // Thêm khóa ngoại nếu bảng checkin_sukien tồn tại
        if ($this->db->tableExists('checkin_sukien')) {
            $this->db->query('ALTER TABLE `checkout_sukien` ADD CONSTRAINT `checkout_sukien_checkin_sukien_id_foreign` 
                              FOREIGN KEY (`checkin_sukien_id`) REFERENCES `checkin_sukien`(`checkin_sukien_id`) 
                              ON DELETE SET NULL ON UPDATE CASCADE');
        } else {
            $hasForeignKeys = false;
            log_message('warning', 'Bảng checkin_sukien không tồn tại, không thể tạo khóa ngoại checkin_sukien_id');
        }
        
        if (!$hasForeignKeys) {
            log_message('notice', 'Một số khóa ngoại không thể tạo do bảng tham chiếu chưa tồn tại. Vui lòng chạy migration các bảng liên quan trước.');
        }
        
        // Thêm giá trị mặc định CURRENT_TIMESTAMP cho trường thoi_gian_check_out
        $this->db->query("ALTER TABLE `checkout_sukien` MODIFY `thoi_gian_check_out` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
        
        // Thêm giá trị mặc định CURRENT_TIMESTAMP cho trường created_at
        $this->db->query("ALTER TABLE `checkout_sukien` MODIFY `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP");
        
        // Thêm ràng buộc check cho đánh giá
        $this->db->query("ALTER TABLE `checkout_sukien` ADD CONSTRAINT `check_danh_gia` CHECK (`danh_gia` BETWEEN 1 AND 5 OR `danh_gia` IS NULL)");
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('checkout_sukien');
    }
} 