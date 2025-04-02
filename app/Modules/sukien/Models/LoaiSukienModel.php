<?php

namespace App\Modules\sukien\Models;

use App\Modules\quanlyloaisukien\Models\LoaiSuKienModel as BaseModel;

class LoaiSukienModel extends BaseModel
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Lấy tất cả loại sự kiện
     */
    public function getAllEventTypes()
    {
        $loaiSukien = $this->where('status', 1)
                          ->where('deleted_at IS NULL')
                          ->findAll();
        
        $result = [];
        foreach ($loaiSukien as $loai) {
            $result[] = [
                'id' => $loai->loai_su_kien_id,
                'loai_su_kien' => $loai->ten_loai_su_kien,
                'status' => $loai->status,
                'bin' => 0,
                'slug' => strtolower(str_replace(' ', '-', $loai->ten_loai_su_kien))
            ];
        }
        
        return $result;
    }

    /**
     * Lấy loại sự kiện theo ID
     */
    public function getEventTypeById($id)
    {
        $loaiSukien = $this->find($id);
        
        if (!$loaiSukien) {
            return null;
        }
        
        return [
            'id' => $loaiSukien->loai_su_kien_id,
            'loai_su_kien' => $loaiSukien->ten_loai_su_kien,
            'status' => $loaiSukien->status,
            'bin' => 0,
            'slug' => strtolower(str_replace(' ', '-', $loaiSukien->ten_loai_su_kien))
        ];
    }

    /**
     * Lấy loại sự kiện theo slug
     */
    public function getEventTypeBySlug($slug)
    {
        $loaiSukien = $this->where('status', 1)
                          ->where('deleted_at IS NULL')
                          ->like('LOWER(ten_loai_su_kien)', str_replace('-', ' ', $slug), 'both')
                          ->first();
        
        if (!$loaiSukien) {
            return null;
        }
        
        return [
            'id' => $loaiSukien->loai_su_kien_id,
            'loai_su_kien' => $loaiSukien->ten_loai_su_kien,
            'status' => $loaiSukien->status,
            'bin' => 0,
            'slug' => strtolower(str_replace(' ', '-', $loaiSukien->ten_loai_su_kien))
        ];
    }

    /**
     * Tạo slug từ tên loại sự kiện
     */
    public function createSlug($name)
    {
        // Chuyển đổi tiếng Việt sang không dấu
        $slug = $this->convertToSlug($name);
        
        // Kiểm tra xem slug đã tồn tại chưa
        $count = $this->where('deleted_at IS NULL')
                     ->like('ten_loai_su_kien', $name, 'both')
                     ->countAllResults();
        
        // Nếu slug đã tồn tại, thêm số vào cuối
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        return $slug;
    }
    
    /**
     * Chuyển đổi chuỗi tiếng Việt thành slug
     */
    private function convertToSlug($string)
    {
        $vietnamese = array(
            'à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ',
            'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ',
            'ì', 'í', 'ị', 'ỉ', 'ĩ',
            'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ',
            'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ',
            'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ',
            'đ',
            'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ',
            'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ',
            'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ',
            'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ',
            'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ',
            'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ',
            'Đ'
        );
        
        $latin = array(
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd',
            'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a',
            'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e',
            'i', 'i', 'i', 'i', 'i',
            'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o',
            'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
            'y', 'y', 'y', 'y', 'y',
            'd'
        );
        
        // Thay thế ký tự tiếng Việt
        $string = str_replace($vietnamese, $latin, $string);
        
        // Chuyển đổi sang chữ thường
        $string = strtolower($string);
        
        // Thay thế các ký tự không phải chữ cái và số bằng dấu gạch ngang
        $string = preg_replace('/[^a-z0-9]/', '-', $string);
        
        // Loại bỏ các dấu gạch ngang liên tiếp
        $string = preg_replace('/-+/', '-', $string);
        
        // Xóa dấu gạch ngang ở đầu và cuối
        $string = trim($string, '-');
        
        return $string;
    }

    /**
     * Ghi đè phương thức insert để tự động tạo slug
     */
    public function insert($data = null, bool $returnID = true)
    {
        // Tạo slug nếu chưa có
        if (!isset($data['slug']) && isset($data['loai_su_kien'])) {
            $data['slug'] = $this->createSlug($data['loai_su_kien']);
        }
        
        return parent::insert($data, $returnID);
    }
    
    /**
     * Ghi đè phương thức update để tự động cập nhật slug
     */
    public function update($id = null, $data = null): bool
    {
        // Cập nhật slug nếu tên loại sự kiện thay đổi
        if (!isset($data['slug']) && isset($data['loai_su_kien'])) {
            $data['slug'] = $this->createSlug($data['loai_su_kien']);
        }
        
        return parent::update($id, $data);
    }
} 