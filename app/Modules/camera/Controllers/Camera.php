<?php

namespace App\Modules\camera\Controllers;

use App\Controllers\BaseController;
use App\Modules\camera\Models\CameraModel;
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

class Camera extends BaseController
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
        $this->model = new CameraModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url('camera');
        $this->moduleName = 'Camera';
        
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
        $sort = $this->request->getGet('sort') ?? 'ten_camera';
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
        
        // Lấy dữ liệu camera và thông tin phân trang
        $cameras = $this->model->search($searchParams, [
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
            $redirectUrl = site_url('camera') . '?' . http_build_query($redirectParams);
            
            // Chuyển hướng đến trang cuối cùng
            return redirect()->to($redirectUrl);
        }
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath('camera');
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
        
        // Kiểm tra số lượng camera trả về
        log_message('debug', '[Controller] Số lượng camera trả về: ' . count($cameras));
        if (!empty($cameras)) {
            $firstCamera = $cameras[0];
            log_message('debug', '[Controller] Camera đầu tiên: ID=' . $firstCamera->camera_id . 
                ', Tên=' . $firstCamera->ten_camera . 
                ', Status=' . $firstCamera->status);
        }
        
        // Chuẩn bị dữ liệu cho view
        $this->data['cameras'] = $cameras;
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
            'camera_count' => count($cameras)
        ]));
        
        // Hiển thị view
        return view('App\Modules\camera\Views\index', $this->data);
    }
    
    /**
     * Hiển thị form tạo mới
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Chuẩn bị dữ liệu mặc định cho entity mới
        $camera = new \App\Modules\camera\Entities\Camera([
            'status' => 1,
            'bin' => 0
        ]);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm ' . $this->moduleName,
            'validation' => $this->validator,
            'moduleUrl' => $this->moduleUrl,
            'camera' => $camera,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
            'is_new' => true
        ];
        
        return view('App\Modules\camera\Views\new', $viewData);
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
            'ten_camera' => trim($request->getPost('ten_camera')),
            'ma_camera' => trim($request->getPost('ma_camera')),
            'ip_camera' => trim($request->getPost('ip_camera')),
            'port' => $request->getPost('port'),
            'username' => trim($request->getPost('username')),
            'password' => $request->getPost('password'),
            'status' => $request->getPost('status') ?? 1,
            'bin' => 0
        ];

        // Kiểm tra xem tên camera đã tồn tại chưa
        if (!empty($data['ten_camera'])) {
            // Tìm trực tiếp trong database 
            $existingCamera = $this->model->builder()
                ->where('ten_camera', $data['ten_camera'])
                ->where($this->model->deletedField, null)  // Loại trừ records đã xóa mềm
                ->get()
                ->getRow();
                
            if ($existingCamera) {
                $this->alert->set('danger', 'Tên camera "' . $data['ten_camera'] . '" đã tồn tại, vui lòng chọn tên khác', true);
                return redirect()->back()->withInput();
            }
        }

        // Lưu dữ liệu vào database
        try {
            if ($this->model->insert($data)) {
                $this->alert->set('success', 'Thêm camera thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                $errors = $this->model->errors();
                if (!empty($errors)) {
                    return redirect()->back()->withInput()->with('errors', $errors);
                }
                
                $this->alert->set('danger', 'Có lỗi xảy ra khi thêm camera', true);
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            // Log lỗi
            log_message('error', 'Lỗi khi thêm camera: ' . $e->getMessage());
            
            // Kiểm tra nếu là lỗi duplicate key
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $this->alert->set('danger', 'Tên camera đã tồn tại trong hệ thống, vui lòng chọn tên khác', true);
            } else {
                $this->alert->set('danger', 'Có lỗi xảy ra khi thêm camera: ' . $e->getMessage(), true);
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
            $this->alert->set('danger', 'ID camera không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin camera với relationship
        $camera = $this->model->findWithRelations($id);
        
        if (empty($camera)) {
            $this->alert->set('danger', 'Không tìm thấy camera', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chi tiết', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chi tiết ' . $this->moduleName,
            'camera' => $camera,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\camera\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID camera không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Sử dụng phương thức findWithRelations từ model
        $camera = $this->model->findWithRelations($id);
        
        if (empty($camera)) {
            $this->alert->set('danger', 'Không tìm thấy camera', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chỉnh sửa', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chỉnh sửa ' . $this->moduleName,
            'validation' => $this->validator,
            'camera' => $camera,
            'moduleUrl' => $this->moduleUrl,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
        ];
        
        return view('App\Modules\camera\Views\edit', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID camera không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin camera với relationship
        $existingCamera = $this->model->findWithRelations($id);
        
        if (empty($existingCamera)) {
            $this->alert->set('danger', 'Không tìm thấy camera', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xác thực dữ liệu gửi lên
        $data = $this->request->getPost();
        
        // Chuẩn bị quy tắc validation cho cập nhật - cần truyền mảng có chứa camera_id
        $this->model->prepareValidationRules('update', ['camera_id' => $id]);
        
        // Xử lý validation với quy tắc đã được điều chỉnh
        if (!$this->validate($this->model->getValidationRules())) {
            // Nếu validation thất bại, quay lại form với lỗi
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Kiểm tra xem tên camera đã tồn tại chưa (trừ chính nó)
        if (!empty($data['ten_camera']) && $this->model->isNameExists($data['ten_camera'], $id)) {
            $this->alert->set('danger', 'Tên camera đã tồn tại', true);
            return redirect()->back()->withInput();
        }
        
        // Chuẩn bị dữ liệu để cập nhật
        $updateData = [
            'ten_camera' => $data['ten_camera'],
            'ma_camera' => $data['ma_camera'] ?? null,
            'ip_camera' => $data['ip_camera'] ?? null,
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
        $updateData['created_at'] = $existingCamera->created_at;
        if ($existingCamera->deleted_at) {
            $updateData['deleted_at'] = $existingCamera->deleted_at;
        }
        
        // Cập nhật dữ liệu vào database
        if ($this->model->update($id, $updateData)) {
            $this->alert->set('success', 'Cập nhật camera thành công', true);
            return redirect()->to($this->moduleUrl);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi cập nhật camera: ' . implode(', ', $this->model->errors()), true);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Xóa mềm (chuyển vào thùng rác)
     */
    public function delete($id = null, $backToUrl = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID camera không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Kiểm tra nếu camera đang được sử dụng ở màn hình
        $manhinhModel = new \App\Modules\manhinh\Models\ManhinhModel();
        $usedInManhinh = $manhinhModel->where('camera_id', $id)->where('bin', 0)->countAllResults();
        
        if ($usedInManhinh > 0) {
            $this->alert->set('warning', 'Không thể xóa camera đang được sử dụng bởi ' . $usedInManhinh . ' màn hình', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Thực hiện xóa mềm (chuyển vào thùng rác)
        if ($this->model->moveToRecycleBin($id)) {
            $this->alert->set('success', 'Đã chuyển camera vào thùng rác', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa camera', true);
        }
        
        // Lấy URL trả về từ tham số truy vấn hoặc từ tham số đường dẫn
        $returnUrl = $this->request->getGet('return_url') ?? $backToUrl;
        
        // Xử lý URL chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Hiển thị danh sách camera trong thùng rác
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
            'bin' => 1, // Luôn lấy các camera trong thùng rác
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
        
        // Lấy dữ liệu camera và thông tin phân trang
        $cameras = $this->model->search($searchParams, [
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage,
            'sort' => $sort,
            'order' => $order
        ]);
        
        $total = $this->model->countSearchResults($searchParams);
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath('camera/listdeleted');
            // Không cần thiết lập segment vì chúng ta sử dụng query string
            $pager->setOnly(['keyword', 'perPage', 'sort', 'order', 'status']);
            
            // Đảm bảo perPage được thiết lập đúng trong pager
            $pager->setPerPage($perPage);
            
            // Thiết lập trang hiện tại
            $pager->setCurrentPage($page);
        }
        
        // Chuẩn bị dữ liệu cho view
        $this->data['cameras'] = $cameras;
        $this->data['pager'] = $pager;
        $this->data['currentPage'] = $page;
        $this->data['perPage'] = $perPage;
        $this->data['total'] = $total;
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['keyword'] = $keyword;
        $this->data['status'] = $status;
        
        // Hiển thị view
        return view('App\Modules\camera\Views\listdeleted', $this->data);
    }
    
    /**
     * Khôi phục từ thùng rác
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID camera không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form
        $returnUrl = $this->request->getPost('return_url');
        log_message('debug', 'Restore - Return URL: ' . ($returnUrl ?? 'None'));
        
        if ($this->model->restoreFromRecycleBin($id)) {
            $this->alert->set('success', 'Đã khôi phục camera từ thùng rác', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục camera', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn một camera
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID camera không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form
        $returnUrl = $this->request->getPost('return_url');
        log_message('debug', 'PermanentDelete - Return URL: ' . ($returnUrl ?? 'None'));
        
        if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
            $this->alert->set('success', 'Đã xóa vĩnh viễn camera', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa camera', true);
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
        
        return view('App\Modules\camera\Views\search', $viewData);
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
        // Lấy tham số lọc từ URL
        $keyword = $this->request->getGet('keyword') ?? '';
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'ten_camera';
        $order = $this->request->getGet('order') ?? 'ASC';
        
        // Xây dựng tham số tìm kiếm
        $searchParams = [];
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Đặc biệt, chỉ thêm status vào nếu nó đã được chỉ định (bao gồm status=0)
        if ($status !== null && $status !== '') {
            $searchParams['status'] = (int)$status;
        }
        
        // Log tham số tìm kiếm cuối cùng
        log_message('debug', '[ExportExcel] Tham số tìm kiếm: ' . json_encode($searchParams));
        
        // Lấy dữ liệu camera theo bộ lọc mà không giới hạn phân trang
        $cameras = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        log_message('debug', '[ExportExcel] Số lượng camera xuất: ' . count($cameras));
        
        // Sử dụng thư viện PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập tiêu đề
        $sheet->setCellValue('A1', 'DANH SÁCH CAMERA');
        
        // Thêm thông tin bộ lọc vào tiêu đề nếu có
        $filterInfo = [];
        if (!empty($keyword)) {
            $filterInfo[] = "Từ khóa: " . $keyword;
        }
        if ($status !== null && $status !== '') {
            $filterInfo[] = "Trạng thái: " . ($status == 1 ? 'Hoạt động' : 'Không hoạt động');
        }
        
        if (!empty($filterInfo)) {
            $sheet->setCellValue('A2', 'Bộ lọc: ' . implode(', ', $filterInfo));
            $sheet->mergeCells('A2:G2');
            $sheet->getStyle('A2')->getFont()->setItalic(true);
        }
        
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Thiết lập header
        $headerRow = !empty($filterInfo) ? 4 : 3;
        $sheet->setCellValue('A' . $headerRow, 'STT');
        $sheet->setCellValue('B' . $headerRow, 'MÃ CAMERA');
        $sheet->setCellValue('C' . $headerRow, 'TÊN CAMERA');
        $sheet->setCellValue('D' . $headerRow, 'ĐỊA CHỈ IP');
        $sheet->setCellValue('E' . $headerRow, 'PORT');
        $sheet->setCellValue('F' . $headerRow, 'TÀI KHOẢN');
        $sheet->setCellValue('G' . $headerRow, 'TRẠNG THÁI');
        
        // Định dạng header
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFE0E0E0',
                ],
            ],
        ];
        $sheet->getStyle('A' . $headerRow . ':G' . $headerRow)->applyFromArray($headerStyle);
        
        // Đổ dữ liệu vào sheet
        $row = $headerRow + 1;
        $i = 1;
        foreach ($cameras as $camera) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $camera->ma_camera);
            $sheet->setCellValue('C' . $row, $camera->ten_camera);
            $sheet->setCellValue('D' . $row, $camera->ip_camera);
            $sheet->setCellValue('E' . $row, $camera->port);
            $sheet->setCellValue('F' . $row, $camera->username);
            
            // Xử lý trạng thái
            $status = $camera->status == 1 ? 'Hoạt động' : 'Không hoạt động';
            $sheet->setCellValue('G' . $row, $status);
            
            $row++;
            $i++;
        }
        
        // Định dạng dữ liệu
        $dataStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        $sheet->getStyle('A' . ($headerRow + 1) . ':G' . ($row - 1))->applyFromArray($dataStyle);
        
        // Điều chỉnh kích thước cột
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(40);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(10);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(15);
        
        // Thêm ngày xuất báo cáo
        $row += 1;
        $sheet->setCellValue('A' . $row, 'Ngày xuất báo cáo: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A' . $row . ':G' . $row);
        
        // Định dạng ngày xuất báo cáo
        $sheet->getStyle('A' . $row)->getFont()->setItalic(true);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        
        // Thiết lập header để tải xuống
        $filterSuffix = '';
        if (!empty($keyword)) {
            $filterSuffix .= '_keyword_' . preg_replace('/[^a-z0-9]/i', '_', $keyword);
        }
        if ($status !== null && $status !== '') {
            $filterSuffix .= '_status_' . $status;
        }
        
        $filename = 'Danh_sach_camera' . $filterSuffix . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Redirect output to client browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    
    /**
     * Xuất danh sách camera ra file PDF
     */
    public function exportPdf()
    {
        // Lấy tham số lọc từ URL
        $keyword = $this->request->getGet('keyword') ?? '';
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'ten_camera';
        $order = $this->request->getGet('order') ?? 'ASC';
        
        // Xây dựng tham số tìm kiếm
        $searchParams = [];
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Đặc biệt, chỉ thêm status vào nếu nó đã được chỉ định (bao gồm status=0)
        if ($status !== null && $status !== '') {
            $searchParams['status'] = (int)$status;
        }
        
        // Lấy dữ liệu camera theo bộ lọc mà không giới hạn phân trang
        $cameras = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'DANH SÁCH CAMERA',
            'cameras' => $cameras,
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
            $data['filters'] = implode(', ', $filterInfo);
        }
        
        // Render view thành HTML
        $html = view('App\Modules\camera\Views\export_pdf', $data);
        
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
        
        $filename = 'danh_sach_camera' . $filterSuffix . '_' . date('dmY_His') . '.pdf';
        
        // Stream file PDF để tải xuống
        $dompdf->stream($filename, ['Attachment' => true]);
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
            'bin' => 1 // Luôn lấy các camera trong thùng rác
        ];
        
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Đặc biệt, chỉ thêm status vào nếu nó đã được chỉ định (bao gồm status=0)
        if ($status !== null && $status !== '') {
            $searchParams['status'] = (int)$status;
        }
        
        // Lấy dữ liệu camera theo bộ lọc mà không giới hạn phân trang
        $cameras = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        // Tạo dữ liệu cho PDF
        $pdfData = [
            'title' => 'DANH SÁCH CAMERA ĐÃ XÓA',
            'cameras' => $cameras,
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
        $html = view('App\Modules\camera\Views\export_pdf', $pdfData);
        
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
        
        $filename = 'danh_sach_camera_da_xoa' . $filterSuffix . '_' . date('dmY_His') . '.pdf';
        
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
} 