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

class AuthenticationNguoiDung
{
	/**
	 * @var
	 */
	private $nguoi_dung;

	/**
	 * @param $StudentID
	 * @param $password
	 * @param $remember_me
	 * @return bool
	 */
	public function login($email, $password, $remember_me)
	{
		$model = new \App\Modules\quanlynguoidung\Models\NguoiDungModel();
		
		$nguoi_dung = $model->where('Email', $email)->first();

		if ($nguoi_dung === null) {
			log_message('debug', 'AuthenticationNguoiDung::login - Không tìm thấy người dùng với email: ' . $email);
			return false;
		}
		
		if ( ! $nguoi_dung->verifyPassword($password)) {
			log_message('debug', 'AuthenticationNguoiDung::login - Mật khẩu không hợp lệ cho người dùng: ' . $email);
			return false;
		}

		if ( ! $nguoi_dung->status) {
			log_message('debug', 'AuthenticationNguoiDung::login - Tài khoản không hoạt động: ' . $email);
			return false;
		}

		// Cập nhật thời gian đăng nhập cuối cùng
		$model->updateLastLogin($nguoi_dung->nguoi_dung_id);

		$this->logInNguoiDung($nguoi_dung);

		if ($remember_me) {
			$this->rememberLogin($nguoi_dung->nguoi_dung_id);
		}

		return true;
	}

	/**
	 * @param $student
	 */
	private function logInNguoiDung($nguoi_dung)
	{
		$session = session();
		$session->regenerate();
		$session->set('nguoi_dung_id', $nguoi_dung->nguoi_dung_id);
	}

	/**
	 * @param $nguoi_dung_id
	 */
	private function rememberLogin($nguoi_dung_id)
	{
		$model = new \App\Models\RememberedLoginModel();
		
		list($token, $expiry) = $model->rememberNguoiDungLogin($nguoi_dung_id);
		
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
	 * Lấy thông tin của người dùng từ session hiện tại
	 * 
	 * @return array|object|null Đối tượng người dùng hoặc null nếu không tìm thấy
	 */
	private function getNguoiDungFromSession()
	{
		try {
			// Kiểm tra xem session người dùng có tồn tại không
			if (!session()->has('nguoi_dung_id')) {
				return null;
			}
			
			$nguoi_dung_id = session()->get('nguoi_dung_id');
			
			// Kiểm tra ID người dùng hợp lệ
			if (empty($nguoi_dung_id) || !is_numeric($nguoi_dung_id)) {
				log_message('warning', 'AuthenticationNguoiDung::getNguoiDungFromSession - ID người dùng không hợp lệ: ' . $nguoi_dung_id);
				return null;
			}
			
			$model = new \App\Modules\quanlynguoidung\Models\NguoiDungModel();
			
			// Tìm người dùng theo ID từ session
			$nguoi_dung = $model->where('nguoi_dung_id', $nguoi_dung_id)->first();
			
			// Kiểm tra người dùng có tồn tại và đang hoạt động
			if ($nguoi_dung && $nguoi_dung->status) {
				return $nguoi_dung;
			}
			
			// Người dùng không tồn tại hoặc không hoạt động
			if ($nguoi_dung) {
				log_message('warning', 'AuthenticationNguoiDung::getNguoiDungFromSession - Tài khoản người dùng không hoạt động: ' . $nguoi_dung_id);
			} else {
				log_message('warning', 'AuthenticationNguoiDung::getNguoiDungFromSession - Không tìm thấy người dùng: ' . $nguoi_dung_id);
			}
			
			return null;
		} catch (\Exception $e) {
			log_message('error', 'AuthenticationNguoiDung::getNguoiDungFromSession - Lỗi: ' . $e->getMessage());
			return null;
		}
	}

	/**
	 * Lấy thông tin người dùng từ cookie "remember_me"
	 * 
	 * @return array|object|null Đối tượng người dùng hoặc null nếu không tìm thấy
	 */
	private function getNguoiDungFromRememberCookie()
	{
		try {
			$request = service('request');
			
			// Lấy token từ cookie
			$token = $request->getCookie('remember_me');
			
			if (empty($token)) {
				return null;
			}
			
			// Tìm thông tin đăng nhập từ token
			$remembered_login_model = new \App\Models\RememberedLoginModel();
			$remembered_login = $remembered_login_model->findByToken($token);
			
			if ($remembered_login === null) {
				log_message('warning', 'AuthenticationNguoiDung::getNguoiDungFromRememberCookie - Token không hợp lệ hoặc đã hết hạn');
				return null;
			}
			
			// Lấy ID người dùng từ thông tin đăng nhập
			$nguoi_dung_id = $remembered_login['nguoi_dung_id'] ?? null;
			
			if (empty($nguoi_dung_id) || !is_numeric($nguoi_dung_id)) {
				log_message('warning', 'AuthenticationNguoiDung::getNguoiDungFromRememberCookie - ID người dùng không hợp lệ từ token');
				return null;
			}
			
			// Tìm thông tin người dùng
			$nguoi_dung_model = new \App\Modules\quanlynguoidung\Models\NguoiDungModel();
			$nguoi_dung = $nguoi_dung_model->where('nguoi_dung_id', $nguoi_dung_id)->first();
			
			// Kiểm tra người dùng có tồn tại và còn hoạt động
			// StudyStatusID là trạng thái học tập của sinh viên, cần kiểm tra cả status để đảm bảo tài khoản còn hoạt động
			if ($nguoi_dung && (($nguoi_dung->StudyStatusID ?? 0) > 0) && ($nguoi_dung->status ?? false)) {
				// Đăng nhập lại người dùng để cập nhật session
				$this->logInNguoiDung($nguoi_dung);
				return $nguoi_dung;
			}
			
			// Log thông tin về người dùng không hợp lệ
			if ($nguoi_dung) {
				log_message('warning', 'AuthenticationNguoiDung::getNguoiDungFromRememberCookie - Tài khoản không hoạt động');
			} else {
				log_message('warning', 'AuthenticationNguoiDung::getNguoiDungFromRememberCookie - Không tìm thấy người dùng từ token');
			}
			
			return null;
		} catch (\Exception $e) {
			log_message('error', 'AuthenticationNguoiDung::getNguoiDungFromRememberCookie - Lỗi: ' . $e->getMessage());
			return null;
		}
	}

	/**
	 * Lấy thông tin người dùng hiện tại đang đăng nhập
	 * 
	 * @return array|object|null Thông tin người dùng hoặc null nếu chưa đăng nhập
	 */
	public function getCurrentNguoiDung()
	{
		try {
			// Nếu đã lấy thông tin sinh viên trước đó và khác null, trả về luôn
			if ($this->nguoi_dung !== null) {
				return $this->nguoi_dung;
			}
			
			// Thử lấy thông tin từ session trước
			$this->nguoi_dung = $this->getNguoiDungFromSession();
			
			// Nếu không có trong session, thử lấy từ cookie remember me
			if ($this->nguoi_dung === null) {
				$this->nguoi_dung = $this->getNguoiDungFromRememberCookie();
			}
			
			// Nếu student vẫn là null sau khi thử cả hai cách
			if ($this->nguoi_dung === null) {
				log_message('debug', 'AuthenticationNguoiDung::getCurrentNguoiDung - Không thể lấy thông tin người dùng từ session hoặc cookie');
				return null;
			}
			
			// Log thành công cho mục đích debug (cấp độ debug)
			if (ENVIRONMENT !== 'production') {
				$nguoi_dung_id = (is_object($this->nguoi_dung) && isset($this->nguoi_dung->nguoi_dung_id)) 
					? $this->nguoi_dung->nguoi_dung_id 
					: (is_array($this->nguoi_dung) && isset($this->nguoi_dung['nguoi_dung_id']) 
						? $this->nguoi_dung['nguoi_dung_id'] 
						: 'unknown');
				
				log_message('debug', 'AuthenticationNguoiDung::getCurrentNguoiDung - Đã lấy thông tin người dùng ID: ' . $nguoi_dung_id);
			}
			
			return $this->nguoi_dung;
		} catch (\Exception $e) {
			// Ghi log lỗi
			log_message('error', 'AuthenticationNguoiDung::getCurrentNguoiDung - Lỗi: ' . $e->getMessage());
			
			// Trong trường hợp có lỗi, trả về null
			$this->nguoi_dung = null;
			return null;
		}
	}

	/**
	 * @return bool
	 */
	public function isLoggedInStudent()
	{

		return $this->getCurrentNguoiDung() !== null;

	}

	/*private function verifyPassword($password, $MD5Password, $StudentID)
	{
		return $MD5Password == md5("UisStaffID=" . $StudentID . ";UisPassword=" . $password);
	}*/

	public function getFullName()
	{
		$nguoi_dung = $this->getCurrentNguoiDung();
		if ($nguoi_dung === null) {
			return '';
		}
		
		// Kiểm tra nếu $nguoi_dung là một mảng
		if (is_array($nguoi_dung)) {
			// Nếu là mảng, xử lý tương tự như trường hợp đối tượng
			if (!empty($nguoi_dung)) {
				// Cố gắng lấy ra phần tử đầu tiên của mảng
				$firstNguoiDung = $nguoi_dung[0] ?? null;
				
				if (isset($firstNguoiDung) && is_object($firstNguoiDung)) {
					// Nếu phần tử đầu tiên là đối tượng
					$nameParts = [];
					
					if (!empty($firstNguoiDung->LastName)) {
						$nameParts[] = $firstNguoiDung->LastName;
					}
					
					if (!empty($firstNguoiDung->MiddleName)) {
						$nameParts[] = $firstNguoiDung->MiddleName;
					}
					
					if (!empty($firstNguoiDung->FirstName)) {
						$nameParts[] = $firstNguoiDung->FirstName;
					}
					
					if (!empty($nameParts)) {
						return implode(' ', $nameParts);
					}
					
					// Thử lấy FullName nếu không có các trường tên
					return $firstNguoiDung->FullName ?? '';
				} else if (is_array($firstNguoiDung)) {
					// Nếu phần tử đầu tiên là mảng
					$nameParts = [];
					
					if (!empty($firstNguoiDung['LastName'])) {
						$nameParts[] = $firstNguoiDung['LastName'];
					}
					
					if (!empty($firstNguoiDung['MiddleName'])) {
						$nameParts[] = $firstNguoiDung['MiddleName'];
					}
					
					if (!empty($firstNguoiDung['FirstName'])) {
						$nameParts[] = $firstNguoiDung['FirstName'];
					}
					
					if (!empty($nameParts)) {
						return implode(' ', $nameParts);
					}
					
					// Thử lấy FullName nếu không có các trường tên
					return $firstNguoiDung['FullName'] ?? '';
				} else {
					// Trường hợp đặc biệt khi $firstNguoiDung là string
					// Có thể xảy ra khi phần tử đầu tiên trong mảng là một chuỗi
					return $firstNguoiDung ?? '';
				}
			}
			
			return '';
		}
		
		// Nếu $student là một đối tượng, xử lý bình thường
		// Ưu tiên sử dụng các trường LastName, MiddleName và FirstName nếu có
		if (property_exists($nguoi_dung, 'LastName') || property_exists($nguoi_dung, 'MiddleName') || property_exists($nguoi_dung, 'FirstName')) {
			$nameParts = [];
			
			if (!empty($nguoi_dung->LastName)) {
				$nameParts[] = $nguoi_dung->LastName;
			}
			
			if (!empty($nguoi_dung->MiddleName)) {
				$nameParts[] = $nguoi_dung->MiddleName;
			}
			
			if (!empty($nguoi_dung->FirstName)) {
				$nameParts[] = $nguoi_dung->FirstName;
			}
			
			if (!empty($nameParts)) {
				return implode(' ', $nameParts);
			}
		}
		
		// Nếu không có các trường mới hoặc chúng đều trống, sử dụng FullName
		if (property_exists($nguoi_dung, 'FullName') && !empty($nguoi_dung->FullName)) {
			return $nguoi_dung->FullName;
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
		$nguoi_dung = $this->getCurrentNguoiDung();
		if ($nguoi_dung === null) {
			return '';
		}
		
		// Kiểm tra nếu $nguoi_dung là một mảng
		if (is_array($nguoi_dung)) {
			if (!empty($nguoi_dung)) {
				$firstNguoiDung = $nguoi_dung[0] ?? null;
				
				// Nếu phần tử đầu tiên là đối tượng
				if (isset($firstNguoiDung) && is_object($firstNguoiDung)) {
					return $firstNguoiDung->LastName ?? '';
				}
				
				// Nếu phần tử đầu tiên là mảng
				if (is_array($firstNguoiDung)) {
					return $firstNguoiDung['LastName'] ?? '';
				}
			}
			return '';
		}
		
		// Nếu $nguoi_dung là một đối tượng
		return $nguoi_dung->LastName ?? '';
	}

	/**
	 * Lấy tên đệm của người dùng đã đăng nhập
	 * 
	 * @return string Tên đệm của người dùng
	 */
	public function getMiddleName()
	{
		$nguoi_dung = $this->getCurrentNguoiDung();
		if ($nguoi_dung === null) {
			return '';
		}
		
		// Kiểm tra nếu $nguoi_dung là một mảng
		if (is_array($nguoi_dung)) {
			if (!empty($nguoi_dung)) {
				$firstNguoiDung = $nguoi_dung[0] ?? null;
				
				// Nếu phần tử đầu tiên là đối tượng
				if (isset($firstNguoiDung) && is_object($firstNguoiDung)) {
					return $firstNguoiDung->MiddleName ?? '';
				}
				
				// Nếu phần tử đầu tiên là mảng
				if (is_array($firstNguoiDung)) {
					return $firstNguoiDung['MiddleName'] ?? '';
				}
			}
			return '';
		}
		
		// Nếu $nguoi_dung là một đối tượng
		return $nguoi_dung->MiddleName ?? '';
	}

	/**
	 * Lấy tên của người dùng đã đăng nhập
	 * 
	 * @return string Tên của người dùng
	 */
	public function getFirstName()
	{
		$nguoi_dung = $this->getCurrentNguoiDung();
		if ($nguoi_dung === null) {
			return '';
		}
		
		// Kiểm tra nếu $nguoi_dung là một mảng
		if (is_array($nguoi_dung)) {
			if (!empty($nguoi_dung)) {
				$firstNguoiDung = $nguoi_dung[0] ?? null;
				
				// Nếu phần tử đầu tiên là đối tượng
				if (isset($firstNguoiDung) && is_object($firstNguoiDung)) {
					return $firstNguoiDung->FirstName ?? '';
				}
				
				// Nếu phần tử đầu tiên là mảng
				if (is_array($firstNguoiDung)) {
					return $firstNguoiDung['FirstName'] ?? '';
				}
			}
			return '';
		}
		
		// Nếu $nguoi_dung là một đối tượng
		return $nguoi_dung->FirstName ?? '';
	}

	/**
	 * Lấy thông tin người dùng đã đăng nhập
	 * 
	 * @return object|null Thông tin người dùng hoặc null nếu không tìm thấy
	 */
	public function getUserData()
	{
		return $this->getCurrentNguoiDung();
	}

}

