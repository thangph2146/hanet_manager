<?php

namespace App\Modules\manhinh\Controllers;

use App\Controllers\BaseController;
use App\Modules\manhinh\Models\ManhinhModel;
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

class Manhinh extends BaseController
{
    use ResponseTrait;
    
    protected $model;
    protected $breadcrumb;
    protected $alert;
    protected $moduleUrl;
    protected $moduleName;
    protected $session;
    protected $data;
    protected $permission;
    
    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');
        
        // Khởi tạo các thành phần cần thiết
        $this->model = new ManhinhModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url('manhinh');
        $this->moduleName = 'Màn hình';
        
        // Khởi tạo data để truyền đến view
        $this->data = [
            'title' => $this->moduleName,
            'moduleUrl' => $this->moduleUrl,
            'moduleName' => $this->moduleName,
        ];
        
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
        $this->data['breadcrumb'] = $this->breadcrumb->render();
        $this->data['title'] = 'Danh sách ' . $this->moduleName;
        
        // Lấy tham số từ URL
        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = (int)($this->request->getGet('perPage') ?? 10);
        $sort = $this->request->getGet('sort') ?? 'ten_man_hinh';
        $order = $this->request->getGet('order') ?? 'ASC';
        $keyword = $this->request->getGet('keyword') ?? '';
        $status = $this->request->getGet('status');
        
        // Kiểm tra các tham số không hợp lệ
        if ($page < 1) $page = 1;
        if ($perPage < 1) $perPage = 10;
     
        // Đảm bảo status được xử lý đúng cách, kể cả khi status=0
        // Lưu ý rằng status=0 là một giá trị hợp lệ (không hoạt động)
        $statusFilter = null;
        if ($status !== null && $status !== '') {
            $statusFilter = (int)$status;
        }
        
        // Tính toán offset chính xác cho phân trang
        $offset = ($page - 1) * $perPage;
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        $this->model->setSurroundCount(3);
        
        // Xây dựng tham số tìm kiếm
        $searchParams = [];
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Đặc biệt, chỉ thêm status vào nếu nó đã được chỉ định (bao gồm status=0)
        if ($status !== null && $status !== '') {
            $searchParams['status'] = $statusFilter;
        }
       
        // Lấy dữ liệu màn hình và thông tin phân trang
        $manhinhs = $this->model->search($searchParams, [
            'limit' => $perPage,
            'offset' => $offset,
            'sort' => $sort,
            'order' => $order
        ]);
        
        // Lấy tổng số kết quả
        $total = $this->model->getPager()->getTotal();
        
        // Nếu trang hiện tại lớn hơn tổng số trang, điều hướng về trang cuối cùng
        $pageCount = ceil($total / $perPage);
        if ($total > 0 && $page > $pageCount) {
            // Tạo URL mới với trang cuối cùng
            $redirectParams = $_GET;
            $redirectParams['page'] = $pageCount;
            $redirectUrl = site_url('manhinh') . '?' . http_build_query($redirectParams);
            
            // Chuyển hướng đến trang cuối cùng
            return redirect()->to($redirectUrl);
        }
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath('manhinh');
            // Thêm tất cả các tham số cần giữ lại khi chuyển trang
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order']);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($perPage);
            $pager->setCurrentPage($page);
        }
        
        // Chuẩn bị dữ liệu cho view
        $this->data['manhinhs'] = $manhinhs;
        $this->data['pager'] = $pager;
        $this->data['currentPage'] = $page;
        $this->data['perPage'] = $perPage;
        $this->data['total'] = $total;
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['keyword'] = $keyword;
        $this->data['status'] = $status; // Giữ nguyên status gốc từ request
        
        // Hiển thị view
        return view('App\Modules\manhinh\Views\index', $this->data);
    }
    
    /**
     * Hiển thị form tạo mới
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Tải danh sách camera và template đang hoạt động
        $manhinhModel = new \App\Modules\manhinh\Models\ManhinhModel();
        
        // Sử dụng phương thức mới với giới hạn 20 bản ghi
        $cameras = $manhinhModel->getRelatedCameras('', 20);
        $templates = $manhinhModel->getRelatedTemplates('', 20);
        
        // Chuẩn bị dữ liệu mặc định cho entity mới
        $manhinh = new \App\Modules\manhinh\Entities\ManHinh([
            'status' => 1,
            'bin' => 0
        ]);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm ' . $this->moduleName,
            'validation' => $this->validator,
            'moduleUrl' => $this->moduleUrl,
            'manhinh' => $manhinh,
            'cameras' => $cameras,
            'templates' => $templates,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
            'is_new' => true
        ];
        
        return view('App\Modules\manhinh\Views\new', $viewData);
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

        // Lấy dữ liệu từ request
        $data = [
            'ten_man_hinh' => trim($request->getPost('ten_man_hinh')),
            'ma_man_hinh' => trim($request->getPost('ma_man_hinh')),
            'camera_id' => $request->getPost('camera_id') ? (int)$request->getPost('camera_id') : null,
            'template_id' => $request->getPost('template_id') ? (int)$request->getPost('template_id') : null,
            'status' => $request->getPost('status') ?? 1,
            'bin' => 0
        ];

        // Kiểm tra xem tên màn hình đã tồn tại chưa
        if (!empty($data['ten_man_hinh'])) {
            // Tìm trực tiếp trong database 
            $existingManhinh = $this->model->builder()
                ->where('ten_man_hinh', $data['ten_man_hinh'])
                ->where($this->model->deletedField, null)  // Loại trừ records đã xóa mềm
                ->get()
                ->getRow();
                
            if ($existingManhinh) {
                $this->alert->set('danger', 'Tên màn hình "' . $data['ten_man_hinh'] . '" đã tồn tại, vui lòng chọn tên khác', true);
                return redirect()->back()->withInput();
            }
        }

        // Lưu dữ liệu vào database
        try {
            if ($this->model->insert($data)) {
                $this->alert->set('success', 'Thêm màn hình thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                $errors = $this->model->errors();
                if (!empty($errors)) {
                    return redirect()->back()->withInput()->with('errors', $errors);
                }
                
                $this->alert->set('danger', 'Có lỗi xảy ra khi thêm màn hình', true);
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            // Log lỗi
            log_message('error', 'Lỗi khi thêm màn hình: ' . $e->getMessage());
            
            // Kiểm tra nếu là lỗi duplicate key
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $this->alert->set('danger', 'Tên màn hình đã tồn tại trong hệ thống, vui lòng chọn tên khác', true);
            } else {
                $this->alert->set('danger', 'Có lỗi xảy ra khi thêm màn hình: ' . $e->getMessage(), true);
            }
            
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Hiển thị chi tiết
     */
    public function view($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID màn hình không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin màn hình với relationship
        $manhinh = $this->model->findWithRelations($id);
        
        if (empty($manhinh)) {
            $this->alert->set('danger', 'Không tìm thấy màn hình', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chi tiết', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chi tiết ' . $this->moduleName,
            'manhinh' => $manhinh,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\manhinh\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        // Nếu không có ID được cung cấp, chuyển hướng về trang danh sách
        if ($id === null) {
            return redirect()->to($this->moduleUrl);
        }

        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chỉnh sửa', current_url());

        // Lấy thông tin màn hình theo ID
        $manhinhModel = new \App\Modules\manhinh\Models\ManhinhModel();
        $manhinh = $manhinhModel->findWithRelations($id);

        // Nếu không tìm thấy, chuyển hướng về trang danh sách với thông báo lỗi
        if ($manhinh === null) {
            return redirect()->to($this->moduleUrl)->with('error', 'Màn hình không tồn tại hoặc đã bị xóa.');
        }

        // Lấy danh sách camera và template đang hoạt động
        // Sử dụng phương thức mới với giới hạn 20 bản ghi
        $cameras = $manhinhModel->getRelatedCameras('', 20);
        $templates = $manhinhModel->getRelatedTemplates('', 20);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chỉnh sửa ' . $this->moduleName,
            'validation' => $this->validator,
            'manhinh' => $manhinh,
            'cameras' => $cameras,
            'templates' => $templates,
            'moduleUrl' => $this->moduleUrl,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
        ];
        
        return view('App\Modules\manhinh\Views\edit', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID màn hình không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin màn hình với relationship
        $existingManhinh = $this->model->findWithRelations($id);
        
        if (empty($existingManhinh)) {
            $this->alert->set('danger', 'Không tìm thấy màn hình', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xác thực dữ liệu gửi lên
        $data = $this->request->getPost();
        
        // Kiểm tra và chuyển đổi camera_id và template_id thành số nguyên
        $camera_id = $this->request->getPost('camera_id');
        $template_id = $this->request->getPost('template_id');
        
        // Kiểm tra và chuyển đổi camera_id
        if ($camera_id !== null && $camera_id !== '') {
            if (!is_numeric($camera_id)) {
                return redirect()->back()->withInput()->with('errors', ['camera_id' => 'Camera ID phải là số nguyên']);
            }
            $camera_id = (int)$camera_id;
        }
        
        // Kiểm tra và chuyển đổi template_id
        if ($template_id !== null && $template_id !== '') {
            if (!is_numeric($template_id)) {
                return redirect()->back()->withInput()->with('errors', ['template_id' => 'Template ID phải là số nguyên']);
            }
            $template_id = (int)$template_id;
        }
        
        // Chuẩn bị quy tắc validation cho cập nhật
        $this->model->prepareValidationRules('update', ['man_hinh_id' => $id]);
        
        // Xử lý validation với quy tắc đã được điều chỉnh
        if (!$this->validate($this->model->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Kiểm tra xem tên màn hình đã tồn tại chưa (trừ chính nó)
        if (!empty($data['ten_man_hinh']) && $this->model->isNameExists($data['ten_man_hinh'], $id)) {
            $this->alert->set('danger', 'Tên màn hình đã tồn tại', true);
            return redirect()->back()->withInput();
        }
        
        // Chuẩn bị dữ liệu để cập nhật
        $updateData = [
            'ten_man_hinh' => $data['ten_man_hinh'],
            'ma_man_hinh' => $data['ma_man_hinh'] ?? null,
            'camera_id' => $camera_id,
            'template_id' => $template_id,
            'status' => isset($data['status']) ? (int)$data['status'] : 0,
            'bin' => isset($data['bin']) ? (int)$data['bin'] : 0
        ];
        
        // Giữ lại các trường thời gian từ dữ liệu hiện có
        $updateData['created_at'] = $existingManhinh->created_at;
        if ($existingManhinh->deleted_at) {
            $updateData['deleted_at'] = $existingManhinh->deleted_at;
        }
        
        // Log dữ liệu cập nhật để debug
        log_message('debug', 'Update data: ' . json_encode($updateData));
        
        // Cập nhật dữ liệu vào database
        if ($this->model->update($id, $updateData)) {
            $this->alert->set('success', 'Cập nhật màn hình thành công', true);
            return redirect()->to($this->moduleUrl);
        } else {
            $errors = $this->model->errors();
            $errorMessage = !empty($errors) ? implode(', ', $errors) : 'Lỗi không xác định';
            $this->alert->set('danger', 'Có lỗi xảy ra khi cập nhật màn hình: ' . $errorMessage, true);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Xóa mềm (chuyển vào thùng rác)
     */
    public function delete($id = null, $backToUrl = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID màn hình không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Thực hiện xóa mềm (chuyển vào thùng rác)
        if ($this->model->moveToRecycleBin($id)) {
            $this->alert->set('success', 'Đã chuyển màn hình vào thùng rác', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa màn hình', true);
        }
        
        // Lấy URL trả về từ tham số truy vấn hoặc từ tham số đường dẫn
        $returnUrl = $this->request->getGet('return_url') ?? $backToUrl;
        
        // Xử lý URL chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Hiển thị danh sách màn hình trong thùng rác
     */
    public function listdeleted()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thùng rác', current_url());
        $this->data['breadcrumb'] = $this->breadcrumb->render();
        $this->data['title'] = 'Thùng rác ' . $this->moduleName;
        
        // Lấy tham số từ URL
        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = (int)($this->request->getGet('perPage') ?? 10);
        $sort = $this->request->getGet('sort') ?? 'updated_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        $keyword = $this->request->getGet('keyword') ?? '';
        $status = $this->request->getGet('status');
        
        // Kiểm tra các tham số không hợp lệ
        if ($page < 1) $page = 1;
        if ($perPage < 1) $perPage = 10;
        
        // Log chi tiết URL và tham số
        log_message('debug', '[Controller:listdeleted] URL đầy đủ: ' . current_url() . '?' . http_build_query($_GET));
        log_message('debug', '[Controller:listdeleted] Tham số request: ' . json_encode($_GET));
        log_message('debug', '[Controller:listdeleted] Đã xử lý: page=' . $page . ', perPage=' . $perPage . ', sort=' . $sort . 
            ', order=' . $order . ', keyword=' . $keyword . ', status=' . $status);
        
        // Đảm bảo status được xử lý đúng cách, kể cả khi status=0
        // Lưu ý rằng status=0 là một giá trị hợp lệ (không hoạt động)
        $statusFilter = null;
        if ($status !== null && $status !== '') {
            $statusFilter = (int)$status;
            log_message('debug', '[Controller:listdeleted] Status từ request: ' . $status . ' sau khi ép kiểu: ' . $statusFilter);
        }
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        $this->model->setSurroundCount(3);
        
        // Xây dựng tham số tìm kiếm
        $searchParams = [
            'bin' => 1, // Luôn lấy các màn hình trong thùng rác
            'sort' => $sort,
            'order' => $order
        ];
        
        // Thêm keyword nếu có
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Đặc biệt, chỉ thêm status vào nếu nó đã được chỉ định (bao gồm status=0)
        if ($status !== null && $status !== '') {
            $searchParams['status'] = $statusFilter;
        }
        
        // Log tham số tìm kiếm cuối cùng
        log_message('debug', '[Controller:listdeleted] Tham số tìm kiếm cuối cùng: ' . json_encode($searchParams));
        
        // Lấy dữ liệu màn hình và thông tin phân trang
        $manhinhs = $this->model->search($searchParams, [
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage,
            'sort' => $sort,
            'order' => $order
        ]);
        
        $total = $this->model->countSearchResults($searchParams);
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath('manhinh/listdeleted');
            // Không cần thiết lập segment vì chúng ta sử dụng query string
            $pager->setOnly(['keyword', 'perPage', 'sort', 'order', 'status']);
            
            // Đảm bảo perPage được thiết lập đúng trong pager
            $pager->setPerPage($perPage);
            
            // Thiết lập trang hiện tại
            $pager->setCurrentPage($page);
        }
        
        // Chuẩn bị dữ liệu cho view
        $this->data['manhinhs'] = $manhinhs;
        $this->data['pager'] = $pager;
        $this->data['currentPage'] = $page;
        $this->data['perPage'] = $perPage;
        $this->data['total'] = $total;
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['keyword'] = $keyword;
        $this->data['status'] = $status;
        
        // Hiển thị view
        return view('App\Modules\manhinh\Views\listdeleted', $this->data);
    }
    
    /**
     * Khôi phục từ thùng rác
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID màn hình không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form
        $returnUrl = $this->request->getPost('return_url');
        log_message('debug', 'Restore - Return URL: ' . ($returnUrl ?? 'None'));
        
        if ($this->model->restoreFromRecycleBin($id)) {
            $this->alert->set('success', 'Đã khôi phục màn hình từ thùng rác', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục màn hình', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn một màn hình
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID màn hình không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form
        $returnUrl = $this->request->getPost('return_url');
        log_message('debug', 'PermanentDelete - Return URL: ' . ($returnUrl ?? 'None'));
        
        if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
            $this->alert->set('success', 'Đã xóa vĩnh viễn màn hình', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa màn hình', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Tìm kiếm camera
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
            'breadcrumb' => $this->breadcrumb->add('Tìm kiếm', current_url())->render(),
            'title' => 'Tìm kiếm ' . $this->moduleName,
            'cameras' => $results,
            'pager' => $this->model->pager,
            'keyword' => $keyword,
            'filters' => $filters,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\manhinh\Views\search', $viewData);
    }
    
    /**
     * Xóa nhiều camera (chuyển vào thùng rác)
     */
    public function deleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedIds = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn camera nào để xóa', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl);
        }
        
        // Log để debug
        log_message('debug', 'DeleteMultiple - POST data: ' . json_encode($_POST));
        log_message('debug', 'DeleteMultiple - Selected IDs: ' . json_encode($selectedIds));
        log_message('debug', 'DeleteMultiple - Return URL: ' . ($returnUrl ?? 'None'));
        
        $successCount = 0;
        
        // Kiểm tra nếu $selectedIds đã là mảng thì sử dụng trực tiếp, không cần explode
        $idArray = is_array($selectedIds) ? $selectedIds : explode(',', $selectedIds);
        
        foreach ($idArray as $id) {
            if ($this->model->moveToRecycleBin($id)) {
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã chuyển $successCount camera vào thùng rác", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa camera', true);
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
        $redirectUrl = $this->moduleUrl;
        
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
     * Thay đổi trạng thái nhiều camera
     */
    public function statusMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        $newStatus = $this->request->getPost('status');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn camera nào để thay đổi trạng thái', true);
            return redirect()->to($this->moduleUrl);
        }
        
        if ($newStatus === null || !in_array($newStatus, ['0', '1'])) {
            $this->alert->set('warning', 'Trạng thái không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        $successCount = 0;
        // Kiểm tra nếu $selectedIds đã là mảng thì sử dụng trực tiếp, không cần explode
        $idArray = is_array($selectedIds) ? $selectedIds : explode(',', $selectedIds);
        
        foreach ($idArray as $id) {
            if ($this->model->update($id, ['status' => $newStatus])) {
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $statusText = $newStatus == '1' ? 'hoạt động' : 'không hoạt động';
            $this->alert->set('success', "Đã chuyển $successCount camera sang trạng thái $statusText", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể thay đổi trạng thái', true);
        }
        
        return redirect()->to($this->moduleUrl);
    }
    
    /**
     * Khôi phục nhiều camera từ thùng rác
     */
    public function restoreMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedIds = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn camera nào để khôi phục', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
        }
        
        // Log toàn bộ POST data để debug
        log_message('debug', 'RestoreMultiple - POST data: ' . json_encode($_POST));
        log_message('debug', 'RestoreMultiple - Selected IDs: ' . json_encode($selectedIds));
        log_message('debug', 'RestoreMultiple - Return URL: ' . ($returnUrl ?? 'None'));
        
        $successCount = 0;
        $failCount = 0;
        $errorMessages = [];
        
        // Kiểm tra nếu $selectedIds đã là mảng thì sử dụng trực tiếp, không cần explode
        $idArray = is_array($selectedIds) ? $selectedIds : explode(',', $selectedIds);
        
        // Log thông tin mảng ID để debug
        log_message('debug', 'RestoreMultiple - ID Array: ' . json_encode($idArray));
        log_message('debug', 'RestoreMultiple - Số lượng ID cần khôi phục: ' . count($idArray));
        
        foreach ($idArray as $id) {
            log_message('debug', 'RestoreMultiple - Đang khôi phục ID: ' . $id);
            
            try {
                $camera = $this->model->find($id);
                if (!$camera) {
                    log_message('error', 'RestoreMultiple - Không tìm thấy camera với ID: ' . $id);
                    $failCount++;
                    $errorMessages[] = "Không tìm thấy camera ID: {$id}";
                    continue;
                }
                
                // Kiểm tra xem camera có đang trong thùng rác không
                if ($camera->bin != 1) {
                    log_message('warning', 'RestoreMultiple - Camera ID: ' . $id . ' không nằm trong thùng rác (bin = ' . $camera->bin . ')');
                    $failCount++;
                    $errorMessages[] = "Camera ID: {$id} không nằm trong thùng rác";
                    continue;
                }
                
                // Đặt lại trạng thái bin và lưu
                $camera->bin = 0;
                if ($this->model->save($camera)) {
                    $successCount++;
                    log_message('debug', 'RestoreMultiple - Khôi phục thành công ID: ' . $id);
                } else {
                    $failCount++;
                    $errors = $this->model->errors() ? json_encode($this->model->errors()) : 'Unknown error';
                    log_message('error', 'RestoreMultiple - Lỗi lưu camera ID: ' . $id . ', Errors: ' . $errors);
                    $errorMessages[] = "Lỗi lưu camera ID: {$id}";
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
                $this->alert->set('warning', "Đã khôi phục {$successCount} camera, nhưng có {$failCount} camera không thể khôi phục", true);
            } else {
                $this->alert->set('success', "Đã khôi phục {$successCount} camera từ thùng rác", true);
            }
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể khôi phục camera nào', true);
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
     * Xóa vĩnh viễn nhiều camera
     */
    public function permanentDeleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedIds = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn camera nào để xóa vĩnh viễn', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
        }
        
        // Log để debug
        log_message('debug', 'PermanentDeleteMultiple - POST data: ' . json_encode($_POST));
        log_message('debug', 'PermanentDeleteMultiple - Selected IDs: ' . json_encode($selectedIds));
        log_message('debug', 'PermanentDeleteMultiple - Return URL: ' . ($returnUrl ?? 'None'));
        
        $successCount = 0;
        
        // Kiểm tra nếu $selectedIds đã là mảng thì sử dụng trực tiếp, không cần explode
        $idArray = is_array($selectedIds) ? $selectedIds : explode(',', $selectedIds);
        
        foreach ($idArray as $id) {
            if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
                $successCount++;
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
     * Xuất danh sách camera ra file Excel
     */
    public function exportExcel()
    {
        // Lấy tham số tìm kiếm từ URL
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'ten_man_hinh';
        $order = $this->request->getGet('order') ?? 'ASC';

        // Chuẩn bị tham số tìm kiếm
        $searchParams = [
            'keyword' => $keyword,
            'status' => $status,
            'bin' => 0
        ];

        // Log tham số tìm kiếm
        log_message('debug', 'Export Excel - Tham số tìm kiếm: ' . json_encode($searchParams));

        // Lấy dữ liệu màn hình không giới hạn số lượng
        $this->builder = $this->model->builder();
        $this->builder->select([
            'man_hinh.*',
            'camera.ten_camera',
            'template.ten_template'
        ]);
        
        // Join với bảng camera và template
        $this->builder->join('camera', 'camera.camera_id = man_hinh.camera_id', 'left');
        $this->builder->join('template', 'template.template_id = man_hinh.template_id', 'left');
        
        // Xử lý từ khóa tìm kiếm
        if (!empty($keyword)) {
            $this->builder->groupStart()
                ->like('man_hinh.ten_man_hinh', $keyword)
                ->orLike('man_hinh.ma_man_hinh', $keyword)
                ->groupEnd();
        }
        
        // Xử lý trạng thái
        if ($status !== null && $status !== '') {
            $this->builder->where('man_hinh.status', $status);
        }
        
        // Lọc các bản ghi không nằm trong thùng rác
        $this->builder->where('man_hinh.bin', 0);
        
        // Sắp xếp kết quả
        $this->builder->orderBy($sort, $order);
        
        // Lấy kết quả
        $manhinhs = $this->builder->get()->getResult();

        // Tạo spreadsheet mới
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Thiết lập font chữ
        $sheet->getStyle('A1:E999')->getFont()->setName('Times New Roman');

        // Thiết lập tiêu đề
        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'DANH SÁCH MÀN HÌNH');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Thêm thông tin lọc nếu có
        $row = 2;
        $filterInfo = [];
        if (!empty($keyword)) {
            $filterInfo[] = "Từ khóa: " . $keyword;
        }
        if ($status !== null && $status !== '') {
            $filterInfo[] = "Trạng thái: " . ($status == 1 ? 'Hoạt động' : 'Không hoạt động');
        }
        if (!empty($filterInfo)) {
            $sheet->mergeCells("A{$row}:E{$row}");
            $sheet->setCellValue("A{$row}", implode(', ', $filterInfo));
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $row++;
        }

        // Thêm ngày xuất
        $row++;
        $sheet->mergeCells("A{$row}:E{$row}");
        $sheet->setCellValue("A{$row}", 'Ngày xuất: ' . date('d/m/Y H:i:s'));
        $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header của bảng
        $row++;
        $headers = ['STT', 'MÃ MÀN HÌNH', 'TÊN MÀN HÌNH', 'CAMERA', 'TEMPLATE'];
        $sheet->fromArray($headers, NULL, "A{$row}");
        
        // Định dạng header
        $headerStyle = $sheet->getStyle("A{$row}:E{$row}");
        $headerStyle->getFont()->setBold(true);
        $headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $headerStyle->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('CCCCCC');

        // Đổ dữ liệu
        $startRow = $row + 1;
        $index = 1;
        foreach ($manhinhs as $manhinh) {
            $sheet->setCellValue("A{$startRow}", $index);
            $sheet->setCellValue("B{$startRow}", $manhinh->ma_man_hinh);
            $sheet->setCellValue("C{$startRow}", $manhinh->ten_man_hinh);
            $sheet->setCellValue("D{$startRow}", $manhinh->ten_camera ?? 'Chưa gắn camera');
            $sheet->setCellValue("E{$startRow}", $manhinh->ten_template ?? 'Chưa gắn template');
            
            // Căn giữa cột STT
            $sheet->getStyle("A{$startRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $startRow++;
            $index++;
        }

        // Tự động điều chỉnh độ rộng cột
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Tạo border cho bảng
        $lastRow = $startRow - 1;
        $sheet->getStyle("A{$row}:E{$lastRow}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Thiết lập header cho response
        $filename = 'Danh_sach_man_hinh';
        if (!empty($keyword)) {
            $filename .= '_keyword_' . preg_replace('/[^a-z0-9]/i', '_', $keyword);
        }
        if ($status !== null && $status !== '') {
            $filename .= '_status_' . $status;
        }
        $filename .= '_' . date('dmY_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Xuất file
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    
    /**
     * Xuất danh sách camera ra file PDF
     */
    public function exportPdf()
    {
        // Lấy các tham số tìm kiếm từ URL
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'ten_man_hinh';
        $order = $this->request->getGet('order') ?? 'ASC';
        
        // Chuẩn bị tham số tìm kiếm
        $searchParams = [
            'bin' => 0 // Không lấy dữ liệu trong thùng rác
        ];
        
        // Khởi tạo builder query
        $builder = $this->model->builder();
        
        // Select các trường cần thiết
        $builder->select([
            'man_hinh.*',
            'camera.ten_camera',
            'template.ten_template'
        ]);
        
        // Join với bảng camera và template
        $builder->join('camera', 'camera.camera_id = man_hinh.camera_id', 'left');
        $builder->join('template', 'template.template_id = man_hinh.template_id', 'left');
        
        // Thêm điều kiện tìm kiếm nếu có
        if (!empty($keyword)) {
            $builder->groupStart()
                    ->like('man_hinh.ten_man_hinh', $keyword)
                    ->orLike('man_hinh.ma_man_hinh', $keyword)
                    ->groupEnd();
        }
        
        if ($status !== null && $status !== '') {
            $builder->where('man_hinh.status', $status);
        }
        
        // Thêm điều kiện bin = 0
        $builder->where('man_hinh.bin', 0);
        
        // Sắp xếp kết quả
        $builder->orderBy($sort, $order);
        
        // Lấy dữ liệu
        $manhinhs = $builder->get()->getResult();
        
        // Chuẩn bị mảng filters để hiển thị trong PDF
        $filters = [];
        if (!empty($keyword)) {
            $filters[] = 'Từ khóa: ' . $keyword;
        }
        if ($status !== null && $status !== '') {
            $filters[] = 'Trạng thái: ' . ($status == 1 ? 'Hoạt động' : 'Không hoạt động');
        }
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'DANH SÁCH MÀN HÌNH',
            'date' => date('d/m/Y H:i'),
            'filters' => $filters,
            'manhinhs' => $manhinhs
        ];
        
        // Tạo HTML từ view
        $html = view('App\Modules\manhinh\Views\export_pdf', $data);
        
        // Khởi tạo Dompdf với các tùy chọn
        $options = new Options();
        $options->set('defaultFont', 'Times New Roman');
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        
        // Thiết lập kích thước giấy và hướng giấy
        $dompdf->setPaper('A4', 'landscape');
        
        // Render PDF
        $dompdf->render();
        
        // Tạo tên file
        $filename = 'Danh_sach_man_hinh';
        if (!empty($filters)) {
            $filename .= '_filtered';
        }
        $filename .= '_' . date('YmdHis') . '.pdf';
        
        // Stream file PDF
        $dompdf->stream($filename, ['Attachment' => 1]);
        exit();
    }
    
    /**
     * Xuất danh sách camera đã xóa ra file PDF
     */
    public function exportDeletedPdf()
    {
        // Lấy tham số lọc từ URL
        $keyword = $this->request->getGet('keyword') ?? '';
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'updated_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        
        // Xây dựng tham số tìm kiếm
        $searchParams = [
            'bin' => 1 // Luôn lấy các màn hình trong thùng rác
        ];
        
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Đặc biệt, chỉ thêm status vào nếu nó đã được chỉ định (bao gồm status=0)
        if ($status !== null && $status !== '') {
            $searchParams['status'] = (int)$status;
        }
        
        // Lấy dữ liệu màn hình theo bộ lọc mà không giới hạn phân trang
        $manhinhs = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        // Tạo dữ liệu cho PDF
        $pdfData = [
            'title' => 'DANH SÁCH MÀN HÌNH ĐÃ XÓA',
            'manhinhs' => $manhinhs,
            'date' => date('d/m/Y H:i:s')
        ];
        
        // Thêm thông tin bộ lọc vào tiêu đề nếu có
        $filterInfo = [];
        if (!empty($keyword)) {
            $filterInfo[] = "Từ khóa: " . $keyword;
        }
        if ($status !== null && $status !== '') {
            $filterInfo[] = "Trạng thái: " . ($status == 1 ? 'Hoạt động' : 'Không hoạt động');
        }
        
        if (!empty($filterInfo)) {
            $pdfData['filters'] = implode(', ', $filterInfo);
        }
        
        // Render view thành HTML
        $html = view('App\Modules\manhinh\Views\export_pdf', $pdfData);
        
        // Tạo đối tượng DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape'); // Định dạng ngang cho trang PDF
        
        // Render PDF
        $dompdf->render();
        
        // Thiết lập tên file dựa trên bộ lọc
        $filterSuffix = '';
        if (!empty($keyword)) {
            $filterSuffix .= '_keyword_' . preg_replace('/[^a-z0-9]/i', '_', $keyword);
        }
        if ($status !== null && $status !== '') {
            $filterSuffix .= '_status_' . $status;
        }
        
        $filename = 'danh_sach_man_hinh_da_xoa' . $filterSuffix . '_' . date('dmY_His') . '.pdf';
        
        // Stream file PDF để tải xuống
        $dompdf->stream($filename, ['Attachment' => true]);
        exit();
    }
    
    /**
     * Xuất danh sách camera đã xóa ra file Excel
     */
    public function exportDeletedExcel()
    {
        // Lấy tham số lọc từ URL
        $keyword = $this->request->getGet('keyword') ?? '';
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'updated_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        
        // Xây dựng tham số tìm kiếm
        $searchParams = [
            'bin' => 1 // Luôn lấy các camera trong thùng rác
        ];
        
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Đặc biệt, chỉ thêm status vào nếu nó đã được chỉ định (bao gồm status=0)
        if ($status !== null && $status !== '') {
            $searchParams['status'] = (int)$status;
        }
        
        // Log tham số tìm kiếm cuối cùng
        log_message('debug', '[ExportDeletedExcel] Tham số tìm kiếm: ' . json_encode($searchParams));
        
        // Lấy dữ liệu camera theo bộ lọc mà không giới hạn phân trang
        $cameras = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        log_message('debug', '[ExportDeletedExcel] Số lượng camera xuất: ' . count($cameras));
        
        // Tạo đối tượng spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập tiêu đề
        $sheet->setCellValue('A1', 'DANH SÁCH CAMERA ĐÃ XÓA');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Thêm thông tin bộ lọc vào tiêu đề nếu có
        $filterInfo = [];
        if (!empty($keyword)) {
            $filterInfo[] = "Từ khóa: " . $keyword;
        }
        if ($status !== null && $status !== '') {
            $filterInfo[] = "Trạng thái: " . ($status == 1 ? 'Hoạt động' : 'Không hoạt động');
        }
        
        // Thiết lập ngày xuất và bộ lọc
        $rowInfo = 2;
        $sheet->setCellValue('A' . $rowInfo, 'Ngày xuất: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A' . $rowInfo . ':G' . $rowInfo);
        
        if (!empty($filterInfo)) {
            $rowInfo++;
            $sheet->setCellValue('A' . $rowInfo, 'Bộ lọc: ' . implode(', ', $filterInfo));
            $sheet->mergeCells('A' . $rowInfo . ':G' . $rowInfo);
            $sheet->getStyle('A' . $rowInfo)->getFont()->setItalic(true);
        }
        
        // Thiết lập header
        $headerRow = $rowInfo + 2;
        $headers = ['STT', 'Mã Camera', 'Tên Camera', 'Địa chỉ IP', 'Port', 'Trạng thái', 'Ngày xóa'];
        $column = 'A';
        
        foreach ($headers as $header) {
            $sheet->setCellValue($column . $headerRow, $header);
            $sheet->getStyle($column . $headerRow)->getFont()->setBold(true);
            $column++;
        }
        
        // Thêm dữ liệu
        $row = $headerRow + 1;
        $count = 1;
        foreach ($cameras as $camera) {
            $column = 'A';
            $sheet->setCellValue($column++ . $row, $count++);
            $sheet->setCellValue($column++ . $row, $camera->ma_camera);
            $sheet->setCellValue($column++ . $row, $camera->ten_camera);
            $sheet->setCellValue($column++ . $row, $camera->ip_camera);
            $sheet->setCellValue($column++ . $row, $camera->port);
            $sheet->setCellValue($column++ . $row, $camera->status == 1 ? 'Hoạt động' : 'Không hoạt động');
            $sheet->setCellValue($column++ . $row, $camera->deleted_at ? date('d/m/Y H:i', strtotime($camera->deleted_at)) : '');
            $row++;
        }
        
        // Định dạng dữ liệu
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . $headerRow . ':G' . ($row - 1))->applyFromArray($dataStyle);
        
        // Tự động điều chỉnh độ rộng cột
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Thiết lập header để tải xuống
        $filterSuffix = '';
        if (!empty($keyword)) {
            $filterSuffix .= '_keyword_' . preg_replace('/[^a-z0-9]/i', '_', $keyword);
        }
        if ($status !== null && $status !== '') {
            $filterSuffix .= '_status_' . $status;
        }
        
        $filename = 'Danh_sach_camera_da_xoa' . $filterSuffix . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Thiết lập header
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        // Xuất file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    
    /**
     * Tìm kiếm camera qua AJAX
     */
    public function searchCameras()
    {
        // Kiểm tra yêu cầu Ajax
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Truy cập trực tiếp không được phép']);
        }
        
        // Lấy từ khóa tìm kiếm
        $keyword = $this->request->getGet('keyword') ?? '';
        
        // Tìm kiếm camera
        $cameras = $this->model->searchCameras($keyword);
        
        // Trả về kết quả dưới dạng JSON
        return $this->response->setJSON([
            'success' => true,
            'data' => $cameras,
            'csrf_token_name' => csrf_token(),
            'csrf_hash' => csrf_hash()
        ]);
    }
    
    /**
     * Tìm kiếm template qua AJAX
     */
    public function searchTemplates()
    {
        // Kiểm tra yêu cầu Ajax
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Truy cập trực tiếp không được phép']);
        }
        
        // Lấy từ khóa tìm kiếm
        $keyword = $this->request->getGet('keyword') ?? '';
        
        // Tìm kiếm template
        $templates = $this->model->searchTemplates($keyword);
        
        // Trả về kết quả dưới dạng JSON
        return $this->response->setJSON([
            'success' => true,
            'data' => $templates,
            'csrf_token_name' => csrf_token(),
            'csrf_hash' => csrf_hash()
        ]);
    }
} 