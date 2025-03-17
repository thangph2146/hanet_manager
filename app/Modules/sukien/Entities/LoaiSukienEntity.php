<?php

namespace App\Modules\sukien\Entities;

use CodeIgniter\Entity\Entity;

class LoaiSukienEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [
        'id'        => 'int',
        'status'    => 'int',
        'bin'       => 'int',
    ];
    
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
}
