<?php

namespace App\Modules\sukiendiengia\Libraries;

/**
 * Lớp Pager - cung cấp chức năng phân trang cho module SuKienDienGia
 * 
 * Lớp này thay thế cho \CodeIgniter\Pager\Pager mặc định để tùy chỉnh
 * cách hiển thị và xử lý phân trang riêng cho module SuKienDienGia.
 */
class Pager
{
    /**
     * Tên module
     * 
     * @var string
     */
    protected $module_name = 'sukiendiengia';

    /**
     * Số lượng trang hiển thị xung quanh trang hiện tại
     * 
     * @var int
     */
    protected $surroundCount = 2;
    
    /**
     * Tổng số trang
     * 
     * @var int
     */
    protected $pageCount = 1;
    
    /**
     * Trang hiện tại
     * 
     * @var int
     */
    protected $currentPage = 1;
    
    /**
     * Segment URL chứa số trang
     * 
     * @var int
     */
    protected $segment = 2;
    
    /**
     * Đường dẫn cơ sở cho các liên kết trang
     * 
     * @var string
     */
    protected $path = '';
    
    /**
     * Tổng số bản ghi
     * 
     * @var int
     */
    protected $total = 0;
    
    /**
     * Số bản ghi trên mỗi trang
     * 
     * @var int
     */
    protected $perPage = 10;
    
    /**
     * Danh sách các tham số URL cần giữ lại khi chuyển trang
     * 
     * @var array
     */
    protected $only = [];
    
    /**
     * Constructor
     * 
     * @param int $total Tổng số bản ghi
     * @param int $perPage Số bản ghi trên mỗi trang
     * @param int $currentPage Trang hiện tại
     */
    public function __construct(int $total = 0, int $perPage = 10, int $currentPage = 1)
    {
        $this->total = $total;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
        
        // Tính tổng số trang
        $this->calculatePageCount();
    }
    
    /**
     * Thiết lập số lượng trang hiển thị xung quanh trang hiện tại
     * 
     * @param int $count Số lượng trang
     * @return $this
     */
    public function setSurroundCount(int $count)
    {
        $this->surroundCount = $count;
        return $this;
    }
    
    /**
     * Thiết lập segment URL chứa số trang
     * 
     * @param int $segment Số segment
     * @return $this
     */
    public function setSegment(int $segment)
    {
        $this->segment = $segment;
        return $this;
    }
    
    /**
     * Thiết lập đường dẫn cơ sở cho các liên kết trang
     * 
     * @param string $path Đường dẫn
     * @return $this
     */
    public function setPath(string $path)
    {
        $this->path = rtrim($path, '/');
        return $this;
    }
    
    /**
     * Thiết lập tổng số bản ghi
     * 
     * @param int $total Tổng số bản ghi
     * @return $this
     */
    public function setTotal(int $total)
    {
        $this->total = $total;
        $this->calculatePageCount();
        
        // Đảm bảo trang hiện tại vẫn hợp lệ sau khi tổng số trang thay đổi
        $this->currentPage = max(1, min($this->currentPage, $this->pageCount > 0 ? $this->pageCount : 1));
        
        return $this;
    }
    
    /**
     * Thiết lập số bản ghi trên mỗi trang
     * 
     * @param int $perPage Số bản ghi trên mỗi trang
     * @return $this
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;
        $this->calculatePageCount();
        
        // Đảm bảo trang hiện tại vẫn hợp lệ sau khi tổng số trang thay đổi
        $this->currentPage = max(1, min($this->currentPage, $this->pageCount > 0 ? $this->pageCount : 1));
        
        return $this;
    }
    
    /**
     * Thiết lập trang hiện tại
     * 
     * @param int $currentPage Trang hiện tại
     * @return $this
     */
    public function setCurrentPage(int $currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }
    
    /**
     * Thiết lập danh sách các tham số URL cần giữ lại khi chuyển trang
     * 
     * @param array $only Danh sách tham số
     * @return $this
     */
    public function setOnly(array $only)
    {
        $this->only = $only;
        return $this;
    }
    
    /**
     * Lấy tổng số bản ghi
     * 
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }
    
    /**
     * Lấy số bản ghi trên mỗi trang
     * 
     * @return int
     */
    public function getPerPage(): int
    {
        return $this->perPage;
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
     * Lấy tổng số trang
     * 
     * @return int
     */
    public function getPageCount()
    {
        return $this->pageCount;
    }
    
    /**
     * Kiểm tra xem có trang trước hay không
     * 
     * @return bool
     */
    public function hasPrevious()
    {
        return $this->currentPage > 1;
    }
    
    /**
     * Kiểm tra xem có trang sau hay không
     * 
     * @return bool
     */
    public function hasNext()
    {
        return $this->currentPage < $this->pageCount;
    }
    
    /**
     * Lấy số trang trước
     * 
     * @return int
     */
    public function getPreviousPage()
    {
        return max(1, $this->currentPage - 1);
    }
    
    /**
     * Lấy số trang sau
     * 
     * @return int
     */
    public function getNextPage()
    {
        return min($this->pageCount, $this->currentPage + 1);
    }
    
    /**
     * Tính toán tổng số trang dựa trên tổng số bản ghi và số bản ghi trên mỗi trang
     */
    protected function calculatePageCount()
    {
        // Đảm bảo perPage > 0 để tránh chia cho 0
        if ($this->perPage < 1) {
            $this->perPage = 1;
        }
        
        // Nếu không có bản ghi nào, vẫn có ít nhất 1 trang
        if ($this->total <= 0) {
            $this->pageCount = 1;
        } else {
            $this->pageCount = (int)ceil($this->total / $this->perPage);
        }
        
        // Đảm bảo luôn có ít nhất 1 trang
        if ($this->pageCount < 1) {
            $this->pageCount = 1;
        }
    }
    
    /**
     * Tạo URL cho một trang cụ thể
     * 
     * @param int $page Số trang
     * @return string URL cho trang
     */
    public function getPageURL(int $page)
    {
        // Đảm bảo $page nằm trong khoảng hợp lệ
        $page = max(1, min($page, $this->pageCount));
        
        // Xây dựng URL cơ sở
        $url = $this->path ? site_url($this->path) : site_url();
        
        // Lấy các tham số GET hiện tại
        $params = $_GET;
        
        // Nếu $only được chỉ định, chỉ giữ lại các tham số được yêu cầu
        if (!empty($this->only)) {
            $filteredParams = [];
            foreach ($this->only as $key) {
                if (isset($params[$key])) {
                    $filteredParams[$key] = $params[$key];
                }
            }
            $params = $filteredParams;
        }
        
        // Thêm tham số page
        $params['page'] = $page;
        
        // Xây dựng query string
        $queryString = http_build_query($params);
        
        return $url . '?' . $queryString;
    }
    
    /**
     * Lấy danh sách các số trang cần hiển thị
     * 
     * @return array
     */
    public function getPageNumbers()
    {
        // Tạo mảng chứa các số trang sẽ hiển thị
        $pages = [];
        
        // Xác định khoảng hiển thị
        $start = max(1, $this->currentPage - $this->surroundCount);
        $end = min($this->pageCount, $this->currentPage + $this->surroundCount);
        
        // Điều chỉnh khoảng để luôn hiển thị đủ số lượng trang
        if ($end - $start + 1 < $this->surroundCount * 2 + 1) {
            if ($start === 1) {
                $end = min($this->pageCount, $start + $this->surroundCount * 2);
            } elseif ($end === $this->pageCount) {
                $start = max(1, $end - $this->surroundCount * 2);
            }
        }
        
        // Luôn hiển thị trang đầu tiên
        if ($start > 1) {
            $pages[] = [
                'page' => 1,
                'url' => $this->getPageURL(1),
                'isCurrent' => false
            ];
            
            // Hiển thị dấu 3 chấm nếu cần
            if ($start > 2) {
                $pages[] = [
                    'page' => '...',
                    'url' => '',
                    'isCurrent' => false,
                    'isEllipsis' => true
                ];
            }
        }
        
        // Hiển thị các trang trong khoảng
        for ($i = $start; $i <= $end; $i++) {
            $pages[] = [
                'page' => $i,
                'url' => $this->getPageURL($i),
                'isCurrent' => ($i === $this->currentPage)
            ];
        }
        
        // Luôn hiển thị trang cuối cùng
        if ($end < $this->pageCount) {
            // Hiển thị dấu 3 chấm nếu cần
            if ($end < $this->pageCount - 1) {
                $pages[] = [
                    'page' => '...',
                    'url' => '',
                    'isCurrent' => false,
                    'isEllipsis' => true
                ];
            }
            
            $pages[] = [
                'page' => $this->pageCount,
                'url' => $this->getPageURL($this->pageCount),
                'isCurrent' => false
            ];
        }
        
        return $pages;
    }
    
    /**
     * Hiển thị phân trang
     * 
     * @return string HTML của phân trang
     */
    public function render()
    {
        // Nếu chỉ có 1 trang, không cần hiển thị phân trang
        if ($this->pageCount <= 1) {
            return '';
        }
        
        // Sử dụng template phân trang
        return $this->display();
    }
    
    /**
     * Hiển thị phân trang sử dụng template
     * 
     * @return string HTML của phân trang
     */
    protected function display()
    {
        // Nếu chỉ có 1 trang hoặc không có bản ghi nào, không cần hiển thị phân trang
        if ($this->pageCount <= 1 || $this->total <= 0) {
            return '';
        }
        
        // Tìm kiếm template phân trang
        $viewPath = 'App\Modules\\' . $this->module_name . '\Views\pagers\pager';
        
        // Truyền dữ liệu cho view
        $data = [
            'pager' => $this,
            'pageCount' => $this->pageCount,
            'currentPage' => $this->currentPage,
            'hasPrevious' => $this->hasPrevious(),
            'hasNext' => $this->hasNext(),
            'previousPage' => $this->getPreviousPage(),
            'nextPage' => $this->getNextPage(),
            'firstPageURL' => $this->getPageURL(1),
            'lastPageURL' => $this->getPageURL($this->pageCount),
            'previousPageURL' => $this->getPageURL($this->getPreviousPage()),
            'nextPageURL' => $this->getPageURL($this->getNextPage()),
            'pageNumbers' => $this->getPageNumbers(),
        ];
        
        // Render view
        return view($viewPath, $data);
    }
    
    /**
     * Lấy URL của trang đầu tiên
     * 
     * @return string
     */
    public function getFirst(): string
    {
        return $this->getPageURL(1);
    }
    
    /**
     * Lấy URL của trang cuối cùng
     * 
     * @return string
     */
    public function getLast(): string
    {
        return $this->getPageURL($this->pageCount);
    }
    
    /**
     * Lấy URL của trang trước
     * 
     * @return string
     */
    public function getPrevious(): string
    {
        return $this->getPageURL($this->getPreviousPage());
    }
    
    /**
     * Lấy URL của trang tiếp theo
     * 
     * @return string
     */
    public function getNext(): string
    {
        return $this->getPageURL($this->getNextPage());
    }
    
    /**
     * Phương thức debug để ghi log các tham số phân trang
     * 
     * @return array
     */
    public function debug()
    {
        return [
            'total' => $this->total,
            'perPage' => $this->perPage,
            'currentPage' => $this->currentPage,
            'pageCount' => $this->pageCount,
            'path' => $this->path,
            'segment' => $this->segment,
            'only' => $this->only,
            'query' => $_GET
        ];
    }
    
    /**
     * Tạo URL cho một trang cụ thể và log thông tin
     * 
     * @param int $page Số trang
     * @param bool $debug Bật chế độ debug
     * @return string URL cho trang
     */
    public function debugPageURL(int $page, bool $debug = true)
    {
        $url = $this->getPageURL($page);
        
        if ($debug) {
            $debugInfo = [
                'page' => $page,
                'url' => $url,
                'query' => $_GET,
                'path' => $this->path,
                'only' => $this->only
            ];
            
            // Ghi log ra file
            $logPath = WRITEPATH . 'logs/pager-debug.log';
            file_put_contents($logPath, date('Y-m-d H:i:s') . ' - ' . json_encode($debugInfo) . "\n", FILE_APPEND);
        }
        
        return $url;
    }
    
    public function getLastPage(): int
    {
        return $this->perPage > 0 ? (int)ceil($this->total / $this->perPage) : 1;
    }
    
    public function hasMore(): bool
    {
        return $this->currentPage < $this->getLastPage();
    }
} 