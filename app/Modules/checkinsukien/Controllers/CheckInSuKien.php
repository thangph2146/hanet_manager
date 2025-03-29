<?php

namespace App\Modules\checkinsukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\checkinsukien\Models\CheckInSuKienModel;
use App\Modules\sukien\Models\SuKienModel;
use App\Modules\dangkysukien\Models\DangKySuKienModel;
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
use App\Modules\checkinsukien\Traits\ExportTrait;
use App\Modules\checkinsukien\Traits\RelationTrait;

class CheckInSuKien extends BaseController
{
    use ResponseTrait;
    use ExportTrait;
    use RelationTrait;
    
    protected $module_name = 'checkinsukien';
    protected $moduleUrl;
    protected $model;
    protected $suKienModel;
    protected $dangKySuKienModel;
    protected $alert;
    protected $session;
    protected $title = 'Check In Sự Kiện';
    protected $controller_name = 'CheckInSuKien';
    protected $primary_key = 'checkin_sukien_id';
    // Search
    protected $field_sort = 'thoi_gian_check_in';
    protected $field_order = 'DESC';

    // Export
    protected $export_excel = 'danh_sach_checkin_su_kien_excel';
    protected $export_excel_title = 'DANH SÁCH CHECK IN SỰ KIỆN (Excel)';

    protected $export_pdf = 'danh_sach_checkin_su_kien_pdf';
    protected $export_pdf_title = 'DANH SÁCH CHECK IN SỰ KIỆN (PDF)';

    protected $export_excel_deleted = 'danh_sach_checkin_su_kien_da_xoa_excel';
    protected $export_excel_deleted_title = 'DANH SÁCH CHECK IN SỰ KIỆN ĐÃ XÓA (Excel)';

    protected $export_pdf_deleted = 'danh_sach_checkin_su_kien_da_xoa_pdf';
    protected $export_pdf_deleted_title = 'DANH SÁCH CHECK IN SỰ KIỆN ĐÃ XÓA (PDF)';

    protected $pager_only = [
        'keyword', 
        'perPage', 
        'sort', 
        'order', 
        'status', 
        'su_kien_id',
        'checkin_type',
        'hinh_thuc_tham_gia',
        'face_verified',
        'start_date',
        'end_date'
    ];

    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');

        // Khởi tạo các thành phần cần thiết
        $this->model = new CheckInSuKienModel();
        $this->suKienModel = new SuKienModel();
        $this->dangKySuKienModel = new DangKySuKienModel();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url($this->module_name);
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
        // Chuẩn bị dữ liệu cho form
        $viewData = $this->prepareFormData();
        
        // Hiển thị form thêm mới
        return $this->render('new', $viewData);
    }
    
    /**
     * Xử lý tạo mới dữ liệu
     */
    public function create()
    {
        // Kiểm tra xem đã submit chưa
        if (!$this->request->is('post')) {
            return redirect()->to(site_url($this->module_name . '/new'));
        }
        
        // Lấy dữ liệu từ form
        $postData = $this->request->getPost();
        
        // Xác thực dữ liệu
        $rules = $this->model->prepareValidationRules('insert', $postData);
        if (!$this->validate($rules)) {
            // Khi xác thực không thành công, lưu lỗi và redirect về form
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }
        
        // Kiểm tra xem sự kiện có tồn tại không
        $su_kien_id = $this->request->getPost('su_kien_id');
        $suKien = $this->suKienModel->find($su_kien_id);
        
        // Nếu không tìm thấy sự kiện hoặc sự kiện đã bị xóa
        if (!$suKien || !empty($suKien->deleted_at)) {
            $this->alert->set('danger', 'Sự kiện không tồn tại trong hệ thống', true);
            return redirect()->back()->withInput();
        }
        
        // Chuẩn bị dữ liệu để insert
        $data = [
            'su_kien_id' => $su_kien_id,
            'email' => $this->request->getPost('email'),
            'ho_ten' => $this->request->getPost('ho_ten'),
            'dangky_sukien_id' => $this->request->getPost('dangky_sukien_id') ?: null,
            'thoi_gian_check_in' => $this->request->getPost('thoi_gian_check_in') ?: date('Y-m-d H:i:s'),
            'checkin_type' => $this->request->getPost('checkin_type'),
            'ma_xac_nhan' => $this->request->getPost('ma_xac_nhan'),
            'hinh_thuc_tham_gia' => $this->request->getPost('hinh_thuc_tham_gia'),
            'ghi_chu' => $this->request->getPost('ghi_chu'),
            'status' => $this->request->getPost('status') !== null ? $this->request->getPost('status') : 1,
        ];
        
        // Xử lý face_id nếu có
        if ($this->request->getPost('checkin_type') === 'face_id') {
            // Lấy thông tin về face verification
            $data['face_verified'] = (int)$this->request->getPost('face_verified');
            $data['face_match_score'] = $this->request->getPost('face_match_score');
            
            // Xử lý upload ảnh khuôn mặt nếu có
            $faceImage = $this->request->getFile('face_image');
            if ($faceImage && $faceImage->isValid() && !$faceImage->hasMoved()) {
                $newName = $faceImage->getRandomName();
                $faceImage->move(ROOTPATH . 'public/uploads/faces', $newName);
                $data['face_image_path'] = $newName;
            }
        }
        
        try {
            // Thực hiện insert dữ liệu
            $id = $this->model->insertData($data);
            
            // Thông báo thành công và redirect
            $this->alert->set('success', 'Thêm mới check-in sự kiện thành công', true);
            return redirect()->to(site_url($this->module_name . '/view/' . $id));
        } catch (Exception $e) {
            // Ghi log lỗi và thông báo
            log_message('error', '[ERROR] {exception}', ['exception' => $e]);
            $this->alert->set('danger', 'Đã có lỗi xảy ra: ' . $e->getMessage(), true);
            return redirect()->back()->withInput();
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
        
        // Lấy thông tin dữ liệu cơ bản thông qua model
        $data = $this->model->find($id);
        
        if (empty($data)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin sự kiện thông qua model
        $data->suKien = $this->suKienModel->find($data->getSuKienId());
        
        // Lấy thông tin đăng ký sự kiện nếu có
        if ($data->getDangKySuKienId()) {
            $data->dangKySuKien = $this->dangKySuKienModel->find($data->getDangKySuKienId());
        }
        
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
        // Kiểm tra xem bản ghi tồn tại không
        $checkin = $this->model->find($id);
        if (!$checkin) {
            $this->alert->set('danger', 'Không tìm thấy check-in sự kiện!', true);
            return redirect()->to(site_url($this->module_name));
        }
        
        // Hiển thị form cập nhật
        $viewData = $this->prepareFormData($checkin);
        
        return $this->render('edit', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null)
    {
        // Kiểm tra ID
        if (empty($id)) {
            $this->alert->set('danger', 'ID không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Kiểm tra xem bản ghi tồn tại không
        $checkin = $this->model->find($id);
        if (!$checkin) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Kiểm tra xem đã submit chưa
        if (!$this->request->is('post')) {
            return redirect()->to(site_url($this->module_name . '/edit/' . $id));
        }
        
        // Lấy dữ liệu từ form
        $postData = $this->request->getPost();
        
        // Xác thực dữ liệu
        $rules = $this->model->prepareValidationRules('update', $postData);
        if (!$this->validate($rules)) {
            // Khi xác thực không thành công, lưu lỗi và redirect về form
            session()->setFlashdata('errors', $this->validator->getErrors());
            return redirect()->back()->withInput();
        }
        
        // Kiểm tra xem sự kiện có tồn tại không
        $su_kien_id = $this->request->getPost('su_kien_id');
        $suKien = $this->suKienModel->find($su_kien_id);
        
        // Nếu không tìm thấy sự kiện hoặc sự kiện đã bị xóa
        if (!$suKien || !empty($suKien->deleted_at)) {
            $this->alert->set('danger', 'Sự kiện không tồn tại trong hệ thống', true);
            return redirect()->back()->withInput();
        }
        
        // Chuẩn bị dữ liệu để update
        $data = [
            'su_kien_id' => $su_kien_id,
            'email' => $this->request->getPost('email'),
            'ho_ten' => $this->request->getPost('ho_ten'),
            'dangky_sukien_id' => $this->request->getPost('dangky_sukien_id') ?: null,
            'thoi_gian_check_in' => $this->request->getPost('thoi_gian_check_in') ?: date('Y-m-d H:i:s'),
            'checkin_type' => $this->request->getPost('checkin_type'),
            'ma_xac_nhan' => $this->request->getPost('ma_xac_nhan'),
            'hinh_thuc_tham_gia' => $this->request->getPost('hinh_thuc_tham_gia'),
            'ghi_chu' => $this->request->getPost('ghi_chu'),
            'status' => $this->request->getPost('status'),
        ];
        
        // Xử lý face_id nếu có
        if ($this->request->getPost('checkin_type') === 'face_id') {
            // Lấy thông tin về face verification
            $data['face_verified'] = (int)$this->request->getPost('face_verified');
            $data['face_match_score'] = $this->request->getPost('face_match_score');
            
            // Xử lý upload ảnh khuôn mặt nếu có
            $faceImage = $this->request->getFile('face_image');
            if ($faceImage && $faceImage->isValid() && !$faceImage->hasMoved()) {
                // Xóa ảnh cũ nếu có
                if (!empty($checkin->face_image_path)) {
                    $oldImagePath = ROOTPATH . 'public/uploads/faces/' . $checkin->face_image_path;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                
                $newName = $faceImage->getRandomName();
                $faceImage->move(ROOTPATH . 'public/uploads/faces', $newName);
                $data['face_image_path'] = $newName;
            }
        }
        
        try {
            // Thực hiện update dữ liệu
            $this->model->updateData($id, $data);
            
            // Thông báo thành công và redirect
            $this->alert->set('success', 'Cập nhật check-in sự kiện thành công', true);
            return redirect()->to(site_url($this->module_name . '/view/' . $id));
        } catch (Exception $e) {
            // Ghi log lỗi và thông báo
            log_message('error', '[ERROR] {exception}', ['exception' => $e]);
            $this->alert->set('danger', 'Đã có lỗi xảy ra: ' . $e->getMessage(), true);
            return redirect()->back()->withInput();
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
     * @param int $id ID của bản ghi check-in
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
        $checkin = $this->model->find($id);
        if (empty($checkin)) {
            $this->alert->set('danger', 'Không tìm thấy bản ghi check-in có ID: ' . $id, true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Log thông tin trước khi cập nhật
        log_message('info', "Cập nhật trạng thái check-in ID: {$id}, từ trạng thái: {$checkin->getStatus()} sang: {$status}");
        
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

        // Lấy danh sách sự kiện
        $suKienList = $this->suKienModel->findAll();

        // Thiết lập danh sách sự kiện cho trait
        $this->setSuKienList($suKienList);

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
     * Xuất danh sách check-in sự kiện ra file PDF
     */
    public function exportPdf()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status'); 
        $sort = $this->request->getGet('sort') ?? $this->field_sort;
        $order = $this->request->getGet('order') ?? $this->field_order;

        // Lấy danh sách sự kiện
        $suKienList = $this->suKienModel->findAll();

        // Thiết lập danh sách sự kiện cho trait
        $this->setSuKienList($suKienList);

        $criteria = $this->prepareSearchCriteria($keyword, $status);
        $options = $this->prepareSearchOptions($sort, $order);
        $data = $this->getExportData($criteria, $options);

        $filters = [];
        if (!empty($keyword)) $filters['Từ khóa'] = $keyword;
        if (isset($status) && $status !== '') $filters['Trạng thái'] = $status == 1 ? 'Hoạt động' : 'Không hoạt động';  
        if (!empty($sort)) $filters['Sắp xếp theo'] = $this->getSortText($sort, $order);

        $this->createPdfFile($data, $filters, $this->export_pdf, $this->export_pdf_title);
    }

    /**
     * Xuất danh sách check-in sự kiện đã xóa ra file PDF
     */
    public function exportDeletedPdf()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? $this->field_sort;
        $order = $this->request->getGet('order') ?? $this->field_order;

        // Lấy danh sách sự kiện
        $suKienList = $this->suKienModel->findAll();

        // Thiết lập danh sách sự kiện cho trait
        $this->setSuKienList($suKienList);

        $criteria = $this->prepareSearchCriteria($keyword, $status, true);
        $options = $this->prepareSearchOptions($sort, $order);
        $data = $this->getExportData($criteria, $options);

        $filters = [];
        if (!empty($keyword)) $filters['Từ khóa'] = $keyword;
        if (isset($status) && $status !== '') $filters['Trạng thái'] = $status == 1 ? 'Hoạt động' : 'Không hoạt động';      
        if (!empty($sort)) $filters['Sắp xếp theo'] = $this->getSortText($sort, $order);
        $filters['Trạng thái'] = 'Đã xóa';

        $this->createPdfFile($data, $filters, $this->export_pdf_deleted, $this->export_pdf_deleted_title);
    }

    /**
     * Xuất danh sách check-in sự kiện đã xóa ra file Excel
     */
    public function exportDeletedExcel()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? $this->field_sort;
        $order = $this->request->getGet('order') ?? $this->field_order;

        // Lấy danh sách sự kiện
        $suKienList = $this->suKienModel->findAll();

        // Thiết lập danh sách sự kiện cho trait
        $this->setSuKienList($suKienList);

        // Chuẩn bị tiêu chí tìm kiếm
        $criteria = $this->prepareSearchCriteria($keyword, $status, true);
        $options = $this->prepareSearchOptions($sort, $order);

        // Lấy dữ liệu
        $data = $this->getExportData($criteria, $options);

        // Chuẩn bị headers
        $headers = $this->prepareExcelHeaders(true);

        // Chuẩn bị filters
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
            'checkin_sukien_id' => 'ID',
            'su_kien_id' => 'Sự kiện',
            'dangky_sukien_id' => 'Đăng ký sự kiện',
            'ho_ten' => 'Họ tên',
            'email' => 'Email',
            'thoi_gian_check_in' => 'Thời gian check-in',
            'checkin_type' => 'Loại check-in',
            'face_match_score' => 'Điểm khớp khuôn mặt',
            'face_verified' => 'Xác thực khuôn mặt',
            'hinh_thuc_tham_gia' => 'Hình thức tham gia',
            'status' => 'Trạng thái',
            'ip_address' => 'Địa chỉ IP',
            'device_info' => 'Thông tin thiết bị',
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
     * Chuẩn bị dữ liệu cho form
     * 
     * @param CheckInSuKien|null $data Dữ liệu check-in nếu có
     * @return array Dữ liệu cho view
     */
    protected function prepareFormData($data = null)
    {
        // Chuẩn bị dữ liệu từ bản ghi nếu có
        $id = isset($data) ? $data->getId() : 0;
        $su_kien_id = isset($data) ? $data->getSuKienId() : '';
        $dangky_sukien_id = isset($data) ? $data->getDangKySuKienId() : '';
        $ho_ten = isset($data) ? $data->getHoTen() : '';
        $email = isset($data) ? $data->getEmail() : '';
        $thoi_gian_check_in = isset($data) && $data->getThoiGianCheckIn() ? $data->getThoiGianCheckIn()->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
        $checkin_type = isset($data) ? $data->getCheckinType() : 'manual';
        $face_verified = isset($data) ? $data->isFaceVerified() : 0;
        $face_match_score = isset($data) ? $data->getFaceMatchScore() : '';
        $ma_xac_nhan = isset($data) ? $data->getMaXacNhan() : '';
        $hinh_thuc_tham_gia = isset($data) ? $data->getHinhThucThamGia() : 'offline';
        $ghi_chu = isset($data) ? $data->getGhiChu() : '';
        $status = isset($data) ? $data->getStatus() : 1;
        
        // Lấy danh sách sự kiện
        $suKienList = $this->suKienModel->findAll();
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'module_name' => $this->module_name,
            'title' => isset($data) ? 'Cập nhật check-in sự kiện' : 'Thêm mới check-in sự kiện',
            'data' => $data,
            'id' => $id,
            'su_kien_id' => $su_kien_id,
            'dangky_sukien_id' => $dangky_sukien_id,
            'ho_ten' => $ho_ten,
            'email' => $email,
            'thoi_gian_check_in' => $thoi_gian_check_in,
            'checkin_type' => $checkin_type,
            'face_verified' => $face_verified,
            'face_match_score' => $face_match_score,
            'ma_xac_nhan' => $ma_xac_nhan,
            'hinh_thuc_tham_gia' => $hinh_thuc_tham_gia,
            'ghi_chu' => $ghi_chu,
            'status' => $status,
            'suKienList' => $suKienList,
            'validation' => $this->validator,
            'errors' => session()->getFlashdata('errors') ?? []
        ];
        
        return $viewData;
    }

    /**
     * Render view với data
     * 
     * @param string $view Tên view
     * @param array $data Dữ liệu truyền cho view
     * @return string HTML
     */
    protected function render(string $view, array $data = [])
    {
        return view('App\Modules\\' . $this->module_name . '\Views\\' . $view, $data);
    }

    /**
     * Chuẩn bị tham số tìm kiếm
     */
    protected function prepareSearchParams($request)
    {
        return [
            'page' => (int)($request->getGet('page') ?? 1),
            'perPage' => (int)($request->getGet('perPage') ?? 10),
            'sort' => $request->getGet('sort') ?? 'created_at',
            'order' => $request->getGet('order') ?? 'DESC',
            'keyword' => $request->getGet('keyword') ?? '',
            'status' => $request->getGet('status'),
            'su_kien_id' => $request->getGet('su_kien_id'),
            'checkin_type' => $request->getGet('checkin_type'),
            'face_verified' => $request->getGet('face_verified'),
            'hinh_thuc_tham_gia' => $request->getGet('hinh_thuc_tham_gia'),
            'dangky_sukien_id' => $request->getGet('dangky_sukien_id'),
            'start_date' => $request->getGet('start_date'),
            'end_date' => $request->getGet('end_date')
        ];
    }

    /**
     * Xử lý tham số tìm kiếm
     */
    protected function processSearchParams($params)
    {
        // Kiểm tra và điều chỉnh các tham số không hợp lệ
        if ($params['page'] < 1) $params['page'] = 1;
        if ($params['perPage'] < 1) $params['perPage'] = 10;

        return $params;
    }

    /**
     * Xây dựng tham số tìm kiếm cho model
     */
    protected function buildSearchCriteria($params)
    {
        $criteria = [];
        
        if (!empty($params['keyword'])) {
            $criteria['keyword'] = $params['keyword'];
        }

        if (isset($params['status']) && $params['status'] !== '') {
            $criteria['status'] = $params['status'];
        }
        
        if (isset($params['su_kien_id']) && $params['su_kien_id'] !== '') {
            $criteria['su_kien_id'] = $params['su_kien_id'];
        }
        
        if (isset($params['checkin_type']) && $params['checkin_type'] !== '') {
            $criteria['checkin_type'] = $params['checkin_type'];
        }
        
        if (isset($params['face_verified']) && $params['face_verified'] !== '') {
            $criteria['face_verified'] = $params['face_verified'];
        }
        
        if (isset($params['hinh_thuc_tham_gia']) && $params['hinh_thuc_tham_gia'] !== '') {
            $criteria['hinh_thuc_tham_gia'] = $params['hinh_thuc_tham_gia'];
        }
        
        if (isset($params['dangky_sukien_id']) && $params['dangky_sukien_id'] !== '') {
            $criteria['dangky_sukien_id'] = $params['dangky_sukien_id'];
        }
        
        if (!empty($params['start_date'])) {
            $criteria['start_date'] = $params['start_date'];
        }
        
        if (!empty($params['end_date'])) {
            $criteria['end_date'] = $params['end_date'];
        }

        return $criteria;
    }

    /**
     * Xây dựng tùy chọn tìm kiếm cho model
     */
    protected function buildSearchOptions($params)
    {
        return [
            'limit' => $params['perPage'],
            'offset' => ($params['page'] - 1) * $params['perPage'],
            'sort' => $params['sort'],
            'order' => $params['order']
        ];
    }
}