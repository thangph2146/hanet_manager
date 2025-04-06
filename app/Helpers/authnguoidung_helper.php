<?php
/**
 * Đây là file class auth_helper các thao tác liên quan đến các xử lý tức thời
 * @author Phùng Duy Vũ <vupd@buh.edu.vn>
 * 4/3/2022
 * 9:05 AM
 *
 * @func current_student() return tất cả các thông tin của student hiện đang đăng nhập
 * @func current_agent($agent) return thông tin trình duyệt mà student sử dụng để điểm danh
 * @func findMinCalculateDistance(&$locations, $latitude_user, $longitude_user, &$minDistance, &$tempLocation) tìm vị trí student điểm danh gần nhất
 * @func calculate_distance($lat1, $lon1, $lat2, $lon2, $unit='N') tìm khoảng cách giữa 2 vị trí.
 * @func current_getMAC() lấy địa chỉ MAC của student dùng để điểm danh
 *
 */

if ( ! function_exists('current_nguoidung')) {
	/**
	 * Lấy thông tin người dùng hiện tại
	 * @return mixed Đối tượng người dùng hoặc null nếu chưa đăng nhập
	 */
	function current_nguoidung()
	{
		$auth = service('authnguoidung');
		$nguoi_dung = $auth->getCurrentNguoiDung();
		return $nguoi_dung;
	}
}

if ( ! function_exists('getInfoNguoiDung')) {
	/**
	 * Lấy thông tin người dùng hiện tại
	 * @return mixed Đối tượng người dùng hoặc null nếu chưa đăng nhập
	 */
	function getInfoNguoiDung()
	{
		$auth = service('authnguoidung');
		$nguoi_dung = $auth->getCurrentNguoiDung();
		return $nguoi_dung;
	}
}

if ( ! function_exists('getFullNameStudent')) {
	/**
	 * Lấy họ tên đầy đủ của người dùng đang đăng nhập
	 * @return string Họ tên của người dùng
	 */
	function getFullNameStudent()
	{
		try {
			$auth = service('authnguoidung');
			$nguoi_dung = $auth->getCurrentNguoiDung();
			
			if ($nguoi_dung === null) {
				log_message('error', 'Nguoi dung is null');
				return 'Guest';
			}
			
			return $auth->getFullName();
		} catch (\Exception $e) {
			log_message('error', 'Error in getFullNameStudent: ' . $e->getMessage());
			return 'Người dùng';
		}
	}
}

if ( ! function_exists('isLoggedInStudent')) {
	/**
	 * Kiểm tra xem người dùng đã đăng nhập hay chưa
	 * @return bool True nếu đã đăng nhập, False nếu chưa
	 */
	function isLoggedInStudent()
	{
		$auth = service('authnguoidung');
		return $auth->isLoggedInStudent();
	}
}

if ( ! function_exists('getNguoiDungId')) {
	/**
	 * Lấy ID người dùng hiện tại
	 * @return int|null ID người dùng hoặc null nếu chưa đăng nhập
	 */
	function getNguoiDungId()
	{
		$nguoi_dung = current_nguoidung();
		if ($nguoi_dung && is_object($nguoi_dung)) {
			return $nguoi_dung->nguoi_dung_id;
		}
		return null;
	}
}

if ( ! function_exists('getNguoiDungEmail')) {
	/**
	 * Lấy email người dùng hiện tại
	 * @return string|null Email người dùng hoặc null nếu chưa đăng nhập
	 */
	function getNguoiDungEmail()
	{
		$nguoi_dung = current_nguoidung();
		if ($nguoi_dung && is_object($nguoi_dung)) {
			return $nguoi_dung->Email;
		}
		return null;
	}
}

if ( ! function_exists('getNguoiDungLastName')) {
	/**
	 * Lấy họ của người dùng hiện tại
	 * @return string Họ của người dùng hoặc chuỗi rỗng nếu không có
	 */
	function getNguoiDungLastName()
	{
		$auth = service('authnguoidung');
		return $auth->getLastName();
	}
}

if ( ! function_exists('getNguoiDungFirstName')) {
	/**
	 * Lấy tên của người dùng hiện tại
	 * @return string Tên của người dùng hoặc chuỗi rỗng nếu không có
	 */
	function getNguoiDungFirstName()
	{
		$auth = service('authnguoidung');
		return $auth->getFirstName();
	}
}

if ( ! function_exists('getFullName')) {
	/**
	 * Lấy họ tên đầy đủ của người dùng đang đăng nhập (alias của getFullNameStudent)
	 * @return string Họ tên của người dùng
	 */
	function getFullName()
	{
		return getFullNameStudent();
	}
}




