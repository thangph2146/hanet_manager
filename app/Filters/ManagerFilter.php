<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ManagerFilter implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		helper('auth');
		$routes = service('router');
		if (! has_permission(class_basename($routes->controllerName()).'_'.$routes->methodName())) {
			// Lưu thông tin lỗi vào session
			session()->setFlashdata('error', 'Bạn không có quyền truy cập vào tài nguyên này.');
			
			// Ghi log
			log_message('notice', 'Người dùng không có quyền truy cập: ' . current_url() . ' (User ID: ' . (session('user_id') ?? 'unknown') . ')');
			
			// Nếu là request AJAX, trả về JSON
			if ($request->isAJAX()) {
				$response = service('response');
				return $response->setStatusCode(403)
							   ->setJSON([
								   'success' => false,
								   'message' => 'Bạn không có quyền truy cập vào tài nguyên này.',
								   'redirect' => site_url('users/dashboard')
							   ]);
			}
			
			// Chuyển hướng người dùng về dashboard với thông báo lỗi
			return redirect()->to('users/dashboard')->with('error', 'Bạn không có quyền truy cập vào tài nguyên này.');
		}
	}

	//--------------------------------------------------------------------

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		// Do something here
	}
}
