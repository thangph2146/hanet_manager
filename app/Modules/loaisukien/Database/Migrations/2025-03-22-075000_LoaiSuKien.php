<?php

namespace App\Modules\loaisukien\Database\Migrations;

use CodeIgniter\Database\Migration;

class LoaiSuKien extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'loai_su_kien_id' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'ten_loai_su_kien' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => false,
            ],
            'ma_loai_su_kien' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
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
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'      => 'TIMESTAMP',
                'null'      => true,
                'on update' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'deleted_at' => [
                'type'    => 'TIMESTAMP',
                'null'    => true,
                'default' => null,
            ],
        ]);
        
        $this->forge->addKey('loai_su_kien_id', true);
        $this->forge->addKey('ten_loai_su_kien', false, false, 'idx_ten_loai_su_kien');
        $this->forge->addKey('ten_loai_su_kien', false, true, 'uk_ten_loai_su_kien');
        $this->forge->createTable('loai_su_kien');
    }

    public function down()
    {
        $this->forge->dropTable('loai_su_kien');
    }
} 