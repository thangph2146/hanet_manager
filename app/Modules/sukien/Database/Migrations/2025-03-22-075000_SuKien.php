<?php

namespace App\Modules\sukien\Database\Migrations;

use CodeIgniter\Database\Migration;

class SuKien extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'su_kien_id' => [
                'type' => 'INT',
                'auto_increment' => true
            ],
            'ten_su_kien' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'su_kien_poster' => [
                'type' => 'JSON',
                'null' => true
            ],
            'mo_ta' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'mo_ta_su_kien' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'chi_tiet_su_kien' => [
                'type' => 'TEXT',
                'null' => true
            ],
            'thoi_gian_bat_dau' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'thoi_gian_ket_thuc' => [
                'type' => 'DATETIME',
                'null' => false
            ],
            'dia_diem' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'dia_chi_cu_the' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'toa_do_gps' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'loai_su_kien_id' => [
                'type' => 'INT',
                'null' => false
            ],
            'nguoi_tao_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => false
            ],
            'ma_qr_code' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'tong_dang_ky' => [
                'type' => 'INT',
                'default' => 0
            ],
            'tong_check_in' => [
                'type' => 'INT',
                'default' => 0
            ],
            'tong_check_out' => [
                'type' => 'INT',
                'default' => 0
            ],
            'cho_phep_check_in' => [
                'type' => 'BOOLEAN',
                'default' => false
            ],
            'cho_phep_check_out' => [
                'type' => 'BOOLEAN',
                'default' => false
            ],
            'yeu_cau_face_id' => [
                'type' => 'BOOLEAN',
                'default' => false
            ],
            'cho_phep_checkin_thu_cong' => [
                'type' => 'BOOLEAN',
                'default' => false
            ],
            'tu_dong_xac_nhan_svgv' => [
                'type' => 'BOOLEAN',
                'default' => true
            ],
            'yeu_cau_duyet_khach' => [
                'type' => 'BOOLEAN',
                'default' => true
            ],
            'bat_dau_dang_ky' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'ket_thuc_dang_ky' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'han_huy_dang_ky' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'gio_bat_dau' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'gio_ket_thuc' => [
                'type' => 'DATETIME',
                'null' => true
            ],
            'so_luong_tham_gia' => [
                'type' => 'INT',
                'default' => 0
            ],
            'so_luong_dien_gia' => [
                'type' => 'INT',
                'default' => 0
            ],
            'gioi_han_loai_nguoi_dung' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'tu_khoa_su_kien' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'hashtag' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'so_luot_xem' => [
                'type' => 'INT',
                'default' => 0
            ],
            'lich_trinh' => [
                'type' => 'JSON',
                'null' => true
            ],
            'version' => [
                'type' => 'INT',
                'default' => 1
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
        $this->forge->addKey('su_kien_id', true);
        
        // Thêm các chỉ mục
        $this->forge->addKey('ten_su_kien', false, false, 'idx_ten_su_kien');
        $this->forge->addKey('thoi_gian_bat_dau', false, false, 'idx_thoi_gian_bat_dau');
        $this->forge->addKey('thoi_gian_ket_thuc', false, false, 'idx_thoi_gian_ket_thuc');
        $this->forge->addKey('loai_su_kien_id', false, false, 'idx_loai_su_kien_id');
        $this->forge->addKey('nguoi_tao_id', false, false, 'idx_nguoi_tao_id');
        $this->forge->addKey('slug', false, false, 'idx_sukien_slug');
        $this->forge->addKey('gio_bat_dau', false, false, 'idx_sukien_bat_dau');

        // Tạo bảng
        $this->forge->createTable('su_kien');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('su_kien');
    }
} 