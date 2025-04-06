<?php
/**
 * Đây là file class auth_helper các thao tác liên quan đến các xử lý tức thời
 * @author Phùng Duy Vũ <vupd@buh.edu.vn>
 * 4/3/2022
 * 9:05 AM
 *
 * @func current_user() return tất cả các thông tin của user hiện đang đăng nhập
 * @func current_agent($agent) return thông tin trình duyệt mà user sử dụng để điểm danh
 * @func findMinCalculateDistance(&$locations, $latitude_user, $longitude_user, &$minDistance, &$tempLocation) tìm vị trí user điểm danh gần nhất
 * @func calculate_distance($lat1, $lon1, $lat2, $lon2, $unit='N') tìm khoảng cách giữa 2 vị trí.
 * @func current_getMAC() lấy địa chỉ MAC của user dùng để điểm danh
 *
 */

if ( ! function_exists('current_user')) {

	/**
	 * @return mixed
	 */
	function current_user()
	{
		$auth = service('auth');
		
		return $auth->getCurrentUser();
	}
}

if ( ! function_exists('has_role')) {

	/**
	 * @return mixed
	 */
	function has_role($role)
	{
		$auth = service('auth');

		return $auth->has_role($role);
	}
}

if ( ! function_exists('has_permission')) {

	/**
	 * @return mixed
	 */
	function has_permission($permission)
	{
		$auth = service('auth');

		return $auth->has_permission($permission);
	}
}

if ( ! function_exists('getFullName')) {

	/**
	 * @return mixed
	 */
	function getFullName()
	{
		try {
			$auth = service('auth');
			$user = $auth->getCurrentUser();
			
			if ($user === null) {
				log_message('error', 'User is null');
				return 'Guest';
			}
			
			// Thử lấy tên từ các trường riêng biệt trước
			$nameParts = [];
			
			// Xử lý khi $user là đối tượng
			if (is_object($user)) {
				if (!empty($user->LastName)) {
					$nameParts[] = $user->LastName;
				}
				
				if (!empty($user->MiddleName)) {
					$nameParts[] = $user->MiddleName;
				}
				
				if (!empty($user->FirstName)) {
					$nameParts[] = $user->FirstName;
				}
				
				// Nếu không có các trường riêng biệt, thử dùng FullName
				if (empty($nameParts) && !empty($user->FullName)) {
					return $user->FullName;
				}
			}
			// Xử lý khi $user là mảng
			else if (is_array($user)) {
				// Nếu là phần tử đầu tiên của mảng
				if (!empty($user[0])) {
					$firstUser = $user[0];
					
					if (is_object($firstUser)) {
						if (!empty($firstUser->LastName)) {
							$nameParts[] = $firstUser->LastName;
						}
						
						if (!empty($firstUser->MiddleName)) {
							$nameParts[] = $firstUser->MiddleName;
						}
						
						if (!empty($firstUser->FirstName)) {
							$nameParts[] = $firstUser->FirstName;
						}
						
						// Nếu không có các trường riêng biệt, thử dùng FullName
						if (empty($nameParts) && !empty($firstUser->FullName)) {
							return $firstUser->FullName;
						}
					}
					else if (is_array($firstUser)) {
						if (!empty($firstUser['LastName'])) {
							$nameParts[] = $firstUser['LastName'];
						}
						
						if (!empty($firstUser['MiddleName'])) {
							$nameParts[] = $firstUser['MiddleName'];
						}
						
						if (!empty($firstUser['FirstName'])) {
							$nameParts[] = $firstUser['FirstName'];
						}
						
						// Nếu không có các trường riêng biệt, thử dùng FullName
						if (empty($nameParts) && !empty($firstUser['FullName'])) {
							return $firstUser['FullName'];
						}
					}
				}
			}
			
			// Nếu có các phần tên riêng biệt
			if (!empty($nameParts)) {
				return implode(' ', $nameParts);
			}
			
			// Sử dụng phương thức getFullName của service
			return $auth->getFullName();
			
		} catch (\Exception $e) {
			log_message('error', 'Error in getFullName: ' . $e->getMessage());
			return 'Người dùng';
		}
	}
}

if ( ! function_exists('getFullRole')) {

	/**
	 * @return mixed
	 */
	function getFullRole()
	{
		$auth = service('auth');

		return $auth->getFullRole();
	}
}

if ( ! function_exists('current_agent')) {

	/**
	 * @param $agent
	 * @return mixed|string
	 */
	function current_agent($agent)
	{
		if ($agent->isBrowser()) {
			$currentAgent = $agent->getBrowser() . ' ' . $agent->getVersion();
		} elseif ($agent->isRobot()) {
			$currentAgent = $agent->getRobot();
		} elseif ($agent->isMobile()) {
			$currentAgent = $agent->getMobile();
		} else {
			$currentAgent = 'Unidentified User Agent';
		}

		return $currentAgent;
	}
}
