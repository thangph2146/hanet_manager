<?php

namespace App\Modules\formdangkysukien\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;
use App\Modules\quanlysukien\Entities\SuKien;

class FormDangKySuKien extends BaseEntity
{
    protected $tableName = 'form_dang_ky_su_kien';
    protected $primaryKey = 'form_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'form_id' => 'int',
        'su_kien_id' => 'int',
        'cau_truc_form' => 'json',
        'hien_thi_cong_khai' => 'boolean',
        'bat_buoc_dien' => 'boolean',
        'so_lan_su_dung' => 'int',
        'status' => 'int',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Các quy tắc xác thực cụ thể cho FormDangKySuKien
    protected $validationRules = [
        'ten_form' => [
            'rules' => 'required|max_length[255]',
            'label' => 'Tên form'
        ],
        'mo_ta' => [
            'rules' => 'permit_empty',
            'label' => 'Mô tả chi tiết'
        ],
        'su_kien_id' => [
            'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id]',
            'label' => 'ID sự kiện'
        ],
        'cau_truc_form' => [
            'rules' => 'required',
            'label' => 'Cấu trúc form'
        ],
        'hien_thi_cong_khai' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'label' => 'Hiển thị công khai'
        ],
        'bat_buoc_dien' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'label' => 'Bắt buộc điền'
        ],
        'so_lan_su_dung' => [
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]',
            'label' => 'Số lần sử dụng'
        ],
        'status' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'label' => 'Trạng thái'
        ]
    ];
    
    protected $validationMessages = [
        'ten_form' => [
            'required' => '{field} là bắt buộc',
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'su_kien_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên',
            'is_not_unique' => '{field} không tồn tại trong hệ thống'
        ],
        'cau_truc_form' => [
            'required' => '{field} là bắt buộc'
        ],
        'hien_thi_cong_khai' => [
            'in_list' => '{field} không hợp lệ'
        ],
        'bat_buoc_dien' => [
            'in_list' => '{field} không hợp lệ'
        ],
        'so_lan_su_dung' => [
            'integer' => '{field} phải là số nguyên',
            'greater_than_equal_to' => '{field} không được nhỏ hơn 0'
        ],
        'status' => [
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID của form
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->getFormId();
    }
    
    /**
     * Lấy ID form
     *
     * @return int
     */
    public function getFormId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên form
     *
     * @return string
     */
    public function getTenForm(): string
    {
        return $this->attributes['ten_form'] ?? '';
    }
    
    /**
     * Lấy mô tả form
     *
     * @return string|null
     */
    public function getMoTa(): ?string
    {
        return $this->attributes['mo_ta'] ?? null;
    }
    
    /**
     * Lấy ID sự kiện
     *
     * @return int
     */
    public function getSuKienId(): int
    {
        return (int)($this->attributes['su_kien_id'] ?? 0);
    }
    
    /**
     * Lấy tên sự kiện
     * 
     * @return string
     */
    public function getTenSuKien(): string
    {
        $suKienId = $this->getSuKienId();
        
        if ($suKienId > 0) {
            $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
            $suKien = $suKienModel->find($suKienId);
            
            if ($suKien && method_exists($suKien, 'getTenSuKien')) {
                return $suKien->getTenSuKien();
            }
        }
        
        return '';
    }
    
    /**
     * Lấy entity SuKien
     * 
     * @return SuKien|null
     */
    public function getSuKien(): ?SuKien
    {
        $suKienId = $this->getSuKienId();
        
        if ($suKienId > 0) {
            $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
            return $suKienModel->find($suKienId);
        }
        
        return null;
    }
    
    /**
     * Lấy cấu trúc form
     *
     * @return array|null
     */
    public function getCauTrucForm(): ?array
    {
        $cauTruc = $this->attributes['cau_truc_form'] ?? null;
        
        if (is_string($cauTruc)) {
            return json_decode($cauTruc, true);
        }
        
        return $cauTruc;
    }
    
    /**
     * Thiết lập cấu trúc form
     *
     * @param array|string $cauTruc Cấu trúc form dưới dạng mảng hoặc chuỗi JSON
     * @return $this
     */
    public function setCauTrucForm($cauTruc)
    {
        if (is_array($cauTruc)) {
            $this->attributes['cau_truc_form'] = json_encode($cauTruc);
        } else {
            $this->attributes['cau_truc_form'] = $cauTruc;
        }
        
        return $this;
    }
    
    /**
     * Lấy cấu trúc form dưới dạng chuỗi JSON
     *
     * @return string
     */
    public function getCauTrucFormJson(): string
    {
        $cauTruc = $this->attributes['cau_truc_form'] ?? null;
        
        if (is_string($cauTruc)) {
            return $cauTruc;
        }
        
        return json_encode($cauTruc);
    }
    
    /**
     * Lấy danh sách các trường trong form
     *
     * @return array
     */
    public function getFields(): array
    {
        $cauTruc = $this->getCauTrucForm();
        
        if ($cauTruc && isset($cauTruc['fields']) && is_array($cauTruc['fields'])) {
            return $cauTruc['fields'];
        }
        
        return [];
    }
    
    /**
     * Lấy thông tin một trường cụ thể theo ID
     *
     * @param string $fieldId ID của trường cần lấy
     * @return array|null Thông tin trường hoặc null nếu không tìm thấy
     */
    public function getField(string $fieldId): ?array
    {
        $fields = $this->getFields();
        
        foreach ($fields as $field) {
            if (isset($field['id']) && $field['id'] === $fieldId) {
                return $field;
            }
        }
        
        return null;
    }
    
    /**
     * Lấy tiêu đề form
     *
     * @return string
     */
    public function getTieuDeForm(): string
    {
        $cauTruc = $this->getCauTrucForm();
        
        if ($cauTruc && isset($cauTruc['title'])) {
            return $cauTruc['title'];
        }
        
        return $this->getTenForm();
    }
    
    /**
     * Lấy số lượng trường trong form
     *
     * @return int
     */
    public function countFields(): int
    {
        return count($this->getFields());
    }
    
    /**
     * Lấy danh sách các trường bắt buộc trong form
     *
     * @return array
     */
    public function getRequiredFields(): array
    {
        $fields = $this->getFields();
        $requiredFields = [];
        
        foreach ($fields as $field) {
            if (isset($field['required']) && $field['required'] === true) {
                $requiredFields[] = $field;
            }
        }
        
        return $requiredFields;
    }
    
    /**
     * Lấy số lượng trường bắt buộc trong form
     *
     * @return int
     */
    public function countRequiredFields(): int
    {
        return count($this->getRequiredFields());
    }
    
    /**
     * Kiểm tra trạng thái hiển thị công khai
     *
     * @return bool
     */
    public function isHienThiCongKhai(): bool
    {
        return (bool)($this->attributes['hien_thi_cong_khai'] ?? true);
    }
    
    /**
     * Thiết lập trạng thái hiển thị công khai
     *
     * @param bool|int $status Trạng thái hiển thị công khai
     * @return $this
     */
    public function setHienThiCongKhai($status)
    {
        $this->attributes['hien_thi_cong_khai'] = (bool)$status;
        return $this;
    }
    
    /**
     * Lấy giá trị hiển thị công khai
     *
     * @return int
     */
    public function getHienThiCongKhai(): int
    {
        return (int)($this->attributes['hien_thi_cong_khai'] ?? 1);
    }
    
    /**
     * Kiểm tra trạng thái bắt buộc điền
     *
     * @return bool
     */
    public function isBatBuocDien(): bool
    {
        return (bool)($this->attributes['bat_buoc_dien'] ?? false);
    }
    
    /**
     * Thiết lập trạng thái bắt buộc điền
     *
     * @param bool|int $status Trạng thái bắt buộc điền
     * @return $this
     */
    public function setBatBuocDien($status)
    {
        $this->attributes['bat_buoc_dien'] = (bool)$status;
        return $this;
    }
    
    /**
     * Lấy giá trị bắt buộc điền
     *
     * @return int
     */
    public function getBatBuocDien(): int
    {
        return (int)($this->attributes['bat_buoc_dien'] ?? 0);
    }
    
    /**
     * Lấy số lần sử dụng form
     *
     * @return int
     */
    public function getSoLanSuDung(): int
    {
        return (int)($this->attributes['so_lan_su_dung'] ?? 0);
    }
    
    /**
     * Tăng số lần sử dụng form
     *
     * @param int $increment Số lượng tăng
     * @return $this
     */
    public function tangSoLanSuDung(int $increment = 1)
    {
        $this->attributes['so_lan_su_dung'] = $this->getSoLanSuDung() + $increment;
        return $this;
    }
    
    /**
     * Thiết lập số lần sử dụng form
     *
     * @param int $count Số lần sử dụng
     * @return $this
     */
    public function setSoLanSuDung(int $count)
    {
        $this->attributes['so_lan_su_dung'] = $count;
        return $this;
    }
    
    /**
     * Kiểm tra trạng thái hoạt động
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (int)($this->attributes['status'] ?? 1) === 1;
    }
    
    /**
     * Lấy trạng thái
     *
     * @return int
     */
    public function getStatus(): int
    {
        return (int)($this->attributes['status'] ?? 1);
    }
    
    /**
     * Thiết lập trạng thái
     *
     * @param int|bool $status Trạng thái (0: Không hoạt động, 1: Hoạt động)
     * @return $this
     */
    public function setStatus($status)
    {
        $this->attributes['status'] = (int)$status;
        return $this;
    }
    
    /**
     * Lấy text trạng thái bắt buộc điền
     *
     * @return string
     */
    public function getBatBuocDienText(): string
    {
        return $this->getBatBuocDien() ? 'Có' : 'Không';
    }

    /**
     * Lấy text trạng thái hiển thị công khai
     *
     * @return string
     */
    public function getHienThiCongKhaiText(): string
    {
        return $this->getHienThiCongKhai() ? 'Có' : 'Không';
    }

    /**
     * Lấy text trạng thái
     * 
     * @return string
     */
    public function getStatusText(): string
    {
        return $this->getStatus() ? 'Hoạt động' : 'Không hoạt động';
    }
    
    /**
     * Lấy ngày tạo
     *
     * @return Time|null
     */
    public function getCreatedAt(): ?Time
    {
        $created = $this->attributes['created_at'] ?? null;
        
        if (empty($created)) {
            return null;
        }
        
        if ($created instanceof Time) {
            return $created;
        }
        
        return new Time($created);
    }
    
    /**
     * Lấy ngày cập nhật
     *
     * @return Time|null
     */
    public function getUpdatedAt(): ?Time
    {
        if (empty($this->attributes['updated_at'])) {
            return null;
        }
        
        return $this->attributes['updated_at'] instanceof Time 
            ? $this->attributes['updated_at'] 
            : new Time($this->attributes['updated_at']);
    }
    
    /**
     * Lấy ngày xóa
     *
     * @return Time|null
     */
    public function getDeletedAt(): ?Time
    {
        if (empty($this->attributes['deleted_at'])) {
            return null;
        }
        
        return $this->attributes['deleted_at'] instanceof Time 
            ? $this->attributes['deleted_at'] 
            : new Time($this->attributes['deleted_at']);
    }
    
    /**
     * Kiểm tra xem bản ghi đã bị xóa chưa
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return !empty($this->attributes['deleted_at']);
    }
    
    /**
     * Lấy các quy tắc xác thực
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->validationRules;
    }
    
    /**
     * Lấy các thông báo xác thực
     *
     * @return array
     */
    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }
    
    /**
     * Xác thực dữ liệu form đăng ký
     *
     * @param array $data Dữ liệu cần xác thực
     * @return array Mảng chứa kết quả xác thực [isValid, errors]
     */
    public function validateFormData(array $data): array
    {
        $fields = $this->getFields();
        $errors = [];
        
        foreach ($fields as $field) {
            $fieldId = $field['id'] ?? '';
            $fieldLabel = $field['label'] ?? $fieldId;
            $required = $field['required'] ?? false;
            
            // Kiểm tra trường bắt buộc
            if ($required && (!isset($data[$fieldId]) || trim($data[$fieldId]) === '')) {
                $errors[$fieldId] = "Trường '{$fieldLabel}' là bắt buộc.";
                continue;
            }
            
            // Bỏ qua nếu không có dữ liệu và không bắt buộc
            if (!isset($data[$fieldId]) || $data[$fieldId] === '') {
                continue;
            }
            
            // Xác thực theo loại trường
            $fieldType = $field['type'] ?? '';
            $value = $data[$fieldId];
            
            switch ($fieldType) {
                case 'email':
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$fieldId] = "'{$fieldLabel}' không phải là địa chỉ email hợp lệ.";
                    }
                    break;
                    
                case 'tel':
                    if (isset($field['validation']['pattern'])) {
                        $pattern = '/' . $field['validation']['pattern'] . '/';
                        if (!preg_match($pattern, $value)) {
                            $errors[$fieldId] = "'{$fieldLabel}' không đúng định dạng.";
                        }
                    }
                    break;
                    
                case 'text':
                case 'textarea':
                    if (isset($field['validation']['min_length']) && strlen($value) < $field['validation']['min_length']) {
                        $errors[$fieldId] = "'{$fieldLabel}' phải có ít nhất {$field['validation']['min_length']} ký tự.";
                    }
                    
                    if (isset($field['validation']['max_length']) && strlen($value) > $field['validation']['max_length']) {
                        $errors[$fieldId] = "'{$fieldLabel}' không được vượt quá {$field['validation']['max_length']} ký tự.";
                    }
                    break;
                    
                case 'select':
                    if (isset($field['options']) && !$this->isValidOption($value, $field['options'])) {
                        $errors[$fieldId] = "Giá trị '{$value}' không hợp lệ cho trường '{$fieldLabel}'.";
                    }
                    break;
                    
                case 'checkbox':
                case 'radio':
                    if (is_array($value)) {
                        foreach ($value as $v) {
                            if (isset($field['options']) && !$this->isValidOption($v, $field['options'])) {
                                $errors[$fieldId] = "Giá trị '{$v}' không hợp lệ cho trường '{$fieldLabel}'.";
                                break;
                            }
                        }
                    } elseif (isset($field['options']) && !$this->isValidOption($value, $field['options'])) {
                        $errors[$fieldId] = "Giá trị '{$value}' không hợp lệ cho trường '{$fieldLabel}'.";
                    }
                    break;
            }
        }
        
        return [
            'isValid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Kiểm tra giá trị có nằm trong danh sách lựa chọn không
     *
     * @param string $value Giá trị cần kiểm tra
     * @param array $options Danh sách lựa chọn
     * @return bool
     */
    private function isValidOption(string $value, array $options): bool
    {
        foreach ($options as $option) {
            if (isset($option['value']) && $option['value'] === $value) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Lấy thông tin tóm tắt về form
     *
     * @return array
     */
    public function toSummary(): array
    {
        return [
            'form_id' => $this->getFormId(),
            'ten_form' => $this->getTenForm(),
            'ten_su_kien' => $this->getTenSuKien(),
            'so_truong' => $this->countFields(),
            'so_truong_bat_buoc' => $this->countRequiredFields(),
            'bat_buoc_dien' => $this->isBatBuocDien(),
            'hien_thi_cong_khai' => $this->isHienThiCongKhai(),
            'so_lan_su_dung' => $this->getSoLanSuDung(),
            'status' => $this->isActive()
        ];
    }
}