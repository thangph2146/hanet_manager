<?php

namespace App\Modules\quanlynguoidung\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateRequiredFields extends Migration
{
    public function up()
    {
        // Danh sách các trường không cho phép NULL
        $requiredFields = ['Email', 'FullName', 'LastName', 'MiddleName', 'FirstName', 'MobilePhone'];
        
        // Lấy thông tin cấu trúc bảng
        $fields = $this->db->getFieldData('nguoi_dung');
        $fieldTypes = [];
        
        // Lưu thông tin kiểu dữ liệu và độ dài của từng trường
        foreach ($fields as $field) {
            $fieldTypes[$field->name] = [
                'type' => $field->type,
                'max_length' => $field->max_length ?? null,
                'primary_key' => $field->primary_key ?? false
            ];
        }
        
        foreach ($fieldTypes as $fieldName => $fieldInfo) {
            // Bỏ qua trường ID và các trường thời gian tự động
            if ($fieldName === 'nguoi_dung_id' || 
                in_array($fieldName, ['created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }
            
            // Lấy kiểu dữ liệu
            $type = $fieldInfo['type'];
            $maxLength = $fieldInfo['max_length'];
            
            // Chuẩn bị phần định nghĩa kiểu dữ liệu dựa vào kiểu hiện tại
            $typeDefinition = $this->getTypeDefinition($type, $maxLength);
            
            // Kiểm tra xem trường có nằm trong danh sách các trường bắt buộc không
            if (in_array($fieldName, $requiredFields)) {
                // Đặt các trường bắt buộc thành NOT NULL
                $this->db->query("ALTER TABLE `nguoi_dung` MODIFY `{$fieldName}` {$typeDefinition} NOT NULL");
                log_message('info', "Đã đặt trường {$fieldName} thành NOT NULL với kiểu {$typeDefinition}");
            } else {
                // Xử lý đặc biệt cho trường status
                if ($fieldName === 'status') {
                    $this->db->query("ALTER TABLE `nguoi_dung` MODIFY `{$fieldName}` TINYINT(1) DEFAULT 1 NULL");
                } else {
                    // Đặt các trường khác thành NULL
                    $this->db->query("ALTER TABLE `nguoi_dung` MODIFY `{$fieldName}` {$typeDefinition} NULL");
                }
                log_message('info', "Đã đặt trường {$fieldName} thành NULL với kiểu {$typeDefinition}");
            }
        }
        
        log_message('info', 'Đã cập nhật các trường trong bảng nguoi_dung thành công');
    }

    /**
     * Hàm trả về định nghĩa kiểu dữ liệu SQL dựa vào thông tin từ CSDL
     */
    private function getTypeDefinition($type, $maxLength)
    {
        $type = strtoupper($type);
        
        switch ($type) {
            case 'VARCHAR':
                return "VARCHAR({$maxLength})";
            case 'INT':
            case 'INTEGER':
                return $maxLength ? "INT({$maxLength})" : "INT(11)";
            case 'TINYINT':
                return "TINYINT(1)";
            case 'DATETIME':
                return "DATETIME";
            case 'TEXT':
                return "TEXT";
            case 'DECIMAL':
                return "DECIMAL(10,2)";
            default:
                return "VARCHAR(255)";
        }
    }

    public function down()
    {
        // Xây dựng cấu trúc gốc của bảng
        $originalStructure = [
            'AccountId' => 'VARCHAR(50) NULL',
            'u_id' => 'INT(11) NULL',
            'FirstName' => 'VARCHAR(100) NULL',
            'MiddleName' => 'VARCHAR(100) NULL',
            'LastName' => 'VARCHAR(100) NULL',
            'AccountType' => 'VARCHAR(20) NULL',
            'FullName' => 'VARCHAR(100) NULL',
            'MobilePhone' => 'VARCHAR(20) NULL',
            'Email' => 'VARCHAR(100) NULL',
            'HomePhone1' => 'VARCHAR(20) NULL',
            'PW' => 'VARCHAR(255) NULL',
            'HomePhone' => 'VARCHAR(20) NULL',
            'avatar' => 'VARCHAR(255) NULL',
            'loai_nguoi_dung_id' => 'INT(11) NULL',
            'mat_khau_local' => 'VARCHAR(255) NULL',
            'nam_hoc_id' => 'INT(11) NULL',
            'bac_hoc_id' => 'INT(11) NULL',
            'he_dao_tao_id' => 'INT(11) NULL',
            'nganh_id' => 'INT(11) NULL',
            'phong_khoa_id' => 'INT(11) NULL',
            'status' => 'TINYINT(1) DEFAULT 1 NULL',
            'last_login' => 'DATETIME NULL'
        ];
        
        // Khôi phục từng trường về cấu trúc gốc
        foreach ($originalStructure as $fieldName => $fieldDef) {
            $this->db->query("ALTER TABLE `nguoi_dung` MODIFY `{$fieldName}` {$fieldDef}");
            log_message('info', "Đã khôi phục trường {$fieldName} về {$fieldDef}");
        }
        
        log_message('info', 'Đã khôi phục cấu trúc ban đầu của bảng nguoi_dung');
    }
} 