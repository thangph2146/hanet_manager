<?php

namespace App\Modules\facenguoidung\Database\Migrations;

use CodeIgniter\Database\Migration;

class FaceNguoiDung extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'face_nguoi_dung_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nguoi_dung_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'duong_dan_anh' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'ngay_cap_nhat' => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'default'    => null,
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
        
        $this->forge->addKey('face_nguoi_dung_id', true);
        $this->forge->addKey('nguoi_dung_id', false, false, 'idx_nguoi_dung_id');
        $this->forge->createTable('face_nguoi_dung');
    }

    public function down()
    {
        $this->forge->dropTable('face_nguoi_dung');
    }
} 