<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ThamGiaSuKien extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'tham_gia_su_kien_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'nguoi_dung_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'su_kien_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'thoi_gian_diem_danh' => [
                'type' => 'TIMESTAMP',
                'null' => true
            ],
            'phuong_thuc_diem_danh' => [
                'type' => 'ENUM',
                'constraint' => ['qr_code', 'face_id', 'manual'],
                'default' => 'manual',
                'null' => false
            ],
            'ghi_chu' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'bin' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
                'null' => false
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
                'on update' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP')
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        // Add primary key
        $this->forge->addKey('tham_gia_su_kien_id', true);
        
        // Add indexes
        $this->forge->addKey('nguoi_dung_id', false, false, 'idx_nguoi_dung_id');
        $this->forge->addKey('su_kien_id', false, false, 'idx_su_kien_id');
        $this->forge->addKey('thoi_gian_diem_danh', false, false, 'idx_thoi_gian_diem_danh');
        $this->forge->addKey(['status', 'nguoi_dung_id', 'su_kien_id'], false, false, 'idx_status_nguoidung_sukien');
        
        // Add unique constraint
        $this->forge->addKey(['nguoi_dung_id', 'su_kien_id'], false, true, 'uk_nguoi_dung_event');

        // Create the table
        $this->forge->createTable('tham_gia_su_kien');
    }

    public function down()
    {
        // Drop the table
        $this->forge->dropTable('tham_gia_su_kien');
    }
} 