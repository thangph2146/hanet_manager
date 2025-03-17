<?php

namespace App\Modules\sukien\Entities;

use CodeIgniter\Entity\Entity;

class LoaiSukienEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'            => 'int',
        'nguoi_tao_id'  => 'int',
        'status'        => 'int',
        'bin'           => 'int',
        'tong_su_kien'  => 'int',
    ];
    
    /**
     * Lấy tổng số sự kiện thuộc loại này
     */
    public function getTongSuKien()
    {
        return $this->attributes['tong_su_kien'] ?? 0;
    }
    
    /**
     * Đặt tổng số sự kiện thuộc loại này
     */
    public function setTongSuKien(int $tongSuKien)
    {
        $this->attributes['tong_su_kien'] = $tongSuKien;
        
        return $this;
    }
    
    /**
     * Tăng tổng số sự kiện thuộc loại này
     */
    public function tangTongSuKien()
    {
        $this->attributes['tong_su_kien'] = ($this->getTongSuKien() + 1);
        
        return $this;
    }
    
    /**
     * Lấy tên loại sự kiện
     */
    public function getLoaiSuKien()
    {
        return $this->attributes['loai_su_kien'] ?? '';
    }
    
    /**
     * Đặt tên loại sự kiện
     */
    public function setLoaiSuKien(string $loaiSuKien)
    {
        $this->attributes['loai_su_kien'] = $loaiSuKien;
        
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
     * Kiểm tra trạng thái của loại sự kiện
     */
    public function isActive()
    {
        return isset($this->attributes['status']) && $this->attributes['status'] == 1;
    }
    
    /**
     * Đặt trạng thái của loại sự kiện
     */
    public function setStatus(int $status)
    {
        $this->attributes['status'] = $status;
        
        return $this;
    }
    
    /**
     * Tạo slug từ tên loại sự kiện
     */
    public function createSlugFromName()
    {
        if (!empty($this->attributes['loai_su_kien'])) {
            $this->attributes['slug'] = $this->convertToSlug($this->attributes['loai_su_kien']);
        }
        
        return $this;
    }
    
    /**
     * Chuyển đổi chuỗi thành slug
     */
    private function convertToSlug($string)
    {
        // Chuyển về chữ thường và loại bỏ khoảng trắng ở đầu và cuối
        $string = mb_strtolower(trim($string));
        
        // Thay thế các ký tự tiếng Việt
        $vietnamese = array(
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
        );
        $replacements = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
        );
        $string = str_replace($vietnamese, $replacements, $string);
        
        // Thay thế các ký tự không phải chữ cái và số bằng dấu gạch ngang
        $string = preg_replace('/[^a-z0-9]/', '-', $string);
        
        // Thay thế nhiều dấu gạch ngang liên tiếp bằng một dấu gạch ngang
        $string = preg_replace('/-+/', '-', $string);
        
        // Loại bỏ dấu gạch ngang ở đầu và cuối
        $string = trim($string, '-');
        
        return $string;
    }
    
    /**
     * Lấy mô tả loại sự kiện
     */
    public function getMoTa()
    {
        return $this->attributes['mo_ta'] ?? '';
    }
    
    /**
     * Đặt mô tả loại sự kiện
     */
    public function setMoTa(string $moTa)
    {
        $this->attributes['mo_ta'] = $moTa;
        
        return $this;
    }
    
    /**
     * Lấy icon loại sự kiện
     */
    public function getIcon()
    {
        return $this->attributes['icon'] ?? '';
    }
    
    /**
     * Đặt icon loại sự kiện
     */
    public function setIcon(string $icon)
    {
        $this->attributes['icon'] = $icon;
        
        return $this;
    }
    
    /**
     * Lấy màu nền loại sự kiện
     */
    public function getMauNen()
    {
        return $this->attributes['mau_nen'] ?? '#ffffff';
    }
    
    /**
     * Đặt màu nền loại sự kiện
     */
    public function setMauNen(string $mauNen)
    {
        $this->attributes['mau_nen'] = $mauNen;
        
        return $this;
    }
    
    /**
     * Tạo đối tượng sự kiện mới
     */
    public function taoSuKien(int $nguoiTaoId)
    {
        $sukien = new SukienEntity();
        $sukien->setLoaiSuKienId($this->id);
        $sukien->setNguoiTaoId($nguoiTaoId);
        $sukien->setStatus(SukienEntity::STATUS_INACTIVE); // Mặc định không hoạt động
        
        // Tăng tổng số sự kiện
        $this->tangTongSuKien();
        
        return $sukien;
    }
}
