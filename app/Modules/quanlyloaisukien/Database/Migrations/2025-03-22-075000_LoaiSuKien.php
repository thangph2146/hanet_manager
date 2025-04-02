<?php

namespace App\Modules\quanlyloaisukien\Database\Migrations;

use CodeIgniter\Database\Migration;

class LoaiSuKien extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'loai_su_kien_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ten_loai_su_kien' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'ma_loai_su_kien' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
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
        $this->forge->addKey('loai_su_kien_id', true);
        
        // Thêm chỉ mục cho ten_loai_su_kien
        $this->forge->addKey('ten_loai_su_kien', false, false, 'idx_ten_loai_su_kien');
        
        // Thêm unique key cho ten_loai_su_kien
        $this->forge->addUniqueKey('ten_loai_su_kien', 'uk_ten_loai_su_kien');

        // Tạo bảng
        $this->forge->createTable('loai_su_kien');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('loai_su_kien');
    }
} 