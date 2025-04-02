<?php

namespace App\Modules\quanlyloaisukien\Libraries;

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
        
        // Xác định trang bắt đầu và kết thúc hiển thị xung quanh trang hiện tại
        $startPage = max($this->currentPage - $this->surroundCount, 2);
        $endPage = min($this->currentPage + $this->surroundCount, $pageCount - 1);
        
        // Nếu startPage lớn hơn 2, hiển thị dấu ba chấm sau trang đầu tiên
        if ($startPage > 2) {
            $pages[] = [
                'page' => null,
                'label' => '...',
                'url' => null,
                'active' => false,
            ];
        }
        
        // Hiển thị các trang xung quanh trang hiện tại
        for ($i = $startPage; $i <= $endPage; $i++) {
            $pages[] = [
                'page' => $i,
                'label' => (string) $i,
                'url' => $this->getUrl($i),
                'active' => $i === $this->currentPage,
            ];
        }
        
        // Nếu endPage nhỏ hơn pageCount - 1, hiển thị dấu ba chấm trước trang cuối cùng
        if ($endPage < $pageCount - 1) {
            $pages[] = [
                'page' => null,
                'label' => '...',
                'url' => null,
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
     * Lấy URL cho một trang cụ thể
     * 
     * @param int $page Số trang
     * @return string
     */
    public function getUrl(int $page): string
    {
        // Sử dụng route URL nếu có
        if ($this->routeUrl) {
            return str_replace('{page}', (string) $page, $this->routeUrl);
        }
        
        // Xây dựng URL từ path và query parameters
        $path = $this->path ?: current_url();
        $query = $_GET;
        
        // Chỉ giữ lại các tham số được chỉ định trong $only nếu được thiết lập
        if (!empty($this->only)) {
            $filtered = [];
            foreach ($this->only as $key) {
                if (isset($query[$key])) {
                    $filtered[$key] = $query[$key];
                }
            }
            $query = $filtered;
        }
        
        // Thêm hoặc cập nhật tham số page
        $query['page'] = $page;
        
        // Tạo query string
        $queryString = http_build_query($query);
        
        return $path . '?' . $queryString;
    }
    
    /**
     * Lấy URL trang trước
     * 
     * @return string|null
     */
    public function getPreviousPageUrl(): ?string
    {
        if (!$this->hasPreviousPage()) {
            return null;
        }
        
        return $this->getUrl($this->currentPage - 1);
    }
    
    /**
     * Lấy URL trang kế tiếp
     * 
     * @return string|null
     */
    public function getNextPageUrl(): ?string
    {
        if (!$this->hasNextPage()) {
            return null;
        }
        
        return $this->getUrl($this->currentPage + 1);
    }
    
    /**
     * Tạo HTML cho phân trang
     * 
     * @return string
     */
    public function render(): string
    {
        $html = '<nav aria-label="Phân trang">';
        $html .= '<ul class="pagination">';
        
        // Nút Previous
        if ($this->hasPreviousPage()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getPreviousPageUrl() . '" aria-label="Trang trước">';
            $html .= '<span aria-hidden="true">&laquo;</span>';
            $html .= '</a>';
            $html .= '</li>';
        } else {
            $html .= '<li class="page-item disabled">';
            $html .= '<span class="page-link" aria-label="Trang trước">';
            $html .= '<span aria-hidden="true">&laquo;</span>';
            $html .= '</span>';
            $html .= '</li>';
        }
        
        // Các trang
        foreach ($this->getPages() as $page) {
            if ($page['page'] === null) {
                $html .= '<li class="page-item disabled"><span class="page-link">' . $page['label'] . '</span></li>';
            } else {
                $activeClass = $page['active'] ? ' active' : '';
                $html .= '<li class="page-item' . $activeClass . '">';
                $html .= '<a class="page-link" href="' . $page['url'] . '">' . $page['label'] . '</a>';
                $html .= '</li>';
            }
        }
        
        // Nút Next
        if ($this->hasNextPage()) {
            $html .= '<li class="page-item">';
            $html .= '<a class="page-link" href="' . $this->getNextPageUrl() . '" aria-label="Trang sau">';
            $html .= '<span aria-hidden="true">&raquo;</span>';
            $html .= '</a>';
            $html .= '</li>';
        } else {
            $html .= '<li class="page-item disabled">';
            $html .= '<span class="page-link" aria-label="Trang sau">';
            $html .= '<span aria-hidden="true">&raquo;</span>';
            $html .= '</span>';
            $html .= '</li>';
        }
        
        $html .= '</ul>';
        $html .= '</nav>';
        
        return $html;
    }
    
    /**
     * Trả về HTML của phân trang
     */
    public function links()
    {
        return $this->render();
    }
} 