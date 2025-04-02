<?php

namespace App\Modules\quanlycheckinsukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\quanlycheckinsukien\Models\CheckInSuKienModel;
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
use App\Modules\quanlycheckinsukien\Traits\ExportTrait;
use App\Modules\quanlycheckinsukien\Traits\RelationTrait;

class QuanLyCheckInSuKien extends BaseController
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
    protected $module_name = 'quanlycheckinsukien';
    protected $controller_name = 'QuanLyCheckInSuKien';
    protected $masterScript;
    
    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');

        // Khởi tạo các thành phần cần thiết
        $this->model = new CheckInSuKienModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url($this->module_name);
        $this->title = 'Check-in Sự kiện';
        $this->title_home = 'Danh sách check-in sự kiện';
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
     * Hiển thị form thêm mới check-in sự kiện
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
     * Xử lý thêm mới check-in sự kiện
     */
    public function create()
    {
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Loại bỏ checkin_sukien_id khi thêm mới vì là trường auto_increment
        if (isset($data['checkin_sukien_id'])) {
            unset($data['checkin_sukien_id']);
        }
        
        // Xử lý thông tin bổ sung nếu có
        if (isset($data['thong_tin_bo_sung']) && is_array($data['thong_tin_bo_sung'])) {
            $data['thong_tin_bo_sung'] = json_encode($data['thong_tin_bo_sung']);
        }
        
        // Cập nhật thời gian check-in nếu chưa có
        if (empty($data['thoi_gian_check_in'])) {
            $data['thoi_gian_check_in'] = Time::now()->toDateTimeString();
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
     * Hiển thị form chỉnh sửa check-in sự kiện
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
                ->with('error', 'Không tìm thấy thông tin check-in sự kiện');
        }

        return view('App\Modules\\' . $this->module_name . '\Views\edit', [
            'module_name' => $this->module_name,
            'title' => $this->title,
            'title_home' => $this->title_home,
            'action' => site_url($this->module_name . '/update/' . $id),
            'data' => $data
        ]);
    }
    
    /**
     * Xử lý cập nhật check-in sự kiện
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
            $this->alert->set('danger', 'Không tìm thấy thông tin check-in sự kiện', true);
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
     * Xử lý xóa check-in sự kiện
     */
    public function delete($id = null)
    {
        if (!$id) {
            return redirect()->to($this->moduleUrl)
                ->with('error', 'ID không hợp lệ');
        }

        if ($this->model->delete($id)) {
            return redirect()->to($this->moduleUrl)
                ->with('success', 'Xóa thông tin check-in sự kiện thành công');
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
            $this->alert->set('danger', 'ID check-in sự kiện không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Đảm bảo các model relationship được khởi tạo
        $this->initializeRelationTrait();
        
        // Lấy thông tin dữ liệu cơ bản
        $data = $this->model->find($id);
        
        if (empty($data)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu check-in sự kiện', true);
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
            'masterScript' => $this->masterScript
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
        log_message('debug', '[QuanLyCheckInSuKien::listdeleted] Search criteria: ' . json_encode($criteria));
        log_message('debug', '[QuanLyCheckInSuKien::listdeleted] Search options: ' . json_encode($options));
        
        // Lấy dữ liệu đã xóa và thông tin phân trang
        $pageData = $this->model->searchDeleted($criteria, $options);
        
        // Log số lượng kết quả tìm được
        log_message('debug', '[QuanLyCheckInSuKien::listdeleted] Found ' . count($pageData) . ' deleted records');
        
        // In thông tin mẫu của bản ghi đầu tiên nếu có
        if (!empty($pageData)) {
            log_message('debug', '[QuanLyCheckInSuKien::listdeleted] Sample first record: ' . json_encode($pageData[0]));
        }
        
        // Lấy tổng số kết quả
        $pager = $this->model->getPager();
        $total = $pager ? $pager->getTotal() : $this->model->countDeletedSearchResults($criteria);
        
        // Log tổng số kết quả
        log_message('debug', '[QuanLyCheckInSuKien::listdeleted] Total deleted records: ' . $total);
        
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
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order', 'su_kien_id', 'checkin_type', 'hinh_thuc_tham_gia']);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Xử lý dữ liệu nếu cần
        $processedData = $this->processData($pageData);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'title_home' => $this->title_home,
            'title' => 'Check-in sự kiện đã xóa',
            'processedData' => $processedData,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $params['perPage'],
            'keyword' => $params['keyword'] ?? '',
            'status' => $params['status'] ?? '',
            'su_kien_id' => $params['su_kien_id'] ?? '',
            'checkin_type' => $params['checkin_type'] ?? '',
            'hinh_thuc_tham_gia' => $params['hinh_thuc_tham_gia'] ?? '',
            'start_date' => $params['start_date'] ?? '',
            'end_date' => $params['end_date'] ?? '',
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
            log_message('debug', '[' . $this->controller_name . '::restore] Attempting to restore check-in with ID: ' . $id);
            
            // Kiểm tra xem bản ghi có tồn tại và bị xóa hay không
            $checkin = $this->model->withDeleted()->find($id);
            
            if (!$checkin || $checkin->deleted_at === null) {
                log_message('error', '[' . $this->controller_name . '::restore] Check-in not found or not deleted: ' . $id);
                $this->alert->set('danger', 'Không tìm thấy check-in đã xóa với ID: ' . $id, true);
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
            
            log_message('debug', '[' . $this->controller_name . '::restoreMultiple] Restored ' . $successCount . ' out of ' . count($idArray) . ' check-ins');
            
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
     * Xác minh khuôn mặt cho check-in
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
        return [
            'keyword' => $request->getGet('keyword') ?? '',
            'status' => $request->getGet('status') ?? '',
            'page' => (int)($request->getGet('page') ?? 1),
            'perPage' => (int)($request->getGet('perPage') ?? 10),
            'sort' => $request->getGet('sort') ?? 'thoi_gian_check_in',
            'order' => $request->getGet('order') ?? 'DESC',
            'su_kien_id' => $request->getGet('su_kien_id') ?? '',
            'checkin_type' => $request->getGet('checkin_type') ?? '',
            'hinh_thuc_tham_gia' => $request->getGet('hinh_thuc_tham_gia') ?? '',
            'face_verified' => $request->getGet('face_verified') ?? '',
            'start_date' => $request->getGet('start_date') ?? '',
            'end_date' => $request->getGet('end_date') ?? '',
            'dangky_sukien_id' => $request->getGet('dangky_sukien_id') ?? '',
        ];
    }
    
    /**
     * Xử lý tham số tìm kiếm
     */
    protected function processSearchParams($params)
    {
        // Đảm bảo page >= 1
        $params['page'] = max(1, $params['page']);
        
        // Đảm bảo perPage nằm trong danh sách cho phép
        $validPerPage = [10, 25, 50, 100];
        if (!in_array($params['perPage'], $validPerPage)) {
            $params['perPage'] = $validPerPage[0];
        }
        
        // Đảm bảo sort là một trường hợp lệ
        $validSortFields = [
            'checkin_sukien_id', 'su_kien_id', 'ho_ten', 'email', 'thoi_gian_check_in', 
            'checkin_type', 'face_verified', 'status', 'hinh_thuc_tham_gia', 
            'created_at', 'updated_at'
        ];
        if (!in_array($params['sort'], $validSortFields)) {
            $params['sort'] = 'thoi_gian_check_in';
        }
        
        // Đảm bảo order là ASC hoặc DESC
        $params['order'] = strtoupper($params['order']);
        if (!in_array($params['order'], ['ASC', 'DESC'])) {
            $params['order'] = 'DESC';
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
        
        // Lọc theo loại check-in
        if (!empty($params['checkin_type'])) {
            $criteria['checkin_type'] = $params['checkin_type'];
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
            'sort' => $params['sort'] ?? 'thoi_gian_check_in',
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
        
        return [
            'breadcrumb' => $this->breadcrumb->render(),
            'processedData' => $processedData,
            'pager' => $pager,
            'keyword' => $params['keyword'] ?? '',
            'status' => $params['status'] ?? '',
            'su_kien_id' => $params['su_kien_id'] ?? '',
            'checkin_type' => $params['checkin_type'] ?? '',
            'hinh_thuc_tham_gia' => $params['hinh_thuc_tham_gia'] ?? '',
            'face_verified' => $params['face_verified'] ?? '',
            'start_date' => $params['start_date'] ?? '',
            'end_date' => $params['end_date'] ?? '',
            'perPage' => $params['perPage'] ?? 10,
            'sort' => $params['sort'] ?? 'thoi_gian_check_in',
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
            $sheet->setCellValue('F' . $row, $item->so_dien_thoai ?? '');
            
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

    /**
     * Xuất danh sách check-in sự kiện ra file Excel
     */
    public function exportExcel()
    {
        // Lấy các tham số tìm kiếm
        $params = $this->prepareSearchParams($this->request);
        
        // Xử lý tham số tìm kiếm để phù hợp với xuất dữ liệu
        $options = $this->prepareSearchOptions($params);
        
        // Chuẩn bị tiêu chí tìm kiếm
        $criteria = $this->prepareExportCriteria($params);
        
        // Lấy dữ liệu 
        $data = $this->getExportData($criteria, $options);
        
        // Lấy và định dạng bộ lọc
        $filters = $this->formatFilters($params);
        
        // Lấy headers cho Excel
        $headers = $this->getExportHeaders();
        
        // Tạo tên file với timestamp để tránh trùng lặp
        $filename = 'danh_sach_check_in_su_kien_' . date('YmdHis') . '.xlsx';
        
        // Tạo và xuất file Excel
        $this->createExcelFile($data, $headers, $filters, $filename);
    }
    
    /**
     * Xuất danh sách check-in sự kiện đã xóa ra Excel
     */
    public function exportDeletedExcel()
    {
        // Lấy các tham số tìm kiếm
        $params = $this->prepareSearchParams($this->request);
        
        // Xử lý tham số tìm kiếm đặc biệt cho các bản ghi đã xóa
        $options = $this->prepareSearchOptions($params);
        $options['includeDeleted'] = true;
        
        // Chuẩn bị tiêu chí tìm kiếm cho các bản ghi đã xóa
        $criteria = $this->prepareExportCriteria($params);
        $criteria['deleted'] = true;
        
        // Lấy dữ liệu đã xóa
        $data = $this->getExportData($criteria, $options);
        
        // Lấy và định dạng bộ lọc
        $filters = $this->formatFilters($params);
        
        // Lấy headers cho Excel với thông tin ngày xóa
        $headers = $this->getExportHeaders(true);
        
        // Tạo tên file với timestamp để tránh trùng lặp
        $filename = 'danh_sach_check_in_su_kien_da_xoa_' . date('YmdHis') . '.xlsx';
        
        // Tạo và xuất file Excel với tham số đã xóa
        $this->createExcelFile($data, $headers, $filters, $filename, true);
    }

    /**
     * Xóa vĩnh viễn một bản ghi đã xóa mềm
     *
     * @param int|null $id ID của bản ghi cần xóa vĩnh viễn
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function permanentDelete($id = null)
    {
        $returnUrl = $this->request->getGet('return_url') ?: site_url($this->module_name . '/listdeleted');
        $returnUrl = $this->processReturnUrl($returnUrl);
        
        if (!$id) {
            $this->alert->set('error', 'ID không hợp lệ', true);
            return redirect()->to($returnUrl);
        }

        try {
            // Kiểm tra bản ghi có tồn tại và đã bị xóa mềm hay không
            $data = $this->model->withDeleted()->find($id);
            
            if (!$data) {
                throw new \RuntimeException('Không tìm thấy bản ghi có ID #' . $id);
            }
            
            if ($data->deleted_at === null) {
                throw new \RuntimeException('Bản ghi chưa bị xóa mềm, không thể xóa vĩnh viễn');
            }
            
            // Thực hiện xóa vĩnh viễn
            if ($this->model->permanentDelete($id)) {
                $this->alert->set('success', 'Đã xóa vĩnh viễn bản ghi thành công', true);
            } else {
                throw new \RuntimeException('Không thể xóa vĩnh viễn bản ghi');
            }
            
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::permanentDelete] ' . $e->getMessage());
            $this->alert->set('error', 'Có lỗi xảy ra: ' . $e->getMessage(), true);
        }
        
        return redirect()->to($returnUrl);
    }

    /**
     * Xóa vĩnh viễn nhiều bản ghi đã xóa mềm
     * 
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function permanentDeleteMultiple()
    {
        $returnUrl = $this->request->getPost('return_url') ?: site_url($this->module_name . '/listdeleted');
        $returnUrl = $this->processReturnUrl($returnUrl);
        $selectedIds = $this->request->getPost('selected_ids');
        
        if (empty($selectedIds)) {
            $this->alert->set('error', 'Vui lòng chọn ít nhất một bản ghi để xóa vĩnh viễn', true);
            return redirect()->to($returnUrl);
        }
        
        $ids = is_array($selectedIds) ? $selectedIds : explode(',', $selectedIds);
        $ids = array_map('intval', $ids);
        
        try {
            // Lấy danh sách các bản ghi đã chọn
            $items = $this->model->withDeleted()->find($ids);
            
            if (empty($items)) {
                throw new \RuntimeException('Không tìm thấy bản ghi cần xóa vĩnh viễn');
            }
            
            // Đếm số bản ghi thành công
            $successCount = 0;
            
            // Lưu lại lỗi nếu có
            $errors = [];
            
            // Xử lý từng bản ghi
            foreach ($items as $item) {
                try {
                    // Kiểm tra bản ghi đã bị xóa mềm chưa
                    if ($item->deleted_at === null) {
                        throw new \RuntimeException('Bản ghi ID #' . $item->checkin_sukien_id . ' chưa bị xóa mềm, không thể xóa vĩnh viễn.');
                    }
                    
                    // Xóa vĩnh viễn bản ghi
                    if ($this->model->permanentDelete($item->checkin_sukien_id)) {
                        $successCount++;
                    } else {
                        throw new \RuntimeException('Không thể xóa vĩnh viễn bản ghi ID #' . $item->checkin_sukien_id);
                    }
                } catch (\Exception $e) {
                    $errors[] = $e->getMessage();
                    log_message('error', '[' . $this->controller_name . '::permanentDeleteMultiple] Lỗi xóa vĩnh viễn ID #' . 
                        ($item->checkin_sukien_id ?? 'unknown') . ': ' . $e->getMessage());
                }
            }
            
            // Thông báo kết quả
            if ($successCount > 0) {
                $this->alert->set('success', 'Đã xóa vĩnh viễn ' . $successCount . ' bản ghi thành công.', true);
            }
            
            if (!empty($errors)) {
                // Nếu có lỗi, hiển thị thông báo lỗi
                $this->alert->set('error', 'Có ' . count($errors) . ' lỗi xảy ra: ' . implode(', ', array_slice($errors, 0, 3)) . 
                    (count($errors) > 3 ? '...' : ''), true);
            }
            
        } catch (\Exception $e) {
            log_message('error', '[' . $this->controller_name . '::permanentDeleteMultiple] ' . $e->getMessage());
            $this->alert->set('error', 'Có lỗi xảy ra: ' . $e->getMessage(), true);
        }
        
        return redirect()->to($returnUrl);
    }

    /**
     * Alias cho permanentDeleteMultiple
     */
    public function deletePermanentMultiple()
    {
        return $this->permanentDeleteMultiple();
    }

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
        if (isset($params['keyword']) && $params['keyword'] !== '') {
            $criteria['keyword'] = trim($params['keyword']);
        }

        // Xử lý sự kiện
        if (isset($params['su_kien_id']) && $params['su_kien_id'] !== '') {
            $criteria['su_kien_id'] = (int)$params['su_kien_id'];
        }

        // Xử lý loại check-in
        if (isset($params['checkin_type']) && $params['checkin_type'] !== '') {
            $criteria['checkin_type'] = $params['checkin_type'];
        }

        // Xử lý hình thức tham gia
        if (isset($params['hinh_thuc_tham_gia']) && $params['hinh_thuc_tham_gia'] !== '') {
            $criteria['hinh_thuc_tham_gia'] = $params['hinh_thuc_tham_gia'];
        }

        // Xử lý trạng thái xác minh khuôn mặt
        if (isset($params['face_verified']) && $params['face_verified'] !== '') {
            $criteria['face_verified'] = (int)$params['face_verified'];
        }

        // Xử lý trạng thái
        if (isset($params['status']) && $params['status'] !== '') {
            $criteria['status'] = (int)$params['status'];
        }

        // Xử lý khoảng thời gian
        if (isset($params['start_date']) && $params['start_date'] !== '') {
            $criteria['start_date'] = date('Y-m-d 00:00:00', strtotime($params['start_date']));
        }
        if (isset($params['end_date']) && $params['end_date'] !== '') {
            $criteria['end_date'] = date('Y-m-d 23:59:59', strtotime($params['end_date']));
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

}