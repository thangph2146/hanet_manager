<?php

namespace App\Modules\khoahoc\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;

class KhoaHoc extends BaseEntity
{
    protected $tableName = 'khoa_hoc';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    
    protected $casts = [
        'khoa_hoc_id' => 'int',
        'nam_bat_dau' => 'int',
        'nam_ket_thuc' => 'int',
        'phong_khoa_id' => 'int',
        'status' => 'int',
        'bin' => 'int',
    ];
    
    protected $datamap = [];
    
    protected $jsonFields = [];
    
    protected $hiddenFields = [
        'deleted_at',
    ];
    
    // Các quy tắc xác thực cụ thể cho KhoaHoc
    protected $validationRules = [];
    
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        
        // Define validation rules here to allow dynamic values
        $this->validationRules = [
            'ten_khoa_hoc' => 'required|min_length[3]|max_length[100]',
            'nam_bat_dau' => 'permit_empty|numeric|less_than_equal_to['.date('Y').']',
            'nam_ket_thuc' => 'permit_empty|numeric|greater_than_equal_to[nam_bat_dau]',
            'status' => 'permit_empty|in_list[0,1]',
            'bin' => 'permit_empty|in_list[0,1]',
        ];
    }
    
    protected $validationMessages = [
        'ten_khoa_hoc' => [     
            'required' => 'Tên khóa học là bắt buộc',
            'min_length' => 'Tên khóa học phải có ít nhất {param} ký tự',
            'max_length' => 'Tên khóa học không được vượt quá {param} ký tự',
        ],
        'nam_bat_dau' => [
            'numeric' => 'Năm bắt đầu phải là số',
            'less_than_equal_to' => 'Năm bắt đầu không được lớn hơn năm hiện tại'
        ],
        'nam_ket_thuc' => [
            'numeric' => 'Năm kết thúc phải là số',
            'greater_than_equal_to' => 'Năm kết thúc phải lớn hơn hoặc bằng năm bắt đầu'
        ],
        'phong_khoa_id' => [
            'numeric' => 'ID phòng khoa phải là số',
            'is_natural' => 'ID phòng khoa phải là số tự nhiên'
        ]
    ];
    
    /**
     * Lấy ID của khóa học
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)$this->attributes['khoa_hoc_id'] ?? 0;
    }
    
    /**
     * Lấy tên khóa học
     *
     * @return string
     */
    public function getTenKhoaHoc(): string
    {
        return $this->attributes['ten_khoa_hoc'] ?? '';
    }
    
    /**
     * Cập nhật tên khóa học
     *
     * @param string $tenKhoaHoc
     * @return $this
     */
    public function setTenKhoaHoc(string $tenKhoaHoc)
    {
        $this->attributes['ten_khoa_hoc'] = $tenKhoaHoc;
        return $this;
    }
    
    /**
     * Lấy năm bắt đầu của khóa học
     *
     * @return int|null
     */
    public function getNamBatDau(): ?int
    {
        return $this->attributes['nam_bat_dau'] ?? null;
    }
    
    /**
     * Cập nhật năm bắt đầu của khóa học
     *
     * @param int|null $namBatDau
     * @return $this
     */
    public function setNamBatDau(?int $namBatDau)
    {
        $this->attributes['nam_bat_dau'] = $namBatDau;
        return $this;
    }
    
    /**
     * Lấy năm kết thúc của khóa học
     *
     * @return int|null
     */
    public function getNamKetThuc(): ?int
    {
        return $this->attributes['nam_ket_thuc'] ?? null;
    }
    
    /**
     * Cập nhật năm kết thúc của khóa học
     *
     * @param int|null $namKetThuc
     * @return $this
     */
    public function setNamKetThuc(?int $namKetThuc)
    {
        $this->attributes['nam_ket_thuc'] = $namKetThuc;
        return $this;
    }
    
    /**
     * Lấy ID phòng khoa
     *
     * @return int|null
     */
    public function getPhongKhoaId(): ?int
    {
        return $this->attributes['phong_khoa_id'] ?? null;
    }
    
    /**
     * Cập nhật ID phòng khoa
     *
     * @param int|null $phongKhoaId
     * @return $this
     */
    public function setPhongKhoaId(?int $phongKhoaId)
    {
        $this->attributes['phong_khoa_id'] = $phongKhoaId;
        return $this;
    }
    
    /**
     * Kiểm tra khóa học có đang hoạt động không
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)($this->attributes['status'] ?? false);
    }
    
    /**
     * Đặt trạng thái hoạt động cho khóa học
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
     * Kiểm tra khóa học có đang trong thùng rác không
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
    
    /**
     * Lấy thời gian học của khóa học (dạng chuỗi)
     * 
     * @return string
     */
    public function getThoiGianHoc(): string
    {
        if (!$this->getNamBatDau() && !$this->getNamKetThuc()) {
            return 'Chưa xác định';
        }
        
        if ($this->getNamBatDau() && !$this->getNamKetThuc()) {
            return 'Từ năm ' . $this->getNamBatDau();
        }
        
        if (!$this->getNamBatDau() && $this->getNamKetThuc()) {
            return 'Đến năm ' . $this->getNamKetThuc();
        }
        
        return $this->getNamBatDau() . ' - ' . $this->getNamKetThuc();
    }
}
