<?php

namespace App\Modules\quanlysukien\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveRegistrationTimesFromSuKien extends Migration
{
    public function up()
    {
        // Kiểm tra xem cột thoi_gian_bat_dau_dang_ky có tồn tại không
        if ($this->db->fieldExists('thoi_gian_bat_dau_dang_ky', 'su_kien')) {
            // Nếu tồn tại thì xóa cột
            $this->forge->dropColumn('su_kien', 'thoi_gian_bat_dau_dang_ky');
            log_message('info', 'Đã xóa cột thoi_gian_bat_dau_dang_ky khỏi bảng su_kien');
        } else {
            log_message('info', 'Cột thoi_gian_bat_dau_dang_ky không tồn tại trong bảng su_kien');
        }

        // Kiểm tra xem cột thoi_gian_ket_thuc_dang_ky có tồn tại không
        if ($this->db->fieldExists('thoi_gian_ket_thuc_dang_ky', 'su_kien')) {
            // Nếu tồn tại thì xóa cột
            $this->forge->dropColumn('su_kien', 'thoi_gian_ket_thuc_dang_ky');
            log_message('info', 'Đã xóa cột thoi_gian_ket_thuc_dang_ky khỏi bảng su_kien');
        } else {
            log_message('info', 'Cột thoi_gian_ket_thuc_dang_ky không tồn tại trong bảng su_kien');
        }
    }

    public function down()
    {
        // Kiểm tra xem cột thoi_gian_bat_dau_dang_ky đã tồn tại chưa
        if (!$this->db->fieldExists('thoi_gian_bat_dau_dang_ky', 'su_kien')) {
            // Nếu chưa tồn tại thì thêm cột
            $this->forge->addColumn('su_kien', [
                'thoi_gian_bat_dau_dang_ky' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'comment' => 'Thời gian bắt đầu đăng ký sự kiện'
                ]
            ]);
            log_message('info', 'Đã thêm cột thoi_gian_bat_dau_dang_ky vào bảng su_kien');
        } else {
            log_message('info', 'Cột thoi_gian_bat_dau_dang_ky đã tồn tại trong bảng su_kien');
        }

        // Kiểm tra xem cột thoi_gian_ket_thuc_dang_ky đã tồn tại chưa
        if (!$this->db->fieldExists('thoi_gian_ket_thuc_dang_ky', 'su_kien')) {
            // Nếu chưa tồn tại thì thêm cột
            $this->forge->addColumn('su_kien', [
                'thoi_gian_ket_thuc_dang_ky' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'comment' => 'Thời gian kết thúc đăng ký sự kiện'
                ]
            ]);
            log_message('info', 'Đã thêm cột thoi_gian_ket_thuc_dang_ky vào bảng su_kien');
        } else {
            log_message('info', 'Cột thoi_gian_ket_thuc_dang_ky đã tồn tại trong bảng su_kien');
        }
    }
} 