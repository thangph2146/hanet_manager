<?php

namespace App\Modules\sukien\Entities;

use CodeIgniter\Entity\Entity;

class DangKySukienEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['ngay_dang_ky', 'created_at', 'updated_at'];
    protected $casts   = [
        'id'               => 'int',
        'su_kien_id'       => 'int',
        'nguoi_dung_id'    => 'int',
        'status'           => 'int',
        'bin'              => 'int',
        'face_verified'    => 'boolean',
    ];
    
    // Các loại người đăng ký
    const LOAI_KHACH = 'khach';
    const LOAI_SINH_VIEN = 'sinh_vien';
    const LOAI_GIANG_VIEN = 'giang_vien';
    
    /**
     * Lấy ID sự kiện
     */
    public function getSuKienId()
    {
        return $this->attributes['su_kien_id'] ?? 0;
    }
    
    /**
     * Đặt ID sự kiện
     */
    public function setSuKienId(int $suKienId)
    {
        $this->attributes['su_kien_id'] = $suKienId;
        
        return $this;
    }
    
    /**
     * Lấy ID người dùng
     */
    public function getNguoiDungId()
    {
        return $this->attributes['nguoi_dung_id'] ?? 0;
    }
    
    /**
     * Đặt ID người dùng
     */
    public function setNguoiDungId(int $nguoiDungId)
    {
        $this->attributes['nguoi_dung_id'] = $nguoiDungId;
        
        return $this;
    }
    
    /**
     * Lấy ngày đăng ký
     */
    public function getNgayDangKy()
    {
        return $this->attributes['ngay_dang_ky'] ?? null;
    }
    
    /**
     * Đặt ngày đăng ký
     */
    public function setNgayDangKy(string $ngayDangKy)
    {
        $this->attributes['ngay_dang_ky'] = $ngayDangKy;
        
        return $this;
    }
    
    /**
     * Lấy nội dung góp ý
     */
    public function getNoiDungGopY()
    {
        return $this->attributes['noi_dung_gop_y'] ?? '';
    }
    
    /**
     * Đặt nội dung góp ý
     */
    public function setNoiDungGopY(string $noiDungGopY)
    {
        $this->attributes['noi_dung_gop_y'] = $noiDungGopY;
        
        return $this;
    }
    
    /**
     * Lấy nguồn giới thiệu
     */
    public function getNguonGioiThieu()
    {
        return $this->attributes['nguon_gioi_thieu'] ?? '';
    }
    
    /**
     * Đặt nguồn giới thiệu
     */
    public function setNguonGioiThieu(string $nguonGioiThieu)
    {
        $this->attributes['nguon_gioi_thieu'] = $nguonGioiThieu;
        
        return $this;
    }
    
    /**
     * Lấy loại người dùng
     */
    public function getLoaiNguoiDung()
    {
        return $this->attributes['loai_nguoi_dung'] ?? '';
    }
    
    /**
     * Đặt loại người dùng
     */
    public function setLoaiNguoiDung(string $loaiNguoiDung)
    {
        $this->attributes['loai_nguoi_dung'] = $loaiNguoiDung;
        
        return $this;
    }
    
    /**
     * Lấy phân loại người đăng ký (khách, sinh viên, giảng viên)
     */
    public function getLoaiNguoiDangKy()
    {
        return $this->attributes['loai_nguoi_dang_ky'] ?? '';
    }
    
    /**
     * Đặt phân loại người đăng ký (khách, sinh viên, giảng viên)
     */
    public function setLoaiNguoiDangKy(string $loaiNguoiDangKy)
    {
        $this->attributes['loai_nguoi_dang_ky'] = $loaiNguoiDangKy;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem người đăng ký có phải là khách không
     */
    public function isKhach()
    {
        return $this->getLoaiNguoiDangKy() === self::LOAI_KHACH;
    }
    
    /**
     * Kiểm tra xem người đăng ký có phải là sinh viên không
     */
    public function isSinhVien()
    {
        return $this->getLoaiNguoiDangKy() === self::LOAI_SINH_VIEN;
    }
    
    /**
     * Kiểm tra xem người đăng ký có phải là giảng viên không
     */
    public function isGiangVien()
    {
        return $this->getLoaiNguoiDangKy() === self::LOAI_GIANG_VIEN;
    }
    
    /**
     * Lấy trình độ học vấn
     */
    public function getTrinhDoHocVan()
    {
        return $this->attributes['trinh_do_hoc_van'] ?? '';
    }
    
    /**
     * Đặt trình độ học vấn
     */
    public function setTrinhDoHocVan(string $trinhDoHocVan)
    {
        $this->attributes['trinh_do_hoc_van'] = $trinhDoHocVan;
        
        return $this;
    }
    
    /**
     * Lấy đường dẫn hình ảnh khuôn mặt
     */
    public function getFaceImagePath()
    {
        return $this->attributes['face_image_path'] ?? '';
    }
    
    /**
     * Đặt đường dẫn hình ảnh khuôn mặt
     */
    public function setFaceImagePath(string $imagePath)
    {
        $this->attributes['face_image_path'] = $imagePath;
        
        return $this;
    }
    
    /**
     * Lấy dữ liệu Face ID
     */
    public function getFaceId()
    {
        return $this->attributes['face_id'] ?? '';
    }
    
    /**
     * Đặt dữ liệu Face ID
     */
    public function setFaceId(string $faceId)
    {
        $this->attributes['face_id'] = $faceId;
        
        return $this;
    }
    
    /**
     * Kiểm tra xem khuôn mặt đã được xác minh chưa
     */
    public function isFaceVerified()
    {
        return isset($this->attributes['face_verified']) && $this->attributes['face_verified'] === true;
    }
    
    /**
     * Đánh dấu khuôn mặt đã được xác minh
     */
    public function setFaceVerified(bool $verified = true)
    {
        $this->attributes['face_verified'] = $verified;
        
        return $this;
    }
    
    /**
     * Kiểm tra trạng thái của đăng ký
     */
    public function isActive()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1;
    }
    
    /**
     * Đặt trạng thái của đăng ký
     */
    public function setStatus(int $status)
    {
        $this->attributes['status'] = $status;
        
        return $this;
    }
    
    /**
     * Lấy trạng thái đăng ký dưới dạng văn bản
     */
    public function getStatusText()
    {
        if (!isset($this->attributes['status'])) {
            return 'Không xác định';
        }
        
        switch ($this->attributes['status']) {
            case 1:
                return 'Đã xác nhận';
            case 0:
                return 'Chờ xác nhận';
            case -1:
                return 'Đã hủy';
            default:
                return 'Không xác định';
        }
    }
    
    /**
     * Kiểm tra xem đăng ký đã được xác nhận chưa
     */
    public function isConfirmed()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1;
    }
    
    /**
     * Kiểm tra xem đăng ký đã bị hủy chưa
     */
    public function isCancelled()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == -1;
    }
    
    /**
     * Kiểm tra xem đăng ký đang chờ xác nhận không
     */
    public function isPending()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 0;
    }
}
