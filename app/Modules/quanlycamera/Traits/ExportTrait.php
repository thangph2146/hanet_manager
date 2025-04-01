<?php

namespace App\Modules\quanlycamera\Traits;

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
            $criteria['status'] = (int)$status;
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
        // Nếu không có sort được chỉ định, mặc định là sắp xếp theo ID
        if (empty($sort)) {
            $sort = 'camera_id';
            $order = 'ASC';
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
        $headers = [
            'STT' => 'A',
            'ID' => 'B',
            'Tên Camera' => 'C',
            'Mã Camera' => 'D',
            'Trạng thái' => 'E',
            'Ngày tạo' => 'F',
            'Ngày cập nhật' => 'G',
        ];

        if ($includeDeleted) {
            $headers['Ngày xóa'] = 'H';
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
        $sheet->setCellValue('A1', 'DANH SÁCH CAMERA');
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
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item->camera_id);
            $sheet->setCellValue('C' . $row, $item->ten_camera);
            $sheet->setCellValue('D' . $row, $item->ma_camera);
            $sheet->setCellValue('E' . $row, $item->status == 1 ? 'Hoạt động' : 'Không hoạt động');
            $sheet->setCellValue('F' . $row, $item->created_at);
            $sheet->setCellValue('G' . $row, $item->updated_at);

            // Xử lý thời gian xóa
            if ($includeDeleted && isset($item->deleted_at)) {
                if (!empty($item->deleted_at)) {
                    try {
                        $deletedAt = $item->deleted_at instanceof Time ? 
                            $item->deleted_at : 
                            Time::parse($item->deleted_at);
                        $sheet->setCellValue('H' . $row, $deletedAt->format('d/m/Y'));
                    } catch (\Exception $e) {
                        $sheet->setCellValue('H' . $row, '');
                    }
                } else {
                    $sheet->setCellValue('H' . $row, '');
                }
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
     * Tạo file PDF
     */
    protected function createPdfFile($data, $filters, $title, $filename, $includeDeleted = false)
    {
        // Cấu hình PDF
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        
        // Chuẩn bị CSS
        $css = '
            <style>
                body { 
                    font-family: DejaVu Sans, sans-serif; 
                    font-size: 12px;
                    margin: 0;
                    padding: 20px;
                }
                h2 { 
                    text-align: center; 
                    margin-bottom: 5px;
                }
                .subtitle {
                    text-align: center;
                    font-style: italic;
                    margin-bottom: 20px;
                }
                .filters {
                    margin-bottom: 15px;
                    padding: 10px;
                    background-color: #f8f9fa;
                    border-radius: 5px;
                }
                .filter-title {
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .filter-item {
                    margin-bottom: 3px;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-bottom: 15px;
                }
                th { 
                    background-color: #4472C4; 
                    color: white;
                    text-align: center;
                    padding: 8px;
                    font-weight: bold;
                    border: 1px solid #000;
                }
                td { 
                    border: 1px solid #000; 
                    padding: 6px; 
                }
                .text-center {
                    text-align: center;
                }
                .text-right {
                    text-align: right;
                }
                .footer {
                    text-align: right;
                    font-weight: bold;
                    margin-top: 10px;
                }
            </style>
        ';
        
        // Chuẩn bị HTML
        $html = '<!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                ' . $css . '
            </head>
            <body>';
            
        // Thêm tiêu đề
        $html .= '<h2>' . strtoupper($title) . '</h2>';
        $html .= '<div class="subtitle">Ngày xuất: ' . Time::now()->format('d/m/Y H:i:s') . '</div>';
        
        // Thêm thông tin bộ lọc
        if (!empty($filters)) {
            $html .= '<div class="filters">';
            $html .= '<div class="filter-title">Thông tin bộ lọc:</div>';
            foreach ($filters as $key => $value) {
                $html .= '<div class="filter-item"><strong>' . $key . ':</strong> ' . $value . '</div>';
            }
            $html .= '</div>';
        }
        
        // Tạo bảng dữ liệu
        $html .= '<table>';
        $html .= '<thead>
            <tr>
                <th>STT</th>
                <th>ID</th>
                <th>Tên Camera</th>
                <th>Mã Camera</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>';
                
        if ($includeDeleted) {
            $html .= '<th>Ngày xóa</th>';
        }
        
        $html .= '</tr></thead><tbody>';
        
        // Thêm dữ liệu
        foreach ($data as $index => $item) {
            $html .= '<tr>';
            $html .= '<td class="text-center">' . ($index + 1) . '</td>';
            $html .= '<td class="text-center">' . $item->camera_id . '</td>';
            $html .= '<td>' . $item->ten_camera . '</td>';
            $html .= '<td>' . $item->ma_camera . '</td>';
            $html .= '<td class="text-center">' . ($item->status == 1 ? 'Hoạt động' : 'Không hoạt động') . '</td>';
            $html .= '<td class="text-center">' . ($item->created_at ? ($item->created_at instanceof Time ? $item->created_at->format('d/m/Y') : Time::parse($item->created_at)->format('d/m/Y')) : '') . '</td>';
            $html .= '<td class="text-center">' . ($item->updated_at ? ($item->updated_at instanceof Time ? $item->updated_at->format('d/m/Y') : Time::parse($item->updated_at)->format('d/m/Y')) : '') . '</td>';
            
            if ($includeDeleted) {
                if (!empty($item->deleted_at)) {
                    try {
                        $deletedAt = $item->deleted_at instanceof Time ? 
                            $item->deleted_at : 
                            Time::parse($item->deleted_at);
                        $html .= '<td class="text-center">' . $deletedAt->format('d/m/Y') . '</td>';
                    } catch (\Exception $e) {
                        $html .= '<td></td>';
                    }
                } else {
                    $html .= '<td></td>';
                }
            }
            
            $html .= '</tr>';
        }
        
        $html .= '</tbody></table>';
        
        // Thêm tổng số bản ghi
        $html .= '<div class="footer">Tổng số bản ghi: ' . count($data) . '</div>';
        
        $html .= '</body></html>';
        
        // Tạo PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Xuất file
        $dompdf->stream($filename . '.pdf', ['Attachment' => true]);
        exit();
    }

    /**
     * Format thông tin bộ lọc để hiển thị
     */
    protected function formatFilters($filters)
    {
        $formattedFilters = [];
        
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                switch ($key) {
                    case 'keyword':
                        if (!empty($value)) {
                            $formattedFilters['Từ khóa'] = $value;
                        }
                        break;
                    case 'status':
                        if ($value !== '') {
                            $formattedFilters['Trạng thái'] = $value == 1 ? 'Hoạt động' : 'Không hoạt động';
                        }
                        break;
                    case 'deleted':
                        if ($value === true) {
                            $formattedFilters['Tình trạng'] = 'Đã xóa';
                        }
                        break;
                    default:
                        if ($value !== '') {
                            $formattedFilters[ucfirst($key)] = $value;
                        }
                        break;
                }
            }
        }
        
        return $formattedFilters;
    }
} 