<?php

namespace App\Modules\sukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\sukien\Models\SukienModel;
use App\Modules\sukien\Models\LoaiSukienModel;

class Sitemap extends BaseController
{
    protected $sukienModel;
    protected $loaiSukienModel;
    
    public function __construct()
    {
        $this->sukienModel = new SukienModel();
        $this->loaiSukienModel = new LoaiSukienModel();
    }
    
    public function index()
    {
        // Thiết lập header
        $this->response->setContentType('application/xml');
        
        // Lấy tất cả sự kiện
        $events = $this->sukienModel->getAllEvents();
        
        // Lấy tất cả danh mục
        $categories = $this->loaiSukienModel->getAllEventTypes();
        
        // Chuẩn bị dữ liệu cho sitemap
        $data = [
            'base_url' => base_url(),
            'events' => $events,
            'categories' => $categories,
            'current_date' => date('Y-m-d\TH:i:sP')
        ];
        
        return view('App\Modules\sukien\Views\sitemap', $data);
    }
} 