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
	/**
	 * @return string
	 */
	public function index()
	{
		/*$studentModel = new StudentInfoModel();
		$studentID = '030138220294';
		$password = '123456';
		$PW = md5("UisStaffID=" . $studentID . ";UisPassword=" . $password);
		//$UpdateDate = date("Y-m-d H:i:s");
		$studentModel->protect(FALSE)->set(['PW' => $PW])->where('StudentID', $studentID)->update();*/

		// Lấy URL đăng nhập Google
		$googleAuth = service('googleAuth');
		$googleAuthUrl = $googleAuth->getAuthUrl();
		
		return view('login/index', ['googleAuthUrl' => $googleAuthUrl]);
	}



	public function admin()
	{
		// Lấy URL đăng nhập Google
		$googleAuth = service('googleAuth');
		$googleAuthUrl = $googleAuth->getAuthUrl();
		
		return view('login/new', ['googleAuthUrl' => $googleAuthUrl]);
	}

	/**
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function create()
	{
		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');

		$auth = service('auth');

		if ($auth->login($username, $password)) {
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
	public function googleCallback()
	{
		// Lấy code từ callback URL
		$code = $this->request->getGet('code');
		
		if (empty($code)) {
			return redirect()->to('login/admin')
							 ->with('warning', 'Không thể xác thực với Google!');
		}
		
		// Xử lý code để lấy thông tin người dùng
		$googleAuth = service('googleAuth');
		$googleUser = $googleAuth->handleCallback($code);
		
		if (empty($googleUser)) {
			return redirect()->to('login/admin')
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
		if ($googleAuth->loginWithGoogle($googleUser)) {
			$redirect_url = session('redirect_url') ?? 'users/dashboard';
			unset($_SESSION['redirect_url']);
			
			return redirect()->to($redirect_url)
							 ->with('info', 'Bạn đã đăng nhập thành công với Google!')
							 ->withCookies();
		} else {
			return redirect()->to('login/admin')
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
		return redirect()->to('Login')
						 ->with('info', 'bạn đã logout thành công!');
	}
}
