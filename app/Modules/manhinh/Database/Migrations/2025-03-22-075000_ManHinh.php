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
            'ten_man_hinh' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'ma_man_hinh' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'camera_id' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => true
            ],
            'template_id' => [
                'type' => 'INT',
                'null' => true,
                'unsigned' => true
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
        
        // Add foreign keys
        $this->forge->addForeignKey('camera_id', 'camera', 'camera_id', 'CASCADE', 'SET NULL');
        $this->forge->addForeignKey('template_id', 'template', 'template_id', 'CASCADE', 'SET NULL');
        
        // Add indexes
        $this->forge->addKey('ten_man_hinh', false, false, 'idx_ten_man_hinh');
        $this->forge->addKey('ma_man_hinh', false, false, 'idx_ma_man_hinh');
        $this->forge->addKey('status', false, false, 'idx_status');
        $this->forge->addKey('bin', false, false, 'idx_bin');
        
        // Add unique constraints
        $this->forge->addUniqueKey('ten_man_hinh', 'uk_ten_man_hinh');
        $this->forge->addUniqueKey('ma_man_hinh', 'uk_ma_man_hinh');

        // Create the table
        $this->forge->createTable('man_hinh');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('man_hinh');
    }
} 