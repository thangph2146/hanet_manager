<?php

namespace App\Modules\namhoc\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNamHocTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'nam_hoc_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ten_nam_hoc' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'ngay_bat_dau' => [
                'type'       => 'DATE',
                'null'       => true,
            ],
            'ngay_ket_thuc' => [
                'type'       => 'DATE',
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
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'default'    => null,
            ],
            'updated_at' => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'default'    => null,
                'on update'  => 'CURRENT_TIMESTAMP',
            ],
            'deleted_at' => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'default'    => null,
            ],
        ]);
        
        $this->forge->addKey('nam_hoc_id', true);
        $this->forge->createTable('nam_hoc');
    }

    public function down()
    {
        $this->forge->dropTable('nam_hoc');
    }
} 