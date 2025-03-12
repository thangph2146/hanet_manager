<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNguoiDung extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'Id' => [
                'type'           => 'INT',
                'constraint'     => 8,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'AccountId' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => false,
            ],
            'u_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'AccountType' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => false,
            ],
            'FullName' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'MobilePhone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'Email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'HomePhone1' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'PW' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'HomePhone' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'loai_nguoi_dung_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'mat_khau_local' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
            ],
            'nam_hoc_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'bac_hoc_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'he_dao_tao_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'nganh_id' => [
                'type'       => 'INT',
                'null'       => true,
            ],
            'phong_khoa_id' => [
                'type'       => 'INT',
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
        $this->forge->addKey('Id', true);
        $this->forge->createTable('nguoi_dung');

        // Insert mock data
        $data = [
            [
                'AccountId' => 'ACC001',
                'u_id' => 1,
                'AccountType' => 'Admin',
                'FullName' => 'Nguyen Van A',
                'MobilePhone' => '0123456789',
                'Email' => 'nguyenvana@example.com',
                'HomePhone1' => '0123456789',
                'PW' => password_hash('password123', PASSWORD_DEFAULT),
                'HomePhone' => '0123456789',
                'loai_nguoi_dung_id' => 1,
                'mat_khau_local' => password_hash('localpassword', PASSWORD_DEFAULT),
                'nam_hoc_id' => 1,
                'bac_hoc_id' => 1,
                'he_dao_tao_id' => 1,
                'nganh_id' => 1,
                'phong_khoa_id' => 1,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'AccountId' => 'ACC002',
                'u_id' => 2,
                'AccountType' => 'User',
                'FullName' => 'Tran Thi B',
                'MobilePhone' => '0987654321',
                'Email' => 'tranthib@example.com',
                'HomePhone1' => '0987654321',
                'PW' => password_hash('password123', PASSWORD_DEFAULT),
                'HomePhone' => '0987654321',
                'loai_nguoi_dung_id' => 2,
                'mat_khau_local' => password_hash('localpassword', PASSWORD_DEFAULT),
                'nam_hoc_id' => 2,
                'bac_hoc_id' => 2,
                'he_dao_tao_id' => 2,
                'nganh_id' => 2,
                'phong_khoa_id' => 2,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'AccountId' => 'ACC003',
                'u_id' => 3,
                'AccountType' => 'User',
                'FullName' => 'Le Van C',
                'MobilePhone' => '0912345678',
                'Email' => 'levanc@example.com',
                'HomePhone1' => '0912345678',
                'PW' => password_hash('password123', PASSWORD_DEFAULT),
                'HomePhone' => '0912345678',
                'loai_nguoi_dung_id' => 3,
                'mat_khau_local' => password_hash('localpassword', PASSWORD_DEFAULT),
                'nam_hoc_id' => 3,
                'bac_hoc_id' => 3,
                'he_dao_tao_id' => 3,
                'nganh_id' => 3,
                'phong_khoa_id' => 3,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'AccountId' => 'ACC004',
                'u_id' => 4,
                'AccountType' => 'User',
                'FullName' => 'Pham Thi D',
                'MobilePhone' => '0934567890',
                'Email' => 'phamthid@example.com',
                'HomePhone1' => '0934567890',
                'PW' => password_hash('password123', PASSWORD_DEFAULT),
                'HomePhone' => '0934567890',
                'loai_nguoi_dung_id' => 4,
                'mat_khau_local' => password_hash('localpassword', PASSWORD_DEFAULT),
                'nam_hoc_id' => 4,
                'bac_hoc_id' => 4,
                'he_dao_tao_id' => 4,
                'nganh_id' => 4,
                'phong_khoa_id' => 4,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'AccountId' => 'ACC005',
                'u_id' => 5,
                'AccountType' => 'User',
                'FullName' => 'Hoang Van E',
                'MobilePhone' => '0945678901',
                'Email' => 'hoangvane@example.com',
                'HomePhone1' => '0945678901',
                'PW' => password_hash('password123', PASSWORD_DEFAULT),
                'HomePhone' => '0945678901',
                'loai_nguoi_dung_id' => 5,
                'mat_khau_local' => password_hash('localpassword', PASSWORD_DEFAULT),
                'nam_hoc_id' => 5,
                'bac_hoc_id' => 5,
                'he_dao_tao_id' => 5,
                'nganh_id' => 5,
                'phong_khoa_id' => 5,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'AccountId' => 'ACC006',
                'u_id' => 6,
                'AccountType' => 'User',
                'FullName' => 'Nguyen Thi F',
                'MobilePhone' => '0956789012',
                'Email' => 'nguyenthif@example.com',
                'HomePhone1' => '0956789012',
                'PW' => password_hash('password123', PASSWORD_DEFAULT),
                'HomePhone' => '0956789012',
                'loai_nguoi_dung_id' => 6,
                'mat_khau_local' => password_hash('localpassword', PASSWORD_DEFAULT),
                'nam_hoc_id' => 6,
                'bac_hoc_id' => 6,
                'he_dao_tao_id' => 6,
                'nganh_id' => 6,
                'phong_khoa_id' => 6,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'AccountId' => 'ACC007',
                'u_id' => 7,
                'AccountType' => 'User',
                'FullName' => 'Tran Van G',
                'MobilePhone' => '0967890123',
                'Email' => 'tranvang@example.com',
                'HomePhone1' => '0967890123',
                'PW' => password_hash('password123', PASSWORD_DEFAULT),
                'HomePhone' => '0967890123',
                'loai_nguoi_dung_id' => 7,
                'mat_khau_local' => password_hash('localpassword', PASSWORD_DEFAULT),
                'nam_hoc_id' => 7,
                'bac_hoc_id' => 7,
                'he_dao_tao_id' => 7,
                'nganh_id' => 7,
                'phong_khoa_id' => 7,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'AccountId' => 'ACC008',
                'u_id' => 8,
                'AccountType' => 'User',
                'FullName' => 'Le Thi H',
                'MobilePhone' => '0978901234',
                'Email' => 'lethih@example.com',
                'HomePhone1' => '0978901234',
                'PW' => password_hash('password123', PASSWORD_DEFAULT),
                'HomePhone' => '0978901234',
                'loai_nguoi_dung_id' => 8,
                'mat_khau_local' => password_hash('localpassword', PASSWORD_DEFAULT),
                'nam_hoc_id' => 8,
                'bac_hoc_id' => 8,
                'he_dao_tao_id' => 8,
                'nganh_id' => 8,
                'phong_khoa_id' => 8,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'AccountId' => 'ACC009',
                'u_id' => 9,
                'AccountType' => 'User',
                'FullName' => 'Pham Van I',
                'MobilePhone' => '0989012345',
                'Email' => 'phamvani@example.com',
                'HomePhone1' => '0989012345',
                'PW' => password_hash('password123', PASSWORD_DEFAULT),
                'HomePhone' => '0989012345',
                'loai_nguoi_dung_id' => 9,
                'mat_khau_local' => password_hash('localpassword', PASSWORD_DEFAULT),
                'nam_hoc_id' => 9,
                'bac_hoc_id' => 9,
                'he_dao_tao_id' => 9,
                'nganh_id' => 9,
                'phong_khoa_id' => 9,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
            [
                'AccountId' => 'ACC010',
                'u_id' => 10,
                'AccountType' => 'User',
                'FullName' => 'Hoang Thi J',
                'MobilePhone' => '0990123456',
                'Email' => 'hoangthij@example.com',
                'HomePhone1' => '0990123456',
                'PW' => password_hash('password123', PASSWORD_DEFAULT),
                'HomePhone' => '0990123456',
                'loai_nguoi_dung_id' => 10,
                'mat_khau_local' => password_hash('localpassword', PASSWORD_DEFAULT),
                'nam_hoc_id' => 10,
                'bac_hoc_id' => 10,
                'he_dao_tao_id' => 10,
                'nganh_id' => 10,
                'phong_khoa_id' => 10,
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'deleted_at' => null,
            ],
        ];

        $this->db->table('nguoi_dung')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('nguoi_dung');
    }
}
