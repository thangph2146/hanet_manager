<?php

namespace App\Modules\phongkhoa\Database\Migrations;

use CodeIgniter\Database\Migration;

class PhongKhoa extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'phong_khoa_id' => [
                'type'           => 'INT',
                'auto_increment' => true
            ],
            'ma_phong_khoa' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => false
            ],
            'ten_phong_khoa' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false
            ],
            'ghi_chu' => [
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
        
        $this->forge->addKey('phong_khoa_id', true);
        $this->forge->addKey('ma_phong_khoa', false, false, 'idx_ma_phong_khoa');
        $this->forge->addKey('ten_phong_khoa', false, false, 'idx_ten_phong_khoa');
        $this->forge->addUniqueKey('ma_phong_khoa', 'uk_ma_phong_khoa');
        $this->forge->createTable('phong_khoa');
    }

    public function down()
    {
        $this->forge->dropTable('phong_khoa');
    }
} 