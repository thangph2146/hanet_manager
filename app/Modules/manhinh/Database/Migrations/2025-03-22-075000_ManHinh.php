<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ManHinh extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'man_hinh_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ten_man_hinh' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'ma_man_hinh' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'camera_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'template_id' => [
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
        $this->forge->addKey('man_hinh_id', true);
        
        // Thêm chỉ mục
        $this->forge->addKey('ten_man_hinh', false, false, 'idx_ten_man_hinh');
        
        // Thêm ràng buộc unique cho ten_man_hinh
        $this->forge->addUniqueKey('ten_man_hinh', 'uk_ten_man_hinh');

        // Tạo bảng
        $this->forge->createTable('man_hinh');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('man_hinh');
    }
} 