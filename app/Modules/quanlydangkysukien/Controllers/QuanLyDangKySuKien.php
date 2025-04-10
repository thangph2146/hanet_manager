<?php

namespace App\Modules\quanlydangkysukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\quanlydangkysukien\Models\DangKySuKienModel;
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
use App\Modules\quanlydangkysukien\Traits\ExportTrait;
use App\Modules\quanlydangkysukien\Traits\RelationTrait;

class QuanLyDangKySuKien extends BaseController
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
    protected $module_name = 'quanlydangkysukien';
    protected $controller_name = 'QuanLyDangKySuKien';
    protected $masterScript;
    
    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');

        // Khởi tạo các thành phần cần thiết
        $this->model = new DangKySuKienModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url($this->module_name);
        $this->title = 'Đăng ký sự kiện';
        $this->title_home = 'Danh sách đăng ký sự kiện';
        
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
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order', 'su_kien_id', 'loai_nguoi_dang_ky', 'hinh_thuc_tham_gia', 'start_date', 'end_date']);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Lấy danh sách sự kiện cho filter và hiển thị
        $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
        $suKiens = $suKienModel->where('deleted_at', null)
                              ->orderBy('created_at', 'DESC')
                              ->findAll();
        
        // Lấy danh sách loại người dùng cho filter
        $loaiNguoiDungOptions = $this->model->getLoaiNguoiDungOptions();
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'module_name' => $this->module_name,
            'title' => $this->title,
            'title_home' => $this->title_home,
            'masterScript' => $this->masterScript,
            'processedData' => $pageData,
            'pager' => $pager,
            'perPage' => $params['perPage'],
            'total' => $total,
            'keyword' => $params['keyword'] ?? '',
            'status' => $params['status'] ?? '',
            'su_kien_id' => $params['su_kien_id'] ?? '',
            'loai_nguoi_dang_ky' => $params['loai_nguoi_dang_ky'] ?? '',
            'hinh_thuc_tham_gia' => $params['hinh_thuc_tham_gia'] ?? '',
            'suKiens' => $suKiens,
            'loaiNguoiDungOptions' => $loaiNguoiDungOptions
        ];

        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\index', $viewData);
    }
    
    /**
     * Hiển thị form thêm mới đăng ký sự kiện
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Lấy danh sách sự kiện cho dropdown
        $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
        $suKiens = $suKienModel->where('deleted_at', null)
                              ->orderBy('created_at', 'DESC')
                              ->findAll();
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm mới ' . $this->title,  
            'module_name' => $this->module_name,
            'title_home' => $this->title_home,
            'action' => site_url($this->module_name . '/create'),
            'suKiens' => $suKiens
        ];
        
        // Hiển thị view
        return view('App\Modules\\' . $this->module_name . '\Views\new', $viewData);
    }
    
    /**
     * Xử lý thêm mới loại sự kiện
     */
    public function create()
    {
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Loại bỏ loai_su_kien_id khi thêm mới vì là trường auto_increment
        if (isset($data['loai_su_kien_id'])) {
            unset($data['loai_su_kien_id']);
        }
        
        // Đảm bảo trạng thái có giá trị mặc định là 1 (Hoạt động) nếu không được gửi lên
        if (!isset($data['status'])) {
            $data['status'] = 1;
        }
        
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
            // Lưu dữ liệu
            $insertId = $this->model->insert($data);
            
            if ($insertId) {
                $this->alert->set('success', 'Thêm mới ' . $this->title . ' thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                $this->alert->set('error', 'Thêm mới ' . $this->title . ' thất bại', true);
                return redirect()->back()->withInput();
            }
        } catch (DataException $e) {
            // Ghi log lỗi
            log_message('error', '[' . $this->controller_name . '::create] Lỗi khi lưu dữ liệu: ' . $e->getMessage());
            
            // Hiển thị thông báo lỗi
            $this->alert->set('error', 'Đã xảy ra lỗi khi lưu dữ liệu: ' . $e->getMessage(), true);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Hiển thị form chỉnh sửa đăng ký sự kiện
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
                ->with('error', 'Không tìm thấy thông tin đăng ký sự kiện');
        }

        // Lấy danh sách sự kiện cho dropdown
        $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
        $suKiens = $suKienModel->where('deleted_at', null)
                              ->orderBy('created_at', 'DESC')
                              ->findAll();

        return view('App\Modules\\' . $this->module_name . '\Views\edit', [
            'module_name' => $this->module_name,
            'title' => $this->title,
            'title_home' => $this->title_home,
            'action' => site_url($this->module_name . '/update/' . $id),
            'data' => $data,
            'suKiens' => $suKiens,
            'pager' => null,
            'perPage' => 1,
            'total' => 1
        ]);
    }
    
    /**
     * Xử lý cập nhật loại sự kiện
     */
    public function update($id = null)
    {
        // Kiểm tra xem bản ghi có tồn tại không
        $loaiSuKien = $this->model->find($id);
        if (!$loaiSuKien) {
            $this->alert->set('error', 'Không tìm thấy ' . $this->title . ' với ID: ' . $id, true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
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
            // Đảm bảo status có giá trị phù hợp
            if (!isset($data['status'])) {
                $data['status'] = 1;
            }
            
            // Cập nhật dữ liệu
            $updated = $this->model->update($id, $data);
            
            if ($updated) {
                $this->alert->set('success', 'Cập nhật ' . $this->title . ' thành công', true);
                
                // Xử lý redirect: quay lại trang trước hoặc trang danh sách
                $returnUrl = $this->request->getPost('return_url');
                if (!empty($returnUrl)) {
                    $returnUrl = $this->processReturnUrl($returnUrl);
                    return redirect()->to($returnUrl);
                }
                
                return redirect()->to($this->moduleUrl);
            } else {
                $this->alert->set('error', 'Cập nhật ' . $this->title . ' thất bại', true);
                return redirect()->back()->withInput();
            }
        } catch (DataException $e) {
            // Ghi log lỗi
            log_message('error', '[' . $this->controller_name . '::update] Lỗi khi cập nhật dữ liệu: ' . $e->getMessage());
            
            // Hiển thị thông báo lỗi
            $this->alert->set('error', 'Đã xảy ra lỗi khi cập nhật dữ liệu: ' . $e->getMessage(), true);
            return redirect()->back()->withInput();
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
            return redirect()->to(site_url($this->module_name . '/listdeleted'))
                ->with('success', 'Xóa thông tin đăng ký sự kiện thành công');
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
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Đảm bảo các model relationship được khởi tạo
        $this->initializeRelationTrait();
        
        // Lấy thông tin dữ liệu cơ bản
        $data = $this->model->find($id);
        
        if (empty($data)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu đăng ký sự kiện', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin sự kiện
        $suKien = $data->getSuKien();
        
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
        
        // Bật debug để hiển thị câu SQL
        //$this->db->debug = true;
        
        // Lấy dữ liệu đã xóa và thông tin phân trang
        $pageData = $this->model->searchDeleted($criteria, $options);
        
        // Tắt debug
        //$this->db->debug = false;
        
        // Kiểm tra và lọc dữ liệu để đảm bảo chỉ lấy bản ghi đã xóa
        $filteredData = [];
        foreach ($pageData as $item) {
            if ($item->deleted_at !== null) {
                $filteredData[] = $item;
            } else {
                log_message('warning', "[QuanLyCheckOutSuKien::listdeleted] Lọc bỏ bản ghi không hợp lệ (deleted_at = null): ID={$item->checkout_sukien_id}");
            }
        }
        
        // Nếu có sự khác biệt giữa dữ liệu gốc và sau khi lọc
        if (count($pageData) !== count($filteredData)) {
            log_message('warning', "[QuanLyCheckOutSuKien::listdeleted] Đã lọc bỏ " . (count($pageData) - count($filteredData)) . " bản ghi không hợp lệ");
            // Gán lại dữ liệu đã lọc
            $pageData = $filteredData;
        }
        
        // Log số lượng kết quả tìm được
        log_message('debug', '[QuanLyCheckOutSuKien::listdeleted] Found ' . count($pageData) . ' deleted records');
        
        // In thông tin của các bản ghi để debug
        if (!empty($pageData)) {
            foreach ($pageData as $index => $item) {
                log_message('debug', "[QuanLyCheckOutSuKien::listdeleted] Record {$index}: ID={$item->checkout_sukien_id}, deleted_at=" . 
                    ($item->deleted_at ? $item->getDeletedAtFormatted() : 'NULL'));
            }
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
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order', 'su_kien_id', 'checkout_type', 'hinh_thuc_tham_gia', 'start_date', 'end_date']);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Xử lý dữ liệu nếu cần
        $processedData = $this->processData($pageData);
        
        // Lấy danh sách loại người dùng cho filter
        $loaiNguoiDungOptions = $this->model->getLoaiNguoiDungOptions();
        
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
            'su_kien_id' => $params['su_kien_id'] ?? '',
            'checkout_type' => $params['checkout_type'] ?? '',
            'hinh_thuc_tham_gia' => $params['hinh_thuc_tham_gia'] ?? '',
            'start_date' => $params['start_date'] ?? '',
            'end_date' => $params['end_date'] ?? '',
            'module_name' => $this->module_name,
            'masterScript' => $this->masterScript,
            'loaiNguoiDungOptions' => $loaiNguoiDungOptions
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
     * Xây dựng tiêu chí tìm kiếm từ tham số
     *
     * @param array $params Tham số tìm kiếm
     * @return array
     */
    protected function buildSearchCriteria(array $params): array
    {
        $criteria = [];
        
        // Xử lý từ khóa tìm kiếm
        if (!empty($params['keyword'])) {
            $criteria['keyword'] = $params['keyword'];
        }
        
        // Xử lý ngày bắt đầu
        if (!empty($params['start_date'])) {
            $criteria['start_date'] = $params['start_date'];
        }
        
        // Xử lý ngày kết thúc
        if (!empty($params['end_date'])) {
            $criteria['end_date'] = $params['end_date'];
        }
        
        return $criteria;
    }
    
    /**
     * Xây dựng tùy chọn tìm kiếm từ tham số
     *
     * @param array $params Tham số tìm kiếm
     * @return array
     */
    protected function buildSearchOptions(array $params): array
    {
        $options = [];
        
        // Xử lý phân trang
        $options['limit'] = $params['perPage'];
        $options['offset'] = ($params['page'] - 1) * $params['perPage'];
        
        // Sắp xếp mặc định theo thời gian tạo giảm dần
        $options['sort'] = 'created_at';
        $options['order'] = 'DESC';
        
        return $options;
    }
    
    /**
     * Chuẩn bị tham số tìm kiếm từ request
     *
     * @param \CodeIgniter\HTTP\IncomingRequest $request
     * @return array
     */
    protected function prepareSearchParams($request): array
    {
        return [
            'keyword' => $request->getGet('keyword'),
            'start_date' => $request->getGet('start_date'),
            'end_date' => $request->getGet('end_date'),
            'perPage' => (int) ($request->getGet('perPage') ?? 10),
            'page' => max(1, (int) ($request->getGet('page') ?? 1))
        ];
    }
    
    /**
     * Xử lý tham số tìm kiếm
     *
     * @param array $params Tham số tìm kiếm
     * @return array
     */
    protected function processSearchParams(array $params): array
    {
        // Xử lý từ khóa tìm kiếm
        if (isset($params['keyword'])) {
            $params['keyword'] = trim($params['keyword']);
        }
        
        // Xử lý ngày bắt đầu
        if (!empty($params['start_date'])) {
            $params['start_date'] = date('Y-m-d H:i:s', strtotime($params['start_date']));
        }
        
        // Xử lý ngày kết thúc
        if (!empty($params['end_date'])) {
            $params['end_date'] = date('Y-m-d H:i:s', strtotime($params['end_date']));
        }
        
        return $params;
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
            'total' => $params['total'] ?? count($processedData),
            'suKiens' => $params['suKiens'] ?? []
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
     * Xuất dữ liệu ra file Excel
     */
    public function exportExcel()
    {
        try {
            // Lấy các tham số tìm kiếm từ URL
            $params = $this->prepareSearchParams($this->request);
            $params = $this->processSearchParams($params);
            
            // Xây dựng tiêu chí và tùy chọn tìm kiếm
            $criteria = $this->buildSearchCriteria($params);
            $options = $this->buildSearchOptions($params);
            
            // Loại bỏ giới hạn kết quả để xuất tất cả dữ liệu
            $options['limit'] = 0;
            
            // Lấy dữ liệu từ model
            $data = $this->model->search($criteria, $options);
            
            // Ghi log
            log_message('info', 'Xuất Excel loại sự kiện: ' . count($data) . ' bản ghi');
            
            // Tạo tên file
            $filename = 'danh_sach_loai_su_kien_' . date('YmdHis');
            
            // Tạo và xuất file Excel
            return $this->createExcelFile($data, $this->getExportHeaders(), $params, $filename);
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
            // Lấy các tham số tìm kiếm từ URL
            $params = $this->prepareSearchParams($this->request);
            $params = $this->processSearchParams($params);
            
            // Xây dựng tiêu chí và tùy chọn tìm kiếm cho dữ liệu đã xóa
            $criteria = $this->buildSearchCriteria($params);
            $criteria['ignoreDeletedCheck'] = true; // Chỉ lấy bản ghi đã xóa
            
            $options = $this->buildSearchOptions($params);
            $options['limit'] = 0; // Không giới hạn kết quả
            
            // Chắc chắn model đang ở chế độ withDeleted
            $this->model->onlyDeleted();
            
            // Lấy dữ liệu đã xóa từ model
            $data = $this->model->search($criteria, $options);
            
            // Ghi log
            log_message('info', 'Xuất Excel loại sự kiện đã xóa: ' . count($data) . ' bản ghi');
            
            // Tạo tên file
            $filename = 'danh_sach_loai_su_kien_da_xoa_' . date('YmdHis');
            
            // Tạo và xuất file Excel với cột ngày xóa
            return $this->createExcelFile($data, $this->getExportHeaders(true), $params, $filename, true);
        } catch (\Exception $e) {
            log_message('error', 'Lỗi xuất Excel đã xóa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xuất Excel đã xóa: ' . $e->getMessage());
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