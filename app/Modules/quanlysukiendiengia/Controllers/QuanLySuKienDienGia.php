<?php

namespace App\Modules\quanlysukiendiengia\Controllers;

use App\Controllers\BaseController;
use App\Modules\quanlysukiendiengia\Models\SuKienDienGiaModel;
use App\Modules\quanlydiengia\Models\DienGiaModel;
use App\Modules\quanlysukien\Models\SuKienModel;
use App\Libraries\Alert;
use CodeIgniter\Database\Exceptions\DataException;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Dompdf\Dompdf;
use Dompdf\Options;
use CodeIgniter\I18n\Time;
use App\Modules\quanlysukiendiengia\Traits\ExportTrait;
use App\Modules\quanlysukiendiengia\Traits\RelationTrait;

class QuanLySuKienDienGia extends BaseController
{
    use ResponseTrait;
    use ExportTrait;
    use RelationTrait;
    
    protected $model;
    protected $dienGiaModel;
    protected $suKienModel;
    protected $alert;
    protected $moduleUrl;
    protected $title = 'Sự kiện diễn giả';
    protected $module_name = 'quanlysukiendiengia';
    protected $controller_name = 'QuanLySuKienDienGia';
    protected $primary_key = 'su_kien_dien_gia_id';
    // Search
    protected $field_sort = 'thu_tu';
    protected $field_order = 'ASC';

    // Export
    protected $export_excel = 'danh_sach_su_kien_dien_gia_excel';
    protected $export_excel_title = 'DANH SÁCH SỰ KIỆN DIỄN GIẢ (Excel)';

    protected $export_pdf = 'danh_sach_su_kien_dien_gia_pdf';
    protected $export_pdf_title = 'DANH SÁCH SỰ KIỆN DIỄN GIẢ (PDF)';

    protected $export_excel_deleted = 'danh_sach_su_kien_dien_gia_da_xoa_excel';
    protected $export_excel_deleted_title = 'DANH SÁCH SỰ KIỆN DIỄN GIẢ ĐÃ XÓA (Excel)';

    protected $export_pdf_deleted = 'danh_sach_su_kien_dien_gia_da_xoa_pdf';
    protected $export_pdf_deleted_title = 'DANH SÁCH SỰ KIỆN DIỄN GIẢ ĐÃ XÓA (PDF)';

    protected $pager_only = [
        'keyword', 
        'perPage', 
        'sort', 
        'order', 
        'trang_thai_tham_gia', 
        'su_kien_id',
        'dien_gia_id',  
        'hien_thi_cong_khai',
    ];

    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');

        // Khởi tạo các thành phần cần thiết
        $this->model = new SuKienDienGiaModel();
        $this->dienGiaModel = new DienGiaModel();
        $this->suKienModel = new SuKienModel();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url($this->module_name);
        
        // Khởi tạo các model quan hệ
        $this->initializeRelationTrait();
      
    }
    

    /**
     * Hiển thị danh sách tham gia sự kiện
     */
    public function index()
    {
        // Lấy và xử lý tham số tìm kiếm
        $params = $this->prepareSearchParams($this->request);
        $params = $this->processSearchParams($params);
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        $this->model->setSurroundCount(3);
        
        // Xây dựng tiêu chí và tùy chọn tìm kiếm
        $criteria = $this->buildSearchCriteria($params);
        $options = $this->buildSearchOptions($params);
        
        // Lấy dữ liệu tham gia sự kiện và thông tin phân trang thông qua model
        $pageData = $this->model->search($criteria, $options);
        
        // Lấy tổng số kết quả thông qua model
        $pager = $this->model->getPager();
        $total = $pager ? $pager->getTotal() : $this->model->countSearchResults($criteria);
        
        // Nếu trang hiện tại lớn hơn tổng số trang, điều hướng về trang cuối cùng
        $pageCount = ceil($total / $params['perPage']);
        if ($total > 0 && $params['page'] > $pageCount) {
            // Tạo URL mới với trang cuối cùng
            $redirectParams = $_GET;
            $redirectParams['page'] = $pageCount;
            $redirectUrl = site_url($this->module_name) . '?' . http_build_query($redirectParams);
            
            // Chuyển hướng đến trang cuối cùng
            return redirect()->to($redirectUrl);
        }
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath($this->module_name);
            // Thêm tất cả các tham số cần giữ lại khi chuyển trang
            $pager->setOnly($this->pager_only);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Chuẩn bị dữ liệu cho view
        $viewData = $this->prepareViewData($this->module_name, $pageData, $pager, array_merge($params, ['total' => $total]));
        
        // Thêm danh sách sự kiện và diễn giả để hiển thị tên thay vì ID
        $viewData['suKienList'] = $this->suKienModel->findAll();
        $viewData['dienGiaList'] = $this->dienGiaModel->findAll();
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\index', $viewData);
    }
    
    /**
     * Hiển thị form thêm mới
     */
    public function new()
    {
        // Sử dụng prepareFormData để chuẩn bị dữ liệu cho form
        $viewData = $this->prepareFormData($this->module_name);
        
        // Lấy danh sách sự kiện và diễn giả từ model
        $viewData['suKienList'] = $this->suKienModel->findAll();
        $viewData['dienGiaList'] = $this->dienGiaModel->findAll();
        
        // Thêm dữ liệu cho view
        $viewData['title'] = 'Thêm mới ' . $this->title;
        $viewData['validation'] = $this->validator;
        $viewData['errors'] = session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []);
        $viewData['action'] = site_url($this->module_name . '/create');
        $viewData['method'] = 'POST';
        
        return view('App\Modules\\' . $this->module_name . '\Views\new', $viewData);
    }
    
    /**
     * Xử lý thêm mới dữ liệu
     */
    public function create()
    {
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Xử lý định dạng thời gian bằng cách sử dụng phương thức formatDateTime từ model
        if (!empty($data['thoi_gian_trinh_bay'])) {
            $data['thoi_gian_trinh_bay'] = $this->model->formatDateTime($data['thoi_gian_trinh_bay']);
        }
        
        if (!empty($data['thoi_gian_ket_thuc'])) {
            $data['thoi_gian_ket_thuc'] = $this->model->formatDateTime($data['thoi_gian_ket_thuc']);
        }
        
        // Kiểm tra xem mối quan hệ đã tồn tại chưa
        if (!empty($data['su_kien_id']) && !empty($data['dien_gia_id'])) {
            if ($this->model->isRelationExists($data['su_kien_id'], $data['dien_gia_id'])) {
                return redirect()->back()->withInput()->with('error', 'Diễn giả đã được thêm vào sự kiện này');
            }
        }
        
        // Chuẩn bị các quy tắc validation
        $this->model->prepareValidationRules('insert');
        
        // Kiểm tra dữ liệu
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        try {
            // Xử lý tài liệu đính kèm nếu có
            $files = $this->request->getFiles();
            $hasValidFiles = false;
            
            if (!empty($files['tai_lieu_dinh_kem'])) {
                $uploadedFiles = $files['tai_lieu_dinh_kem'];
                if (is_array($uploadedFiles) && !empty($uploadedFiles[0]) && $uploadedFiles[0]->isValid()) {
                    $data['tai_lieu_dinh_kem'] = $this->uploadFiles($uploadedFiles);
                    $hasValidFiles = true;
                }
            }
            
            // Nếu không có tệp hợp lệ, đặt tai_lieu_dinh_kem là JSON rỗng []
            if (!$hasValidFiles) {
                $data['tai_lieu_dinh_kem'] = json_encode([]);
            }
            
            // Lưu dữ liệu thông qua model
            if ($this->model->insert($data)) {
                $this->alert->set('success', 'Thêm mới ' . $this->title . ' thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                throw new \RuntimeException('Không thể thêm mới ' . $this->title);
            }
        } catch (\Exception $e) {
            log_message('error', 'Lỗi thêm mới sự kiện diễn giả: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm mới ' . $this->title . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Hiển thị chi tiết
     */
    public function view($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Đảm bảo các model relationship được khởi tạo
        $this->initializeRelationTrait();
        
        // Lấy thông tin dữ liệu cơ bản thông qua model
        $data = $this->model->find($id);
        
        if (empty($data)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin sự kiện và diễn giả thông qua model
        $data->suKien = $this->suKienModel->find($data->getSuKienId());
        $data->dienGia = $this->dienGiaModel->find($data->getDienGiaId());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'title' => 'Chi tiết ' . $this->title,
            'data' => $data,
            'moduleUrl' => $this->moduleUrl,
            'module_name' => $this->module_name
        ];
        
        return view('App\Modules\\' . $this->module_name . '\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin từ model
        $data = $this->model->find($id);
        
        if (empty($data)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Sử dụng prepareFormData để chuẩn bị dữ liệu cho form
        $viewData = $this->prepareFormData($this->module_name, $data);
        
        // Lấy danh sách sự kiện và diễn giả từ model
        $viewData['suKienList'] = $this->suKienModel->findAll();
        $viewData['dienGiaList'] = $this->dienGiaModel->findAll();
        
        // Thêm dữ liệu cho view
        $viewData['title'] = 'Chỉnh sửa ' . $this->title;
        $viewData['validation'] = $this->validator;
        $viewData['errors'] = session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []);
        $viewData['action'] = site_url($this->module_name . '/update/' . $id);
        $viewData['method'] = 'POST';
        
        return view('App\Modules\\' . $this->module_name . '\Views\edit', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin từ model
        $existingRecord = $this->model->find($id);
        
        if (empty($existingRecord)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xác thực dữ liệu gửi lên
        $data = $this->request->getPost();
        
        // Xử lý định dạng thời gian bằng cách sử dụng phương thức formatDateTime từ model
        if (!empty($data['thoi_gian_trinh_bay'])) {
            $data['thoi_gian_trinh_bay'] = $this->model->formatDateTime($data['thoi_gian_trinh_bay']);
        }
        
        if (!empty($data['thoi_gian_ket_thuc'])) {
            $data['thoi_gian_ket_thuc'] = $this->model->formatDateTime($data['thoi_gian_ket_thuc']);
        }
  
        // Kiểm tra xem mối quan hệ đã tồn tại chưa (nếu có thay đổi)
        if (!empty($data['su_kien_id']) && !empty($data['dien_gia_id']) && 
            ($data['su_kien_id'] != $existingRecord->getSuKienId() || $data['dien_gia_id'] != $existingRecord->getDienGiaId())) {
            if ($this->model->isRelationExists($data['su_kien_id'], $data['dien_gia_id'], $id)) {
                return redirect()->back()->withInput()->with('error', 'Diễn giả đã được thêm vào sự kiện này');
            }
        }
    
        // Chuẩn bị quy tắc validation cho cập nhật
        $this->model->prepareValidationRules('update', [$this->primary_key => $id]);
        
        // Kiểm tra dữ liệu
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        try {
            // Xử lý tài liệu đính kèm nếu có
            $files = $this->request->getFiles();
            if (!empty($files['tai_lieu_dinh_kem'])) {
                $uploadedFiles = $files['tai_lieu_dinh_kem'];
                // Chỉ cập nhật khi có tệp hợp lệ được tải lên
                if (is_array($uploadedFiles) && !empty($uploadedFiles[0]) && $uploadedFiles[0]->isValid()) {
                    $data['tai_lieu_dinh_kem'] = $this->uploadFiles($uploadedFiles);
                }
            } else {
                // Nếu không có tệp mới, bỏ qua trường này để tránh ghi đè thành NULL
                unset($data['tai_lieu_dinh_kem']);
            }
            
            // Lưu dữ liệu thông qua model
            if ($this->model->update($id, $data)) {
                $this->alert->set('success', 'Cập nhật ' . $this->title . ' thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                throw new \RuntimeException('Không thể cập nhật ' . $this->title);
            }
        } catch (\Exception $e) {
            log_message('error', 'Lỗi cập nhật sự kiện diễn giả: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật ' . $this->title . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Xóa mềm (chuyển vào thùng rác)
     */
    public function delete($id = null, $backToUrl = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Thực hiện xóa mềm thông qua model
        if ($this->model->delete($id)) {
            $this->alert->set('success', 'Đã xóa dữ liệu thành công', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa dữ liệu', true);
        }
        
        // Lấy URL trả về từ tham số truy vấn hoặc từ tham số đường dẫn
        $returnUrl = $this->request->getGet('return_url') ?? $backToUrl;
        
        // Xử lý URL chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Xóa nhiều template (chuyển vào thùng rác)
     */
    public function deleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn dữ liệu nào để xóa', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl);
        }

        $successCount = 0;
        
        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        foreach ($idArray as $id) {
            if ($this->model->delete($id)) {
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã chuyển $successCount dữ liệu vào thùng rác", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa dữ liệu', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Hiển thị danh sách đã xóa
     */
    public function listdeleted()
    {
        // Lấy và xử lý tham số tìm kiếm
        $params = $this->prepareSearchParams($this->request);
        $params = $this->processSearchParams($params);
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        $this->model->setSurroundCount(3);
        
        // Xây dựng tiêu chí và tùy chọn tìm kiếm
        $criteria = $this->buildSearchCriteria($params);
        $options = $this->buildSearchOptions($params);
        
        // Thiết lập cờ để lấy các bản ghi đã xóa
        $criteria['deleted'] = true;
        
        // Lấy dữ liệu đã xóa thông qua model
        $pageData = $this->model->searchDeleted($criteria, $options);
        
        // Lấy tổng số kết quả
        $total = $this->model->countDeletedSearchResults($criteria);
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath($this->module_name . '/listdeleted');
            $pager->setOnly($this->pager_only);
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Chuẩn bị dữ liệu cho view
        $viewData = $this->prepareViewData($this->module_name, $pageData, $pager, array_merge($params, ['total' => $total]));
        
        // Thêm danh sách sự kiện và diễn giả để hiển thị tên thay vì ID
        $viewData['suKienList'] = $this->suKienModel->findAll();
        $viewData['dienGiaList'] = $this->dienGiaModel->findAll();
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\listdeleted', $viewData);
    }
    
    /**
     * Khôi phục từ thùng rác
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form hoặc từ HTTP_REFERER
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        // Khôi phục bản ghi thông qua model
        try {
            // Sử dụng phương thức của BaseModel thay vì truy xuất trực tiếp
            if ($this->model->restore($id)) {
                $this->alert->set('success', 'Đã khôi phục dữ liệu từ thùng rác', true);
            } else {
                $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục dữ liệu', true);
            }
        } catch (\Exception $e) {
            $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục dữ liệu', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Khôi phục nhiều dữ liệu từ thùng rác
     */
    public function restoreMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn dữ liệu nào để khôi phục', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        
        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        foreach ($idArray as $id) {
            try {
                if ($this->model->restore($id)) {
                    $successCount++;
                }
            } catch (\Exception $e) {
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã khôi phục $successCount dữ liệu từ thùng rác", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể khôi phục dữ liệu', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn một bản ghi
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form hoặc từ HTTP_REFERER
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        // Xóa vĩnh viễn thông qua model
        try {
            if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
                $this->alert->set('success', 'Đã xóa vĩnh viễn dữ liệu', true);
            } else {
                $this->alert->set('danger', 'Có lỗi xảy ra khi xóa dữ liệu', true);
            }
        } catch (\Exception $e) {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa dữ liệu', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Cập nhật trạng thái tham gia
     */
    public function updateStatus($id = null, $status = null)
    {
        if (empty($id) || empty($status)) {
            $this->alert->set('danger', 'Thông tin không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        // Cập nhật trạng thái thông qua model
        if ($this->model->updateTrangThaiThamGia($id, $status)) {
            $this->alert->set('success', 'Cập nhật trạng thái thành công', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi cập nhật trạng thái', true);
        }
        
        // Lấy URL trở về
        $returnUrl = $this->request->getGet('return_url') ?? $this->request->getServer('HTTP_REFERER');
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Xuất danh sách tham gia sự kiện ra file Excel
     */
    public function exportExcel()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? $this->field_sort;
        $order = $this->request->getGet('order') ?? $this->field_order;

        $criteria = $this->prepareSearchCriteria($keyword, $status);
        $options = $this->prepareSearchOptions($sort, $order);
        $data = $this->getExportData($criteria, $options);
        $headers = $this->prepareExcelHeaders();

        $filters = [];
        if (!empty($keyword)) $filters['Từ khóa'] = $keyword;
        if (isset($status) && $status !== '') $filters['Trạng thái'] = $status == 1 ? 'Hoạt động' : 'Không hoạt động';
        if (!empty($sort)) $filters['Sắp xếp theo'] = $this->getSortText($sort, $order);

        $this->createExcelFile($data, $headers, $filters, $this->export_excel, false);
    }

    /**
     * Xuất danh sách tham gia sự kiện ra file PDF
     */
    public function exportPdf()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status'); 
        $sort = $this->request->getGet('sort') ?? $this->field_sort;
        $order = $this->request->getGet('order') ?? $this->field_order;

        $criteria = $this->prepareSearchCriteria($keyword, $status);
        $options = $this->prepareSearchOptions($sort, $order);
        $data = $this->getExportData($criteria, $options);

        $filters = [];
        if (!empty($keyword)) $filters['Từ khóa'] = $keyword;
        if (isset($status) && $status !== '') $filters['Trạng thái'] = $status == 1 ? 'Hoạt động' : 'Không hoạt động';  
        if (!empty($sort)) $filters['Sắp xếp theo'] = $this->getSortText($sort, $order);

        $this->createPdfFile($data, $filters, $this->export_excel, $this->export_excel_title);
    }

    /**
     * Xuất danh sách tham gia sự kiện đã xóa ra file PDF
     */
    public function exportDeletedPdf()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? $this->field_sort;
        $order = $this->request->getGet('order') ?? $this->field_order;

        $criteria = $this->prepareSearchCriteria($keyword, $status, true);
        $options = $this->prepareSearchOptions($sort, $order);
        $data = $this->getExportData($criteria, $options);

        $filters = [];
        if (!empty($keyword)) $filters['Từ khóa'] = $keyword;
        if (isset($status) && $status !== '') $filters['Trạng thái'] = $status == 1 ? 'Hoạt động' : 'Không hoạt động';      
        if (!empty($sort)) $filters['Sắp xếp theo'] = $this->getSortText($sort, $order);
        $filters['Trạng thái'] = 'Đã xóa';

        $this->createPdfFile($data, $filters, $this->export_excel_deleted, $this->export_excel_deleted_title);
    }

    /**
     * Xuất danh sách tham gia sự kiện đã xóa ra file Excel
     */
    public function exportDeletedExcel()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? $this->field_sort;
        $order = $this->request->getGet('order') ?? $this->field_order;

        $criteria = $this->prepareSearchCriteria($keyword, $status, true);
        $options = $this->prepareSearchOptions($sort, $order);
        $data = $this->getExportData($criteria, $options);
        $headers = $this->prepareExcelHeaders(true);

        $filters = [];
        if (!empty($keyword)) $filters['Từ khóa'] = $keyword;
        if (isset($status) && $status !== '') $filters['Trạng thái'] = $status == 1 ? 'Hoạt động' : 'Không hoạt động';  
        if (!empty($sort)) $filters['Sắp xếp theo'] = $this->getSortText($sort, $order);
        $filters['Trạng thái'] = 'Đã xóa';

        $this->createExcelFile($data, $headers, $filters, $this->export_excel_deleted, true);
    }

    /**
     * Lấy văn bản mô tả cho trường sắp xếp
     *
     * @param string $sort Trường sắp xếp
     * @param string $order Hướng sắp xếp
     * @return string Văn bản mô tả
     */
    protected function getSortText($sort, $order = 'ASC')
    {
        $sortFields = [
            'su_kien_dien_gia_id' => 'ID',
            'su_kien_id' => 'Sự kiện',
            'dien_gia_id' => 'Diễn giả',
            'thu_tu' => 'Thứ tự',
            'vai_tro' => 'Vai trò',
            'thoi_gian_trinh_bay' => 'Thời gian trình bày',
            'thoi_gian_ket_thuc' => 'Thời gian kết thúc',
            'thoi_luong_phut' => 'Thời lượng',
            'tieu_de_trinh_bay' => 'Tiêu đề',
            'trang_thai_tham_gia' => 'Trạng thái tham gia',
            'hien_thi_cong_khai' => 'Hiển thị công khai',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'deleted_at' => 'Ngày xóa'
        ];
        
        $orderText = $order == 'ASC' ? 'tăng dần' : 'giảm dần';
        $fieldText = isset($sortFields[$sort]) ? $sortFields[$sort] : $sort;
        
        return "$fieldText ($orderText)";
    }
}