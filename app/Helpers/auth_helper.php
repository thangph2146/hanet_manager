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
		$auth = service('auth');

		return $auth->getFullName();
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
