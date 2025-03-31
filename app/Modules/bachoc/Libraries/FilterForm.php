<?php
/**
 * 01/04/2023
 * Thư viện để gọi và quản lý scripts và styles trong module bachoc
 */

namespace App\Modules\bachoc\Libraries;

use CodeIgniter\HTTP\RequestInterface;

class FilterForm
{
    /**
     * Tên module
     * 
     * @var string
     */
    protected $module_name = 'bachoc';

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * Constructor.
     *
     * @param RequestInterface|null $request
     */
    public function __construct(RequestInterface $request = null)
    {
        $this->request = $request ?? service('request');
    }

    /**
     * Render the filter form component.
     *
     * @param array $data Data to pass to the view, typically including:
     *                    - search: Current search term.
     *                    - filters: Current filter values (e.g., ['status' => 'active']).
     *                    - searchFields: Fields available for search.
     *                    - filterFields: Fields available for filtering.
     *                    - moduleName: Name of the module.
     *                    - sort: Current sort field.
     *                    - order: Current sort order (asc/desc).
     *                    - perPage: Current items per page.
     * @return string Rendered HTML of the filter form.
     */
    public function render(array $data = []): string
    {
        // Các tham số thường dùng cho lọc và phân trang mà Pager cũng hay giữ lại
        $commonParams = ['keyword', 'status', 'sort', 'order', 'perPage'];
        $requestParams = $this->request->getGet($commonParams); // Lấy các tham số này từ GET request

        // Set default values and merge request parameters if not already set in $data
        $defaults = [
            'keyword' => $requestParams['keyword'] ?? '', // Sử dụng 'keyword' thay vì 'search' cho nhất quán
            'filters' => [],
            'searchFields' => [],
            'filterFields' => [],
            'moduleName' => 'bachoc', // Default module name if not provided
            'sort' => $requestParams['sort'] ?? '',
            'order' => $requestParams['order'] ?? '',
            'perPage' => $requestParams['perPage'] ?? 10, // Giả sử mặc định là 10 nếu không có
        ];

        // Ưu tiên giá trị từ $data nếu được cung cấp
        $viewData = array_merge($defaults, $data);

        // Nếu 'status' có trong $requestParams nhưng không có trong $data['filters']
        // thì cập nhật $viewData['filters']
        if (isset($requestParams['status']) && !isset($viewData['filters']['status'])) {
            $viewData['filters']['status'] = $requestParams['status'];
        }
        
        // Đảm bảo $viewData['search'] được đặt nếu 'keyword' được sử dụng
        if (!isset($viewData['search']) && isset($viewData['keyword'])) {
             $viewData['search'] = $viewData['keyword'];
        }


        // Use the namespace to call the view component
        // Assumes 'bachoc' namespace is registered in Config/View.php
        // **Quan trọng:** Đảm bảo view component tồn tại tại:
        // app/Modules/bachoc/Views/Components/filter_form.php
        $viewPath = 'App\Modules\\' . $this->module_name . '\Views\Components\filter_form';
        return view($viewPath, $viewData);
    }
} 