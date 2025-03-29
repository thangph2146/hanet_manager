<?php

namespace App\Modules\checkinsukien\Traits;

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
    protected $export_title = 'DANH SÁCH CHECK IN SỰ KIỆN';
    protected $search_field = 'thoi_gian_check_in';
    protected $search_order = 'DESC';
    protected $suKienList = [];
    protected $dienGiaList = [];
    protected $header_title = [
        'STT' => 'A',
        'ID' => 'B',
        'Tên sự kiện' => 'C',
        'Họ tên' => 'D',
        'Email' => 'E',
        'Thời gian check-in' => 'F',
        'Loại check-in' => 'G',
        'Hình thức tham gia' => 'H',
        'Điểm khớp khuôn mặt' => 'I',
        'Xác thực khuôn mặt' => 'J',
        'Trạng thái' => 'K',
        'Địa chỉ IP' => 'L',
        'Thông tin thiết bị' => 'M',
        'Ngày tạo' => 'N',
        'Ngày cập nhật' => 'O'
    ];
    protected $header_title_deleted = [
        'Ngày xóa' => 'P'
    ];

    protected $excel_row = [
        'A' => ['method' => null, 'align' => 'center'], // STT
        'B' => ['method' => 'getId', 'align' => 'center'],
        'C' => ['method' => 'getTenSuKien', 'align' => 'left'],
        'D' => ['method' => 'getHoTen', 'align' => 'left'],
        'E' => ['method' => 'getEmail', 'align' => 'left'],
        'F' => ['method' => 'getThoiGianCheckInFormatted', 'align' => 'center'],
        'G' => ['method' => 'getCheckinTypeText', 'align' => 'center'],
        'H' => ['method' => 'getHinhThucThamGiaText', 'align' => 'center'],
        'I' => ['method' => 'getFaceMatchScorePercent', 'align' => 'center'],
        'J' => ['method' => 'isFaceVerified', 'align' => 'center', 'format' => 'boolean'],
        'K' => ['method' => 'getStatusText', 'align' => 'center'],
        'L' => ['method' => 'getIpAddress', 'align' => 'left'],
        'M' => ['method' => 'getFormattedDeviceInfo', 'align' => 'left'],
        'N' => ['method' => 'getCreatedAt', 'align' => 'center'],
        'O' => ['method' => 'getUpdatedAt', 'align' => 'center'],
    ];

    protected $excel_row_deleted = [
        'P' => ['method' => 'getDeletedAt', 'align' => 'center'],
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
        $lastHeader = end($headers);
        $sheet->mergeCells('A1:' . $lastHeader . '1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Thêm ngày xuất
        $sheet->setCellValue('A2', 'Ngày xuất: ' . Time::now()->format('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:' . $lastHeader . '2');
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
            // Kết hợp excel_row và excel_row_deleted nếu includeDeleted = true
            $excel_rows = $includeDeleted ? array_merge($this->excel_row, $this->excel_row_deleted) : $this->excel_row;
            
            foreach ($excel_rows as $col => $config) {
                if ($col === 'A') {
                    $value = $index + 1;
                } else {
                    $method = $config['method'];
                    if ($method === 'getTenSuKien') {
                        // Kiểm tra nếu thuộc tính ten_su_kien đã được join trong query
                        if (isset($item->attributes['ten_su_kien'])) {
                            $value = $item->attributes['ten_su_kien'];
                        } else {
                            // Tìm tên sự kiện từ danh sách suKienList
                            $suKienName = '';
                            foreach ($this->suKienList as $suKien) {
                                if ($suKien->su_kien_id == $item->getSuKienId()) {
                                    $suKienName = $suKien->ten_su_kien;
                                    break;
                                }
                            }
                            $value = $suKienName;
                        }
                    } else {
                        $value = $method ? $item->$method() : '';
                        
                        // Xử lý định dạng ngày tháng
                        if (in_array($method, ['getCreatedAt', 'getUpdatedAt', 'getDeletedAt'])) {
                            if ($value instanceof Time) {
                                $value = $value->format('d/m/Y H:i:s');
                            } elseif ($value) {
                                $value = date('d/m/Y H:i:s', strtotime($value));
                            } else {
                                $value = '';
                            }
                        }
                    }
                    
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
            $sheet->getStyle('A' . $row . ':' . $lastHeader . $row)
                  ->applyFromArray($contentStyle);
            
            $row++;
        }

        // Thêm tổng số bản ghi
        $sheet->setCellValue('A' . $row, 'Tổng số bản ghi: ' . count($data));
        $sheet->mergeCells('A' . $row . ':' . $lastHeader . $row);
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
        foreach (range('A', $lastHeader) as $col) {
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
        $title = $isDeleted ? 'DANH SÁCH CHECK IN SỰ KIỆN ĐÃ XÓA' : 'DANH SÁCH CHECK IN SỰ KIỆN';
        
        // Tạo tên file dựa trên loại và thời gian
        $filename = 'checkin_su_kien_' . ($isDeleted ? 'deleted_' : '') . date('YmdHis');
        
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

    /**
     * Thiết lập danh sách sự kiện
     */
    public function setSuKienList($list)
    {
        $this->suKienList = $list;
        return $this;
    }
    
    /**
     * Thiết lập danh sách diễn giả
     */
    public function setDienGiaList($list)
    {
        $this->dienGiaList = $list;
        return $this;
    }
} 