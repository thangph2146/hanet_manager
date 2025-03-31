<?php

namespace App\Libraries;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;

/**
 * Controller cơ sở cho các module
 * 
 * Cung cấp các phương thức chung để xử lý các chức năng CRUD và xuất dữ liệu
 * dựa trên thư viện Modules
 */
class ModuleController extends BaseController
{
    use ResponseTrait;
    
    /**
     * Thư viện Modules
     *
     * @var \App\Libraries\Modules
     */
    protected $moduleService;
    
    /**
     * Model của module
     *
     * @var \App\Models\BaseModel
     */
    protected $model;
    
    /**
     * URL cơ sở của module
     * Ví dụ: base_url('admin/bachoc')
     *
     * @var string
     */
    protected $moduleUrl;
    
    /**
     * Tiêu đề của module
     * Ví dụ: 'Bậc Học'
     *
     * @var string
     */
    protected $title;
    
    /**
     * Tên module
     * Ví dụ: 'bachoc'
     *
     * @var string
     */
    protected $module_name;
    
    /**
     * Tên controller
     * Ví dụ: 'BacHoc'
     * 
     * @var string
     */
    protected $controller_name;
    
    /**
     * Đường dẫn route URL của module
     * Ví dụ: 'admin/bachoc'
     *
     * @var string
     */
    protected $route_url;
    
    /**
     * Constructor
     * 
     * Khởi tạo thư viện Modules và các thuộc tính cần thiết
     */
    public function __construct()
    {
        // Khởi tạo phiên làm việc
        $this->session = service('session');
        
        // Khởi tạo các thuộc tính module
        if (empty($this->module_name)) {
            $namespace = get_class($this);
            $parts = explode('\\', $namespace);
            if (count($parts) >= 3 && $parts[1] == 'Modules') {
                $this->module_name = strtolower($parts[2]);
            } else {
                $this->module_name = 'core';
            }
        }
        
        if (empty($this->controller_name)) {
            $class = get_class($this);
            $parts = explode('\\', $class);
            $this->controller_name = end($parts);
        }
        
        if (empty($this->route_url)) {
            $this->route_url = 'admin/' . $this->module_name;
        }
        
        if (empty($this->moduleUrl)) {
            $this->moduleUrl = base_url($this->route_url);
        }
        
        // Khởi tạo thư viện Modules
        $this->moduleService = new Modules([
            'module_name' => $this->module_name,
            'controller_name' => $this->controller_name,
            'route_url' => $this->route_url,
            'moduleUrl' => $this->moduleUrl,
            'title' => $this->title
        ]);
        
        // Khởi tạo các thành phần khác
        $this->initializeComponents();
    }
    
    /**
     * Khởi tạo các thành phần cần thiết
     * 
     * Override phương thức này nếu cần thêm các model khác
     */
    protected function initializeComponents()
    {
        // Được triển khai bởi lớp con
    }
    
    /**
     * Chuẩn bị tham số tìm kiếm từ request
     * 
     * @param object $request Request
     * @return array Tham số tìm kiếm đã xử lý
     */
    protected function prepareSearchParams($request)
    {
        return $this->moduleService->prepareSearchParams($request);
    }
    
    /**
     * Xử lý tham số tìm kiếm
     * 
     * @param array $params Tham số tìm kiếm
     * @return array Tham số tìm kiếm đã xử lý
     */
    protected function processSearchParams($params)
    {
        return $params;
    }
    
    /**
     * Xây dựng tiêu chí tìm kiếm
     * 
     * @param array $params Tham số tìm kiếm
     * @return array Tiêu chí tìm kiếm
     */
    protected function buildSearchCriteria($params)
    {
        return $this->moduleService->buildSearchCriteria($params);
    }
    
    /**
     * Xây dựng tùy chọn tìm kiếm
     * 
     * @param array $params Tham số tìm kiếm
     * @return array Tùy chọn tìm kiếm
     */
    protected function buildSearchOptions($params)
    {
        return $this->moduleService->buildSearchOptions($params);
    }
    
    /**
     * Xử lý dữ liệu module trước khi hiển thị
     * 
     * @param array $data Dữ liệu
     * @return array Dữ liệu đã xử lý
     */
    protected function processData($data)
    {
        return $this->moduleService->processData($data);
    }
    
    /**
     * Chuẩn bị dữ liệu cho view
     * 
     * @param string $module_name Tên module
     * @param array $data Dữ liệu
     * @param object $pager Đối tượng phân trang
     * @param array $params Tham số bổ sung
     * @return array Dữ liệu cho view
     */
    protected function prepareViewData($module_name, $data, $pager, $params)
    {
        return $this->moduleService->prepareViewData($data, $pager, $params);
    }
    
    /**
     * Chuẩn bị dữ liệu cho form
     * 
     * @param string $module_name Tên module
     * @param object $data Dữ liệu (nếu có)
     * @return array Dữ liệu cho form
     */
    protected function prepareFormData($module_name, $data = null)
    {
        return $this->moduleService->prepareFormData($data);
    }
    
    /**
     * Xử lý URL trả về
     * 
     * @param string $returnUrl URL trả về
     * @return string URL đã xử lý
     */
    protected function processReturnUrl($returnUrl)
    {
        return $this->moduleService->processReturnUrl($returnUrl);
    }
    
    /**
     * Trang danh sách module
     * 
     * @return string
     */
    public function index()
    {
        // Cập nhật breadcrumb
        $this->moduleService->breadcrumb->add('Danh sách', current_url());
        
        // Lấy và xử lý tham số tìm kiếm
        $params = $this->prepareSearchParams($this->request);
        $params = $this->processSearchParams($params);
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        if (method_exists($this->model, 'setSurroundCount')) {
            $this->model->setSurroundCount(3);
        }
        
        // Xây dựng tiêu chí và tùy chọn tìm kiếm
        $criteria = $this->buildSearchCriteria($params);
        $options = $this->buildSearchOptions($params);
        
        // Lấy dữ liệu và thông tin phân trang
        $pageData = $this->model->search($criteria, $options);
        
        // Lấy tổng số kết quả
        $pager = method_exists($this->model, 'getPager') ? $this->model->getPager() : null;
        $total = $pager ? $pager->getTotal() : $this->model->countSearchResults($criteria);
        
        // Nếu trang hiện tại lớn hơn tổng số trang, điều hướng về trang cuối cùng
        $pageCount = ceil($total / $params['perPage']);
        if ($total > 0 && $params['page'] > $pageCount) {
            // Tạo URL mới với trang cuối cùng
            $redirectParams = $_GET;
            $redirectParams['page'] = $pageCount;
            $redirectUrl = site_url($this->route_url) . '?' . http_build_query($redirectParams);
            
            // Chuyển hướng đến trang cuối cùng
            return redirect()->to($redirectUrl);
        }
        
        // Lấy pager từ model và thiết lập các tham số
        if ($pager !== null) {
            $pager->setPath($this->route_url);
            $pager->setRouteUrl($this->route_url);
            // Thêm tất cả các tham số cần giữ lại khi chuyển trang
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order']);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Chuẩn bị dữ liệu cho view
        $viewData = $this->prepareViewData($this->module_name, $pageData, $pager, array_merge($params, ['total' => $total]));
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\index', $viewData);
    }
    
    /**
     * Hiển thị form thêm mới
     * 
     * @return string
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->moduleService->breadcrumb->add('Thêm mới', current_url());
        
        // Chuẩn bị dữ liệu cho form
        $viewData = $this->prepareFormData($this->module_name);
        
        // Bổ sung thông tin
        $viewData['action'] = site_url($this->route_url . '/create');
        $viewData['method'] = 'POST';
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\new', $viewData);
    }
    
    /**
     * Xử lý thêm mới dữ liệu
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function create()
    {
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Chuẩn bị quy tắc validation
        if (method_exists($this->model, 'prepareValidationRules')) {
            $this->model->prepareValidationRules('insert');
        }
        
        // Kiểm tra dữ liệu
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        try {
            // Lưu dữ liệu
            if ($this->model->insert($data)) {
                $this->moduleService->alert->set('success', 'Thêm mới ' . $this->title . ' thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                throw new \RuntimeException('Không thể thêm mới ' . $this->title);
            }
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::create] ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm mới ' . $this->title);
        }
    }
    
    /**
     * Hiển thị chi tiết
     * 
     * @param int $id ID bản ghi
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function view($id = null)
    {
        if (empty($id)) {
            $this->moduleService->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin dữ liệu
        $data = $this->model->find($id);
        
        if (empty($data)) {
            $this->moduleService->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xử lý dữ liệu
        $processedData = $this->processData([$data]);
        $data = $processedData[0] ?? $data;
        
        // Cập nhật breadcrumb
        $this->moduleService->breadcrumb->add('Chi tiết', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = $this->prepareFormData($this->module_name, $data);
        $viewData['data'] = $data;
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     * 
     * @param int $id ID bản ghi
     * @return string|\CodeIgniter\HTTP\RedirectResponse
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            $this->moduleService->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin dữ liệu
        $data = method_exists($this->model, 'findWithRelations') ? 
            $this->model->findWithRelations($id) : 
            $this->model->find($id);
        
        if (empty($data)) {
            $this->moduleService->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->moduleService->breadcrumb->add('Chỉnh sửa', current_url());
        
        // Chuẩn bị dữ liệu cho form
        $viewData = $this->prepareFormData($this->module_name, $data);
        
        // Bổ sung thông tin
        $viewData['action'] = site_url($this->route_url . '/update/' . $id);
        $viewData['method'] = 'POST';
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\edit', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     * 
     * @param int $id ID bản ghi
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->moduleService->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin dữ liệu hiện tại
        $existingRecord = method_exists($this->model, 'findWithRelations') ? 
            $this->model->findWithRelations($id) : 
            $this->model->find($id);
        
        if (empty($existingRecord)) {
            $this->moduleService->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Chuẩn bị quy tắc validation
        if (method_exists($this->model, 'prepareValidationRules')) {
            $this->model->prepareValidationRules('update', $id);
        }
        
        // Kiểm tra dữ liệu
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        try {
            // Cập nhật dữ liệu
            if ($this->model->update($id, $data)) {
                $this->moduleService->alert->set('success', 'Cập nhật ' . $this->title . ' thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                throw new \RuntimeException('Không thể cập nhật ' . $this->title);
            }
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::update] ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật ' . $this->title);
        }
    }
    
    /**
     * Xóa dữ liệu (chuyển vào thùng rác)
     * 
     * @param int $id ID bản ghi
     * @param string $backToUrl URL trả về sau khi xóa
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function delete($id = null, $backToUrl = null)
    {
        if (empty($id)) {
            $this->moduleService->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Thực hiện xóa mềm
        if ($this->model->delete($id)) {
            $this->moduleService->alert->set('success', 'Đã xóa dữ liệu thành công', true);
        } else {
            $this->moduleService->alert->set('danger', 'Có lỗi xảy ra khi xóa dữ liệu', true);
        }
        
        // Lấy URL trả về từ tham số truy vấn hoặc từ tham số đường dẫn
        $returnUrl = $this->request->getGet('return_url') ?? $backToUrl;
        
        // Xử lý URL chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Hiển thị danh sách đã xóa
     * 
     * @return string
     */
    public function listdeleted()
    {
        // Cập nhật breadcrumb
        $this->moduleService->breadcrumb->add('Thùng rác', current_url());
        
        // Lấy và xử lý tham số tìm kiếm
        $params = $this->prepareSearchParams($this->request);
        $params = $this->processSearchParams($params);
        
        // Ghi đè sort mặc định cho trang list deleted
        $params['sort'] = $this->request->getGet('sort') ?? 'deleted_at';
        $params['order'] = $this->request->getGet('order') ?? 'DESC';
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        if (method_exists($this->model, 'setSurroundCount')) {
            $this->model->setSurroundCount(3);
        }
        
        // Xây dựng tiêu chí và tùy chọn tìm kiếm
        $criteria = $this->buildSearchCriteria($params);
        $options = $this->buildSearchOptions($params);
        
        // Thêm điều kiện để chỉ lấy các bản ghi đã xóa
        $criteria['deleted'] = true;
        
        // Đảm bảo withDeleted được thiết lập
        $this->model->withDeleted();
        
        // Lấy dữ liệu và thông tin phân trang
        $pageData = method_exists($this->model, 'searchDeleted') ? 
            $this->model->searchDeleted($criteria, $options) : 
            $this->model->search($criteria, $options);
        
        // Xử lý dữ liệu
        $pageData = $this->processData($pageData);
        
        // Lấy tổng số kết quả
        $totalMethod = method_exists($this->model, 'countDeletedResults') ? 
            'countDeletedResults' : 'countSearchResults';
        $total = $this->model->$totalMethod($criteria);
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = method_exists($this->model, 'getPager') ? $this->model->getPager() : null;
        if ($pager === null) {
            // Tạo pager mới nếu cần
            $pagerClass = "\\App\\Modules\\{$this->module_name}\\Libraries\\Pager";
            if (class_exists($pagerClass)) {
                $pager = new $pagerClass($total, $params['perPage'], $params['page']);
                $pager->setSurroundCount(3);
            }
        }
        
        if ($pager !== null) {
            $pager->setPath($this->route_url . '/listdeleted');
            $pager->setOnly(['keyword', 'perPage', 'sort', 'order', 'status']);
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Chuẩn bị dữ liệu cho view
        $viewData = $this->prepareViewData($this->module_name, $pageData, $pager, array_merge($params, ['total' => $total]));
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\listdeleted', $viewData);
    }
    
    /**
     * Khôi phục dữ liệu từ thùng rác
     * 
     * @param int $id ID bản ghi
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->moduleService->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        // Khôi phục bản ghi
        if (method_exists($this->model, 'restore')) {
            $result = $this->model->restore($id);
        } else {
            $result = $this->model->update($id, ['deleted_at' => null]);
        }
        
        if ($result) {
            $this->moduleService->alert->set('success', 'Đã khôi phục dữ liệu từ thùng rác', true);
        } else {
            $this->moduleService->alert->set('danger', 'Có lỗi xảy ra khi khôi phục dữ liệu', true);
        }
        
        // Chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn dữ liệu
     * 
     * @param int $id ID bản ghi
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            $this->moduleService->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        // Xóa vĩnh viễn bản ghi
        if ($this->model->delete($id, true)) {
            $this->moduleService->alert->set('success', 'Đã xóa vĩnh viễn dữ liệu', true);
        } else {
            $this->moduleService->alert->set('danger', 'Có lỗi xảy ra khi xóa dữ liệu', true);
        }
        
        // Chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa nhiều dữ liệu (chuyển vào thùng rác)
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function deleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedItems)) {
            $this->moduleService->alert->set('warning', 'Chưa chọn dữ liệu nào để xóa', true);
            
            // Chuyển hướng
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
            $this->moduleService->alert->set('success', "Đã chuyển $successCount dữ liệu vào thùng rác", true);
        } else {
            $this->moduleService->alert->set('danger', 'Có lỗi xảy ra, không thể xóa dữ liệu', true);
        }
        
        // Chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Thay đổi trạng thái nhiều dữ liệu
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function statusMultiple()
    {
        // Lấy dữ liệu từ POST request
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        if (empty($selectedItems)) {
            $this->moduleService->alert->set('warning', 'Chưa chọn dữ liệu nào để thay đổi trạng thái', true);
            // Chuyển hướng
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl);
        }
        
        // Khởi tạo biến đếm kết quả
        $successCount = 0;
        $errorCount = 0;
        
        // Xử lý từng ID được chọn
        foreach ($selectedItems as $id) {
            try {
                // Lấy thông tin hiện tại của bản ghi
                $currentRecord = $this->model->find($id);
                
                if (!$currentRecord) {
                    $errorCount++;
                    continue;
                }
                
                // Đổi trạng thái ngược lại (0 -> 1 hoặc 1 -> 0)
                $newStatus = $currentRecord->status == '1' ? '0' : '1';
                
                // Cập nhật trạng thái
                $updateResult = $this->model->update($id, ['status' => $newStatus]);
                
                if ($updateResult) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
                log_message('error', "[statusMultiple] - Error processing ID: $id - " . $e->getMessage());
            }
        }
        
        // Thiết lập thông báo kết quả
        if ($successCount > 0) {
            $message = "Đã cập nhật thành công trạng thái cho $successCount mục";
            if ($errorCount > 0) {
                $message .= " (có $errorCount mục lỗi)";
            }
            $this->moduleService->alert->set('success', $message, true);
        } else {
            $this->moduleService->alert->set('error', 'Không thể cập nhật trạng thái cho bất kỳ mục nào', true);
        }
        
        // Chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl);
    }
    
    /**
     * Khôi phục nhiều dữ liệu từ thùng rác
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function restoreMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        if (empty($selectedItems)) {
            $this->moduleService->alert->set('warning', 'Chưa chọn dữ liệu nào để khôi phục', true);
            
            // Chuyển hướng
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        $failCount = 0;
        
        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        foreach ($idArray as $id) {
            try {
                // Khôi phục bản ghi
                if (method_exists($this->model, 'restore')) {
                    $result = $this->model->restore($id);
                } else {
                    $result = $this->model->update($id, ['deleted_at' => null]);
                }
                
                if ($result) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            } catch (\Exception $e) {
                $failCount++;
                log_message('error', 'RestoreMultiple - Ngoại lệ khi khôi phục ID: ' . $id . ', Error: ' . $e->getMessage());
            }
        }
        
        // Tổng kết kết quả
        if ($successCount > 0) {
            if ($failCount > 0) {
                $this->moduleService->alert->set('warning', "Đã khôi phục {$successCount} dữ liệu, nhưng có {$failCount} dữ liệu không thể khôi phục", true);
            } else {
                $this->moduleService->alert->set('success', "Đã khôi phục {$successCount} dữ liệu từ thùng rác", true);
            }
        } else {
            $this->moduleService->alert->set('danger', 'Có lỗi xảy ra, không thể khôi phục dữ liệu nào', true);
        }
        
        // Chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn nhiều dữ liệu
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function permanentDeleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        if (empty($selectedItems)) {
            $this->moduleService->alert->set('warning', 'Chưa chọn dữ liệu nào để xóa vĩnh viễn', true);
            
            // Chuyển hướng
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        
        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        foreach ($idArray as $id) {
            if ($this->model->delete($id, true)) {
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $this->moduleService->alert->set('success', "Đã xóa vĩnh viễn $successCount dữ liệu", true);
        } else {
            $this->moduleService->alert->set('danger', 'Có lỗi xảy ra, không thể xóa dữ liệu', true);
        }
        
        // Chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xuất dữ liệu ra Excel
     */
    public function exportExcel()
    {
        // Phương thức này được thực hiện bởi lớp con
    }
    
    /**
     * Xuất dữ liệu ra PDF
     */
    public function exportPdf()
    {
        // Phương thức này được thực hiện bởi lớp con
    }
    
    /**
     * Xuất dữ liệu đã xóa ra Excel
     */
    public function exportDeletedExcel()
    {
        // Phương thức này được thực hiện bởi lớp con
    }
    
    /**
     * Xuất dữ liệu đã xóa ra PDF
     */
    public function exportDeletedPdf()
    {
        // Phương thức này được thực hiện bởi lớp con
    }
    
    /**
     * Lấy nhãn cho việc sắp xếp
     * 
     * @param string $sort Trường sắp xếp
     * @param string $order Thứ tự sắp xếp
     * @return string Nhãn hiển thị
     */
    protected function getSortText($sort, $order)
    {
        // Phương thức này cần được ghi đè bởi lớp con để cung cấp nhãn phù hợp
        $field = $sort;
        return "$field (" . ($order === 'DESC' ? 'Giảm dần' : 'Tăng dần') . ")";
    }
} 