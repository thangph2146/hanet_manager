<?php

namespace App\Modules\loainguoidung\Database\Migrations;

use CodeIgniter\Database\Migration;

class LoaiNguoiDung extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'loai_nguoi_dung_id' => [
                'type'           => 'INT',
                'auto_increment' => true
            ],
            'ten_loai' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false
            ],
            'mo_ta' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1
            ],
            'bin' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')
            ],
            'updated_at' => [
                'type'       => 'TIMESTAMP',
                'null'       => true,
                'on update'  => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')
            ],
            'deleted_at' => [
                'type'       => 'TIMESTAMP',
                'null'       => true
            ],
        ]);
        
        $this->forge->addKey('loai_nguoi_dung_id', true);
        $this->forge->addKey('ten_loai', false, false, 'idx_ten_loai');
        $this->forge->createTable('loai_nguoi_dung');
    }

    public function down()
    {
        $this->forge->dropTable('loai_nguoi_dung');
    }
}
