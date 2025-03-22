<?php

namespace App\Modules\khoahoc\Database\Migrations;

use CodeIgniter\Database\Migration;

class KhoaHoc extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'khoa_hoc_id' => [
                'type'           => 'INT',
                'auto_increment' => true
            ],
            'ten_khoa_hoc' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => false
            ],
            'nam_bat_dau' => [
                'type' => 'INT',
                'null' => false
            ],
            'nam_ket_thuc' => [
                'type' => 'INT',
                'null' => false
            ],
            'phong_khoa_id' => [
                'type' => 'INT',
                'null' => false
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
        
        $this->forge->addKey('khoa_hoc_id', true);
        $this->forge->addKey('ten_khoa_hoc', false, false, 'idx_ten_khoa_hoc');
        $this->forge->createTable('khoa_hoc');
    }

    public function down()
    {
        $this->forge->dropTable('khoa_hoc');
    }
}
