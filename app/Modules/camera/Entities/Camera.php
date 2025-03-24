<?php

namespace App\Modules\camera\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class Camera extends BaseEntity
{
    protected $tableName = 'camera';
    protected $primaryKey = 'camera_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'camera_id' => 'int',
        'port' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Trường duy nhất cần kiểm tra
    protected $uniqueFields = [
        'ten_camera' => 'Tên camera'
    ];
    
    // Các quy tắc xác thực cụ thể cho Camera
    protected $validationRules = [
        'ten_camera' => 'required|min_length[3]|max_length[255]|is_unique[camera.ten_camera,camera_id,{camera_id}]',
        'ma_camera' => 'permit_empty|max_length[20]',
        'ip_camera' => 'permit_empty|max_length[100]',
        'port' => 'permit_empty|integer',
        'username' => 'permit_empty|max_length[50]',
        'password' => 'permit_empty|max_length[50]',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_camera' => [
            'required' => 'Tên camera là bắt buộc',
            'min_length' => 'Tên camera phải có ít nhất {param} ký tự',
            'max_length' => 'Tên camera không được vượt quá {param} ký tự',
            'is_unique' => 'Tên camera đã tồn tại, vui lòng chọn tên khác',
        ],
        'ma_camera' => [
            'max_length' => 'Mã camera không được vượt quá {param} ký tự',
        ],
        'ip_camera' => [
            'max_length' => 'IP camera không được vượt quá {param} ký tự',
        ],
        'port' => [
            'integer' => 'Port phải là số nguyên',
        ],
        'username' => [
            'max_length' => 'Username không được vượt quá {param} ký tự',
        ],
        'password' => [
            'max_length' => 'Password không được vượt quá {param} ký tự',
        ],
    ];
    
    /**
     * Lấy ID của camera
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên camera
     *
     * @return string
     */
    public function getTenCamera(): string
    {
        return $this->attributes['ten_camera'] ?? '';
    }
    
    /**
     * Lấy mã camera
     *
     * @return string|null
     */
    public function getMaCamera(): ?string
    {
        return $this->attributes['ma_camera'] ?? null;
    }
    
    /**
     * Lấy IP của camera
     *
     * @return string|null
     */
    public function getIpCamera(): ?string
    {
        return $this->attributes['ip_camera'] ?? null;
    }
    
    /**
     * Lấy port của camera
     *
     * @return int|null
     */
    public function getPort(): ?int
    {
        $port = $this->attributes['port'] ?? null;
        return $port !== null ? (int)$port : null;
    }
    
    /**
     * Lấy username của camera
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->attributes['username'] ?? null;
    }
    
    /**
     * Lấy password của camera
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->attributes['password'] ?? null;
    }
    
    /**
     * Kiểm tra camera có đang hoạt động không
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? false);
    }
    
    /**
     * Đặt trạng thái hoạt động cho camera
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
     * Kiểm tra camera có đang trong thùng rác không
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
     * Lấy thông tin IP:Port
     * 
     * @return string
     */
    public function getConnectionInfo()
    {
        $ip = $this->getIpCamera();
        $port = $this->getPort();
        
        if (empty($ip)) {
            return '<span class="text-muted">Chưa cấu hình</span>';
        }
        
        if (!empty($port)) {
            return '<span class="badge bg-info">' . esc($ip) . ':' . esc($port) . '</span>';
        }
        
        return '<span class="badge bg-info">' . esc($ip) . '</span>';
    }
    
    /**
     * Lấy thông tin đăng nhập camera
     * 
     * @return string
     */
    public function getCredentialsInfo()
    {
        $username = $this->getUsername();
        
        if (empty($username)) {
            return '<span class="text-muted">Không có</span>';
        }
        
        return '<span class="badge bg-secondary">' . esc($username) . '</span>';
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
        
        $time = $this->attributes['deleted_at'] instanceof Time ? $this->attributes['deleted_at'] : new Time($this->attributes['deleted_at']);
        return $time->format('d/m/Y H:i:s');
    }
    
    /**
     * Kiểm tra mã camera có là duy nhất không
     *
     * @param string $code Mã cần kiểm tra
     * @param int|null $excludeId ID cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isUniqueCode(string $code, ?int $excludeId = null): bool
    {
        return $this->validateUniqueField('ma_camera', $code, $excludeId);
    }
    
    /**
     * Kiểm tra tên camera có là duy nhất không
     *
     * @param string $name Tên cần kiểm tra
     * @param int|null $excludeId ID cần loại trừ khi kiểm tra
     * @return bool
     */
    public function isUniqueName(string $name, ?int $excludeId = null): bool
    {
        return $this->validateUniqueField('ten_camera', $name, $excludeId);
    }
    
    /**
     * Phương thức trợ giúp để kiểm tra tính duy nhất của một trường
     *
     * @param string $field Tên trường cần kiểm tra
     * @param mixed $value Giá trị cần kiểm tra
     * @param int|null $exceptId ID cần loại trừ
     * @return bool
     */
    protected function validateUniqueField(string $field, $value, ?int $exceptId = null): bool
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->tableName);
        
        $builder->where($field, $value);
        
        if ($exceptId !== null) {
            $builder->where("{$this->primaryKey} !=", $exceptId);
        }
        
        // Trả về true nếu không tìm thấy bản ghi nào (tức là giá trị là duy nhất)
        return $builder->countAllResults() === 0;
    }
    
    /**
     * Overrides BaseEntity setAttributes() để tự động trim dữ liệu chuỗi
     * 
     * @param array $data
     * @return $this
     */
    public function setAttributes(array $data)
    {
        // Tự động trim các trường chuỗi
        foreach ($data as $key => $value) {
            // Chỉ trim các trường là chuỗi và không phải là mật khẩu
            if (is_string($value) && $key !== 'password') {
                $data[$key] = trim($value);
            }
        }
        
        // Gọi phương thức setAttributes của lớp cha
        return parent::setAttributes($data);
    }
} 