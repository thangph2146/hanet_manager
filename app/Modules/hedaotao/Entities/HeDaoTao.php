<?php

namespace App\Modules\hedaotao\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class HeDaoTao extends BaseEntity
{
    protected $tableName = 'he_dao_tao';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'he_dao_tao_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $datamap = [];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Các quy tắc xác thực cụ thể cho HeDaoTao
    protected $validationRules = [
        'ten_he_dao_tao' => 'required|min_length[3]|max_length[100]',
        'ma_he_dao_tao' => 'permit_empty|max_length[20]',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_he_dao_tao' => [
            'required' => 'Tên hệ đào tạo là bắt buộc',
            'min_length' => 'Tên hệ đào tạo phải có ít nhất {param} ký tự',
            'max_length' => 'Tên hệ đào tạo không được vượt quá {param} ký tự',
        ],
        'ma_he_dao_tao' => [
            'max_length' => 'Mã hệ đào tạo không được vượt quá {param} ký tự',
        ],
    ];
    
    /**
     * Lấy ID của hệ đào tạo
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->attributes['he_dao_tao_id'] ?? 0;
    }
    
    /**
     * Lấy tên hệ đào tạo
     *
     * @return string
     */
    public function getTenHeDaoTao(): string
    {
        return $this->attributes['ten_he_dao_tao'] ?? '';
    }
    
    /**
     * Cập nhật tên hệ đào tạo
     *
     * @param string $tenHeDaoTao
     * @return $this
     */
    public function setTenHeDaoTao(string $tenHeDaoTao)
    {
        $this->attributes['ten_he_dao_tao'] = $tenHeDaoTao;
        return $this;
    }
    
    /**
     * Lấy mã hệ đào tạo
     *
     * @return string|null
     */
    public function getMaHeDaoTao(): ?string
    {
        return $this->attributes['ma_he_dao_tao'] ?? null;
    }
    
    /**
     * Cập nhật mã hệ đào tạo
     *
     * @param string|null $maHeDaoTao
     * @return $this
     */
    public function setMaHeDaoTao(?string $maHeDaoTao)
    {
        $this->attributes['ma_he_dao_tao'] = $maHeDaoTao;
        return $this;
    }
    
    /**
     * Kiểm tra hệ đào tạo có đang hoạt động không
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? false);
    }
    
    /**
     * Đặt trạng thái hoạt động cho hệ đào tạo
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
     * Kiểm tra hệ đào tạo có đang trong thùng rác không
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