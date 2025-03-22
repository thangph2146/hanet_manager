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
        $this->forge->addKey('he_dao_tao_id', true);
        
        // Add index for ten_he_dao_tao
        $this->forge->addKey('ten_he_dao_tao', false, false, 'idx_ten_he_dao_tao');
        
        // Add unique constraint for ten_he_dao_tao
        $this->forge->addKey('ten_he_dao_tao', false, true, 'uk_ten_he_dao_tao');

        // Create the table
        $this->forge->createTable('he_dao_tao');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('he_dao_tao');
    }
}
