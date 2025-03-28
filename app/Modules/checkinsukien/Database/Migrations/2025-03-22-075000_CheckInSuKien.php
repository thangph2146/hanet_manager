<?php

namespace App\Modules\checkinsukien\Database\Migrations;

use CodeIgniter\Database\Migration;

class CheckInSuKien extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'checkin_sukien_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'sukien_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'Email người check-in'
            ],
            'ho_ten' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Họ tên người check-in'
            ],
            'dangky_sukien_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'thoi_gian_check_in' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'checkin_type' => [
                'type' => 'ENUM',
                'constraint' => ['face_id', 'manual', 'qr_code', 'online'],
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
                'comment' => 'Dữ liệu vị trí khi check-in'
            ],
            'device_info' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Thiết bị dùng để check-in'
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
                'comment' => 'Địa chỉ IP khi check-in'
            ],
            'thong_tin_bo_sung' => [
                'type' => 'JSON',
                'null' => true
            ],
            'ghi_chu' => [
                'type' => 'TEXT',
                'null' => true
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
        $this->forge->addKey('checkin_sukien_id', true);
        
        // Thêm các chỉ mục (index)
        $this->forge->addKey('sukien_id', false, false, 'idx_sukien_id');
        $this->forge->addKey('email', false, false, 'idx_email');
        $this->forge->addKey('dangky_sukien_id', false, false, 'idx_dangky_sukien_id');
        $this->forge->addKey('thoi_gian_check_in', false, false, 'idx_thoi_gian_check_in');
        $this->forge->addKey('checkin_type', false, false, 'idx_checkin_type');
        $this->forge->addKey('hinh_thuc_tham_gia', false, false, 'idx_hinh_thuc_tham_gia');
        
        // Thêm khóa ngoại
        $this->forge->addForeignKey('sukien_id', 'su_kien', 'su_kien_id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dangky_sukien_id', 'dangky_sukien', 'dangky_sukien_id', 'SET NULL', 'CASCADE');
        
        // Tạo bảng
        $this->forge->createTable('checkin_sukien', true, [
            'ENGINE' => 'InnoDB',
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'COMMENT' => 'Bảng lưu trữ thông tin check-in sự kiện'
        ]);
        
        // Thêm giá trị mặc định CURRENT_TIMESTAMP cho các trường datetime
        $this->db->query("ALTER TABLE `checkin_sukien` MODIFY `thoi_gian_check_in` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE `checkin_sukien` MODIFY `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP");
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('checkin_sukien');
    }
} 