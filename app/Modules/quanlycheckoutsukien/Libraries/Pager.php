<?php

namespace App\Modules\quanlycheckoutsukien\Libraries;

class Pager 
{
    protected $total = 0;
    protected $perPage = 10;
    protected $currentPage = 1;
    protected $surroundCount = 2;
    protected $path = '';
    protected $query = [];
    protected $routeUrl = '';
    protected $only = [];
    
    /**
     * Constructor
     * 
     * @param int $total Tổng số mục
     * @param int $perPage Số mục trên mỗi trang
     * @param int $currentPage Trang hiện tại
     * @param int $surroundCount Số trang hiển thị xung quanh trang hiện tại
     */
    public function __construct(int $total = 0, int $perPage = 10, int $currentPage = 1, int $surroundCount = 2)
    {
        $this->total = $total;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
        $this->surroundCount = $surroundCount;
    }
    
    /**
     * Thiết lập tổng số mục
     * 
     * @param int $total
     * @return $this
     */
    public function setTotal(int $total)
    {
        $this->total = $total;
        return $this;
    }
    
    /**
     * Lấy tổng số mục
     * 
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
    
    /**
     * Thiết lập số mục trên mỗi trang
     * 
     * @param int $perPage
     * @return $this
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
        return $this;
    }
    
    /**
     * Lấy số mục trên mỗi trang
     * 
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }
    
    /**
     * Thiết lập trang hiện tại
     * 
     * @param int $currentPage
     * @return $this
     */
    public function setCurrentPage(int $currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }
    
    /**
     * Lấy trang hiện tại
     * 
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }
    
    /**
     * Thiết lập số trang hiển thị xung quanh trang hiện tại
     * 
     * @param int $surroundCount
     * @return $this
     */
    public function setSurroundCount(int $surroundCount)
    {
        $this->surroundCount = $surroundCount;
        return $this;
    }
    
    /**
     * Lấy số trang hiển thị xung quanh trang hiện tại
     * 
     * @return int
     */
    public function getSurroundCount(): int
    {
        return $this->surroundCount;
    }
    
    /**
     * Thiết lập đường dẫn cơ sở
     * 
     * @param string $path
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = $path;
        return $this;
    }
    
    /**
     * Lấy đường dẫn cơ sở
     * 
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
    
    /**
     * Thiết lập route URL
     * 
     * @param string $routeUrl
     * @return $this
     */
    public function setRouteUrl(string $routeUrl)
    {
        $this->routeUrl = $routeUrl;
        return $this;
    }
    
    /**
     * Lấy route URL
     * 
     * @return string
     */
    public function getRouteUrl(): string
    {
        return $this->routeUrl;
    }
    
    /**
     * Thiết lập các tham số chỉ được bao gồm trong URL phân trang
     * 
     * @param array $only
     * @return $this
     */
    public function setOnly(array $only)
    {
        $this->only = $only;
        return $this;
    }
    
    /**
     * Lấy các tham số chỉ được bao gồm trong URL phân trang
     * 
     * @return array
     */
    public function getOnly(): array
    {
        return $this->only;
    }
    
    /**
     * Tính số trang
     * 
     * @return int
     */
    public function getPageCount(): int
    {
        return ceil($this->total / $this->perPage);
    }
    
    /**
     * Kiểm tra xem có trang kế tiếp hay không
     * 
     * @return bool
     */
    public function hasNextPage(): bool
    {
        return $this->currentPage < $this->getPageCount();
    }
    
    /**
     * Kiểm tra xem có trang trước hay không
     * 
     * @return bool
     */
    public function hasPreviousPage(): bool
    {
        return $this->currentPage > 1;
    }
    
    /**
     * Lấy danh sách trang để hiển thị
     * 
     * @return array
     */
    public function getPages(): array
    {
        $pages = [];
        $pageCount = $this->getPageCount();
        
        // Nếu không có trang nào, trả về mảng rỗng
        if ($pageCount <= 0) {
            return $pages;
        }
        
        // Nếu số trang nhỏ hơn hoặc bằng (2 * surroundCount + 1), hiển thị tất cả trang
        if ($pageCount <= (2 * $this->surroundCount + 1)) {
            for ($i = 1; $i <= $pageCount; $i++) {
                $pages[] = [
                    'page' => $i,
                    'label' => (string) $i,
                    'url' => $this->getUrl($i),
                    'active' => $i === $this->currentPage,
                ];
            }
            
            return $pages;
        }
        
        // Luôn hiển thị trang đầu tiên
        $pages[] = [
            'page' => 1,
            'label' => '1',
            'url' => $this->getUrl(1),
            'active' => 1 === $this->currentPage,
        ];
        
        // Xác định trang bắt đầu và kết thúc trong phạm vi xung quanh trang hiện tại
        $startPage = max(2, $this->currentPage - $this->surroundCount);
        $endPage = min($pageCount - 1, $this->currentPage + $this->surroundCount);
        
        // Thêm dấu chấm lửng nếu có khoảng cách giữa trang đầu tiên và trang bắt đầu
        if ($startPage > 2) {
            $pages[] = [
                'page' => null,
                'label' => '...',
                'url' => '',
                'active' => false,
            ];
        }
        
        // Thêm các trang trong phạm vi
        for ($i = $startPage; $i <= $endPage; $i++) {
            $pages[] = [
                'page' => $i,
                'label' => (string) $i,
                'url' => $this->getUrl($i),
                'active' => $i === $this->currentPage,
            ];
        }
        
        // Thêm dấu chấm lửng nếu có khoảng cách giữa trang kết thúc và trang cuối cùng
        if ($endPage < $pageCount - 1) {
            $pages[] = [
                'page' => null,
                'label' => '...',
                'url' => '',
                'active' => false,
            ];
        }
        
        // Luôn hiển thị trang cuối cùng
        $pages[] = [
            'page' => $pageCount,
            'label' => (string) $pageCount,
            'url' => $this->getUrl($pageCount),
            'active' => $pageCount === $this->currentPage,
        ];
        
        return $pages;
    }
    
    /**
     * Tạo URL cho trang cụ thể
     * 
     * @param int $page
     * @return string
     */
    public function getUrl(int $page): string
    {
        // Nếu routeUrl không được thiết lập, sử dụng path
        $baseUrl = $this->routeUrl ?: $this->path;
        $baseUrl = site_url($baseUrl);
        
        // Lấy các tham số hiện tại từ URL
        $params = $_GET;
        
        // Nếu chỉ định mảng only, chỉ giữ lại các tham số được chỉ định
        if (!empty($this->only)) {
            $filteredParams = [];
            foreach ($this->only as $key) {
                if (isset($params[$key])) {
                    $filteredParams[$key] = $params[$key];
                }
            }
            $params = $filteredParams;
        }
        
        // Cập nhật tham số page
        $params['page'] = $page;
        
        // Xây dựng URL với các tham số
        $queryString = http_build_query($params);
        
        return $baseUrl . ($queryString ? '?' . $queryString : '');
    }
    
    /**
     * Lấy URL trang trước
     * 
     * @return string|null
     */
    public function getPreviousPageUrl(): ?string
    {
        return $this->hasPreviousPage() ? $this->getUrl($this->currentPage - 1) : null;
    }
    
    /**
     * Lấy URL trang kế tiếp
     * 
     * @return string|null
     */
    public function getNextPageUrl(): ?string
    {
        return $this->hasNextPage() ? $this->getUrl($this->currentPage + 1) : null;
    }
    
    /**
     * Render phân trang với Bootstrap
     * 
     * @return string
     */
    public function render(): string
    {
        // Kiểm tra nếu không có trang nào
        if ($this->getPageCount() <= 1) {
            return '';
        }
        
        $pages = $this->getPages();
        $html = '<nav aria-label="Page navigation">';
        $html .= '<ul class="pagination justify-content-center mb-0">';
        
        // Nút Previous
        $prevUrl = $this->getPreviousPageUrl();
        $html .= '<li class="page-item ' . ($prevUrl ? '' : 'disabled') . '">';
        $html .= '<a class="page-link" href="' . ($prevUrl ?: '#') . '" ' . ($prevUrl ? '' : 'tabindex="-1" aria-disabled="true"') . '>';
        $html .= '<i class="bx bx-chevron-left"></i> Trước';
        $html .= '</a>';
        $html .= '</li>';
        
        // Các trang
        foreach ($pages as $page) {
            if ($page['page'] === null) {
                // Dấu chấm lửng
                $html .= '<li class="page-item disabled"><span class="page-link">' . $page['label'] . '</span></li>';
            } else {
                $html .= '<li class="page-item ' . ($page['active'] ? 'active' : '') . '">';
                $html .= '<a class="page-link" href="' . $page['url'] . '">' . $page['label'] . '</a>';
                $html .= '</li>';
            }
        }
        
        // Nút Next
        $nextUrl = $this->getNextPageUrl();
        $html .= '<li class="page-item ' . ($nextUrl ? '' : 'disabled') . '">';
        $html .= '<a class="page-link" href="' . ($nextUrl ?: '#') . '" ' . ($nextUrl ? '' : 'tabindex="-1" aria-disabled="true"') . '>';
        $html .= 'Tiếp <i class="bx bx-chevron-right"></i>';
        $html .= '</a>';
        $html .= '</li>';
        
        $html .= '</ul>';
        $html .= '</nav>';
        
        return $html;
    }
    
    /**
     * Hiển thị phân trang (alias của render())
     * 
     * @return string
     */
    public function links()
    {
        return $this->render();
    }
} 