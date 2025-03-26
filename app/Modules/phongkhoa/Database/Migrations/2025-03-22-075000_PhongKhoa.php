<?php

namespace App\Modules\phongkhoa\Database\Migrations;

use CodeIgniter\Database\Migration;

class PhongKhoa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'phong_khoa_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ma_phong_khoa' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false
            ],
            'ten_phong_khoa' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false
            ],
            'ghi_chu' => [
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
        $this->forge->addKey('phong_khoa_id', true);
        
        // Add index for ma_phong_khoa
        $this->forge->addKey('ma_phong_khoa', false, false, 'idx_ma_phong_khoa');
        
        // Add index for ten_phong_khoa
        $this->forge->addKey('ten_phong_khoa', false, false, 'idx_ten_phong_khoa');
        
        // Add unique constraint for ma_phong_khoa
        $this->forge->addKey('ma_phong_khoa', false, true, 'uk_ma_phong_khoa');

        // Create the table
        $this->forge->createTable('phong_khoa');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('phong_khoa');
    }
} 