<?php namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class GuestUserFilter implements FilterInterface
{
    // Danh sách các trang chỉ dành cho khách (không cần đăng nhập)
    protected array $guestPages = [
        'login',
        'login/admin',  // Trang đăng nhập
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        if (service('auth')->isLoggedInUser()) {
            // Lấy đường dẫn hiện tại
            $currentURI = uri_string();

            // Nếu người dùng truy cập vào một trong các trang chỉ dành cho khách (guest), chuyển hướng đến dashboard
            if (in_array($currentURI, $this->guestPages)) {
                return redirect()->to('/Users/dashboard');
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Không cần xử lý gì thêm sau request
    }
}