<?php

namespace App\Modules\quanlysukien\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveRegistrationTimesFromSuKien extends Migration
{
    public function up()
    {
        // Kiểm tra nếu các cột tồn tại trước khi xóa
        if ($this->db->fieldExists('thoi_gian_bat_dau_dang_ky', 'su_kien')) {
            $this->forge->dropColumn('su_kien', 'thoi_gian_bat_dau_dang_ky');
        }
        
        if ($this->db->fieldExists('thoi_gian_ket_thuc_dang_ky', 'su_kien')) {
            $this->forge->dropColumn('su_kien', 'thoi_gian_ket_thuc_dang_ky');
        }
        
        // Ghi log
        log_message('info', 'Migration RemoveRegistrationTimesFromSuKien: Đã xóa các trường thoi_gian_bat_dau_dang_ky và thoi_gian_ket_thuc_dang_ky khỏi bảng su_kien.');
    }

    public function down()
    {
        // Nếu cần rollback, sẽ thêm lại các cột này
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
        log_message('info', 'Migration RemoveRegistrationTimesFromSuKien (rollback): Đã thêm lại các trường thoi_gian_bat_dau_dang_ky và thoi_gian_ket_thuc_dang_ky vào bảng su_kien.');
    }
} 