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

use App\Models\StudentModel;
use App\Models\StudentInfoModel;
use App\Models\UserModel;

class Login extends BaseController
{

	public function index()
	{
		$googleAuth = service('googleAuth');
		$googleAuthUrl = $googleAuth->getAuthUrl('student');	
		return view('students/login', ['googleAuthUrl' => $googleAuthUrl]);
	}

	public function create_student()
	{
		$email = $this->request->getPost('email');
		$password = $this->request->getPost('password');	
		$login_type = $this->request->getPost('login_type');
		$remember_me = (bool) $this->request->getPost('remember_me');

		$authStudent = service('authStudent');

		if ($authStudent->login($email, $password, $remember_me)) {

			$redirect_url = session('redirect_url') ?? 'students/dashboard';

			unset($_SESSION['redirect_url']);

			return redirect()->to($redirect_url)
							 ->with('info', 'Bạn đã login thành công!')
							 ->withCookies();

		}

		return redirect()->back()
						 ->withInput()
						 ->with('warning', 'Login đã xảy ra lỗi!');

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
		$login_type = $this->request->getPost('login_type');
		$auth = service('auth');

		if ($auth->login($u_email, $password)) {
			$redirect_url = session('redirect_url') ?? 'users/dashboard';
			unset($_SESSION['redirect_url']);

			return redirect()->to($redirect_url)
							 ->with('info', 'Bạn đã login thành công!')
							 ->withCookies();
		} else {
			return redirect()->back()
							 ->withInput()
							 ->with('warning', 'Login đã xảy ra lỗi!');
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
		
		// Nếu có state và state không rỗng, sử dụng nó làm login_type
		if (!empty($state)) {
			$login_type = $state;
		}
		
		// Nếu vẫn không có login_type, sử dụng 'admin' làm mặc định
		if (empty($login_type)) {
			$login_type = 'admin';
		}
		
		if (empty($code)) {
			$redirect = ($login_type == 'student') ? 'login' : 'login/admin';
			return redirect()->to($redirect)
							 ->with('warning', 'Không thể xác thực với Google!');
		}
		
		// Xử lý code để lấy thông tin người dùng
		$googleAuth = service('googleAuth');
		$googleUser = $googleAuth->handleCallback($code, $login_type);
		
		if (empty($googleUser)) {
			$redirect = ($login_type == 'student') ? 'login' : 'login/admin';
			return redirect()->to($redirect)
							 ->with('warning', 'Không thể lấy thông tin từ Google!');
		}
		
		// Kiểm tra xem người dùng đã tồn tại trong hệ thống chưa
		$userModel = new UserModel();
		$user = $userModel->where('u_email', $googleUser['email'])->first();
		
		// Nếu người dùng chưa tồn tại, tạo mới
		if ($user === null) {
			// Tạo người dùng mới từ thông tin Google
			$userData = [
				'u_username' => explode('@', $googleUser['email'])[0], // Tạo username từ email
				'u_email' => $googleUser['email'],
				'u_FullName' => $googleUser['name'],
				'u_status' => 1, // Kích hoạt tài khoản
				'u_google_id' => $googleUser['id'],
				'password' => bin2hex(random_bytes(8)), // Tạo mật khẩu ngẫu nhiên
				'password_confirmation' => bin2hex(random_bytes(8))
			];
			
			// Thử tạo người dùng mới
			try {
				$userId = $userModel->insert($userData);
				
				if (!$userId) {
					return redirect()->to('login/admin')
									 ->with('warning', 'Không thể tạo tài khoản mới!');
				}
				
				// Lấy thông tin người dùng vừa tạo
				$user = $userModel->find($userId);
			} catch (\Exception $e) {
				log_message('error', 'Google Login Error: ' . $e->getMessage());
				return redirect()->to('login/admin')
								 ->with('warning', 'Không thể tạo tài khoản mới: ' . $e->getMessage());
			}
		}
		
		// Đăng nhập người dùng
		if ($googleAuth->loginWithGoogle($googleUser, $login_type)) {
			// Xác định redirect_url dựa trên login_type
			if ($login_type == 'student') {
				$redirect_url = session('redirect_url') ?? 'students/dashboard';
			} else {
				$redirect_url = session('redirect_url') ?? 'users/dashboard';
			}
			
			unset($_SESSION['redirect_url']);
			
			return redirect()->to($redirect_url)
							 ->with('info', 'Bạn đã đăng nhập thành công với Google!')
							 ->withCookies();
		} else {
			$redirect = ($login_type == 'student') ? 'login' : 'login/admin';
			return redirect()->to($redirect)
							 ->with('warning', 'Đăng nhập với Google không thành công!');
		}
	}

	/**
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function delete()
	{
		service('auth')->logout();
		return redirect()->to('login/showlogoutmessage')
						 ->withCookies();
	}

	/**
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function showLogoutMessage()
	{
		return redirect()->to('login/admin')
						 ->with('info', 'bạn đã logout thành công!');
	}

	/*public function deleteStudent()
	{
		service('authStudent')->logout();
		return redirect()->to('login/showlogoutmessageStudent')
						 ->withCookies();
	}*/

	/**
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function showLogoutMessageStudent()
	{
		return redirect()->to('login')
						 ->with('info', 'bạn đã logout thành công!');
	}
}
