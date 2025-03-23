<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Nganh extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'nganh_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ma_nganh' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false
            ],
            'ten_nganh' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
                'null' => false
            ],
            'phong_khoa_id' => [
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
        $this->forge->addKey('nganh_id', true);
        
        // Add index for ma_nganh
        $this->forge->addKey('ma_nganh', false, false, 'idx_ma_nganh');
        
        // Add index for ten_nganh
        $this->forge->addKey('ten_nganh', false, false, 'idx_ten_nganh');
        
        // Add index for phong_khoa_id
        $this->forge->addKey('phong_khoa_id', false, false, 'idx_phong_khoa_id');
        
        // Add unique constraint for ma_nganh
        $this->forge->addKey('ma_nganh', false, true, 'uk_ma_nganh');

        // Create the table
        $this->forge->createTable('nganh');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('nganh');
    }
} 