<?php

namespace App\Modules\quanlycheckinsukien\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CheckInSuKienSeeder extends Seeder
{
    public function run()
    {
        // Check if table exists before seeding
        if (!$this->db->tableExists('checkin_sukien')) {
            echo "Table checkin_sukien does not exist. Run migrations first.\n";
            return;
        }

        // Sample data for checkin_sukien
        $data = [
            [
                'su_kien_id' => 1,
                'email' => 'user1@example.com',
                'ho_ten' => 'Nguyen Van A',
                'dangky_sukien_id' => 1,
                'thoi_gian_check_in' => date('Y-m-d H:i:s'),
                'checkin_type' => 'qr_code',
                'face_verified' => false,
                'ma_xac_nhan' => 'ABC123',
                'status' => 1,
                'hinh_thuc_tham_gia' => 'offline',
                'ip_address' => '127.0.0.1',
                'device_info' => 'Web Browser',
                'ghi_chu' => 'Demo data'
            ],
            [
                'su_kien_id' => 1,
                'email' => 'user2@example.com',
                'ho_ten' => 'Tran Thi B',
                'dangky_sukien_id' => 2,
                'thoi_gian_check_in' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'checkin_type' => 'manual',
                'face_verified' => false,
                'ma_xac_nhan' => 'DEF456',
                'status' => 1,
                'hinh_thuc_tham_gia' => 'online',
                'ip_address' => '192.168.1.1',
                'device_info' => 'Mobile App',
                'ghi_chu' => 'Demo data'
            ],
            [
                'su_kien_id' => 2,
                'email' => 'user3@example.com',
                'ho_ten' => 'Le Van C',
                'dangky_sukien_id' => 3,
                'thoi_gian_check_in' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'checkin_type' => 'face_id',
                'face_verified' => true,
                'face_match_score' => 0.85,
                'face_image_path' => '/uploads/faces/user3.jpg',
                'ma_xac_nhan' => 'GHI789',
                'status' => 1,
                'hinh_thuc_tham_gia' => 'offline',
                'ip_address' => '192.168.1.2',
                'device_info' => 'Kiosk',
                'ghi_chu' => 'Demo data'
            ],
        ];

        // Check if su_kien table exists before inserting data with foreign keys
        $su_kien_exists = $this->db->tableExists('su_kien');
        $dangky_sukien_exists = $this->db->tableExists('dangky_sukien');
        
        // Only seed if the referenced tables exist, to avoid foreign key constraint errors
        if ($su_kien_exists && $dangky_sukien_exists) {
            // Check if we have at least one su_kien record and one dangky_sukien record
            $su_kien_count = $this->db->table('su_kien')->countAllResults();
            $dangky_sukien_count = $this->db->table('dangky_sukien')->countAllResults();
            
            if ($su_kien_count > 0 && $dangky_sukien_count > 0) {
                // Insert the sample data
                foreach ($data as $record) {
                    // Check if the record already exists to avoid duplicates
                    $exists = $this->db->table('checkin_sukien')
                                      ->where('email', $record['email'])
                                      ->where('su_kien_id', $record['su_kien_id'])
                                      ->where('thoi_gian_check_in', $record['thoi_gian_check_in'])
                                      ->countAllResults();
                    
                    if ($exists === 0) {
                        $this->db->table('checkin_sukien')->insert($record);
                    }
                }
                
                echo "CheckInSuKien seeder: Sample data inserted successfully.\n";
            } else {
                echo "CheckInSuKien seeder: No records found in su_kien or dangky_sukien tables. Skipping seeding.\n";
            }
        } else {
            echo "CheckInSuKien seeder: Required tables (su_kien or dangky_sukien) do not exist. Skipping seeding.\n";
        }
    }
}