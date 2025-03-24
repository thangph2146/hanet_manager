<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ManHinh extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'man_hinh_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ma_man_hinh' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'ten_man_hinh' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'camera_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'temlate_id' => [
                'type' => 'INT',
                'null' => true
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'bin' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'on update' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ]
        ]);

        // Add primary key
        $this->forge->addKey('man_hinh_id', true);
        
        // Add index for ma_man_hinh
        $this->forge->addKey('ma_man_hinh', false, false, 'idx_ma_man_hinh');
        
        // Add index for ten_man_hinh
        $this->forge->addKey('ten_man_hinh', false, false, 'idx_ten_man_hinh');
        
        // Add index for camera_id
        $this->forge->addKey('camera_id', false, false, 'idx_camera_id');
        
        // Add index for temlate_id
        $this->forge->addKey('temlate_id', false, false, 'idx_temlate_id');
        
        // Add unique constraint for ten_man_hinh
        $this->forge->addKey('ten_man_hinh', false, true, 'uk_ten_man_hinh');

        // Create the table
        $this->forge->createTable('man_hinh');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('man_hinh');
    }
} 