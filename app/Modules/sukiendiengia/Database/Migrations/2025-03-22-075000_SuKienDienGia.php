<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SuKienDienGia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'su_kien_id' => [
                'type' => 'INT',
                'null' => false
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
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        // Add primary key
        $this->forge->addPrimaryKey(['su_kien_id', 'dien_gia_id']);
        
        // Add indexes
        $this->forge->addKey('dien_gia_id', false, false, 'idx_dien_gia_id');
        $this->forge->addKey('su_kien_id', false, false, 'idx_su_kien_id');
        
        // Create the table
        $this->forge->createTable('su_kien_dien_gia');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('su_kien_dien_gia');
    }
} 