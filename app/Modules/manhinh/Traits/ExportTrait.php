<?php

namespace App\Modules\manhinh\Traits;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Dompdf\Dompdf;
use Dompdf\Options;
use CodeIgniter\I18n\Time;
use App\Modules\camera\Models\CameraModel;
use App\Modules\template\Models\TemplateModel;

trait ExportTrait
{
    /**
     * Chuẩn bị tiêu chí tìm kiếm
     */
    protected function prepareSearchCriteria($keyword, $status, $camera_id = null, $template_id = null, $includeDeleted = false)
    {
        $criteria = [];
        
        if (!empty($keyword)) {
            $criteria['keyword'] = $keyword;
        }
        
        if (isset($status) && $status !== '') {
            $criteria['status'] = (int)$status;
        }
        
        if (!empty($camera_id)) {
            $criteria['camera_id'] = $camera_id;
        }
        
        if (!empty($template_id)) {
            $criteria['template_id'] = $template_id;
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
        // Nếu không có sort được chỉ định, mặc định là sắp xếp theo ID màn hình tăng dần
        if (empty($sort)) {
            $sort = 'man_hinh_id';
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
        // Đảm bảo các model đã được khởi tạo
        if (method_exists($this, 'initializeRelationTrait')) {
            $this->initializeRelationTrait();
        }
        
        // Lấy dữ liệu từ model
        $data = isset($criteria['deleted']) && $criteria['deleted'] 
            ? $this->model->searchDeleted($criteria, $options)
            : $this->model->search($criteria, $options);

        // Lấy danh sách dữ liệu liên quan
        $cameraList = $this->cameraModel->findAll();
        $templateList = $this->templateModel->findAll();
        
        $cameraMap = [];
        foreach ($cameraList as $camera) {
            $cameraMap[$camera->camera_id] = $camera;
        }
        
        $templateMap = [];
        foreach ($templateList as $template) {
            $templateMap[$template->template_id] = $template;
        }

        // Gán thông tin liên quan vào dữ liệu
        foreach ($data as &$item) {
            // Gán thông tin camera
            $cameraId = $item->getCameraId();
            if (!empty($cameraId) && isset($cameraMap[$cameraId])) {
                $item->camera = $cameraMap[$cameraId];
                $item->ten_camera = $cameraMap[$cameraId]->getTenCamera();
            } else {
                $item->camera = null;
                $item->ten_camera = 'Chưa xác định';
            }
            
            // Gán thông tin template
            $templateId = $item->getTemplateId();
            if (!empty($templateId) && isset($templateMap[$templateId])) {
                $item->template = $templateMap[$templateId];
                $item->ten_template = $templateMap[$templateId]->getTenTemplate();
            } else {
                $item->template = null;
                $item->ten_template = 'Chưa xác định';
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
            'Tên màn hình' => 'C',
            'Mã màn hình' => 'D',
            'Camera' => 'E',
            'Template' => 'F',
            'Trạng thái' => 'G',
            'Ngày tạo' => 'H',
            'Ngày cập nhật' => 'I',
        ];

        if ($includeDeleted) {
            $headers['Ngày xóa'] = 'J';
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
        $sheet->setCellValue('A1', 'DANH SÁCH MÀN HÌNH');
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
            $sheet->setCellValue('B' . $row, $item->getId());
            $sheet->setCellValue('C' . $row, $item->getTenManHinh());
            $sheet->setCellValue('D' . $row, $item->getMaManHinh());
            $sheet->setCellValue('E' . $row, !empty($item->ten_camera) ? $item->ten_camera : 'Chưa xác định');
            $sheet->setCellValue('F' . $row, !empty($item->ten_template) ? $item->ten_template : 'Chưa xác định');
            $sheet->setCellValue('G' . $row, $item->isActive() ? 'Hoạt động' : 'Không hoạt động');
            $sheet->setCellValue('H' . $row, $item->getCreatedAtFormatted());
            $sheet->setCellValue('I' . $row, $item->getUpdatedAtFormatted());

            // Xử lý thời gian xóa
            if ($includeDeleted) {
                $sheet->setCellValue('J' . $row, $item->getDeletedAtFormatted());
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