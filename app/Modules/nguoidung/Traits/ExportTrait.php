<?php

namespace App\Modules\nguoidung\Traits;

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
        // Nếu không có sort được chỉ định, mặc định là sắp xếp theo thời gian điểm danh giảm dần
        if (empty($sort)) {
            $sort = 'nguoi_dung_id';
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

        // Lấy danh sách các đối tượng liên quan
        $phongKhoaModel = new \App\Modules\phongkhoa\Models\PhongKhoaModel();
        $loaiNguoiDungModel = new \App\Modules\loainguoidung\Models\LoaiNguoiDungModel();
        $namHocModel = new \App\Modules\namhoc\Models\NamHocModel();
        $bacHocModel = new \App\Modules\bachoc\Models\BacHocModel();
        $heDaoTaoModel = new \App\Modules\hedaotao\Models\HeDaoTaoModel();
        $nganhModel = new \App\Modules\nganh\Models\NganhModel();

        // Tạo map các đối tượng liên quan để truy cập nhanh
        $phongKhoaMap = [];
        $loaiNguoiDungMap = [];
        $namHocMap = [];
        $bacHocMap = [];
        $heDaoTaoMap = [];
        $nganhMap = [];

        foreach ($phongKhoaModel->findAll() as $pk) {
            $phongKhoaMap[$pk->getId()] = $pk;
        }

        foreach ($loaiNguoiDungModel->findAll() as $lnd) {
            $loaiNguoiDungMap[$lnd->getId()] = $lnd;
        }

        foreach ($namHocModel->findAll() as $nh) {
            $namHocMap[$nh->getId()] = $nh;
        }

        foreach ($bacHocModel->findAll() as $bh) {
            $bacHocMap[$bh->getId()] = $bh;
        }

        foreach ($heDaoTaoModel->findAll() as $hdt) {
            $heDaoTaoMap[$hdt->getId()] = $hdt;
        }

        foreach ($nganhModel->findAll() as $n) {
            $nganhMap[$n->getId()] = $n;
        }

        // Gán thông tin các đối tượng liên quan vào dữ liệu
        foreach ($data as &$item) {
            if (!empty($item->phong_khoa_id) && isset($phongKhoaMap[$item->phong_khoa_id])) {
                $item->phong_khoa = $phongKhoaMap[$item->phong_khoa_id];
            }
            if (!empty($item->loai_nguoi_dung_id) && isset($loaiNguoiDungMap[$item->loai_nguoi_dung_id])) {
                $item->loai_nguoi_dung = $loaiNguoiDungMap[$item->loai_nguoi_dung_id];
            }
            if (!empty($item->nam_hoc_id) && isset($namHocMap[$item->nam_hoc_id])) {
                $item->nam_hoc = $namHocMap[$item->nam_hoc_id];
            }
            if (!empty($item->bac_hoc_id) && isset($bacHocMap[$item->bac_hoc_id])) {
                $item->bac_hoc = $bacHocMap[$item->bac_hoc_id];
            }
            if (!empty($item->he_dao_tao_id) && isset($heDaoTaoMap[$item->he_dao_tao_id])) {
                $item->he_dao_tao = $heDaoTaoMap[$item->he_dao_tao_id];
            }
            if (!empty($item->nganh_id) && isset($nganhMap[$item->nganh_id])) {
                $item->nganh = $nganhMap[$item->nganh_id];
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
            'Tài khoản' => 'C',
            'Họ và tên' => 'D',
            'Email' => 'E',
            'Số điện thoại' => 'F',
            'Tên' => 'G',
            'Loại tài khoản' => 'H',
            'Số điện thoại nhà 1' => 'I',
            'Số điện thoại nhà' => 'J',
            'ID người dùng' => 'K',
            'Loại người dùng' => 'L',
            'Phòng khoa' => 'M',
            'Năm học' => 'N',
            'Bậc học' => 'O',
            'Hệ đào tạo' => 'P',
            'Ngành' => 'Q',
            'Trạng thái' => 'R',
            'Lần đăng nhập cuối' => 'S',
            'Ngày tạo' => 'T',
            'Ngày cập nhật' => 'U'
        ];

        if ($includeDeleted) {
            $headers['Ngày xóa'] = 'V';
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
        $sheet->setCellValue('A1', 'DANH SÁCH NGƯỜI DÙNG');
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
            $sheet->setCellValue('B' . $row, $item->nguoi_dung_id);
            $sheet->setCellValue('C' . $row, $item->AccountId);
            $sheet->setCellValue('D' . $row, $item->FullName);
            $sheet->setCellValue('E' . $row, $item->Email);
            $sheet->setCellValue('F' . $row, $item->MobilePhone);
            $sheet->setCellValue('G' . $row, $item->FirstName);
            $sheet->setCellValue('H' . $row, $item->AccountType);
            $sheet->setCellValue('I' . $row, $item->HomePhone1);
            $sheet->setCellValue('J' . $row, $item->HomePhone);
            $sheet->setCellValue('K' . $row, $item->u_id);
            
            // Xử lý các trường relation
            $sheet->setCellValue('L' . $row, $item->getLoaiNguoiDungDisplay());
            $sheet->setCellValue('M' . $row, $item->getPhongKhoaDisplay());
            $sheet->setCellValue('N' . $row, $item->getNamHocDisplay());
            $sheet->setCellValue('O' . $row, $item->getBacHocDisplay());
            $sheet->setCellValue('P' . $row, $item->getHeDaoTaoDisplay());
            $sheet->setCellValue('Q' . $row, $item->getNganhDisplay());
            
            $sheet->setCellValue('R' . $row, $item->status == 1 ? 'Hoạt động' : 'Không hoạt động');
            
            // Xử lý thời gian đăng nhập cuối
            if (!empty($item->last_login)) {
                try {
                    $lastLogin = $item->last_login instanceof Time ? 
                        $item->last_login : 
                        Time::parse($item->last_login);
                    $sheet->setCellValue('S' . $row, $lastLogin->format('d/m/Y H:i:s'));
                } catch (\Exception $e) {
                    $sheet->setCellValue('S' . $row, '');
                }
            } else {
                $sheet->setCellValue('S' . $row, '');
            }
            
            // Xử lý thời gian tạo
            if (!empty($item->created_at)) {
                try {
                    $createdAt = $item->created_at instanceof Time ? 
                        $item->created_at : 
                        Time::parse($item->created_at);
                    $sheet->setCellValue('T' . $row, $createdAt->format('d/m/Y H:i:s'));
                } catch (\Exception $e) {
                    $sheet->setCellValue('T' . $row, '');
                }
            } else {
                $sheet->setCellValue('T' . $row, '');
            }
            
            // Xử lý thời gian cập nhật
            if (!empty($item->updated_at)) {
                try {
                    $updatedAt = $item->updated_at instanceof Time ? 
                        $item->updated_at : 
                        Time::parse($item->updated_at);
                    $sheet->setCellValue('U' . $row, $updatedAt->format('d/m/Y H:i:s'));
                } catch (\Exception $e) {
                    $sheet->setCellValue('U' . $row, '');
                }
            } else {
                $sheet->setCellValue('U' . $row, '');
            }

            // Xử lý thời gian xóa
            if ($includeDeleted && isset($item->deleted_at)) {
                if (!empty($item->deleted_at)) {
                    try {
                        $deletedAt = $item->deleted_at instanceof Time ? 
                            $item->deleted_at : 
                            Time::parse($item->deleted_at);
                        $sheet->setCellValue('V' . $row, $deletedAt->format('d/m/Y H:i:s'));
                    } catch (\Exception $e) {
                        $sheet->setCellValue('V' . $row, '');
                    }
                } else {
                    $sheet->setCellValue('V' . $row, '');
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