<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Template extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'template_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ten_template' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'ma_template' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
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
        $this->forge->addKey('template_id', true);
        
        // Add index for ten_template
        $this->forge->addKey('ten_template', false, false, 'idx_ten_template');
        
        // Add unique constraint for ten_template
        $this->forge->addKey('ten_template', false, true, 'uk_ten_template');

        // Create the table
        $this->forge->createTable('template');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('template');
    }
} 