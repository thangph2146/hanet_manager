<?php

namespace App\Modules\quanlycheckinsukien\Database\Migrations;

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
            'su_kien_id' => [
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
                'constraint' => 11,
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
                'constraint' => 1,
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
        $this->forge->addKey('su_kien_id', false, false, 'idx_su_kien_id');
        $this->forge->addKey('email', false, false, 'idx_email');
        $this->forge->addKey('dangky_sukien_id', false, false, 'idx_dangky_su_kien_id');
        $this->forge->addKey('thoi_gian_check_in', false, false, 'idx_thoi_gian_check_in');
        $this->forge->addKey('checkin_type', false, false, 'idx_checkin_type');
        $this->forge->addKey('hinh_thuc_tham_gia', false, false, 'idx_hinh_thuc_tham_gia');
        
        // Tạo bảng
        $this->forge->createTable('checkin_sukien', true, [
            'ENGINE' => 'InnoDB',
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci'
        ]);
        
        // Thêm giá trị mặc định CURRENT_TIMESTAMP cho các trường datetime
        $this->db->query("ALTER TABLE `checkin_sukien` MODIFY `thoi_gian_check_in` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->db->query("ALTER TABLE `checkin_sukien` MODIFY `created_at` DATETIME NULL DEFAULT CURRENT_TIMESTAMP");
        
        // Add foreign keys conditionally after table creation
        // Check if su_kien table exists
        $tableExists = $this->db->tableExists('su_kien');
        if ($tableExists) {
            $this->db->query('ALTER TABLE `checkin_sukien` ADD CONSTRAINT `fk_checkin_sukien_su_kien` FOREIGN KEY (`su_kien_id`) REFERENCES `su_kien`(`su_kien_id`) ON DELETE CASCADE ON UPDATE CASCADE');
        }
        
        // Check if dangky_sukien table exists
        $tableExists = $this->db->tableExists('dangky_sukien');
        if ($tableExists) {
            $this->db->query('ALTER TABLE `checkin_sukien` ADD CONSTRAINT `fk_checkin_sukien_dangky_sukien` FOREIGN KEY (`dangky_sukien_id`) REFERENCES `dangky_sukien`(`dangky_sukien_id`) ON DELETE SET NULL ON UPDATE SET NULL');
        }
    }

    public function down()
    {
        // Drop foreign keys first
        $this->db->query('ALTER TABLE `checkin_sukien` DROP FOREIGN KEY IF EXISTS `fk_checkin_sukien_su_kien`');
        $this->db->query('ALTER TABLE `checkin_sukien` DROP FOREIGN KEY IF EXISTS `fk_checkin_sukien_dangky_sukien`');
        
        // Xóa bảng
        $this->forge->dropTable('checkin_sukien');
    }
}