<?php

namespace App\Modules\sukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\sukien\Models\SukienModel;

class Sitemap extends BaseController
{
    protected $sukienModel;
    
    public function __construct()
    {
        $this->sukienModel = new SukienModel();
    }
    
    public function index()
    {
        // Thiết lập header
        $this->response->setContentType('application/xml');
        
        // Lấy tất cả sự kiện
        $events = $this->sukienModel->getAllEvents();
        
        // Lấy tất cả danh mục
        $categories = $this->sukienModel->getEventTypes();
        
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