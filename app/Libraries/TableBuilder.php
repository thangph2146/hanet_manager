<?php

namespace App\Libraries;

use CodeIgniter\View\Table;

/**
 * TableBuilder - Thư viện xây dựng bảng linh hoạt cho CodeIgniter 4
 * Hỗ trợ tạo bảng dựa trên cấu hình, làm đơn giản hóa việc xây dựng bảng HTML
 */
class TableBuilder
{
    protected $table;
    protected $config = [];
    protected $data = [];
    protected $heading = [];
    protected $footing = [];
    protected $useDataTable = false;
    protected $dataTableOptions = [];
    protected $exportOptions = [];
    protected $filters = [];
    
    /**
     * Khởi tạo TableBuilder
     */
    public function __construct(array $config = [])
    {
        $this->table = new Table();
        
        // Khởi tạo các thuộc tính mặc định
        $this->data = [];
        $this->heading = [];
        $this->footing = [];
        $this->useDataTable = false;
        $this->dataTableOptions = [];
        $this->exportOptions = [
            'enable' => false,
            'copy' => false,
            'excel' => false,
            'pdf' => false,
            'print' => false
        ];
        $this->filters = [];
        
        // Thiết lập template mặc định để bảng đẹp hơn
        $this->table->setTemplate([
            'table_open' => '<table class="table table-striped table-bordered">',
            'thead_open' => '<thead>',
            'thead_close' => '</thead>',
            'tbody_open' => '<tbody>',
            'tbody_close' => '</tbody>'
        ]);
        
        // Thiết lập template nếu có
        if (isset($config['template'])) {
            $this->table->setTemplate($config['template']);
        }
        
        // Thiết lập caption nếu có
        if (isset($config['caption'])) {
            $this->table->setCaption($config['caption']);
        }
        
        $this->config = $config;
    }
    
    /**
     * Thiết lập các bộ lọc cho bảng
     *
     * @param array $filters Mảng chứa cấu hình của các bộ lọc
     * @return TableBuilder
     */
    public function setFilters(array $filters)
    {
        // Đảm bảo định dạng đúng cho filters
        $formattedFilters = [];
        foreach ($filters as $key => $filter) {
            // Nếu là mảng tuần tự (không có key là chuỗi), thêm trực tiếp
            if (is_int($key)) {
                $formattedFilters[] = $filter;
            } else {
                // Nếu là mảng kết hợp, chuyển key thành phần tử name
                $filter['name'] = $filter['name'] ?? $key;
                $formattedFilters[] = $filter;
            }
        }
        
        $this->filters = $formattedFilters;
        return $this;
    }
    
    /**
     * Bật/tắt sử dụng DataTable
     * 
     * @param bool $use Bật/tắt sử dụng DataTable
     * @return TableBuilder
     */
    public function useDataTable($use = true)
    {
        $this->useDataTable = $use;
        return $this;
    }
    
    /**
     * Thiết lập tùy chọn cho DataTable
     * 
     * @param array $options Tùy chọn cho DataTable
     * @return TableBuilder
     */
    public function setDataTableOptions(array $options)
    {
        $this->dataTableOptions = $options;
        return $this;
    }
    
    /**
     * Thiết lập tùy chọn xuất dữ liệu
     * 
     * @param array|bool $options Tùy chọn xuất dữ liệu hoặc true để bật tất cả
     * @return TableBuilder
     */
    public function setExportOptions($options)
    {
        $this->exportOptions = $options;
        return $this;
    }
    
    /**
     * Đặt cấu hình bảng
     * 
     * @param array $config Cấu hình bảng
     * @return TableBuilder
     */
    public function config(array $config)
    {
        $this->config = array_merge($this->config, $config);
        
        // Thiết lập tiêu đề nếu có
        if (isset($config['heading'])) {
            $this->setHeading($config['heading']);
        }
        
        // Thiết lập footer nếu có
        if (isset($config['footing'])) {
            $this->setFooting($config['footing']);
        }
        
        // Thiết lập dữ liệu nếu có
        if (isset($config['data'])) {
            $this->setData($config['data']);
        }
        
        // Thiết lập caption nếu có
        if (isset($config['caption'])) {
            $this->setCaption($config['caption']);
        }
        
        // Thiết lập template nếu có
        if (isset($config['template'])) {
            $this->setTemplate($config['template']);
        }
        
        // Thiết lập tùy chọn DataTable nếu có
        if (isset($config['dataTableOptions'])) {
            $this->setDataTableOptions($config['dataTableOptions']);
        }
        
        // Bật/tắt DataTable nếu có
        if (isset($config['useDataTable'])) {
            $this->useDataTable($config['useDataTable']);
        }
        
        // Thiết lập tùy chọn xuất dữ liệu nếu có
        if (isset($config['exportOptions'])) {
            $this->setExportOptions($config['exportOptions']);
        }
        
        // Thiết lập các bộ lọc nếu có
        if (isset($config['filters'])) {
            $this->setFilters($config['filters']);
        }
        
        return $this;
    }
    
    /**
     * Đặt tiêu đề cho bảng
     * 
     * @param mixed $heading Tiêu đề cho bảng
     * @return TableBuilder
     */
    public function setHeading($heading)
    {
        if (is_array($heading)) {
            $this->heading = $heading;
            call_user_func_array([$this->table, 'setHeading'], $heading);
        } else {
            $this->heading = func_get_args();
            call_user_func_array([$this->table, 'setHeading'], func_get_args());
        }
        
        return $this;
    }
    
    /**
     * Đặt footer cho bảng
     * 
     * @param mixed $footing Footer cho bảng
     * @return TableBuilder
     */
    public function setFooting($footing)
    {
        if (is_array($footing)) {
            $this->footing = $footing;
            call_user_func_array([$this->table, 'setFooting'], $footing);
        } else {
            $this->footing = func_get_args();
            call_user_func_array([$this->table, 'setFooting'], func_get_args());
        }
        
        return $this;
    }
    
    /**
     * Thêm hàng vào bảng
     * 
     * @param mixed $row Dữ liệu hàng
     * @return TableBuilder
     */
    public function addRow($row)
    {
        if (is_array($row)) {
            call_user_func_array([$this->table, 'addRow'], $row);
        } else {
            call_user_func_array([$this->table, 'addRow'], func_get_args());
        }
        
        return $this;
    }
    
    /**
     * Đặt dữ liệu cho bảng
     * 
     * @param mixed $data Dữ liệu cho bảng
     * @return TableBuilder
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
    
    /**
     * Đặt caption cho bảng
     * 
     * @param string $caption Caption cho bảng
     * @return TableBuilder
     */
    public function setCaption($caption)
    {
        $this->table->setCaption($caption);
        return $this;
    }
    
    /**
     * Đặt template cho bảng
     * 
     * @param array $template Template cho bảng
     * @return TableBuilder
     */
    public function setTemplate($template)
    {
        $this->table->setTemplate($template);
        return $this;
    }
    
    /**
     * Format cột theo callback
     *
     * @param int $column Chỉ số cột
     * @param callable $callback Callback function
     * @return TableBuilder
     */
    public function formatColumn($column, callable $callback)
    {
        if (is_array($this->data)) {
            foreach ($this->data as $i => $row) {
                if (isset($row[$column])) {
                    $this->data[$i][$column] = $callback($row[$column], $row, $i);
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Generate HTML for the table
     * 
     * @return string
     */
    public function generate($data = null)
    {
        // Cập nhật dữ liệu nếu có
        if ($data !== null) {
            $this->setData($data);
        }

        // Sử dụng phương thức Generate của CodeIgniter's Table class
        $html = $this->table->generate($this->data);
        
        // Thêm id duy nhất cho bảng nếu chưa có
        $tableId = '';
        $dom = new \DOMDocument();
        @$dom->loadHTML($html);
        $tables = $dom->getElementsByTagName('table');
        if ($tables->length > 0) {
            $tableElement = $tables->item(0);
            if ($tableElement->hasAttribute('id')) {
                $tableId = $tableElement->getAttribute('id');
            } else {
                $tableId = 'table_' . uniqid();
                // Thêm ID vào thẻ table
                $html = preg_replace('/<table/', '<table id="' . $tableId . '"', $html, 1);
            }
            
            // Thêm class table-builder để đánh dấu bảng cần xử lý bởi JS
            if ($this->useDataTable) {
                $html = preg_replace('/<table(.*?)class="(.*?)"/', '<table$1class="$2 table-builder"', $html, 1);
                if (strpos($html, 'table-builder') === false) {
                    $html = preg_replace('/<table/', '<table class="table-builder"', $html, 1);
                }
            }
        }
        
        // Thêm data-config nếu có tùy chọn DataTable
        if ($this->useDataTable && !empty($this->dataTableOptions)) {
            $configJson = htmlspecialchars(json_encode($this->dataTableOptions), ENT_QUOTES, 'UTF-8');
            $html = preg_replace('/<table(.*?)>/', '<table$1 data-config="' . $configJson . '">', $html, 1);
        }
        
        // Hiển thị bộ lọc nếu có
        $filterHtml = '';
        if (!empty($this->filters)) {
            $filterHtml = $this->renderFilters();
        }
        
        // Thêm các nút export thủ công nếu cần
        $exportButtons = '';
        if ($this->useDataTable && !empty($this->exportOptions) && $this->exportOptions['enable']) {
            $exportButtons = $this->getManualExportButtons($tableId);
            
            // Thêm script flag để đánh dấu trang có sử dụng TableBuilder
            $html .= '<script>document.documentElement.classList.add("table-builder-enabled");</script>';
        }
        
        return $filterHtml . $exportButtons . $html;
    }
    
    /**
     * Tạo các nút xuất thủ công
     * 
     * @param string $tableId ID của bảng
     * @return string
     */
    protected function getManualExportButtons($tableId)
    {
        if (!$this->useDataTable || empty($this->exportOptions) || !$this->exportOptions['enable']) {
            return '';
        }
        
        $buttons = '<div class="manual-export-buttons d-flex flex-wrap mb-2" data-tableid="'.$tableId.'" id="export-buttons-'.$tableId.'">';
        
        if ($this->exportOptions['excel']) {
            $buttons .= '<button type="button" class="btn btn-success btn-sm me-1 btn-excel" data-tableid="'.$tableId.'">
                <i class="bi bi-file-earmark-excel" style="display:inline-block;"></i> Excel
            </button>';
        }
        
        if ($this->exportOptions['pdf']) {
            $buttons .= '<button type="button" class="btn btn-danger btn-sm me-1 btn-pdf" data-tableid="'.$tableId.'">
                <i class="bi bi-file-earmark-pdf" style="display:inline-block;"></i> PDF
            </button>';
        }
        
        $buttons .= '</div>';
        
        return $buttons;
    }
    
    /**
     * Tạo HTML cho bộ lọc
     *
     * @return string
     */
    protected function renderFilters()
    {
        if (empty($this->filters)) {
            return '';
        }
        
        $html = '<div class="table-filter-container mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Bộ lọc báo cáo</h5>
                    <button type="button" class="btn btn-sm btn-link" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>
                <div class="collapse show" id="filterCollapse">
                    <div class="card-body">
                        <form class="table-filter-form" method="get">
                            <div class="row g-3">';
        
        foreach ($this->filters as $filter) {
            $type = $filter['type'] ?? 'text';
            $name = $filter['name'] ?? '';
            $label = $filter['label'] ?? '';
            $column = $filter['column'] ?? 0;
            $placeholder = $filter['placeholder'] ?? '';
            $options = isset($filter['options']) && is_array($filter['options']) ? $filter['options'] : [];
            $class = $filter['class'] ?? 'col-md-4 col-12';
            
            $html .= '<div class="' . $class . '">';
            
            switch ($type) {
                case 'text':
                    $html .= $this->renderTextFilter($name, $label, $column, $placeholder);
                    break;
                    
                case 'select':
                    $html .= $this->renderSelectFilter($name, $label, $column, $options);
                    break;
                    
                case 'date':
                    $html .= $this->renderDateFilter($name, $label, $column, $placeholder);
                    break;
                    
                case 'daterange':
                    $html .= $this->renderDateRangeFilter($name, $label, $column);
                    break;
                    
                case 'number':
                    $html .= $this->renderNumberFilter($name, $label, $column, $placeholder);
                    break;
                    
                case 'numberrange':
                    $html .= $this->renderNumberRangeFilter($name, $label, $column);
                    break;
            }
            
            $html .= '</div>';
        }
        
        $html .= '</div>
                            <div class="d-flex justify-content-end mt-3">
                                <button type="button" class="btn btn-secondary table-filter-reset me-2">Làm mới</button>
                                <button type="button" class="btn btn-primary table-filter-submit">Lọc dữ liệu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>';
        
        // Thêm JavaScript để kích hoạt tính năng lọc
        $html .= '<script>document.documentElement.classList.add("table-filter-enabled");</script>';
        
        return $html;
    }
    
    /**
     * Tạo HTML cho bộ lọc text
     *
     * @param string $name
     * @param string $label
     * @param int $column
     * @param string $placeholder
     * @return string
     */
    protected function renderTextFilter($name, $label, $column, $placeholder = '')
    {
        return '<div class="form-group">
            <label for="filter-' . $name . '">' . $label . '</label>
            <input type="text" class="form-control form-control-sm text-filter" id="filter-' . $name . '" 
                name="' . $name . '" placeholder="' . $placeholder . '" data-column="' . $column . '">
        </div>';
    }
    
    /**
     * Tạo HTML cho bộ lọc select
     *
     * @param string $name
     * @param string $label
     * @param int $column
     * @param array $options
     * @return string
     */
    protected function renderSelectFilter($name, $label, $column, $options = [])
    {
        $html = '<div class="form-group">
            <label for="filter-' . $name . '">' . $label . '</label>
            <select class="form-select form-select-sm select-filter" id="filter-' . $name . '" 
                name="' . $name . '" data-column="' . $column . '">
                <option value="">-- Tất cả --</option>';
        
        foreach ($options as $value => $text) {
            $html .= '<option value="' . $value . '">' . $text . '</option>';
        }
        
        $html .= '</select>
        </div>';
        
        return $html;
    }
    
    /**
     * Tạo HTML cho bộ lọc date
     *
     * @param string $name
     * @param string $label
     * @param int $column
     * @param string $placeholder
     * @return string
     */
    protected function renderDateFilter($name, $label, $column, $placeholder = '')
    {
        return '<div class="form-group">
            <label for="filter-' . $name . '">' . $label . '</label>
            <input type="date" class="form-control form-control-sm date-filter" id="filter-' . $name . '" 
                name="' . $name . '" placeholder="' . $placeholder . '" data-column="' . $column . '">
        </div>';
    }
    
    /**
     * Tạo HTML cho bộ lọc daterange
     *
     * @param string $name
     * @param string $label
     * @param int $column
     * @return string
     */
    protected function renderDateRangeFilter($name, $label, $column)
    {
        return '<div class="form-group">
            <label>' . $label . '</label>
            <div class="date-range-filter" data-column="' . $column . '">
                <div class="row g-2">
                    <div class="col-6">
                        <input type="date" class="form-control form-control-sm date-from" 
                            id="filter-' . $name . '-from" name="' . $name . '_from" placeholder="Từ ngày">
                    </div>
                    <div class="col-6">
                        <input type="date" class="form-control form-control-sm date-to" 
                            id="filter-' . $name . '-to" name="' . $name . '_to" placeholder="Đến ngày">
                    </div>
                </div>
            </div>
        </div>';
    }
    
    /**
     * Tạo HTML cho bộ lọc number
     *
     * @param string $name
     * @param string $label
     * @param int $column
     * @param string $placeholder
     * @return string
     */
    protected function renderNumberFilter($name, $label, $column, $placeholder = '')
    {
        return '<div class="form-group">
            <label for="filter-' . $name . '">' . $label . '</label>
            <input type="number" class="form-control form-control-sm number-filter" id="filter-' . $name . '" 
                name="' . $name . '" placeholder="' . $placeholder . '" data-column="' . $column . '">
        </div>';
    }
    
    /**
     * Tạo HTML cho bộ lọc numberrange
     *
     * @param string $name
     * @param string $label
     * @param int $column
     * @return string
     */
    protected function renderNumberRangeFilter($name, $label, $column)
    {
        return '<div class="form-group">
            <label>' . $label . '</label>
            <div class="number-range-filter" data-column="' . $column . '">
                <div class="row g-2">
                    <div class="col-6">
                        <input type="number" class="form-control form-control-sm number-min" 
                            id="filter-' . $name . '-min" name="' . $name . '_min" placeholder="Từ">
                    </div>
                    <div class="col-6">
                        <input type="number" class="form-control form-control-sm number-max" 
                            id="filter-' . $name . '-max" name="' . $name . '_max" placeholder="Đến">
                    </div>
                </div>
            </div>
        </div>';
    }
    
    /**
     * Magic method để chuyển các phương thức không tồn tại đến đối tượng Table
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (method_exists($this->table, $method)) {
            $result = call_user_func_array([$this->table, $method], $args);
            
            // Nếu kết quả là đối tượng Table, trả về $this để method chaining
            if ($result instanceof Table) {
                return $this;
            }
            
            return $result;
        }
        
        throw new \BadMethodCallException("Phương thức {$method} không tồn tại trong lớp Table");
    }
} 