<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class GuestUserFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (service('auth')->isLoggedInUser()) {
            // Lấy đường dẫn hiện tại
            $currentURI = uri_string();

            $blockedPages = ['login/admin', 'login/student'];
            if (in_array($currentURI, $blockedPages)) {
                return redirect()->to('/users/dashboard');
            }
        }

        return null; // Không chặn các trang khác
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Không cần xử lý gì thêm
    }
}