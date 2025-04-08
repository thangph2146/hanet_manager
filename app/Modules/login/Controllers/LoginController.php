<?php

namespace App\Modules\login\Controllers;

use App\Controllers\BaseController;
use App\Models\StudentModel;
use App\Models\StudentInfoModel;
use App\Models\UserModel;

class LoginController extends BaseController
{
    public function index()
    {
        // Lấy tham số redirect từ URL nếu có
        $redirect = $this->request->getGet('redirect');
        if (!empty($redirect)) {
            session()->set('redirect_url', urldecode($redirect));
        }
        
        $googleAuth = service('googleAuth');
        $googleAuthUrl = $googleAuth->getAuthUrl('nguoidung');    
        return view('App\Modules\login\nguoidung\Views\login', ['googleAuthUrl' => $googleAuthUrl]);
    }

    public function register()
    {
        // Return register page for students
        return view('App\Modules\login\nguoidung\Views\register');
    }

    public function create_nguoidung()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');    
        $remember_me = (bool) $this->request->getPost('remember_me');

        $authnguoidung = service('authnguoidung');

        if ($authnguoidung->login($email, $password, $remember_me)) {
            // Cập nhật thời gian đăng nhập cuối cùng
            $nguoi_dung = $authnguoidung->getCurrentNguoiDung();
          
            $redirect_url = session('redirect_url') ?? '/';
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirect_url)
                             ->with('success', 'Đăng nhập thành công!')
                             ->withCookies();
        }

        return redirect()->back()
                         ->withInput()
                         ->with('warning', 'Đăng nhập không thành công! Vui lòng kiểm tra email và mật khẩu.');
    }

    /**
     * Xử lý đăng ký tài khoản sinh viên mới
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create_nguoidung_account()
    {
        $rules = [
            'fullname' => [
                'label' => 'Họ và tên',
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => '{field} không được để trống',
                    'min_length' => '{field} phải có ít nhất {param} ký tự',
                    'max_length' => '{field} không được quá {param} ký tự'
                ]
            ],
            'email' => [
                'label' => 'Email sinh viên',
                'rules' => 'required|valid_email|is_unique[nguoi_dung.Email]',
                'errors' => [
                    'required' => '{field} không được để trống',
                    'valid_email' => '{field} phải đúng định dạng',
                    'is_unique' => '{field} đã tồn tại trong hệ thống'
                ]
            ],
            'username' => [
                'label' => 'Tên của bạn',
                'rules' => 'required|min_length[2]|max_length[50]',
                'errors' => [
                    'required' => '{field} không được để trống',
                    'min_length' => '{field} phải có ít nhất {param} ký tự',
                    'max_length' => '{field} không được quá {param} ký tự'
                ]
            ],
            'password' => [
                'label' => 'Mật khẩu',
                'rules' => 'required|min_length[6]|max_length[255]',
                'errors' => [
                    'required' => '{field} không được để trống',
                    'min_length' => '{field} phải có ít nhất {param} ký tự',
                    'max_length' => '{field} không được quá {param} ký tự'
                ]
            ],
            'password_confirm' => [
                'label' => 'Xác nhận mật khẩu',
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => '{field} không được để trống',
                    'matches' => '{field} không khớp với mật khẩu'
                ]
            ],
            'mobile' => [
                'label' => 'Số điện thoại',
                'rules' => 'permit_empty|min_length[10]|max_length[20]',
                'errors' => [
                    'min_length' => '{field} phải có ít nhất {param} ký tự',
                    'max_length' => '{field} không được quá {param} ký tự'
                ]
            ],
            'agree' => [
                'label' => 'Điều khoản sử dụng',
                'rules' => 'required',
                'errors' => [
                    'required' => 'Bạn phải đồng ý với {field}'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', $this->validator->getErrors());
        }
        
        // Lấy dữ liệu từ form
        $username = $this->request->getPost('username');
        $fullname = $this->request->getPost('fullname');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $mobile = $this->request->getPost('mobile');
        
        // Tạo AccountId từ email sinh viên
        $accountId = explode('@', $email)[0]; // Lấy phần trước @ làm tên đăng nhập

        // Phân tách họ tên thành các thành phần
        $nameParts = explode(' ', $fullname);
        $firstName = array_pop($nameParts); // Phần tử cuối cùng là tên
        $lastName = array_shift($nameParts) ?? ''; // Phần tử đầu tiên là họ
        $middleName = implode(' ', $nameParts); // Phần còn lại là tên đệm

        // Kiểm tra xem AccountId đã tồn tại chưa
        $nguoiDungModel = new \App\Modules\quanlynguoidung\Models\NguoiDungModel();
        if ($nguoiDungModel->isAccountIdExists($accountId)) {
            $accountId = $accountId . rand(100, 999);
        }
        
        // Tạo dữ liệu người dùng mới
        $nguoiDungData = [
            'AccountId' => $accountId,
            'FirstName' => $firstName,
            'MiddleName' => $middleName,
            'LastName' => $lastName,
            'FullName' => $fullname,
            'Email' => $email,
            'MobilePhone' => $mobile,
            'mat_khau_local' => password_hash($password, PASSWORD_DEFAULT),
            'loai_nguoi_dung_id' => 2, // ID cho loại người dùng sinh viên
            'status' => 1
        ];
        
        try {
            if ($nguoiDungModel->insert($nguoiDungData)) {
                // Đăng nhập tự động sau khi đăng ký
                $authnguoidung = service('authnguoidung');
                if ($authnguoidung->login($email, $password, false)) {
                    return redirect()->to('/')
                                    ->with('success', 'Đăng ký tài khoản thành công và đã đăng nhập!')
                                    ->withCookies();
                }
                
                // Nếu đăng nhập tự động không thành công, chuyển hướng đến trang đăng nhập
                return redirect()->to('login/nguoi-dung')
                                ->with('success', 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.');
            } else {
                $errors = $nguoiDungModel->errors();
                if (empty($errors)) {
                    $errors = ['database' => 'Không thể tạo tài khoản. Vui lòng thử lại sau.'];
                }
                
                return redirect()->back()
                                ->withInput()
                                ->with('error', $errors);
            }
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi đăng ký: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Đã xảy ra lỗi khi đăng ký. Vui lòng thử lại sau.');
        }
    }

    /**
     * Xử lý callback từ Google sau khi sinh viên đăng nhập
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function googleCallback($login_type = null)
    {
        // Lấy code từ callback URL
        $code = $this->request->getGet('code');
        
        // Lấy state từ query string (chứa login_type)
        $state = $this->request->getGet('state');
        
        // In ra thông tin để debug (có thể xóa sau khi đã hoạt động)
        log_message('debug', 'Google Callback Student - State: ' . $state . ', Login Type param: ' . $login_type);
        
        // Đảm bảo login_type là 'student'
        $login_type = 'student';
        
        if (empty($code)) {
            return redirect()->to('login')
                            ->with('warning', 'Không thể xác thực với Google!');
        }
        
        // Xử lý code để lấy thông tin người dùng
        $googleAuth = service('googleAuth');
        $googleUser = $googleAuth->handleCallback($code, $login_type);
        
        if (empty($googleUser)) {
            return redirect()->to('login')
                            ->with('warning', 'Không thể lấy thông tin từ Google!');
        }
        
        // Tìm sinh viên theo email
        $model = new StudentModel();
        $user = $model->where('Email', $googleUser['email'])->first();
        
        // Hiển thị thông báo phù hợp nếu không tìm thấy người dùng
        if ($user === null) {
            return redirect()->to('login')
                            ->with('warning', 'Không tìm thấy tài khoản sinh viên với email: ' . $googleUser['email']);
        }
        
        // Đăng nhập sinh viên
        if ($googleAuth->loginWithGoogle($googleUser, $login_type)) {
            $redirect_url = session('redirect_url') ?? '/';
            unset($_SESSION['redirect_url']);
            
            return redirect()->to($redirect_url)
                            ->with('info', 'Bạn đã đăng nhập thành công với Google!')
                            ->withCookies();
        } else {
            return redirect()->to('login')
                            ->with('warning', 'Đăng nhập với Google không thành công!');
        }
    }

    public function deletenguoidung()
    {
        $nguoi_dung = service('authnguoidung')->getCurrentNguoiDung();
        $name = $nguoi_dung ? $nguoi_dung->getFullName() : 'Người dùng';
        
        service('authnguoidung')->logout();
        
        return redirect()->to('login/nguoi-dung')
                         ->with('success', 'Đăng xuất thành công! Hẹn gặp lại ' . $name)
                         ->withCookies();
    }

    public function logoutnguoidung()
    {
        $nguoi_dung = service('authnguoidung')->getCurrentNguoiDung();
        $name = $nguoi_dung ? $nguoi_dung->getFullName() : 'Người dùng';
        
        service('authnguoidung')->logout();
        
        return redirect()->to('login/nguoi-dung')
                         ->with('success', 'Đăng xuất thành công! Hẹn gặp lại ' . $name)
                         ->withCookies();
    }

    public function showLogoutMessageStudent()
    {
        return redirect()->to('login/nguoi-dung')
                         ->with('info', 'Bạn đã đăng xuất thành công!');
    }
} 