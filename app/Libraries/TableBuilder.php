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
        
        // Thiết lập template mặc định nếu có
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
        $this->filters = $filters;
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
     * Generate HTML table từ dữ liệu
     * 
     * @param mixed $data Dữ liệu bổ sung (nếu có)
     * @return string
     */
    public function generate($data = null)
    {
        // Sử dụng dữ liệu hiện tại nếu không có dữ liệu được cung cấp
        if ($data === null) {
            $data = $this->data;
        }
        
        // Generate bảng HTML cơ bản
        $tableHtml = $this->table->generate($data);
        
        // Tạo bộ lọc nếu có
        $filterHtml = '';
        if (!empty($this->filters)) {
            $filterHtml = $this->renderFilters();
        }
        
        // Thêm DataTable nếu được bật
        if ($this->useDataTable) {
            $tableId = $this->config['id'] ?? 'table-' . uniqid();
            
            // Thêm ID vào bảng nếu chưa có
            if (strpos($tableHtml, 'id=') === false) {
                $tableHtml = str_replace('<table', '<table id="' . $tableId . '"', $tableHtml);
            }
            
            // Thiết lập tùy chọn mặc định cho DataTable
            $defaultOptions = [
                'paging' => true,
                'searching' => true,
                'ordering' => true,
                'info' => true,
                'responsive' => true,
                'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'Tất cả']],
                'language' => [
                    'url' => '//cdn.datatables.net/plug-ins/1.13.7/i18n/Vietnamese.json'
                ]
            ];
            
            // Merge với tùy chọn người dùng
            $options = array_merge($defaultOptions, $this->dataTableOptions);
            
            // Thêm buttons nếu có tùy chọn xuất
            if (!empty($this->exportOptions)) {
                $options['dom'] = 'Blfrtip';
                $options['buttons'] = $this->getExportButtons();
            }
            
            // Tạo script DataTable
            $optionsJson = json_encode($options);
            $script = <<<EOT
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (typeof $.fn.DataTable !== 'undefined') {
                    var table = $('#{$tableId}').DataTable({$optionsJson});
                    
                    // Xử lý sự kiện cho bộ lọc
                    $('.table-filter-submit').on('click', function(e) {
                        e.preventDefault();
                        applyFilters(table);
                    });
                    
                    $('.table-filter-reset').on('click', function(e) {
                        e.preventDefault();
                        resetFilters(table);
                    });
                    
                    // Hàm áp dụng bộ lọc
                    function applyFilters(table) {
                        // Lọc theo tùy chỉnh
                        $.fn.dataTable.ext.search = [];
                        
                        // Thêm các hàm lọc tùy chỉnh
                        var dateRangeFilters = [];
                        
                        // Lọc khoảng ngày
                        $('.date-range-filter').each(function() {
                            var fromDate = $(this).find('.date-from').val();
                            var toDate = $(this).find('.date-to').val();
                            var columnIndex = $(this).data('column');
                            
                            if (fromDate || toDate) {
                                dateRangeFilters.push({
                                    fromDate: fromDate ? new Date(fromDate) : null,
                                    toDate: toDate ? new Date(toDate) : null,
                                    columnIndex: columnIndex
                                });
                            }
                        });
                        
                        if (dateRangeFilters.length > 0) {
                            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                                var valid = true;
                                
                                for (var i = 0; i < dateRangeFilters.length; i++) {
                                    var filter = dateRangeFilters[i];
                                    var cellData = data[filter.columnIndex];
                                    var cellDate = new Date(cellData);
                                    
                                    if (filter.fromDate && cellDate < filter.fromDate) {
                                        valid = false;
                                        break;
                                    }
                                    
                                    if (filter.toDate && cellDate > filter.toDate) {
                                        valid = false;
                                        break;
                                    }
                                }
                                
                                return valid;
                            });
                        }
                        
                        // Lọc theo select
                        $('.select-filter').each(function() {
                            var value = $(this).val();
                            var columnIndex = $(this).data('column');
                            
                            if (value && value !== '') {
                                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                                    return data[columnIndex] === value;
                                });
                            }
                        });
                        
                        // Lọc theo text
                        $('.text-filter').each(function() {
                            var value = $(this).val();
                            var columnIndex = $(this).data('column');
                            
                            if (value && value !== '') {
                                table.column(columnIndex).search(value);
                            }
                        });
                        
                        // Lọc khoảng số
                        $('.number-range-filter').each(function() {
                            var minValue = $(this).find('.number-min').val();
                            var maxValue = $(this).find('.number-max').val();
                            var columnIndex = $(this).data('column');
                            
                            if (minValue || maxValue) {
                                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                                    var value = parseFloat(data[columnIndex]) || 0;
                                    
                                    if (minValue && value < parseFloat(minValue)) {
                                        return false;
                                    }
                                    
                                    if (maxValue && value > parseFloat(maxValue)) {
                                        return false;
                                    }
                                    
                                    return true;
                                });
                            }
                        });
                        
                        // Cập nhật bảng
                        table.draw();
                    }
                    
                    // Hàm reset bộ lọc
                    function resetFilters(table) {
                        $('.table-filter-form')[0].reset();
                        $.fn.dataTable.ext.search = [];
                        table.search('').columns().search('').draw();
                    }
                } else {
                    console.error('DataTables không được tải!');
                }
            });
            </script>
            EOT;
            
            // Thêm CSS và JS cần thiết
            $headerScripts = $this->getDataTableAssets();
            
            // Thêm thủ công các nút export nếu cần
            $manualExportButtons = '';
            if (!empty($this->exportOptions)) {
                $manualExportButtons = $this->getManualExportButtons($tableId);
            }
            
            return $headerScripts . $filterHtml . $manualExportButtons . $tableHtml . $script;
        }
        
        return $filterHtml . $tableHtml;
    }
    
    /**
     * Tạo các nút export thủ công nếu DataTables không hoạt động
     *
     * @param string $tableId ID của bảng
     * @return string HTML cho các nút export
     */
    protected function getManualExportButtons($tableId)
    {
        if (empty($this->exportOptions)) {
            return '';
        }
        
        $buttons = '<div class="btn-export-group">';
        $buttons .= '<div class="btn-group" role="group" aria-label="Export buttons">';
        
        // Nếu exportOptions là true, thêm tất cả các nút
        if ($this->exportOptions === true) {
            $buttons .= '<button type="button" class="btn btn-sm btn-outline-secondary export-copy" data-table="' . $tableId . '"><i class="bi bi-clipboard"></i> Sao chép</button>';
            $buttons .= '<button type="button" class="btn btn-sm btn-outline-success export-excel" data-table="' . $tableId . '"><i class="bi bi-file-earmark-excel"></i> Excel</button>';
            $buttons .= '<button type="button" class="btn btn-sm btn-outline-danger export-pdf" data-table="' . $tableId . '"><i class="bi bi-file-earmark-pdf"></i> PDF</button>';
            $buttons .= '<button type="button" class="btn btn-sm btn-outline-dark export-print" data-table="' . $tableId . '"><i class="bi bi-printer"></i> In</button>';
        } 
        // Nếu exportOptions là mảng, thêm các nút theo cấu hình
        else if (is_array($this->exportOptions)) {
            if (in_array('copy', $this->exportOptions)) {
                $buttons .= '<button type="button" class="btn btn-sm btn-outline-secondary export-copy" data-table="' . $tableId . '"><i class="bi bi-clipboard"></i> Sao chép</button>';
            }
            
            if (in_array('excel', $this->exportOptions)) {
                $buttons .= '<button type="button" class="btn btn-sm btn-outline-success export-excel" data-table="' . $tableId . '"><i class="bi bi-file-earmark-excel"></i> Excel</button>';
            }
            
            if (in_array('pdf', $this->exportOptions)) {
                $buttons .= '<button type="button" class="btn btn-sm btn-outline-danger export-pdf" data-table="' . $tableId . '"><i class="bi bi-file-earmark-pdf"></i> PDF</button>';
            }
            
            if (in_array('print', $this->exportOptions)) {
                $buttons .= '<button type="button" class="btn btn-sm btn-outline-dark export-print" data-table="' . $tableId . '"><i class="bi bi-printer"></i> In</button>';
            }
        }
        
        $buttons .= '</div>';
        $buttons .= '</div>';
        
        return $buttons;
    }
    
    /**
     * Lấy cấu hình nút xuất dữ liệu
     *
     * @return array
     */
    protected function getExportButtons()
    {
        $defaultButtons = [];
        
        // Nếu exportOptions là true, thêm tất cả các nút
        if ($this->exportOptions === true) {
            $defaultButtons = [
                [
                    'extend' => 'copy',
                    'text' => '<i class="bi bi-clipboard"></i> Sao chép',
                    'className' => 'btn btn-sm btn-outline-secondary',
                    'exportOptions' => [
                        'columns' => ':visible:not(:last-child)'
                    ]
                ],
                [
                    'extend' => 'excel',
                    'text' => '<i class="bi bi-file-earmark-excel"></i> Excel',
                    'className' => 'btn btn-sm btn-outline-success',
                    'exportOptions' => [
                        'columns' => ':visible:not(:last-child)'
                    ]
                ],
                [
                    'extend' => 'csv',
                    'text' => '<i class="bi bi-file-earmark-text"></i> CSV',
                    'className' => 'btn btn-sm btn-outline-info',
                    'exportOptions' => [
                        'columns' => ':visible:not(:last-child)'
                    ]
                ],
                [
                    'extend' => 'pdf',
                    'text' => '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    'className' => 'btn btn-sm btn-outline-danger',
                    'exportOptions' => [
                        'columns' => ':visible:not(:last-child)'
                    ]
                ],
                [
                    'extend' => 'print',
                    'text' => '<i class="bi bi-printer"></i> In',
                    'className' => 'btn btn-sm btn-outline-dark',
                    'exportOptions' => [
                        'columns' => ':visible:not(:last-child)'
                    ]
                ]
            ];
        } 
        // Nếu exportOptions là mảng, thêm các nút theo cấu hình
        else if (is_array($this->exportOptions)) {
            if (in_array('copy', $this->exportOptions)) {
                $defaultButtons[] = [
                    'extend' => 'copy',
                    'text' => '<i class="bi bi-clipboard"></i> Sao chép',
                    'className' => 'btn btn-sm btn-outline-secondary',
                    'exportOptions' => [
                        'columns' => ':visible:not(:last-child)'
                    ]
                ];
            }
            
            if (in_array('excel', $this->exportOptions)) {
                $defaultButtons[] = [
                    'extend' => 'excel',
                    'text' => '<i class="bi bi-file-earmark-excel"></i> Excel',
                    'className' => 'btn btn-sm btn-outline-success',
                    'exportOptions' => [
                        'columns' => ':visible:not(:last-child)'
                    ]
                ];
            }
            
            if (in_array('csv', $this->exportOptions)) {
                $defaultButtons[] = [
                    'extend' => 'csv',
                    'text' => '<i class="bi bi-file-earmark-text"></i> CSV',
                    'className' => 'btn btn-sm btn-outline-info',
                    'exportOptions' => [
                        'columns' => ':visible:not(:last-child)'
                    ]
                ];
            }
            
            if (in_array('pdf', $this->exportOptions)) {
                $defaultButtons[] = [
                    'extend' => 'pdf',
                    'text' => '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    'className' => 'btn btn-sm btn-outline-danger',
                    'exportOptions' => [
                        'columns' => ':visible:not(:last-child)'
                    ]
                ];
            }
            
            if (in_array('print', $this->exportOptions)) {
                $defaultButtons[] = [
                    'extend' => 'print',
                    'text' => '<i class="bi bi-printer"></i> In',
                    'className' => 'btn btn-sm btn-outline-dark',
                    'exportOptions' => [
                        'columns' => ':visible:not(:last-child)'
                    ]
                ];
            }
        }
        
        return $defaultButtons;
    }
    
    /**
     * Lấy CSS và JS cần thiết cho DataTable
     *
     * @return string
     */
    protected function getDataTableAssets()
    {
        $assets = '
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">';
        
        if (!empty($this->exportOptions)) {
            $assets .= '
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">';
        }
        
        $assets .= '
        <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>';
        
        if (!empty($this->exportOptions)) {
            $assets .= '
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>';
        }
        
        $assets .= '
        <style>
            .dt-buttons {
                margin-bottom: 15px;
                display: flex;
                gap: 5px;
                flex-wrap: wrap;
            }
            .dt-buttons .btn {
                margin-right: 5px;
            }
            /* Cải thiện CSS cho phân trang */
            .dataTables_wrapper .dataTables_paginate {
                margin-top: 15px;
                display: flex;
                justify-content: flex-end;
                font-size: 0.75rem;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                padding: 0.2em 0.4em;
                margin: 0;
                border-radius: 3px;
                border: none;
                color: #0d6efd !important;
                cursor: pointer;
                transition: all 0.2s ease;
                background: transparent !important;
                position: relative;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                background-color: rgba(13, 110, 253, 0.1) !important;
                color: #0a58ca !important;
                box-shadow: none;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: #0d6efd !important;
                color: #fff !important;
                border: none;
                font-weight: 500;
            }
            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
                color: #adb5bd !important;
                background-color: transparent !important;
                border: none;
                cursor: not-allowed;
                opacity: 0.5;
            }
            .dataTables_wrapper .dataTables_paginate .ellipsis {
                padding: 0.25em 0.4em;
                color: #6c757d;
            }
            .dataTables_wrapper .dataTables_length, 
            .dataTables_wrapper .dataTables_filter {
                margin-bottom: 15px;
            }
            .btn-export-group {
                display: flex;
                margin-bottom: 15px;
                gap: 5px;
            }
            /* CSS cho bộ lọc báo cáo */
            .table-filter-container {
                margin-bottom: 1.5rem;
            }
            .table-filter-container .card-header {
                background-color: #f8f9fa;
                padding: 0.75rem 1rem;
            }
            .table-filter-container .card-body {
                padding: 1rem;
                background-color: #ffffff;
            }
            .table-filter-container .form-group {
                margin-bottom: 1rem;
            }
            .table-filter-container label {
                font-size: 0.875rem;
                font-weight: 500;
                margin-bottom: 0.25rem;
                display: block;
            }
            .table-filter-container .form-control,
            .table-filter-container .form-select {
                font-size: 0.875rem;
            }
            .table-filter-container .btn-sm {
                font-size: 0.875rem;
                padding: 0.25rem 0.75rem;
            }
            .date-range-filter, .number-range-filter {
                margin-top: 5px;
            }
            @media (max-width: 768px) {
                .table-filter-container .btn {
                    width: 100%;
                    margin-bottom: 5px;
                }
                .table-filter-container .d-flex {
                    flex-direction: column;
                }
                .table-filter-container .me-2 {
                    margin-right: 0 !important;
                }
            }
        </style>';
        
        // Thêm JavaScript xử lý các nút export thủ công
        if (!empty($this->exportOptions)) {
            $assets .= '
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Xử lý sự kiện cho nút sao chép
                document.querySelectorAll(".export-copy").forEach(function(button) {
                    button.addEventListener("click", function() {
                        var tableId = this.getAttribute("data-table");
                        var table = $("#" + tableId).DataTable();
                        
                        if (table) {
                            // Nếu DataTable đã được khởi tạo, sử dụng nút của DataTable
                            table.button(".buttons-copy").trigger();
                        } else {
                            // Ngược lại, thực hiện sao chép thủ công
                            copyTableToClipboard(tableId);
                        }
                    });
                });
                
                // Xử lý sự kiện cho nút Excel
                document.querySelectorAll(".export-excel").forEach(function(button) {
                    button.addEventListener("click", function() {
                        var tableId = this.getAttribute("data-table");
                        var table = $("#" + tableId).DataTable();
                        
                        if (table) {
                            // Nếu DataTable đã được khởi tạo, sử dụng nút của DataTable
                            table.button(".buttons-excel").trigger();
                        }
                    });
                });
                
                // Xử lý sự kiện cho nút PDF
                document.querySelectorAll(".export-pdf").forEach(function(button) {
                    button.addEventListener("click", function() {
                        var tableId = this.getAttribute("data-table");
                        var table = $("#" + tableId).DataTable();
                        
                        if (table) {
                            // Nếu DataTable đã được khởi tạo, sử dụng nút của DataTable
                            table.button(".buttons-pdf").trigger();
                        }
                    });
                });
                
                // Xử lý sự kiện cho nút In
                document.querySelectorAll(".export-print").forEach(function(button) {
                    button.addEventListener("click", function() {
                        var tableId = this.getAttribute("data-table");
                        var table = $("#" + tableId).DataTable();
                        
                        if (table) {
                            // Nếu DataTable đã được khởi tạo, sử dụng nút của DataTable
                            table.button(".buttons-print").trigger();
                        }
                    });
                });
                
                // Hàm sao chép bảng vào clipboard
                function copyTableToClipboard(tableId) {
                    var table = document.getElementById(tableId);
                    var range = document.createRange();
                    range.selectNode(table);
                    window.getSelection().removeAllRanges();
                    window.getSelection().addRange(range);
                    
                    try {
                        document.execCommand("copy");
                        alert("Đã sao chép bảng vào clipboard!");
                    } catch (err) {
                        console.error("Không thể sao chép: ", err);
                    }
                    
                    window.getSelection().removeAllRanges();
                }
            });
            </script>';
        }
        
        return $assets;
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
                        <form class="table-filter-form">
                            <div class="row g-3">';
        
        foreach ($this->filters as $filter) {
            $type = $filter['type'] ?? 'text';
            $name = $filter['name'] ?? '';
            $label = $filter['label'] ?? '';
            $column = $filter['column'] ?? 0;
            $placeholder = $filter['placeholder'] ?? '';
            $options = $filter['options'] ?? [];
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
                            id="filter-' . $name . '-from" name="' . $name . '-from" placeholder="Từ ngày">
                    </div>
                    <div class="col-6">
                        <input type="date" class="form-control form-control-sm date-to" 
                            id="filter-' . $name . '-to" name="' . $name . '-to" placeholder="Đến ngày">
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
                            id="filter-' . $name . '-min" name="' . $name . '-min" placeholder="Từ">
                    </div>
                    <div class="col-6">
                        <input type="number" class="form-control form-control-sm number-max" 
                            id="filter-' . $name . '-max" name="' . $name . '-max" placeholder="Đến">
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