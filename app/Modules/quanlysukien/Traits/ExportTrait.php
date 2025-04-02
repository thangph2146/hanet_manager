<?php

namespace App\Modules\quanlysukien\Traits;

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
    protected $export_title = 'DANH SÁCH LOẠI SỰ KIỆN';
    protected $search_field = 'ten_loai_su_kien';
    protected $search_order = 'ASC';
    protected $header_title = [
        'STT' => 'A',
        'ID' => 'B',
        'Tên loại sự kiện' => 'C',
        'Mã loại sự kiện' => 'D',
        'Trạng thái' => 'E',
        'Ngày tạo' => 'F',
        'Ngày cập nhật' => 'G'
    ];
    protected $header_title_deleted = [
        'Ngày xóa' => 'H'
    ];

    /**
     * Chuẩn bị tùy chọn tìm kiếm cho export
     * 
     * @param array $params Tham số tìm kiếm từ request
     * @return array
     */
    protected function prepareSearchOptions(array $params = []): array
    {
        // Xử lý các tham số tìm kiếm
        $criteria = $this->prepareExportCriteria($params);
        
        // Xử lý sắp xếp
        $sort = $params['sort'] ?? 'thoi_gian_check_out';
        $order = strtoupper($params['order'] ?? 'DESC');

        // Đảm bảo order là ASC hoặc DESC
        if (!in_array($order, ['ASC', 'DESC'])) {
            $order = 'DESC';
        }

        // Đảm bảo sort là một trường hợp lệ
        $validSortFields = $this->getValidSortFields();
        if (!in_array($sort, $validSortFields)) {
            $sort = 'thoi_gian_check_out';
        }

        // Xử lý perPage và page
        $perPage = isset($params['perPage']) ? (int)$params['perPage'] : 10;
        $page = isset($params['page']) ? (int)$params['page'] : 1;

        // Đảm bảo perPage nằm trong danh sách cho phép
        $validPerPage = [10, 25, 50, 100];
        if (!in_array($perPage, $validPerPage)) {
            $perPage = 10;
        }

        // Tính offset
        $offset = ($page - 1) * $perPage;

        return array_merge($criteria, [
            'sort' => $sort,
            'order' => $order,
            'perPage' => $perPage,
            'page' => $page,
            'offset' => $offset,
            'limit' => 0 // Không giới hạn khi xuất
        ]);
    }

    /**
     * Chuẩn bị tiêu chí tìm kiếm cho export
     * 
     * @param array $params Tham số từ request
     * @return array
     */
    protected function prepareExportCriteria(array $params): array
    {
        $criteria = [];

        // Xử lý từ khóa tìm kiếm
        if (isset($params['keyword'])) {
            $criteria['keyword'] = trim($params['keyword']);
        }

        // Xử lý sự kiện
        if (isset($params['su_kien_id'])) {
            $criteria['su_kien_id'] = $params['su_kien_id'] !== '' ? (int)$params['su_kien_id'] : null;
        }

        // Xử lý loại check-out
        if (isset($params['checkout_type'])) {
            $criteria['checkout_type'] = $params['checkout_type'] !== '' ? $params['checkout_type'] : null;
        }

        // Xử lý hình thức tham gia
        if (isset($params['hinh_thuc_tham_gia'])) {
            $criteria['hinh_thuc_tham_gia'] = $params['hinh_thuc_tham_gia'] !== '' ? $params['hinh_thuc_tham_gia'] : null;
        }

        // Xử lý trạng thái
        if (isset($params['status'])) {
            $criteria['status'] = $params['status'] !== '' ? (int)$params['status'] : null;
        }

        // Xử lý khoảng thời gian
        if (isset($params['start_date'])) {
            $criteria['start_date'] = $params['start_date'] !== '' ? 
                date('Y-m-d 00:00:00', strtotime($params['start_date'])) : null;
        }
        if (isset($params['end_date'])) {
            $criteria['end_date'] = $params['end_date'] !== '' ? 
                date('Y-m-d 23:59:59', strtotime($params['end_date'])) : null;
        }

        return $criteria;
    }

    /**
     * Lấy danh sách các trường sắp xếp hợp lệ
     * 
     * @return array
     */
    protected function getValidSortFields(): array
    {
        return [
            'checkout_sukien_id', 
            'su_kien_id', 
            'ho_ten', 
            'email', 
            'thoi_gian_check_out',
            'checkout_type', 
            'face_verified', 
            'status', 
            'hinh_thuc_tham_gia',
            'attendance_duration_minutes',
            'danh_gia',
            'created_at', 
            'updated_at', 
            'deleted_at'
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
     * Format bộ lọc để hiển thị trong file xuất
     * 
     * @param array $params Tham số tìm kiếm
     * @return array Bộ lọc đã định dạng
     */
    protected function formatFilters(array $params): array
    {
        $filters = [];
        
        // Thêm bộ lọc từ khóa
        if (!empty($params['keyword'])) {
            $filters[] = ['Từ khóa tìm kiếm', $params['keyword']];
        }
        
        // Thêm bộ lọc trạng thái
        if (isset($params['status']) && $params['status'] !== '') {
            $statusText = '';
            switch ((string)$params['status']) {
                case '1':
                    $statusText = 'Hoạt động';
                    break;
                case '0':
                    $statusText = 'Không hoạt động';
                    break;
                default:
                    $statusText = 'Tất cả';
            }
            $filters[] = ['Trạng thái', $statusText];
        }
        
        return $filters;
    }

    /**
     * Chuẩn bị tiêu chí tìm kiếm cho check-in sự kiện
     */
    protected function prepareSearchCriteria($params, $includeDeleted = false)
    {
        $criteria = [];
        
        // Xử lý từ khóa tìm kiếm
        if (!empty($params['keyword'])) {
            $criteria['keyword'] = $params['keyword'];
        }
        
        // Xử lý trạng thái
        if (isset($params['status']) && $params['status'] !== '') {
            $criteria['status'] = (int)$params['status'];
        }
        
        // Xử lý sự kiện
        if (!empty($params['su_kien_id'])) {
            $criteria['su_kien_id'] = (int)$params['su_kien_id'];
        }
        
        // Xử lý loại check-in
        if (!empty($params['checkin_type'])) {
            $criteria['checkin_type'] = $params['checkin_type'];
        }
        
        // Xử lý hình thức tham gia
        if (!empty($params['hinh_thuc_tham_gia'])) {
            $criteria['hinh_thuc_tham_gia'] = $params['hinh_thuc_tham_gia'];
        }
        
        // Xử lý xác minh khuôn mặt
        if (isset($params['face_verified']) && $params['face_verified'] !== '') {
            $criteria['face_verified'] = (int)$params['face_verified'];
        }
        
        // Xử lý khoảng thời gian
        if (!empty($params['start_date'])) {
            $criteria['start_date'] = $params['start_date'];
        }
        if (!empty($params['end_date'])) {
            $criteria['end_date'] = $params['end_date'];
        }
        
        if ($includeDeleted) {
            $criteria['deleted'] = true;
        }
        
        return $criteria;
    }

    /**
     * Lấy headers cho file xuất
     *
     * @param bool $includeDeleted Có bao gồm cột ngày xóa không
     * @return array
     */
    protected function getExportHeaders(bool $includeDeleted = false): array
    {
        if ($includeDeleted) {
            return array_merge($this->header_title, $this->header_title_deleted);
        }
        
        return $this->header_title;
    }

    /**
     * Tạo và xuất file Excel
     * 
     * @param array $data Dữ liệu xuất
     * @param array $headers Tiêu đề các cột
     * @param array $filters Thông tin bộ lọc
     * @param string $filename Tên file
     * @param bool $includeDeleted Có bao gồm dữ liệu đã xóa
     */
    protected function createExcelFile($data, $headers, $filters, $filename, $includeDeleted = false)
    {
        // Tạo đối tượng Spreadsheet mới
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập các style
        $titleStyle = [
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1A5FB4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ];
        
        $subtitleStyle = [
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_RIGHT],
        ];
        
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]]
        ];
        
        $dataStyle = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ];
        
        // Tiêu đề chính
        $title = 'DANH SÁCH CHECK-OUT SỰ KIỆN' . ($includeDeleted ? ' ĐÃ XÓA' : '');
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells('A1:' . end($headers) . '1');
        $sheet->getStyle('A1')->applyFromArray($titleStyle);
        $sheet->getRowDimension(1)->setRowHeight(30);
        
        // Ngày xuất
        $sheet->setCellValue('A2', 'Ngày xuất: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:' . end($headers) . '2');
        $sheet->getStyle('A2')->applyFromArray($subtitleStyle);
        
        // Thêm thông tin bộ lọc
        $currentRow = 4;
        if (!empty($filters)) {
            $sheet->setCellValue('A3', 'THÔNG TIN BỘ LỌC:');
            $sheet->mergeCells('A3:' . end($headers) . '3');
            $sheet->getStyle('A3')->getFont()->setBold(true);
            
            $filterRow = 4;
            foreach ($filters as $label => $value) {
                $sheet->setCellValue('A' . $filterRow, $label . ': ' . $value);
                $sheet->mergeCells('A' . $filterRow . ':' . end($headers) . $filterRow);
                $filterRow++;
            }
            $currentRow = $filterRow + 1;
        }
        
        // Thêm header cho bảng dữ liệu
        $headerRow = $currentRow;
        $col = 'A';
        foreach (array_keys($headers) as $header) {
            $sheet->setCellValue($col . $headerRow, $header);
            $sheet->getStyle($col . $headerRow)->applyFromArray($headerStyle);
            $col++;
        }
        $sheet->getRowDimension($headerRow)->setRowHeight(25);
        
        // Thêm dữ liệu
        $dataStartRow = $headerRow + 1;
        $row = $dataStartRow;
        
        foreach ($data as $index => $item) {
            $col = 'A';
            
            // STT
            $sheet->setCellValue($col++ . $row, $index + 1);
            
            // ID
            $sheet->setCellValue($col++ . $row, $item->getId());
            
            // Họ tên
            $sheet->setCellValue($col++ . $row, $item->getHoTen());
            
            // Email
            $sheet->setCellValue($col++ . $row, $item->getEmail());
            
            // Sự kiện
            $suKien = $item->getSuKien();
            $sheet->setCellValue($col++ . $row, $item->getTenSuKien() ?? '');
            
            // Thời gian check-out
            $sheet->setCellValue($col++ . $row, $item->getThoiGianCheckOutFormatted());
            
            // Loại check-out
            $sheet->setCellValue($col++ . $row, $item->getCheckoutTypeText());
            
            // Hình thức
            $sheet->setCellValue($col++ . $row, $item->getHinhThucThamGiaText());
            
            // Trạng thái
            $sheet->setCellValue($col++ . $row, $item->getStatusText());
            
            // Thông tin xác minh khuôn mặt
            $sheet->setCellValue($col++ . $row, $item->isFaceVerified() ? 'Đã xác minh' : 'Chưa xác minh');
            
            // Điểm số khớp khuôn mặt
            $sheet->setCellValue($col++ . $row, $item->getFaceMatchScore() ? 
                number_format($item->getFaceMatchScore() * 100, 2) . '%' : '');
            
            // Thời gian tham dự
            $sheet->setCellValue($col++ . $row, $item->getAttendanceDurationFormatted());
            
            // Đánh giá
            $sheet->setCellValue($col++ . $row, $item->getDanhGiaStars());
            
            // Nội dung đánh giá
            $sheet->setCellValue($col++ . $row, $item->getNoiDungDanhGia());
            
            // Phản hồi
            $sheet->setCellValue($col++ . $row, $item->getFeedback());
            
            // Thông tin thời gian
            $sheet->setCellValue($col++ . $row, $item->getCreatedAtFormatted());
            $sheet->setCellValue($col++ . $row, $item->getUpdatedAtFormatted());
            
            // Ngày xóa (nếu có)
            if ($includeDeleted) {
                $sheet->setCellValue($col++ . $row, $item->getDeletedAtFormatted());
            }
            
            $row++;
        }
        
        // Áp dụng style cho dữ liệu
        if ($row > $dataStartRow) {
            $lastCol = $includeDeleted ? 'R' : 'Q';
            $sheet->getStyle('A' . $dataStartRow . ':' . $lastCol . ($row - 1))->applyFromArray($dataStyle);
        }
        
        // Tự động điều chỉnh độ rộng cột
        foreach (range('A', $lastCol ?? 'Q') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Thêm tổng số bản ghi
        $totalRow = $row + 1;
        $sheet->setCellValue('A' . $totalRow, 'Tổng số bản ghi: ' . count($data));
        $sheet->mergeCells('A' . $totalRow . ':' . ($lastCol ?? 'Q') . $totalRow);
        $sheet->getStyle('A' . $totalRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        // Xuất file
        try {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit();
        } catch (\Exception $e) {
            log_message('error', 'Lỗi xuất Excel: ' . $e->getMessage());
            throw $e;
        }
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
        
        // Lấy đường dẫn template
        $templatePath = 'App\Modules\quanlycheckoutsukien\Views\export\pdf_template';
        
        // Chuẩn bị dữ liệu để đưa vào template
        $viewData = [
            'data' => $data,
            'filters' => $filters,
            'deleted' => $includeDeleted,
            'title' => 'DANH SÁCH CHECK-OUT SỰ KIỆN' . ($includeDeleted ? ' ĐÃ XÓA' : ''),
            'export_date' => date('d/m/Y H:i:s')
        ];
        
        // Tạo HTML từ template
        $html = view($templatePath, $viewData);
        
        // Tạo PDF
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Xuất file
        $dompdf->stream($filename . '.pdf', ['Attachment' => true]);
        exit();
    }

    /**
     * Lấy CSS cho PDF
     */
    protected function getPdfStyles()
    {
        return '
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
                    color: #1a5fb4;
                }
                .subtitle {
                    text-align: center;
                    font-style: italic;
                    margin-bottom: 20px;
                    color: #666;
                }
                .filter-section {
                    margin: 15px 0;
                    padding: 10px;
                    background-color: #f8f9fa;
                    border-radius: 5px;
                }
                .filter-title {
                    font-weight: bold;
                    margin-bottom: 10px;
                    color: #1a5fb4;
                    border-bottom: 1px solid #dee2e6;
                    padding-bottom: 5px;
                }
                .filter-content {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                }
                .filter-item {
                    margin-bottom: 5px;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin: 15px 0;
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
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .footer {
                    text-align: right;
                    font-weight: bold;
                    margin-top: 10px;
                    color: #1a5fb4;
                }
            </style>
        ';
    }

    /**
     * Lấy header cho PDF
     */
    protected function getPdfHeader($title)
    {
        return '<!DOCTYPE html>
            <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                ' . $this->getPdfStyles() . '
            </head>
            <body>
                <h2>' . strtoupper($title) . '</h2>
                <div class="subtitle">Ngày xuất: ' . date('d/m/Y H:i:s') . '</div>';
    }

    /**
     * Lấy bảng dữ liệu cho PDF
     */
    protected function getPdfTable($data, $includeDeleted)
    {
        $html = '<table>';
        
        // Thêm header bảng
        $html .= '<thead><tr>
            <th>STT</th>
            <th>ID</th>
            <th>Sự kiện</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>Thời gian check-out</th>
            <th>Loại check-out</th>
            <th>Hình thức</th>
            <th>Trạng thái</th>
            <th>Xác minh KM</th>
            <th>Điểm số KM</th>
            <th>Thời gian tham dự</th>
            <th>Đánh giá</th>
            <th>Nội dung đánh giá</th>
            <th>Phản hồi</th>
            <th>Ngày tạo</th>
            <th>Ngày cập nhật</th>';
            
        if ($includeDeleted) {
            $html .= '<th>Ngày xóa</th>';
        }
        
        $html .= '</tr></thead><tbody>';
        
        // Thêm dữ liệu
        foreach ($data as $index => $item) {
            $html .= $this->getPdfTableRow($index + 1, $item, $includeDeleted);
        }
        
        $html .= '</tbody></table>';
        
        return $html;
    }

    /**
     * Lấy dòng dữ liệu cho bảng PDF
     */
    protected function getPdfTableRow($index, $item, $includeDeleted): string
    {
        $html = '<tr>';
        $html .= '<td class="text-center">' . $index . '</td>';
        $html .= '<td class="text-center">' . $item->getId() . '</td>';
        
        // Thông tin sự kiện
        $suKien = $item->getSuKien();
        $html .= '<td>' . ($item->getTenSuKien() ?? '') . '</td>';
        
        // Thông tin cá nhân
        $html .= '<td>' . $item->getHoTen() . '</td>';
        $html .= '<td>' . $item->getEmail() . '</td>';
        
        // Thông tin check-out
        $html .= '<td class="text-center">' . $item->getThoiGianCheckOutFormatted() . '</td>';
        $html .= '<td class="text-center">' . $item->getCheckoutTypeText() . '</td>';
        $html .= '<td class="text-center">' . $item->getHinhThucThamGiaText() . '</td>';
        $html .= '<td class="text-center">' . $item->getStatusText() . '</td>';
        
        // Thông tin xác minh
        $html .= '<td class="text-center">' . ($item->isFaceVerified() ? 'Đã xác minh' : 'Chưa xác minh') . '</td>';
        $html .= '<td class="text-center">' . ($item->getFaceMatchScore() ? 
            number_format($item->getFaceMatchScore() * 100, 2) . '%' : '') . '</td>';
        
        // Thông tin tham dự và đánh giá
        $html .= '<td class="text-center">' . $item->getAttendanceDurationFormatted() . '</td>';
        $html .= '<td class="text-center">' . $item->getDanhGiaStars() . '</td>';
        $html .= '<td>' . $item->getNoiDungDanhGia() . '</td>';
        $html .= '<td>' . $item->getFeedback() . '</td>';
        
        // Thông tin thời gian
        $html .= '<td class="text-center">' . $item->getCreatedAtFormatted() . '</td>';
        $html .= '<td class="text-center">' . $item->getUpdatedAtFormatted() . '</td>';
        
        if ($includeDeleted) {
            $html .= '<td class="text-center">' . $item->getDeletedAtFormatted() . '</td>';
        }
        
        $html .= '</tr>';
        
        return $html;
    }

    /**
     * Lấy footer cho PDF
     */
    protected function getPdfFooter($total)
    {
        return '<div class="footer">Tổng số bản ghi: ' . $total . '</div>';
    }

    /**
     * Thêm thông tin bộ lọc vào file PDF
     */
    protected function addFilterInfoToPdf(string $html, array $filters): string
    {
        if (empty($filters)) {
            return $html;
        }

        $filterHtml = '
        <div class="filter-section" style="margin: 15px 0; background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #dee2e6;">
            <div class="filter-title" style="font-weight: bold; font-size: 13px; margin-bottom: 12px; color: #1a5fb4; border-bottom: 2px solid #1a5fb4; padding-bottom: 8px;">
                THÔNG TIN BỘ LỌC
            </div>
            <div class="filter-content" style="display: flex; flex-wrap: wrap; gap: 12px;">';

        // Chia thành 2 cột
        $midPoint = ceil(count($filters) / 2);
        $count = 0;
        $leftColumn = '<div style="flex: 1; min-width: 300px;">';
        $rightColumn = '<div style="flex: 1; min-width: 300px;">';

        foreach ($filters as $label => $value) {
            $filterItem = '
                <div class="filter-item" style="margin-bottom: 8px; padding: 5px; background-color: #ffffff; border-radius: 4px;">
                    <span style="font-weight: bold; color: #2c3e50;">' . $label . ':</span>
                    <span style="margin-left: 5px; color: #34495e;">' . $value . '</span>
                </div>';

            if ($count < $midPoint) {
                $leftColumn .= $filterItem;
            } else {
                $rightColumn .= $filterItem;
            }
            $count++;
        }

        $leftColumn .= '</div>';
        $rightColumn .= '</div>';
        
        $filterHtml .= $leftColumn . $rightColumn . '</div></div>';

        // Chèn thông tin bộ lọc vào sau tiêu đề
        $position = strpos($html, '</div>', strpos($html, 'class="subtitle"')) + 6;
        return substr_replace($html, $filterHtml, $position, 0);
    }
} 