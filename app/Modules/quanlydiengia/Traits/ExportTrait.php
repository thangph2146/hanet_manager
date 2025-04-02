<?php

namespace App\Modules\quanlydiengia\Traits;

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
    protected $export_title = 'DANH SÁCH DIỄN GIẢ';
    protected $search_field = 'ten_dien_gia';
    protected $search_order = 'DESC';
    protected $header_title =  [
        'STT' => 'A',
        'ID' => 'B',
        'Tên diễn giả' => 'C',
        'Chức danh' => 'D',
        'Tổ chức' => 'E',
        'Email' => 'F',
        'Điện thoại' => 'G',
        'Website' => 'H',
        'Giới thiệu' => 'I',
        'Chuyên môn' => 'J',
        'Thành tựu' => 'K',
        'Mạng xã hội' => 'L',
        'Số sự kiện tham gia' => 'M',
        'Trạng thái' => 'N',
        'Ngày tạo' => 'O',
        'Ngày cập nhật' => 'P'
    ];
    protected $header_title_deleted =  [
        'Ngày xóa' => 'Q'
    ];

    protected $excel_row = [
        'A' => ['method' => null, 'align' => 'center'], // STT
        'B' => ['method' => 'getId', 'align' => 'center'],
        'C' => ['method' => 'getTenDienGia', 'align' => 'left'],
        'D' => ['method' => 'getChucDanh', 'align' => 'left'],
        'E' => ['method' => 'getToChuc', 'align' => 'left'],
        'F' => ['method' => 'getEmail', 'align' => 'left'],
        'G' => ['method' => 'getDienThoai', 'align' => 'left'],
        'H' => ['method' => 'getWebsite', 'align' => 'left'],
        'I' => ['method' => 'getGioiThieu', 'align' => 'left', 'wrap' => true],
        'J' => ['method' => 'getChuyenMon', 'align' => 'left', 'wrap' => true],
        'K' => ['method' => 'getThanhTuu', 'align' => 'left', 'wrap' => true],
        'L' => ['method' => 'getMangXaHoi', 'align' => 'left', 'wrap' => true, 'json' => true],
        'M' => ['method' => 'getSoSuKienThamGia', 'align' => 'center'],
        'N' => ['method' => 'getStatus', 'align' => 'center', 'format' => 'status'],
        'O' => ['method' => 'getCreatedAtFormatted', 'align' => 'center'],
        'P' => ['method' => 'getUpdatedAtFormatted', 'align' => 'center'],
        'Q' => ['method' => 'getDeletedAtFormatted', 'align' => 'center']
    ];

    protected function getLastColumn()
    {
        $columns = array_keys($this->excel_row);
        return end($columns);
    }

    /**
     * Chuẩn bị tiêu chí tìm kiếm
     */
    protected function prepareSearchCriteria($keyword, $status, $includeDeleted = false)
    {
        $criteria = [];
        
        if (!empty($keyword)) {
            $criteria['keyword'] = $keyword;
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
        // Nếu không có sort được chỉ định, mặc định là sắp xếp theo thứ tự tăng dần
        if (empty($sort)) {
            $sort = $this->search_field;
            $order = $this->search_order;
        }

        return [
            'sort' => $sort,
            'order' => $order,
            'limit' => 1000 // Giới hạn số lượng bản ghi xuất
        ];
    }

    /**
     * Lấy dữ liệu để xuất
     */
    protected function getExportData($criteria, $options)
    {
        // Lấy dữ liệu từ model
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
        $col = 1;
        foreach ($headers as $header => $column) {
            $sheet->setCellValue($column . $row, $header);
            $sheet->getStyle($column . $row)->applyFromArray($headerStyle);
            $col++;
        }

        // Thêm dữ liệu
        $row++;
        foreach ($data as $index => $item) {
            foreach ($this->excel_row as $col => $config) {
                if ($col === 'A') {
                    $value = $index + 1;
                } else {
                    $method = $config['method'];
                    $value = $method ? $item->$method() : '';
                    
                    // Xử lý định dạng đặc biệt
                    if (isset($config['json']) && $config['json'] && is_array($value)) {
                        // Thay đổi format hiển thị mạng xã hội để dễ đọc hơn
                        $formattedValue = '';
                        foreach ($value as $platform => $url) {
                            $platformName = ucfirst($platform);
                            $formattedValue .= "{$platformName}: {$url}\n";
                        }
                        $value = $formattedValue;
                    } elseif (isset($config['format'])) {
                        switch ($config['format']) {
                            case 'status':
                                $value = $value ? 'Hoạt động' : 'Không hoạt động';
                                break;
                        }
                    }
                }
                
                $sheet->setCellValue($col . $row, $value);
                
                // Áp dụng căn lề
                if (isset($config['align'])) {
                    $sheet->getStyle($col . $row)->getAlignment()
                          ->setHorizontal($config['align'] === 'center' ? 
                              Alignment::HORIZONTAL_CENTER : Alignment::HORIZONTAL_LEFT);
                }
                
                // Áp dụng wrap text
                if (isset($config['wrap']) && $config['wrap']) {
                    $sheet->getStyle($col . $row)->getAlignment()->setWrapText(true);
                }
            }
            
            // Áp dụng style cho hàng
            $lastColumn = $this->getLastColumn();
            $sheet->getStyle('A' . $row . ':' . $lastColumn . $row)
                  ->applyFromArray($contentStyle);
            
            $row++;
        }

        // Thêm tổng số bản ghi
        $sheet->setCellValue('A' . $row, 'Tổng số bản ghi: ' . count($data));
        $lastColumn = $this->getLastColumn();
        $sheet->mergeCells('A' . $row . ':' . $lastColumn . $row);
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
        foreach (range('A', $lastColumn) as $col) {
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
     * Tạo file PDF
     */
    protected function createPdfFile($data, $filters, $title, $filename, $includeDeleted = false)
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'title' => $title,
            'date' => Time::now()->format('d/m/Y'),
            'filters' => $this->formatFilters($filters),
            'data' => $data,
            'includeDeletedAt' => $includeDeleted,
            'total_records' => count($data)
        ];
        
        // Render view
        $html = view('App\Modules\\' . $this->module_name . '\Views\export_pdf', $viewData);
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Tải file PDF
        $dompdf->stream($filename . '.pdf', ['Attachment' => true]);
        exit();
    }

    /**
     * Format filters để hiển thị trong PDF
     */
    protected function formatFilters($filters)
    {
        if (empty($filters)) {
            return '';
        }

        $formatted = [];
        foreach ($filters as $key => $value) {
            $formatted[] = "$key: $value";
        }

        return implode('<br>', $formatted);
    }
} 