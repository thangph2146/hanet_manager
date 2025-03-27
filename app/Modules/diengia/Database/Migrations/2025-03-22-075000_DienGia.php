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
            'thu_tu' => [
                'type' => 'INT',
                'default' => 0
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
        
        // Thêm chỉ mục cho ten_dien_gia
        $this->forge->addKey('ten_dien_gia', false, false, 'idx_ten_dien_gia');
        
        // Tạo bảng
        $this->forge->createTable('dien_gia');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('dien_gia');
    }
} 