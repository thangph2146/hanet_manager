<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Camera extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'camera_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ma_camera' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'ten_camera' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'ip_camera' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'port' => [
                'type' => 'INT',
                'constraint' => 5,
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
        $this->forge->addKey('camera_id', true);
        
        // Add index for ma_camera
        $this->forge->addKey('ma_camera', false, false, 'idx_ma_camera');
        
        // Add index for ten_camera
        $this->forge->addKey('ten_camera', false, false, 'idx_ten_camera');
        
        // Add index for ip_camera
        $this->forge->addKey('ip_camera', false, false, 'idx_ip_camera');
        
        // Add index for status
        $this->forge->addKey('status', false, false, 'idx_status');
        
        // Add index for bin
        $this->forge->addKey('bin', false, false, 'idx_bin');
        
        // Add unique constraint for ten_camera
        $this->forge->addKey('ten_camera', false, true, 'uk_ten_camera');

        // Create the table
        $this->forge->createTable('camera');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('camera');
    }
} 