<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSuKienTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'Id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ten_su_kien' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'loai_su_kien_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'chi_tiet_su_kien' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'bat_dau_dang_ky' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'ket_thuc_dang_ky' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'so_luong_tham_gia' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'null'       => true,
            ],
            'url_su_kien' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'tu_khoa_su_kien' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'mo_ta_su_kien' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'hashtag' => [
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
        
        $this->forge->createTable('su_kien');
    }

    public function down()
    {
        $this->forge->dropTable('su_kien');
    }
} 