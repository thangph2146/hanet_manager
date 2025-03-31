<?php

namespace App\Libraries;

use CodeIgniter\I18n\Time;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * Thư viện Modules
 * 
 * Cung cấp các dịch vụ chung để xử lý các chức năng của tất cả các module
 * giúp làm giảm mã lặp lại và tối ưu hóa code
 */
class Modules
{
    /**
     * Đối tượng model của module
     *
     * @var \App\Models\BaseModel
     */
    protected $model;
    
    /**
     * Breadcrumb của module
     *
     * @var \App\Libraries\Breadcrumb
     */
    protected $breadcrumb;
    
    /**
     * Đối tượng Alert để hiển thị thông báo
     *
     * @var \App\Libraries\Alert
     */
    protected $alert;
    
    /**
     * URL cơ sở của module
     * Ví dụ: base_url('admin/bachoc')
     *
     * @var string
     */
    protected $moduleUrl;
    
    /**
     * Tiêu đề của module
     * Ví dụ: 'Bậc Học'
     *
     * @var string
     */
    protected $title;
    
    /**
     * Tên module
     * Ví dụ: 'bachoc'
     *
     * @var string
     */
    protected $module_name;
    
    /**
     * Tên controller
     * Ví dụ: 'BacHoc'
     * 
     * @var string
     */
    protected $controller_name;
    
    /**
     * Đường dẫn route URL của module
     * Ví dụ: 'admin/bachoc'
     *
     * @var string
     */
    protected $route_url;
    
    /**
     * Đối tượng MasterScript để quản lý scripts và styles
     *
     * @var object
     */
    protected $masterScript;
    
    /**
     * Constructor
     * 
     * @param array $config Cấu hình cho module
     */
    public function __construct(array $config = [])
    {
        // Thiết lập các thuộc tính từ cấu hình
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        // Khởi tạo session nếu cần
        $this->session = service('session');
        
        // Khởi tạo đối tượng MasterScript
        if (!empty($this->route_url) && !empty($this->module_name)) {
            $this->initializeMasterScript();
        }
    }
    
    /**
     * Khởi tạo MasterScript
     */
    public function initializeMasterScript()
    {
        $masterScriptClass = "\\App\\Modules\\{$this->module_name}\\Libraries\\MasterScript";
        if (class_exists($masterScriptClass)) {
            $this->masterScript = new $masterScriptClass($this->route_url, $this->module_name);
        } else {
            log_message('warning', "Không tìm thấy lớp MasterScript cho module {$this->module_name}");
        }
    }
    
    /**
     * Khởi tạo model, breadcrumb và alert
     * 
     * @param string $modelClass Tên đầy đủ của class model
     * @return $this
     */
    public function initialize($modelClass, $title = '', $route_url = '')
    {
        // Khởi tạo model
        if (class_exists($modelClass)) {
            $this->model = new $modelClass();
        } else {
            throw new \RuntimeException("Không tìm thấy lớp model: {$modelClass}");
        }
        
        // Khởi tạo breadcrumb và alert
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thiết lập tiêu đề và route_url nếu cung cấp
        if (!empty($title)) {
            $this->title = $title;
        }
        
        if (!empty($route_url)) {
            $this->route_url = $route_url;
            $this->moduleUrl = base_url($route_url);
        }
        
        return $this;
    }
    
    /**
     * Xử lý tham số tìm kiếm
     * 
     * @param \CodeIgniter\HTTP\IncomingRequest $request Request hiện tại
     * @return array Tham số tìm kiếm đã xử lý
     */
    public function prepareSearchParams($request)
    {
        $params = [
            'page' => (int)($request->getGet('page') ?? 1),
            'perPage' => (int)($request->getGet('perPage') ?? 10),
            'sort' => $request->getGet('sort') ?? 'created_at',
            'order' => $request->getGet('order') ?? 'DESC',
            'keyword' => $request->getGet('keyword') ?? '',
            'status' => $request->getGet('status'),
        ];
        
        // Kiểm tra và điều chỉnh các tham số không hợp lệ
        if ($params['page'] < 1) {
            $params['page'] = 1;
        }
        
        if ($params['perPage'] < 1) {
            $params['perPage'] = 10;
        }
        
        // Xử lý status
        if ($params['status'] !== null && $params['status'] !== '') {
            $params['status'] = (int)$params['status'];
        }
        
        return $params;
    }
    
    /**
     * Xây dựng tiêu chí tìm kiếm cho model
     * 
     * @param array $params Tham số tìm kiếm
     * @return array Tiêu chí tìm kiếm
     */
    public function buildSearchCriteria($params)
    {
        $criteria = [];
        
        if (!empty($params['keyword'])) {
            $criteria['keyword'] = $params['keyword'];
        }
        
        if (isset($params['status']) && $params['status'] !== '') {
            $criteria['status'] = $params['status'];
        }
        
        return $criteria;
    }
    
    /**
     * Xây dựng tùy chọn tìm kiếm cho model
     * 
     * @param array $params Tham số tìm kiếm
     * @return array Tùy chọn tìm kiếm
     */
    public function buildSearchOptions($params)
    {
        return [
            'limit' => $params['perPage'],
            'offset' => ($params['page'] - 1) * $params['perPage'],
            'sort' => $params['sort'],
            'order' => $params['order']
        ];
    }
    
    /**
     * Chuẩn bị dữ liệu cho view
     * 
     * @param array $data Dữ liệu cần chuẩn bị
     * @param object $pager Đối tượng phân trang
     * @param array $params Tham số bổ sung
     * @return array Dữ liệu đã chuẩn bị cho view
     */
    public function prepareViewData($data, $pager, $params = [])
    {
        // Xử lý dữ liệu và thêm relation
        $processedData = $this->processData($data);
        
        return [
            'processedData' => $processedData,
            'pager' => $pager,
            'currentPage' => $params['page'] ?? 1,
            'perPage' => $params['perPage'] ?? 10,
            'total' => $params['total'] ?? 0,
            'sort' => $params['sort'] ?? 'created_at',
            'order' => $params['order'] ?? 'DESC',
            'keyword' => $params['keyword'] ?? '',
            'status' => $params['status'] ?? '',
            'title' => 'Danh sách ' . $this->title,
            'moduleUrl' => $this->moduleUrl,
            'module_name' => $this->module_name,
            'route_url' => $this->route_url,
            'masterScript' => $this->masterScript,
            'breadcrumb' => $this->breadcrumb->render()
        ];
    }
    
    /**
     * Xử lý dữ liệu trước khi hiển thị
     * 
     * @param array $data Dữ liệu cần xử lý
     * @return array Dữ liệu đã xử lý
     */
    public function processData($data)
    {
        if (empty($data)) {
            return [];
        }
        
        foreach ($data as &$item) {
            // Xử lý thời gian tạo
            if (!empty($item->created_at)) {
                try {
                    $item->created_at = $item->created_at instanceof Time ? 
                        $item->created_at : 
                        Time::parse($item->created_at);
                } catch (\Exception $e) {
                    log_message('error', 'Lỗi xử lý thời gian tạo: ' . $e->getMessage());
                    $item->created_at = null;
                }
            }
            
            // Xử lý thời gian cập nhật
            if (!empty($item->updated_at)) {
                try {
                    $item->updated_at = $item->updated_at instanceof Time ? 
                        $item->updated_at : 
                        Time::parse($item->updated_at);
                } catch (\Exception $e) {
                    log_message('error', 'Lỗi xử lý thời gian cập nhật: ' . $e->getMessage());
                    $item->updated_at = null;
                }
            }
            
            // Xử lý thời gian xóa
            if (!empty($item->deleted_at)) {
                try {
                    $item->deleted_at = $item->deleted_at instanceof Time ? 
                        $item->deleted_at : 
                        Time::parse($item->deleted_at);
                } catch (\Exception $e) {
                    log_message('error', 'Lỗi xử lý thời gian xóa: ' . $e->getMessage());
                    $item->deleted_at = null;
                }
            }
            
            // Xử lý trạng thái
            if (isset($item->status)) {
                $item->status_text = $item->status == 1 ? 'Hoạt động' : 'Không hoạt động';
                $item->status_class = $item->status == 1 ? 'status-active' : 'status-inactive';
            }
        }
        
        return $data;
    }
    
    /**
     * Chuẩn bị dữ liệu cho form
     * 
     * @param object $data Dữ liệu khóa học (nếu là cập nhật)
     * @return array Dữ liệu để truyền vào view
     */
    public function prepareFormData($data = null)
    {
        return [
            'data' => $data,
            'module_name' => $this->module_name,
            'title' => $this->title,
            'moduleUrl' => $this->moduleUrl,
            'route_url' => $this->route_url,
            'validation' => \Config\Services::validation(),
            'errors' => session()->getFlashdata('errors') ?? [],
            'breadcrumb' => $this->breadcrumb->render(),
            'masterScript' => $this->masterScript
        ];
    }
    
    /**
     * Xử lý URL trả về, loại bỏ domain nếu cần
     * 
     * @param string|null $returnUrl URL trả về
     * @return string URL đích đã được xử lý
     */
    public function processReturnUrl($returnUrl)
    {
        // Mặc định là URL module
        $redirectUrl = $this->moduleUrl;
        
        if (!empty($returnUrl)) {
            // Giải mã URL
            $decodedUrl = urldecode($returnUrl);
            log_message('debug', 'Return URL sau khi giải mã: ' . $decodedUrl);
            
            // Kiểm tra nếu URL chứa domain, chỉ lấy phần path và query
            if (strpos($decodedUrl, 'http') === 0) {
                $urlParts = parse_url($decodedUrl);
                $path = $urlParts['path'] ?? '';
                $query = isset($urlParts['query']) ? '?' . $urlParts['query'] : '';
                $decodedUrl = $path . $query;
            }
            
            // Xử lý đường dẫn tương đối
            if (strpos($decodedUrl, '/') === 0) {
                $decodedUrl = substr($decodedUrl, 1);
            }
            
            // Cập nhật URL đích
            $redirectUrl = $decodedUrl;
        }
        
        return $redirectUrl;
    }
    
    /**
     * Chuẩn bị tiêu chí tìm kiếm cho việc xuất dữ liệu
     * 
     * @param string $keyword Từ khóa tìm kiếm
     * @param mixed $status Trạng thái
     * @param bool $includeDeleted Có bao gồm dữ liệu đã xóa không
     * @return array Tiêu chí tìm kiếm
     */
    public function prepareExportCriteria($keyword, $status, $includeDeleted = false)
    {
        $criteria = [];
        
        if (!empty($keyword)) {
            $criteria['keyword'] = $keyword;
        }
        
        if (isset($status) && $status !== '') {
            $criteria['status'] = (int)$status;
        }
        
        if ($includeDeleted) {
            $criteria['deleted'] = true;
        }
        
        return $criteria;
    }
    
    /**
     * Chuẩn bị tùy chọn tìm kiếm cho việc xuất dữ liệu
     * 
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp
     * @return array Tùy chọn tìm kiếm
     */
    public function prepareExportOptions($sort, $order)
    {
        return [
            'sort' => $sort,
            'order' => $order,
            'limit' => 1000 // Giới hạn số lượng bản ghi xuất
        ];
    }
    
    /**
     * Tạo file Excel từ dữ liệu
     * 
     * @param array $data Dữ liệu cần xuất
     * @param array $headers Tiêu đề cột
     * @param array $filters Bộ lọc đã áp dụng
     * @param string $filename Tên file (không bao gồm đuôi)
     * @param bool $includeDeleted Có bao gồm cột ngày xóa không
     * @param string $title Tiêu đề tài liệu
     * @param array $primaryKeyField Tên trường khóa chính
     * @param array $displayFields Mảng tương ứng giữa tên cột và tên trường
     */
    public function exportToExcel($data, $headers, $filters, $filename, $includeDeleted = false, $title = '', $primaryKeyField = '', $displayFields = [])
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập style cho header
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        
        // Thiết lập style cho nội dung
        $contentStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];
        
        // Thiết lập style cho filters
        $filterStyle = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F8F9FA'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];
        
        // Thiết lập tiêu đề
        $documentTitle = !empty($title) ? $title : 'DANH SÁCH DỮ LIỆU';
        $sheet->setCellValue('A1', $documentTitle);
        $sheet->mergeCells('A1:' . end($headers) . '1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Thêm ngày xuất
        $sheet->setCellValue('A2', 'Ngày xuất: ' . Time::now()->format('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:' . end($headers) . '2');
        $sheet->getStyle('A2')->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            'font' => ['italic' => true],
        ]);
        
        // Thêm thông tin bộ lọc
        $row = 3;
        if (!empty($filters)) {
            $sheet->setCellValue('A' . $row, 'Thông tin bộ lọc:');
            $sheet->getStyle('A' . $row)->applyFromArray($filterStyle);
            $row++;
            
            foreach ($filters as $key => $value) {
                $sheet->setCellValue('A' . $row, $key . ':');
                $sheet->setCellValue('B' . $row, $value);
                $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray($filterStyle);
                $row++;
            }
            $row++;
        }
        
        // Thêm headers
        $col = 0;
        foreach ($headers as $header => $column) {
            $sheet->setCellValue($column . $row, $header);
            $sheet->getStyle($column . $row)->applyFromArray($headerStyle);
            $col++;
        }
        
        // Thêm dữ liệu
        $row++;
        $idField = !empty($primaryKeyField) ? $primaryKeyField : $this->model->primaryKey;
        
        foreach ($data as $index => $item) {
            $col = 0;
            
            // Thêm STT
            $sheet->setCellValue('A' . $row, $index + 1);
            $col++;
            
            // Thêm các trường dữ liệu khác
            foreach ($displayFields as $header => $field) {
                $column = $headers[$header];
                $value = property_exists($item, $field) ? $item->$field : '';
                
                // Xử lý trường trạng thái
                if ($field === 'status') {
                    $value = ($value == 1) ? 'Hoạt động' : 'Không hoạt động';
                }
                
                // Xử lý trường thời gian
                if (in_array($field, ['created_at', 'updated_at', 'deleted_at']) && !empty($value)) {
                    try {
                        $dateTime = $value instanceof Time ? $value : Time::parse($value);
                        $value = $dateTime->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {
                        log_message('error', 'Lỗi xử lý thời gian trong xuất Excel: ' . $e->getMessage());
                        $value = '';
                    }
                }
                
                $sheet->setCellValue($column . $row, $value);
            }
            
            $sheet->getStyle('A' . $row . ':' . end($headers) . $row)->applyFromArray($contentStyle);
            $row++;
        }
        
        // Thêm tổng số bản ghi
        $sheet->setCellValue('A' . $row, 'Tổng số bản ghi: ' . count($data));
        $sheet->mergeCells('A' . $row . ':' . end($headers) . $row);
        $sheet->getStyle('A' . $row)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
            'font' => ['bold' => true],
            'borders' => [
                'top' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);
        
        // Tự động điều chỉnh độ rộng cột
        foreach (range('A', end($headers)) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Tạo file Excel
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit();
    }
    
    /**
     * Tạo file PDF từ dữ liệu
     * 
     * @param array $data Dữ liệu cần xuất
     * @param array $filters Bộ lọc đã áp dụng
     * @param string $title Tiêu đề tài liệu
     * @param string $filename Tên file (không bao gồm đuôi)
     * @param bool $includeDeleted Có bao gồm cột ngày xóa không
     * @param array $displayFields Mảng tương ứng giữa tên cột và tên trường
     */
    public function exportToPdf($data, $filters, $title, $filename, $includeDeleted = false, $displayFields = [])
    {
        // Chuẩn bị HTML
        $html = '<style>
            body { font-family: DejaVu Sans, sans-serif; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
            th { background-color: #f2f2f2; }
            .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; }
            .subtitle { text-align: right; font-style: italic; margin-bottom: 20px; }
            .filter-title { font-weight: bold; margin-bottom: 10px; }
            .filter-item { margin-bottom: 5px; }
            .total { text-align: right; font-weight: bold; margin-top: 10px; }
        </style>';
        
        // Thêm tiêu đề
        $html .= '<div class="title">' . $title . '</div>';
        $html .= '<div class="subtitle">Ngày xuất: ' . Time::now()->format('d/m/Y H:i:s') . '</div>';
        
        // Thêm thông tin bộ lọc
        if (!empty($filters)) {
            $html .= '<div class="filter-title">Thông tin bộ lọc:</div>';
            foreach ($filters as $key => $value) {
                $html .= '<div class="filter-item">' . $key . ': ' . $value . '</div>';
            }
            $html .= '<br>';
        }
        
        // Bắt đầu bảng
        $html .= '<table>';
        
        // Thêm header
        $html .= '<tr>';
        $html .= '<th>STT</th>';
        
        // Hiển thị các cột theo displayFields
        foreach ($displayFields as $header => $field) {
            $html .= '<th>' . $header . '</th>';
        }
        
        $html .= '</tr>';
        
        // Thêm dữ liệu
        foreach ($data as $index => $item) {
            $html .= '<tr>';
            $html .= '<td>' . ($index + 1) . '</td>';
            
            // Hiển thị các trường theo displayFields
            foreach ($displayFields as $header => $field) {
                $value = property_exists($item, $field) ? $item->$field : '';
                
                // Xử lý trường trạng thái
                if ($field === 'status') {
                    $value = ($value == 1) ? 'Hoạt động' : 'Không hoạt động';
                }
                
                // Xử lý trường thời gian
                if (in_array($field, ['created_at', 'updated_at', 'deleted_at']) && !empty($value)) {
                    try {
                        $dateTime = $value instanceof Time ? $value : Time::parse($value);
                        $value = $dateTime->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {
                        log_message('error', 'Lỗi xử lý thời gian trong xuất PDF: ' . $e->getMessage());
                        $value = '';
                    }
                }
                
                $html .= '<td>' . $value . '</td>';
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        // Thêm tổng số bản ghi
        $html .= '<div class="total">Tổng số bản ghi: ' . count($data) . '</div>';
        
        // Thiết lập options cho DomPDF
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        
        // Khởi tạo DomPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Xuất file PDF
        $dompdf->stream($filename . '.pdf', ['Attachment' => true]);
        exit();
    }
    
    /**
     * Lấy text cho sắp xếp
     * 
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp
     * @param array $sortFields Mảng ánh xạ tên trường với nhãn hiển thị
     * @return string Nhãn hiển thị cho việc sắp xếp
     */
    public function getSortText($sort, $order, $sortFields = [])
    {
        $field = isset($sortFields[$sort]) ? $sortFields[$sort] : $sort;
        return "$field (" . ($order === 'DESC' ? 'Giảm dần' : 'Tăng dần') . ")";
    }
    
    /**
     * Định dạng các bộ lọc để hiển thị
     * 
     * @param array $filters Mảng các bộ lọc
     * @return string HTML hiển thị các bộ lọc
     */
    public function formatFilters($filters)
    {
        if (empty($filters)) {
            return '<div class="alert alert-info">Không có bộ lọc nào được áp dụng</div>';
        }
        
        $html = '<div class="card mb-3"><div class="card-header">Bộ lọc</div><div class="card-body"><ul class="list-group list-group-flush">';
        
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                $html .= '<li class="list-group-item d-flex justify-content-between align-items-center">';
                $html .= '<span><strong>' . $key . ':</strong></span> <span>' . $value . '</span>';
                $html .= '</li>';
            }
        }
        
        $html .= '</ul></div></div>';
        
        return $html;
    }
} 