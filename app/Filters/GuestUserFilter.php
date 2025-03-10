<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class GuestUserFilter implements FilterInterface
{
	public function before(RequestInterface $request, $arguments = null)
	{
		if ( service('auth')->isLoggedInUser() ) {

			return redirect()->to('/Users/dashboard');

		}
	}

	//--------------------------------------------------------------------

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		// Do something here
	}
}
