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

		return view('Login/index');
	}

	/*public function create_student()
	{
		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');
		$remember_me = (bool) $this->request->getPost('remember_me');

		$authStudent = service('authStudent');

		if ($authStudent->login($username, $password, $remember_me)) {

			$redirect_url = session('redirect_url') ?? 'Students/dashboard';

			unset($_SESSION['redirect_url']);

			return redirect()->to($redirect_url)
							 ->with('info', 'Bạn đã login thành công!')
							 ->withCookies();

		} else {

			return redirect()->back()
							 ->withInput()
							 ->with('warning', 'Login đã xảy ra lỗi!');
		}
	}*/

	public function admin()
	{
		return view('Login/new');
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

			$redirect_url = session('redirect_url') ?? 'Users/dashboard';

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
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function delete()
	{
		service('auth')->logout();
		return redirect()->to('Login/showLogoutMessage')
						 ->withCookies();
	}

	/**
	 * @return \CodeIgniter\HTTP\RedirectResponse
	 */
	public function showLogoutMessage()
	{
		return redirect()->to('Login/admin')
						 ->with('info', 'bạn đã logout thành công!');
	}

	/*public function deleteStudent()
	{
		service('authStudent')->logout();
		return redirect()->to('Login/showLogoutMessageStudent')
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
