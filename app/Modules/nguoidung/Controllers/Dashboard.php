<?php

namespace App\Modules\nguoidung\Controllers;

use App\Controllers\BaseController;
use App\Modules\nguoidung\Models\NguoiDungModel;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    protected $nguoiDungModel;
    
    public function __construct()
    {
        // Tải helper session
        helper('App\Modules\nguoidung\Helpers\session');
        
        // Khởi tạo model
        $this->nguoiDungModel = new NguoiDungModel();
    }
    
    /**
     * Hiển thị trang dashboard
     */
    public function index()
    {
        // Kiểm tra đăng nhập
        if (!nguoidung_is_logged_in()) {
            // Lưu URL hiện tại để chuyển hướng sau khi đăng nhập
            nguoidung_session_set('redirect_url', current_url());
            
            // Chuyển hướng đến trang đăng nhập
            return redirect()->to(base_url('nguoidung/login'))
                             ->with('warning', 'Vui lòng đăng nhập để tiếp tục!');
        }
        
        // Lấy thông tin người dùng đăng nhập
        $userData = nguoidung_session_get('logged_user');
        
        // Lấy thông tin chi tiết người dùng từ cơ sở dữ liệu
        $user = $this->nguoiDungModel->find($userData['id']);
        
        if (!$user) {
            // Nếu không tìm thấy người dùng, đăng xuất và chuyển hướng đến trang đăng nhập
            nguoidung_logout();
            return redirect()->to(base_url('nguoidung/login'))
                             ->with('warning', 'Tài khoản không tồn tại!');
        }
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'Dashboard',
            'user' => $user,
            'userData' => $userData
        ];
        
        // Hiển thị view dashboard
        return view('App\Modules\nguoidung\Views\dashboard', $data);
    }
    
    /**
     * Hiển thị trang thông tin cá nhân
     */
    public function profile()
    {
        // Kiểm tra đăng nhập
        if (!nguoidung_is_logged_in()) {
            // Lưu URL hiện tại để chuyển hướng sau khi đăng nhập
            nguoidung_session_set('redirect_url', current_url());
            
            // Chuyển hướng đến trang đăng nhập
            return redirect()->to(base_url('nguoidung/login'))
                             ->with('warning', 'Vui lòng đăng nhập để tiếp tục!');
        }
        
        // Lấy thông tin người dùng đăng nhập
        $userData = nguoidung_session_get('logged_user');
        
        // Lấy thông tin chi tiết người dùng từ cơ sở dữ liệu
        $user = $this->nguoiDungModel->find($userData['id']);
        
        if (!$user) {
            // Nếu không tìm thấy người dùng, đăng xuất và chuyển hướng đến trang đăng nhập
            nguoidung_logout();
            return redirect()->to(base_url('nguoidung/login'))
                             ->with('warning', 'Tài khoản không tồn tại!');
        }
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'Thông tin cá nhân',
            'user' => $user,
            'userData' => $userData
        ];
        
        // Hiển thị view profile
        return view('App\Modules\nguoidung\Views\profile', $data);
    }
    
    /**
     * Cập nhật thông tin cá nhân
     */
    public function updateProfile()
    {
        // Kiểm tra đăng nhập
        if (!nguoidung_is_logged_in()) {
            return redirect()->to(base_url('nguoidung/login'))
                             ->with('warning', 'Vui lòng đăng nhập để tiếp tục!');
        }
        
        // Lấy thông tin người dùng đăng nhập
        $userData = nguoidung_session_get('logged_user');
        
        // Xác thực dữ liệu
        $rules = [
            'FullName' => 'required|min_length[3]|max_length[100]',
            'FirstName' => 'permit_empty|max_length[50]',
            'LastName' => 'permit_empty|max_length[50]',
            'Phone' => 'permit_empty|max_length[20]',
        ];
        
        $messages = [
            'FullName' => [
                'required' => 'Họ tên không được để trống',
                'min_length' => 'Họ tên phải có ít nhất 3 ký tự',
                'max_length' => 'Họ tên không được vượt quá 100 ký tự',
            ],
            'FirstName' => [
                'max_length' => 'Tên không được vượt quá 50 ký tự',
            ],
            'LastName' => [
                'max_length' => 'Họ không được vượt quá 50 ký tự',
            ],
            'Phone' => [
                'max_length' => 'Số điện thoại không được vượt quá 20 ký tự',
            ],
        ];
        
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }
        
        // Cập nhật thông tin người dùng
        $data = [
            'FullName' => $this->request->getPost('FullName'),
            'FirstName' => $this->request->getPost('FirstName'),
            'LastName' => $this->request->getPost('LastName'),
            'Phone' => $this->request->getPost('Phone'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->nguoiDungModel->update($userData['id'], $data);
        
        // Cập nhật thông tin trong session
        $userData['fullname'] = $data['FullName'];
        nguoidung_session_set('logged_user', $userData);
        
        return redirect()->to(base_url('nguoidung/dashboard/profile'))
                         ->with('success', 'Cập nhật thông tin thành công!');
    }
    
    /**
     * Hiển thị trang đổi mật khẩu
     */
    public function changePassword()
    {
        // Kiểm tra đăng nhập
        if (!nguoidung_is_logged_in()) {
            return redirect()->to(base_url('nguoidung/login'))
                             ->with('warning', 'Vui lòng đăng nhập để tiếp tục!');
        }
        
        // Lấy thông tin người dùng đăng nhập
        $userData = nguoidung_session_get('logged_user');
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'Đổi mật khẩu',
            'userData' => $userData
        ];
        
        // Hiển thị view đổi mật khẩu
        return view('App\Modules\nguoidung\Views\change_password', $data);
    }
    
    /**
     * Xử lý đổi mật khẩu
     */
    public function updatePassword()
    {
        // Kiểm tra đăng nhập
        if (!nguoidung_is_logged_in()) {
            return redirect()->to(base_url('nguoidung/login'))
                             ->with('warning', 'Vui lòng đăng nhập để tiếp tục!');
        }
        
        // Lấy thông tin người dùng đăng nhập
        $userData = nguoidung_session_get('logged_user');
        
        // Xác thực dữ liệu
        $rules = [
            'current_password' => 'required',
            'new_password' => 'required|min_length[6]',
            'confirm_password' => 'required|matches[new_password]',
        ];
        
        $messages = [
            'current_password' => [
                'required' => 'Mật khẩu hiện tại không được để trống',
            ],
            'new_password' => [
                'required' => 'Mật khẩu mới không được để trống',
                'min_length' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            ],
            'confirm_password' => [
                'required' => 'Xác nhận mật khẩu không được để trống',
                'matches' => 'Xác nhận mật khẩu không khớp với mật khẩu mới',
            ],
        ];
        
        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }
        
        // Lấy thông tin người dùng từ cơ sở dữ liệu
        $user = $this->nguoiDungModel->find($userData['id']);
        
        // Kiểm tra mật khẩu hiện tại
        if (!password_verify($this->request->getPost('current_password'), $user->PW)) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Mật khẩu hiện tại không đúng!');
        }
        
        // Cập nhật mật khẩu mới
        $this->nguoiDungModel->update($userData['id'], [
            'PW' => password_hash($this->request->getPost('new_password'), PASSWORD_DEFAULT),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->to(base_url('nguoidung/dashboard'))
                         ->with('success', 'Đổi mật khẩu thành công!');
    }
} 