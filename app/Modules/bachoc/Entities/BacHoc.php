<?php

namespace App\Modules\bachoc\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class BacHoc extends BaseEntity
{
    protected $tableName = 'bac_hoc';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'bac_hoc_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $datamap = [];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Các quy tắc xác thực cụ thể cho BacHoc
    protected $validationRules = [];
    
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        
        // Define validation rules here to allow dynamic values
        $this->validationRules = [
            'ten_bac_hoc' => 'required|min_length[3]|max_length[100]|is_unique[bac_hoc.ten_bac_hoc,bac_hoc_id,{bac_hoc_id}]',
            'ma_bac_hoc' => 'permit_empty|max_length[20]',
            'status' => 'permit_empty|in_list[0,1]',
            'bin' => 'permit_empty|in_list[0,1]',
        ];
    }
    
    protected $validationMessages = [
        'ten_bac_hoc' => [     
            'required' => 'Tên bậc học là bắt buộc',
            'min_length' => 'Tên bậc học phải có ít nhất {param} ký tự',
            'max_length' => 'Tên bậc học không được vượt quá {param} ký tự',
            'is_unique' => 'Tên bậc học này đã tồn tại',
        ],
        'ma_bac_hoc' => [
            'max_length' => 'Mã bậc học không được vượt quá {param} ký tự',
        ],
    ];
    
    /**
     * Lấy ID của bậc học
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->attributes['bac_hoc_id'] ?? 0;
    }
    
    /**
     * Lấy tên bậc học
     *
     * @return string
     */
    public function getTenBacHoc(): string
    {
        return $this->attributes['ten_bac_hoc'] ?? '';
    }
    
    /**
     * Cập nhật tên bậc học
     *
     * @param string $tenBacHoc
     * @return $this
     */
    public function setTenBacHoc(string $tenBacHoc)
    {
        $this->attributes['ten_bac_hoc'] = $tenBacHoc;
        return $this;
    }
    
    /**
     * Lấy mã bậc học
     *
     * @return string|null
     */
    public function getMaBacHoc(): ?string
    {
        return $this->attributes['ma_bac_hoc'] ?? null;
    }
    
    /**
     * Cập nhật mã bậc học
     *
     * @param string|null $maBacHoc
     * @return $this
     */
    public function setMaBacHoc(?string $maBacHoc)
    {
        $this->attributes['ma_bac_hoc'] = $maBacHoc;
        return $this;
    }
    
    /**
     * Kiểm tra bậc học có đang hoạt động không
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? false);
    }
    
    /**
     * Đặt trạng thái hoạt động cho bậc học
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
     * Kiểm tra bậc học có đang trong thùng rác không
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
     * Lấy ngày tạo dưới dạng chuỗi với định dạng cụ thể
     *
     * @param string $format
     * @return string
     */
    public function getCreatedAtFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        return $this->created_at instanceof Time 
            ? $this->created_at->format($format) 
            : '';
    }
    
    /**
     * Lấy ngày cập nhật dưới dạng chuỗi với định dạng cụ thể
     *
     * @param string $format
     * @return string
     */
    public function getUpdatedAtFormatted(string $format = 'd/m/Y H:i:s'): string
    {
        return $this->updated_at instanceof Time 
            ? $this->updated_at->format($format) 
            : '';
    }
} 