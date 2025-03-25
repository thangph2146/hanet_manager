<?php

namespace App\Modules\template\Controllers;

use App\Controllers\BaseController;
use App\Modules\template\Models\TemplateModel;
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

class Template extends BaseController
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
        $this->model = new TemplateModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url('template');
        $this->moduleName = 'Template';
        
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
        $sort = $this->request->getGet('sort') ?? 'ten_template';
        $order = $this->request->getGet('order') ?? 'ASC';
        $keyword = $this->request->getGet('keyword') ?? '';
        $status = $this->request->getGet('status');
        
        // Kiểm tra các tham số không hợp lệ
        if ($page < 1) $page = 1;
        if ($perPage < 1) $perPage = 10;
        
        // Log chi tiết URL và tham số
        log_message('debug', '[Controller] URL đầy đủ: ' . current_url() . '?' . http_build_query($_GET));
        log_message('debug', '[Controller] Tham số request: ' . json_encode($_GET));
        log_message('debug', '[Controller] Đã xử lý: page=' . $page . ', perPage=' . $perPage . ', sort=' . $sort . 
            ', order=' . $order . ', keyword=' . $keyword . ', status=' . $status);
        
        // Đảm bảo status được xử lý đúng cách, kể cả khi status=0
        // Lưu ý rằng status=0 là một giá trị hợp lệ (không hoạt động)
        $statusFilter = null;
        if ($status !== null && $status !== '') {
            $statusFilter = (int)$status;
            log_message('debug', '[Controller] Status từ request: ' . $status . ' sau khi ép kiểu: ' . $statusFilter);
        }
        
        // Tính toán offset chính xác cho phân trang
        $offset = ($page - 1) * $perPage;
        log_message('debug', '[Controller] Đã tính toán: offset=' . $offset . ' (từ page=' . $page . ', perPage=' . $perPage . ')');
        
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
        
        // Log tham số tìm kiếm cuối cùng
        log_message('debug', '[Controller] Tham số tìm kiếm cuối cùng: ' . json_encode($searchParams));
        
        // Lấy dữ liệu template và thông tin phân trang
        $templates = $this->model->search($searchParams, [
            'limit' => $perPage,
            'offset' => $offset,
            'sort' => $sort,
            'order' => $order
        ]);
        
        // Lấy tổng số kết quả
        $total = $this->model->getPager()->getTotal();
        log_message('debug', '[Controller] Tổng số kết quả từ pager: ' . $total);
        
        // Nếu trang hiện tại lớn hơn tổng số trang, điều hướng về trang cuối cùng
        $pageCount = ceil($total / $perPage);
        if ($total > 0 && $page > $pageCount) {
            log_message('debug', '[Controller] Trang yêu cầu (' . $page . ') vượt quá tổng số trang (' . $pageCount . '), chuyển hướng về trang cuối.');
            
            // Tạo URL mới với trang cuối cùng
            $redirectParams = $_GET;
            $redirectParams['page'] = $pageCount;
            $redirectUrl = site_url('template') . '?' . http_build_query($redirectParams);
            
            // Chuyển hướng đến trang cuối cùng
            return redirect()->to($redirectUrl);
        }
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath('template');
            // Thêm tất cả các tham số cần giữ lại khi chuyển trang
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order']);
            
            // Đảm bảo perPage và currentPage được thiết lập đúng
            $pager->setPerPage($perPage);
            $pager->setCurrentPage($page);
            
            // Log thông tin pager cuối cùng
            log_message('debug', '[Controller] Thông tin pager: ' . json_encode([
                'total' => $pager->getTotal(),
                'perPage' => $pager->getPerPage(),
                'currentPage' => $pager->getCurrentPage(),
                'pageCount' => $pager->getPageCount()
            ]));
        }
        
        // Kiểm tra số lượng template trả về
        log_message('debug', '[Controller] Số lượng template trả về: ' . count($templates));
        if (!empty($templates)) {
            $firstTemplate = $templates[0];
            log_message('debug', '[Controller] Template đầu tiên: ID=' . $firstTemplate->template_id . 
                ', Tên=' . $firstTemplate->ten_template . 
                ', Status=' . $firstTemplate->status);
        }
        
        // Chuẩn bị dữ liệu cho view
        $this->data['templates'] = $templates;
        $this->data['pager'] = $pager;
        $this->data['currentPage'] = $page;
        $this->data['perPage'] = $perPage;
        $this->data['total'] = $total;
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['keyword'] = $keyword;
        $this->data['status'] = $status; // Giữ nguyên status gốc từ request
        
        // Debug thông tin cuối cùng
        log_message('debug', '[Controller] Dữ liệu gửi đến view: ' . json_encode([
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'pageCount' => $pager ? $pager->getPageCount() : 0,
            'status' => $status,
            'template_count' => count($templates)
        ]));
        
        // Hiển thị view
        return view('App\Modules\template\Views\index', $this->data);
    }
    
    /**
     * Hiển thị form tạo mới
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Chuẩn bị dữ liệu mặc định cho entity mới
        $template = new \App\Modules\template\Entities\Template([
            'status' => 1,
            'bin' => 0
        ]);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm ' . $this->moduleName,
            'validation' => $this->validator,
            'moduleUrl' => $this->moduleUrl,
            'template' => $template,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
            'is_new' => true
        ];
        
        return view('App\Modules\template\Views\new', $viewData);
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
            'ten_template' => trim($request->getPost('ten_template')),
            'ma_template' => trim($request->getPost('ma_template')),
            'ip_template' => trim($request->getPost('ip_template')),
            'port' => $request->getPost('port'),
            'username' => trim($request->getPost('username')),
            'password' => $request->getPost('password'),
            'status' => $request->getPost('status') ?? 1,
            'bin' => 0
        ];

        // Kiểm tra xem tên template đã tồn tại chưa
        if (!empty($data['ten_template'])) {
            // Tìm trực tiếp trong database 
            $existingTemplate = $this->model->builder()
                ->where('ten_template', $data['ten_template'])
                ->where($this->model->deletedField, null)  // Loại trừ records đã xóa mềm
                ->get()
                ->getRow();
                
            if ($existingTemplate) {
                $this->alert->set('danger', 'Tên template "' . $data['ten_template'] . '" đã tồn tại, vui lòng chọn tên khác', true);
                return redirect()->back()->withInput();
            }
        }

        // Lưu dữ liệu vào database
        try {
            if ($this->model->insert($data)) {
                $this->alert->set('success', 'Thêm template thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                $errors = $this->model->errors();
                if (!empty($errors)) {
                    return redirect()->back()->withInput()->with('errors', $errors);
                }
                
                $this->alert->set('danger', 'Có lỗi xảy ra khi thêm template', true);
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            // Log lỗi
            log_message('error', 'Lỗi khi thêm template: ' . $e->getMessage());
            
            // Kiểm tra nếu là lỗi duplicate key
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $this->alert->set('danger', 'Tên template đã tồn tại trong hệ thống, vui lòng chọn tên khác', true);
            } else {
                $this->alert->set('danger', 'Có lỗi xảy ra khi thêm template: ' . $e->getMessage(), true);
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
            $this->alert->set('danger', 'ID template không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin template với relationship
        $template = $this->model->findWithRelations($id);
        
        if (empty($template)) {
            $this->alert->set('danger', 'Không tìm thấy template', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chi tiết', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chi tiết ' . $this->moduleName,
            'template' => $template,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\template\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID template không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Sử dụng phương thức findWithRelations từ model
        $template = $this->model->findWithRelations($id);
        
        if (empty($template)) {
            $this->alert->set('danger', 'Không tìm thấy template', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chỉnh sửa', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chỉnh sửa ' . $this->moduleName,
            'validation' => $this->validator,
            'template' => $template,
            'moduleUrl' => $this->moduleUrl,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
        ];
        
        return view('App\Modules\template\Views\edit', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID template không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin template với relationship
        $existingTemplate = $this->model->findWithRelations($id);
        
        if (empty($existingTemplate)) {
            $this->alert->set('danger', 'Không tìm thấy template', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xác thực dữ liệu gửi lên
        $data = $this->request->getPost();
        
        // Chuẩn bị quy tắc validation cho cập nhật - cần truyền mảng có chứa template_id
        $this->model->prepareValidationRules('update', ['template_id' => $id]);
        
        // Xử lý validation với quy tắc đã được điều chỉnh
        if (!$this->validate($this->model->getValidationRules())) {
            // Nếu validation thất bại, quay lại form với lỗi
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Kiểm tra xem tên template đã tồn tại chưa (trừ chính nó)
        if (!empty($data['ten_template']) && $this->model->isNameExists($data['ten_template'], $id)) {
            $this->alert->set('danger', 'Tên template đã tồn tại', true);
            return redirect()->back()->withInput();
        }
        
        // Chuẩn bị dữ liệu để cập nhật
        $updateData = [
            'ten_template' => $data['ten_template'],
            'ma_template' => $data['ma_template'] ?? null,
            'ip_template' => $data['ip_template'] ?? null,
            'port' => $data['port'] ?? null,
            'username' => $data['username'] ?? null,
            'status' => $data['status'] ?? 0,
            'bin' => $data['bin'] ?? 0
        ];
        
        // Chỉ cập nhật mật khẩu nếu có nhập mật khẩu mới
        if (!empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }
        
        // Giữ lại các trường thời gian từ dữ liệu hiện có
        $updateData['created_at'] = $existingTemplate->created_at;
        if ($existingTemplate->deleted_at) {
            $updateData['deleted_at'] = $existingTemplate->deleted_at;
        }
        
        // Cập nhật dữ liệu vào database
        if ($this->model->update($id, $updateData)) {
            $this->alert->set('success', 'Cập nhật template thành công', true);
            return redirect()->to($this->moduleUrl);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi cập nhật template: ' . implode(', ', $this->model->errors()), true);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Xóa mềm (chuyển vào thùng rác)
     */
    public function delete($id = null, $backToUrl = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID template không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Kiểm tra nếu template đang được sử dụng ở màn hình
        $manhinhModel = new \App\Modules\manhinh\Models\ManhinhModel();
        $usedInManhinh = $manhinhModel->where('template_id', $id)->where('bin', 0)->countAllResults();
        
        if ($usedInManhinh > 0) {
            $this->alert->set('warning', 'Không thể xóa template đang được sử dụng bởi ' . $usedInManhinh . ' màn hình', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Thực hiện xóa mềm (chuyển vào thùng rác)
        if ($this->model->moveToRecycleBin($id)) {
            $this->alert->set('success', 'Đã chuyển template vào thùng rác', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa template', true);
        }
        
        // Lấy URL trả về từ tham số truy vấn hoặc từ tham số đường dẫn
        $returnUrl = $this->request->getGet('return_url') ?? $backToUrl;
        
        // Xử lý URL chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Hiển thị danh sách template trong thùng rác
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
            'bin' => 1, // Luôn lấy các template trong thùng rác
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
        
        // Lấy dữ liệu template và thông tin phân trang
        $templates = $this->model->search($searchParams, [
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage,
            'sort' => $sort,
            'order' => $order
        ]);
        
        $total = $this->model->countSearchResults($searchParams);
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath('template/listdeleted');
            // Không cần thiết lập segment vì chúng ta sử dụng query string
            $pager->setOnly(['keyword', 'perPage', 'sort', 'order', 'status']);
            
            // Đảm bảo perPage được thiết lập đúng trong pager
            $pager->setPerPage($perPage);
            
            // Thiết lập trang hiện tại
            $pager->setCurrentPage($page);
        }
        
        // Chuẩn bị dữ liệu cho view
        $this->data['templates'] = $templates;
        $this->data['pager'] = $pager;
        $this->data['currentPage'] = $page;
        $this->data['perPage'] = $perPage;
        $this->data['total'] = $total;
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['keyword'] = $keyword;
        $this->data['status'] = $status;
        
        // Hiển thị view
        return view('App\Modules\template\Views\listdeleted', $this->data);
    }
    
    /**
     * Khôi phục từ thùng rác
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID template không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form
        $returnUrl = $this->request->getPost('return_url');
        log_message('debug', 'Restore - Return URL: ' . ($returnUrl ?? 'None'));
        
        if ($this->model->restoreFromRecycleBin($id)) {
            $this->alert->set('success', 'Đã khôi phục template từ thùng rác', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục template', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn một template
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID template không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form
        $returnUrl = $this->request->getPost('return_url');
        log_message('debug', 'PermanentDelete - Return URL: ' . ($returnUrl ?? 'None'));
        
        if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
            $this->alert->set('success', 'Đã xóa vĩnh viễn template', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa template', true);
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
            'breadcrumb' => $this->breadcrumb->add('Tìm kiếm', current_url())->render(),
            'title' => 'Tìm kiếm ' . $this->moduleName,
            'templates' => $results,
            'pager' => $this->model->pager,
            'keyword' => $keyword,
            'filters' => $filters,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\template\Views\search', $viewData);
    }
    
    /**
     * Xóa nhiều template (chuyển vào thùng rác)
     */
    public function deleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedIds = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn template nào để xóa', true);
            
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
            $this->alert->set('success', "Đã chuyển $successCount template vào thùng rác", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa template', true);
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
     * Thay đổi trạng thái nhiều template
     */
    public function statusMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        $newStatus = $this->request->getPost('status');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn template nào để thay đổi trạng thái', true);
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
            $this->alert->set('success', "Đã chuyển $successCount template sang trạng thái $statusText", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể thay đổi trạng thái', true);
        }
        
        return redirect()->to($this->moduleUrl);
    }
    
    /**
     * Khôi phục nhiều template từ thùng rác
     */
    public function restoreMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedIds = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn template nào để khôi phục', true);
            
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
                $template = $this->model->find($id);
                if (!$template) {
                    log_message('error', 'RestoreMultiple - Không tìm thấy template với ID: ' . $id);
                    $failCount++;
                    $errorMessages[] = "Không tìm thấy template ID: {$id}";
                    continue;
                }
                
                // Kiểm tra xem template có đang trong thùng rác không
                if ($template->bin != 1) {
                    log_message('warning', 'RestoreMultiple - Template ID: ' . $id . ' không nằm trong thùng rác (bin = ' . $template->bin . ')');
                    $failCount++;
                    $errorMessages[] = "Template ID: {$id} không nằm trong thùng rác";
                    continue;
                }
                
                // Đặt lại trạng thái bin và lưu
                $template->bin = 0;
                if ($this->model->save($template)) {
                    $successCount++;
                    log_message('debug', 'RestoreMultiple - Khôi phục thành công ID: ' . $id);
                } else {
                    $failCount++;
                    $errors = $this->model->errors() ? json_encode($this->model->errors()) : 'Unknown error';
                    log_message('error', 'RestoreMultiple - Lỗi lưu template ID: ' . $id . ', Errors: ' . $errors);
                    $errorMessages[] = "Lỗi lưu template ID: {$id}";
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
                $this->alert->set('warning', "Đã khôi phục {$successCount} template, nhưng có {$failCount} template không thể khôi phục", true);
            } else {
                $this->alert->set('success', "Đã khôi phục {$successCount} template từ thùng rác", true);
            }
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể khôi phục template nào', true);
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
     * Xóa vĩnh viễn nhiều template
     */
    public function permanentDeleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedIds = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn template nào để xóa vĩnh viễn', true);
            
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
            $this->alert->set('success', "Đã xóa vĩnh viễn $successCount template", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa template', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xuất danh sách template ra file Excel
     */
    public function exportExcel()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'ten_template';
        $order = $this->request->getGet('order') ?? 'ASC';
        
        // Chuẩn bị điều kiện tìm kiếm
        $searchCriteria = [
            'keyword' => $keyword,
            'status' => $status,
            'bin' => 0
        ];
        
        $searchOptions = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả bản ghi
        ];
        
        // Lấy dữ liệu template
        $templates = $this->model->search($searchCriteria, $searchOptions);
        
        // Tạo file Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập font chữ và style
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A1:E1')->getFont()->setSize(12);
        $sheet->getStyle('A1:E1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F5F5F5');
        $sheet->getStyle('A1:E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Thiết lập tiêu đề
        $sheet->setCellValue('A1', 'STT');
        $sheet->setCellValue('B1', 'Mã template');
        $sheet->setCellValue('C1', 'Tên template');
        $sheet->setCellValue('D1', 'Trạng thái');
        $sheet->setCellValue('E1', 'Ngày tạo');
        
        // Điền dữ liệu
        $row = 2;
        foreach ($templates as $i => $template) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $template->ma_template ?: 'Chưa cập nhật');
            $sheet->setCellValue('C' . $row, $template->ten_template);
            $sheet->setCellValue('D' . $row, $template->status == 1 ? 'Hoạt động' : 'Không hoạt động');
            $sheet->setCellValue('E' . $row, $template->getCreatedAtFormatted());
            
            // Căn giữa cột STT và trạng thái
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $row++;
        }
        
        // Tự động điều chỉnh độ rộng cột
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Thêm border cho toàn bộ bảng
        $lastRow = $row - 1;
        $sheet->getStyle('A1:E' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Thiết lập header để tải xuống
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="danh_sach_template.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Tạo file Excel và xuất file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    
    /**
     * Xuất danh sách template ra file PDF
     */
    public function exportPdf()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'ten_template';
        $order = $this->request->getGet('order') ?? 'ASC';
        
        // Chuẩn bị điều kiện tìm kiếm
        $searchCriteria = [
            'keyword' => $keyword,
            'status' => $status,
            'bin' => 0
        ];
        
        $searchOptions = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả bản ghi
        ];
        
        // Lấy dữ liệu template
        $templates = $this->model->search($searchCriteria, $searchOptions);
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'DANH SÁCH TEMPLATE',
            'date' => date('d/m/Y H:i:s'),
            'templates' => $templates,
            'filters' => $this->getFilterDescription($keyword, $status)
        ];
        
        // Tạo file PDF với các tùy chọn
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('App\Modules\template\Views\export_pdf', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Xuất file
        $dompdf->stream('danh_sach_template.pdf', ['Attachment' => true]);
        exit();
    }
    
    /**
     * Xuất danh sách template đã xóa ra file PDF
     */
    public function exportDeletedPdf()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'deleted_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        
        // Chuẩn bị điều kiện tìm kiếm
        $searchCriteria = [
            'keyword' => $keyword,
            'status' => $status,
            'bin' => 1
        ];
        
        $searchOptions = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả bản ghi
        ];
        
        // Lấy dữ liệu template đã xóa
        $templates = $this->model->search($searchCriteria, $searchOptions);
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'DANH SÁCH TEMPLATE ĐÃ XÓA',
            'date' => date('d/m/Y H:i:s'),
            'templates' => $templates,
            'filters' => $this->getFilterDescription($keyword, $status)
        ];
        
        // Tạo file PDF với các tùy chọn
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('App\Modules\template\Views\export_pdf', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Xuất file
        $dompdf->stream('danh_sach_template_da_xoa.pdf', ['Attachment' => true]);
        exit();
    }
    
    /**
     * Xuất danh sách template đã xóa ra file Excel
     */
    public function exportDeletedExcel()
    {
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'deleted_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        
        // Chuẩn bị điều kiện tìm kiếm
        $searchCriteria = [
            'keyword' => $keyword,
            'status' => $status,
            'bin' => 1
        ];
        
        $searchOptions = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả bản ghi
        ];
        
        // Lấy dữ liệu template đã xóa
        $templates = $this->model->search($searchCriteria, $searchOptions);
        
        // Tạo file Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập font chữ và style
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        $sheet->getStyle('A1:F1')->getFont()->setSize(12);
        $sheet->getStyle('A1:F1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F5F5F5');
        $sheet->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Thiết lập tiêu đề
        $sheet->setCellValue('A1', 'STT');
        $sheet->setCellValue('B1', 'Mã template');
        $sheet->setCellValue('C1', 'Tên template');
        $sheet->setCellValue('D1', 'Trạng thái');
        $sheet->setCellValue('E1', 'Ngày tạo');
        $sheet->setCellValue('F1', 'Ngày xóa');
        
        // Điền dữ liệu
        $row = 2;
        foreach ($templates as $i => $template) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $template->ma_template ?: 'Chưa cập nhật');
            $sheet->setCellValue('C' . $row, $template->ten_template);
            $sheet->setCellValue('D' . $row, $template->status == 1 ? 'Hoạt động' : 'Không hoạt động');
            $sheet->setCellValue('E' . $row, $template->getCreatedAtFormatted());
            $sheet->setCellValue('F' . $row, $template->getDeletedAtFormatted());
            
            // Căn giữa cột STT và trạng thái
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $row++;
        }
        
        // Tự động điều chỉnh độ rộng cột
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Thêm border cho toàn bộ bảng
        $lastRow = $row - 1;
        $sheet->getStyle('A1:F' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Thiết lập header để tải xuống
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="danh_sach_template_da_xoa.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Tạo file Excel và xuất file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    
    /**
     * Tạo mô tả bộ lọc
     *
     * @param string|null $keyword Từ khóa tìm kiếm
     * @param string|null $status Trạng thái
     * @return string
     */
    private function getFilterDescription($keyword = null, $status = null)
    {
        $filters = [];
        
        if (!empty($keyword)) {
            $filters[] = "Từ khóa: " . $keyword;
        }
        
        if (isset($status) && $status !== '') {
            $filters[] = "Trạng thái: " . ($status == 1 ? 'Hoạt động' : 'Không hoạt động');
        }
        
        return implode(', ', $filters);
    }
} 