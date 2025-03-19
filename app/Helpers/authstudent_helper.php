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

if ( ! function_exists('current_student')) {

	/**
	 * @return mixed
	 */
	function current_student()
	{
		$auth = service('authstudent');
		
		return $auth->getCurrentStudent();
	}
}


if ( ! function_exists('getFullNameStudent')) {

	/**
	 * @return mixed
	 */
	function getFullNameStudent()
	{
		try {
			$auth = service('authstudent');
			$student = $auth->getCurrentStudent();
			if ($student === null) {
				log_message('error', 'Student is null');
				return 'Guest';
			}
			
			// Debug thông tin
			log_message('info', 'Student data: ' . json_encode($student));
			
			// Kiểm tra và lấy FullName
			$fullName = $student->FullName;
			if (empty($fullName)) {
				log_message('error', 'FullName is empty');
				return 'N/A';
			}
			return $fullName;
			
		} catch (\Exception $e) {
			log_message('error', 'Error in getFullName: ' . $e->getMessage());
			return 'Error';
		}
	}

	if ( ! function_exists('isLoggedInStudent')) {

		/**
		 * @return mixed
		 */
		function isLoggedInStudent()
		{
			$auth = service('authstudent');
			
			return $auth->isLoggedInStudent();
		}
	}
}




