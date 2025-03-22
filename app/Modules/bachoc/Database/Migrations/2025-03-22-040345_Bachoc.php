<?php

namespace App\Modules\bachoc\Database\Migrations;

use CodeIgniter\Database\Migration;

class Bachoc extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'bac_hoc_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ten_bac_hoc' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'ma_bac_hoc' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
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
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('bac_hoc_id', true);
        $this->forge->addIndex('ten_bac_hoc');
        $this->forge->addUniqueKey('ten_bac_hoc');
        $this->forge->createTable('bac_hoc');
    }

    public function down()
    {
        $this->forge->dropTable('bac_hoc');
    }
}
