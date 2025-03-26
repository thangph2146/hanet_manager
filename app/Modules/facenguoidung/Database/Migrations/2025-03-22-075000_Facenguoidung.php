<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FaceNguoiDung extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'face_nguoi_dung_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'nguoi_dung_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => false
            ],
            'duong_dan_anh' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
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
        $this->forge->addKey('face_nguoi_dung_id', true);
        
        // Thêm chỉ mục cho nguoi_dung_id
        $this->forge->addKey('nguoi_dung_id', false, false, 'idx_nguoi_dung_id');

        // Tạo bảng
        $this->forge->createTable('face_nguoi_dung');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('face_nguoi_dung');
    }
} 