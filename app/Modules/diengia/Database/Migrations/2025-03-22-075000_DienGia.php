<?php

namespace App\Modules\diengia\Database\Migrations;

use CodeIgniter\Database\Migration;

class DienGia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'dien_gia_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'ten_dien_gia' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'chuc_danh' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Chức danh của diễn giả (Tiến sĩ, Giáo sư...)'
            ],
            'to_chuc' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Tổ chức/công ty của diễn giả'
            ],
            'gioi_thieu' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Giới thiệu chi tiết về diễn giả'
            ],
            'avatar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Đường dẫn đến ảnh đại diện'
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Email liên hệ'
            ],
            'dien_thoai' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'comment' => 'Số điện thoại liên hệ'
            ],
            'website' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Website cá nhân nếu có'
            ],
            'chuyen_mon' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Lĩnh vực chuyên môn'
            ],
            'thanh_tuu' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Các thành tựu nổi bật'
            ],
            'mang_xa_hoi' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Thông tin mạng xã hội (JSON)'
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => '1: Hoạt động, 0: Không hoạt động'
            ],
            'so_su_kien_tham_gia' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Số sự kiện đã tham gia'
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
        $this->forge->addKey('dien_gia_id', true);
        
        // Thêm chỉ mục
        $this->forge->addKey('ten_dien_gia', false, false, 'idx_ten_dien_gia');
        $this->forge->addKey('to_chuc', false, false, 'idx_to_chuc');
        $this->forge->addKey('email', false, false, 'idx_email');
        
        // Tạo bảng
        $this->forge->createTable('dien_gia', true, [
            'ENGINE' => 'InnoDB',
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'COMMENT' => 'Bảng lưu trữ thông tin diễn giả'
        ]);
        
        // Thêm giá trị mặc định cho created_at bằng CURRENT_TIMESTAMP
        $this->db->query('ALTER TABLE `dien_gia` MODIFY `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP NULL');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('dien_gia');
    }
} 