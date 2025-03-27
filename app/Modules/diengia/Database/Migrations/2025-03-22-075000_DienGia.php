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
                'unsigned' => true,
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
                'null' => true
            ],
            'to_chuc' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'gioi_thieu' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'avatar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'dien_thoai' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'website' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'nguoi_dung_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true
            ],
            'chuyen_mon' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'thanh_tuu' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'mang_xa_hoi' => [
                'type' => 'JSON',
                'null' => true
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'null' => false
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
        $this->forge->addKey('dien_gia_id', true);
        
        // Thêm chỉ mục cho các trường thường xuyên tìm kiếm
        $this->forge->addKey('ten_dien_gia', false, false, 'idx_ten_dien_gia');
        $this->forge->addKey('email', false, false, 'idx_email');
        $this->forge->addKey('status', false, false, 'idx_status');
        
        // Thêm unique key cho email
        $this->forge->addUniqueKey('email', 'uk_email');

        // Tạo bảng
        $this->forge->createTable('dien_gia', true, [
            'ENGINE' => 'InnoDB',
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci'
        ]);
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('dien_gia');
    }
} 