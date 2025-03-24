<?php

namespace App\Modules\camera\Libraries;

/**
 * Lớp Pager - cung cấp chức năng phân trang cho module Camera
 * 
 * Lớp này thay thế cho \CodeIgniter\Pager\Pager mặc định để tùy chỉnh
 * cách hiển thị và xử lý phân trang riêng cho module Camera.
 */
class Pager
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
        $this->total = max(0, $total);
        $this->perPage = max(1, $perPage);
        
        // Tính tổng số trang
        $this->calculatePageCount();
        
        // Đảm bảo trang hiện tại hợp lệ
        $this->currentPage = max(1, min($currentPage, $this->pageCount > 0 ? $this->pageCount : 1));
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
        $this->total = max(0, $total);
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
        $this->perPage = max(1, $perPage);
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
        // Đảm bảo trang hiện tại hợp lệ (nằm trong khoảng từ 1 đến tổng số trang)
        $this->currentPage = max(1, min($currentPage, $this->pageCount > 0 ? $this->pageCount : 1));
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
        // Đảm bảo số trang nằm trong khoảng hợp lệ
        $page = max(1, min($page, $this->pageCount));
        
        // Lấy path từ thiết lập hoặc URI hiện tại
        if (empty($this->path)) {
            // Nếu không có path được thiết lập, sử dụng URI hiện tại
            $uri = service('uri');
            $path = implode('/', $uri->getSegments());
        } else {
            // Sử dụng path đã được thiết lập
            $path = $this->path;
        }
        
        // Log path được sử dụng
        log_message('debug', '[Pager] Path được sử dụng: ' . $path);
        
        // Lấy tất cả các tham số GET hiện tại
        $query = $_GET;
        
        // Log các tham số GET hiện tại
        log_message('debug', '[Pager] Tham số GET gốc: ' . json_encode($query));
        
        // Cập nhật tham số page
        $query['page'] = $page;
        
        // Đảm bảo perPage luôn được giữ lại
        if (!isset($query['perPage']) && $this->perPage != 10) {
            $query['perPage'] = $this->perPage;
        }
        
        // Đảm bảo các tham số quan trọng luôn được giữ lại
        $importantParams = ['keyword', 'status', 'sort', 'order'];
        foreach ($importantParams as $param) {
            if (isset($_GET[$param])) {
                // Xử lý đặc biệt cho trường hợp status=0
                if ($param === 'status' && (string)$_GET[$param] === '0') {
                    $query[$param] = '0';
                    log_message('debug', '[Pager] Xử lý đặc biệt: giữ lại status=0');
                } 
                // Chỉ giữ lại tham số có giá trị hoặc giá trị rỗng có chủ đích
                else if ($_GET[$param] !== '') {
                    $query[$param] = $_GET[$param];
                }
            }
        }
        
        // Lọc các tham số chỉ định trong only (nếu có)
        if (!empty($this->only)) {
            // Log thông tin only trước khi lọc
            log_message('debug', '[Pager] Danh sách only: ' . json_encode($this->only));
            
            $newQuery = [];
            foreach ($this->only as $key) {
                if (isset($query[$key])) {
                    $newQuery[$key] = $query[$key];
                }
            }
            
            // Luôn thêm tham số page vào danh sách được giữ lại
            $newQuery['page'] = $page;
            
            // Log các tham số sau khi lọc qua only
            log_message('debug', '[Pager] Tham số sau khi lọc qua only: ' . json_encode($newQuery));
            
            $query = $newQuery;
        }
        
        // Tạo query string từ các tham số
        $queryString = http_build_query($query);
        
        // Kết hợp path và query string
        $url = site_url($path);
        if (!empty($queryString)) {
            $url .= '?' . $queryString;
        }
        
        // Log URL cuối cùng 
        log_message('debug', '[Pager] Tạo URL cho trang ' . $page . ': ' . $url);
        
        return $url;
    }
    
    /**
     * Tạo danh sách các số trang để hiển thị
     * 
     * @return array Danh sách các số trang
     */
    public function getPageNumbers()
    {
        // Log thông tin request hiện tại để debug
        if (isset($_GET['status']) && $_GET['status'] === '0') {
            log_message('debug', 'Pager: Đang xử lý getPageNumbers với status=0');
        }
        
        // Nếu tổng số trang ít, hiển thị tất cả
        if ($this->pageCount <= ($this->surroundCount * 2) + 3) {
            return range(1, max(1, $this->pageCount));
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
        // Nếu chỉ có 1 trang hoặc không có bản ghi nào, không cần hiển thị phân trang
        if ($this->pageCount <= 1 || $this->total <= 0) {
            return '';
        }
        
        // Tìm kiếm template phân trang
        $viewPath = 'App\Modules\camera\Views\pagers\pager';
        
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
} 