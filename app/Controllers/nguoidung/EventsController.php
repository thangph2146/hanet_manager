<?php

namespace App\Controllers\nguoidung;

use App\Controllers\BaseController;
use App\Models\SuKienModel;
use App\Models\DangKySuKienModel;

class EventsController extends BaseController
{
    protected $sukienModel;
    protected $dangkysukienModel;
    
    public function __construct()
    {
        $this->sukienModel = new SuKienModel();
        $this->dangkysukienModel = new DangKySuKienModel();
    }
    
    public function index()
    {
        // Hiển thị danh sách sự kiện sắp diễn ra
        $currentPage = $this->request->getVar('page_events') ? (int) $this->request->getVar('page_events') : 1;
        $perPage = 12;
        
        // Lấy các sự kiện sắp diễn ra và đang diễn ra
        $builder = $this->sukienModel
            ->where('trang_thai', 1) // Sự kiện đã được duyệt
            ->where('thoi_gian_bat_dau >=', date('Y-m-d H:i:s', strtotime('-1 day'))) // Bao gồm cả sự kiện đang diễn ra
            ->orderBy('thoi_gian_bat_dau', 'ASC');
            
        // Áp dụng bộ lọc nếu có
        $category = $this->request->getVar('category');
        if (!empty($category) && $category != 'all') {
            $builder->where('phan_loai', $category);
        }
        
        // Tìm kiếm nếu có
        $search = $this->request->getVar('search');
        if (!empty($search)) {
            $builder->groupStart()
                ->like('ten_su_kien', $search)
                ->orLike('mo_ta', $search)
                ->orLike('noi_dung', $search)
                ->groupEnd();
        }
        
        // Lấy dữ liệu với phân trang
        $data['events'] = $builder->paginate($perPage, 'events');
        $data['pager'] = $this->sukienModel->pager;
        $data['categories'] = $this->sukienModel->distinct()
            ->select('phan_loai')
            ->where('phan_loai IS NOT NULL')
            ->get()
            ->getResult();
        
        // Thêm dữ liệu filter
        $data['current_filter'] = [
            'category' => $category,
            'search' => $search
        ];
        
        return view('Modules/nguoidung/Views/eventslist', $data);
    }
    
    public function detail($id)
    {
        // Hiển thị chi tiết sự kiện
        $data['sukien'] = $this->sukienModel->find($id);
        
        if (empty($data['sukien'])) {
            return redirect()->to('/nguoidung/sukien')->with('error', 'Sự kiện không tồn tại');
        }
        
        return view('Modules/nguoidung/Views/sukien_detail', $data);
    }
    
    public function register($id)
    {
        // Xử lý đăng ký sự kiện
        $sukien = $this->sukienModel->find($id);
        
        if (empty($sukien)) {
            return redirect()->to('/nguoidung/sukien')->with('error', 'Sự kiện không tồn tại');
        }
        
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!session()->get('id')) {
            return redirect()->to('/login')->with('error', 'Vui lòng đăng nhập để đăng ký sự kiện');
        }
        
        // Kiểm tra người dùng đã đăng ký sự kiện này chưa
        $dangky = $this->dangkysukienModel->where([
            'ma_sukien' => $id,
            'ma_nguoidung' => session()->get('id')
        ])->first();
        
        if (!empty($dangky)) {
            return redirect()->to('/nguoidung/sukien')->with('error', 'Bạn đã đăng ký sự kiện này');
        }
        
        // Lưu thông tin đăng ký
        $data = [
            'ma_sukien' => $id,
            'ma_nguoidung' => session()->get('id'),
            'ngay_dang_ky' => date('Y-m-d H:i:s'),
            'da_check_in' => 0,
            'da_check_out' => 0
        ];
        
        $this->dangkysukienModel->insert($data);
        
        return redirect()->to('/nguoidung/sukien/dangky-list')->with('success', 'Đăng ký sự kiện thành công');
    }
    
    public function registerList()
    {
        // Kiểm tra đăng nhập
        if (!session()->get('id')) {
            return redirect()->to('/login');
        }
        
        // Lấy danh sách sự kiện đã đăng ký của người dùng
        $builder = $this->dangkysukienModel->select('dangky_sukien.*, sukien.*')
                                         ->join('sukien', 'sukien.ma_sukien = dangky_sukien.ma_sukien')
                                         ->where('dangky_sukien.ma_nguoidung', session()->get('id'))
                                         ->orderBy('sukien.ngay_to_chuc', 'DESC');
        
        $data['registeredEvents'] = $builder->get()->getResult();
        
        return view('Modules/nguoidung/Views/eventshistoryregister', $data);
    }
    
    public function checkinList()
    {
        // Kiểm tra đăng nhập
        if (!session()->get('id')) {
            return redirect()->to('/login');
        }
        
        // Lấy danh sách sự kiện đã check-in của người dùng
        $builder = $this->dangkysukienModel->select('dangky_sukien.*, sukien.*')
                                         ->join('sukien', 'sukien.ma_sukien = dangky_sukien.ma_sukien')
                                         ->where([
                                             'dangky_sukien.ma_nguoidung' => session()->get('id'),
                                             'dangky_sukien.da_check_in' => 1
                                         ])
                                         ->orderBy('sukien.ngay_to_chuc', 'DESC');
        
        $data['attendedEvents'] = $builder->get()->getResult();
        
        return view('Modules/nguoidung/Views/eventscheckin', $data);
    }
    
    public function checkout($id)
    {
        // Xử lý check-out sự kiện
        if (!session()->get('id')) {
            return redirect()->to('/login');
        }
        
        $dangky = $this->dangkysukienModel->where([
            'ma_sukien' => $id,
            'ma_nguoidung' => session()->get('id'),
            'da_check_in' => 1,
            'da_check_out' => 0
        ])->first();
        
        if (empty($dangky)) {
            return redirect()->to('/nguoidung/sukien/tham-gia')->with('error', 'Bạn chưa check-in vào sự kiện này hoặc đã check-out rồi');
        }
        
        // Cập nhật trạng thái check-out
        $this->dangkysukienModel->update($dangky['ma_dangky'], [
            'da_check_out' => 1,
            'thoi_gian_check_out' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->to('/nguoidung/sukien/tham-gia')->with('success', 'Check-out thành công');
    }
    
    public function cancel($id)
    {
        // Xử lý hủy đăng ký sự kiện
        if (!session()->get('id')) {
            return redirect()->to('/login');
        }
        
        $dangky = $this->dangkysukienModel->where([
            'ma_sukien' => $id,
            'ma_nguoidung' => session()->get('id'),
            'da_check_in' => 0
        ])->first();
        
        if (empty($dangky)) {
            return redirect()->to('/nguoidung/sukien/dangky-list')
                ->with('error', 'Bạn chưa đăng ký sự kiện này hoặc đã check-in rồi, không thể hủy');
        }
        
        // Xóa thông tin đăng ký
        $this->dangkysukienModel->delete($dangky['ma_dangky']);
        
        return redirect()->to('/nguoidung/sukien/dangky-list')
            ->with('success', 'Đã hủy đăng ký sự kiện thành công');
    }
} 