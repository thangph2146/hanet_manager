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

			$this->rememberLogin($student->nguoi_dung_id);
			
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
		$session->set('nguoi_dung_id', $student->nguoi_dung_id);
	}

	/**
	 * @param $nguoi_dung_id
	 */
	private function rememberLogin($nguoi_dung_id)
	{
		$model = new \App\Models\RememberedLoginModel();
		
		list($token, $expiry) = $model->rememberStudentLogin($nguoi_dung_id);
		
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
		if ( ! session()->has('nguoi_dung_id')) {
			
			return null;
			
		}
		
		$model = new \App\Models\StudentModel();
		
		$student = $model->where('nguoi_dung_id', session()->get('nguoi_dung_id'))->first();

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

		$student = $student_model->where('nguoi_dung_id', $remembered_login['nguoi_dung_id'])->first();
		
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

	public function getFullName()
	{
		$student = $this->getCurrentStudent();
		if ($student === null) {
			return '';
		}
		
		// Kiểm tra nếu $student là một mảng
		if (is_array($student)) {
			// Nếu là mảng, xử lý tương tự như trường hợp đối tượng
			if (!empty($student)) {
				// Cố gắng lấy ra phần tử đầu tiên của mảng
				$firstStudent = $student[0] ?? null;
				
				if (isset($firstStudent) && is_object($firstStudent)) {
					// Nếu phần tử đầu tiên là đối tượng
					$nameParts = [];
					
					if (!empty($firstStudent->LastName)) {
						$nameParts[] = $firstStudent->LastName;
					}
					
					if (!empty($firstStudent->MiddleName)) {
						$nameParts[] = $firstStudent->MiddleName;
					}
					
					if (!empty($firstStudent->FirstName)) {
						$nameParts[] = $firstStudent->FirstName;
					}
					
					if (!empty($nameParts)) {
						return implode(' ', $nameParts);
					}
					
					// Thử lấy FullName nếu không có các trường tên
					return $firstStudent->FullName ?? '';
				} else if (is_array($firstStudent)) {
					// Nếu phần tử đầu tiên là mảng
					$nameParts = [];
					
					if (!empty($firstStudent['LastName'])) {
						$nameParts[] = $firstStudent['LastName'];
					}
					
					if (!empty($firstStudent['MiddleName'])) {
						$nameParts[] = $firstStudent['MiddleName'];
					}
					
					if (!empty($firstStudent['FirstName'])) {
						$nameParts[] = $firstStudent['FirstName'];
					}
					
					if (!empty($nameParts)) {
						return implode(' ', $nameParts);
					}
					
					// Thử lấy FullName nếu không có các trường tên
					return $firstStudent['FullName'] ?? '';
				} else {
					// Trường hợp đặc biệt khi $firstStudent là string
					// Có thể xảy ra khi phần tử đầu tiên trong mảng là một chuỗi
					return $firstStudent ?? '';
				}
			}
			
			return '';
		}
		
		// Nếu $student là một đối tượng, xử lý bình thường
		// Ưu tiên sử dụng các trường LastName, MiddleName và FirstName nếu có
		if (property_exists($student, 'LastName') || property_exists($student, 'MiddleName') || property_exists($student, 'FirstName')) {
			$nameParts = [];
			
			if (!empty($student->LastName)) {
				$nameParts[] = $student->LastName;
			}
			
			if (!empty($student->MiddleName)) {
				$nameParts[] = $student->MiddleName;
			}
			
			if (!empty($student->FirstName)) {
				$nameParts[] = $student->FirstName;
			}
			
			if (!empty($nameParts)) {
				return implode(' ', $nameParts);
			}
		}
		
		// Nếu không có các trường mới hoặc chúng đều trống, sử dụng FullName
		if (property_exists($student, 'FullName') && !empty($student->FullName)) {
			return $student->FullName;
		}
		
		return '';
	}

	/**
	 * Lấy họ của người dùng đã đăng nhập
	 * 
	 * @return string Họ của người dùng
	 */
	public function getLastName()
	{
		$student = $this->getCurrentStudent();
		if ($student === null) {
			return '';
		}
		
		// Kiểm tra nếu $student là một mảng
		if (is_array($student)) {
			if (!empty($student)) {
				$firstStudent = $student[0] ?? null;
				
				// Nếu phần tử đầu tiên là đối tượng
				if (isset($firstStudent) && is_object($firstStudent)) {
					return $firstStudent->LastName ?? '';
				}
				
				// Nếu phần tử đầu tiên là mảng
				if (is_array($firstStudent)) {
					return $firstStudent['LastName'] ?? '';
				}
			}
			return '';
		}
		
		// Nếu $student là một đối tượng
		return $student->LastName ?? '';
	}

	/**
	 * Lấy tên đệm của người dùng đã đăng nhập
	 * 
	 * @return string Tên đệm của người dùng
	 */
	public function getMiddleName()
	{
		$student = $this->getCurrentStudent();
		if ($student === null) {
			return '';
		}
		
		// Kiểm tra nếu $student là một mảng
		if (is_array($student)) {
			if (!empty($student)) {
				$firstStudent = $student[0] ?? null;
				
				// Nếu phần tử đầu tiên là đối tượng
				if (isset($firstStudent) && is_object($firstStudent)) {
					return $firstStudent->MiddleName ?? '';
				}
				
				// Nếu phần tử đầu tiên là mảng
				if (is_array($firstStudent)) {
					return $firstStudent['MiddleName'] ?? '';
				}
			}
			return '';
		}
		
		// Nếu $student là một đối tượng
		return $student->MiddleName ?? '';
	}

	/**
	 * Lấy tên của người dùng đã đăng nhập
	 * 
	 * @return string Tên của người dùng
	 */
	public function getFirstName()
	{
		$student = $this->getCurrentStudent();
		if ($student === null) {
			return '';
		}
		
		// Kiểm tra nếu $student là một mảng
		if (is_array($student)) {
			if (!empty($student)) {
				$firstStudent = $student[0] ?? null;
				
				// Nếu phần tử đầu tiên là đối tượng
				if (isset($firstStudent) && is_object($firstStudent)) {
					return $firstStudent->FirstName ?? '';
				}
				
				// Nếu phần tử đầu tiên là mảng
				if (is_array($firstStudent)) {
					return $firstStudent['FirstName'] ?? '';
				}
			}
			return '';
		}
		
		// Nếu $student là một đối tượng
		return $student->FirstName ?? '';
	}

	/**
	 * Lấy thông tin người dùng đã đăng nhập
	 * 
	 * @return object|null Thông tin người dùng hoặc null nếu không tìm thấy
	 */
	public function getUserData()
	{
		return $this->getCurrentStudent();
	}

}

