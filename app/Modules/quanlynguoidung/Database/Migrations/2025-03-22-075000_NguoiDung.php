<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class NguoiDung extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'nguoi_dung_id' => [
                'type' => 'INT',
                'constraint' => 8,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'AccountId' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true
            ],
            'u_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'FirstName' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'AccountType' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'FullName' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'MobilePhone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'Email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'HomePhone1' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'PW' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'HomePhone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true
            ],
            'avatar' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'loai_nguoi_dung_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'mat_khau_local' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'nam_hoc_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'bac_hoc_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'he_dao_tao_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'nganh_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'phong_khoa_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'last_login' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true
            ]
        ]);

        // Thêm khóa chính
        $this->forge->addKey('nguoi_dung_id', true);
        
        // Thêm các chỉ mục
        $this->forge->addKey('AccountId', false, false, 'idx_AccountId');
        $this->forge->addKey('FullName', false, false, 'idx_FullName');
        $this->forge->addKey('Email', false, false, 'idx_Email');
        $this->forge->addKey('phong_khoa_id', false, false, 'idx_phong_khoa_id');
        $this->forge->addKey('nganh_id', false, false, 'idx_nganh_id');
        
        // Thêm ràng buộc unique
        $this->forge->addUniqueKey('AccountId', 'uk_AccountId');
        $this->forge->addUniqueKey('Email', 'uk_Email');

        // Tạo bảng
        $this->forge->createTable('nguoi_dung');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('nguoi_dung');
    }
} 