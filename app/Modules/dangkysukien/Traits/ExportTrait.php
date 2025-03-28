<?php

namespace App\Modules\dangkysukien\Traits;

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
    protected $export_title = 'DANH SÁCH ĐĂNG KÝ SỰ KIỆN';
    protected $search_field = 'ngay_dang_ky';
    protected $search_order = 'DESC';
    protected $header_title = [
        'STT' => 'A',
        'ID' => 'B',
        'Tên sự kiện' => 'C',
        'Họ tên' => 'D',
        'Email' => 'E',
        'Điện thoại' => 'F',
        'Loại người đăng ký' => 'G',
        'Hình thức tham gia' => 'H',
        'Ngày đăng ký' => 'I',
        'Trạng thái' => 'J',
        'Trạng thái tham dự' => 'K',
        'Số phút tham dự' => 'L',
        'Phương thức điểm danh' => 'M',
        'Đã check-in' => 'N',
        'Đã check-out' => 'O',
        'Ngày tạo' => 'P',
        'Ngày cập nhật' => 'Q'
    ];
    protected $header_title_deleted = [
        'Ngày xóa' => 'R'
    ];

    protected $excel_row = [
        'A' => ['method' => null, 'align' => 'center'], // STT
        'B' => ['method' => 'getId', 'align' => 'center'],
        'C' => ['method' => 'getTenSuKien', 'align' => 'left'],
        'D' => ['method' => 'getHoTen', 'align' => 'left'],
        'E' => ['method' => 'getEmail', 'align' => 'left'],
        'F' => ['method' => 'getDienThoai', 'align' => 'left'],
        'G' => ['method' => 'getLoaiNguoiDangKyText', 'align' => 'left'],
        'H' => ['method' => 'getHinhThucThamGiaText', 'align' => 'left'],
        'I' => ['method' => 'getNgayDangKyFormatted', 'align' => 'center'],
        'J' => ['method' => 'getStatusText', 'align' => 'center'],
        'K' => ['method' => 'getAttendanceStatusText', 'align' => 'center'],
        'L' => ['method' => 'getAttendanceTimeFormatted', 'align' => 'center'],
        'M' => ['method' => 'getDiemDanhBangText', 'align' => 'center'],
        'N' => ['method' => 'isDaCheckIn', 'align' => 'center', 'format' => 'boolean'],
        'O' => ['method' => 'isDaCheckOut', 'align' => 'center', 'format' => 'boolean'],
        'P' => ['method' => 'getCreatedAtFormatted', 'align' => 'center'],
        'Q' => ['method' => 'getUpdatedAtFormatted', 'align' => 'center'],
        'R' => ['method' => 'getDeletedAtFormatted', 'align' => 'center']
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
        
        if (isset($status)) {
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
                    if (isset($config['format'])) {
                        switch ($config['format']) {
                            case 'boolean':
                                $value = $value ? 'Có' : 'Không';
                                break;
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
            'filters' => $this->formatFilters($filters, true),
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
     * Format filters để hiển thị trong báo cáo
     */
    protected function formatFilters($filters, $forHTML = false)
    {
        if (empty($filters)) {
            return $forHTML ? '' : [];
        }

        $formatted = [];
        foreach ($filters as $key => $value) {
            if ($forHTML) {
                $formatted[] = "$key: $value";
            } else {
                $formatted[$key] = $value;
            }
        }

        return $forHTML ? implode('<br>', $formatted) : $formatted;
    }

    /**
     * Xử lý xuất dữ liệu ra file Excel hoặc PDF
     */
    protected function exportData($data, $type = 'excel', $criteria = [], $isDeleted = false)
    {
        // Định dạng tiêu đề
        $title = $isDeleted ? 'DANH SÁCH ĐĂNG KÝ SỰ KIỆN ĐÃ XÓA' : 'DANH SÁCH ĐĂNG KÝ SỰ KIỆN';
        
        // Tạo tên file dựa trên loại và thời gian
        $filename = 'dang_ky_su_kien_' . ($isDeleted ? 'deleted_' : '') . date('YmdHis');
        
        // Định dạng bộ lọc cho báo cáo
        if ($type === 'excel') {
            $filters = $this->formatFilters($criteria, false);
            $headers = $this->prepareExcelHeaders($isDeleted);
            return $this->createExcelFile($data, $headers, $filters, $filename, $isDeleted);
        } elseif ($type === 'pdf') {
            $filters = $this->formatFilters($criteria, true);
            return $this->createPdfFile($data, $filters, $title, $filename, $isDeleted);
        }
        
        $this->alert->set('danger', 'Loại xuất dữ liệu không hợp lệ', true);
        return redirect()->to($this->moduleUrl);
    }
} 