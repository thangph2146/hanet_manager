<?php

namespace App\Modules\facenguoidung\Models;

use App\Models\BaseModel;
use App\Modules\facenguoidung\Entities\FacenguoidungEntity;

class FacenguoidungModel extends BaseModel
{
    protected $table = 'face_nguoi_dung';
    protected $primaryKey = 'face_nguoi_dung_id';
    protected $returnType = 'App\Modules\facenguoidung\Entities\Facenguoidung';
    protected $useSoftDeletes = true;
    protected $useTimestamps = true;
    
    protected $allowedFields = [
        'nguoi_dung_id',
        'duong_dan_anh',
        'ngay_cap_nhat',
        'status',
        'bin',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    // Timestamps
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    
    // Validation
    protected $validationRules = [
        'nguoi_dung_id' => 'required|numeric',
        'duong_dan_anh' => 'required',
    ];
    
    protected $validationMessages = [
        'nguoi_dung_id' => [
            'required' => 'Người dùng là bắt buộc',
            'numeric' => 'ID người dùng phải là số',
        ],
        'duong_dan_anh' => [
            'required' => 'Đường dẫn ảnh là bắt buộc',
        ],
    ];
    
    // Searchable fields
    protected $searchableFields = [
        'nguoi_dung_id',
        'duong_dan_anh'
    ];
    
    // Filterable fields
    protected $filterableFields = [
        'nguoi_dung_id',
        'status',
        'bin'
    ];
    
    // Relations
    protected $relations = [
        'nguoi_dung' => [
            'type' => 'n-1', // Mối quan hệ nhiều khuôn mặt thuộc về một người dùng
            'table' => 'nguoi_dung', // Bảng nguoi_dung
            'foreignKey' => 'nguoi_dung_id', // Khóa ngoại trong bảng face_nguoi_dung
            'foreignPrimaryKey' => 'nguoi_dung_id', // Khóa chính trong bảng nguoi_dung
            'select' => 'nguoi_dung.nguoi_dung_id, nguoi_dung.FullName as ho_ten, nguoi_dung.Email as email, nguoi_dung.MobilePhone as mobile_phone, nguoi_dung.status',
            'entity' => 'App\Modules\nguoidung\Entities\NguoiDungEntity',
            'conditions' => [
                [
                    'field' => 'nguoi_dung.bin',
                    'value' => 0,
                    'operator' => '='
                ]
            ]
        ]
    ];
    
    /**
     * Find all active face recognition records
     */
    public function getAllActive()
    {
        return $this->where('status', 1)
                    ->where('bin', 0)
                    ->findAll();
    }
    
    /**
     * Find all deleted face recognition records
     */
    public function getAllDeleted()
    {
        return $this->where('bin', 1)
                    ->findAll();
    }
    
    /**
     * Find face records by nguoi_dung_id
     */
    public function findByNguoiDungId($nguoiDungId)
    {
        return $this->where('nguoi_dung_id', $nguoiDungId)
                    ->where('status', 1)
                    ->where('bin', 0)
                    ->findAll();
    }
    
    /**
     * Upload and save face image with compression
     */
    public function uploadAndSaveFace($nguoiDungId, $imageFile)
    {
        // Get instance of upload service
        $upload = \Config\Services::upload();
        
        // Set upload configurations
        $uploadPath = 'public/data/images/' . date('Y') . '/' . date('m') . '/' . date('d');
        $config = [
            'upload_path' => './' . $uploadPath,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size' => 5120, // 5MB max before compression
            'encrypt_name' => true,
        ];
        
        // Create directory if not exists
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0777, true);
        }
        
        // Initialize upload
        $upload->initialize($config);
        
        // Perform upload
        if ($upload->do_upload($imageFile)) {
            $uploadData = $upload->data();
            $originalPath = $uploadData['full_path'];
            
            // Compress the image
            $this->compressImage($originalPath, $originalPath, 75); // Compress with quality 75%
            
            $imagePath = $uploadPath . '/' . $uploadData['file_name'];
            
            // Save to database
            $data = [
                'nguoi_dung_id' => $nguoiDungId,
                'duong_dan_anh' => $imagePath,
                'ngay_cap_nhat' => date('Y-m-d H:i:s'),
                'status' => 1,
                'bin' => 0,
            ];
            
            $this->insert($data);
            return true;
        }
        
        return false;
    }
    
    /**
     * Compress image to reduce file size
     * 
     * @param string $source Source image path
     * @param string $destination Destination image path
     * @param int $quality Compression quality (0-100)
     * @param int $maxWidth Maximum width (optional)
     * @param int $maxHeight Maximum height (optional)
     * @return bool Success or failure
     */
    private function compressImage($source, $destination, $quality = 75, $maxWidth = 1200, $maxHeight = 1200)
    {
        // Get image info
        $info = getimagesize($source);
        if (!$info) {
            return false;
        }
        
        // Check if resize is needed
        $width = $info[0];
        $height = $info[1];
        $resizeNeeded = ($width > $maxWidth || $height > $maxHeight);
        
        // Calculate new dimensions if resize is needed
        if ($resizeNeeded) {
            if ($width > $height) {
                $newWidth = $maxWidth;
                $newHeight = ($height / $width) * $maxWidth;
            } else {
                $newHeight = $maxHeight;
                $newWidth = ($width / $height) * $maxHeight;
            }
        } else {
            $newWidth = $width;
            $newHeight = $height;
        }
        
        // Create image based on file type
        switch ($info['mime']) {
            case 'image/jpeg':
                $image = imagecreatefromjpeg($source);
                break;
            case 'image/png':
                $image = imagecreatefrompng($source);
                // Handle transparency for PNG
                imagealphablending($image, false);
                imagesavealpha($image, true);
                break;
            case 'image/gif':
                $image = imagecreatefromgif($source);
                break;
            default:
                return false;
        }
        
        // Resize if needed
        if ($resizeNeeded) {
            $newImage = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preserve transparency for PNG
            if ($info['mime'] == 'image/png') {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                $transparent = imagecolorallocatealpha($newImage, 255, 255, 255, 127);
                imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
            }
            
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            $image = $newImage;
        }
        
        // Save compressed image
        switch ($info['mime']) {
            case 'image/jpeg':
                imagejpeg($image, $destination, $quality);
                break;
            case 'image/png':
                // Convert quality scale from 0-100 to 0-9 for PNG
                $pngQuality = 9 - round(($quality / 100) * 9);
                imagepng($image, $destination, $pngQuality);
                break;
            case 'image/gif':
                imagegif($image, $destination);
                break;
            default:
                return false;
        }
        
        // Free memory
        imagedestroy($image);
        if (isset($newImage)) {
            imagedestroy($newImage);
        }
        
        return true;
    }
} 