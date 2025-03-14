<?php
/**
 * Đây là file class Authentication các thao tác liên quan đến login, user
 * @author Phùng Duy Vũ <vupd@buh.edu.vn>
 * 4/3/2022
 * 9:08 AM
 *
 * @func login($email, $password, $remember_me) xử lý việc login, tạo session, return bool nếu login thành công
 * @func logInUser($user) tạo session(user_id) khi user login thành công
 * @func rememberLogin($user_id) tạo cookie user khi user login thành công và check vào remember me, set cookie(remember_me) với token và thời hạn
 * @func logout() hủy session và cookie nếu có
 * @func getUserFromSession() lấy thông tin user đã login thành công nếu có
 * @func getUserFromRememberCookie() lấy thông tin user nếu cookie có
 * @func getCurrentUser() lấy thông tin user hiện tại đang đăng nhập hệ thống
 * @func isLoggedIn() kiểm tra việc user đã login hay chưa trả về true nếu đang login, false nếu chưa
 *
 */
	
namespace App\Libraries;

class AuthenticationStudent
{
	/**
	 * @var
	 */
	private $student;

	/**
	 * @param $StudentID
	 * @param $password
	 * @param $remember_me
	 * @return bool
	 */
	public function login($email, $password, $remember_me)
	{
		$model = new \App\Models\StudentModel();
		
		$student = $model->where('Email', $email)->first();

		if ($student === null) {
			
			return false;
			
		}
		
		if ( ! $student->verifyPassword($password)) {
			
			return false;
			
		}

		if ( ! $student->status) {
			
			return false;
			
		}

		$this->logInStudent($student);

		if ($remember_me) {

			$this->rememberLogin($student->student_id);
			
		}

		return true;
	}

	/**
	 * @param $student
	 */
	private function logInStudent($student)
	{
		$session = session();
		$session->regenerate();
		$session->set('student_id', $student->student_id);
	}

	/**
	 * @param $student_id
	 */
	private function rememberLogin($student_id)
	{
		$model = new \App\Models\RememberedLoginModel();
		
		list($token, $expiry) = $model->rememberStudentLogin($student_id);
		
		$response = service('response');
		
		$response->setCookie('remember_me', $token, $expiry);
	}

	/**
	 * hủy session và cookie nếu có
	 */
	public function logout()
	{
		$token = service('request')->getCookie('remember_me');
		
		if ($token !== null) {
			
			$model = new \App\Models\RememberedLoginModel();
			
			$model->deleteByToken($token);
		}
		
		service('response')->deleteCookie('remember_me');
		
		session()->destroy();
	}

	/**
	 * @return array|object|void|null
	 */
	private function getStudentFromSession()
	{
		if ( ! session()->has('student_id')) {
			
			return null;
			
		}
		
		$model = new \App\Models\StudentModel();
		
		$student = $model->where('student_id', session()->get('student_id'))->first();

		if ($student && $student->status) {
			
			return $student;
		}
	}

	/**
	 * @return array|object|void|null
	 */
	private function getStudentFromRememberCookie()
	{
		$request = service('request');
		
		$token = $request->getCookie('remember_me');
		
		if ($token === null) {
			
			return null;
		}
		
		$remembered_login_model = new \App\Models\RememberedLoginModel();
		
		$remembered_login = $remembered_login_model->findByToken($token);
		
		if ($remembered_login === null) {
			
			return null;
		}
		
		$student_model = new \App\Models\StudentModel();

		$student = $student_model->where('student_id', $remembered_login['student_id'])->first();
		
		if ($student && $student->StudyStatusID) {
			
			$this->logInStudent($student);
			
			return $student;
		}
	}

	/**
	 * @return array|object|void|null
	 */
	public function getCurrentStudent()
	{
		if ($this->student === null) {

			$this->student = $this->getStudentFromSession();
		}

		if ($this->student === null) {
			
			$this->student = $this->getStudentFromRememberCookie();
		}

		return $this->student;
	}

	/**
	 * @return bool
	 */
	public function isLoggedInStudent()
	{

		return $this->getCurrentStudent() !== null;

	}

	/*private function verifyPassword($password, $MD5Password, $StudentID)
	{
		return $MD5Password == md5("UisStaffID=" . $StudentID . ";UisPassword=" . $password);
	}*/

}

