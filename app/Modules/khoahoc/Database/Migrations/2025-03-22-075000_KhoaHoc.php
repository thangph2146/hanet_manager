<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class KhoaHoc extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'khoa_hoc_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ten_khoa_hoc' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'nam_bat_dau' => [
                'type' => 'INT',
                'null' => true
            ],
            'nam_ket_thuc' => [
                'type' => 'INT',
                'null' => true
            ],
            'phong_khoa_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        // Thêm khóa chính
        $this->forge->addKey('khoa_hoc_id', true);
        
        // Thêm chỉ mục cho ten_khoa_hoc
        $this->forge->addKey('ten_khoa_hoc', false, false, 'idx_ten_khoa_hoc');
        
        // Thêm chỉ mục cho phong_khoa_id
        $this->forge->addKey('phong_khoa_id', false, false, 'idx_phong_khoa_id');

        // Tạo bảng
        $this->forge->createTable('khoa_hoc');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('khoa_hoc');
    }
} 