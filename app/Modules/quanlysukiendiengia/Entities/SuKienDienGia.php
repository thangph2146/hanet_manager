<?php

namespace App\Modules\quanlysukiendiengia\Entities;

use App\Entities\BaseEntity;
use CodeIgniter\I18n\Time;
use App\Modules\quanlysukien\Entities\SuKien;
use App\Modules\quanlydiengia\Entities\DienGia;

class SuKienDienGia extends BaseEntity
{
    protected $tableName = 'su_kien_dien_gia';
    protected $primaryKey = 'su_kien_dien_gia_id';
    
    protected $dates = [
        'thoi_gian_trinh_bay',
        'thoi_gian_ket_thuc',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    protected $casts = [
        'su_kien_dien_gia_id' => 'int',
        'su_kien_id' => 'int',
        'dien_gia_id' => 'int',
        'thu_tu' => 'int',
        'thoi_luong_phut' => 'int',
        'tai_lieu_dinh_kem' => 'json',
        'hien_thi_cong_khai' => 'boolean',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
    
    // Định nghĩa các ràng buộc unique
    protected $uniqueKeys = [
        'uk_sukien_diengia' => ['su_kien_id', 'dien_gia_id']
    ];
    
    // Các quy tắc xác thực cụ thể cho SuKienDienGia
    protected $validationRules = [
        'su_kien_id' => [
            'rules' => 'required|integer|is_not_unique[su_kien.su_kien_id]',
            'label' => 'ID sự kiện'
        ],
        'dien_gia_id' => [
            'rules' => 'required|integer|is_not_unique[dien_gia.dien_gia_id]',
            'label' => 'ID diễn giả'
        ],
        'thu_tu' => [
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]',
            'label' => 'Thứ tự'
        ],
        'vai_tro' => [
            'rules' => 'permit_empty|max_length[100]',
            'label' => 'Vai trò'
        ],
        'mo_ta' => [
            'rules' => 'permit_empty',
            'label' => 'Mô tả'
        ],
        'thoi_gian_trinh_bay' => [
            'rules' => 'permit_empty|valid_date',
            'label' => 'Thời gian trình bày'
        ],
        'thoi_gian_ket_thuc' => [
            'rules' => 'permit_empty|valid_date',
            'label' => 'Thời gian kết thúc'
        ],
        'thoi_luong_phut' => [
            'rules' => 'permit_empty|integer|greater_than_equal_to[0]',
            'label' => 'Thời lượng (phút)'
        ],
        'tieu_de_trinh_bay' => [
            'rules' => 'permit_empty|max_length[255]',
            'label' => 'Tiêu đề trình bày'
        ],
        'tai_lieu_dinh_kem' => [
            'rules' => 'permit_empty',
            'label' => 'Tài liệu đính kèm'
        ],
        'trang_thai_tham_gia' => [
            'rules' => 'permit_empty|in_list[xac_nhan,cho_xac_nhan,tu_choi,khong_lien_he_duoc]',
            'label' => 'Trạng thái tham gia'
        ],
        'hien_thi_cong_khai' => [
            'rules' => 'permit_empty|in_list[0,1]',
            'label' => 'Hiển thị công khai'
        ],
        'ghi_chu' => [
            'rules' => 'permit_empty',
            'label' => 'Ghi chú'
        ]
    ];
    
    protected $validationMessages = [
        'su_kien_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên',
            'is_not_unique' => '{field} không tồn tại trong hệ thống'
        ],
        'dien_gia_id' => [
            'required' => '{field} là bắt buộc',
            'integer' => '{field} phải là số nguyên',
            'is_not_unique' => '{field} không tồn tại trong hệ thống'
        ],
        'thu_tu' => [
            'integer' => '{field} phải là số nguyên',
            'greater_than_equal_to' => '{field} không được nhỏ hơn 0'
        ],
        'vai_tro' => [
            'max_length' => '{field} không được vượt quá 100 ký tự'
        ],
        'thoi_gian_trinh_bay' => [
            'valid_date' => '{field} không hợp lệ'
        ],
        'thoi_gian_ket_thuc' => [
            'valid_date' => '{field} không hợp lệ'
        ],
        'thoi_luong_phut' => [
            'integer' => '{field} phải là số nguyên',
            'greater_than_equal_to' => '{field} không được nhỏ hơn 0'
        ],
        'tieu_de_trinh_bay' => [
            'max_length' => '{field} không được vượt quá 255 ký tự'
        ],
        'trang_thai_tham_gia' => [
            'in_list' => '{field} không hợp lệ'
        ],
        'hien_thi_cong_khai' => [
            'in_list' => '{field} không hợp lệ'
        ]
    ];
    
    /**
     * Lấy ID
     *
     * @return int
     */
    public function getId(): int
    {
        return (int)($this->attributes[$this->primaryKey] ?? 0);
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
     * Lấy ID diễn giả
     *
     * @return int
     */
    public function getDienGiaId(): int
    {
        return (int)($this->attributes['dien_gia_id'] ?? 0);
    }
    
    /**
     * Get the speaker name
     * 
     * @return string
     */
    public function getTenDienGia(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia) {
            return $dienGia->getTenDienGia();
        }
        
        return "Unknown Speaker";
    }
    
    /**
     * Get the display name combining event name and speaker name
     * 
     * @return string
     */
    public function getTenSuKienDienGia(){
        $suKienId = $this->getSuKienId();
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instances to find data instead of trying to use find() on entity objects
        $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        
        $suKien = $suKienModel->find($suKienId);
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($suKien && $dienGia) {
            return $suKien->getTenSuKien() . " - " . $dienGia->getTenDienGia();
        }
        
        return "Unknown Event - Unknown Speaker";
    }

    /**
     * Get the speaker position/title
     * 
     * @return string
     */
    public function getChucDanh(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia && method_exists($dienGia, 'getChucDanh')) {
            return $dienGia->getChucDanh();
        }
        
        return "";
    }
    
    /**
     * Get the organization of the speaker
     * 
     * @return string
     */
    public function getToChuc(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia && method_exists($dienGia, 'getToChuc')) {
            return $dienGia->getToChuc();
        }
        
        return "";
    }
    
    /**
     * Get the speaker introduction
     * 
     * @return string
     */
    public function getGioiThieu(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia && method_exists($dienGia, 'getGioiThieu')) {
            return $dienGia->getGioiThieu();
        }
        
        return "";
    }
    
    /**
     * Get the speaker avatar
     * 
     * @return string
     */
    public function getAvatar(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia && method_exists($dienGia, 'getAvatar')) {
            return $dienGia->getAvatar();
        }
        
        return "";
    }
    
    /**
     * Get the speaker email
     * 
     * @return string
     */
    public function getEmail(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia && method_exists($dienGia, 'getEmail')) {
            return $dienGia->getEmail();
        }
        
        return "";
    }
    
    /**
     * Get the speaker phone number
     * 
     * @return string
     */
    public function getDienThoai(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia && method_exists($dienGia, 'getDienThoai')) {
            return $dienGia->getDienThoai();
        }
        
        return "";
    }
    
    /**
     * Get the speaker website
     * 
     * @return string
     */
    public function getWebsite(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia && method_exists($dienGia, 'getWebsite')) {
            return $dienGia->getWebsite();
        }
        
        return "";
    }
    
    /**
     * Get the speaker specialization
     * 
     * @return string
     */
    public function getChuyenMon(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia && method_exists($dienGia, 'getChuyenMon')) {
            return $dienGia->getChuyenMon();
        }
        
        return "";
    }
    
    /**
     * Get the speaker achievements
     * 
     * @return string
     */
    public function getThanhTuu(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia && method_exists($dienGia, 'getThanhTuu')) {
            return $dienGia->getThanhTuu();
        }
        
        return "";
    }
    
    /**
     * Get the speaker social media profiles
     * 
     * @return array|string
     */
    public function getMangXaHoi(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia && method_exists($dienGia, 'getMangXaHoi')) {
            return $dienGia->getMangXaHoi();
        }
        
        return "";
    }
    
    /**
     * Get the speaker status
     * 
     * @return string|int
     */
    public function getStatus(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to find data
        $dienGiaModel = new \App\Modules\diengia\Models\DienGiaModel();
        $dienGia = $dienGiaModel->find($dienGiaId);
        
        if ($dienGia && method_exists($dienGia, 'getStatus')) {
            return $dienGia->getStatus();
        }
        
        return 1; // Assuming 1 is active
    }
    
    /**
     * Get number of events the speaker is participating in
     * 
     * @return int
     */
    public function getSoSuKienThamGia(){
        $dienGiaId = $this->getDienGiaId();
        
        // Use model instance to count
        $suKienDienGiaModel = new \App\Modules\sukiendiengia\Models\SuKienDienGiaModel();
        return $suKienDienGiaModel->where('dien_gia_id', $dienGiaId)->countAllResults();
    }

    /**
     * Lấy thứ tự
     *
     * @return int
     */
    public function getThuTu(): int
    {
        return (int)($this->attributes['thu_tu'] ?? 0);
    }
    
    /**
     * Lấy vai trò
     *
     * @return string|null
     */
    public function getVaiTro(): ?string
    {
        return $this->attributes['vai_tro'] ?? null;
    }
    
    /**
     * Lấy mô tả
     *
     * @return string|null
     */
    public function getMoTa(): ?string
    {
        return $this->attributes['mo_ta'] ?? null;
    }
    
    /**
     * Lấy thời gian trình bày
     *
     * @return Time|null
     */
    public function getThoiGianTrinhBay(): ?Time
    {
        if (empty($this->attributes['thoi_gian_trinh_bay'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_trinh_bay'] instanceof Time 
            ? $this->attributes['thoi_gian_trinh_bay'] 
            : new Time($this->attributes['thoi_gian_trinh_bay']);
    }
    
    /**
     * Lấy thời gian kết thúc
     *
     * @return Time|null
     */
    public function getThoiGianKetThuc(): ?Time
    {
        if (empty($this->attributes['thoi_gian_ket_thuc'])) {
            return null;
        }
        
        return $this->attributes['thoi_gian_ket_thuc'] instanceof Time 
            ? $this->attributes['thoi_gian_ket_thuc'] 
            : new Time($this->attributes['thoi_gian_ket_thuc']);
    }
    
    /**
     * Lấy thời lượng (phút)
     *
     * @return int|null
     */
    public function getThoiLuongPhut(): ?int
    {
        return isset($this->attributes['thoi_luong_phut']) ? (int)$this->attributes['thoi_luong_phut'] : null;
    }
    
    /**
     * Lấy tiêu đề trình bày
     *
     * @return string|null
     */
    public function getTieuDeTrinhBay(): ?string
    {
        return $this->attributes['tieu_de_trinh_bay'] ?? null;
    }
    
    /**
     * Lấy tài liệu đính kèm
     *
     * @return array|null
     */
    public function getTaiLieuDinhKem(): ?array
    {
        $taiLieu = $this->attributes['tai_lieu_dinh_kem'] ?? null;
        
        if (is_string($taiLieu)) {
            return json_decode($taiLieu, true);
        }
        
        return $taiLieu;
    }
    
    /**
     * Lấy trạng thái tham gia
     *
     * @return string
     */
    public function getTrangThaiThamGia(): string
    {
        return $this->attributes['trang_thai_tham_gia'] ?? 'cho_xac_nhan';
    }
    
    /**
     * Lấy trạng thái hiển thị công khai
     *
     * @return bool
     */
    public function getHienThiCongKhai(): bool
    {
        return (bool)($this->attributes['hien_thi_cong_khai'] ?? true);
    }
    
    /**
     * Lấy ghi chú
     *
     * @return string|null
     */
    public function getGhiChu(): ?string
    {
        return $this->attributes['ghi_chu'] ?? null;
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
     * Lấy thời gian trình bày đã định dạng
     *
     * @param string $format Định dạng thời gian
     * @return string|null
     */
    public function getThoiGianTrinhBayFormatted(string $format = 'd/m/Y H:i'): ?string
    {
        $time = $this->getThoiGianTrinhBay();
        return $time ? $time->format($format) : null;
    }
    
    /**
     * Lấy thời gian kết thúc đã định dạng
     *
     * @param string $format Định dạng thời gian
     * @return string|null
     */
    public function getThoiGianKetThucFormatted(string $format = 'd/m/Y H:i'): ?string
    {
        $time = $this->getThoiGianKetThuc();
        return $time ? $time->format($format) : null;
    }
    
    /**
     * Lấy ngày tạo đã định dạng
     *
     * @return string|null
     */
    public function getCreatedAtFormatted(): ?string
    {
        $createdAt = $this->getCreatedAt();
        return $createdAt ? $createdAt->format('d/m/Y H:i:s') : null;
    }
    
    /**
     * Lấy ngày cập nhật đã định dạng
     *
     * @return string|null
     */
    public function getUpdatedAtFormatted(): ?string
    {
        $updatedAt = $this->getUpdatedAt();
        return $updatedAt ? $updatedAt->format('d/m/Y H:i:s') : null;
    }
    
    /**
     * Lấy ngày xóa đã định dạng
     *
     * @return string|null
     */
    public function getDeletedAtFormatted(): ?string
    {
        $deletedAt = $this->getDeletedAt();
        return $deletedAt ? $deletedAt->format('d/m/Y H:i:s') : null;
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
     * Lấy thông tin tên trạng thái tham gia để hiển thị
     *
     * @return string
     */
    public function getTrangThaiThamGiaText(): string
    {
        $trangThai = $this->getTrangThaiThamGia();
        
        $trangThaiMap = [
            'xac_nhan' => 'Đã xác nhận',
            'cho_xac_nhan' => 'Chờ xác nhận',
            'tu_choi' => 'Từ chối',
            'khong_lien_he_duoc' => 'Không liên hệ được'
        ];
        
        return $trangThaiMap[$trangThai] ?? 'Chờ xác nhận';
    }
}