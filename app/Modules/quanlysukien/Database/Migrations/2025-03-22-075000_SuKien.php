<?php

namespace App\Modules\quanlysukien\Database\Migrations;

use CodeIgniter\Database\Migration;

class SuKien extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'su_kien_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'ten_su_kien' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false
            ],
            'su_kien_poster' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Lưu trữ thông tin về poster/hình ảnh sự kiện'
            ],
            'mo_ta' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Mô tả ngắn gọn về sự kiện'
            ],
            'mo_ta_su_kien' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Mô tả chi tiết về sự kiện'
            ],
            'chi_tiet_su_kien' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Thông tin chi tiết của sự kiện'
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
                'null' => true,
                'comment' => 'Tọa độ GPS để định vị địa điểm'
            ],
            'loai_su_kien_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false
            ],
            'ma_qr_code' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Mã QR dùng để điểm danh'
            ],
            'status' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => '1: Hoạt động, 0: Không hoạt động'
            ],
            'tong_dang_ky' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Tổng số người đăng ký tham gia'
            ],
            'tong_check_in' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Tổng số người đã check-in'
            ],
            'tong_check_out' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Tổng số người đã check-out'
            ],
            'cho_phep_check_in' => [
                'type' => 'BOOLEAN',
                'default' => true
            ],
            'cho_phep_check_out' => [
                'type' => 'BOOLEAN',
                'default' => true
            ],
            'yeu_cau_face_id' => [
                'type' => 'BOOLEAN',
                'default' => false,
                'comment' => 'Yêu cầu xác thực khuôn mặt khi check-in/out'
            ],
            'cho_phep_checkin_thu_cong' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'comment' => 'Cho phép admin check-in thủ công'
            ],
            'bat_dau_dang_ky' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Thời gian bắt đầu đăng ký'
            ],
            'ket_thuc_dang_ky' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Thời gian kết thúc đăng ký'
            ],
            'han_huy_dang_ky' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Hạn chót hủy đăng ký'
            ],
            'gio_bat_dau' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Giờ bắt đầu chính xác của sự kiện'
            ],
            'gio_ket_thuc' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Giờ kết thúc chính xác của sự kiện'
            ],
            'so_luong_tham_gia' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Giới hạn số người tham gia'
            ],
            'so_luong_dien_gia' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Số lượng diễn giả'
            ],
            'gioi_han_loai_nguoi_dung' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Giới hạn loại người dùng được tham gia'
            ],
            'tu_khoa_su_kien' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Từ khóa tìm kiếm sự kiện'
            ],
            'hashtag' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Đường dẫn thân thiện cho sự kiện'
            ],
            'so_luot_xem' => [
                'type' => 'INT',
                'default' => 0
            ],
            'lich_trinh' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Lịch trình chi tiết của sự kiện'
            ],
            'hinh_thuc' => [
                'type' => 'ENUM',
                'constraint' => ['offline', 'online', 'hybrid'],
                'default' => 'offline',
                'comment' => 'Hình thức tổ chức sự kiện'
            ],
            'link_online' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Link tham gia nếu là sự kiện online'
            ],
            'mat_khau_online' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Mật khẩu tham gia online nếu có'
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
        
        // Thêm khoá ngoại
        $this->forge->addForeignKey('loai_su_kien_id', 'loai_su_kien', 'loai_su_kien_id', 'RESTRICT', 'CASCADE');
        
        // Thêm chỉ mục
        $this->forge->addKey('ten_su_kien', false, false, 'idx_ten_su_kien');
        $this->forge->addKey('thoi_gian_bat_dau', false, false, 'idx_thoi_gian_bat_dau');
        $this->forge->addKey('thoi_gian_ket_thuc', false, false, 'idx_thoi_gian_ket_thuc');
        $this->forge->addKey('loai_su_kien_id', false, false, 'idx_loai_su_kien_id');
        $this->forge->addKey('slug', false, false, 'idx_sukien_slug');
        $this->forge->addKey('gio_bat_dau', false, false, 'idx_sukien_bat_dau');
        $this->forge->addKey('hinh_thuc', false, false, 'idx_hinh_thuc');
        
        // Tạo bảng
        $this->forge->createTable('su_kien', true, [
            'ENGINE' => 'InnoDB',
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'COMMENT' => 'Bảng lưu trữ thông tin sự kiện'
        ]);
        
        // Thêm giá trị mặc định cho created_at bằng CURRENT_TIMESTAMP
        $this->db->query('ALTER TABLE `su_kien` MODIFY `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP NULL');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('su_kien');
    }
} 