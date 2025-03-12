<?php

namespace App\Modules\nguoidung\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class NguoiDungSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('vi_VN');
        
        // Thêm dữ liệu mẫu cho admin
        $this->db->table('nguoi_dung')->insert([
            'AccountId' => 'admin',
            'u_id' => 1,
            'FirstName' => 'Admin',
            'AccountType' => 'admin',
            'FullName' => 'Administrator',
            'MobilePhone' => $faker->phoneNumber,
            'Email' => 'admin@example.com',
            'HomePhone1' => $faker->phoneNumber,
            'PW' => password_hash('admin123', PASSWORD_DEFAULT),
            'HomePhone' => $faker->phoneNumber,
            'loai_nguoi_dung_id' => 1,
            'mat_khau_local' => password_hash('admin123', PASSWORD_DEFAULT),
            'nam_hoc_id' => 1,
            'bac_hoc_id' => 1,
            'he_dao_tao_id' => 1,
            'nganh_id' => 1,
            'phong_khoa_id' => 1,
            'status' => 1,
            'bin' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        // Thêm dữ liệu mẫu cho giảng viên
        $this->db->table('nguoi_dung')->insert([
            'AccountId' => 'gv001',
            'u_id' => 2,
            'FirstName' => 'Nguyễn',
            'AccountType' => 'giangvien',
            'FullName' => 'Nguyễn Văn A',
            'MobilePhone' => $faker->phoneNumber,
            'Email' => 'nguyenvana@example.com',
            'HomePhone1' => $faker->phoneNumber,
            'PW' => password_hash('gv123', PASSWORD_DEFAULT),
            'HomePhone' => $faker->phoneNumber,
            'loai_nguoi_dung_id' => 2,
            'mat_khau_local' => password_hash('gv123', PASSWORD_DEFAULT),
            'nam_hoc_id' => 1,
            'bac_hoc_id' => 1,
            'he_dao_tao_id' => 1,
            'nganh_id' => 1,
            'phong_khoa_id' => 2,
            'status' => 1,
            'bin' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        // Thêm dữ liệu mẫu cho sinh viên
        $this->db->table('nguoi_dung')->insert([
            'AccountId' => 'sv001',
            'u_id' => 3,
            'FirstName' => 'Trần',
            'AccountType' => 'sinhvien',
            'FullName' => 'Trần Thị B',
            'MobilePhone' => $faker->phoneNumber,
            'Email' => 'tranthib@example.com',
            'HomePhone1' => $faker->phoneNumber,
            'PW' => password_hash('sv123', PASSWORD_DEFAULT),
            'HomePhone' => $faker->phoneNumber,
            'loai_nguoi_dung_id' => 3,
            'mat_khau_local' => password_hash('sv123', PASSWORD_DEFAULT),
            'nam_hoc_id' => 1,
            'bac_hoc_id' => 1,
            'he_dao_tao_id' => 1,
            'nganh_id' => 2,
            'phong_khoa_id' => 3,
            'status' => 1,
            'bin' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        // Thêm dữ liệu mẫu cho nhân viên
        $this->db->table('nguoi_dung')->insert([
            'AccountId' => 'nv001',
            'u_id' => 4,
            'FirstName' => 'Lê',
            'AccountType' => 'nhanvien',
            'FullName' => 'Lê Văn C',
            'MobilePhone' => $faker->phoneNumber,
            'Email' => 'levanc@example.com',
            'HomePhone1' => $faker->phoneNumber,
            'PW' => password_hash('nv123', PASSWORD_DEFAULT),
            'HomePhone' => $faker->phoneNumber,
            'loai_nguoi_dung_id' => 4,
            'mat_khau_local' => password_hash('nv123', PASSWORD_DEFAULT),
            'nam_hoc_id' => 1,
            'bac_hoc_id' => 1,
            'he_dao_tao_id' => 1,
            'nganh_id' => 1,
            'phong_khoa_id' => 4,
            'status' => 1,
            'bin' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        
        // Thêm dữ liệu mẫu cho người dùng đã bị xóa (bin = 1)
        $this->db->table('nguoi_dung')->insert([
            'AccountId' => 'deleted001',
            'u_id' => 5,
            'FirstName' => 'Phạm',
            'AccountType' => 'sinhvien',
            'FullName' => 'Phạm Văn D',
            'MobilePhone' => $faker->phoneNumber,
            'Email' => 'phamvand@example.com',
            'HomePhone1' => $faker->phoneNumber,
            'PW' => password_hash('deleted123', PASSWORD_DEFAULT),
            'HomePhone' => $faker->phoneNumber,
            'loai_nguoi_dung_id' => 3,
            'mat_khau_local' => password_hash('deleted123', PASSWORD_DEFAULT),
            'nam_hoc_id' => 1,
            'bac_hoc_id' => 1,
            'he_dao_tao_id' => 1,
            'nganh_id' => 3,
            'phong_khoa_id' => 3,
            'status' => 0,
            'bin' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => date('Y-m-d H:i:s'),
        ]);
    }
} 