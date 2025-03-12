<?php

namespace App\Modules\nguoidung\Controllers;

use App\Controllers\BaseController;
use App\Libraries\FormRenderer;
use App\Modules\nguoidung\Models\NguoiDungModel;
use CodeIgniter\HTTP\ResponseInterface;


class NguoiDungController extends BaseController
{
    protected $nguoiDungModel;
    protected $formRenderer;
    
    public function __construct()
    {
        $this->nguoiDungModel = new NguoiDungModel();
        $this->formRenderer = new FormRenderer();
    }
    
    /**
     * Hiển thị danh sách người dùng
     */
    public function index()
    {
        $data = [
            'nguoiDungs' => $this->nguoiDungModel->getAllNguoiDung(),
            'title' => 'Danh sách người dùng',
        ];
        
        // Using the FormRenderer library to render the view
        return $this->formRenderer
            ->setLayout('layouts/default')
            ->with($data)
            ->render('nguoidung/Views/index');
    }
    
    /**
     * Hiển thị form thêm người dùng
     */
    public function create()
    {
        return $this->formRenderer
            ->setLayout('layouts/default')
            ->with([
                'title' => 'Thêm người dùng mới',
            ])
            ->form(['action' => base_url('nguoidung/store'), 'method' => 'post'])
            ->input('AccountId', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập mã tài khoản'
            ])
            ->select('AccountType', [
                '' => 'Chọn loại tài khoản', 
                'admin' => 'Quản trị viên', 
                'giangvien' => 'Giảng viên',
                'sinhvien' => 'Sinh viên',
                'nhanvien' => 'Nhân viên'
            ], '', ['class' => 'form-control'])
            ->input('FirstName', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập họ'
            ])
            ->input('FullName', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập họ tên đầy đủ'
            ])
            ->input('Email', 'email', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập email'
            ])
            ->input('MobilePhone', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập số điện thoại di động'
            ])
            ->input('HomePhone', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập số điện thoại nhà'
            ])
            ->input('HomePhone1', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập số điện thoại nhà khác'
            ])
            ->input('PW', 'password', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập mật khẩu'
            ])
            ->input('mat_khau_local', 'password', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập mật khẩu local'
            ])
            ->select('loai_nguoi_dung_id', [
                '' => 'Chọn loại người dùng',
                '1' => 'Quản trị viên',
                '2' => 'Giảng viên',
                '3' => 'Sinh viên',
                '4' => 'Nhân viên'
            ], '', ['class' => 'form-control'])
            ->select('nam_hoc_id', [
                '' => 'Chọn năm học',
                '1' => '2023-2024',
                '2' => '2024-2025'
            ], '', ['class' => 'form-control'])
            ->select('bac_hoc_id', [
                '' => 'Chọn bậc học',
                '1' => 'Đại học',
                '2' => 'Cao đẳng',
                '3' => 'Thạc sĩ',
                '4' => 'Tiến sĩ'
            ], '', ['class' => 'form-control'])
            ->select('he_dao_tao_id', [
                '' => 'Chọn hệ đào tạo',
                '1' => 'Chính quy',
                '2' => 'Liên thông',
                '3' => 'Vừa làm vừa học'
            ], '', ['class' => 'form-control'])
            ->select('nganh_id', [
                '' => 'Chọn ngành',
                '1' => 'Công nghệ thông tin',
                '2' => 'Kế toán',
                '3' => 'Quản trị kinh doanh'
            ], '', ['class' => 'form-control'])
            ->select('phong_khoa_id', [
                '' => 'Chọn phòng/khoa',
                '1' => 'Phòng đào tạo',
                '2' => 'Khoa CNTT',
                '3' => 'Khoa Kinh tế',
                '4' => 'Phòng hành chính'
            ], '', ['class' => 'form-control'])
            ->select('status', [
                '1' => 'Hoạt động',
                '0' => 'Không hoạt động'
            ], '1', ['class' => 'form-control'])
            ->submit('Lưu', ['class' => 'btn btn-primary'])
            ->render('nguoidung/Views/form');
    }
    
    /**
     * Lưu người dùng mới
     */
    public function store()
    {
        $data = $this->request->getPost();
        
        // Đặt giá trị mặc định cho bin
        $data['bin'] = 0;
        
        // Nếu không có mật khẩu local, sử dụng mật khẩu chính
        if (empty($data['mat_khau_local'])) {
            $data['mat_khau_local'] = $data['PW'];
        }
        
        if ($this->nguoiDungModel->insert($data)) {
            return redirect()->to(base_url('nguoidung'))
                ->with('success', 'Người dùng đã được thêm thành công.');
        } else {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi thêm người dùng.')
                ->with('validation', $this->nguoiDungModel->errors())
                ->withInput();
        }
    }
    
    /**
     * Hiển thị chi tiết người dùng
     */
    public function show($id)
    {
        $nguoiDung = $this->nguoiDungModel->findNguoiDung($id);
        
        if (!$nguoiDung) {
            return redirect()->to(base_url('nguoidung'))
                ->with('error', 'Người dùng không tồn tại.');
        }
        
        return $this->formRenderer
            ->setLayout('layouts/default')
            ->with([
                'nguoiDung' => $nguoiDung,
                'title' => 'Chi tiết người dùng: ' . $nguoiDung->FullName,
            ])
            ->render('nguoidung/Views/show');
    }
    
    /**
     * Hiển thị form chỉnh sửa người dùng
     */
    public function edit($id)
    {
        $nguoiDung = $this->nguoiDungModel->findNguoiDung($id);
        
        if (!$nguoiDung) {
            return redirect()->to(base_url('nguoidung'))
                ->with('error', 'Người dùng không tồn tại.');
        }
        
        // Không yêu cầu mật khẩu khi cập nhật
        $this->nguoiDungModel->disablePasswordValidation();
        
        return $this->formRenderer
            ->setLayout('layouts/default')
            ->with([
                'title' => 'Chỉnh sửa người dùng: ' . $nguoiDung->FullName,
                'nguoiDung' => $nguoiDung,
            ])
            ->form(['action' => base_url('nguoidung/update/' . $id), 'method' => 'post'])
            ->input('AccountId', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập mã tài khoản',
                'value' => $nguoiDung->AccountId
            ])
            ->select('AccountType', [
                '' => 'Chọn loại tài khoản', 
                'admin' => 'Quản trị viên', 
                'giangvien' => 'Giảng viên',
                'sinhvien' => 'Sinh viên',
                'nhanvien' => 'Nhân viên'
            ], $nguoiDung->AccountType, ['class' => 'form-control'])
            ->input('FirstName', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập họ',
                'value' => $nguoiDung->FirstName
            ])
            ->input('FullName', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập đầy đủ tên',
                'value' => $nguoiDung->FirstName
            ])
            ->input('Email', 'email', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập email',
                'value' => $nguoiDung->Email
            ])
            ->input('MobilePhone', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập số điện thoại di động',
                'value' => $nguoiDung->MobilePhone
            ])
            ->input('HomePhone', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập số điện thoại nhà',
                'value' => $nguoiDung->HomePhone
            ])
            ->input('HomePhone1', 'text', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập số điện thoại nhà khác',
                'value' => $nguoiDung->HomePhone1
            ])
            ->input('PW', 'password', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập mật khẩu mới (để trống nếu không thay đổi)'
            ])
            ->input('mat_khau_local', 'password', [
                'class' => 'form-control', 
                'placeholder' => 'Nhập mật khẩu local mới (để trống nếu không thay đổi)'
            ])
            ->select('loai_nguoi_dung_id', [
                '' => 'Chọn loại người dùng',
                '1' => 'Quản trị viên',
                '2' => 'Giảng viên',
                '3' => 'Sinh viên',
                '4' => 'Nhân viên'
            ], $nguoiDung->loai_nguoi_dung_id, ['class' => 'form-control'])
            ->select('nam_hoc_id', [
                '' => 'Chọn năm học',
                '1' => '2023-2024',
                '2' => '2024-2025'
            ], $nguoiDung->nam_hoc_id, ['class' => 'form-control'])
            ->select('bac_hoc_id', [
                '' => 'Chọn bậc học',
                '1' => 'Đại học',
                '2' => 'Cao đẳng',
                '3' => 'Thạc sĩ',
                '4' => 'Tiến sĩ'
            ], $nguoiDung->bac_hoc_id, ['class' => 'form-control'])
            ->select('he_dao_tao_id', [
                '' => 'Chọn hệ đào tạo',
                '1' => 'Chính quy',
                '2' => 'Liên thông',
                '3' => 'Vừa làm vừa học'
            ], $nguoiDung->he_dao_tao_id, ['class' => 'form-control'])
            ->select('nganh_id', [
                '' => 'Chọn ngành',
                '1' => 'Công nghệ thông tin',
                '2' => 'Kế toán',
                '3' => 'Quản trị kinh doanh'
            ], $nguoiDung->nganh_id, ['class' => 'form-control'])
            ->select('phong_khoa_id', [
                '' => 'Chọn phòng/khoa',
                '1' => 'Phòng đào tạo',
                '2' => 'Khoa CNTT',
                '3' => 'Khoa Kinh tế',
                '4' => 'Phòng hành chính'
            ], $nguoiDung->phong_khoa_id, ['class' => 'form-control'])
            ->select('status', [
                '1' => 'Hoạt động',
                '0' => 'Không hoạt động'
            ], $nguoiDung->status, ['class' => 'form-control'])
            ->submit('Cập nhật', ['class' => 'btn btn-primary'])
            ->render('Modules/nguoidung/Views/form');
    }
    
    /**
     * Cập nhật thông tin người dùng
     */
    public function update($id)
    {
        $nguoiDung = $this->nguoiDungModel->findNguoiDung($id);
        
        if (!$nguoiDung) {
            return redirect()->to(base_url('nguoidung'))
                ->with('error', 'Người dùng không tồn tại.');
        }
        
        $data = $this->request->getPost();
        
        // Nếu không nhập mật khẩu mới, loại bỏ trường mật khẩu
        if (empty($data['PW'])) {
            unset($data['PW']);
        }
        
        // Nếu không nhập mật khẩu local mới, loại bỏ trường mật khẩu local
        if (empty($data['mat_khau_local'])) {
            unset($data['mat_khau_local']);
        }
        
        if ($this->nguoiDungModel->update($id, $data)) {
            return redirect()->to(base_url('nguoidung'))
                ->with('success', 'Người dùng đã được cập nhật thành công.');
        } else {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật người dùng.')
                ->with('validation', $this->nguoiDungModel->errors())
                ->withInput();
        }
    }
    
    /**
     * Xóa người dùng (soft delete)
     */
    public function delete($id)
    {
        $nguoiDung = $this->nguoiDungModel->findNguoiDung($id);
        
        if (!$nguoiDung) {
            return redirect()->to(base_url('nguoidung'))
                ->with('error', 'Người dùng không tồn tại.');
        }
        
        // Đánh dấu là đã xóa
        if ($this->nguoiDungModel->update($id, ['bin' => 1, 'deleted_at' => date('Y-m-d H:i:s')])) {
            return redirect()->to(base_url('nguoidung'))
                ->with('success', 'Người dùng đã được chuyển vào thùng rác.');
        } else {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa người dùng.');
        }
    }
    
    /**
     * Hiển thị danh sách người dùng đã xóa
     */
    public function trash()
    {
        $data = [
            'nguoiDungs' => $this->nguoiDungModel->getAllNguoiDungDeleted(),
            'title' => 'Thùng rác người dùng',
        ];
        
        return view('Modules/nguoidung/Views/trash', $data);
    }
    
    /**
     * Khôi phục người dùng đã xóa
     */
    public function restore($id)
    {
        $nguoiDung = $this->nguoiDungModel->findNguoiDung($id);
        
        if (!$nguoiDung) {
            return redirect()->to(base_url('nguoidung/trash'))
                ->with('error', 'Người dùng không tồn tại.');
        }
        
        // Khôi phục người dùng
        if ($this->nguoiDungModel->update($id, ['bin' => 0, 'deleted_at' => null])) {
            return redirect()->to(base_url('nguoidung/trash'))
                ->with('success', 'Người dùng đã được khôi phục thành công.');
        } else {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi khôi phục người dùng.');
        }
    }
    
    /**
     * Xóa vĩnh viễn người dùng
     */
    public function purge($id)
    {
        $nguoiDung = $this->nguoiDungModel->findNguoiDung($id);
        
        if (!$nguoiDung) {
            return redirect()->to(base_url('nguoidung/trash'))
                ->with('error', 'Người dùng không tồn tại.');
        }
        
        // Xóa vĩnh viễn người dùng
        if ($this->nguoiDungModel->delete($id, true)) {
            return redirect()->to(base_url('nguoidung/trash'))
                ->with('success', 'Người dùng đã được xóa vĩnh viễn.');
        } else {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa vĩnh viễn người dùng.');
        }
    }
    
    /**
     * Tìm kiếm người dùng
     */
    public function search()
    {
        $keyword = $this->request->getGet('keyword');
        
        if (empty($keyword)) {
            return redirect()->to(base_url('nguoidung'));
        }
        
        $data = [
            'nguoiDungs' => $this->nguoiDungModel->searchNguoiDung($keyword),
            'title' => 'Kết quả tìm kiếm: ' . $keyword,
            'keyword' => $keyword,
        ];
        
        return view('Modules/nguoidung/Views/search', $data);
    }
}