<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Diengia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'dien_gia_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ten_dien_gia' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'chuc_danh' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'to_chuc' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'gioi_thieu' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'avatar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'thu_tu' => [
                'type' => 'INT',
                'default' => 0
            ],
            'bin' => [
                'type' => 'TINYINT',
                'constraint' => 1,
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

        // Add primary key
        $this->forge->addKey('dien_gia_id', true);
        
        // Add index for ten_dien_gia
        $this->forge->addKey('ten_dien_gia', false, false, 'idx_ten_dien_gia');
        
        // Add index for bin
        $this->forge->addKey('bin', false, false, 'idx_bin');
        
        // Create the table
        $this->forge->createTable('dien_gia');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('dien_gia');
    }
} 