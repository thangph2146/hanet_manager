<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLogNguoiDungTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'Id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ngay_tao' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'ngay_dang_nhap_gan_nhat' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'ip_dang_nhap' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
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
        
        $this->forge->createTable('log_nguoi_dung');
    }

    public function down()
    {
        $this->forge->dropTable('log_nguoi_dung');
    }
} 