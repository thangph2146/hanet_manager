<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class HeDaoTao extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'he_dao_tao_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ten_he_dao_tao' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'ma_he_dao_tao' => [
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
        $this->forge->addKey('he_dao_tao_id', true);
        
        // Thêm chỉ mục cho ten_he_dao_tao
        $this->forge->addKey('ten_he_dao_tao', false, false, 'idx_ten_he_dao_tao');
        
        // Thêm unique key cho ten_he_dao_tao
        $this->forge->addUniqueKey('ten_he_dao_tao', 'uk_ten_he_dao_tao');

        // Tạo bảng
        $this->forge->createTable('he_dao_tao');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('he_dao_tao');
    }
} 