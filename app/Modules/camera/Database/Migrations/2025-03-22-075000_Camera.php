<?php

namespace App\Modules\camera\Database\Migrations;

use CodeIgniter\Database\Migration;

class Camera extends Migration
{
    public function up()
    {
        // Tạo bảng camera
        $this->forge->addField([
            'camera_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'ten_camera' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'ma_camera' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'ip_camera' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'port' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
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
        $this->forge->addKey('camera_id', true);
        
        // Thêm chỉ mục
        $this->forge->addKey('ten_camera');
        
        // Thêm ràng buộc unique
        $this->forge->addUniqueKey('ten_camera', 'uk_ten_camera');

        // Tạo bảng
        $this->forge->createTable('camera', true, [
            'ENGINE' => 'InnoDB',
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_general_ci'
        ]);
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('camera');
    }
} 