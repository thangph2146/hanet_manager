<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoaiNguoiDungTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'Id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'TypeName' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'Description' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
        ]);
        
        $this->forge->createTable('loai_nguoi_dung');
    }

    public function down()
    {
        $this->forge->dropTable('loai_nguoi_dung');
    }
} 