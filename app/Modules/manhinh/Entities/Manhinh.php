<?php

namespace App\Modules\manhinh\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class Manhinh extends BaseEntity
{
    protected $tableName = 'man_hinh';
    protected $primaryKey = 'man_hinh_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'man_hinh_id' => 'int',
        'camera_id' => 'int',
        'temlate_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Trường duy nhất cần kiểm tra - chỉ giữ ten_man_hinh là unique theo schema mới
    protected $uniqueFields = [
        'ten_man_hinh' => 'Tên màn hình'
    ];
    
    // Các quy tắc xác thực cụ thể cho Manhinh
    protected $validationRules = [
        'ten_man_hinh' => 'required|min_length[3]|max_length[255]|is_unique[man_hinh.ten_man_hinh,man_hinh_id,{man_hinh_id}]',
        'ma_man_hinh' => 'permit_empty|max_length[20]',
        'camera_id' => 'permit_empty|integer',
        'temlate_id' => 'permit_empty|integer',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_man_hinh' => [
            'required' => 'Tên màn hình là bắt buộc',
            'min_length' => 'Tên màn hình phải có ít nhất {param} ký tự',
            'max_length' => 'Tên màn hình không được vượt quá {param} ký tự',
            'is_unique' => 'Tên màn hình đã tồn tại, vui lòng chọn tên khác',
        ],
        'ma_man_hinh' => [
            'max_length' => 'Mã màn hình không được vượt quá {param} ký tự',
        ],
        'camera_id' => [
            'integer' => 'ID camera phải là số nguyên',
        ],
        'temlate_id' => [
            'integer' => 'ID template phải là số nguyên',
        ],
    ];
    
    /**
     * Lấy ID của màn hình
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên màn hình
     *
     * @return string
     */
    public function getTenManHinh(): string
    {
        return $this->attributes['ten_man_hinh'] ?? '';
    }
    
    /**
     * Lấy mã màn hình
     *
     * @return string|null
     */
    public function getMaManHinh(): ?string
    {
        return $this->attributes['ma_man_hinh'] ?? null;
    }
    
    /**
     * Lấy ID camera
     *
     * @return int|null
     */
    public function getCameraId(): ?int
    {
        $id = $this->attributes['camera_id'] ?? null;
        return $id !== null ? (int)$id : null;
    }
    
    /**
     * Lấy ID template
     *
     * @return int|null
     */
    public function getTemlateId(): ?int
    {
        $id = $this->attributes['temlate_id'] ?? null;
        return $id !== null ? (int)$id : null;
    }
    
    /**
     * Kiểm tra màn hình có đang hoạt động không
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? false);
    }
    
    /**
     * Đặt trạng thái hoạt động cho màn hình
     *
     * @param bool $status
     * @return $this
     */
    public function setStatus(bool $status)
    {
        $this->attributes['status'] = (int)$status;
        return $this;
    }
    
    /**
     * Kiểm tra màn hình có đang trong thùng rác không
     *
     * @return bool
     */
    public function isInBin(): bool
    {
        return (bool)($this->attributes['bin'] ?? false);
    }
    
    /**
     * Đặt trạng thái thùng rác
     *
     * @param bool $binStatus
     * @return $this
     */
    public function setBinStatus(bool $binStatus)
    {
        $this->attributes['bin'] = (int)$binStatus;
        return $this;
    }
    
    /**
     * Lấy thông tin camera dưới dạng văn bản có định dạng
     * 
     * @return string
     */
    public function getCameraInfo()
    {
        // Kiểm tra nếu thuộc tính camera tồn tại
        if (isset($this->camera) && !empty($this->camera)) {
            return '<span class="badge bg-info">' . esc($this->camera->ten_camera) . '</span>';
        }
        
        // Nếu không có camera hoặc chưa load
        return '<span class="text-muted">Không có</span>';
    }
    
    /**
     * Lấy thông tin template dưới dạng văn bản có định dạng
     * 
     * @return string
     */
    public function getTemplateInfo()
    {
        // Kiểm tra nếu thuộc tính template tồn tại
        if (isset($this->template) && !empty($this->template)) {
            return '<span class="badge bg-info">' . esc($this->template->ten_template) . '</span>';
        }
        
        // Nếu không có template hoặc chưa load
        return '<span class="text-muted">Không có</span>';
    }
    
    /**
     * Lấy nhãn trạng thái hiển thị
     *
     * @return string HTML với badge status
     */
    public function getStatusLabel()
    {
        if ($this->status == 1) {
            return '<span class="badge bg-success">Hoạt động</span>';
        } else {
            return '<span class="badge bg-danger">Không hoạt động</span>';
        }
    }
    
    /**
     * Lấy ngày tạo đã định dạng
     * 
     * @return string
     */
    public function getCreatedAtFormatted()
    {
        if (empty($this->attributes['created_at'])) {
            return '<span class="text-muted fst-italic">Chưa cập nhật</span>';
        }
        
        $time = $this->attributes['created_at'] instanceof Time ? $this->attributes['created_at'] : new Time($this->attributes['created_at']);
        return $time->format('d/m/Y H:i:s');
    }
    
    /**
     * Lấy ngày cập nhật đã định dạng
     * 
     * @return string
     */
    public function getUpdatedAtFormatted()
    {
        if (empty($this->attributes['updated_at'])) {
            return '<span class="text-muted fst-italic">Chưa cập nhật</span>';
        }
        
        $time = $this->attributes['updated_at'] instanceof Time ? $this->attributes['updated_at'] : new Time($this->attributes['updated_at']);
        return $time->format('d/m/Y H:i:s');
    }
    
    /**
     * Lấy ngày xóa đã định dạng
     *
     * @return string
     */
    public function getDeletedAtFormatted()
    {
        // Kiểm tra nếu deleted_at là null, chuỗi rỗng hoặc định dạng ngày mặc định
        if (!isset($this->attributes['deleted_at']) || 
            empty($this->attributes['deleted_at']) || 
            $this->attributes['deleted_at'] == '0000-00-00 00:00:00') {
            return '';
        }
        
        try {
            // Chuyển đổi sang đối tượng Time
            $time = $this->attributes['deleted_at'] instanceof Time ? 
                $this->attributes['deleted_at'] : 
                new Time($this->attributes['deleted_at']);
                
            return $time->format('d/m/Y H:i:s');
        } catch (\Exception $e) {
            // Trả về chuỗi rỗng nếu có lỗi
            return '';
        }
    }
    
    /**
     * Kiểm tra xem mã màn hình có phải là duy nhất không
     *
     * @param string $code Mã màn hình cần kiểm tra
     * @param int|null $excludeId ID màn hình cần loại trừ (khi cập nhật)
     * @return bool
     */
    public function isUniqueCode(string $code, ?int $excludeId = null): bool
    {
        $model = model('App\Modules\manhinh\Models\ManhinhModel');
        return !$model->isCodeExists($code, $excludeId);
    }
    
    /**
     * Kiểm tra xem tên màn hình có phải là duy nhất không
     *
     * @param string $name Tên màn hình cần kiểm tra
     * @param int|null $excludeId ID màn hình cần loại trừ (khi cập nhật)
     * @return bool
     */
    public function isUniqueName(string $name, ?int $excludeId = null): bool
    {
        $model = model('App\Modules\manhinh\Models\ManhinhModel');
        return !$model->isNameExists($name, $excludeId);
    }
    
    /**
     * Kiểm tra tính duy nhất của một trường khi thêm hoặc cập nhật
     *
     * @param string $field Tên trường cần kiểm tra
     * @param mixed $value Giá trị cần kiểm tra
     * @param int|null $exceptId ID cần loại trừ khỏi kiểm tra (khi cập nhật)
     * @return bool true nếu giá trị là duy nhất
     */
    protected function validateUniqueField(string $field, $value, ?int $exceptId = null): bool
    {
        // Đảm bảo có model
        $model = model('App\Modules\manhinh\Models\ManhinhModel');
        
        // Bỏ qua nếu giá trị rỗng
        if (empty($value)) {
            return true;
        }
        
        // Tạo query builder
        $builder = $model->builder();
        
        // Thêm điều kiện so sánh cho trường
        $builder->where($field, $value);
        
        // Nếu đang cập nhật, loại trừ bản ghi hiện tại
        if ($exceptId !== null) {
            $builder->where("{$this->primaryKey} !=", $exceptId);
        }
        
        // Kiểm tra xem có bản ghi nào khớp không
        return ($builder->countAllResults() === 0);
    }
} 