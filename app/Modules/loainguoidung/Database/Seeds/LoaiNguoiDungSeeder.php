<?php

namespace App\Modules\loainguoidung\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LoaiNguoiDungSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'ten_loai' => 'Quản trị viên',
                'mo_ta' => 'Người dùng có toàn quyền quản trị hệ thống',
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'ten_loai' => 'Nhân viên',
                'mo_ta' => 'Người dùng có quyền hạn trong phạm vi công việc được giao',
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'ten_loai' => 'Khách hàng',
                'mo_ta' => 'Người dùng chỉ có quyền sử dụng dịch vụ cơ bản',
                'status' => 1,
                'bin' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('loai_nguoi_dung')->insertBatch($data);
    }
} 