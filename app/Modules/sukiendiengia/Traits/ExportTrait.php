<?php

namespace App\Modules\sukiendiengia\Traits;

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
    protected function prepareSearchCriteria($keyword, $su_kien_id, $dien_gia_id, $includeDeleted = false)
    {
        $criteria = [];
        
        if (!empty($keyword)) {
            $criteria['keyword'] = $keyword;
        }
        
        if (!empty($su_kien_id)) {
            $criteria['su_kien_id'] = $su_kien_id;
        }
        
        if (!empty($dien_gia_id)) {
            $criteria['dien_gia_id'] = $dien_gia_id;
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
        // Nếu không có sort được chỉ định, mặc định là sắp xếp theo thứ tự
        if (empty($sort)) {
            $sort = 'thu_tu';
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
        if (isset($criteria['deleted']) && $criteria['deleted']) {
            return $this->model->searchDeleted($criteria, $options);
        }
        return $this->model->search($criteria, $options);
    }

    /**
     * Chuẩn bị headers cho Excel
     */
    protected function prepareExcelHeaders($includeDeleted = false)
    {
        $headers = [
            'STT' => 'A',
            'Sự kiện ID' => 'B',
            'Diễn giả ID' => 'C',
            'Thứ tự' => 'D',
            'Ngày tạo' => 'E',
            'Ngày cập nhật' => 'F'
        ];

        if ($includeDeleted) {
            $headers['Ngày xóa'] = 'G';
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
        $sheet->setCellValue('A1', 'DANH SÁCH SỰ KIỆN DIỄN GIẢ');
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
            $sheet->setCellValue('B' . $row, $item->su_kien_id);
            $sheet->setCellValue('C' . $row, $item->dien_gia_id);
            $sheet->setCellValue('D' . $row, $item->thu_tu ?? 0);
            
            // Xử lý ngày tạo
            if (!empty($item->created_at)) {
                try {
                    $createdAt = $item->created_at instanceof Time ? 
                        $item->created_at : 
                        Time::parse($item->created_at);
                    $sheet->setCellValue('E' . $row, $createdAt->format('d/m/Y H:i:s'));
                } catch (\Exception $e) {
                    $sheet->setCellValue('E' . $row, '');
                }
            } else {
                $sheet->setCellValue('E' . $row, '');
            }
            
            // Xử lý ngày cập nhật
            if (!empty($item->updated_at)) {
                try {
                    $updatedAt = $item->updated_at instanceof Time ? 
                        $item->updated_at : 
                        Time::parse($item->updated_at);
                    $sheet->setCellValue('F' . $row, $updatedAt->format('d/m/Y H:i:s'));
                } catch (\Exception $e) {
                    $sheet->setCellValue('F' . $row, '');
                }
            } else {
                $sheet->setCellValue('F' . $row, '');
            }

            // Xử lý thời gian xóa
            if ($includeDeleted && isset($item->deleted_at)) {
                if (!empty($item->deleted_at)) {
                    try {
                        $deletedAt = $item->deleted_at instanceof Time ? 
                            $item->deleted_at : 
                            Time::parse($item->deleted_at);
                        $sheet->setCellValue('G' . $row, $deletedAt->format('d/m/Y H:i:s'));
                    } catch (\Exception $e) {
                        $sheet->setCellValue('G' . $row, '');
                    }
                } else {
                    $sheet->setCellValue('G' . $row, '');
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
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'title' => $title,
            'date' => Time::now()->format('d/m/Y H:i:s'),
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