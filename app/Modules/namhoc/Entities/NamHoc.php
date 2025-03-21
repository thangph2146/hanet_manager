<?php

namespace App\Modules\namhoc\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class NamHoc extends BaseEntity
{
    protected $tableName = 'nam_hoc';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'ngay_bat_dau',
        'ngay_ket_thuc'
    ];
    
    protected $casts = [
        'nam_hoc_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $datamap = [];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Các quy tắc xác thực cụ thể cho NamHoc
    protected $validationRules = [
        'ten_nam_hoc' => 'required|min_length[3]|max_length[50]',
        'ngay_bat_dau' => 'permit_empty|valid_date',
        'ngay_ket_thuc' => 'permit_empty|valid_date',
        'status' => 'permit_empty|in_list[0,1]',
        'bin' => 'permit_empty|in_list[0,1]',
    ];
    
    protected $validationMessages = [
        'ten_nam_hoc' => [
            'required' => 'Tên năm học là bắt buộc',
            'min_length' => 'Tên năm học phải có ít nhất {param} ký tự',
            'max_length' => 'Tên năm học không được vượt quá {param} ký tự',
        ],
        'ngay_bat_dau' => [
            'valid_date' => 'Ngày bắt đầu không hợp lệ',
        ],
        'ngay_ket_thuc' => [
            'valid_date' => 'Ngày kết thúc không hợp lệ',
        ],
    ];
    
    /**
     * Lấy ID của năm học
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->attributes['nam_hoc_id'] ?? 0;
    }
    
    /**
     * Lấy tên năm học
     *
     * @return string
     */
    public function getTenNamHoc(): string
    {
        return $this->attributes['ten_nam_hoc'] ?? '';
    }
    
    /**
     * Cập nhật tên năm học
     *
     * @param string $tenNamHoc
     * @return $this
     */
    public function setTenNamHoc(string $tenNamHoc)
    {
        $this->attributes['ten_nam_hoc'] = $tenNamHoc;
        return $this;
    }
    
    /**
     * Lấy ngày bắt đầu
     *
     * @return Time|null
     */
    public function getNgayBatDau(): ?Time
    {
        if (empty($this->attributes['ngay_bat_dau'])) {
            return null;
        }
        
        if ($this->attributes['ngay_bat_dau'] instanceof Time) {
            return $this->attributes['ngay_bat_dau'];
        }
        
        // Chuyển đổi chuỗi thành đối tượng Time
        try {
            return new Time($this->attributes['ngay_bat_dau']);
        } catch (\Exception $e) {
            log_message('error', 'Error converting ngay_bat_dau to Time: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Cập nhật ngày bắt đầu
     *
     * @param string|null $ngayBatDau
     * @return $this
     */
    public function setNgayBatDau(?string $ngayBatDau)
    {
        $this->attributes['ngay_bat_dau'] = $ngayBatDau;
        return $this;
    }
    
    /**
     * Lấy ngày kết thúc
     *
     * @return Time|null
     */
    public function getNgayKetThuc(): ?Time
    {
        if (empty($this->attributes['ngay_ket_thuc'])) {
            return null;
        }
        
        if ($this->attributes['ngay_ket_thuc'] instanceof Time) {
            return $this->attributes['ngay_ket_thuc'];
        }
        
        // Chuyển đổi chuỗi thành đối tượng Time
        try {
            return new Time($this->attributes['ngay_ket_thuc']);
        } catch (\Exception $e) {
            log_message('error', 'Error converting ngay_ket_thuc to Time: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Cập nhật ngày kết thúc
     *
     * @param string|null $ngayKetThuc
     * @return $this
     */
    public function setNgayKetThuc(?string $ngayKetThuc)
    {
        $this->attributes['ngay_ket_thuc'] = $ngayKetThuc;
        return $this;
    }
    
    /**
     * Kiểm tra năm học có đang hoạt động không
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? false);
    }
    
    /**
     * Đặt trạng thái hoạt động cho năm học
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
     * Kiểm tra năm học có đang trong thùng rác không
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
     * Lấy ngày bắt đầu dưới dạng chuỗi với định dạng cụ thể
     *
     * @param string $format
     * @return string
     */
    public function getNgayBatDauFormatted(string $format = 'd/m/Y'): string
    {
        return $this->ngay_bat_dau instanceof Time 
            ? $this->ngay_bat_dau->format($format) 
            : '';
    }
    
    /**
     * Lấy ngày kết thúc dưới dạng chuỗi với định dạng cụ thể
     *
     * @param string $format
     * @return string
     */
    public function getNgayKetThucFormatted(string $format = 'd/m/Y'): string
    {
        return $this->ngay_ket_thuc instanceof Time 
            ? $this->ngay_ket_thuc->format($format) 
            : '';
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