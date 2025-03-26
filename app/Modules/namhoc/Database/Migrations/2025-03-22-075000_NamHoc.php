<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NamHoc extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'nam_hoc_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ten_nam_hoc' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => false
            ],
            'ngay_bat_dau' => [
                'type' => 'DATE',
                'null' => true
            ],
            'ngay_ket_thuc' => [
                'type' => 'DATE',
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

        // Thêm khóa chính
        $this->forge->addKey('nam_hoc_id', true);
        
        // Thêm chỉ mục cho ten_nam_hoc
        $this->forge->addKey('ten_nam_hoc', false, false, 'idx_ten_nam_hoc');
        
        // Thêm ràng buộc unique cho ten_nam_hoc
        $this->forge->addKey('ten_nam_hoc', false, true, 'uk_ten_nam_hoc');

        // Tạo bảng
        $this->forge->createTable('nam_hoc');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('nam_hoc');
    }
} 