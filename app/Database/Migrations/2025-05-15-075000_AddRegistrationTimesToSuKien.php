<?php

namespace App\Modules\quanlysukien\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRegistrationTimesToSuKien extends Migration
{
    public function up()
    {
        // Kiểm tra nếu các cột đã tồn tại
        $fields = [];
        
        if (!$this->db->fieldExists('thoi_gian_bat_dau_dang_ky', 'su_kien')) {
            $fields['thoi_gian_bat_dau_dang_ky'] = [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Thời gian bắt đầu đăng ký tham gia sự kiện',
                'after' => 'cho_phep_checkin_thu_cong'
            ];
        }

        if (!$this->db->fieldExists('thoi_gian_ket_thuc_dang_ky', 'su_kien')) {
            $fields['thoi_gian_ket_thuc_dang_ky'] = [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Thời gian kết thúc đăng ký tham gia sự kiện',
                'after' => 'thoi_gian_bat_dau_dang_ky'
            ];
        }

        // Nếu có trường cần thêm thì thực hiện
        if (!empty($fields)) {
            $this->forge->addColumn('su_kien', $fields);
        }
        
        // Ghi log
        log_message('info', 'Migration AddRegistrationTimesToSuKien: Đã thêm các trường thời gian đăng ký vào bảng su_kien.');
    }

    public function down()
    {
        // Xóa các cột (nếu tồn tại)
        if ($this->db->fieldExists('thoi_gian_bat_dau_dang_ky', 'su_kien')) {
            $this->forge->dropColumn('su_kien', 'thoi_gian_bat_dau_dang_ky');
        }
        
        if ($this->db->fieldExists('thoi_gian_ket_thuc_dang_ky', 'su_kien')) {
            $this->forge->dropColumn('su_kien', 'thoi_gian_ket_thuc_dang_ky');
        }
        
        // Ghi log
        log_message('info', 'Migration AddRegistrationTimesToSuKien: Đã xóa các trường thời gian đăng ký khỏi bảng su_kien.');
    }
} 