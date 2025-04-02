<?php

namespace App\Modules\quanlycheckinsukien\Traits;

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
        $sort = $params['sort'] ?? 'thoi_gian_check_in';
        $order = strtoupper($params['order'] ?? 'DESC');

        // Đảm bảo order là ASC hoặc DESC
        if (!in_array($order, ['ASC', 'DESC'])) {
            $order = 'DESC';
        }

        // Đảm bảo sort là một trường hợp lệ
        $validSortFields = $this->getValidSortFields();
        if (!in_array($sort, $validSortFields)) {
            $sort = 'thoi_gian_check_in';
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

        // Xử lý loại check-in
        if (isset($params['checkin_type'])) {
            $criteria['checkin_type'] = $params['checkin_type'] !== '' ? $params['checkin_type'] : null;
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
            'checkin_sukien_id', 
            'su_kien_id', 
            'ho_ten', 
            'email', 
            'thoi_gian_check_in',
            'checkin_type', 
            'face_verified', 
            'status', 
            'hinh_thuc_tham_gia',
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

        // Thêm từ khóa tìm kiếm
        if (!empty($params['keyword'])) {
            $filters['Từ khóa tìm kiếm'] = trim($params['keyword']);
        }

        // Thêm trạng thái
        if (isset($params['status']) && $params['status'] !== '') {
            $statusLabels = [
                0 => 'Vô hiệu',
                1 => 'Hoạt động',
                2 => 'Đang xử lý'
            ];
            $filters['Trạng thái'] = $statusLabels[$params['status']] ?? 'Không xác định';
        }

        // Thêm sự kiện
        if (!empty($params['su_kien_id'])) {
            $suKien = $this->suKienModel->find($params['su_kien_id']);
            if ($suKien) {
                $filters['Sự kiện'] = $suKien->getTenSuKien() ?? $suKien->ten_su_kien;
            }
        }

        // Thêm loại check-in
        if (!empty($params['checkin_type'])) {
            $checkinTypes = [
                'manual' => 'Thủ công',
                'face_id' => 'Nhận diện khuôn mặt',
                'qr_code' => 'Mã QR',
                'auto' => 'Tự động',
                'online' => 'Trực tuyến'
            ];
            $filters['Loại check-in'] = $checkinTypes[$params['checkin_type']] ?? $params['checkin_type'];
        }

        // Thêm hình thức tham gia
        if (!empty($params['hinh_thuc_tham_gia'])) {
            $hinhThucThamGia = [
                'offline' => 'Trực tiếp',
                'online' => 'Trực tuyến'
            ];
            $filters['Hình thức tham gia'] = $hinhThucThamGia[$params['hinh_thuc_tham_gia']] ?? $params['hinh_thuc_tham_gia'];
        }

        // Thêm trạng thái xác minh khuôn mặt
        if (isset($params['face_verified']) && $params['face_verified'] !== '') {
            $faceVerifiedLabels = [
                0 => 'Chưa xác minh',
                1 => 'Đã xác minh',
                2 => 'Đang xử lý'
            ];
            $filters['Xác minh khuôn mặt'] = $faceVerifiedLabels[$params['face_verified']] ?? 'Không xác định';
        }

        // Thêm khoảng thời gian
        if (!empty($params['start_date']) || !empty($params['end_date'])) {
            $timeRange = '';
            if (!empty($params['start_date'])) {
                $timeRange .= 'Từ ' . date('d/m/Y', strtotime($params['start_date']));
            }
            if (!empty($params['end_date'])) {
                $timeRange .= ($timeRange ? ' đến ' : 'Đến ') . date('d/m/Y', strtotime($params['end_date']));
            }
            $filters['Thời gian'] = $timeRange;
        }

        // Thêm sắp xếp
        if (!empty($params['sort'])) {
            $sortFields = [
                'thoi_gian_check_in' => 'Thời gian check-in',
                'ho_ten' => 'Họ tên',
                'email' => 'Email',
                'status' => 'Trạng thái',
                'created_at' => 'Ngày tạo',
                'updated_at' => 'Ngày cập nhật'
            ];
            $sortField = $sortFields[$params['sort']] ?? $params['sort'];
            $sortOrder = !empty($params['order']) ? strtoupper($params['order']) : 'DESC';
            $filters['Sắp xếp'] = $sortField . ' ' . ($sortOrder === 'DESC' ? 'giảm dần' : 'tăng dần');
        }

        return $filters;
    }

    /**
     * Chuẩn bị tiêu chí tìm kiếm
     * 
     * @param array $params Tham số từ request
     * @param bool $includeDeleted Có bao gồm các bản ghi đã xóa hay không
     * @return array Tiêu chí tìm kiếm
     */
    protected function prepareSearchCriteria($params, $includeDeleted = false)
    {
        $criteria = [];

        // Thiết lập tìm kiếm cho các bản ghi đã xóa nếu cần
        if ($includeDeleted) {
            $criteria['deleted'] = true;
        }

        // Xử lý từ khóa tìm kiếm
        if (!empty($params['keyword'])) {
            $criteria['keyword'] = trim($params['keyword']);
        }

        // Xử lý sự kiện
        if (isset($params['su_kien_id']) && $params['su_kien_id'] !== '') {
            $criteria['su_kien_id'] = $params['su_kien_id'];
        }

        // Xử lý loại check-in
        if (isset($params['checkin_type']) && $params['checkin_type'] !== '') {
            $criteria['checkin_type'] = $params['checkin_type'];
        }

        // Xử lý hình thức tham gia
        if (isset($params['hinh_thuc_tham_gia']) && $params['hinh_thuc_tham_gia'] !== '') {
            $criteria['hinh_thuc_tham_gia'] = $params['hinh_thuc_tham_gia'];
        }

        // Xử lý trạng thái
        if (isset($params['status']) && $params['status'] !== '') {
            $criteria['status'] = $params['status'];
        }

        // Xử lý khoảng thời gian
        if (!empty($params['start_date'])) {
            $criteria['start_date'] = date('Y-m-d 00:00:00', strtotime($params['start_date']));
        }
        if (!empty($params['end_date'])) {
            $criteria['end_date'] = date('Y-m-d 23:59:59', strtotime($params['end_date']));
        }

        return $criteria;
    }

    /**
     * Lấy headers cho tệp xuất
     * 
     * @param bool $includeDeleted Có bao gồm thông tin xóa hay không
     * @return array Mảng headers
     */
    protected function getExportHeaders(bool $includeDeleted = false): array
    {
        $headers = [
            'STT' => 'A',
            'ID' => 'B',
            'Sự kiện' => 'C',
            'Họ tên' => 'D',
            'Email' => 'E',
            'Số điện thoại' => 'F',
            'Thời gian check-in' => 'G',
            'Loại check-in' => 'H',
            'Hình thức tham gia' => 'I',
            'Face verified' => 'J',
            'Trạng thái' => 'K',
            'Ngày tạo' => 'L',
            'Ngày cập nhật' => 'M',
        ];

        if ($includeDeleted) {
            $headers['Ngày xóa'] = 'N';
        }

        return $headers;
    }

    /**
     * Tạo file Excel với dữ liệu
     * 
     * @param array $data Dữ liệu để xuất
     * @param array $headers Headers cho Excel
     * @param array $filters Bộ lọc đã áp dụng
     * @param string $filename Tên file
     * @param bool $includeDeleted Có bao gồm các bản ghi đã xóa hay không
     * @return void
     */
    protected function createExcelFile($data, $headers, $filters, $filename, $includeDeleted = false)
    {
        // Khởi tạo spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set thông tin file
        $spreadsheet->getProperties()
            ->setCreator('Hệ thống quản lý')
            ->setLastModifiedBy('Hệ thống quản lý')
            ->setTitle('Danh sách check-in sự kiện')
            ->setSubject('Danh sách check-in sự kiện')
            ->setDescription('Xuất dữ liệu check-in sự kiện')
            ->setKeywords('check-in sự kiện')
            ->setCategory('Báo cáo');
        
        // Thiết lập tiêu đề
        $sheet->setCellValue('A1', 'DANH SÁCH CHECK-IN SỰ KIỆN');
        $sheet->mergeCells('A1:' . end($headers) . '1');
        
        // Style cho tiêu đề
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Thêm thông tin thời gian xuất
        $sheet->setCellValue('A2', 'Thời gian xuất: ' . Time::now()->format('d/m/Y H:i:s'));
        $sheet->mergeCells('A2:' . end($headers) . '2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Thêm thông tin bộ lọc
        $filterRow = 3;
        if (!empty($filters)) {
            $sheet->setCellValue('A' . $filterRow, 'Bộ lọc:');
            $sheet->mergeCells('A' . $filterRow . ':' . end($headers) . $filterRow);
            $sheet->getStyle('A' . $filterRow)->getFont()->setBold(true);
            
            $filterRow++;
            foreach ($filters as $key => $value) {
                $sheet->setCellValue('A' . $filterRow, $key . ': ' . $value);
                $sheet->mergeCells('A' . $filterRow . ':' . end($headers) . $filterRow);
                $filterRow++;
            }
        }
        
        // Header cho bảng dữ liệu
        $headerRow = $filterRow + 1;
        $colIndex = 0;
        foreach ($headers as $headerText => $column) {
            $sheet->setCellValue($column . $headerRow, $headerText);
            $colIndex++;
        }
        
        // Thiết lập style cho header
        $sheet->getStyle('A' . $headerRow . ':' . end($headers) . $headerRow)->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        
        // Thêm dữ liệu
        $row = $headerRow + 1;
        foreach ($data as $index => $item) {
            // STT
            $sheet->setCellValue('A' . $row, $index + 1);
            
            // ID
            $sheet->setCellValue('B' . $row, $item->checkin_sukien_id);
            
            // Sự kiện
            $suKienTen = '';
            if (isset($item->su_kien_id) && $item->su_kien_id) {
                $suKien = $this->suKienModel->find($item->su_kien_id);
                if ($suKien) {
                    $suKienTen = $suKien->getTenSuKien() ?? $suKien->ten_su_kien;
                }
            }
            $sheet->setCellValue('C' . $row, $suKienTen);
            
            // Thông tin người dùng
            $sheet->setCellValue('D' . $row, $item->ho_ten);
            $sheet->setCellValue('E' . $row, $item->email);
            $sheet->setCellValue('F' . $row, $item->so_dien_thoai);
            
            // Thời gian check-in
            $thoiGianCheckIn = '';
            if (!empty($item->thoi_gian_check_in)) {
                $thoiGianCheckIn = $item->thoi_gian_check_in instanceof Time ? 
                    $item->thoi_gian_check_in->format('d/m/Y H:i:s') : 
                    Time::parse($item->thoi_gian_check_in)->format('d/m/Y H:i:s');
            }
            $sheet->setCellValue('G' . $row, $thoiGianCheckIn);
            
            // Loại check-in
            $checkinTypes = [
                'manual' => 'Thủ công',
                'face_id' => 'Nhận diện khuôn mặt',
                'qr_code' => 'Mã QR',
                'auto' => 'Tự động',
                'online' => 'Trực tuyến'
            ];
            $checkinType = $checkinTypes[$item->checkin_type] ?? $item->checkin_type;
            $sheet->setCellValue('H' . $row, $checkinType);
            
            // Hình thức tham gia
            $hinhThucThamGia = [
                'offline' => 'Trực tiếp',
                'online' => 'Trực tuyến'
            ];
            $hinhThuc = $hinhThucThamGia[$item->hinh_thuc_tham_gia] ?? $item->hinh_thuc_tham_gia;
            $sheet->setCellValue('I' . $row, $hinhThuc);
            
            // Face verified
            $faceVerified = 'Không';
            if ($item->face_verified == 1) {
                $faceVerified = 'Đã xác thực';
            }
            $sheet->setCellValue('J' . $row, $faceVerified);
            
            // Trạng thái
            $statusLabels = [
                0 => 'Vô hiệu',
                1 => 'Hoạt động',
                2 => 'Đang xử lý'
            ];
            $statusText = $statusLabels[$item->status] ?? 'Không xác định';
            $sheet->setCellValue('K' . $row, $statusText);
            
            // Ngày tạo
            $createdAt = '';
            if (!empty($item->created_at)) {
                $createdAt = $item->created_at instanceof Time ? 
                    $item->created_at->format('d/m/Y H:i:s') : 
                    Time::parse($item->created_at)->format('d/m/Y H:i:s');
            }
            $sheet->setCellValue('L' . $row, $createdAt);
            
            // Ngày cập nhật
            $updatedAt = '';
            if (!empty($item->updated_at)) {
                $updatedAt = $item->updated_at instanceof Time ? 
                    $item->updated_at->format('d/m/Y H:i:s') : 
                    Time::parse($item->updated_at)->format('d/m/Y H:i:s');
            }
            $sheet->setCellValue('M' . $row, $updatedAt);
            
            // Ngày xóa (nếu có)
            if ($includeDeleted) {
                $deletedAt = '';
                if (!empty($item->deleted_at)) {
                    $deletedAt = $item->deleted_at instanceof Time ? 
                        $item->deleted_at->format('d/m/Y H:i:s') : 
                        Time::parse($item->deleted_at)->format('d/m/Y H:i:s');
                }
                $sheet->setCellValue('N' . $row, $deletedAt);
            }
            
            $row++;
        }
        
        // Auto-size các cột
        foreach ($headers as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Style cho bảng dữ liệu
        $sheet->getStyle('A' . ($headerRow + 1) . ':' . end($headers) . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);
        
        // Căn giữa một số cột
        $centerCols = ['A', 'B', 'G', 'H', 'I', 'J', 'K', 'L', 'M'];
        if ($includeDeleted) {
            $centerCols[] = 'N';
        }
        
        foreach ($centerCols as $col) {
            $sheet->getStyle($col . ($headerRow + 1) . ':' . $col . ($row - 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }
        
        // Lưu file Excel
        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }
} 