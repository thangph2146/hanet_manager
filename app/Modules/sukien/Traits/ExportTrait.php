<?php

namespace App\Modules\sukien\Traits;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Dompdf\Dompdf;
use Dompdf\Options;
use CodeIgniter\I18n\Time;

trait ExportTrait
{
    protected $export_title = 'DANH SÁCH SỰ KIỆN';
    protected $search_field = 'thoi_gian_bat_dau';
    protected $search_order = 'DESC';
    protected $header_title =  [
        'STT' => 'A',
        'ID' => 'B',
        'Tên sự kiện' => 'C',
        'Mô tả' => 'D',
        'Thời gian bắt đầu' => 'E',
        'Thời gian kết thúc' => 'F',
        'Địa điểm' => 'G',
        'Địa chỉ cụ thể' => 'H',
        'Loại sự kiện' => 'I',
        'Tổng đăng ký' => 'J',
        'Tổng check-in' => 'K',
        'Tổng check-out' => 'L',
        'Hình thức' => 'M',
        'Trạng thái' => 'N',
        'Lượt xem' => 'O',
        'Ngày tạo' => 'P',
        'Ngày cập nhật' => 'Q'
    ];
    protected $header_title_deleted =  [
        'Ngày xóa' => 'R'
    ];

    protected $excel_row = [
        'A' => ['method' => null, 'align' => 'center'], // STT
        'B' => ['method' => 'getId', 'align' => 'center'],
        'C' => ['method' => 'getTenSuKien', 'align' => 'left'],
        'D' => ['method' => 'getMoTa', 'align' => 'left', 'wrap' => true],
        'E' => ['method' => 'getThoiGianBatDauFormatted', 'align' => 'center'],
        'F' => ['method' => 'getThoiGianKetThucFormatted', 'align' => 'center'],
        'G' => ['method' => 'getDiaDiem', 'align' => 'left'],
        'H' => ['method' => 'getDiaChiCuThe', 'align' => 'left'],
        'I' => ['method' => 'getLoaiSuKienId', 'align' => 'center'],
        'J' => ['method' => 'getTongDangKy', 'align' => 'center'],
        'K' => ['method' => 'getTongCheckIn', 'align' => 'center'],
        'L' => ['method' => 'getTongCheckOut', 'align' => 'center'],
        'M' => ['method' => 'getHinhThucText', 'align' => 'center'],
        'N' => ['method' => 'getStatusText', 'align' => 'center'],
        'O' => ['method' => 'getSoLuotXem', 'align' => 'center'],
        'P' => ['method' => 'getCreatedAtFormatted', 'align' => 'center'],
        'Q' => ['method' => 'getUpdatedAtFormatted', 'align' => 'center']
    ];
    
    protected $excel_row_deleted = [
        'R' => ['method' => 'getDeletedAtFormatted', 'align' => 'center'],
    ];

    /**
     * Chuẩn bị tiêu chí tìm kiếm
     */
    protected function prepareSearchCriteria($keyword, $status, $includeDeleted = false)
    {
        $criteria = [];
        
        if (!empty($keyword)) {
            $criteria['keyword'] = $keyword;
        }
        
        if (isset($status) && $status !== '') {
            $criteria['status'] = $status;
        }
        
        if ($includeDeleted) {
            $criteria['deleted'] = true;
        }
        
        return $criteria;
    }

    /**
     * Chuẩn bị tùy chọn tìm kiếm
     */
    protected function prepareSearchOptions($sort, $order)
    {
        if (empty($sort)) {
            $sort = $this->search_field;
            $order = $this->search_order;
        }

        return [
            'sort' => $sort,
            'order' => $order,
            'limit' => 1000
        ];
    }

    /**
     * Lấy dữ liệu để xuất
     */
    protected function getExportData($criteria, $options)
    {
        $data = isset($criteria['deleted']) && $criteria['deleted'] 
            ? $this->model->searchDeleted($criteria, $options)
            : $this->model->search($criteria, $options);

        return $data;
    }

    /**
     * Chuẩn bị headers cho Excel
     */
    protected function prepareExcelHeaders($includeDeleted = false)
    {
        $headers = $this->header_title;

        if ($includeDeleted) {
            $headers = array_merge($headers, $this->header_title_deleted);
        }

        return $headers;
    }

    /**
     * Tạo file Excel
     */
    protected function createExcelFile($data, $headers, $filters, $filename, $includeDeleted = false)
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

        // Thêm tiêu đề
        $sheet->setCellValue('A1', $this->export_title);
        $lastHeader = end($headers);
        $sheet->mergeCells('A1:' . $lastHeader . '1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        // Thêm ngày xuất
        $sheet->setCellValue('A2', 'Ngày xuất: ' . Time::now()->format('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:' . $lastHeader . '2');

        // Thêm bộ lọc
        if (!empty($filters)) {
            $filterText = $this->formatFilters($filters);
            $sheet->setCellValue('A3', 'Bộ lọc: ' . $filterText);
            $sheet->mergeCells('A3:' . $lastHeader . '3');
            $sheet->getStyle('A3')->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ]);
        }

        // Thêm header cho bảng
        $row = !empty($filters) ? 5 : 4;
        $col = 'A';
        
        foreach ($headers as $header => $column) {
            $sheet->setCellValue($column . $row, $header);
            $col = $column;
        }
        
        // Áp dụng style cho header
        $sheet->getStyle('A' . $row . ':' . $col . $row)->applyFromArray($headerStyle);
        
        // Thêm dữ liệu
        $row++;
        $startRow = $row;
        
        $excelRows = $this->excel_row;
        if ($includeDeleted) {
            $excelRows = array_merge($excelRows, $this->excel_row_deleted);
        }
        
        foreach ($data as $index => $item) {
            $col = 'A';
            
            // STT
            $sheet->setCellValue($col . $row, $index + 1);
            
            // Điền dữ liệu theo cấu hình
            foreach ($excelRows as $column => $config) {
                if ($column === 'A') {
                    // Đã xử lý STT ở trên
                    continue;
                }
                
                $value = '';
                
                if (!empty($config['method'])) {
                    $method = $config['method'];
                    
                    if (method_exists($item, $method)) {
                        $value = $item->$method();
                        
                        // Định dạng dữ liệu nếu cần
                        if (isset($config['format'])) {
                            switch ($config['format']) {
                                case 'boolean':
                                    $value = $value ? 'Có' : 'Không';
                                    break;
                                case 'date':
                                    if ($value instanceof Time) {
                                        $value = $value->format('d/m/Y');
                                    }
                                    break;
                                case 'datetime':
                                    if ($value instanceof Time) {
                                        $value = $value->format('d/m/Y H:i:s');
                                    }
                                    break;
                                case 'status':
                                    $value = $value ? 'Hoạt động' : 'Không hoạt động';
                                    break;
                            }
                        } else {
                            // Xử lý mặc định cho các loại dữ liệu phổ biến
                            if ($value instanceof Time) {
                                $value = $value->format('d/m/Y H:i:s');
                            } elseif (is_bool($value)) {
                                $value = $value ? 'Có' : 'Không';
                            }
                        }
                    }
                }
                
                $sheet->setCellValue($column . $row, $value);
                
                // Thiết lập căn chỉnh cho cột
                if (isset($config['align'])) {
                    $alignmentStyle = [];
                    
                    switch ($config['align']) {
                        case 'left':
                            $alignmentStyle['horizontal'] = Alignment::HORIZONTAL_LEFT;
                            break;
                        case 'center':
                            $alignmentStyle['horizontal'] = Alignment::HORIZONTAL_CENTER;
                            break;
                        case 'right':
                            $alignmentStyle['horizontal'] = Alignment::HORIZONTAL_RIGHT;
                            break;
                    }
                    
                    if (!empty($alignmentStyle)) {
                        $sheet->getStyle($column . $row)->getAlignment()->applyFromArray($alignmentStyle);
                    }
                }
                
                // Thiết lập wrap text nếu cần
                if (isset($config['wrap']) && $config['wrap']) {
                    $sheet->getStyle($column . $row)->getAlignment()->setWrapText(true);
                }
            }
            
            $row++;
        }
        
        // Áp dụng style cho nội dung
        if ($row > $startRow) {
            $sheet->getStyle('A' . $startRow . ':' . $col . ($row - 1))->applyFromArray($contentStyle);
        }
        
        // Tự động điều chỉnh độ rộng cột
        foreach (range('A', $col) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Lưu file Excel
        $writer = new Xlsx($spreadsheet);
        $filePath = ROOTPATH . 'writable/uploads/' . $filename;
        $writer->save($filePath);
        
        return $filePath;
    }
    
    /**
     * Tạo file PDF
     */
    protected function createPdfFile($data, $filters, $title, $filename, $includeDeleted = false)
    {
        // Tạo tùy chọn cho Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        
        // Khởi tạo Dompdf
        $dompdf = new Dompdf($options);
        
        // Chuẩn bị header
        $headers = $this->header_title;
        if ($includeDeleted) {
            $headers = array_merge($headers, $this->header_title_deleted);
        }
        
        // Xây dựng HTML
        $html = '<style>
            body { font-family: DejaVu Sans, sans-serif; }
            table { width: 100%; border-collapse: collapse; }
            th, td { border: 1px solid #ccc; padding: 4px; }
            th { background-color: #4472C4; color: white; }
            h1 { text-align: center; }
            .filters { margin-bottom: 15px; }
            .even { background-color: #f8f9fa; }
        </style>';
        
        $html .= '<h1>' . $title . '</h1>';
        $html .= '<div class="filters"><strong>Ngày xuất:</strong> ' . Time::now()->format('d/m/Y H:i:s') . '</div>';
        
        if (!empty($filters)) {
            $html .= '<div class="filters"><strong>Bộ lọc:</strong> ' . $this->formatFilters($filters, true) . '</div>';
        }
        
        $html .= '<table>';
        $html .= '<tr>';
        $html .= '<th>STT</th>';
        
        foreach ($headers as $header => $column) {
            $html .= '<th>' . $header . '</th>';
        }
        
        $html .= '</tr>';
        
        // Thêm dữ liệu
        $excelRows = $this->excel_row;
        if ($includeDeleted) {
            $excelRows = array_merge($excelRows, $this->excel_row_deleted);
        }
        
        foreach ($data as $index => $item) {
            $rowClass = $index % 2 === 0 ? 'even' : '';
            $html .= '<tr class="' . $rowClass . '">';
            
            // STT
            $html .= '<td style="text-align: center;">' . ($index + 1) . '</td>';
            
            // Thêm dữ liệu theo cấu hình
            foreach ($excelRows as $column => $config) {
                if ($column === 'A') continue; // Đã xử lý STT
                
                $value = '';
                $align = isset($config['align']) ? 'text-align: ' . $config['align'] . ';' : '';
                
                if (!empty($config['method'])) {
                    $method = $config['method'];
                    
                    if (method_exists($item, $method)) {
                        $value = $item->$method();
                        
                        // Định dạng dữ liệu
                        if (isset($config['format'])) {
                            switch ($config['format']) {
                                case 'boolean':
                                    $value = $value ? 'Có' : 'Không';
                                    break;
                                case 'date':
                                    if ($value instanceof Time) {
                                        $value = $value->format('d/m/Y');
                                    }
                                    break;
                                case 'datetime':
                                    if ($value instanceof Time) {
                                        $value = $value->format('d/m/Y H:i:s');
                                    }
                                    break;
                                case 'status':
                                    $value = $value ? 'Hoạt động' : 'Không hoạt động';
                                    break;
                            }
                        } else {
                            // Xử lý mặc định
                            if ($value instanceof Time) {
                                $value = $value->format('d/m/Y H:i:s');
                            } elseif (is_bool($value)) {
                                $value = $value ? 'Có' : 'Không';
                            }
                        }
                    }
                }
                
                $html .= '<td style="' . $align . '">' . ($value ?? '') . '</td>';
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        
        // Tải nội dung HTML vào Dompdf
        $dompdf->loadHtml($html);
        
        // Thiết lập kích thước giấy
        $dompdf->setPaper('A4', 'landscape');
        
        // Render PDF
        $dompdf->render();
        
        // Lưu file PDF
        $output = $dompdf->output();
        $filePath = ROOTPATH . 'writable/uploads/' . $filename;
        file_put_contents($filePath, $output);
        
        return $filePath;
    }
    
    /**
     * Định dạng các bộ lọc để hiển thị
     */
    protected function formatFilters($filters, $forHTML = false)
    {
        $formattedFilters = [];
        
        foreach ($filters as $key => $value) {
            if ($key === 'keyword' && !empty($value)) {
                $formattedFilters[] = 'Từ khóa: ' . $value;
            } elseif ($key === 'status' && $value !== '') {
                $statusText = $value ? 'Hoạt động' : 'Không hoạt động';
                $formattedFilters[] = 'Trạng thái: ' . $statusText;
            } elseif ($key === 'loai_su_kien_id' && !empty($value)) {
                $formattedFilters[] = 'Loại sự kiện: ' . $value;
            } elseif ($key === 'hinh_thuc' && !empty($value)) {
                $hinhThucText = '';
                switch ($value) {
                    case 'offline':
                        $hinhThucText = 'Trực tiếp';
                        break;
                    case 'online':
                        $hinhThucText = 'Trực tuyến';
                        break;
                    case 'hybrid':
                        $hinhThucText = 'Kết hợp';
                        break;
                    default:
                        $hinhThucText = $value;
                }
                $formattedFilters[] = 'Hình thức: ' . $hinhThucText;
            }
        }
        
        return implode($forHTML ? ' | ' : ', ', $formattedFilters);
    }
    
    /**
     * Xử lý xuất dữ liệu
     */
    protected function exportData($data, $type = 'excel', $criteria = [], $isDeleted = false)
    {
        // Chuẩn bị title
        $title = $isDeleted ? $this->export_title . ' ĐÃ XÓA' : $this->export_title;
        
        // Chuẩn bị filters để hiển thị
        $filters = [];
        if (!empty($criteria['keyword'])) {
            $filters['keyword'] = $criteria['keyword'];
        }
        if (isset($criteria['status']) && $criteria['status'] !== '') {
            $filters['status'] = $criteria['status'];
        }
        if (!empty($criteria['loai_su_kien_id'])) {
            $filters['loai_su_kien_id'] = $criteria['loai_su_kien_id'];
        }
        if (!empty($criteria['hinh_thuc'])) {
            $filters['hinh_thuc'] = $criteria['hinh_thuc'];
        }
        
        // Tạo tên file dựa trên thời gian
        $timestamp = date('YmdHis');
        $filename = strtolower(str_replace(' ', '_', $title)) . '_' . $timestamp . ($type === 'excel' ? '.xlsx' : '.pdf');
        
        // Chuẩn bị headers
        $headers = $this->prepareExcelHeaders($isDeleted);
        
        // Tạo file dựa trên loại
        if ($type === 'excel') {
            $filePath = $this->createExcelFile($data, $headers, $filters, $filename, $isDeleted);
        } else {
            $filePath = $this->createPdfFile($data, $filters, $title, $filename, $isDeleted);
        }
        
        // Tải file
        return $this->downloadFile($filePath, $filename, $type);
    }
    
    /**
     * Tải file
     */
    protected function downloadFile($filePath, $filename, $type = 'excel')
    {
        if (file_exists($filePath)) {
            $mimeType = $type === 'excel' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'application/pdf';
            
            header('Content-Type: ' . $mimeType);
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Content-Length: ' . filesize($filePath));
            
            readfile($filePath);
            exit;
        }
        
        // Nếu file không tồn tại, chuyển hướng về trang chính
        return redirect()->to($this->moduleUrl);
    }
} 