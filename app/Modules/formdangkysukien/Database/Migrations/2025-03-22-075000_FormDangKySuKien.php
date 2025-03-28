<?php

namespace App\Modules\formdangkysukien\Database\Migrations;

use CodeIgniter\Database\Migration;

class FormDangKySuKien extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'form_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'ten_form' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
                'comment' => 'Tên form đăng ký'
            ],
            'mo_ta' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Mô tả chi tiết về form'
            ],
            'su_kien_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'comment' => 'Liên kết đến sự kiện'
            ],
            'cau_truc_form' => [
                'type' => 'JSON',
                'null' => false,
                'comment' => 'Cấu trúc form dưới dạng JSON'
            ],
            'hien_thi_cong_khai' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'comment' => 'Hiển thị form công khai'
            ],
            'bat_buoc_dien' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Bắt buộc phải điền form khi đăng ký'
            ],
            'so_lan_su_dung' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Số lần form được sử dụng'
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => '1: Hoạt động, 0: Không hoạt động'
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
                'null' => true,
                'comment' => 'Thời điểm xóa mềm (soft delete)'
            ]
        ]);

        // Thêm khóa chính
        $this->forge->addKey('form_id', true);
        
        // Thêm khoá ngoại
        $this->forge->addForeignKey('su_kien_id', 'su_kien', 'su_kien_id', 'CASCADE', 'CASCADE');
        
        // Thêm chỉ mục
        $this->forge->addKey('ten_form', false, false, 'idx_ten_form');
        $this->forge->addKey('su_kien_id', false, false, 'idx_su_kien_id');
        
        // Tạo bảng
        $this->forge->createTable('form_dang_ky_su_kien', true, [
            'ENGINE' => 'InnoDB',
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'COMMENT' => 'Bảng lưu trữ thông tin form đăng ký sự kiện'
        ]);
        
        // Thêm giá trị mặc định cho created_at bằng CURRENT_TIMESTAMP
        $this->db->query('ALTER TABLE `form_dang_ky_su_kien` MODIFY `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP NULL');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('form_dang_ky_su_kien');
    }
} 