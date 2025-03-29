<?php

namespace App\Modules\checkoutsukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\checkoutsukien\Models\CheckOutSuKienModel;
use App\Modules\dangkysukien\Models\DangKySuKienModel;
use App\Modules\sukien\Models\SuKienModel;
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
use App\Modules\checkoutsukien\Traits\ExportTrait;
use App\Modules\checkoutsukien\Traits\RelationTrait;

class CheckOutSuKien extends BaseController
{
    use ResponseTrait;
    use ExportTrait;
    use RelationTrait;
    
    protected $model;
    protected $suKienModel;
    protected $alert;
    protected $moduleUrl;
    protected $title = 'Check Out Sự Kiện';
    protected $module_name = 'checkoutsukien';
    protected $controller_name = 'CheckOutSuKien';
    protected $primary_key = 'checkout_sukien_id';
    // Search
    protected $field_sort = 'ngay_checkout';
    protected $field_order = 'DESC';
    protected $limit = 100;

    // Export
    protected $export_excel = 'danh_sach_checkout_su_kien_excel';
    protected $export_excel_title = 'DANH SÁCH CHECK OUT SỰ KIỆN (Excel)';

    protected $export_pdf = 'danh_sach_checkout_su_kien_pdf';
    protected $export_pdf_title = 'DANH SÁCH CHECK OUT SỰ KIỆN (PDF)';

    protected $export_excel_deleted = 'danh_sach_checkout_su_kien_da_xoa_excel';
    protected $export_excel_deleted_title = 'DANH SÁCH CHECK OUT SỰ KIỆN ĐÃ XÓA (Excel)';

    protected $export_pdf_deleted = 'danh_sach_checkout_su_kien_da_xoa_pdf';
    protected $export_pdf_deleted_title = 'DANH SÁCH CHECK OUT SỰ KIỆN ĐÃ XÓA (PDF)';

    protected $pager_only = [
        'keyword', 
        'perPage', 
        'sort', 
        'order', 
        'status', 
        'su_kien_id',
        'checkout_type',
        'face_verified',
        'hinh_thuc_tham_gia',
        'dangky_sukien_id',
        'checkin_sukien_id',
        'start_date',
        'end_date',
        'danh_gia'
    ];

    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');

        // Khởi tạo các thành phần cần thiết
        $this->model = new CheckOutSuKienModel();
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
        
        // Lấy danh sách sự kiện từ model
        $viewData['suKienList'] = model('App\Modules\sukien\Models\SuKienModel')->findAll();
        
        // Thêm dữ liệu cho view
        $viewData['title'] = 'Thêm mới ' . $this->title;
        $viewData['validation'] = \Config\Services::validation();
        $viewData['errors'] = session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []);
        $viewData['action'] = site_url($this->module_name . '/create');
        $viewData['method'] = 'POST';
        
        return view('App\Modules\\' . $this->module_name . '\Views\new', $viewData);
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
        
        // Tự động load dữ liệu quan hệ qua gọi phương thức getter
        // Không cần lưu lại kết quả, chỉ cần gọi để tải dữ liệu
        $data->getSuKien();
        $data->getDangKySuKien();
        $data->getCheckInSuKien();
        
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
        
        // Lấy danh sách sự kiện từ model
        $viewData['suKienList'] = model('App\Modules\sukien\Models\SuKienModel')->findAll();
        
        // Thêm dữ liệu cho view
        $viewData['title'] = 'Chỉnh sửa ' . $this->title;
        $viewData['validation'] = \Config\Services::validation();
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
        
        // Nếu là phương thức GET, chuyển đến trang edit
        if ($this->request->getMethod() === 'get') {
            return $this->edit($id);
        }
        
        // Kiểm tra xem bản ghi tồn tại không
        $existingRecord = $this->model->find($id);
        if (empty($existingRecord)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
    
        // Đảm bảo có ID của bản ghi đang cập nhật
        $data[$this->primary_key] = $id;
        
        // Xử lý định dạng thời gian check-out
        if (!empty($data['thoi_gian_check_out'])) {
            $data['thoi_gian_check_out'] = $this->model->formatDateTime($data['thoi_gian_check_out']);
        }
        
        // Xử lý upload ảnh face nếu có
        $faceImage = $this->request->getFile('face_image');
        if ($faceImage && $faceImage->isValid() && !$faceImage->hasMoved()) {
            try {
                // Tạo thư mục nếu chưa tồn tại
                $uploadPath = ROOTPATH . 'public/uploads/face_images';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Di chuyển file và cập nhật đường dẫn
                $newName = $faceImage->getRandomName();
                $faceImage->move($uploadPath, $newName);
                
                // Xóa file ảnh cũ nếu có
                if (!empty($existingRecord->face_image_path)) {
                    $oldFilePath = ROOTPATH . 'public' . $existingRecord->face_image_path;
                    if (file_exists($oldFilePath)) {
                        @unlink($oldFilePath);
                    }
                }
                
                $data['face_image_path'] = '/uploads/face_images/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'Lỗi upload ảnh: ' . $e->getMessage());
            }
        }
        
        // Xử lý các trường nullable (có thể null)
        $nullableFields = [
            'dangky_sukien_id', 'checkin_sukien_id', 'face_match_score',
            'ma_xac_nhan', 'danh_gia', 'ghi_chu', 'noi_dung_danh_gia',
            'feedback', 'attendance_duration_minutes'
        ];
        
        foreach ($nullableFields as $field) {
            if (isset($data[$field]) && (trim((string)$data[$field]) === '' || $data[$field] === null)) {
                $data[$field] = null;
            }
        }
        
        // Xử lý các trường checkbox (chuyển đổi thành boolean)
        $data['face_verified'] = isset($data['face_verified']) ? 1 : 0;
        
        // Xử lý dữ liệu số
        if (isset($data['face_match_score']) && $data['face_match_score'] !== null) {
            // Thay thế dấu phẩy bằng dấu chấm (nếu có) trước khi chuyển đổi
            $data['face_match_score'] = str_replace(',', '.', (string)$data['face_match_score']);
            $data['face_match_score'] = (float)$data['face_match_score'];
            // Đảm bảo giá trị nằm trong khoảng [0, 1]
            if ($data['face_match_score'] < 0) $data['face_match_score'] = 0;
            if ($data['face_match_score'] > 1) $data['face_match_score'] = 1;
        }
        
        if (isset($data['danh_gia']) && $data['danh_gia'] !== null) {
            $data['danh_gia'] = (int)$data['danh_gia'];
            // Đảm bảo giá trị nằm trong khoảng [1, 5]
            if ($data['danh_gia'] < 1) $data['danh_gia'] = 1;
            if ($data['danh_gia'] > 5) $data['danh_gia'] = 5;
        }
        
        if (isset($data['attendance_duration_minutes']) && $data['attendance_duration_minutes'] !== null) {
            $data['attendance_duration_minutes'] = (int)$data['attendance_duration_minutes'];
        }
        
        // Cập nhật IP address
        $data['ip_address'] = $this->request->getIPAddress();
        
        // Xử lý thông tin vị trí và thiết bị nếu được cung cấp
        if (!empty($data['location_data'])) {
            $data['location_data'] = json_encode($data['location_data']);
        }
        
        if (!empty($data['device_info'])) {
            $data['device_info'] = json_encode($data['device_info']);
        }
        // Chuẩn bị quy tắc validation cho cập nhật
        $this->model->prepareValidationRules('update', $data);
        
        // Kiểm tra dữ liệu
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        try {
            // Lưu dữ liệu thông qua model
            if ($this->model->update($id, $data)) {
                $this->alert->set('success', 'Cập nhật ' . $this->title . ' thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                throw new \RuntimeException('Không thể cập nhật ' . $this->title);
            }
        } catch (\Exception $e) {
            log_message('error', 'Lỗi cập nhật checkout sự kiện: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật ' . $this->title . ': ' . $e->getMessage());
        }
    }
    
    /**
     * Xử lý thêm mới dữ liệu
     */
    public function create()
    {
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
        // Xử lý các trường nullable (có thể null)
        $nullableFields = [
            'dangky_sukien_id', 'checkin_sukien_id', 'face_match_score',
            'ma_xac_nhan', 'danh_gia', 'ghi_chu', 'noi_dung_danh_gia',
            'feedback', 'attendance_duration_minutes', 'thong_tin_bo_sung'
        ];
        
        foreach ($nullableFields as $field) {
            if (isset($data[$field]) && (trim((string)$data[$field]) === '' || $data[$field] === null)) {
                $data[$field] = null;
            }
        }
        
        // Chuyển đổi dữ liệu sang đúng kiểu
        if (isset($data['su_kien_id'])) {
            $data['su_kien_id'] = (int)$data['su_kien_id'];
        }
        
        if (isset($data['dangky_sukien_id']) && $data['dangky_sukien_id'] !== null) {
            $data['dangky_sukien_id'] = (int)$data['dangky_sukien_id'];
        }
        
        if (isset($data['checkin_sukien_id']) && $data['checkin_sukien_id'] !== null) {
            $data['checkin_sukien_id'] = (int)$data['checkin_sukien_id'];
        }
        
        if (isset($data['attendance_duration_minutes']) && $data['attendance_duration_minutes'] !== null) {
            $data['attendance_duration_minutes'] = (int)$data['attendance_duration_minutes'];
        }
        
        // Xử lý định dạng thời gian check-out
        if (!empty($data['thoi_gian_check_out'])) {
            $data['thoi_gian_check_out'] = $this->model->formatDateTime($data['thoi_gian_check_out']);
        }
        
        // Xử lý face_match_score
        if (isset($data['face_match_score']) && $data['face_match_score'] !== null) {
            // Thay thế dấu phẩy bằng dấu chấm (nếu có) trước khi chuyển đổi
            $data['face_match_score'] = str_replace(',', '.', (string)$data['face_match_score']);
            $data['face_match_score'] = (float)$data['face_match_score'];
            // Đảm bảo giá trị nằm trong khoảng [0, 1]
            if ($data['face_match_score'] < 0) $data['face_match_score'] = 0;
            if ($data['face_match_score'] > 1) $data['face_match_score'] = 1;
        }
        
        // Xử lý các trường checkbox (chuyển đổi thành boolean)
        $data['face_verified'] = isset($data['face_verified']) ? 1 : 0;
        
        // Xử lý danh_gia
        if (isset($data['danh_gia']) && $data['danh_gia'] !== null) {
            $data['danh_gia'] = (int)$data['danh_gia'];
            // Đảm bảo giá trị nằm trong khoảng [1, 5]
            if ($data['danh_gia'] < 1) $data['danh_gia'] = 1;
            if ($data['danh_gia'] > 5) $data['danh_gia'] = 5;
        }
        
        // Cập nhật IP address
        $data['ip_address'] = $this->request->getIPAddress();
        
        // Xử lý thông tin vị trí và thiết bị nếu được cung cấp
        if (!empty($data['location_data'])) {
            $data['location_data'] = $data['location_data'];
        }
        
        if (!empty($data['device_info'])) {
            $data['device_info'] = $data['device_info'];
        }
        
        // Xử lý upload ảnh face nếu có
        $faceImage = $this->request->getFile('face_image');
        if ($faceImage && $faceImage->isValid() && !$faceImage->hasMoved()) {
            try {
                // Tạo thư mục nếu chưa tồn tại
                $uploadPath = ROOTPATH . 'public/uploads/face_images';
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }
                
                // Di chuyển file và cập nhật đường dẫn
                $newName = $faceImage->getRandomName();
                $faceImage->move($uploadPath, $newName);
                
                $data['face_image_path'] = '/uploads/face_images/' . $newName;
            } catch (\Exception $e) {
                log_message('error', 'Lỗi upload ảnh: ' . $e->getMessage());
            }
        }
        
        // Ghi log dữ liệu để debug
        log_message('debug', 'Dữ liệu tạo mới CheckOutSuKien: ' . json_encode($data));
        
        // Chuẩn bị các quy tắc validation
        $this->model->prepareValidationRules('insert', $data);
        
        // Kiểm tra dữ liệu
        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            log_message('debug', 'Validation errors: ' . json_encode($this->validator->getErrors()));
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        try {
            // Lưu dữ liệu thông qua model
            if ($this->model->insert($data)) {
                $this->alert->set('success', 'Thêm mới ' . $this->title . ' thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                throw new \RuntimeException('Không thể thêm mới ' . $this->title);
            }
        } catch (\Exception $e) {
            log_message('error', 'Lỗi thêm mới checkout sự kiện: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi thêm mới ' . $this->title . ': ' . $e->getMessage());
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
        
        // Áp dụng thông tin ngày bắt đầu và kết thúc từ tham số URL
        // Chuyển đổi từ start_date/end_date thành tu_ngay/den_ngay để phù hợp với Model
        if (!empty($params['start_date'])) {
            $criteria['start_date'] = $params['start_date'];
        }
        
        if (!empty($params['end_date'])) {
            $criteria['end_date'] = $params['end_date'];
        }
        
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
        
        // Thêm danh sách sự kiện để hiển thị tên thay vì ID
        $viewData['suKienList'] = $this->suKienModel->findAll();
        
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
     *
     * @param int $id ID của bản ghi check-out
     * @param int $status Trạng thái muốn cập nhật (0: Vô hiệu, 1: Hoạt động, 2: Đang xử lý)
     * @return ResponseInterface
     */
    public function updateStatus($id = null, $status = null)
    {
        // Kiểm tra ID và status
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ: ' . $id, true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Đảm bảo status là số nguyên và nằm trong các giá trị hợp lệ
        $validStatus = ['0', '1', '2', 0, 1, 2];
        if (!in_array($status, $validStatus)) {
            $this->alert->set('danger', 'Trạng thái không hợp lệ: ' . $status, true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Đảm bảo ID là số nguyên
        $id = (int)$id;
        
        // Kiểm tra sự tồn tại của bản ghi
        $checkout = $this->model->find($id);
        if (empty($checkout)) {
            $this->alert->set('danger', 'Không tìm thấy bản ghi check-out có ID: ' . $id, true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Log thông tin trước khi cập nhật
        log_message('info', "Cập nhật trạng thái check-out ID: {$id}, từ trạng thái: {$checkout->getStatus()} sang: {$status}");
        
        // Cập nhật trạng thái thông qua model
        try {
            $result = $this->model->updateTrangThaiThamGia($id, (int)$status);
            if ($result) {
                $this->alert->set('success', 'Cập nhật trạng thái thành công cho ID: ' . $id, true);
            } else {
                $this->alert->set('danger', 'Có lỗi xảy ra khi cập nhật trạng thái cho ID: ' . $id, true);
                log_message('error', "Cập nhật trạng thái thất bại cho ID: {$id}, status: {$status}");
            }
        } catch (\Exception $e) {
            $this->alert->set('danger', 'Lỗi: ' . $e->getMessage(), true);
            log_message('error', "Exception khi cập nhật trạng thái: " . $e->getMessage());
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
    
    /**
     * Xử lý URL trả về
     * 
     * @param string|null $returnUrl URL trả về
     * @return string URL đã xử lý
     */
    protected function processReturnUrl($returnUrl = null)
    {
        if (empty($returnUrl)) {
            return $this->moduleUrl;
        }

        // Kiểm tra URL có phải là URL nội bộ không
        $baseUrl = base_url();
        if (strpos($returnUrl, $baseUrl) !== 0) {
            return $this->moduleUrl;
        }

        return $returnUrl;
    }

    /**
     * Tái tạo URL từ các thành phần đã phân tích
     *
     * @param array $parsedUrl Mảng chứa các thành phần của URL đã phân tích
     * @param array $query Các tham số query string
     * @return string URL đã được tái tạo
     */
    protected function buildUrl(array $parsedUrl, array $query = []): string
    {
        $scheme = $parsedUrl['scheme'] ?? '';
        $host = $parsedUrl['host'] ?? '';
        $path = $parsedUrl['path'] ?? '';
        
        // Tạo URL cơ bản
        $url = $scheme . '/' . $host . $path;
        
        // Thêm query string nếu có
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }
        
        return $url;
    }

    /**
     * Xóa vĩnh viễn nhiều dữ liệu
     */
    public function permanentDeleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedItems = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn dữ liệu nào để xóa vĩnh viễn', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        
        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        foreach ($idArray as $id) {
            try {
                if ($this->model->delete($id, true)) { // true = permanent delete
                    $successCount++;
                }
            } catch (\Exception $e) {
                log_message('error', "Lỗi khi xóa vĩnh viễn ID {$id}: " . $e->getMessage());
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
     * Chuẩn bị dữ liệu cho form
     *
     * @param string $module_name Tên module
     * @param object|null $data Dữ liệu hiện tại (nếu có)
     * @return array Dữ liệu chuẩn bị cho form
     */
    protected function prepareFormData(string $module_name, $data = null): array
    {
        $viewData = [
            'module_name' => $module_name,
            'data' => $data,
            'record' => $data,
            'validation' => $this->validator,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : [])
        ];
        
        return $viewData;
    }

    protected function prepareSearchOptions($sort = null, $order = null, $limit = null, $offset = null)
    {
        // Thay thế ngay_checkout bằng thoi_gian_check_out nếu là trường sắp xếp
        if ($sort === 'ngay_checkout') {
            $sort = 'thoi_gian_check_out';
        }
        
        return [
            'sort' => $sort ?? $this->field_sort,
            'order' => $order ?? $this->field_order,
            'limit' => $limit ?? (property_exists($this, 'limit') ? $this->limit : 100),
            'offset' => $offset ?? 0
        ];
    }
}