<?php

namespace App\Modules\bachoc\Database\Migrations;

use CodeIgniter\Database\Migration;

class Bachoc extends Migration
{
    public function up()
    {
        // Kiểm tra xem bảng đã tồn tại chưa
        if ($this->db->tableExists('bac_hoc')) {
            // Nếu bảng đã tồn tại, xóa nó đi để tạo lại
            $this->forge->dropTable('bac_hoc', true);
        }
        
        // Tạo lại cấu trúc bảng
        $this->forge->addField([
            'bac_hoc_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'ten_bac_hoc' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'ma_bac_hoc' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
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
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('bac_hoc_id', true);
        $this->forge->addUniqueKey('ten_bac_hoc');
        
        // Tạo bảng với IF NOT EXISTS để tránh lỗi
        $this->forge->createTable('bac_hoc', true);
    }

    public function down()
    {
        $this->forge->dropTable('bac_hoc', true);
    }
}
