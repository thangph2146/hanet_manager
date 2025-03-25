<?php

namespace App\Modules\diengia\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

class DienGia extends BaseEntity
{
    protected $tableName = 'dien_gia';
    protected $primaryKey = 'dien_gia_id';
    
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'dien_gia_id' => 'int',
        'thu_tu' => 'int',
        'status' => 'int'
    ];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Trường duy nhất cần kiểm tra
    protected $uniqueFields = [
        'ten_dien_gia' => 'Tên diễn giả'
    ];
    
    // Các quy tắc xác thực cụ thể cho DienGia
    protected $validationRules = [
        'ten_dien_gia' => 'required|min_length[3]|max_length[255]|is_unique[dien_gia.ten_dien_gia,dien_gia_id,{dien_gia_id}]',
        'chuc_danh' => 'permit_empty|max_length[255]',
        'to_chuc' => 'permit_empty|max_length[255]',
        'gioi_thieu' => 'permit_empty',
        'avatar' => 'permit_empty',
        'thu_tu' => 'permit_empty|integer',
        'status' => 'permit_empty|integer|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_dien_gia' => [
            'required' => 'Tên diễn giả là bắt buộc',
            'min_length' => 'Tên diễn giả phải có ít nhất {param} ký tự',
            'max_length' => 'Tên diễn giả không được vượt quá {param} ký tự',
            'is_unique' => 'Tên diễn giả đã tồn tại, vui lòng chọn tên khác',
        ],
        'chuc_danh' => [
            'max_length' => 'Chức danh không được vượt quá {param} ký tự',
        ],
        'to_chuc' => [
            'max_length' => 'Tổ chức không được vượt quá {param} ký tự',
        ],
        'avatar' => [
            'max_length' => 'Đường dẫn avatar không được vượt quá {param} ký tự',
        ],
        'thu_tu' => [
            'integer' => 'Thứ tự phải là số nguyên',
        ],
        'status' => [
            'integer' => 'Trạng thái phải là số nguyên',
            'in_list' => 'Trạng thái phải là 0 hoặc 1',
        ],
    ];
    
    /**
     * Lấy ID của diễn giả
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
    }
    
    /**
     * Lấy tên diễn giả
     *
     * @return string
     */
    public function getTenDienGia(): string
    {
        return $this->attributes['ten_dien_gia'] ?? '';
    }
    
    /**
     * Lấy chức danh của diễn giả
     *
     * @return string|null
     */
    public function getChucDanh(): ?string
    {
        return $this->attributes['chuc_danh'] ?? null;
    }
    
    /**
     * Lấy tổ chức của diễn giả
     *
     * @return string|null
     */
    public function getToChuc(): ?string
    {
        return $this->attributes['to_chuc'] ?? null;
    }
    
    /**
     * Lấy giới thiệu của diễn giả
     *
     * @return string|null
     */
    public function getGioiThieu(): ?string
    {
        return $this->attributes['gioi_thieu'] ?? null;
    }
    
    /**
     * Lấy đường dẫn avatar của diễn giả
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->attributes['avatar'] ?? null;
    }
    
    /**
     * Lấy thứ tự hiển thị của diễn giả
     *
     * @return int
     */
    public function getThuTu(): int
    {
        return (int)($this->attributes['thu_tu'] ?? 0);
    }
    
    /**
     * Đặt thứ tự hiển thị cho diễn giả
     *
     * @param int $thuTu
     * @return $this
     */
    public function setThuTu(int $thuTu)
    {
        $this->attributes['thu_tu'] = $thuTu;
        return $this;
    }
    
    /**
     * Kiểm tra diễn giả có đang trong thùng rác không
     *
     * @return bool
     */
    public function isInBin(): bool
    {
        return isset($this->attributes['deleted_at']) && $this->attributes['deleted_at'] !== null;
    }
    
    /**
     * Kiểm tra diễn giả có bị xóa mềm không
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return isset($this->attributes['deleted_at']) && $this->attributes['deleted_at'] !== null;
    }
    
    /**
     * Lấy url đầy đủ của avatar
     * 
     * @return string
     */
    public function getAvatarUrl()
    {
        $avatar = $this->getAvatar();
        
        if (empty($avatar)) {
            return base_url('assets/images/default-avatar.png');
        }
        
        // Kiểm tra nếu avatar đã là URL đầy đủ
        if (strpos($avatar, 'http://') === 0 || strpos($avatar, 'https://') === 0) {
            return $avatar;
        }
        
        // Kiểm tra nếu avatar bắt đầu với "data/images"
        if (strpos($avatar, 'data/images') === 0) {
            return base_url($avatar);
        }
        
        // Kiểm tra nếu avatar đã có tiền tố "public/"
        if (strpos($avatar, 'public/') === 0) {
            return base_url(substr($avatar, 7)); // Bỏ "public/" khỏi đường dẫn
        }
        
        return base_url($avatar);
    }
    
    /**
     * Lấy HTML hiển thị avatar
     * 
     * @param string $class Class CSS bổ sung
     * @param string $style Style CSS bổ sung
     * @return string
     */
    public function getAvatarHtml($class = 'img-thumbnail', $style = 'width: 100px;')
    {
        $avatarUrl = $this->getAvatarUrl();
        return '<img src="' . esc($avatarUrl) . '" alt="' . esc($this->getTenDienGia()) . '" class="' . esc($class) . '" style="' . esc($style) . '">';
    }
    
    /**
     * Lấy thông tin tổ chức và chức danh
     * 
     * @return string
     */
    public function getToChucVaChucDanh()
    {
        $chucDanh = $this->getChucDanh();
        $toChuc = $this->getToChuc();
        
        if (empty($chucDanh) && empty($toChuc)) {
            return '<span class="text-muted">Chưa cập nhật</span>';
        }
        
        if (empty($chucDanh)) {
            return '<span class="badge bg-info">' . esc($toChuc) . '</span>';
        }
        
        if (empty($toChuc)) {
            return '<span class="badge bg-primary">' . esc($chucDanh) . '</span>';
        }
        
        return '<span class="badge bg-primary">' . esc($chucDanh) . '</span> - <span class="badge bg-info">' . esc($toChuc) . '</span>';
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
        return $time->toLocalizedString('dd/MM/yyyy HH:mm:ss');
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
        return $time->toLocalizedString('dd/MM/yyyy HH:mm:ss');
    }
    
    /**
     * Lấy ngày xóa đã định dạng
     * 
     * @return string
     */
    public function getDeletedAtFormatted()
    {
        if (empty($this->attributes['deleted_at'])) {
            return '<span class="text-muted fst-italic">Chưa xóa</span>';
        }
        
        $time = $this->attributes['deleted_at'] instanceof Time ? $this->attributes['deleted_at'] : new Time($this->attributes['deleted_at']);
        return $time->toLocalizedString('dd/MM/yyyy HH:mm:ss');
    }
    
    /**
     * Kiểm tra tên diễn giả đã tồn tại chưa
     *
     * @param string $name Tên diễn giả cần kiểm tra
     * @param int|null $excludeId ID diễn giả để loại trừ khỏi việc kiểm tra (hữu ích khi cập nhật)
     * @return bool Trả về true nếu tên đã tồn tại, false nếu chưa
     */
    public function isUniqueName(string $name, ?int $excludeId = null): bool
    {
        return $this->validateUniqueField('ten_dien_gia', $name, $excludeId);
    }
    
    /**
     * Xác thực giá trị duy nhất của một trường
     *
     * @param string $field Tên trường
     * @param mixed $value Giá trị cần kiểm tra
     * @param int|null $exceptId ID để loại trừ (dùng khi cập nhật)
     * @return bool Trả về true nếu giá trị duy nhất, false nếu đã tồn tại
     */
    protected function validateUniqueField(string $field, $value, ?int $exceptId = null): bool
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table($this->tableName);
        $builder->where($field, $value);
        
        // Loại trừ ID hiện tại nếu đang cập nhật
        if ($exceptId !== null) {
            $builder->where("{$this->primaryKey} !=", $exceptId);
        }
        
        // Chỉ kiểm tra các bản ghi không bị xóa mềm
        $builder->where('deleted_at IS NULL');
        
        return $builder->countAllResults() === 0;
    }
    
    /**
     * Cập nhật thuộc tính từ một mảng dữ liệu
     *
     * @param array $data
     * @return $this
     */
    public function setAttributes(array $data)
    {
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this->attributes) || in_array($key, array_keys($this->casts))) {
                $this->attributes[$key] = $value;
            }
        }
        
        return $this;
    }
    
    /**
     * Lấy quy tắc xác thực
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return $this->validationRules;
    }
    
    /**
     * Lấy thông báo xác thực
     *
     * @return array
     */
    public function getValidationMessages(): array
    {
        return $this->validationMessages;
    }
    
    /**
     * Lấy trạng thái hoạt động của diễn giả
     *
     * @return int
     */
    public function getStatus(): int
    {
        return (int)($this->attributes['status'] ?? 1);
    }
    
    /**
     * Đặt trạng thái hoạt động cho diễn giả
     *
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status)
    {
        $this->attributes['status'] = $status;
        return $this;
    }
    
    /**
     * Kiểm tra diễn giả có đang hoạt động không
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->getStatus() == 1;
    }
} 