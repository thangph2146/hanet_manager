<?php

namespace App\Modules\quanlybachoc\Controllers;

use App\Controllers\BaseController;
use App\Modules\quanlybachoc\Models\QuanLyBacHocModel;
use App\Libraries\Breadcrumb;
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
use App\Modules\quanlybachoc\Traits\ExportTrait;
use App\Modules\quanlybachoc\Traits\RelationTrait;

class QuanLyBacHoc extends BaseController
{
    use ResponseTrait;
    use ExportTrait;
    use RelationTrait;
    
    protected $model;
    protected $breadcrumb;
    protected $alert;
    protected $moduleUrl;
    protected $title;
    protected $module_name = 'quanlybachoc';
    protected $controller_name = 'QuanLyBacHoc';
    protected $masterScript;
    
    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');

        // Khởi tạo các thành phần cần thiết
        $this->model = new QuanLyBacHocModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url($this->module_name);
        $this->title = 'Bậc Học';
        
        // Khởi tạo thư viện MasterScript với module_name và module_name
        $this->masterScript = new \App\Modules\quanlybachoc\Libraries\MasterScript($this->module_name, $this->module_name);
        
        // Khởi tạo các model quan hệ
        $this->initializeRelationTrait();
    }
    
    /**
     * Hiển thị danh sách bậc học
     */
    public function index()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Danh sách', current_url());
        
        // Lấy và xử lý tham số tìm kiếm
        $params = $this->prepareSearchParams($this->request);
        $params = $this->processSearchParams($params);
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        $this->model->setSurroundCount(3);
        
        // Xây dựng tiêu chí và tùy chọn tìm kiếm
        $criteria = $this->buildSearchCriteria($params);
        $options = $this->buildSearchOptions($params);
        
        // Lấy dữ liệu bậc học và thông tin phân trang
        $pageData = $this->model->search($criteria, $options);
        // Lấy tổng số kết quả
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
            $pager->setRouteUrl($this->module_name);
            // Thêm tất cả các tham số cần giữ lại khi chuyển trang
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order', 'bac_hoc_id']);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Chuẩn bị dữ liệu cho view
        $viewData = $this->prepareViewData($this->module_name, $pageData, $pager, array_merge($params, ['total' => $total]));
        // Thêm module_name và masterScript vào viewData để sử dụng trong view
        $viewData['module_name'] = $this->module_name;
        $viewData['module_name'] = $this->module_name;
        $viewData['masterScript'] = $this->masterScript;
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\index', $viewData);
    }
    
    /**
     * Hiển thị form thêm mới
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Sử dụng prepareFormData để chuẩn bị dữ liệu cho form
        $viewData = $this->prepareFormData($this->module_name);
        
        // Thêm dữ liệu cho view
        $viewData['breadcrumb'] = $this->breadcrumb->render();
        $viewData['title'] = 'Thêm mới ' . $this->title;
        $viewData['validation'] = $this->validator;
        $viewData['moduleUrl'] = $this->moduleUrl;
        $viewData['errors'] = session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []);
        $viewData['action'] = site_url($this->module_name . '/create');
        $viewData['method'] = 'POST';
        $viewData['module_name'] = $this->module_name;
        $viewData['masterScript'] = $this->masterScript;
        
        return view('App\Modules\\' . $this->module_name . '\Views\new', $viewData);
    }
    
    /**
     * Xử lý thêm mới dữ liệu
     */
    public function create()
    {
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Loại bỏ bac_hoc_id khi thêm mới vì là trường auto_increment
        if (isset($data['bac_hoc_id'])) {
            unset($data['bac_hoc_id']);
        }
        
        // Chuẩn bị quy tắc validation cho thêm mới
        $this->model->prepareValidationRules('insert', $data);
        
        // Kiểm tra dữ liệu
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            // Lấy danh sách lỗi
            $errors = $this->validator->getErrors();
            
            // Ghi log chi tiết lỗi
            log_message('error', '[' . $this->controller_name . '::create] Lỗi validation: ' . json_encode($errors, JSON_UNESCAPED_UNICODE));
            
            // Hiển thị thông báo lỗi
            return redirect()->back()
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại các thông tin bắt buộc')
                ->with('errors', $errors);
        }
        
        try {
            // Lưu dữ liệu trực tiếp
            if ($this->model->insert($data)) {
                $this->alert->set('success', 'Thêm mới ' . $this->title . ' thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                // Nếu có lỗi từ model
                $modelErrors = $this->model->errors();
                if (!empty($modelErrors)) {
                    log_message('error', '[' . $this->controller_name . '::create] Lỗi model: ' . json_encode($modelErrors, JSON_UNESCAPED_UNICODE));
                    
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Có lỗi khi lưu dữ liệu')
                        ->with('errors', $modelErrors);
                }
                
                throw new \RuntimeException('Không thể thêm mới ' . $this->title);
            }
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::create] ' . $e->getMessage());
            
            // Nếu lỗi là Integrity Constraint Violation (ví dụ: trùng khóa)
            if (strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Dữ liệu đã tồn tại, vui lòng kiểm tra lại thông tin');
            }
            
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
            $this->alert->set('danger', 'ID bậc học không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Đảm bảo các model relationship được khởi tạo
        $this->initializeRelationTrait();
        
        // Lấy thông tin dữ liệu cơ bản
        $data = $this->model->find($id);
        
        if (empty($data)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu bậc học', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xử lý dữ liệu và nạp các quan hệ
        $processedData = $this->processData([$data]);
        $data = $processedData[0] ?? $data;
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chi tiết', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chi tiết ' . $this->title,
            'data' => $data,
            'moduleUrl' => $this->moduleUrl,
            'module_name' => $this->module_name,
            'module_name' => $this->module_name,
            'masterScript' => $this->masterScript
        ];
        
        return view('App\Modules\\' . $this->module_name . '\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID bậc học không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Sử dụng phương thức findWithRelations từ model
        $data = $this->model->findWithRelations($id);
        
        if (empty($data)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu bậc học', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chỉnh sửa', current_url());
        
        // Sử dụng prepareFormData để chuẩn bị dữ liệu cho form
        $viewData = $this->prepareFormData($this->module_name, $data);
        
        // Thêm dữ liệu cho view
        $viewData['breadcrumb'] = $this->breadcrumb->render();
        $viewData['title'] = 'Chỉnh sửa ' . $this->title;
        $viewData['validation'] = $this->validator;
        $viewData['moduleUrl'] = $this->moduleUrl;
        $viewData['errors'] = session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []);
        $viewData['action'] = site_url($this->module_name . '/update/' . $id);
        $viewData['method'] = 'POST';
        $viewData['module_name'] = $this->module_name;
        $viewData['masterScript'] = $this->masterScript;
        
        return view('App\Modules\\' . $this->module_name . '\Views\edit', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID bậc học không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin bậc học với relationship
        $existingRecord = $this->model->findWithRelations($id);
        
        if (empty($existingRecord)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu bậc học', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xác thực dữ liệu gửi lên
        $data = $this->request->getPost();
        
        // Xử lý thời gian điểm danh
        if (!empty($data['thoi_gian_diem_danh'])) {
            try {
                $time = Time::parse($data['thoi_gian_diem_danh']);
                $data['thoi_gian_diem_danh'] = $time->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                log_message('error', 'Lỗi xử lý thời gian điểm danh: ' . $e->getMessage());
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Thời gian điểm danh không hợp lệ');
            }
        }
    
        // Chuẩn bị quy tắc validation cho cập nhật
        $this->model->prepareValidationRules('update', $id);
        
        // Kiểm tra dữ liệu
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            // Lấy danh sách lỗi
            $errors = $this->validator->getErrors();
            
            // Ghi log chi tiết lỗi
            log_message('error', '[' . $this->controller_name . '::update] Lỗi validation: ' . json_encode($errors, JSON_UNESCAPED_UNICODE));
            
            // Hiển thị thông báo lỗi
            return redirect()->back()
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại các thông tin bắt buộc')
                ->with('errors', $errors);
        }
        
        try {
            // Cập nhật dữ liệu
            if ($this->model->update($id, $data)) {
                $this->alert->set('success', 'Cập nhật ' . $this->title . ' thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                // Nếu có lỗi từ model
                $modelErrors = $this->model->errors();
                if (!empty($modelErrors)) {
                    log_message('error', '[' . $this->controller_name . '::update] Lỗi model: ' . json_encode($modelErrors, JSON_UNESCAPED_UNICODE));
                    
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Có lỗi khi lưu dữ liệu')
                        ->with('errors', $modelErrors);
                }
                
                throw new \RuntimeException('Không thể cập nhật ' . $this->title);
            }
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::update] ' . $e->getMessage());
            
            // Nếu lỗi là Integrity Constraint Violation (ví dụ: trùng khóa)
            if (strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Dữ liệu đã tồn tại, vui lòng kiểm tra lại thông tin');
            }
            
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
            $this->alert->set('danger', 'ID bậc học không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Thực hiện xóa mềm (sử dụng deleted_at thay vì bin)
        if ($this->model->delete($id)) {
            $this->alert->set('success', 'Đã xóa dữ liệu bậc học thành công', true);
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
     * Hiển thị danh sách tham gia sự kiện đã xóa
     */
    public function listdeleted()
    {
        // Lấy và xử lý tham số tìm kiếm
        $params = $this->prepareSearchParams($this->request);
        $params = $this->processSearchParams($params);
        
        // Ghi đè sort mặc định cho trang list deleted
        $params['sort'] = $this->request->getGet('sort') ?? 'deleted_at';
        $params['order'] = $this->request->getGet('order') ?? 'DESC';
        
        // Log chi tiết URL và tham số
        log_message('debug', '[Controller:listdeleted] URL đầy đủ: ' . current_url() . '?' . http_build_query($_GET));
        log_message('debug', '[Controller:listdeleted] Tham số request: ' . json_encode($_GET));
        log_message('debug', '[Controller:listdeleted] Đã xử lý: page=' . $params['page'] . ', perPage=' . $params['perPage'] . 
            ', sort=' . $params['sort'] . ', order=' . $params['order'] . ', keyword=' . $params['keyword'] . 
            ', status=' . $params['status']);
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        $this->model->setSurroundCount(3);
        
        // Xây dựng tiêu chí và tùy chọn tìm kiếm
        $criteria = $this->buildSearchCriteria($params);
        $options = $this->buildSearchOptions($params);
        
        // Thêm điều kiện để chỉ lấy các bản ghi đã xóa
        $criteria['deleted'] = true;
        
        // Đảm bảo withDeleted được thiết lập
        $this->model->withDeleted();
        
        // Lấy dữ liệu tham gia sự kiện và thông tin phân trang
        $pageData = $this->model->search($criteria, $options);
        
        // Xử lý dữ liệu và nạp các quan hệ
        $pageData = $this->processData($pageData);
        
        // Lấy tổng số kết quả
        $total = $this->model->countSearchResults($criteria);
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager === null) {
            // Tạo pager mới nếu getPager() trả về null
            $pager = new \App\Modules\namhoc\Libraries\Pager(
                $total,
                $params['perPage'],
                $params['page']
            );
            $pager->setSurroundCount(3);
        }
        
        $pager->setPath($this->module_name . '/listdeleted');
        $pager->setOnly(['keyword', 'perPage', 'sort', 'order', 'status', 'bac_hoc_id']);
        $pager->setPerPage($params['perPage']);
        $pager->setCurrentPage($params['page']);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = $this->prepareViewData($this->module_name, $pageData, $pager, array_merge($params, ['total' => $total]));
        // Thêm module_name vào viewData để sử dụng trong view
        $viewData['module_name'] = $this->module_name;
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\listdeleted', $viewData);
    }
    
    /**
     * Khôi phục từ thùng rác
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID bậc học không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form hoặc từ HTTP_REFERER
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        log_message('debug', 'Restore - Return URL: ' . ($returnUrl ?? 'None'));
        
        // Khôi phục bản ghi bằng cách đặt deleted_at thành NULL
        if ($this->model->update($id, ['deleted_at' => null])) {
            $this->alert->set('success', 'Đã khôi phục dữ liệu từ thùng rác', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục dữ liệu', true);
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
        log_message('debug', 'PermanentDelete - Return URL: ' . ($returnUrl ?? 'None'));
        
        if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
            $this->alert->set('success', 'Đã xóa vĩnh viễn dữ liệu', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa dữ liệu', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Tìm kiếm template
     */
    public function search()
    {
        // Lấy dữ liệu từ request
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        
        // Chuẩn bị tiêu chí tìm kiếm
        $criteria = [
            'keyword' => $keyword
        ];
        
        // Thêm bộ lọc nếu có
        $filters = [];
        if ($status !== null && $status !== '') {
            $filters['status'] = (int)$status;
        }
        
        // Chỉ thêm vào criteria nếu có bộ lọc
        if (!empty($filters)) {
            $criteria['filters'] = $filters;
        }
        
        // Thiết lập tùy chọn
        $options = [
            'sort_field' => $this->request->getGet('sort') ?? 'updated_at',
            'sort_direction' => $this->request->getGet('order') ?? 'DESC',
            'limit' => (int)($this->request->getGet('length') ?? 10),
            'offset' => (int)($this->request->getGet('start') ?? 0)
        ];
        
        // Thực hiện tìm kiếm
        $results = $this->model->search($criteria, $options);
        
        // Tổng số kết quả
        $totalRecords = $this->model->countSearchResults($criteria);
        
        // Nếu yêu cầu là AJAX (từ DataTables)
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'draw' => $this->request->getGet('draw'),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalRecords,
                'data' => $results
            ]);
        }
        
        // Nếu không phải AJAX, hiển thị trang tìm kiếm
        $viewData = [
            'title' => 'Tìm kiếm ' . $this->title,
            'data' => $results,
            'pager' => $this->model->pager,
            'keyword' => $keyword,
            'filters' => $filters,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\\' . $this->module_name . '\Views\search', $viewData);
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
        
        // Log để debug
        log_message('debug', 'DeleteMultiple - POST data: ' . json_encode($_POST));
        log_message('debug', 'DeleteMultiple - Selected Items: ' . (is_array($selectedItems) ? json_encode($selectedItems) : $selectedItems));
        log_message('debug', 'DeleteMultiple - Return URL: ' . ($returnUrl ?? 'None'));
        
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
     * Xử lý URL trả về, loại bỏ domain nếu cần
     * 
     * @param string|null $returnUrl URL trả về
     * @return string URL đích đã được xử lý
     */
    private function processReturnUrl($returnUrl)
    {
        // Mặc định là URL module
        $redirectUrl = $this->moduleUrl . '/listdeleted';
        
        if (!empty($returnUrl)) {
            // Giải mã URL
            $decodedUrl = urldecode($returnUrl);
            log_message('debug', 'Return URL sau khi giải mã: ' . $decodedUrl);
            
            // Kiểm tra nếu URL chứa domain, chỉ lấy phần path và query
            if (strpos($decodedUrl, 'http') === 0) {
                $urlParts = parse_url($decodedUrl);
                $path = $urlParts['path'] ?? '';
                $query = isset($urlParts['query']) ? '?' . $urlParts['query'] : '';
                $decodedUrl = $path . $query;
            }
            
            // Xử lý đường dẫn tương đối
            if (strpos($decodedUrl, '/') === 0) {
                $decodedUrl = substr($decodedUrl, 1);
            }
            
            // Log cho debug
            log_message('debug', 'URL sau khi xử lý: ' . $decodedUrl);
            
            // Cập nhật URL đích
            $redirectUrl = $decodedUrl;
        }
        
        return $redirectUrl;
    }
    
    /**
     * Thay đổi trạng thái nhiều template
     */
    public function statusMultiple()
    {
        // Lấy dữ liệu từ POST request
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        // Log thông tin chi tiết để debug
        log_message('debug', '[statusMultiple] - Request Method: ' . $this->request->getMethod());
        log_message('debug', '[statusMultiple] - POST data: ' . json_encode($_POST));
        log_message('debug', '[statusMultiple] - Selected Items: ' . (is_array($selectedItems) ? json_encode($selectedItems) : $selectedItems));
        log_message('debug', '[statusMultiple] - Return URL: ' . ($returnUrl ?? 'None'));
        log_message('debug', '[statusMultiple] - CSRF: ' . json_encode($this->request->getPost(csrf_token()))); 
        log_message('debug', '[statusMultiple] - Server variables: ' . json_encode($_SERVER));
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn dữ liệu nào để thay đổi trạng thái', true);
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl);
        }
        
        // Khởi tạo biến đếm kết quả
        $successCount = 0;
        $errorCount = 0;
        $errors = [];
        
        // Xử lý từng ID được chọn
        foreach ($selectedItems as $id) {
            try {
                // Lấy thông tin hiện tại của bản ghi
                $currentRecord = $this->model->find($id);
                
                if (!$currentRecord) {
                    $errorCount++;
                    $errors[] = "Không tìm thấy bản ghi với ID: $id";
                    continue;
                }
                
                // Đổi trạng thái ngược lại (0 -> 1 hoặc 1 -> 0)
                $newStatus = $currentRecord->status == '1' ? '0' : '1';
                
                // Cập nhật trạng thái
                $updateResult = $this->model->update($id, ['status' => $newStatus]);
                
                if ($updateResult) {
                    $successCount++;
                    log_message('debug', "[statusMultiple] - Successfully updated status for ID: $id to: $newStatus");
                } else {
                    $errorCount++;
                    $errors[] = "Lỗi khi cập nhật trạng thái cho ID: $id";
                    log_message('error', "[statusMultiple] - Failed to update status for ID: $id");
                }
            } catch (\Exception $e) {
                $errorCount++;
                $errors[] = "Lỗi khi xử lý ID: $id - " . $e->getMessage();
                log_message('error', "[statusMultiple] - Error processing ID: $id - " . $e->getMessage());
            }
        }
        
        // Log kết quả cuối cùng
        log_message('debug', "[statusMultiple] - Final Results - Success: $successCount, Errors: $errorCount");
        if (!empty($errors)) {
            log_message('error', "[statusMultiple] - Error Details: " . json_encode($errors));
        }
        
        // Thiết lập thông báo kết quả
        if ($successCount > 0) {
            $message = "Đã cập nhật thành công trạng thái cho $successCount mục";
            if ($errorCount > 0) {
                $message .= " (có $errorCount mục lỗi)";
            }
            $this->alert->set('success', $message, true);
        } else {
            $this->alert->set('error', 'Không thể cập nhật trạng thái cho bất kỳ mục nào', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        log_message('debug', "[statusMultiple] - Redirecting to: " . ($redirectUrl ?: $this->moduleUrl));
        
        return redirect()->to($redirectUrl ?: $this->moduleUrl);
    }
    
    /**
     * Khôi phục nhiều bản ghi từ thùng rác
     */
    public function restoreMultiple()
    {
        // Lấy các ID được chọn từ form và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        // Log thông tin để debug
        log_message('debug', 'RestoreMultiple - POST data: ' . json_encode($_POST));
        log_message('debug', 'RestoreMultiple - Selected Items: ' . (is_array($selectedItems) ? json_encode($selectedItems) : $selectedItems));
        log_message('debug', 'RestoreMultiple - Return URL: ' . ($returnUrl ?? 'None'));
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn dữ liệu nào để khôi phục', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        $failCount = 0;
        $errorMessages = [];
        
        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        // Log thông tin mảng ID để debug
        log_message('debug', 'RestoreMultiple - ID Array: ' . json_encode($idArray));
        log_message('debug', 'RestoreMultiple - Số lượng ID cần khôi phục: ' . count($idArray));
        
        foreach ($idArray as $id) {
            log_message('debug', 'RestoreMultiple - Đang khôi phục ID: ' . $id);
            
            try {
                // Khôi phục bằng cách đặt deleted_at thành NULL
                if ($this->model->update($id, ['deleted_at' => null])) {
                    $successCount++;
                    log_message('debug', 'RestoreMultiple - Khôi phục thành công ID: ' . $id);
                } else {
                    $failCount++;
                    $errors = $this->model->errors() ? json_encode($this->model->errors()) : 'Unknown error';
                    log_message('error', 'RestoreMultiple - Lỗi khôi phục dữ liệu ID: ' . $id . ', Errors: ' . $errors);
                    $errorMessages[] = "Lỗi khôi phục dữ liệu ID: {$id}";
                }
            } catch (\Exception $e) {
                $failCount++;
                log_message('error', 'RestoreMultiple - Ngoại lệ khi khôi phục ID: ' . $id . ', Error: ' . $e->getMessage());
                $errorMessages[] = "Lỗi khôi phục ID: {$id} - " . $e->getMessage();
            }
        }
        
        // Tổng kết kết quả
        log_message('info', "RestoreMultiple - Kết quả: Thành công: {$successCount}, Thất bại: {$failCount}");
        
        if ($successCount > 0) {
            if ($failCount > 0) {
                $this->alert->set('warning', "Đã khôi phục {$successCount} dữ liệu, nhưng có {$failCount} dữ liệu không thể khôi phục", true);
            } else {
                $this->alert->set('success', "Đã khôi phục {$successCount} dữ liệu từ thùng rác", true);
            }
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể khôi phục dữ liệu nào', true);
            // Log chi tiết lỗi
            if (!empty($errorMessages)) {
                log_message('error', 'RestoreMultiple - Chi tiết lỗi: ' . json_encode($errorMessages));
            }
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn nhiều bản ghi
     */
    public function permanentDeleteMultiple()
    {
        // Lấy các ID được chọn từ form và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        // Log thông tin để debug
        log_message('debug', 'PermanentDeleteMultiple - POST data: ' . json_encode($_POST));
        log_message('debug', 'PermanentDeleteMultiple - Selected Items: ' . (is_array($selectedItems) ? json_encode($selectedItems) : $selectedItems));
        log_message('debug', 'PermanentDeleteMultiple - Return URL: ' . ($returnUrl ?? 'None'));
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn dữ liệu nào để xóa vĩnh viễn', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        
        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        // Log thông tin mảng ID để debug
        log_message('debug', 'PermanentDeleteMultiple - ID Array: ' . json_encode($idArray));
        log_message('debug', 'PermanentDeleteMultiple - Số lượng ID cần xóa vĩnh viễn: ' . count($idArray));
        
        foreach ($idArray as $id) {
            log_message('debug', 'PermanentDeleteMultiple - Đang xóa vĩnh viễn ID: ' . $id);
            
            if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
                $successCount++;
                log_message('debug', 'PermanentDeleteMultiple - Xóa vĩnh viễn thành công ID: ' . $id);
            } else {
                log_message('error', 'PermanentDeleteMultiple - Lỗi xóa vĩnh viễn ID: ' . $id);
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã xóa vĩnh viễn $successCount dữ liệu", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa dữ liệu', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xuất danh sách tham gia sự kiện ra file Excel
     */
    public function exportExcel()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'bac_hoc_id';
        $order = $this->request->getGet('order') ?? 'ASC';

        $criteria = $this->prepareSearchCriteria($keyword, $status);
        $options = $this->prepareSearchOptions($sort, $order);
        $data = $this->getExportData($criteria, $options);
        $headers = $this->prepareExcelHeaders();

        $filters = [];
        if (!empty($keyword)) $filters['Từ khóa'] = $keyword;
        if (isset($status) && $status !== '') $filters['Trạng thái'] = $status == 1 ? 'Hoạt động' : 'Không hoạt động';
        if (!empty($sort)) $filters['Sắp xếp theo'] = $this->getSortText($sort, $order);

        $this->createExcelFile($data, $headers, $filters, 'danh_sach_bac_hoc');
    }

    /**
     * Xuất danh sách tham gia sự kiện ra file PDF
     */
    public function exportPdf()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'bac_hoc_id';
        $order = $this->request->getGet('order') ?? 'ASC';

        $criteria = $this->prepareSearchCriteria($keyword, $status);
        $options = $this->prepareSearchOptions($sort, $order);
        $data = $this->getExportData($criteria, $options);

        $filters = [];
        if (!empty($keyword)) $filters['Từ khóa'] = $keyword;
        if (isset($status) && $status !== '') $filters['Trạng thái'] = $status == 1 ? 'Hoạt động' : 'Không hoạt động';
        if (!empty($sort)) $filters['Sắp xếp theo'] = $this->getSortText($sort, $order);

        $this->createPdfFile($data, $filters, 'DANH SÁCH BẬC HỌC', 'danh_sach_bac_hoc');
    }

    /**
     * Xuất danh sách tham gia sự kiện đã xóa ra file PDF
     */
    public function exportDeletedPdf()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'deleted_at';
        $order = $this->request->getGet('order') ?? 'ASC';

        $criteria = $this->prepareSearchCriteria($keyword, $status, true);
        $options = $this->prepareSearchOptions($sort, $order);
        $data = $this->getExportData($criteria, $options);

        $filters = [];
        if (!empty($keyword)) $filters['Từ khóa'] = $keyword;
        if (isset($status) && $status !== '') $filters['Trạng thái'] = $status == 1 ? 'Hoạt động' : 'Không hoạt động';  
        if (!empty($sort)) $filters['Sắp xếp theo'] = $this->getSortText($sort, $order);
        $filters['Trạng thái'] = 'Đã xóa';

        $this->createPdfFile($data, $filters, 'DANH SÁCH BẬC HỌC ĐÃ XÓA', 'danh_sach_bac_hoc_da_xoa', true);
    }

    /**
     * Xuất danh sách tham gia sự kiện đã xóa ra file Excel
     */
    public function exportDeletedExcel()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'deleted_at';
        $order = $this->request->getGet('order') ?? 'DESC';

        $criteria = $this->prepareSearchCriteria($keyword, $status, true);
        $options = $this->prepareSearchOptions($sort, $order);
        $data = $this->getExportData($criteria, $options);
        $headers = $this->prepareExcelHeaders(true);

        $filters = [];
        if (!empty($keyword)) $filters['Từ khóa'] = $keyword;
        if (isset($status) && $status !== '') $filters['Trạng thái'] = $status == 1 ? 'Hoạt động' : 'Không hoạt động';  
        if (!empty($sort)) $filters['Sắp xếp theo'] = $this->getSortText($sort, $order);
        $filters['Trạng thái'] = 'Đã xóa';

        $this->createExcelFile($data, $headers, $filters, 'danh_sach_bac_hoc_da_xoa', true);
    }

    /**
     * Lấy text cho sắp xếp
     */
    protected function getSortText($sort, $order)
    {
        $sortFields = [
            'bac_hoc_id' => 'ID',
            'ten_bac_hoc' => 'Tên bậc học',
            'ma_bac_hoc' => 'Mã bậc học',
            'status' => 'Trạng thái',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'deleted_at' => 'Ngày xóa',
        ];

        $field = $sortFields[$sort] ?? $sort;
        return "$field (" . ($order === 'DESC' ? 'Giảm dần' : 'Tăng dần') . ")";
    }

    // Thêm vào phương thức này để hỗ trợ tìm kiếm các bản ghi đã xóa
    public function searchDeleted(array $criteria = [], array $options = [])
    {
        // Đảm bảo withDeleted được thiết lập
        $this->model->withDeleted();
        
        // Thêm điều kiện để chỉ lấy các bản ghi đã xóa
        $criteria['deleted'] = true;
        
        // Sử dụng phương thức search hiện tại với tham số đã sửa đổi
        return $this->model->search($criteria, $options);
    }
    
    // Đếm số lượng bản ghi đã xóa theo tiêu chí tìm kiếm
    public function countDeletedResults(array $criteria = [])
    {
        // Đảm bảo withDeleted được thiết lập
        $this->model->withDeleted();
        
        // Thêm điều kiện để chỉ lấy các bản ghi đã xóa
        $criteria['deleted'] = true;
        
        // Sử dụng phương thức countSearchResults hiện tại với tham số đã sửa đổi
        return $this->model->countSearchResults($criteria);
    }
} 