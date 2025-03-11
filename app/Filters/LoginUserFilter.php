<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginUserFilter implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		if ( ! service('auth')->isLoggedInUser()) {

			session()->set('redirect_url', current_url());

			return redirect()->to('login/admin')
							 ->with('info', 'Vui lòng Đăng nhập trước!');

		}
	}

	//--------------------------------------------------------------------

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		// Do something here
	}
}