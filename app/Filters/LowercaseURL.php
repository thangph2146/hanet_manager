<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LowercaseURL implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = service('uri');

        // Chuyển tất cả URI về chữ thường
        $lowercaseURI = strtolower($uri->getPath());

        if ($uri->getPath() !== $lowercaseURI) {
            return redirect()->to(base_url($lowercaseURI));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Không cần thay đổi gì ở đây
    }
}
