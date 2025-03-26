<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Bachoc extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'bac_hoc_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ten_bac_hoc' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'ma_bac_hoc' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
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
        $this->forge->addKey('bac_hoc_id', true);
        
        // Thêm chỉ mục cho ten_bac_hoc
        $this->forge->addKey('ten_bac_hoc', false, false, 'idx_ten_bac_hoc');
        
        // Thêm unique key cho ten_bac_hoc
        $this->forge->addUniqueKey('ten_bac_hoc', 'uk_ten_bac_hoc');

        // Tạo bảng
        $this->forge->createTable('bac_hoc');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('bac_hoc');
    }
} 