<?php

namespace App\Modules\quanlysukiendiengia\Database\Migrations;

use CodeIgniter\Database\Migration;

class SuKienDienGia extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'su_kien_dien_gia_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => true
            ],
            'su_kien_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false
            ],
            'dien_gia_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false
            ],
            'thu_tu' => [
                'type' => 'INT',
                'default' => 0,
                'comment' => 'Thứ tự xuất hiện của diễn giả'
            ],
            'vai_tro' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'comment' => 'Vai trò trong sự kiện (Chủ tọa, Người thuyết trình...)'
            ],
            'mo_ta' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Mô tả vai trò hoặc nội dung trình bày'
            ],
            'thoi_gian_trinh_bay' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Thời gian diễn giả trình bày'
            ],
            'thoi_gian_ket_thuc' => [
                'type' => 'DATETIME',
                'null' => true,
                'comment' => 'Thời gian kết thúc trình bày'
            ],
            'thoi_luong_phut' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'Thời lượng trình bày (phút)'
            ],
            'tieu_de_trinh_bay' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Tiêu đề phần trình bày'
            ],
            'tai_lieu_dinh_kem' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Tài liệu đính kèm (JSON)'
            ],
            'trang_thai_tham_gia' => [
                'type' => 'ENUM',
                'constraint' => ['xac_nhan', 'cho_xac_nhan', 'tu_choi', 'khong_lien_he_duoc'],
                'default' => 'cho_xac_nhan',
                'comment' => 'Trạng thái tham gia của diễn giả'
            ],
            'hien_thi_cong_khai' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'comment' => 'Hiển thị thông tin diễn giả công khai'
            ],
            'ghi_chu' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Ghi chú về diễn giả trong sự kiện'
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
        $this->forge->addKey('su_kien_dien_gia_id', true);
        
        // Thêm khoá ngoại nếu các bảng liên quan đã tồn tại
        if ($this->db->tableExists('su_kien') && $this->db->tableExists('dien_gia')) {
            $this->forge->addForeignKey('su_kien_id', 'su_kien', 'su_kien_id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('dien_gia_id', 'dien_gia', 'dien_gia_id', 'CASCADE', 'CASCADE');
        }
        
        // Thêm chỉ mục
        $this->forge->addUniqueKey(['su_kien_id', 'dien_gia_id'], 'uk_sukien_diengia');
        $this->forge->addKey('su_kien_id', false, false, 'idx_su_kien_id');
        $this->forge->addKey('dien_gia_id', false, false, 'idx_dien_gia_id');
        $this->forge->addKey('trang_thai_tham_gia', false, false, 'idx_trang_thai_tham_gia');
        
        // Tạo bảng
        $this->forge->createTable('su_kien_dien_gia', true, [
            'ENGINE' => 'InnoDB',
            'CHARACTER SET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
            'COMMENT' => 'Bảng liên kết giữa sự kiện và diễn giả'
        ]);
        
        // Thêm giá trị mặc định cho created_at bằng CURRENT_TIMESTAMP
        $this->db->query('ALTER TABLE `su_kien_dien_gia` MODIFY `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP NULL');
    }

    public function down()
    {
        // Xóa bảng
        $this->forge->dropTable('su_kien_dien_gia');
    }
} 