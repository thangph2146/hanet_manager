<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NguoiDung extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'Id' => [
                'type'           => 'INT',
                'constraint'     => 8,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'AccountId' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'u_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'AccountType' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
            ],
            'FullName' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'MobilePhone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'Email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'HomePhone1' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'PW' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'HomePhone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'loai_nguoi_dung_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'mat_khau_local' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'nam_hoc_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'bac_hoc_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'he_dao_tao_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'nganh_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'phong_khoa_id' => [
                'type'       => 'INT',
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
        $this->forge->addKey('Id', true);
        $this->forge->createTable('nguoi_dung');
    }

    public function down()
    {
        $this->forge->dropTable('nguoi_dung');
    }
}
