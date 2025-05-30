<?php

namespace App\Modules\quanlycamera\Database\Migrations;

use CodeIgniter\Database\Migration;

class Camera extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'camera_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
                'null' => false
            ],
            'ten_camera' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'ma_camera' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'vi_tri' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
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
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        // Thêm khóa chính
        $this->forge->addPrimaryKey('camera_id');
        
        // Thêm chỉ mục cho ten_camera
        $this->forge->addKey('ten_camera');
        
        // Thêm unique key cho ma_camera
        $this->forge->addUniqueKey('ma_camera');

        // Tạo bảng
        $this->forge->createTable('camera');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('camera');
    }
}