<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNguoiDungIdToDangKySuKien extends Migration
{
    public function up()
    {
        // Kiểm tra xem cột nguoi_dung_id đã tồn tại chưa
        if (!$this->db->fieldExists('nguoi_dung_id', 'dangky_sukien')) {
            $this->forge->addColumn('dangky_sukien', [
                'nguoi_dung_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,
                    'after' => 'dangky_sukien_id'
                ]
            ]);
            
            // Tạo index cho cột nguoi_dung_id
            $this->forge->addKey('nguoi_dung_id', false);
        }
        
        // Kiểm tra xem cột ma_sinh_vien đã tồn tại chưa
        if (!$this->db->fieldExists('ma_sinh_vien', 'dangky_sukien')) {
            $this->forge->addColumn('dangky_sukien', [
                'ma_sinh_vien' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                    'after' => 'ho_ten'
                ]
            ]);
        }
        
        // Kiểm tra xem cột so_dien_thoai đã tồn tại chưa
        if (!$this->db->fieldExists('so_dien_thoai', 'dangky_sukien') && $this->db->fieldExists('dien_thoai', 'dangky_sukien')) {
            // Nếu đã có cột dien_thoai, thêm cột so_dien_thoai mới và sao chép dữ liệu
            $this->forge->addColumn('dangky_sukien', [
                'so_dien_thoai' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => true,
                    'after' => 'dien_thoai'
                ]
            ]);
            
            // Cập nhật dữ liệu từ dien_thoai sang so_dien_thoai
            $this->db->query('UPDATE dangky_sukien SET so_dien_thoai = dien_thoai WHERE so_dien_thoai IS NULL');
        }
    }

    public function down()
    {
        // Xóa các cột nếu cần rollback
        if ($this->db->fieldExists('nguoi_dung_id', 'dangky_sukien')) {
            $this->forge->dropColumn('dangky_sukien', 'nguoi_dung_id');
        }
        
        if ($this->db->fieldExists('ma_sinh_vien', 'dangky_sukien')) {
            $this->forge->dropColumn('dangky_sukien', 'ma_sinh_vien');
        }
        
        if ($this->db->fieldExists('so_dien_thoai', 'dangky_sukien')) {
            $this->forge->dropColumn('dangky_sukien', 'so_dien_thoai');
        }
    }
}
