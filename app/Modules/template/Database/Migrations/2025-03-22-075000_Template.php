<?php

namespace App\Modules\template\Database\Migrations;

use CodeIgniter\Database\Migration;

class Template extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'template_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'ten_template' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'ma_template' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
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
        $this->forge->addKey('template_id', true);
        
        // Thêm chỉ mục cho ten_template
        $this->forge->addKey('ten_template', false, false, 'idx_ten_template');
        
        // Thêm unique key cho ten_template
        $this->forge->addUniqueKey('ten_template', 'uk_ten_template');

        // Tạo bảng
        $this->forge->createTable('template', true, [
            'ENGINE' => 'InnoDB',
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci'
        ]);
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('template');
    }
} 