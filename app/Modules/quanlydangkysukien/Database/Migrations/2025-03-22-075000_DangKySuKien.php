<?php

namespace App\Modules\quanlydangkysukien\Database\Migrations;

use CodeIgniter\Database\Migration;

class DangKySuKien extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'dangky_sukien_id' => [
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
                'comment' => 'Email người đăng ký'
            ],
            'ho_ten' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Họ tên người đăng ký'
            ],
            'dien_thoai' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => 'Số điện thoại liên hệ'
            ],
            'loai_nguoi_dang_ky' => [
                'type' => 'ENUM',
                'constraint' => ['khach', 'sinh_vien', 'giang_vien'],
                'default' => 'khach'
            ],
            'ngay_dang_ky' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')
            ],
            'ma_xac_nhan' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => 'Mã xác nhận đăng ký'
            ],
            'status' => [
                'type' => 'TINYINT',
                'default' => 0,
                'comment' => '0: chờ xác nhận, 1: đã xác nhận, -1: đã hủy'
            ],
            'noi_dung_gop_y' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'nguon_gioi_thieu' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Nguồn biết đến sự kiện'
            ],
            'don_vi_to_chuc' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Đơn vị/tổ chức của người đăng ký'
            ],
            'face_image_path' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'face_verified' => [
                'type' => 'BOOLEAN',
                'default' => false
            ],
            'da_check_in' => [
                'type' => 'BOOLEAN',
                'default' => false
            ],
            'da_check_out' => [
                'type' => 'BOOLEAN',
                'default' => false
            ],
            'checkin_sukien_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'checkout_sukien_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'thoi_gian_duyet' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'thoi_gian_huy' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'ly_do_huy' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'hinh_thuc_tham_gia' => [
                'type' => 'ENUM',
                'constraint' => ['offline', 'online', 'hybrid'],
                'default' => 'offline',
                'comment' => 'Hình thức tham gia đăng ký'
            ],
            'attendance_status' => [
                'type' => 'ENUM',
                'constraint' => ['not_attended', 'partial', 'full'],
                'default' => 'not_attended'
            ],
            'attendance_minutes' => [
                'type' => 'INT',
                'default' => 0
            ],
            'diem_danh_bang' => [
                'type' => 'ENUM',
                'constraint' => ['qr_code', 'face_id', 'manual', 'none'],
                'default' => 'none'
            ],
            'thong_tin_dang_ky' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Thông tin bổ sung khi đăng ký'
            ],
            'ly_do_tham_du' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Lý do muốn tham dự sự kiện'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')
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
        $this->forge->addKey('dangky_sukien_id', true);
        
        // Thêm các chỉ mục
        $this->forge->addKey('su_kien_id', false, false, 'idx_sukien_id');
        $this->forge->addKey('email', false, false, 'idx_email');
        $this->forge->addKey('ho_ten', false, false, 'idx_ho_ten');
        $this->forge->addKey('status', false, false, 'idx_status');
        $this->forge->addKey('da_check_in', false, false, 'idx_da_check_in');
        $this->forge->addKey('da_check_out', false, false, 'idx_da_check_out');
        $this->forge->addKey('hinh_thuc_tham_gia', false, false, 'idx_hinh_thuc_tham_gia');
        $this->forge->addKey('checkin_sukien_id', false, false, 'idx_checkin_sukien_id');
        $this->forge->addKey('checkout_sukien_id', false, false, 'idx_checkout_sukien_id');
        
        // Thêm chỉ mục unique
        $this->forge->addUniqueKey(['su_kien_id', 'email'], 'idx_sukien_email');
        
        
        // Tạo bảng
        $this->forge->createTable('dangky_sukien', true, [
            'ENGINE' => 'InnoDB',
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'COMMENT' => 'Bảng lưu trữ thông tin đăng ký sự kiện'
        ]);
        
        // Add foreign keys conditionally after table creation
        // Check if su_kien table exists
        $tableExists = $this->db->tableExists('su_kien');
        if ($tableExists) {
            $this->db->query('ALTER TABLE `dangky_sukien` ADD CONSTRAINT `fk_dangky_sukien_su_kien` FOREIGN KEY (`su_kien_id`) REFERENCES `su_kien`(`su_kien_id`) ON DELETE CASCADE ON UPDATE CASCADE');
        }
        
        // Check if checkin_sukien table exists
        $tableExists = $this->db->tableExists('checkin_sukien');
        if ($tableExists) {
            $this->db->query('ALTER TABLE `dangky_sukien` ADD CONSTRAINT `fk_dangky_sukien_checkin` FOREIGN KEY (`checkin_sukien_id`) REFERENCES `checkin_sukien`(`checkin_sukien_id`) ON DELETE SET NULL ON UPDATE SET NULL');
        }
        
        // Check if checkout_sukien table exists
        $tableExists = $this->db->tableExists('checkout_sukien');
        if ($tableExists) {
            $this->db->query('ALTER TABLE `dangky_sukien` ADD CONSTRAINT `fk_dangky_sukien_checkout` FOREIGN KEY (`checkout_sukien_id`) REFERENCES `checkout_sukien`(`checkout_sukien_id`) ON DELETE SET NULL ON UPDATE SET NULL');
        }
    }

    public function down()
    {
        // Drop foreign keys conditionally
        try {
            $this->db->query('ALTER TABLE `dangky_sukien` DROP FOREIGN KEY IF EXISTS `fk_dangky_sukien_su_kien`');
        } catch (\Exception $e) {
            // Ignore errors if constraint doesn't exist
        }
        
        try {
            $this->db->query('ALTER TABLE `dangky_sukien` DROP FOREIGN KEY IF EXISTS `fk_dangky_sukien_checkin`');
        } catch (\Exception $e) {
            // Ignore errors if constraint doesn't exist
        }
        
        try {
            $this->db->query('ALTER TABLE `dangky_sukien` DROP FOREIGN KEY IF EXISTS `fk_dangky_sukien_checkout`');
        } catch (\Exception $e) {
            // Ignore errors if constraint doesn't exist
        }
        
        // Sau đó xóa bảng
        $this->forge->dropTable('dangky_sukien');
    }
}