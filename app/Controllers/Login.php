<?php
/**
 * Đây là file class Controller Login thao tác liên quan đến vị trí login
 * @author	Phùng Duy Vũ <vupd@buh.edu.vn>
 *
 * @func index() hiển thị trang login, để user đăng nhập.
 * @func create() tạo session user nếu user login thành công.
 * @func delete() hủy session bằng cách gọi @func logout.
 * @func showLogoutMessage gửi tin nhắn khi user login thành công.
 */
namespace App\Controllers;

use App\Modules\quanlynguoidung\Models\NguoiDungModel;
use App\Models\StudentInfoModel;
use App\Models\UserModel;

class Login extends BaseController
{
	// Login Nguoi Dung
	public function index()
	{
		// Lấy tham số redirect từ URL nếu có
		$redirect = $this->request->getGet('redirect');
		if (!empty($redirect)) {
			session()->set('redirect_url', urldecode($redirect));
		}
		
		$googleAuth = service('googleAuth');
		$googleAuthUrl = $googleAuth->getAuthUrl('nguoidung');	
		return view('login/nguoidung/Views/login', ['googleAuthUrl' => $googleAuthUrl]);
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
			
			$redirect_url = session('redirect_url') ?? 'nguoi-dung/dashboard';

			unset($_SESSION['redirect_url']);

			return redirect()->to($redirect_url)
							 ->with('success', 'Đăng nhập thành công!')
							 ->withCookies();

		}

		return redirect()->back()
						 ->withInput()
						 ->with('warning', 'Đăng nhập không thành công! Vui lòng kiểm tra email và mật khẩu.');

	}

	public function deleteNguoiDung()
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
	
	public function showlogoutmessagenguoidung()
	{
		return redirect()->to('login/nguoi-dung')
						 ->with('info', 'Bạn đã đăng xuất thành công!');
	}

	// Login Admin
	public function admin()
	{
		// Lấy URL đăng nhập Google
		$googleAuth = service('googleAuth');
		$googleAuthUrl = $googleAuth->getAuthUrl('admin');
		
		return view('login/new', ['googleAuthUrl' => $googleAuthUrl]);
	}

	/**
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function create()
	{
		$u_email = $this->request->getPost('u_email');
		$password = $this->request->getPost('password');
		$auth = service('auth');

		if ($auth->login($u_email, $password)) {
			$redirect_url = session('redirect_url') ?? 'users/dashboard';
			unset($_SESSION['redirect_url']);

			return redirect()->to($redirect_url)
							 ->with('success', 'Đăng nhập thành công!')
							 ->withCookies();
		} else {
			return redirect()->back()
							 ->withInput()
							 ->with('warning', 'Đăng nhập không thành công! Vui lòng kiểm tra email và mật khẩu.');
		}
	}
	
	/**
	 * Xử lý callback từ Google sau khi người dùng đăng nhập
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function googleCallback($login_type = null)
	{
		// Lấy code từ callback URL
		$code = $this->request->getGet('code');
		
		// Lấy state từ query string (chứa login_type)
		$state = $this->request->getGet('state');
		
		// In ra thông tin để debug (có thể xóa sau khi đã hoạt động)
		log_message('debug', 'Google Callback - State: ' . $state . ', Login Type param: ' . $login_type);
		
		// Nếu có state và state không rỗng, sử dụng nó làm login_type
		if (!empty($state)) {
			$login_type = $state;
		}
		
		// Nếu vẫn không có login_type, sử dụng 'admin' làm mặc định
		if (empty($login_type)) {
			$login_type = 'admin';
		}
		
		if (empty($code)) {
			$redirect = ($login_type == 'nguoidung') ? 'login/nguoi-dung' : 'login/admin';
			return redirect()->to($redirect)
							 ->with('warning', 'Không thể xác thực với Google!');
		}
		
		// Xử lý code để lấy thông tin người dùng
		$googleAuth = service('googleAuth');
		$googleUser = $googleAuth->handleCallback($code, $login_type);
		
		if (empty($googleUser)) {
			$redirect = ($login_type == 'nguoidung') ? 'login/nguoi-dung' : 'login/admin';
			return redirect()->to($redirect)
							 ->with('warning', 'Không thể lấy thông tin từ Google!');
		}
		
		// Xác định model và điều kiện tìm kiếm dựa trên login_type
		if ($login_type == 'nguoidung') {
			$model = new NguoiDungModel();
			$user = $model->where('Email', $googleUser['email'])->first();
			$redirect_failure = 'login/nguoi-dung';
			
			// Hiển thị thông báo phù hợp nếu không tìm thấy người dùng
			if ($user === null) {
				return redirect()->to($redirect_failure)
								 ->with('warning', 'Không tìm thấy tài khoản người dùng với email: ' . $googleUser['email']);
			}
		} else {
			$model = new UserModel();
			$user = $model->where('u_email', $googleUser['email'])->first();
			$redirect_failure = 'login/admin';
			
			// Hiển thị thông báo phù hợp nếu không tìm thấy người dùng
			if ($user === null) {
				return redirect()->to($redirect_failure)
								 ->with('warning', 'Không tìm thấy tài khoản quản trị với email: ' . $googleUser['email']);
			}
		}
		
		// Đăng nhập người dùng
		if ($googleAuth->loginWithGoogle($googleUser, $login_type)) {
			// Xác định redirect_url dựa trên login_type
			if ($login_type == 'nguoidung') {
				$redirect_url = session('redirect_url') ?? 'nguoi-dung/dashboard';
			} else {
				$redirect_url = session('redirect_url') ?? 'users/dashboard';
			}
			
			unset($_SESSION['redirect_url']);
			
			return redirect()->to($redirect_url)
							 ->with('info', 'Bạn đã đăng nhập thành công với Google!')
							 ->withCookies();
		} else {
			$redirect = ($login_type == 'nguoidung') ? 'login/nguoi-dung' : 'login/admin';
			return redirect()->to($redirect)
							 ->with('warning', 'Đăng nhập với Google không thành công!');
		}
	}

	/**
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function delete()
	{
		$user = service('auth')->getCurrentUser();
		$name = $user ? $user->name : 'Quản trị viên';
		
		service('auth')->logout();
		
		return redirect()->to('login/admin')
						 ->with('success', 'Đăng xuất thành công! Hẹn gặp lại ' . $name)
						 ->withCookies();
	}

	/**
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function showLogoutMessage()
	{
		return redirect()->to('login/admin')
						 ->with('info', 'Bạn đã đăng xuất thành công!');
	}

	/*public function deleteStudent()
	{
		service('authnguoidung')->logout();
		return redirect()->to('login/showlogoutmessageStudent')
						 ->withCookies();
	}*/

	/**
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function showLogoutMessageStudent()
	{
		return redirect()->to('login/nguoi-dung')
						 ->with('info', 'Bạn đã đăng xuất thành công!');
	}
}
