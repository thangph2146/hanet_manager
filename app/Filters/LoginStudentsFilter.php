<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginStudentsFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!service('authstudent')->isLoggedInStudent()) {
            session()->set('redirect_url', current_url());
            return redirect()->to('login/nguoi-dung')
                             ->with('info', 'Vui lòng Đăng nhập trước!');
        }
    }


	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		// Do something here
	}
}
