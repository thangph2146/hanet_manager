<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogSystemTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'Id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'noi_dung_thuc_hien' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'sql_thuc_hien' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'action' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'thoi_gian_log' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
            ],
            'bin' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->createTable('log_system');
    }

    public function down()
    {
        $this->forge->dropTable('log_system');
    }
} 