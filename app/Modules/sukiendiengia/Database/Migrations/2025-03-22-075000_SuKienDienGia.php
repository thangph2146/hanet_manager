<?php

namespace App\Modules\sukiendiengia\Database\Migrations;

use CodeIgniter\Database\Migration;

class SuKienDienGia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'su_kien_dien_gia_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'su_kien_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'dien_gia_id' => [
                'type' => 'INT',
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

        // Thêm khóa chính kết hợp
        $this->forge->addKey(['su_kien_id', 'dien_gia_id'], true);
        
        // Thêm các chỉ mục
        $this->forge->addKey('su_kien_id', false, false, 'idx_su_kien_id');
        $this->forge->addKey('dien_gia_id', false, false, 'idx_dien_gia_id');

        // Tạo bảng
        $this->forge->createTable('su_kien_dien_gia');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('su_kien_dien_gia');
    }
} 