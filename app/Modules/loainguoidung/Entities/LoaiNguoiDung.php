<?php

namespace App\Modules\loainguoidung\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class LoaiNguoiDung extends BaseEntity
{
    protected $tableName = 'loai_nguoi_dung';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'loai_nguoi_dung_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $datamap = [];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Các quy tắc xác thực cụ thể cho LoaiNguoiDung
    protected $validationRules = [
        'ten_loai' => 'required|min_length[3]|max_length[50]',
        'mo_ta' => 'permit_empty|max_length[1000]',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_loai' => [
            'required' => 'Tên loại người dùng là bắt buộc',
            'min_length' => 'Tên loại người dùng phải có ít nhất {param} ký tự',
            'max_length' => 'Tên loại người dùng không được vượt quá {param} ký tự',
        ],
    ];
    
    /**
     * Lấy ID của loại người dùng
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->attributes['loai_nguoi_dung_id'] ?? 0;
    }
    
    /**
     * Lấy tên loại người dùng
     *
     * @return string
     */
    public function getTenLoai(): string
    {
        return $this->attributes['ten_loai'] ?? '';
    }
    
    /**
     * Cập nhật tên loại người dùng
     *
     * @param string $tenLoai
     * @return $this
     */
    public function setTenLoai(string $tenLoai)
    {
        $this->attributes['ten_loai'] = $tenLoai;
        return $this;
    }
    
    /**
     * Lấy mô tả loại người dùng
     *
     * @return string|null
     */
    public function getMoTa(): ?string
    {
        return $this->attributes['mo_ta'] ?? null;
    }
    
    /**
     * Cập nhật mô tả loại người dùng
     *
     * @param string|null $moTa
     * @return $this
     */
    public function setMoTa(?string $moTa)
    {
        $this->attributes['mo_ta'] = $moTa;
        return $this;
    }
    
    /**
     * Kiểm tra loại người dùng có đang hoạt động không
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? false);
    }
    
    /**
     * Đặt trạng thái hoạt động cho loại người dùng
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
     * Kiểm tra loại người dùng có đang trong thùng rác không
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
