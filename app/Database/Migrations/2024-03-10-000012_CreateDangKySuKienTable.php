<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDangKySuKienTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'Id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'su_kien_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'nguoi_dung_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'ngay_dang_ky' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'noi_dung_gop_y' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'nguon_gioi_thieu' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
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
        ]);
        
        $this->forge->createTable('dang_ky_su_kien');
    }

    public function down()
    {
        $this->forge->dropTable('dang_ky_su_kien');
    }
} 