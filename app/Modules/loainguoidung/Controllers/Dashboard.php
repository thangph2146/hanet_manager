<?php

namespace App\Modules\loainguoidung\Controllers;

use App\Controllers\BaseController;
use App\Modules\loainguoidung\Models\LoaiNguoiDungModel;

class Dashboard extends BaseController
{
    protected $loaiNguoiDungModel;
    
    public function __construct()
    {
        $this->loaiNguoiDungModel = new LoaiNguoiDungModel();
    }
    
    /**
     * Trang tổng quan quản lý loại người dùng
     */
    public function index()
    {
        // Thống kê số lượng
        $totalAll = $this->loaiNguoiDungModel->countAll();
        $totalActive = $this->loaiNguoiDungModel->where('status', 1)->where('bin', 0)->countAllResults();
        $totalInactive = $this->loaiNguoiDungModel->where('status', 0)->where('bin', 0)->countAllResults();
        $totalBinned = $this->loaiNguoiDungModel->where('bin', 1)->countAllResults();
        $totalDeleted = $this->loaiNguoiDungModel->onlyDeleted()->countAllResults();
        
        // Loại người dùng mới nhất
        $recentItems = $this->loaiNguoiDungModel->where('bin', 0)
                                               ->orderBy('created_at', 'DESC')
                                               ->limit(5)
                                               ->findAll();
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'Quản lý loại người dùng',
            'totalAll' => $totalAll,
            'totalActive' => $totalActive,
            'totalInactive' => $totalInactive,
            'totalBinned' => $totalBinned,
            'totalDeleted' => $totalDeleted,
            'recentItems' => $recentItems
        ];
        
        return view('App\Modules\loainguoidung\Views\dashboard', $data);
    }
    
    /**
     * Trang hiển thị biểu đồ và thống kê
     */
    public function statistics()
    {
        // Dữ liệu thống kê theo thời gian (ví dụ: 6 tháng gần nhất)
        $stats = [];
        $now = time();
        
        for ($i = 5; $i >= 0; $i--) {
            $month = date('m', strtotime("-$i months", $now));
            $year = date('Y', strtotime("-$i months", $now));
            $startDate = "$year-$month-01";
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $count = $this->loaiNguoiDungModel->where('created_at >=', $startDate)
                                             ->where('created_at <=', $endDate . ' 23:59:59')
                                             ->countAllResults();
            
            $stats[] = [
                'label' => date('m/Y', strtotime($startDate)),
                'count' => $count
            ];
        }
        
        // Loại người dùng phổ biến nhất (có nhiều người dùng nhất)
        // Đây chỉ là mẫu, bạn cần điều chỉnh logic theo cấu trúc thực tế của hệ thống
        /*
        $popularTypes = $this->loaiNguoiDungModel->select('loai_nguoi_dung.*, COUNT(nguoi_dung.id) as user_count')
                                                  ->join('nguoi_dung', 'nguoi_dung.loai_nguoi_dung_id = loai_nguoi_dung.loai_nguoi_dung_id', 'left')
                                                  ->groupBy('loai_nguoi_dung.loai_nguoi_dung_id')
                                                  ->orderBy('user_count', 'DESC')
                                                  ->limit(5)
                                                  ->findAll();
        */
        
        $data = [
            'title' => 'Thống kê loại người dùng',
            'stats' => $stats,
            //'popularTypes' => $popularTypes
        ];
        
        return view('App\Modules\loainguoidung\Views\statistics', $data);
    }
} 