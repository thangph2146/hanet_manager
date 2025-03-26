<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Nganh extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'nganh_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ten_nganh' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => false
            ],
            'ma_nganh' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false
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
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        // Thêm khóa chính
        $this->forge->addKey('nganh_id', true);
        
        // Thêm các chỉ mục
        $this->forge->addKey('ma_nganh', false, false, 'idx_ma_nganh');
        $this->forge->addKey('ten_nganh', false, false, 'idx_ten_nganh');
        $this->forge->addKey('phong_khoa_id', false, false, 'idx_phong_khoa_id');
        
        // Thêm ràng buộc unique cho ma_nganh
        $this->forge->addUniqueKey('ma_nganh', 'uk_ma_nganh');

        // Tạo bảng
        $this->forge->createTable('nganh');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('nganh');
    }
} 