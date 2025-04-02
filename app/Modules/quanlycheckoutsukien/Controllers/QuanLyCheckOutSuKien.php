<?php

namespace App\Modules\quanlycheckoutsukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\quanlycheckoutsukien\Models\CheckOutSuKienModel;
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
use App\Modules\quanlycheckoutsukien\Traits\ExportTrait;
use App\Modules\quanlycheckoutsukien\Traits\RelationTrait;

class QuanLyCheckOutSuKien extends BaseController
{
    use ResponseTrait;
    use ExportTrait;
    use RelationTrait;
    
    protected $model;
    protected $breadcrumb;
    protected $alert;
    protected $moduleUrl;
    protected $title;
    protected $title_home;
    protected $module_name = 'quanlycheckoutsukien';
    protected $controller_name = 'QuanLyCheckOutSuKien';
    protected $masterScript;
    
    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');

        // Khởi tạo các thành phần cần thiết
        $this->model = new CheckOutSuKienModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url($this->module_name);
        $this->title = 'Check-out Sự kiện';
        $this->title_home = 'Danh sách check-out sự kiện';
        // Khởi tạo thư viện MasterScript với module_name
        $masterScriptClass = "\App\Modules\\" . $this->module_name . '\Libraries\MasterScript';
        $this->masterScript = new $masterScriptClass($this->module_name);
        
        // Khởi tạo các model quan hệ
        $this->initializeRelationTrait();
    }
    
       /**
     * Hiển thị danh sách check-in sự kiện với phân trang
     */
    public function index()
    {
        // Lấy các tham số từ URL
        $params = $this->prepareSearchParams($this->request);
        // Xử lý tham số tìm kiếm
        $params = $this->processSearchParams($params);
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        $this->model->setSurroundCount(3);
        
        // Xây dựng tiêu chí và tùy chọn tìm kiếm
        $criteria = $this->buildSearchCriteria($params);
        $options = $this->buildSearchOptions($params);
        
        // Lấy dữ liệu check-in và thông tin phân trang
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
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order', 'su_kien_id', 'checkin_type', 'hinh_thuc_tham_gia', 'start_date', 'end_date']);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Chuẩn bị dữ liệu cho view
        $viewData = $this->prepareViewData($this->module_name, $pageData, $pager, array_merge($params, ['total' => $total]));
        // Thêm module_name và masterScript vào viewData để sử dụng trong view
        $viewData['module_name'] = $this->module_name;
        $viewData['masterScript'] = $this->masterScript;
        $viewData['title_home'] = $this->title_home;
        $viewData['title'] = $this->title;

        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\index', $viewData);
    }
    
    /**
     * Hiển thị form thêm mới check-out sự kiện
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm mới ' . $this->title,  
            'module_name' => $this->module_name,
            'title_home' => $this->title_home,
            'action' => site_url($this->module_name . '/create')
        ];
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\new', $viewData);
    }
    
    /**
     * Xử lý thêm mới check-out sự kiện
     */
    public function create()
    {
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Loại bỏ checkout_sukien_id khi thêm mới vì là trường auto_increment
        if (isset($data['checkout_sukien_id'])) {
            unset($data['checkout_sukien_id']);
        }
        
        // Xử lý thông tin bổ sung nếu có
        if (isset($data['thong_tin_bo_sung']) && is_array($data['thong_tin_bo_sung'])) {
            $data['thong_tin_bo_sung'] = json_encode($data['thong_tin_bo_sung']);
        }
        
        // Cập nhật thời gian check-out nếu chưa có
        if (empty($data['thoi_gian_check_out'])) {
            $data['thoi_gian_check_out'] = Time::now()->toDateTimeString();
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
            // Đảm bảo trạng thái có giá trị mặc định là 1 nếu không được gửi lên
            if (!isset($data['status'])) {
                $data['status'] = 1;
            }
            
            // Tạo mã xác nhận nếu cần
            if (empty($data['ma_xac_nhan'])) {
                $data['ma_xac_nhan'] = $this->model->generateConfirmationCode();
            }
            
            // Lưu dữ liệu 
            $insertId = $this->model->insertData($data);
            
            if ($insertId) {
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
     * Hiển thị form chỉnh sửa check-out sự kiện
     */
    public function edit($id = null)
    {
        if (!$id) {
            return redirect()->to($this->moduleUrl)
                ->with('error', 'ID không hợp lệ');
        }

        $data = $this->model->find($id);
        if (!$data) {
            return redirect()->to($this->moduleUrl)
                ->with('error', 'Không tìm thấy thông tin check-out sự kiện');
        }

        return view('App\Modules\\' . $this->module_name . '\Views\edit', [
            'module_name' => $this->module_name,
            'title' => $this->title,
            'title_home' => $this->title_home,
            'action' => site_url($this->module_name . '/update/' . $id),
            'data' => $data,
            'pager' => null,
            'perPage' => 1,
            'total' => 1
        ]);
    }
    
    /**
     * Xử lý cập nhật check-out sự kiện
     */
    public function update($id = null)
    {
        if (!$id) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy dữ liệu đối tượng hiện tại
        $fillData = $this->model->find($id);
        if (!$fillData) {
            $this->alert->set('danger', 'Không tìm thấy thông tin check-out sự kiện', true);
            return redirect()->to($this->moduleUrl);
        }

        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Xử lý thông tin bổ sung nếu có
        if (isset($data['thong_tin_bo_sung']) && is_array($data['thong_tin_bo_sung'])) {
            $data['thong_tin_bo_sung'] = json_encode($data['thong_tin_bo_sung']);
        }
        
        // Chuẩn bị quy tắc validation cho cập nhật
        $this->model->prepareValidationRules('update', $data, $id);
        
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
            $fillData->fill($data);
            if (! $fillData->hasChanged()) {
                return redirect()->back()
                                 ->with('warning', 'Cập nhật ' . $this->title . ' dữ liệu không có gì thay đổi!')
                                 ->withInput();
            }
            
            // Cập nhật dữ liệu
            $result = $this->model->updateData($id, $data);
            
            if ($result) {
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
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật ' . $this->title . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Xử lý xóa check-out sự kiện
     */
    public function delete($id = null)
    {
        if (!$id) {
            return redirect()->to($this->moduleUrl)
                ->with('error', 'ID không hợp lệ');
        }

        if ($this->model->delete($id)) {
            return redirect()->to($site_url($this->module_name) . '/listdeleted')
                ->with('success', 'Xóa thông tin check-out sự kiện thành công');
        }

        return redirect()->to($this->moduleUrl)
            ->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
    }
 
   
    /**
     * Hiển thị chi tiết
     */
    public function detail($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID check-out sự kiện không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Đảm bảo các model relationship được khởi tạo
        $this->initializeRelationTrait();
        
        // Lấy thông tin dữ liệu cơ bản
        $data = $this->model->find($id);
        
        if (empty($data)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu check-out sự kiện', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin sự kiện
        $suKien = $data->getSuKien();
        // Lấy thông tin đăng ký sự kiện nếu có
        $dangKySuKien = $data->getDangKySuKien();
        
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
            'suKien' => $suKien,
            'dangKySuKien' => $dangKySuKien,
            'moduleUrl' => $this->moduleUrl,
            'module_name' => $this->module_name,
            'masterScript' => $this->masterScript,
            'pager' => null,
            'perPage' => 1,
            'total' => 1
        ];
        
        return view('App\Modules\\' . $this->module_name . '\Views\detail', $viewData);
    }
    
    
    /**
     * Danh sách đã xóa
     */
    public function listdeleted()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Danh sách đã xóa', current_url());
        
        // Lấy và xử lý tham số tìm kiếm
        $params = $this->prepareSearchParams($this->request);
        $params = $this->processSearchParams($params);
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        $this->model->setSurroundCount(3);
        
        // Xây dựng tiêu chí và tùy chọn tìm kiếm cho các bản ghi đã xóa
        $criteria = $this->buildSearchCriteria($params);
        $criteria['deleted'] = true; // Chỉ lấy các bản ghi đã xóa
        $options = $this->buildSearchOptions($params);
        
        // Log thông tin về criteria và options
        log_message('debug', '[QuanLyCheckOutSuKien::listdeleted] Search criteria: ' . json_encode($criteria));
        log_message('debug', '[QuanLyCheckOutSuKien::listdeleted] Search options: ' . json_encode($options));
        
        // Lấy dữ liệu đã xóa và thông tin phân trang
        $pageData = $this->model->searchDeleted($criteria, $options);
        
        // Log số lượng kết quả tìm được
        log_message('debug', '[QuanLyCheckOutSuKien::listdeleted] Found ' . count($pageData) . ' deleted records');
        
        // In thông tin mẫu của bản ghi đầu tiên nếu có
        if (!empty($pageData)) {
            log_message('debug', '[QuanLyCheckOutSuKien::listdeleted] Sample first record: ' . json_encode($pageData[0]));
        }
        
        // Lấy tổng số kết quả
        $pager = $this->model->getPager();
        $total = $pager ? $pager->getTotal() : $this->model->countDeletedSearchResults($criteria);
        
        // Log tổng số kết quả
        log_message('debug', '[QuanLyCheckOutSuKien::listdeleted] Total deleted records: ' . $total);
        
        // Nếu trang hiện tại lớn hơn tổng số trang, điều hướng về trang cuối cùng
        $pageCount = ceil($total / $params['perPage']);
        if ($total > 0 && $params['page'] > $pageCount) {
            // Tạo URL mới với trang cuối cùng
            $redirectParams = $_GET;
            $redirectParams['page'] = $pageCount;
            $redirectUrl = site_url($this->module_name . '/listdeleted') . '?' . http_build_query($redirectParams);
            
            // Chuyển hướng đến trang cuối cùng
            return redirect()->to($redirectUrl);
        }
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath($this->module_name . '/listdeleted');
            $pager->setRouteUrl($this->module_name . '/listdeleted');
            // Thêm tất cả các tham số cần giữ lại khi chuyển trang
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order', 'su_kien_id', 'checkout_type', 'hinh_thuc_tham_gia']);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Xử lý dữ liệu nếu cần
        $processedData = $this->processData($pageData);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'title_home' => $this->title_home,
            'title' => 'Check-out sự kiện đã xóa',
            'processedData' => $processedData,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $params['perPage'],
            'keyword' => $params['keyword'] ?? '',
            'status' => $params['status'] ?? '',
            'module_name' => $this->module_name,
            'masterScript' => $this->masterScript
        ];
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\listdeleted', $viewData);
    }
    
    /**
     * Xử lý xóa nhiều bản ghi
     */
    public function deleteMultiple()
    {
        // Lấy danh sách ID cần xóa
        $selectedIds = $this->request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            $this->alert->set('danger', 'Không có dữ liệu nào được chọn', true);
            return redirect()->back();
        }
        
        try {
            // Đếm số bản ghi xóa thành công
            $successCount = 0;
            
            foreach ($selectedIds as $id) {
                if ($this->model->delete($id)) {
                    $successCount++;
                }
            }
            
            if ($successCount > 0) {
                $this->alert->set('success', 'Đã xóa ' . $successCount . ' ' . $this->title, true);
            } else {
                $this->alert->set('danger', 'Không thể xóa ' . $this->title, true);
            }
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::deleteMultiple] ' . $e->getMessage());
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa nhiều ' . $this->title, true);
        }
        
        return redirect()->back();
    }
    
    /**
     * Xử lý khôi phục bản ghi
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->back();
        }
        
        try {
            // Ghi log dữ liệu đầu vào
            log_message('debug', '[' . $this->controller_name . '::restore] Attempting to restore check-out with ID: ' . $id);
            
            // Kiểm tra xem bản ghi có tồn tại và bị xóa hay không
            $checkout = $this->model->withDeleted()->find($id);
            
            if (!$checkout || $checkout->deleted_at === null) {
                log_message('error', '[' . $this->controller_name . '::restore] Check-out not found or not deleted: ' . $id);
                $this->alert->set('danger', 'Không tìm thấy check-out đã xóa với ID: ' . $id, true);
                return redirect()->back();
            }
            
            // Sử dụng phương thức mới để khôi phục trực tiếp từ cơ sở dữ liệu
            $result = $this->model->restoreFromTrash($id);
            
            log_message('debug', '[' . $this->controller_name . '::restore] Restore result: ' . ($result ? 'success' : 'failed'));
            
            if ($result) {
                $this->alert->set('success', 'Khôi phục ' . $this->title . ' thành công', true);
            } else {
                $this->alert->set('danger', 'Không thể khôi phục ' . $this->title, true);
            }
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::restore] ' . $e->getMessage());
            $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục ' . $this->title . ': ' . $e->getMessage(), true);
        }
        
        // Kiểm tra nếu là request AJAX
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => session()->has('success'),
                'message' => session()->get('success') ?? session()->get('error')
            ]);
        }
        
        return redirect()->back();
    }
    
    /**
     * Xử lý khôi phục nhiều bản ghi
     */
    public function restoreMultiple()
    {
        // Lấy danh sách ID cần khôi phục
        $selectedIds = $this->request->getPost('selected_ids');
        
        // Ghi log dữ liệu đầu vào
        log_message('debug', '[' . $this->controller_name . '::restoreMultiple] POST data: ' . json_encode($_POST));
        log_message('debug', '[' . $this->controller_name . '::restoreMultiple] Selected IDs: ' . (is_array($selectedIds) ? json_encode($selectedIds) : $selectedIds));
        
        if (empty($selectedIds)) {
            $this->alert->set('danger', 'Không có dữ liệu nào được chọn', true);
            return redirect()->back();
        }
        
        try {
            // Đảm bảo $selectedIds là mảng
            $idArray = is_array($selectedIds) ? $selectedIds : explode(',', $selectedIds);
            
            log_message('debug', '[' . $this->controller_name . '::restoreMultiple] Processing ' . count($idArray) . ' IDs');
            
            // Sử dụng phương thức mới để khôi phục trực tiếp nhiều bản ghi cùng lúc
            $successCount = $this->model->restoreMultipleFromTrash($idArray);
            
            log_message('debug', '[' . $this->controller_name . '::restoreMultiple] Restored ' . $successCount . ' out of ' . count($idArray) . ' check-outs');
            
            if ($successCount > 0) {
                $this->alert->set('success', 'Đã khôi phục ' . $successCount . ' ' . $this->title, true);
            } else {
                $this->alert->set('danger', 'Không thể khôi phục ' . $this->title, true);
            }
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::restoreMultiple] ' . $e->getMessage());
            $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục nhiều ' . $this->title . ': ' . $e->getMessage(), true);
        }
        
        return redirect()->back();
    }

    /**
     * Xử lý thay đổi trạng thái tham gia
     */
    public function statusMultiple()
    {
        // Lấy danh sách ID cần cập nhật và trạng thái mới
        $selectedIds = $this->request->getPost('selected_ids');
        $newStatus = $this->request->getPost('new_status');
        
        if (empty($selectedIds) || $newStatus === null) {
            $this->alert->set('danger', 'Dữ liệu không hợp lệ', true);
            return redirect()->back();
        }
        
        try {
            $successCount = 0;
            
            foreach ($selectedIds as $id) {
                if ($this->model->updateTrangThaiThamGia($id, $newStatus)) {
                    $successCount++;
                }
            }
            
            if ($successCount > 0) {
                $this->alert->set('success', 'Đã cập nhật trạng thái ' . $successCount . ' ' . $this->title, true);
            } else {
                $this->alert->set('danger', 'Không thể cập nhật trạng thái ' . $this->title, true);
            }
        } catch (\Exception $e) {
            $this->alert->set('danger', 'Có lỗi xảy ra khi cập nhật trạng thái ' . $this->title, true);
        }
        
        return redirect()->back();
    }

    /**
     * Xác minh khuôn mặt cho check-out
     */
    public function verifyFace($id = null, $verified = 1, $score = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->back();
        }
        
        try {
            $result = $this->model->updateFaceVerification($id, (bool)$verified, $score);
            
            if ($result) {
                $this->alert->set('success', 'Cập nhật xác minh khuôn mặt thành công', true);
            } else {
                $this->alert->set('danger', 'Không thể cập nhật xác minh khuôn mặt', true);
            }
        } catch (\Exception $e) {
            $this->alert->set('danger', 'Có lỗi xảy ra: ' . $e->getMessage(), true);
        }
        
        return redirect()->back();
    }

    /**
     * Chuẩn bị tham số tìm kiếm
     */
    protected function prepareSearchParams($request)
    {
        // Xử lý perPage trước
        $perPage = $request->getGet('perPage');
        $perPage = !empty($perPage) ? (int)$perPage : 10;
        
        return [
            'keyword' => $request->getGet('keyword') ?? '',
            'status' => $request->getGet('status') ?? '',
            'page' => (int)($request->getGet('page') ?? 1),
            'perPage' => $perPage,
            'sort' => $request->getGet('sort') ?? 'thoi_gian_check_out',
            'order' => $request->getGet('order') ?? 'DESC',
            'su_kien_id' => $request->getGet('su_kien_id') ?? '',
            'checkout_type' => $request->getGet('checkout_type') ?? '',
            'hinh_thuc_tham_gia' => $request->getGet('hinh_thuc_tham_gia') ?? '',
            'face_verified' => $request->getGet('face_verified') ?? '',
            'start_date' => $request->getGet('start_date') ?? '',
            'end_date' => $request->getGet('end_date') ?? '',
            'dangky_sukien_id' => $request->getGet('dangky_sukien_id') ?? '',
        ];
    }
    
    /**
     * Xử lý các tham số tìm kiếm
     * 
     * @param array $params Tham số tìm kiếm
     * @return array Tham số đã xử lý
     */
    protected function processSearchParams($params)
    {
        // Sắp xếp
        if (!isset($params['sort']) || empty($params['sort'])) {
            $params['sort'] = 'thoi_gian_check_out';
        }
        
        // Thứ tự sắp xếp
        if (!isset($params['order']) || empty($params['order'])) {
            $params['order'] = 'DESC';
        }
        
        // Đảm bảo thứ tự sắp xếp hợp lệ
        if (!in_array(strtoupper($params['order']), ['ASC', 'DESC'])) {
            $params['order'] = 'DESC';
        }
        
        // Phân trang
        if (!isset($params['perPage']) || empty($params['perPage'])) {
            $params['perPage'] = 10;
        }
        
        // Đảm bảo perPage hợp lệ
        $validPerPage = [10, 25, 50, 100];
        if (!in_array((int)$params['perPage'], $validPerPage)) {
            $params['perPage'] = 10;
        }
        
        // Trang hiện tại
        if (!isset($params['page']) || empty($params['page'])) {
            $params['page'] = 1;
        }
        
        // Đảm bảo page hợp lệ
        if ((int)$params['page'] < 1) {
            $params['page'] = 1;
        }
        
        return $params;
    }
    
    /**
     * Xây dựng tiêu chí tìm kiếm từ tham số
     */
    protected function buildSearchCriteria($params)
    {
        $criteria = [];
        
        // Tìm kiếm theo từ khóa
        if (!empty($params['keyword'])) {
            // Đảm bảo keyword là chuỗi
            $criteria['keyword'] = is_array($params['keyword']) ? implode(' ', $params['keyword']) : $params['keyword'];
        }
        
        // Lọc theo trạng thái
        if (isset($params['status']) && $params['status'] !== '') {
            $criteria['status'] = (int)$params['status'];
        }
        
        // Lọc theo sự kiện ID
        if (!empty($params['su_kien_id'])) {
            $criteria['su_kien_id'] = (int)$params['su_kien_id'];
        }
        
        // Lọc theo loại check-out
        if (!empty($params['checkout_type'])) {
            $criteria['checkout_type'] = $params['checkout_type'];
        }
        
        // Lọc theo hình thức tham gia
        if (!empty($params['hinh_thuc_tham_gia'])) {
            $criteria['hinh_thuc_tham_gia'] = $params['hinh_thuc_tham_gia'];
        }
        
        // Lọc theo trạng thái xác minh khuôn mặt
        if (isset($params['face_verified']) && $params['face_verified'] !== '') {
            $criteria['face_verified'] = (int)$params['face_verified'];
        }
        
        // Lọc theo khoảng thời gian
        if (!empty($params['start_date'])) {
            $criteria['start_date'] = $params['start_date'];
        }
        
        if (!empty($params['end_date'])) {
            $criteria['end_date'] = $params['end_date'];
        }
        
        return $criteria;
    }
    
    /**
     * Xây dựng tùy chọn tìm kiếm từ tham số
     */
    protected function buildSearchOptions($params)
    {
        // Đảm bảo các giá trị là số nguyên
        $page = (int)($params['page'] ?? 1);
        $perPage = (int)($params['perPage'] ?? 10);
        
        // Tính toán offset
        $offset = ($page - 1) * $perPage;
        
        return [
            'page' => $page,
            'perPage' => $perPage,
            'offset' => $offset,
            'limit' => $perPage,
            'sort' => $params['sort'] ?? 'thoi_gian_check_out',
            'order' => $params['order'] ?? 'DESC'
        ];
    }
    
    /**
     * Chuẩn bị dữ liệu cho view
     */
    protected function prepareViewData($module_name, $pageData, $pager, $params = [])
    {
        // Xử lý dữ liệu nếu cần
        $processedData = $this->processData($pageData);
        
        // Thêm các tùy chọn cho số bản ghi trên trang
        $perPageOptions = [10, 25, 50, 100];
        
        return [
            'breadcrumb' => $this->breadcrumb->render(),
            'processedData' => $processedData,
            'pager' => $pager,
            'keyword' => $params['keyword'] ?? '',
            'status' => $params['status'] ?? '',
            'su_kien_id' => $params['su_kien_id'] ?? '',
            'checkout_type' => $params['checkout_type'] ?? '',
            'hinh_thuc_tham_gia' => $params['hinh_thuc_tham_gia'] ?? '',
            'face_verified' => $params['face_verified'] ?? '',
            'start_date' => $params['start_date'] ?? '',
            'end_date' => $params['end_date'] ?? '',
            'perPage' => $params['perPage'] ?? 10,
            'perPageOptions' => $perPageOptions,
            'sort' => $params['sort'] ?? 'thoi_gian_check_out',
            'order' => $params['order'] ?? 'DESC',
            'page' => $params['page'] ?? 1,
            'module_name' => $module_name,
            'title' => $this->title,
            'total' => $params['total'] ?? count($processedData)
        ];
    }
    
    /**
     * Xử lý dữ liệu trước khi hiển thị
     */
    protected function processData($data)
    {
        if (empty($data)) {
            return [];
        }
        
        // Định dạng dữ liệu bổ sung như ngày tháng, trạng thái, v.v.
        foreach ($data as $index => $item) {
            // Đảm bảo thông tin bổ sung được định dạng đúng
            if (property_exists($item, 'thong_tin_bo_sung') && !empty($item->thong_tin_bo_sung)) {
                if (is_string($item->thong_tin_bo_sung)) {
                    try {
                        $data[$index]->formatted_thong_tin_bo_sung = $item->getFormattedThongTinBoSung();
                    } catch (\Exception $e) {
                        log_message('error', 'Lỗi khi định dạng thông tin bổ sung: ' . $e->getMessage());
                        $data[$index]->formatted_thong_tin_bo_sung = [];
                    }
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Chuẩn bị dữ liệu cho form
     */
    protected function prepareFormData($module_name, $options = [])
    {
        $data = $options['data'] ?? null;
        $title = $options['title'] ?? 'Form';
        
        return [
            'data' => $data,
            'title' => $title,
            'module_name' => $module_name
        ];
    }

    /**
     * Xóa vĩnh viễn một check-in sự kiện
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form hoặc từ HTTP_REFERER
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getVar('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
            $this->alert->set('success', 'Đã xóa vĩnh viễn check-in sự kiện', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa check-in sự kiện', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }

    /**
     * Xóa vĩnh viễn nhiều camera
     */
    public function permanentDeleteMultiple()
    {
        // Lấy các ID được chọn từ form và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn camera nào để xóa vĩnh viễn', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        
        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
       
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
            $this->alert->set('success', "Đã xóa vĩnh viễn $successCount camera", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa camera', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }

    /**
     * Alias cho permanentDeleteMultiple
     */
    public function deletePermanentMultiple()
    {
        return $this->permanentDeleteMultiple();
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
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập các style
        $titleStyle = [
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '1A5FB4']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
        ];
        
        $subtitleStyle = [
            'font' => ['italic' => true, 'size' => 11],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
        ];
        
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];
        
        $dataStyle = [
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
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
            $sheet->setCellValue($col++ . $row, $item->checkout_sukien_id);
            
            // Họ tên
            $sheet->setCellValue($col++ . $row, $item->ho_ten);
            
            // Email
            $sheet->setCellValue($col++ . $row, $item->email);
            
            // Sự kiện
            $sheet->setCellValue($col++ . $row, $item->ten_su_kien ?? '');
            
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
            $sheet->setCellValue($col++ . $row, $item->getDanhGia() ? $item->getDanhGia() . ' sao' : '');
            
            // Nội dung đánh giá
            $sheet->setCellValue($col++ . $row, $item->getNoiDungDanhGia());
            
            // Phản hồi
            $sheet->setCellValue($col++ . $row, $item->getFeedback());
            
            // Ngày tạo
            $sheet->setCellValue($col++ . $row, $item->created_at ? date('d/m/Y H:i', strtotime($item->created_at)) : '');
            
            // Ngày cập nhật
            $sheet->setCellValue($col++ . $row, $item->updated_at ? date('d/m/Y H:i', strtotime($item->updated_at)) : '');
            
            // Ngày xóa (nếu có)
            if ($includeDeleted) {
                $sheet->setCellValue($col++ . $row, $item->deleted_at ? date('d/m/Y H:i', strtotime($item->deleted_at)) : '');
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
        $sheet->getStyle('A' . $totalRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        
        // Xuất file
        try {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
            header('Cache-Control: max-age=0');
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit();
        } catch (\Exception $e) {
            log_message('error', 'Lỗi xuất Excel: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Xuất dữ liệu ra file Excel
     */
    public function exportExcel()
    {
        try {
            // Lấy dữ liệu trực tiếp từ model không phụ thuộc vào filter
            $data = $this->model->getAll([
                'sort' => 'thoi_gian_check_out',
                'order' => 'DESC',
                'limit' => 0 // Không giới hạn số bản ghi
            ]);
            
            // Ghi log
            log_message('info', 'Xuất Excel: ' . count($data) . ' bản ghi');
            
            // Tạo tên file
            $filename = 'checkout_sukien_' . date('YmdHis');
            
            // Tạo và xuất file Excel, không cần xử lý filters
            $this->createExcelFile($data, $this->getExportHeaders(), [], $filename);
        } catch (\Exception $e) {
            log_message('error', 'Lỗi xuất Excel: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xuất Excel: ' . $e->getMessage());
        }
    }

    /**
     * Xuất dữ liệu đã xóa ra file Excel
     */
    public function exportDeletedExcel()
    {
        try {
            // Lấy dữ liệu đã xóa trực tiếp từ model không phụ thuộc vào filter
            $data = $this->model->getAllDeleted([
                'sort' => 'thoi_gian_check_out',
                'order' => 'DESC',
                'limit' => 0 // Không giới hạn số bản ghi
            ]);
            
            // Ghi log
            log_message('info', 'Xuất Excel đã xóa: ' . count($data) . ' bản ghi');
            
            // Tạo tên file
            $filename = 'checkout_sukien_deleted_' . date('YmdHis');
            
            // Tạo và xuất file Excel, không cần xử lý filters
            $this->createExcelFile($data, $this->getExportHeaders(true), [], $filename, true);
        } catch (\Exception $e) {
            log_message('error', 'Lỗi xuất Excel đã xóa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xuất Excel đã xóa: ' . $e->getMessage());
        }
    }

    /**
     * Xuất dữ liệu ra file PDF
     */
    public function exportPdf()
    {
        try {
            // Lấy dữ liệu trực tiếp từ model không phụ thuộc vào filter
            $data = $this->model->getAll([
                'sort' => 'thoi_gian_check_out',
                'order' => 'DESC',
                'limit' => 0 // Không giới hạn số bản ghi
            ]);
            
            // Ghi log
            log_message('info', 'Xuất PDF: ' . count($data) . ' bản ghi');
            
            // Tạo tên file
            $filename = 'checkout_sukien_' . date('YmdHis');
            
            // Tạo và xuất file PDF, không cần xử lý filters
            $this->createPdfFromTemplate($data, [], $filename, false);
        } catch (\Exception $e) {
            log_message('error', 'Lỗi xuất PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xuất PDF: ' . $e->getMessage());
        }
    }

    /**
     * Xuất dữ liệu đã xóa ra file PDF
     */
    public function exportDeletedPdf()
    {
        try {
            // Lấy dữ liệu đã xóa trực tiếp từ model không phụ thuộc vào filter
            $data = $this->model->getAllDeleted([
                'sort' => 'thoi_gian_check_out',
                'order' => 'DESC',
                'limit' => 0 // Không giới hạn số bản ghi
            ]);
            
            // Ghi log
            log_message('info', 'Xuất PDF đã xóa: ' . count($data) . ' bản ghi');
            
            // Tạo tên file
            $filename = 'checkout_sukien_deleted_' . date('YmdHis');
            
            // Tạo và xuất file PDF, không cần xử lý filters
            $this->createPdfFromTemplate($data, [], $filename, true);
        } catch (\Exception $e) {
            log_message('error', 'Lỗi xuất PDF đã xóa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xuất PDF đã xóa: ' . $e->getMessage());
        }
    }

    /**
     * Tạo PDF từ template
     *
     * @param array $data Dữ liệu
     * @param array $filters Bộ lọc
     * @param string $filename Tên file
     * @param bool $includeDeleted Có bao gồm dữ liệu đã xóa
     */
    private function createPdfFromTemplate($data, $filters, $filename, $includeDeleted = false)
    {
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
        
        // Tạo PDF sử dụng thư viện Dompdf
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Xuất file
        $dompdf->stream($filename . '.pdf', ['Attachment' => true]);
        exit();
    }

    /**
     * Lấy headers cho file xuất
     *
     * @param bool $includeDeleted Có bao gồm cột ngày xóa không
     * @return array
     */
    protected function getExportHeaders(bool $includeDeleted = false): array
    {
        $headers = [
            'STT' => 'A',
            'ID' => 'B',
            'Họ tên' => 'C',
            'Email' => 'D',
            'Sự kiện' => 'E',
            'Thời gian check-out' => 'F',
            'Loại check-out' => 'G',
            'Hình thức' => 'H',
            'Trạng thái' => 'I',
            'Xác minh KM' => 'J',
            'Điểm số KM' => 'K',
            'Thời gian tham dự' => 'L',
            'Đánh giá' => 'M',
            'Nội dung đánh giá' => 'N',
            'Phản hồi' => 'O',
            'Ngày tạo' => 'P',
            'Ngày cập nhật' => 'Q'
        ];

        if ($includeDeleted) {
            $headers['Ngày xóa'] = 'R';
        }

        return $headers;
    }

}