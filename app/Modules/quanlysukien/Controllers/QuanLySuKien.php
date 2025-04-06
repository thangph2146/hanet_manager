<?php

namespace App\Modules\quanlysukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\quanlysukien\Models\SuKienModel;
use App\Modules\quanlysukien\Libraries\Pager;
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
use App\Modules\quanlysukien\Traits\ExportTrait;
use App\Modules\quanlysukien\Traits\RelationTrait;
use App\Modules\quanlyloaisukien\Models\LoaiSuKienModel;
use App\Libraries\Pdf;
use App\Libraries\ExcelExport;

class QuanLySuKien extends BaseController
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
    protected $module_name = 'quanlysukien';
    protected $controller_name = 'QuanLySuKien';
    protected $masterScript;
    protected $loaiSuKienModel;
    protected $perPage = 10;
    protected $surroundCount = 2;
    
    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');

        // Khởi tạo các thành phần cần thiết
        $this->model = new SuKienModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        $this->loaiSuKienModel = new LoaiSuKienModel();
        
        // Thông tin module
        $this->moduleUrl = base_url($this->module_name);
        $this->title = 'Sự kiện';
        $this->title_home = 'Danh sách sự kiện';
        
        // Khởi tạo thư viện MasterScript với module_name
        $masterScriptClass = "\App\Modules\\" . $this->module_name . '\Libraries\MasterScript';
        $this->masterScript = new $masterScriptClass($this->module_name);
        
        // Khởi tạo các model quan hệ
        $this->initializeRelationTrait();
        
        $this->model->setSurroundCount($this->surroundCount);
    }
    
    /**
     * Hiển thị danh sách sự kiện với phân trang
     */
    public function index()
    {
        // Lấy các tham số từ URL
        $params = $this->prepareSearchParams($this->request);
        // Xử lý tham số tìm kiếm
        $processedParams = $this->processSearchParams($params);
        
        // Xây dựng tiêu chí và tùy chọn tìm kiếm
        $criteria = $this->buildSearchCriteria($processedParams);
        $options = $this->buildSearchOptions($processedParams);
        
        // Lấy dữ liệu sự kiện và thông tin phân trang
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
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order', 'loai_su_kien_id', 'hinh_thuc', 'start_date', 'end_date']);
            
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
     * Hiển thị form thêm mới sự kiện
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
     * Xử lý thêm mới sự kiện
     */
    public function create()
    {
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Loại bỏ su_kien_id khi thêm mới vì là trường auto_increment
        if (isset($data['su_kien_id'])) {
            unset($data['su_kien_id']);
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
     * Hiển thị form chỉnh sửa sự kiện
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
                ->with('error', 'Không tìm thấy thông tin sự kiện');
        }

        return view('App\Modules\\' . $this->module_name . '\Views\edit', [
            'module_name' => $this->module_name,
            'title' => 'Chỉnh sửa ' . $this->title,
            'title_home' => $this->title_home,
            'action' => site_url($this->module_name . '/update/' . $id),
            'data' => $data,
            'pager' => null,
            'perPage' => 1,
            'total' => 1
        ]);
    }
    
    /**
     * Xử lý cập nhật sự kiện
     */
    public function update($id = null)
    {
        // Kiểm tra xem bản ghi có tồn tại không
        $suKien = $this->model->find($id);
        if (!$suKien) {
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
     * Xử lý xóa sự kiện
     */
    public function delete($id = null)
    {
        if (!$id) {
            return redirect()->to($this->moduleUrl)
                ->with('error', 'ID không hợp lệ');
        }

        if ($this->model->delete($id)) {
            return redirect()->to(site_url($this->module_name . '/listdeleted'))
                ->with('success', 'Xóa thông tin sự kiện thành công');
        }

        return redirect()->to($this->moduleUrl)
            ->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
    }
 
   
    /**
     * Hiển thị chi tiết sự kiện
     */
    public function detail($id = null)
    {
        if (!$id) {
            $this->alert->set('danger', 'ID sự kiện không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }

        // Lấy thông tin sự kiện
        $data = $this->model->find($id);
        if (!$data) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu sự kiện', true);
            return redirect()->to($this->moduleUrl);
        }

        // Xử lý dữ liệu
        $processedData = $this->processData([$data]);
        $data = $processedData[0] ?? $data;

        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chi tiết', current_url());

        // Sửa đường dẫn view để phù hợp với cấu trúc module
        return view('App\Modules\\' . $this->module_name . '\Views\detail', [
            'data' => $data,
            'module_name' => $this->module_name,
            'title' => 'Chi tiết sự kiện',
            'title_home' => $this->title_home,
            'breadcrumb' => $this->breadcrumb->render()
        ]);
    }
    
    /**
     * Danh sách sự kiện đã xóa
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
        log_message('debug', '[QuanLySuKien::listdeleted] Search criteria: ' . json_encode($criteria));
        log_message('debug', '[QuanLySuKien::listdeleted] Search options: ' . json_encode($options));
        
        // Lấy dữ liệu đã xóa và thông tin phân trang
        $pageData = $this->model->searchDeleted($criteria, $options);
        
        // Kiểm tra và lọc dữ liệu để đảm bảo chỉ lấy bản ghi đã xóa
        $filteredData = [];
        foreach ($pageData as $item) {
            if ($item->deleted_at !== null) {
                $filteredData[] = $item;
            } else {
                log_message('warning', "[QuanLySuKien::listdeleted] Lọc bỏ bản ghi không hợp lệ (deleted_at = null): ID={$item->su_kien_id}");
            }
        }
        
        // Nếu có sự khác biệt giữa dữ liệu gốc và sau khi lọc
        if (count($pageData) !== count($filteredData)) {
            log_message('warning', "[QuanLySuKien::listdeleted] Đã lọc bỏ " . (count($pageData) - count($filteredData)) . " bản ghi không hợp lệ");
            // Gán lại dữ liệu đã lọc
            $pageData = $filteredData;
        }
        
        // Log số lượng kết quả tìm được
        log_message('debug', '[QuanLySuKien::listdeleted] Found ' . count($pageData) . ' deleted records');
        
        // Lấy tổng số kết quả
        $pager = $this->model->getPager();
        $total = $pager ? $pager->getTotal() : $this->model->countDeletedSearchResults($criteria);
        
        // Log tổng số kết quả
        log_message('debug', '[QuanLySuKien::listdeleted] Total deleted records: ' . $total);
        
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
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order', 'loai_su_kien_id', 'hinh_thuc', 'start_date', 'end_date']);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($params['perPage']);
            $pager->setCurrentPage($params['page']);
        }
        
        // Xử lý dữ liệu nếu cần
        $processedData = $this->processData($pageData);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'title_home' => $this->title_home,
            'title' => 'Sự kiện đã xóa',
            'processedData' => $processedData,
            'pager' => $pager,
            'total' => $total,
            'perPage' => $params['perPage'],
            'keyword' => $params['keyword'] ?? '',
            'status' => $params['status'] ?? '',
            'loai_su_kien_id' => $params['loai_su_kien_id'] ?? '',
            'hinh_thuc' => $params['hinh_thuc'] ?? '',
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
            
            log_message('debug', '[' . $this->controller_name . '::restoreMultiple] Restored ' . $successCount . ' out of ' . count($idArray) . ' sự kiện');
            
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
     * Xử lý thay đổi trạng thái nhiều sự kiện
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
                if ($this->model->update($id, ['status' => $newStatus])) {
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
     * Xác minh khuôn mặt cho sự kiện
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
            'sort' => $request->getGet('sort') ?? 'ten_su_kien',
            'order' => $request->getGet('order') ?? 'DESC',
            'loai_su_kien_id' => $request->getGet('loai_su_kien_id') ?? '',
            'hinh_thuc' => $request->getGet('hinh_thuc') ?? '',
            'start_date' => $request->getGet('start_date') ?? '',
            'end_date' => $request->getGet('end_date') ?? '',
            'face_verified' => $request->getGet('face_verified') ?? '',
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
            $params['sort'] = 'thoi_gian_bat_dau';
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
            // Tạo một mảng các điều kiện tìm kiếm OR
            $orConditions = [];
            
            // Tìm kiếm theo tên sự kiện
            $orConditions['ten_su_kien'] = $params['keyword'];
            
            // Tìm kiếm theo mô tả
            $orConditions['mo_ta'] = $params['keyword'];
            
            // Tìm kiếm theo địa điểm
            $orConditions['dia_diem'] = $params['keyword'];
            
            // Thêm điều kiện tìm kiếm OR vào criteria
            $criteria['keyword'] = $orConditions;
        }
        
        // Lọc theo trạng thái
        if (isset($params['status']) && $params['status'] !== '') {
            $criteria['status'] = $params['status'];
        }
        
        // Lọc theo loại sự kiện
        if (!empty($params['loai_su_kien_id'])) {
            $criteria['loai_su_kien_id'] = $params['loai_su_kien_id'];
        }
        
        // Lọc theo hình thức
        if (!empty($params['hinh_thuc'])) {
            $criteria['hinh_thuc'] = $params['hinh_thuc'];
        }
        
        // Lọc theo thời gian
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
            'sort' => $params['sort'] ?? 'thoi_gian_bat_dau',
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
            'loai_su_kien_id' => $params['loai_su_kien_id'] ?? '',
            'hinh_thuc' => $params['hinh_thuc'] ?? '',
            'start_date' => $params['start_date'] ?? '',
            'end_date' => $params['end_date'] ?? '',
            'face_verified' => $params['face_verified'] ?? '',
            'perPage' => $params['perPage'] ?? 10,
            'perPageOptions' => $perPageOptions,
            'sort' => $params['sort'] ?? 'thoi_gian_bat_dau',
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
            // Xử lý các trường JSON nếu có
            if (property_exists($item, 'su_kien_poster') && !empty($item->su_kien_poster)) {
                if (is_string($item->su_kien_poster)) {
                    try {
                        $data[$index]->su_kien_poster_data = json_decode($item->su_kien_poster);
                    } catch (\Exception $e) {
                        log_message('error', 'Lỗi khi xử lý dữ liệu JSON của poster: ' . $e->getMessage());
                        $data[$index]->su_kien_poster_data = null;
                    }
                }
            }
            
            // Xử lý lịch trình
            if (property_exists($item, 'lich_trinh') && !empty($item->lich_trinh)) {
                if (is_string($item->lich_trinh)) {
                    try {
                        $data[$index]->lich_trinh_data = json_decode($item->lich_trinh);
                    } catch (\Exception $e) {
                        log_message('error', 'Lỗi khi xử lý dữ liệu JSON của lịch trình: ' . $e->getMessage());
                        $data[$index]->lich_trinh_data = null;
                    }
                }
            }
            
            // Thêm thông tin loại sự kiện nếu cần
            if (property_exists($item, 'loai_su_kien_id') && !empty($item->loai_su_kien_id)) {
                try {
                    $loaiSuKien = $this->loaiSuKienModel->find($item->loai_su_kien_id);
                    if ($loaiSuKien) {
                        $data[$index]->ten_loai_su_kien = $loaiSuKien->getTenLoaiSuKien();
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Lỗi khi lấy thông tin loại sự kiện: ' . $e->getMessage());
                }
            }
            
            // Định dạng hiển thị của hình thức
            if (property_exists($item, 'hinh_thuc')) {
                switch ($item->hinh_thuc) {
                    case 'offline':
                        $data[$index]->hinh_thuc_text = 'Offline';
                        $data[$index]->hinh_thuc_badge = 'badge bg-info';
                        break;
                    case 'online':
                        $data[$index]->hinh_thuc_text = 'Online';
                        $data[$index]->hinh_thuc_badge = 'badge bg-primary';
                        break;
                    case 'hybrid':
                        $data[$index]->hinh_thuc_text = 'Hybrid';
                        $data[$index]->hinh_thuc_badge = 'badge bg-warning';
                        break;
                    default:
                        $data[$index]->hinh_thuc_text = ucfirst($item->hinh_thuc);
                        $data[$index]->hinh_thuc_badge = 'badge bg-secondary';
                        break;
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
        
        // Lấy danh sách loại sự kiện nếu cần
        $danhSachLoaiSuKien = $this->loaiSuKienModel->findAll();
        
        return [
            'data' => $data,
            'title' => $title,
            'module_name' => $module_name,
            'danhSachLoaiSuKien' => $danhSachLoaiSuKien
        ];
    }

    /**
     * Xóa vĩnh viễn một sự kiện
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
            $this->alert->set('success', 'Đã xóa vĩnh viễn sự kiện', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa sự kiện', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }

    /**
     * Xóa vĩnh viễn nhiều sự kiện
     */
    public function permanentDeleteMultiple()
    {
        // Lấy các ID được chọn từ form và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn sự kiện nào để xóa vĩnh viễn', true);
            
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
            $this->alert->set('success', "Đã xóa vĩnh viễn $successCount sự kiện", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa sự kiện', true);
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
            log_message('info', 'Xuất Excel sự kiện: ' . count($data) . ' bản ghi');
            
            // Tạo tên file
            $filename = 'danh_sach_su_kien_' . date('YmdHis');
            
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
            log_message('info', 'Xuất Excel sự kiện đã xóa: ' . count($data) . ' bản ghi');
            
            // Tạo tên file
            $filename = 'danh_sach_su_kien_da_xoa_' . date('YmdHis');
            
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
        $templatePath = 'App\Modules\\' . $this->module_name . '\Views\export\pdf_template';
        
        // Chuẩn bị dữ liệu để đưa vào template
        $viewData = [
            'data' => $data,
            'filters' => $filters,
            'deleted' => $includeDeleted,
            'title' => 'DANH SÁCH SỰ KIỆN' . ($includeDeleted ? ' ĐÃ XÓA' : ''),
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
            'Tên sự kiện' => 'C',
            'Mô tả' => 'D',
            'Thời gian bắt đầu' => 'E',
            'Thời gian kết thúc' => 'F',
            'Địa điểm' => 'G',
            'Địa chỉ cụ thể' => 'H',
            'Loại sự kiện' => 'I',
            'Hình thức' => 'J',
            'Trạng thái' => 'K',
            'Lượt đăng ký' => 'L',
            'Lượt check-in' => 'M',
            'Lượt check-out' => 'N',
            'Ngày tạo' => 'O',
            'Ngày cập nhật' => 'P'
        ];

        if ($includeDeleted) {
            $headers['Ngày xóa'] = 'Q';
        }

        return $headers;
    }

    /**
     * Xử lý việc lưu dữ liệu sự kiện từ form
     */
    private function processSaveData($data, $id = null)
    {
        helper(['form', 'filesystem']);
        
        // Chuẩn bị dữ liệu
        $saveData = [
            'ten_su_kien' => $data['ten_su_kien'] ?? '',
            'mo_ta' => $data['mo_ta'] ?? '',
            'chi_tiet_su_kien' => $data['chi_tiet_su_kien'] ?? '',
            'thoi_gian_bat_dau' => $data['thoi_gian_bat_dau'] ?? null,
            'thoi_gian_ket_thuc' => $data['thoi_gian_ket_thuc'] ?? null,
            'don_vi_to_chuc' => $data['don_vi_to_chuc'] ?? '',
            'don_vi_phoi_hop' => $data['don_vi_phoi_hop'] ?? '',
            'doi_tuong_tham_gia' => $data['doi_tuong_tham_gia'] ?? '',
            'dia_diem' => $data['dia_diem'] ?? '',
            'dia_chi_cu_the' => $data['dia_chi_cu_the'] ?? '',
            'loai_su_kien_id' => $data['loai_su_kien_id'] ?? 0,
            'status' => isset($data['status']) ? (int)$data['status'] : 0,
            'hinh_thuc' => $data['hinh_thuc'] ?? 'offline',
            'so_luong_tham_gia' => $data['so_luong_tham_gia'] ?? 0,
            'toa_do_gps' => $data['toa_do_gps'] ?? '',
            'tu_khoa_su_kien' => $data['tu_khoa_su_kien'] ?? '',
            'hashtag' => $data['hashtag'] ?? '',
            'thoi_gian_checkin_bat_dau' => $data['thoi_gian_checkin_bat_dau'] ?? null,
            'thoi_gian_checkin_ket_thuc' => $data['thoi_gian_checkin_ket_thuc'] ?? null,
            'gio_bat_dau' => $data['gio_bat_dau'] ?? null,
            'gio_ket_thuc' => $data['gio_ket_thuc'] ?? null,
            'bat_dau_dang_ky' => $data['bat_dau_dang_ky'] ?? null,
            'ket_thuc_dang_ky' => $data['ket_thuc_dang_ky'] ?? null,
            'han_huy_dang_ky' => $data['han_huy_dang_ky'] ?? null,
            'link_online' => $data['link_online'] ?? '',
            'mat_khau_online' => $data['mat_khau_online'] ?? ''
        ];

        // Lưu dữ liệu vào model
        if ($id) {
            $updated = $this->model->update($id, $saveData);
            if ($updated) {
                $this->alert->set('success', 'Cập nhật sự kiện thành công', true);
            } else {
                $this->alert->set('danger', 'Có lỗi xảy ra khi cập nhật sự kiện', true);
            }
        } else {
            $insertId = $this->model->insert($saveData);
            if ($insertId) {
                $this->alert->set('success', 'Thêm mới sự kiện thành công', true);
            } else {
                $this->alert->set('danger', 'Có lỗi xảy ra khi thêm mới sự kiện', true);
            }
        }
    }

}