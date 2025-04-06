<?php

namespace App\Modules\quanlynguoidung\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateFullNameToNameParts extends Migration
{
    public function up()
    {
        // Kiểm tra xem trường MiddleName và LastName đã tồn tại chưa
        if (!$this->db->fieldExists('MiddleName', 'nguoi_dung') && !$this->db->fieldExists('LastName', 'nguoi_dung')) {
            // Thêm các trường mới
            $this->forge->addColumn('nguoi_dung', [
                'MiddleName' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'after' => 'FirstName'
                ],
                'LastName' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                    'null' => true,
                    'after' => 'MiddleName'
                ]
            ]);
            
            // Thêm chỉ mục cho các trường mới
            $this->db->query('ALTER TABLE nguoi_dung ADD INDEX idx_LastName (LastName)');
            $this->db->query('ALTER TABLE nguoi_dung ADD INDEX idx_MiddleName (MiddleName)');
            
            // Di chuyển dữ liệu từ FullName sang LastName, MiddleName và FirstName
            $builder = $this->db->table('nguoi_dung');
            $users = $builder->get()->getResult();
            
            foreach ($users as $user) {
                if (!empty($user->FullName)) {
                    $nameParts = $this->splitFullName($user->FullName);
                    
                    $builder->where('nguoi_dung_id', $user->nguoi_dung_id)
                            ->update([
                                'LastName' => $nameParts['LastName'],
                                'MiddleName' => $nameParts['MiddleName'],
                                'FirstName' => $nameParts['FirstName']
                            ]);
                }
            }
            
            $this->db->query('UPDATE nguoi_dung SET FirstName = FullName WHERE FirstName IS NULL AND FullName IS NOT NULL');
            
            log_message('info', 'Di chuyển dữ liệu từ FullName sang LastName, MiddleName và FirstName thành công');
        } else {
            log_message('info', 'Các trường LastName và MiddleName đã tồn tại trong bảng nguoi_dung');
        }
    }

    public function down()
    {
        // Kiểm tra xem các trường LastName và MiddleName có tồn tại không
        if ($this->db->fieldExists('LastName', 'nguoi_dung') && $this->db->fieldExists('MiddleName', 'nguoi_dung')) {
            // Cập nhật lại FullName từ các trường LastName, MiddleName và FirstName trước khi xóa
            $builder = $this->db->table('nguoi_dung');
            $users = $builder->get()->getResult();
            
            foreach ($users as $user) {
                $fullName = trim($user->LastName . ' ' . $user->MiddleName . ' ' . $user->FirstName);
                
                $builder->where('nguoi_dung_id', $user->nguoi_dung_id)
                        ->update(['FullName' => $fullName]);
            }
            
            // Xóa chỉ mục
            $this->db->query('ALTER TABLE nguoi_dung DROP INDEX idx_LastName');
            $this->db->query('ALTER TABLE nguoi_dung DROP INDEX idx_MiddleName');
            
            // Xóa các trường
            $this->forge->dropColumn('nguoi_dung', ['LastName', 'MiddleName']);
            
            log_message('info', 'Đã xóa các trường LastName và MiddleName, dữ liệu đã được ghi vào FullName');
        } else {
            log_message('info', 'Không tìm thấy trường LastName hoặc MiddleName trong bảng nguoi_dung');
        }
    }
    
    /**
     * Tách họ tên đầy đủ thành các phần Họ, Tên đệm và Tên
     * @param string $fullName Họ tên đầy đủ
     * @return array Mảng chứa LastName, MiddleName và FirstName
     */
    private function splitFullName($fullName)
    {
        $fullName = trim($fullName);
        $nameParts = explode(' ', $fullName);
        
        if (count($nameParts) == 1) {
            // Chỉ có một từ, coi như FirstName
            return [
                'LastName' => '',
                'MiddleName' => '',
                'FirstName' => $nameParts[0]
            ];
        } else if (count($nameParts) == 2) {
            // Có hai từ, coi như LastName và FirstName
            return [
                'LastName' => $nameParts[0],
                'MiddleName' => '',
                'FirstName' => $nameParts[1]
            ];
        } else {
            // Nhiều hơn hai từ
            $firstName = array_pop($nameParts); // Lấy từ cuối cùng làm tên
            $lastName = array_shift($nameParts); // Lấy từ đầu tiên làm họ
            $middleName = implode(' ', $nameParts); // Các từ còn lại là tên đệm
            
            return [
                'LastName' => $lastName,
                'MiddleName' => $middleName,
                'FirstName' => $firstName
            ];
        }
    }
} 