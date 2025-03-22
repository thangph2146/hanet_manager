<?php

namespace App\Modules\nganh\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class Nganh extends BaseEntity
{
    protected $tableName = 'nganh';
    protected $primaryKey = 'nganh_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'nganh_id' => 'int',
        'phong_khoa_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Trường duy nhất cần kiểm tra
    protected $uniqueFields = [
        'ma_nganh' => 'Mã ngành',
        'ten_nganh' => 'Tên ngành'
    ];
    
    // Các quy tắc xác thực cụ thể cho Nganh
    protected $validationRules = [
        'ten_nganh' => 'required|min_length[3]|max_length[200]',
        'ma_nganh' => 'required|max_length[20]',
        'phong_khoa_id' => 'permit_empty|integer',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_nganh' => [
            'required' => 'Tên ngành là bắt buộc',
            'min_length' => 'Tên ngành phải có ít nhất {param} ký tự',
            'max_length' => 'Tên ngành không được vượt quá {param} ký tự',
        ],
        'ma_nganh' => [
            'required' => 'Mã ngành là bắt buộc',
            'max_length' => 'Mã ngành không được vượt quá {param} ký tự',
        ],
        'phong_khoa_id' => [
            'integer' => 'ID phòng/khoa phải là số nguyên',
        ],
    ];
    
    /**
     * Lấy ID của ngành
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên ngành
     *
     * @return string
     */
    public function getTenNganh(): string
    {
        return $this->attributes['ten_nganh'] ?? '';
    }
    
    /**
     * Lấy mã ngành
     *
     * @return string|null
     */
    public function getMaNganh(): ?string
    {
        return $this->attributes['ma_nganh'] ?? null;
    }
    
    /**
     * Lấy ID phòng/khoa
     *
     * @return int|null
     */
    public function getPhongKhoaId(): ?int
    {
        $id = $this->attributes['phong_khoa_id'] ?? null;
        return $id !== null ? (int)$id : null;
    }
    
    /**
     * Kiểm tra ngành có đang hoạt động không
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? false);
    }
    
    /**
     * Đặt trạng thái hoạt động cho ngành
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
     * Kiểm tra ngành có đang trong thùng rác không
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
     * Lấy thông tin phòng khoa dưới dạng văn bản có định dạng
     * 
     * @return string
     */
    public function getPhongKhoaInfo()
    {
        // Kiểm tra nếu relationship đã được load
        if ($this->hasRelation('phong_khoa')) {
            $phongKhoa = $this->getRelation('phong_khoa');
            if ($phongKhoa) {
                return '<span class="badge bg-info">' . esc($phongKhoa->ten_phong_khoa) . ' (' . esc($phongKhoa->ma_phong_khoa) . ')</span>';
            }
        }
        
        // Nếu không có phòng khoa hoặc relationship chưa load
        if (empty($this->attributes['phong_khoa_id'])) {
            return '<span class="text-muted">Không có</span>';
        }
        
        return '<span class="badge bg-secondary">ID: ' . $this->attributes['phong_khoa_id'] . '</span>';
    }
    
    /**
     * Lấy nhãn trạng thái ngành dưới dạng HTML
     * 
     * @return string
     */
    public function getStatusLabel()
    {
        if ($this->attributes['status'] == 1) {
            return '<span class="badge bg-success">Hoạt động</span>';
        }
        return '<span class="badge bg-warning">Không hoạt động</span>';
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
        if (empty($this->attributes['deleted_at'])) {
            return '<span class="text-muted fst-italic">Không có</span>';
        }
        
        $time = $this->attributes['deleted_at'] instanceof Time ? $this->attributes['deleted_at'] : new Time($this->attributes['deleted_at']);
        return $time->format('d/m/Y H:i:s');
    }
    
    /**
     * Kiểm tra xem mã ngành có phải là duy nhất không
     *
     * @param string $code Mã ngành cần kiểm tra
     * @param int|null $excludeId ID ngành cần loại trừ (khi cập nhật)
     * @return bool
     */
    public function isUniqueCode(string $code, ?int $excludeId = null): bool
    {
        $model = model('App\Modules\nganh\Models\NganhModel');
        return !$model->isCodeExists($code, $excludeId);
    }
    
    /**
     * Kiểm tra xem tên ngành có phải là duy nhất không
     *
     * @param string $name Tên ngành cần kiểm tra
     * @param int|null $excludeId ID ngành cần loại trừ (khi cập nhật)
     * @return bool
     */
    public function isUniqueName(string $name, ?int $excludeId = null): bool
    {
        $model = model('App\Modules\nganh\Models\NganhModel');
        return !$model->isNameExists($name, $excludeId);
    }
    
    /**
     * Kiểm tra tính duy nhất của mã ngành
     * 
     * @param string $maNganh Mã ngành cần kiểm tra
     * @param int|null $exceptId ID ngành cần loại trừ khỏi việc kiểm tra (khi cập nhật)
     * @return bool True nếu mã ngành là duy nhất, False nếu đã tồn tại
     */
    public function validateUniqueMaNganh(string $maNganh, ?int $exceptId = null): bool
    {
        return $this->validateUniqueField('ma_nganh', $maNganh, $exceptId);
    }
    
    /**
     * Kiểm tra tính duy nhất của tên ngành
     * 
     * @param string $tenNganh Tên ngành cần kiểm tra
     * @param int|null $exceptId ID ngành cần loại trừ khỏi việc kiểm tra (khi cập nhật)
     * @return bool True nếu tên ngành là duy nhất, False nếu đã tồn tại
     */
    public function validateUniqueTenNganh(string $tenNganh, ?int $exceptId = null): bool
    {
        return $this->validateUniqueField('ten_nganh', $tenNganh, $exceptId);
    }
    
    /**
     * Kiểm tra tính duy nhất của một trường
     * 
     * @param string $field Tên trường cần kiểm tra
     * @param mixed $value Giá trị cần kiểm tra
     * @param int|null $exceptId ID cần loại trừ từ kiểm tra
     * @return bool True nếu không tìm thấy bản ghi trùng lặp
     */
    protected function validateUniqueField(string $field, $value, ?int $exceptId = null): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->tableName);
        $builder->where($field, $value);
        $builder->where('bin', 0);
        
        if ($exceptId !== null) {
            $builder->where($this->primaryKey . ' !=', $exceptId);
        }
        
        return $builder->countAllResults() === 0;
    }
} 