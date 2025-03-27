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
        // Nếu không có sort được chỉ định, mặc định là sắp xếp theo ID sự kiện
        if (empty($sort)) {
            $sort = 'su_kien_id';
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

        // Lấy danh sách loại sự kiện
        $loaiSuKienModel = new \App\Modules\loaisukien\Models\LoaiSuKienModel();
        $loaiSuKienList = $loaiSuKienModel->findAll();

        // Tạo map loại sự kiện để truy cập nhanh
        $loaiSuKienMap = [];
        foreach ($loaiSuKienList as $loaiSuKien) {
            $loaiSuKienMap[$loaiSuKien->loai_su_kien_id] = $loaiSuKien;
        }

        // Gán thông tin loại sự kiện vào dữ liệu
        foreach ($data as &$item) {
            if (!empty($item->loai_su_kien_id) && isset($loaiSuKienMap[$item->loai_su_kien_id])) {
                $item->loai_su_kien = $loaiSuKienMap[$item->loai_su_kien_id];
            }
        }

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
            'Tên sự kiện' => 'C',
            'Thời gian bắt đầu' => 'D',
            'Thời gian kết thúc' => 'E',
            'Địa điểm' => 'F',
            'Loại sự kiện' => 'G',
            'Số lượng tham gia' => 'H',
            'Trạng thái' => 'I',
            'Ngày tạo' => 'J',
            'Ngày cập nhật' => 'K',
        ];

        if ($includeDeleted) {
            $headers['Ngày xóa'] = 'L';
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
        $sheet->setCellValue('A1', 'DANH SÁCH SỰ KIỆN');
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
            $sheet->setCellValue('C' . $row, $item->ten_su_kien);
            
            // Định dạng thời gian
            $startTime = !empty($item->thoi_gian_bat_dau) ? 
                (is_string($item->thoi_gian_bat_dau) ? new Time($item->thoi_gian_bat_dau) : $item->thoi_gian_bat_dau) :
                null;
            $endTime = !empty($item->thoi_gian_ket_thuc) ? 
                (is_string($item->thoi_gian_ket_thuc) ? new Time($item->thoi_gian_ket_thuc) : $item->thoi_gian_ket_thuc) :
                null;
                
            $sheet->setCellValue('D' . $row, $startTime ? $startTime->format('d/m/Y H:i') : '');
            $sheet->setCellValue('E' . $row, $endTime ? $endTime->format('d/m/Y H:i') : '');
            $sheet->setCellValue('F' . $row, $item->dia_diem);
            $sheet->setCellValue('G' . $row, !empty($item->loai_su_kien) ? $item->loai_su_kien->ten_loai_su_kien : '');
            $sheet->setCellValue('H' . $row, $item->so_luong_tham_gia);
            $sheet->setCellValue('I' . $row, $item->status == 1 ? 'Hoạt động' : 'Không hoạt động');
            
            // Định dạng thời gian tạo và cập nhật
            $createdAt = !empty($item->created_at) ? 
                (is_string($item->created_at) ? new Time($item->created_at) : $item->created_at) :
                null;
            $updatedAt = !empty($item->updated_at) ? 
                (is_string($item->updated_at) ? new Time($item->updated_at) : $item->updated_at) :
                null;
                
            $sheet->setCellValue('J' . $row, $createdAt ? $createdAt->format('d/m/Y H:i') : '');
            $sheet->setCellValue('K' . $row, $updatedAt ? $updatedAt->format('d/m/Y H:i') : '');

            // Xử lý thời gian xóa
            if ($includeDeleted) {
                if (!empty($item->deleted_at)) {
                    try {
                        $deletedAt = $item->deleted_at instanceof Time ? 
                            $item->deleted_at : 
                            new Time($item->deleted_at);
                        $sheet->setCellValue('L' . $row, $deletedAt->format('d/m/Y H:i'));
                    } catch (\Exception $e) {
                        $sheet->setCellValue('L' . $row, '');
                    }
                } else {
                    $sheet->setCellValue('L' . $row, '');
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