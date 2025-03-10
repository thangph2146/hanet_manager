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
			$response = service('response');

			$response->setStatusCode(403);
			$response->setBody('You do not have permission to access that resource');

			return $response;
		}
	}

	//--------------------------------------------------------------------

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		// Do something here
	}
}
