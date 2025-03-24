<?php

namespace App\Modules\template\Controllers;

use App\Controllers\BaseController;
use App\Modules\template\Models\TemplateModel;
use App\Libraries\Breadcrumb;
use App\Libraries\Alert;
use CodeIgniter\Database\Exceptions\DataException;

class Template extends BaseController
{
    protected $model;
    protected $breadcrumb;
    protected $alert;
    protected $moduleUrl;
    protected $moduleName;
    protected $session;
    
    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');
        
        $this->model = new TemplateModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url('template');
        $this->moduleName = 'Template';
        
        // Thêm breadcrumb cơ bản cho tất cả các trang trong controller này
        $this->breadcrumb->add('Trang chủ', base_url())
                        ->add($this->moduleName, $this->moduleUrl);
    }
    
    /**
     * Hiển thị dashboard của module
     */
    public function index()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Danh sách', current_url());
        
        // Thiết lập tiêu chí tìm kiếm mặc định
        $criteria = ['filters' => ['bin' => 0]];
        
        // Thiết lập tùy chọn
        $options = [
            'sort' => 'updated_at',
            'sort_direction' => 'DESC',
            'page' => 1,
            'limit' => 10
        ];
        
        // Sử dụng phương thức search từ BaseModel thông qua TemplateModel
        $data = $this->model->getAll();
        
        // Lấy đối tượng phân trang
        $pager = $this->model->pager;
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Danh sách ' . $this->moduleName,
            'template' => $data,
            'pager' => $pager,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\template\Views\index', $viewData);
    }
    
    /**
     * Hiển thị form tạo mới
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Chuẩn bị dữ liệu mặc định cho entity mới
        $template = new \App\Modules\template\Entities\Template([
            'status' => 1,
            'bin' => 0
        ]);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm ' . $this->moduleName,
            'validation' => $this->validator,
            'moduleUrl' => $this->moduleUrl,
            'template' => $template,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
            'is_new' => true
        ];
        
        return view('App\Modules\template\Views\form', $viewData);
    }
    
    /**
     * Xử lý lưu dữ liệu mới
     */
    public function create()
    {
        $request = $this->request;

        // Validate dữ liệu đầu vào
        if (!$this->validate($this->model->validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tenTemplate = $request->getPost('ten_template');
        $maTemplate = $request->getPost('ma_template');

        // Kiểm tra tên template đã tồn tại chưa
        if ($this->model->isNameExists($tenTemplate)) {
            return redirect()->back()->withInput()->with('error', 'Tên template đã tồn tại');
        }

        // Kiểm tra mã template đã tồn tại chưa
        if ($this->model->isCodeExists($maTemplate)) {
            return redirect()->back()->withInput()->with('error', 'Mã template đã tồn tại');
        }

        $data = [
            'ten_template' => $tenTemplate,
            'ma_template' => $maTemplate,
            'status' => $request->getPost('status') ?? 1,
            'bin' => 0
        ];

        if ($this->model->insert($data)) {
            return redirect()->to('/template')->with('success', 'Thêm template thành công');
        } else {
            return redirect()->back()->withInput()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
        }
    }
    
    /**
     * Hiển thị thông tin chi tiết của một template
     */
    public function view($id = null)
    {
        if (empty($id)) {
            return redirect()->back()->with('error', 'ID không hợp lệ');
        }
        
        // Tìm template với ID tương ứng
        $template = $this->model->find($id);
        
        if (empty($template)) {
            return redirect()->to('/template')->with('error', 'Không tìm thấy template');
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chi tiết', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chi tiết ' . $this->moduleName,
            'template' => $template,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\template\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID template không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Sử dụng phương thức find từ BaseModel, không validate
        $template = $this->model->find($id);
        
        if (empty($template)) {
            $this->alert->set('danger', 'Không tìm thấy template', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chỉnh sửa', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chỉnh sửa ' . $this->moduleName,
            'validation' => $this->validator,
            'template' => $template,
            'moduleUrl' => $this->moduleUrl,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
        ];
        
        return view('App\Modules\template\Views\edit', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID template không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin template, không validate
        $existingTemplate = $this->model->find($id);
        
        if (empty($existingTemplate)) {
            $this->alert->set('danger', 'Không tìm thấy template', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xác thực dữ liệu gửi lên
        $data = $this->request->getPost();
        
        // Xử lý validation
        if (!$this->validateData($data, $this->model->getValidationRules(), $this->model->getValidationMessages())) {
            // Nếu validation thất bại, quay lại form với lỗi
            return $this->edit($id);
        }
        
        // Kiểm tra xem mã template đã tồn tại chưa (trừ chính nó)
        if ($this->model->isCodeExists($data['ma_template'], $id)) {
            $this->alert->set('danger', 'Mã template đã tồn tại', true);
            return redirect()->back()->withInput();
        }
        
        // Kiểm tra xem tên template đã tồn tại chưa (trừ chính nó)
        if ($this->model->isNameExists($data['ten_template'], $id)) {
            $this->alert->set('danger', 'Tên template đã tồn tại', true);
            return redirect()->back()->withInput();
        }
        
        // Cập nhật dữ liệu
        $updateData = [
            'ten_template' => $data['ten_template'],
            'ma_template' => $data['ma_template'],
            'status' => $data['status'] ?? $existingTemplate->status,
        ];
        
        if ($this->model->update($id, $updateData)) {
            $this->alert->set('success', 'Cập nhật template thành công', true);
            return redirect()->to($this->moduleUrl);
        } else {
            $this->alert->set('danger', 'Cập nhật template thất bại', true);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Xóa một template (chuyển vào thùng rác)
     */
    public function delete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID template không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        if ($this->model->moveToRecycleBin($id)) {
            $this->alert->set('success', 'Đã chuyển template vào thùng rác', true);
        } else {
            $this->alert->set('danger', 'Không thể xóa template', true);
        }
        
        return redirect()->to($this->moduleUrl);
    }
    
    /**
     * Hiển thị danh sách các template đã xóa
     */
    public function listdeleted()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thùng rác', current_url());
        
        // Lấy danh sách template đã xóa
        $deletedTemplates = $this->model->getAllInRecycleBin();
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Template đã xóa',
            'templates' => $deletedTemplates,
            'moduleUrl' => $this->moduleUrl,
        ];
        
        return view('App\Modules\template\Views\deleted', $viewData);
    }
    
    /**
     * Khôi phục template từ thùng rác
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID template không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        if ($this->model->restoreFromRecycleBin($id)) {
            $this->alert->set('success', 'Khôi phục template thành công', true);
        } else {
            $this->alert->set('danger', 'Không thể khôi phục template', true);
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Khôi phục nhiều template cùng lúc
     */
    public function restoreMultiple()
    {
        $ids = $this->request->getPost('ids');
        
        if (empty($ids)) {
            $this->alert->set('danger', 'Không có template nào được chọn', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        
        foreach ($ids as $id) {
            if ($this->model->restoreFromRecycleBin($id)) {
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã khôi phục {$successCount} template", true);
        } else {
            $this->alert->set('danger', 'Không thể khôi phục các template đã chọn', true);
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn nhiều template cùng lúc
     */
    public function permanentDeleteMultiple()
    {
        $ids = $this->request->getPost('ids');
        
        if (empty($ids)) {
            $this->alert->set('danger', 'Không có template nào được chọn', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        
        foreach ($ids as $id) {
            if ($this->model->delete($id, true)) {
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã xóa vĩnh viễn {$successCount} template", true);
        } else {
            $this->alert->set('danger', 'Không thể xóa vĩnh viễn các template đã chọn', true);
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn một template
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID template không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        if ($this->model->delete($id, true)) {
            $this->alert->set('success', 'Đã xóa vĩnh viễn template', true);
        } else {
            $this->alert->set('danger', 'Không thể xóa vĩnh viễn template', true);
        }
        
        return redirect()->to($this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xử lý tìm kiếm template
     */
    public function search()
    {
        // Lấy từ khóa tìm kiếm
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Tìm kiếm', current_url());
        
        // Thiết lập tiêu chí tìm kiếm
        $criteria = ['filters' => ['bin' => 0]];
        
        if (!empty($keyword)) {
            $criteria['keyword'] = $keyword;
        }
        
        if ($status !== null && $status !== '') {
            $criteria['filters']['status'] = $status;
        }
        
        // Thiết lập tùy chọn
        $options = [
            'sort_field' => $this->request->getGet('sort') ?? 'updated_at',
            'sort_direction' => $this->request->getGet('direction') ?? 'DESC',
            'limit' => 10,
            'offset' => (max(1, (int)$this->request->getGet('page') ?? 1) - 1) * 10,
        ];
        
        // Thực hiện tìm kiếm
        $templates = $this->model->search($criteria, $options);
        
        // Lấy đối tượng phân trang
        $pager = $this->model->pager;
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Kết quả tìm kiếm',
            'keyword' => $keyword,
            'status' => $status,
            'templates' => $templates,
            'pager' => $pager,
            'moduleUrl' => $this->moduleUrl,
        ];
        
        return view('App\Modules\template\Views\search', $viewData);
    }
    
    /**
     * Xóa nhiều template cùng lúc
     */
    public function deleteMultiple()
    {
        $ids = $this->request->getPost('ids');
        
        if (empty($ids)) {
            $this->alert->set('danger', 'Không có template nào được chọn', true);
            return redirect()->to($this->moduleUrl);
        }
        
        $successCount = 0;
        
        foreach ($ids as $id) {
            if ($this->model->moveToRecycleBin($id)) {
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã chuyển {$successCount} template vào thùng rác", true);
        } else {
            $this->alert->set('danger', 'Không thể xóa các template đã chọn', true);
        }
        
        return redirect()->to($this->moduleUrl);
    }
    
    /**
     * Cập nhật trạng thái hoạt động của nhiều template cùng lúc
     */
    public function statusMultiple()
    {
        $ids = $this->request->getPost('ids');
        $status = $this->request->getPost('status');
        
        if (empty($ids)) {
            $this->alert->set('danger', 'Không có template nào được chọn', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Validate trạng thái
        if ($status !== '0' && $status !== '1') {
            $this->alert->set('danger', 'Trạng thái không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        $successCount = 0;
        
        foreach ($ids as $id) {
            if ($this->model->update($id, ['status' => $status])) {
                $successCount++;
            }
        }
        
        $statusText = $status == 1 ? 'hoạt động' : 'không hoạt động';
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã cập nhật {$successCount} template sang trạng thái {$statusText}", true);
        } else {
            $this->alert->set('danger', 'Không thể cập nhật trạng thái các template đã chọn', true);
        }
        
        return redirect()->to($this->moduleUrl);
    }
    
    /**
     * Xuất dữ liệu template ra Excel
     */
    public function exportExcel()
    {
        // Lấy tất cả template
        $templates = $this->model->getAll();
        
        // Tạo spreadsheet mới
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập tiêu đề của sheet
        $sheet->setTitle('Danh sách template');
        
        // Thiết lập header cột
        $sheet->setCellValue('A1', 'STT');
        $sheet->setCellValue('B1', 'Mã template');
        $sheet->setCellValue('C1', 'Tên template');
        $sheet->setCellValue('D1', 'Trạng thái');
        $sheet->setCellValue('E1', 'Ngày tạo');
        $sheet->setCellValue('F1', 'Cập nhật lần cuối');
        
        // Chèn dữ liệu
        $row = 2;
        foreach ($templates as $i => $template) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $template->ma_template);
            $sheet->setCellValue('C' . $row, $template->ten_template);
            $sheet->setCellValue('D' . $row, $template->status == 1 ? 'Hoạt động' : 'Không hoạt động');
            
            // Chuyển đổi ngày tháng
            $createdAt = !empty($template->created_at) ? date('d/m/Y H:i:s', strtotime($template->created_at)) : '';
            $updatedAt = !empty($template->updated_at) ? date('d/m/Y H:i:s', strtotime($template->updated_at)) : '';
            
            $sheet->setCellValue('E' . $row, $createdAt);
            $sheet->setCellValue('F' . $row, $updatedAt);
            
            $row++;
        }
        
        // Thiết lập độ rộng cột tự động
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Thiết lập style cho header
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
        ];
        
        $sheet->getStyle('A1:F1')->applyFromArray($styleArray);
        
        // Thiết lập borders cho dữ liệu
        $borderArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        $lastRow = $row - 1;
        $sheet->getStyle('A2:F' . $lastRow)->applyFromArray($borderArray);
        
        // Thiết lập tên file
        $filename = 'danh_sach_template_' . date('YmdHis') . '.xlsx';
        
        // Set header cho download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // Xuất file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Xuất dữ liệu template ra PDF
     */
    public function exportPdf()
    {
        // Lấy tất cả template
        $templates = $this->model->getAll();
        
        // Chuẩn bị dữ liệu cho PDF
        $data = [
            'templates' => $templates,
            'title' => 'Danh sách template'
        ];
        
        // Tải view HTML cho PDF
        $html = view('App\Modules\template\Views\export_pdf', $data);
        
        // Khởi tạo dompdf
        $dompdf = new \Dompdf\Dompdf(); 
        
        // Thiết lập options
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        
        // Load HTML vào dompdf
        $dompdf->loadHtml($html);
        
        // Thiết lập kích thước giấy và hướng
        $dompdf->setPaper('A4', 'landscape');
        
        // Render PDF
        $dompdf->render();
        
        // Thiết lập tên file
        $filename = 'danh_sach_template_' . date('YmdHis') . '.pdf';
        
        // Xuất file PDF
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }
    
    /**
     * Xuất dữ liệu template đã xóa ra Excel
     */
    public function exportDeletedExcel()
    {
        // Lấy tất cả template đã xóa
        $templates = $this->model->getAllInRecycleBin();
        
        // Tạo spreadsheet mới
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập tiêu đề của sheet
        $sheet->setTitle('Danh sách template đã xóa');
        
        // Thiết lập header cột
        $sheet->setCellValue('A1', 'STT');
        $sheet->setCellValue('B1', 'Mã template');
        $sheet->setCellValue('C1', 'Tên template');
        $sheet->setCellValue('D1', 'Trạng thái');
        $sheet->setCellValue('E1', 'Ngày tạo');
        $sheet->setCellValue('F1', 'Ngày xóa');
        
        // Chèn dữ liệu
        $row = 2;
        foreach ($templates as $i => $template) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $template->ma_template);
            $sheet->setCellValue('C' . $row, $template->ten_template);
            $sheet->setCellValue('D' . $row, $template->status == 1 ? 'Hoạt động' : 'Không hoạt động');
            
            // Chuyển đổi ngày tháng
            $createdAt = !empty($template->created_at) ? date('d/m/Y H:i:s', strtotime($template->created_at)) : '';
            $deletedAt = !empty($template->deleted_at) ? date('d/m/Y H:i:s', strtotime($template->deleted_at)) : '';
            
            $sheet->setCellValue('E' . $row, $createdAt);
            $sheet->setCellValue('F' . $row, $deletedAt);
            
            $row++;
        }
        
        // Thiết lập độ rộng cột tự động
        foreach (range('A', 'F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Thiết lập style cho header
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                'startColor' => [
                    'argb' => 'FFA0A0A0',
                ],
                'endColor' => [
                    'argb' => 'FFFFFFFF',
                ],
            ],
        ];
        
        $sheet->getStyle('A1:F1')->applyFromArray($styleArray);
        
        // Thiết lập borders cho dữ liệu
        $borderArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        
        $lastRow = $row - 1;
        if ($lastRow >= 2) {
            $sheet->getStyle('A2:F' . $lastRow)->applyFromArray($borderArray);
        }
        
        // Thiết lập tên file
        $filename = 'danh_sach_template_da_xoa_' . date('YmdHis') . '.xlsx';
        
        // Set header cho download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // Xuất file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    
    /**
     * Xuất dữ liệu template đã xóa ra PDF
     */
    public function exportDeletedPdf()
    {
        // Lấy tất cả template đã xóa
        $templates = $this->model->getAllInRecycleBin();
        
        // Chuẩn bị dữ liệu cho PDF
        $data = [
            'templates' => $templates,
            'title' => 'Danh sách template đã xóa'
        ];
        
        // Tải view HTML cho PDF
        $html = view('App\Modules\template\Views\export_deleted_pdf', $data);
        
        // Khởi tạo dompdf
        $dompdf = new \Dompdf\Dompdf(); 
        
        // Thiết lập options
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);
        
        // Load HTML vào dompdf
        $dompdf->loadHtml($html);
        
        // Thiết lập kích thước giấy và hướng
        $dompdf->setPaper('A4', 'landscape');
        
        // Render PDF
        $dompdf->render();
        
        // Thiết lập tên file
        $filename = 'danh_sach_template_da_xoa_' . date('YmdHis') . '.pdf';
        
        // Xuất file PDF
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }
} 