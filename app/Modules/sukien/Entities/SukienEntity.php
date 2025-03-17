<?php

namespace App\Modules\sukien\Entities;

use CodeIgniter\Entity\Entity;

class SukienEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = [
        'bat_dau_dang_ky', 
        'ket_thuc_dang_ky', 
        'gio_bat_dau', 
        'gio_ket_thuc', 
        'created_at', 
        'updated_at', 
        'deleted_at'
    ];
    protected $casts   = [
        'id'                     => 'int',
        'loai_su_kien_id'        => 'int',
        'nguoi_tao_id'           => 'int',
        'so_luong_tham_gia'      => 'int',
        'so_luong_dien_gia'      => 'int',
        'so_luot_xem'            => 'int',
        'status'                 => 'int',
        'bin'                    => 'int',
    ];
    
    /**
     * Lấy tên sự kiện
     */
    public function getTenSuKien()
    {
        return $this->attributes['ten_su_kien'] ?? '';
    }
    
    /**
     * Đặt tên sự kiện
     */
    public function setTenSuKien(string $ten)
    {
        $this->attributes['ten_su_kien'] = $ten;
        
        return $this;
    }
    
    /**
     * Lấy mô tả sự kiện
     */
    public function getMoTaSuKien()
    {
        return $this->attributes['mo_ta_su_kien'] ?? '';
    }
    
    /**
     * Đặt mô tả sự kiện
     */
    public function setMoTaSuKien(string $moTa)
    {
        $this->attributes['mo_ta_su_kien'] = $moTa;
        
        return $this;
    }
    
    /**
     * Lấy ID người tạo sự kiện
     */
    public function getNguoiTaoId()
    {
        return $this->attributes['nguoi_tao_id'] ?? 0;
    }
    
    /**
     * Đặt ID người tạo sự kiện
     */
    public function setNguoiTaoId(int $nguoiTaoId)
    {
        $this->attributes['nguoi_tao_id'] = $nguoiTaoId;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem người dùng có phải là người tạo sự kiện không
     */
    public function isCreatedBy(int $nguoiDungId)
    {
        return $this->getNguoiTaoId() === $nguoiDungId;
    }
    
    /**
     * Kiểm tra xem người dùng có quyền chỉnh sửa sự kiện không
     * (là người tạo hoặc có quyền admin)
     */
    public function canEdit(int $nguoiDungId, bool $isAdmin = false)
    {
        // Nếu là admin, luôn có quyền chỉnh sửa
        if ($isAdmin) {
            return true;
        }
        
        // Nếu là người tạo, có quyền chỉnh sửa
        return $this->isCreatedBy($nguoiDungId);
    }
    
    /**
     * Lấy chi tiết sự kiện
     */
    public function getChiTietSuKien()
    {
        return $this->attributes['chi_tiet_su_kien'] ?? '';
    }
    
    /**
     * Đặt chi tiết sự kiện
     */
    public function setChiTietSuKien(string $chiTiet)
    {
        $this->attributes['chi_tiet_su_kien'] = $chiTiet;
        
        return $this;
    }
    
    /**
     * Lấy ID loại sự kiện
     */
    public function getLoaiSuKienId()
    {
        return $this->attributes['loai_su_kien_id'] ?? 0;
    }
    
    /**
     * Đặt ID loại sự kiện
     */
    public function setLoaiSuKienId(int $loaiSuKienId)
    {
        $this->attributes['loai_su_kien_id'] = $loaiSuKienId;
        
        return $this;
    }
    
    /**
     * Lấy thời gian bắt đầu đăng ký
     */
    public function getBatDauDangKy()
    {
        return $this->attributes['bat_dau_dang_ky'] ?? null;
    }
    
    /**
     * Đặt thời gian bắt đầu đăng ký
     */
    public function setBatDauDangKy(string $batDauDangKy)
    {
        $this->attributes['bat_dau_dang_ky'] = $batDauDangKy;
        
        return $this;
    }
    
    /**
     * Lấy thời gian kết thúc đăng ký
     */
    public function getKetThucDangKy()
    {
        return $this->attributes['ket_thuc_dang_ky'] ?? null;
    }
    
    /**
     * Đặt thời gian kết thúc đăng ký
     */
    public function setKetThucDangKy(string $ketThucDangKy)
    {
        $this->attributes['ket_thuc_dang_ky'] = $ketThucDangKy;
        
        return $this;
    }
    
    /**
     * Lấy số lượng tham gia
     */
    public function getSoLuongThamGia()
    {
        return $this->attributes['so_luong_tham_gia'] ?? 0;
    }
    
    /**
     * Đặt số lượng tham gia
     */
    public function setSoLuongThamGia(int $soLuong)
    {
        $this->attributes['so_luong_tham_gia'] = $soLuong;
        
        return $this;
    }
    
    /**
     * Lấy giờ bắt đầu sự kiện
     */
    public function getGioBatDau()
    {
        return $this->attributes['gio_bat_dau'] ?? null;
    }
    
    /**
     * Đặt giờ bắt đầu sự kiện
     */
    public function setGioBatDau(string $gioBatDau)
    {
        $this->attributes['gio_bat_dau'] = $gioBatDau;
        
        return $this;
    }
    
    /**
     * Lấy giờ kết thúc sự kiện
     */
    public function getGioKetThuc()
    {
        return $this->attributes['gio_ket_thuc'] ?? null;
    }
    
    /**
     * Đặt giờ kết thúc sự kiện
     */
    public function setGioKetThuc(string $gioKetThuc)
    {
        $this->attributes['gio_ket_thuc'] = $gioKetThuc;
        
        return $this;
    }
    
    /**
     * Lấy số lượng diễn giả
     */
    public function getSoLuongDienGia()
    {
        return $this->attributes['so_luong_dien_gia'] ?? 0;
    }
    
    /**
     * Đặt số lượng diễn giả
     */
    public function setSoLuongDienGia(int $soLuong)
    {
        $this->attributes['so_luong_dien_gia'] = $soLuong;
        
        return $this;
    }
    
    /**
     * Lấy giới hạn loại người dùng
     */
    public function getGioiHanLoaiNguoiDung()
    {
        return $this->attributes['gioi_han_loai_nguoi_dung'] ?? '';
    }
    
    /**
     * Đặt giới hạn loại người dùng
     */
    public function setGioiHanLoaiNguoiDung(string $gioiHan)
    {
        $this->attributes['gioi_han_loai_nguoi_dung'] = $gioiHan;
        
        return $this;
    }
    
    /**
     * Lấy từ khóa sự kiện
     */
    public function getTuKhoaSuKien()
    {
        return $this->attributes['tu_khoa_su_kien'] ?? '';
    }
    
    /**
     * Đặt từ khóa sự kiện
     */
    public function setTuKhoaSuKien(string $tuKhoa)
    {
        $this->attributes['tu_khoa_su_kien'] = $tuKhoa;
        
        return $this;
    }
    
    /**
     * Lấy hashtag
     */
    public function getHashtag()
    {
        return $this->attributes['hashtag'] ?? '';
    }
    
    /**
     * Đặt hashtag
     */
    public function setHashtag(string $hashtag)
    {
        $this->attributes['hashtag'] = $hashtag;
        
        return $this;
    }
    
    /**
     * Lấy slug
     */
    public function getSlug()
    {
        return $this->attributes['slug'] ?? '';
    }
    
    /**
     * Đặt slug
     */
    public function setSlug(string $slug)
    {
        $this->attributes['slug'] = $slug;
        
        return $this;
    }
    
    /**
     * Lấy số lượt xem
     */
    public function getSoLuotXem()
    {
        return $this->attributes['so_luot_xem'] ?? 0;
    }
    
    /**
     * Đặt số lượt xem
     */
    public function setSoLuotXem(int $soLuotXem)
    {
        $this->attributes['so_luot_xem'] = $soLuotXem;
        
        return $this;
    }
    
    /**
     * Lấy lịch trình
     */
    public function getLichTrinh()
    {
        return $this->attributes['lich_trinh'] ?? '';
    }
    
    /**
     * Đặt lịch trình
     */
    public function setLichTrinh(string $lichTrinh)
    {
        $this->attributes['lich_trinh'] = $lichTrinh;
        
        return $this;
    }
    
    /**
     * Kiểm tra trạng thái của sự kiện
     */
    public function isActive()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1;
    }
    
    /**
     * Đặt trạng thái của sự kiện
     */
    public function setStatus(int $status)
    {
        $this->attributes['status'] = $status;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem sự kiện có thể đăng ký được không
     */
    public function isRegistrable()
    {
        $now = date('Y-m-d H:i:s');
        $batDauDangKy = $this->getBatDauDangKy();
        $ketThucDangKy = $this->getKetThucDangKy();
        
        return $this->isActive() && 
               $batDauDangKy && $ketThucDangKy && 
               $now >= $batDauDangKy && 
               $now <= $ketThucDangKy;
    }
    
    /**
     * Kiểm tra xem sự kiện đã bắt đầu chưa
     */
    public function hasStarted()
    {
        $now = date('Y-m-d H:i:s');
        $gioBatDau = $this->getGioBatDau();
        
        return $gioBatDau && $now >= $gioBatDau;
    }
    
    /**
     * Kiểm tra xem sự kiện đã kết thúc chưa
     */
    public function hasEnded()
    {
        $now = date('Y-m-d H:i:s');
        $gioKetThuc = $this->getGioKetThuc();
        
        return $gioKetThuc && $now > $gioKetThuc;
    }
    
    /**
     * Lấy trạng thái sự kiện dưới dạng văn bản
     */
    public function getStatusText()
    {
        if (!$this->isActive()) {
            return 'Không hoạt động';
        }
        
        if ($this->hasEnded()) {
            return 'Đã kết thúc';
        }
        
        if ($this->hasStarted()) {
            return 'Đang diễn ra';
        }
        
        if ($this->isRegistrable()) {
            return 'Đang mở đăng ký';
        }
        
        $now = date('Y-m-d H:i:s');
        $batDauDangKy = $this->getBatDauDangKy();
        
        if ($batDauDangKy && $now < $batDauDangKy) {
            return 'Sắp mở đăng ký';
        }
        
        return 'Sắp diễn ra';
    }
}
