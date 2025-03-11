<?php

namespace App\Modules\nguoidung\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNguoiDungTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 8,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'AccountId' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'u_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'FirstName' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'AccountType' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'FullName' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'MobilePhone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'Email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'HomePhone1' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'PW' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'HomePhone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],
            'loai_nguoi_dung_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'mat_khau_local' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
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
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('nguoi_dung');
    }

    public function down()
    {
        $this->forge->dropTable('nguoi_dung');
    }
} 