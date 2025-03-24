<?php

namespace App\Modules\camera\Libraries;

/**
 * Lớp CameraPager - cung cấp chức năng phân trang cho module Camera
 * 
 * Lớp này thay thế cho \CodeIgniter\Pager\Pager mặc định để tùy chỉnh
 * cách hiển thị và xử lý phân trang riêng cho module Camera.
 */
class CameraPager
{
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
    public function getTotal()
    {
        return $this->total;
    }
    
    /**
     * Lấy số bản ghi trên mỗi trang
     * 
     * @return int
     */
    public function getPerPage()
    {
        return $this->perPage;
    }
    
    /**
     * Lấy trang hiện tại
     * 
     * @return int
     */
    public function getCurrentPage()
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
        $this->pageCount = $this->perPage > 0 ? (int)ceil($this->total / $this->perPage) : 1;
    }
    
    /**
     * Tạo URL cho một trang cụ thể
     * 
     * @param int $page Số trang
     * @return string URL cho trang
     */
    public function getPageURL(int $page)
    {
        $page = max(1, min($page, $this->pageCount));
        
        // Nếu không có path, sử dụng đường dẫn hiện tại
        if (empty($this->path)) {
            // Lấy đường dẫn hiện tại không bao gồm segment chứa số trang
            $uri = service('uri');
            $segments = $uri->getSegments();
            
            // Loại bỏ segment số trang nếu có
            if (count($segments) >= $this->segment) {
                $segments[$this->segment - 1] = $page;
            } else {
                // Thêm segment số trang nếu chưa có
                while (count($segments) < $this->segment - 1) {
                    $segments[] = '';
                }
                $segments[] = $page;
            }
            
            $path = implode('/', $segments);
        } else {
            $path = $this->path . '/' . $page;
        }
        
        // Thêm các tham số GET nếu cần
        $query = $_GET;
        
        // Chỉ giữ lại các tham số đã chỉ định trong only
        if (!empty($this->only)) {
            $newQuery = [];
            foreach ($this->only as $key) {
                if (isset($query[$key])) {
                    $newQuery[$key] = $query[$key];
                }
            }
            $query = $newQuery;
        }
        
        $queryString = http_build_query($query);
        
        if (!empty($queryString)) {
            $path .= '?' . $queryString;
        }
        
        return site_url($path);
    }
    
    /**
     * Tạo danh sách các số trang để hiển thị
     * 
     * @return array Danh sách các số trang
     */
    public function getPageNumbers()
    {
        // Nếu tổng số trang ít, hiển thị tất cả
        if ($this->pageCount <= ($this->surroundCount * 2) + 3) {
            return range(1, $this->pageCount);
        }
        
        // Xác định phạm vi trang cần hiển thị
        $start = max(1, $this->currentPage - $this->surroundCount);
        $end = min($this->pageCount, $this->currentPage + $this->surroundCount);
        
        $pages = [];
        
        // Luôn hiển thị trang đầu tiên
        $pages[] = 1;
        
        // Thêm dấu chấm lửng nếu cần
        if ($start > 2) {
            $pages[] = '...';
        }
        
        // Thêm các trang ở giữa
        for ($i = $start; $i <= $end; $i++) {
            if ($i !== 1 && $i !== $this->pageCount) {
                $pages[] = $i;
            }
        }
        
        // Thêm dấu chấm lửng nếu cần
        if ($end < $this->pageCount - 1) {
            $pages[] = '...';
        }
        
        // Luôn hiển thị trang cuối cùng
        if ($this->pageCount > 1) {
            $pages[] = $this->pageCount;
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
        // Tìm kiếm template phân trang
        $viewPath = 'App\Modules\camera\Views\pagers\camera_pager';
        
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
} 