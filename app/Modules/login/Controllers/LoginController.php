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
        $googleAuth = service('googleAuth');
        $googleAuthUrl = $googleAuth->getAuthUrl('student');    
        return view('App\Modules\login\student\Views\login', ['googleAuthUrl' => $googleAuthUrl]);
    }
    public function register()
    {
        return view('App\Modules\login\student\Views\register');
    }
    public function create_student()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');    
        $remember_me = (bool) $this->request->getPost('remember_me');

        $authStudent = service('authstudent');

        if ($authStudent->login($email, $password, $remember_me)) {
            $redirect_url = session('redirect_url') ?? '/';
            unset($_SESSION['redirect_url']);

            return redirect()->to($redirect_url)
                             ->with('info', 'Bạn đã login thành công!')
                             ->withCookies();
        }

        return redirect()->back()
                         ->withInput()
                         ->with('warning', 'Login đã xảy ra lỗi!');
    }

    /**
     * Xử lý đăng ký tài khoản sinh viên mới
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create_student_account()
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

        // Kiểm tra xem AccountId đã tồn tại chưa
        $studentModel = new StudentModel();
        $existingAccount = $studentModel->where('AccountId', $accountId)->first();
        
        // Nếu đã tồn tại, thêm số ngẫu nhiên vào cuối
        if ($existingAccount) {
            $accountId = $accountId . rand(100, 999);
        }
        
        // Tạo dữ liệu người dùng mới
        $studentData = [
            'AccountId' => $accountId,
            'FirstName' => $username,
            'FullName' => $fullname,
            'Email' => $email,
            'MobilePhone' => $mobile,
            'PW' => password_hash($password, PASSWORD_DEFAULT),
            'mat_khau_local' => password_hash($password, PASSWORD_DEFAULT),
            'loai_nguoi_dung_id' => 2, // ID cho loại người dùng sinh viên
            'status' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        try {
            if ($studentModel->insert($studentData)) {
                // Đăng nhập tự động sau khi đăng ký
                $authStudent = service('authstudent');
                if ($authStudent->login($email, $password, false)) {
                    return redirect()->to('/')
                                    ->with('success', 'Đăng ký tài khoản thành công và đã đăng nhập!')
                                    ->withCookies();
                }
                
                // Nếu đăng nhập tự động không thành công, chuyển hướng đến trang đăng nhập
                return redirect()->to('login/nguoi-dung')
                                ->with('success', 'Đăng ký tài khoản thành công! Vui lòng đăng nhập.');
            } else {
                $errors = $studentModel->errors();
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

    public function deleteStudent()
    {
        service('authStudent')->logout();
        return redirect()->to('login/showlogoutmessagestudent')
                         ->withCookies();
    }

    public function logoutStudent()
    {
        service('authStudent')->logout();
        return redirect()->to('login/showlogoutmessagestudent')
                         ->withCookies();
    }

    public function showLogoutMessageStudent()
    {
        return redirect()->to('login')
                         ->with('info', 'Bạn đã đăng xuất thành công!');
    }
} 