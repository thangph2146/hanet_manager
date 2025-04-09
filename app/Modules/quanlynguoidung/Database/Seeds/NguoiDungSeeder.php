<?php

namespace App\Modules\quanlynguoidung\Database\Seeds;

use CodeIgniter\Database\Seeder;

class NguoiDungSeeder extends Seeder
{
    public function run()
    {
        // Kiểm tra nếu đã tồn tại dữ liệu
        $count = $this->db->table('nguoi_dung')->countAllResults();
        if ($count > 0) {
            echo "Bảng nguoi_dung đã có dữ liệu. Bỏ qua việc tạo dữ liệu mẫu.\n";
            return;
        }
        
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
                'avatar' => 'assets/images/avatars/admin.png',
                'loai_nguoi_dung_id' => 1,
                'mat_khau_local' => password_hash('admin123', PASSWORD_DEFAULT),
                'nam_hoc_id' => 1,
                'bac_hoc_id' => 1,
                'he_dao_tao_id' => 1,
                'nganh_id' => 1,
                'phong_khoa_id' => 1,
                'status' => 1,
                'last_login' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
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
                'avatar' => 'assets/images/avatars/user.png',
                'loai_nguoi_dung_id' => 2,
                'mat_khau_local' => password_hash('user123', PASSWORD_DEFAULT),
                'nam_hoc_id' => 1,
                'bac_hoc_id' => 1,
                'he_dao_tao_id' => 1,
                'nganh_id' => 1,
                'phong_khoa_id' => 1,
                'status' => 1,
                'last_login' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
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