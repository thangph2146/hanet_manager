<?php

namespace App\Modules\nguoidung\Database\Seeds;

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class NguoiDungSeeder extends Seeder
{
    public function run()
    {
        // Dữ liệu mẫu cho người dùng
        $data = [
            [
                'AccountId' => 'admin',
                'u_id' => 1,
                'FirstName' => 'Admin',
                'AccountType' => 'admin',
                'FullName' => 'Administrator',
                'MobilePhone' => '0123456789',
                'Email' => 'admin@example.com',
                'HomePhone1' => '0123456789',
                'PW' => password_hash('admin123', PASSWORD_DEFAULT),
                'HomePhone' => '0123456789',
                'loai_nguoi_dung_id' => 1,
                'mat_khau_local' => password_hash('admin123', PASSWORD_DEFAULT),
                'nam_hoc_id' => 1,
                'bac_hoc_id' => 1,
                'he_dao_tao_id' => 1,
                'nganh_id' => 1,
                'phong_khoa_id' => 1,
                'status' => 1,
                'last_login' => Time::now()->toDateTimeString(),
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ],
            [
                'AccountId' => 'user1',
                'u_id' => 2,
                'FirstName' => 'User',
                'AccountType' => 'user',
                'FullName' => 'Test User',
                'MobilePhone' => '0987654321',
                'Email' => 'user1@example.com',
                'HomePhone1' => '0987654321',
                'PW' => password_hash('user123', PASSWORD_DEFAULT),
                'HomePhone' => '0987654321',
                'loai_nguoi_dung_id' => 2,
                'mat_khau_local' => password_hash('user123', PASSWORD_DEFAULT),
                'nam_hoc_id' => 1,
                'bac_hoc_id' => 1,
                'he_dao_tao_id' => 1,
                'nganh_id' => 1,
                'phong_khoa_id' => 1,
                'status' => 1,
                'last_login' => Time::now()->toDateTimeString(),
                'created_at' => Time::now()->toDateTimeString(),
                'updated_at' => Time::now()->toDateTimeString(),
                'deleted_at' => null
            ]
        ];
        
        // Thêm dữ liệu vào bảng nguoi_dung
        if (!empty($data)) {
            $this->db->table('nguoi_dung')->insertBatch($data);
            echo "Đã tạo " . count($data) . " bản ghi người dùng.\n";
        }
        
        echo "Seeder NguoiDungSeeder đã được chạy thành công! Đã tạo dữ liệu mẫu cho người dùng.\n";
    }
} 