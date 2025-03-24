<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Template extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'template_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ten_template' => [
                'type'           => 'VARCHAR',
                'constraint'     => 255,
                'null'           => false,
            ],
            'ma_template' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'null'           => true,
            ],
            'status' => [
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 1,
                'null'           => false,
            ],
            'bin' => [
                'type'           => 'TINYINT',
                'constraint'     => 1,
                'default'        => 0,
                'null'           => false,
            ],
            'created_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'updated_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
            'deleted_at' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        // Thiết lập primary key
        $this->forge->addKey('template_id', true);
        
        // Thiết lập các index
        $this->forge->addKey('ma_template');
        $this->forge->addKey('ten_template', false, false, 'idx_ten_template');
        $this->forge->addKey('status');
        $this->forge->addKey('bin');
        
        // Thiết lập ràng buộc duy nhất
        $this->forge->addUniqueKey('ten_template', 'uk_ten_template');
        
        // Tạo bảng
        $this->forge->createTable('template');
    }

    public function down()
    {
        // Xóa bảng nếu tồn tại
        $this->forge->dropTable('template', true);
    }
} 