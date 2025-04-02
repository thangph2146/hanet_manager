<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class LoaiNguoiDung extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'loai_nguoi_dung_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ten_loai' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'mo_ta' => [
                'type' => 'TEXT',
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

        // Add primary key
        $this->forge->addKey('loai_nguoi_dung_id', true);
        
        // Add index for ten_loai
        $this->forge->addKey('ten_loai', false, false, 'idx_ten_loai');
        
        // Add index for status
        $this->forge->addKey('status', false, false, 'idx_status');
        
        // Add index for deleted_at (để tối ưu query khi tìm bản ghi bị xóa mềm)
        $this->forge->addKey('deleted_at', false, false, 'idx_deleted_at');
        
        // Add unique constraint for ten_loai
        $this->forge->addKey('ten_loai', false, true, 'uk_ten_loai');

        // Create the table
        $this->forge->createTable('loai_nguoi_dung');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('loai_nguoi_dung');
    }
} 