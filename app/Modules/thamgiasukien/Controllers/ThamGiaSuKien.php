<?php

namespace App\Modules\thamgiasukien\Controllers;

use App\Controllers\BaseController;
use App\Modules\thamgiasukien\Models\ThamGiaSuKienModel;
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

class ThamGiaSuKien extends BaseController
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
        $this->model = new ThamGiaSuKienModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url('thamgiasukien');
        $this->moduleName = 'Tham Gia Sự Kiện';
        
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
        $sort = $this->request->getGet('sort') ?? 'created_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        $keyword = $this->request->getGet('keyword') ?? '';
        $status = $this->request->getGet('status');
        $nguoiDungId = $this->request->getGet('nguoi_dung_id');
        $suKienId = $this->request->getGet('su_kien_id');
        $phuongThucDiemDanh = $this->request->getGet('phuong_thuc_diem_danh');
        
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
        
        // Thêm các tham số tìm kiếm khác
        if (!empty($nguoiDungId)) {
            $searchParams['nguoi_dung_id'] = $nguoiDungId;
        }
        
        if (!empty($suKienId)) {
            $searchParams['su_kien_id'] = $suKienId;
        }
        
        if (!empty($phuongThucDiemDanh)) {
            $searchParams['phuong_thuc_diem_danh'] = $phuongThucDiemDanh;
        }
        
        // Log tham số tìm kiếm cuối cùng
        log_message('debug', '[Controller] Tham số tìm kiếm cuối cùng: ' . json_encode($searchParams));
        
        // Lấy dữ liệu tham gia sự kiện và thông tin phân trang
        $thamGiaSuKiens = $this->model->search($searchParams, [
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
            $redirectUrl = site_url('thamgiasukien') . '?' . http_build_query($redirectParams);
            
            // Chuyển hướng đến trang cuối cùng
            return redirect()->to($redirectUrl);
        }
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath('thamgiasukien');
            // Thêm tất cả các tham số cần giữ lại khi chuyển trang
            $pager->setOnly(['keyword', 'status', 'perPage', 'sort', 'order', 'nguoi_dung_id', 'su_kien_id', 'phuong_thuc_diem_danh']);
            
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
        
        // Kiểm tra số lượng bản ghi trả về
        log_message('debug', '[Controller] Số lượng bản ghi trả về: ' . count($thamGiaSuKiens));
        if (!empty($thamGiaSuKiens)) {
            $firstItem = $thamGiaSuKiens[0];
            log_message('debug', '[Controller] Bản ghi đầu tiên: ID=' . $firstItem->tham_gia_su_kien_id . 
                ', Người dùng ID=' . $firstItem->nguoi_dung_id . 
                ', Sự kiện ID=' . $firstItem->su_kien_id . 
                ', Status=' . $firstItem->status);
        }
        
        // Chuẩn bị dữ liệu cho view
        $this->data['thamGiaSuKiens'] = $thamGiaSuKiens;
        $this->data['pager'] = $pager;
        $this->data['currentPage'] = $page;
        $this->data['perPage'] = $perPage;
        $this->data['total'] = $total;
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['keyword'] = $keyword;
        $this->data['status'] = $status; // Giữ nguyên status gốc từ request
        $this->data['nguoi_dung_id'] = $nguoiDungId;
        $this->data['su_kien_id'] = $suKienId;
        $this->data['phuong_thuc_diem_danh'] = $phuongThucDiemDanh;
        
        // Debug thông tin cuối cùng
        log_message('debug', '[Controller] Dữ liệu gửi đến view: ' . json_encode([
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'pageCount' => $pager ? $pager->getPageCount() : 0,
            'status' => $status,
            'item_count' => count($thamGiaSuKiens)
        ]));
        
        // Hiển thị view
        return view('App\Modules\thamgiasukien\Views\index', $this->data);
    }
    
    /**
     * Hiển thị form tạo mới
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Chuẩn bị dữ liệu mặc định cho entity mới
        $thamGiaSuKien = new \App\Modules\thamgiasukien\Entities\ThamGiaSuKien([
            'status' => 1,
        ]);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm ' . $this->moduleName,
            'validation' => $this->validator,
            'moduleUrl' => $this->moduleUrl,
            'thamGiaSuKien' => $thamGiaSuKien,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
            'is_new' => true
        ];
        
        return view('App\Modules\thamgiasukien\Views\new', $viewData);
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
            'nguoi_dung_id' => (int)trim($request->getPost('nguoi_dung_id')),
            'su_kien_id' => (int)trim($request->getPost('su_kien_id')),
            'phuong_thuc_diem_danh' => trim($request->getPost('phuong_thuc_diem_danh')),
            'thoi_gian_diem_danh' => $request->getPost('thoi_gian_diem_danh'),
            'ghi_chu' => trim($request->getPost('ghi_chu')),
            'status' => $request->getPost('status') ?? 1
        ];

        // Kiểm tra xem người dùng đã tham gia sự kiện này chưa
        if (!empty($data['nguoi_dung_id']) && !empty($data['su_kien_id'])) {
            $existingRecord = $this->model->builder()
                ->where('nguoi_dung_id', $data['nguoi_dung_id'])
                ->where('su_kien_id', $data['su_kien_id'])
                ->get()
                ->getRow();
                
            if ($existingRecord) {
                $this->alert->set('danger', 'Người dùng này đã tham gia sự kiện, vui lòng kiểm tra lại', true);
                return redirect()->back()->withInput();
            }
        }

        // Lưu dữ liệu vào database
        try {
            if ($this->model->insert($data)) {
                $this->alert->set('success', 'Thêm dữ liệu tham gia sự kiện thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                $errors = $this->model->errors();
                if (!empty($errors)) {
                    return redirect()->back()->withInput()->with('errors', $errors);
                }
                
                $this->alert->set('danger', 'Có lỗi xảy ra khi thêm dữ liệu', true);
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            // Log lỗi
            log_message('error', 'Lỗi khi thêm dữ liệu tham gia sự kiện: ' . $e->getMessage());
            
            // Kiểm tra nếu là lỗi duplicate key
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $this->alert->set('danger', 'Người dùng đã tham gia sự kiện này, vui lòng kiểm tra lại', true);
            } else {
                $this->alert->set('danger', 'Có lỗi xảy ra khi thêm dữ liệu: ' . $e->getMessage(), true);
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
            $this->alert->set('danger', 'ID tham gia sự kiện không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin với relationship
        $thamGiaSuKien = $this->model->findWithRelations($id);
        
        if (empty($thamGiaSuKien)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu tham gia sự kiện', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chi tiết', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chi tiết ' . $this->moduleName,
            'thamGiaSuKien' => $thamGiaSuKien,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\thamgiasukien\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID tham gia sự kiện không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Sử dụng phương thức findWithRelations từ model
        $thamGiaSuKien = $this->model->findWithRelations($id);
        
        if (empty($thamGiaSuKien)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu tham gia sự kiện', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chỉnh sửa', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chỉnh sửa ' . $this->moduleName,
            'validation' => $this->validator,
            'thamGiaSuKien' => $thamGiaSuKien,
            'moduleUrl' => $this->moduleUrl,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
        ];
        
        return view('App\Modules\thamgiasukien\Views\edit', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID tham gia sự kiện không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin tham gia sự kiện với relationship
        $existingRecord = $this->model->findWithRelations($id);
        
        if (empty($existingRecord)) {
            $this->alert->set('danger', 'Không tìm thấy dữ liệu tham gia sự kiện', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xác thực dữ liệu gửi lên
        $data = $this->request->getPost();
        
        // Chuẩn bị quy tắc validation cho cập nhật - cần truyền mảng có chứa tham_gia_su_kien_id
        $this->model->prepareValidationRules('update', ['tham_gia_su_kien_id' => $id]);
        
        // Xử lý validation với quy tắc đã được điều chỉnh
        if (!$this->validate($this->model->getValidationRules())) {
            // Nếu validation thất bại, quay lại form với lỗi
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Chuẩn bị dữ liệu để cập nhật
        $updateData = [
            'nguoi_dung_id' => (int)$data['nguoi_dung_id'],
            'su_kien_id' => (int)$data['su_kien_id'],
            'thoi_gian_diem_danh' => $data['thoi_gian_diem_danh'] ?? null,
            'phuong_thuc_diem_danh' => $data['phuong_thuc_diem_danh'],
            'ghi_chu' => $data['ghi_chu'] ?? null,
            'status' => $data['status'] ?? 0
        ];
        
        // Giữ lại các trường thời gian từ dữ liệu hiện có
        $updateData['created_at'] = $existingRecord->created_at;
        
        // Kiểm tra xem nguoi_dung_id và su_kien_id mới đã có trong hệ thống chưa
        if (($existingRecord->nguoi_dung_id != $updateData['nguoi_dung_id'] || 
             $existingRecord->su_kien_id != $updateData['su_kien_id']) &&
            $this->model->where('nguoi_dung_id', $updateData['nguoi_dung_id'])
                         ->where('su_kien_id', $updateData['su_kien_id'])
                         ->where('tham_gia_su_kien_id !=', $id)
                         ->countAllResults() > 0) {
            $this->alert->set('danger', 'Người dùng này đã tham gia sự kiện này, vui lòng kiểm tra lại', true);
            return redirect()->back()->withInput();
        }
        
        // Cập nhật dữ liệu vào database
        if ($this->model->update($id, $updateData)) {
            $this->alert->set('success', 'Cập nhật dữ liệu tham gia sự kiện thành công', true);
            return redirect()->to($this->moduleUrl);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi cập nhật dữ liệu: ' . implode(', ', $this->model->errors()), true);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Xóa mềm (chuyển vào thùng rác)
     */
    public function delete($id = null, $backToUrl = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID tham gia sự kiện không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Thực hiện xóa mềm (chuyển vào thùng rác)
        if ($this->model->delete($id)) {
            $this->alert->set('success', 'Đã xóa dữ liệu tham gia sự kiện thành công', true);
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
     * Hiển thị danh sách tham gia sự kiện đã xóa
     */
    public function listdeleted()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Lịch sử xóa', current_url());
        $this->data['breadcrumb'] = $this->breadcrumb->render();
        $this->data['title'] = 'Lịch sử xóa ' . $this->moduleName;
        
        // Lấy tham số từ URL
        $page = (int)($this->request->getGet('page') ?? 1);
        $perPage = (int)($this->request->getGet('perPage') ?? 10);
        $sort = $this->request->getGet('sort') ?? 'deleted_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        $keyword = $this->request->getGet('keyword') ?? '';
        $status = $this->request->getGet('status');
        $nguoiDungId = $this->request->getGet('nguoi_dung_id');
        $suKienId = $this->request->getGet('su_kien_id');
        
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
            'sort' => $sort,
            'order' => $order,
            'deleted' => true  // Chỉ lấy các bản ghi đã xóa
        ];
        
        // Thêm keyword nếu có
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Đặc biệt, chỉ thêm status vào nếu nó đã được chỉ định (bao gồm status=0)
        if ($status !== null && $status !== '') {
            $searchParams['status'] = $statusFilter;
        }
        
        // Thêm các tham số tìm kiếm khác
        if (!empty($nguoiDungId)) {
            $searchParams['nguoi_dung_id'] = $nguoiDungId;
        }
        
        if (!empty($suKienId)) {
            $searchParams['su_kien_id'] = $suKienId;
        }
        
        // Log tham số tìm kiếm cuối cùng
        log_message('debug', '[Controller:listdeleted] Tham số tìm kiếm cuối cùng: ' . json_encode($searchParams));
        
        // Lấy dữ liệu đã xóa và thông tin phân trang
        $items = $this->model->searchDeleted($searchParams, [
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage
        ]);
        
        $total = $this->model->countDeletedResults($searchParams);
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath('thamgiasukien/listdeleted');
            // Không cần thiết lập segment vì chúng ta sử dụng query string
            $pager->setOnly(['keyword', 'perPage', 'sort', 'order', 'status', 'nguoi_dung_id', 'su_kien_id']);
            
            // Đảm bảo perPage được thiết lập đúng trong pager
            $pager->setPerPage($perPage);
            
            // Thiết lập trang hiện tại
            $pager->setCurrentPage($page);
        }
        
        // Chuẩn bị dữ liệu cho view
        $this->data['thamGiaSuKiens'] = $items;
        $this->data['pager'] = $pager;
        $this->data['currentPage'] = $page;
        $this->data['perPage'] = $perPage;
        $this->data['total'] = $total;
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['keyword'] = $keyword;
        $this->data['status'] = $status;
        $this->data['nguoi_dung_id'] = $nguoiDungId;
        $this->data['su_kien_id'] = $suKienId;
        
        // Hiển thị view
        return view('App\Modules\thamgiasukien\Views\listdeleted', $this->data);
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
        log_message('debug', 'Restore - Return URL: ' . ($returnUrl ?? 'None'));
        
        if ($this->model->restoreFromRecycleBin($id)) {
            $this->alert->set('success', 'Đã khôi phục dữ liệu từ thùng rác', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục dữ liệu', true);
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
        log_message('debug', 'PermanentDelete - Return URL: ' . ($returnUrl ?? 'None'));
        
        if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
            $this->alert->set('success', 'Đã xóa vĩnh viễn dữ liệu', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa dữ liệu', true);
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
        
        return view('App\Modules\thamgiasukien\Views\search', $viewData);
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
     * Khôi phục nhiều bản ghi từ thùng rác
     */
    public function restoreMultiple()
    {
        // Lấy các ID được chọn từ form và URL trả về
        $selectedItems = $this->request->getPost('selected_items');
        
        // Kiểm tra nếu selectedItems là mảng (selected_items[])
        if (is_array($this->request->getPost('selected_items[]'))) {
            $selectedItems = $this->request->getPost('selected_items[]');
        }
        
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        // Log thông tin để debug
        log_message('debug', 'RestoreMultiple - POST data: ' . json_encode($_POST));
        log_message('debug', 'RestoreMultiple - Selected Items: ' . (is_array($selectedItems) ? json_encode($selectedItems) : $selectedItems));
        log_message('debug', 'RestoreMultiple - Return URL: ' . ($returnUrl ?? 'None'));
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn dữ liệu nào để khôi phục', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        $failCount = 0;
        $errorMessages = [];
        
        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        // Log thông tin mảng ID để debug
        log_message('debug', 'RestoreMultiple - ID Array: ' . json_encode($idArray));
        log_message('debug', 'RestoreMultiple - Số lượng ID cần khôi phục: ' . count($idArray));
        
        foreach ($idArray as $id) {
            log_message('debug', 'RestoreMultiple - Đang khôi phục ID: ' . $id);
            
            try {
                $template = $this->model->find($id);
                if (!$template) {
                    log_message('error', 'RestoreMultiple - Không tìm thấy dữ liệu với ID: ' . $id);
                    $failCount++;
                    $errorMessages[] = "Không tìm thấy dữ liệu ID: {$id}";
                    continue;
                }
                
                // Kiểm tra xem bản ghi có đang trong thùng rác không
                if ($template->bin != 1) {
                    log_message('warning', 'RestoreMultiple - Dữ liệu ID: ' . $id . ' không nằm trong thùng rác (bin = ' . $template->bin . ')');
                    $failCount++;
                    $errorMessages[] = "Dữ liệu ID: {$id} không nằm trong thùng rác";
                    continue;
                }
                
                // Đặt lại trạng thái bin và lưu
                $template->bin = false;
                if ($this->model->save($template)) {
                    $successCount++;
                    log_message('debug', 'RestoreMultiple - Khôi phục thành công ID: ' . $id);
                } else {
                    $failCount++;
                    $errors = $this->model->errors() ? json_encode($this->model->errors()) : 'Unknown error';
                    log_message('error', 'RestoreMultiple - Lỗi lưu dữ liệu ID: ' . $id . ', Errors: ' . $errors);
                    $errorMessages[] = "Lỗi lưu dữ liệu ID: {$id}";
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
                $this->alert->set('warning', "Đã khôi phục {$successCount} dữ liệu, nhưng có {$failCount} dữ liệu không thể khôi phục", true);
            } else {
                $this->alert->set('success', "Đã khôi phục {$successCount} dữ liệu từ thùng rác", true);
            }
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể khôi phục dữ liệu nào', true);
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
     * Xóa vĩnh viễn nhiều bản ghi
     */
    public function permanentDeleteMultiple()
    {
        // Lấy các ID được chọn từ form và URL trả về
        $selectedItems = $this->request->getPost('selected_items');
        
        // Kiểm tra nếu selectedItems là mảng (selected_items[])
        if (is_array($this->request->getPost('selected_items[]'))) {
            $selectedItems = $this->request->getPost('selected_items[]');
        }
        
        $returnUrl = $this->request->getPost('return_url') ?? $this->request->getServer('HTTP_REFERER');
        
        // Log thông tin để debug
        log_message('debug', 'PermanentDeleteMultiple - POST data: ' . json_encode($_POST));
        log_message('debug', 'PermanentDeleteMultiple - Selected Items: ' . (is_array($selectedItems) ? json_encode($selectedItems) : $selectedItems));
        log_message('debug', 'PermanentDeleteMultiple - Return URL: ' . ($returnUrl ?? 'None'));
        
        if (empty($selectedItems)) {
            $this->alert->set('warning', 'Chưa chọn dữ liệu nào để xóa vĩnh viễn', true);
            
            // Chuyển hướng đến URL đích đã xử lý
            $redirectUrl = $this->processReturnUrl($returnUrl);
            return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
        }
        
        $successCount = 0;
        
        // Đảm bảo $selectedItems là mảng
        $idArray = is_array($selectedItems) ? $selectedItems : explode(',', $selectedItems);
        
        // Log thông tin mảng ID để debug
        log_message('debug', 'PermanentDeleteMultiple - ID Array: ' . json_encode($idArray));
        log_message('debug', 'PermanentDeleteMultiple - Số lượng ID cần xóa vĩnh viễn: ' . count($idArray));
        
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
            $this->alert->set('success', "Đã xóa vĩnh viễn $successCount dữ liệu", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa dữ liệu', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xuất danh sách tham gia sự kiện ra file Excel
     */
    public function exportExcel()
    {
        // Lấy tất cả tham số từ URL
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $phuong_thuc_diem_danh = $this->request->getGet('phuong_thuc_diem_danh');
        $nguoi_dung_id = $this->request->getGet('nguoi_dung_id');
        $su_kien_id = $this->request->getGet('su_kien_id');
        $sort = $this->request->getGet('sort') ?? 'tham_gia_su_kien_id';
        $order = $this->request->getGet('order') ?? 'ASC';
        
        // Chuẩn bị điều kiện tìm kiếm
        $searchCriteria = [
            'keyword' => $keyword,
            'bin' => 0
        ];
        
        // Thêm điều kiện tìm kiếm nếu có giá trị
        if ($status !== null && $status !== '') {
            $searchCriteria['status'] = $status;
        }
        
        if ($phuong_thuc_diem_danh !== null && $phuong_thuc_diem_danh !== '') {
            $searchCriteria['phuong_thuc_diem_danh'] = $phuong_thuc_diem_danh;
        }
        
        if ($nguoi_dung_id !== null && $nguoi_dung_id !== '') {
            $searchCriteria['nguoi_dung_id'] = $nguoi_dung_id;
        }
        
        if ($su_kien_id !== null && $su_kien_id !== '') {
            $searchCriteria['su_kien_id'] = $su_kien_id;
        }
        
        $searchOptions = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả bản ghi
        ];
        
        // Lấy dữ liệu tham gia sự kiện
        $thamGiaSuKiens = $this->model->search($searchCriteria, $searchOptions);
        
        // Tạo file Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập font chữ và style
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFont()->setSize(12);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F5F5F5');
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Thiết lập tiêu đề
        $sheet->setCellValue('A1', 'STT');
        $sheet->setCellValue('B1', 'ID');
        $sheet->setCellValue('C1', 'Người dùng ID');
        $sheet->setCellValue('D1', 'Sự kiện ID');
        $sheet->setCellValue('E1', 'Thời gian điểm danh');
        $sheet->setCellValue('F1', 'Phương thức điểm danh');
        $sheet->setCellValue('G1', 'Ghi chú');
        $sheet->setCellValue('H1', 'Trạng thái');
        
        // Điền dữ liệu
        $row = 2;
        foreach ($thamGiaSuKiens as $i => $item) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $item->tham_gia_su_kien_id);
            $sheet->setCellValue('C' . $row, $item->nguoi_dung_id);
            $sheet->setCellValue('D' . $row, $item->su_kien_id);
            
            // Thời gian điểm danh
            if (!empty($item->thoi_gian_diem_danh)) {
                $thoi_gian = $item->thoi_gian_diem_danh instanceof \CodeIgniter\I18n\Time
                    ? $item->thoi_gian_diem_danh->format('d/m/Y H:i:s')
                    : date('d/m/Y H:i:s', strtotime($item->thoi_gian_diem_danh));
                $sheet->setCellValue('E' . $row, $thoi_gian);
            } else {
                $sheet->setCellValue('E' . $row, 'Chưa điểm danh');
            }
            
            // Phương thức điểm danh
            $phuongThuc = 'Thủ công';
            if ($item->phuong_thuc_diem_danh == 'qr_code') {
                $phuongThuc = 'QR Code';
            } elseif ($item->phuong_thuc_diem_danh == 'face_id') {
                $phuongThuc = 'Face ID';
            }
            $sheet->setCellValue('F' . $row, $phuongThuc);
            
            // Ghi chú
            $sheet->setCellValue('G' . $row, $item->ghi_chu ?? '');
            
            // Trạng thái
            $sheet->setCellValue('H' . $row, $item->status == 1 ? 'Hoạt động' : 'Không hoạt động');
            
            // Căn giữa các cột
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $row++;
        }
        
        // Tự động điều chỉnh độ rộng cột
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Thêm border cho toàn bộ bảng
        $lastRow = $row - 1;
        $sheet->getStyle('A1:H' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Tạo thông tin bộ lọc
        $row = $lastRow + 2;
        $sheet->setCellValue('A' . $row, 'Thông tin bộ lọc:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        if (!empty($keyword)) {
            $sheet->setCellValue('A' . $row, 'Từ khóa:');
            $sheet->setCellValue('B' . $row, $keyword);
            $row++;
        }
        
        if ($status !== null && $status !== '') {
            $sheet->setCellValue('A' . $row, 'Trạng thái:');
            $sheet->setCellValue('B' . $row, $status == 1 ? 'Hoạt động' : 'Không hoạt động');
            $row++;
        }
        
        if ($phuong_thuc_diem_danh !== null && $phuong_thuc_diem_danh !== '') {
            $sheet->setCellValue('A' . $row, 'Phương thức điểm danh:');
            $phuongThucText = 'Thủ công';
            if ($phuong_thuc_diem_danh == 'qr_code') {
                $phuongThucText = 'QR Code';
            } elseif ($phuong_thuc_diem_danh == 'face_id') {
                $phuongThucText = 'Face ID';
            }
            $sheet->setCellValue('B' . $row, $phuongThucText);
            $row++;
        }
        
        if ($nguoi_dung_id !== null && $nguoi_dung_id !== '') {
            $sheet->setCellValue('A' . $row, 'Người dùng ID:');
            $sheet->setCellValue('B' . $row, $nguoi_dung_id);
            $row++;
        }
        
        if ($su_kien_id !== null && $su_kien_id !== '') {
            $sheet->setCellValue('A' . $row, 'Sự kiện ID:');
            $sheet->setCellValue('B' . $row, $su_kien_id);
            $row++;
        }
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Tổng số bản ghi:');
        $sheet->setCellValue('B' . $row, count($thamGiaSuKiens));
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Ngày xuất:');
        $sheet->setCellValue('B' . $row, date('d/m/Y H:i:s'));
        
        // Thiết lập header để tải xuống
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="danh_sach_tham_gia_su_kien.xlsx"');
        header('Cache-Control: max-age=0');
        
        // Tạo file Excel và xuất file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    
    /**
     * Xuất danh sách tham gia sự kiện ra file PDF
     */
    public function exportPdf()
    {
        // Lấy tất cả tham số từ URL
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $phuong_thuc_diem_danh = $this->request->getGet('phuong_thuc_diem_danh');
        $nguoi_dung_id = $this->request->getGet('nguoi_dung_id');
        $su_kien_id = $this->request->getGet('su_kien_id');
        $sort = $this->request->getGet('sort') ?? 'tham_gia_su_kien_id';
        $order = $this->request->getGet('order') ?? 'ASC';
        
        // Chuẩn bị điều kiện tìm kiếm
        $searchCriteria = [
            'keyword' => $keyword,
            'bin' => 0
        ];
        
        // Thêm điều kiện tìm kiếm nếu có giá trị
        if ($status !== null && $status !== '') {
            $searchCriteria['status'] = $status;
        }
        
        if ($phuong_thuc_diem_danh !== null && $phuong_thuc_diem_danh !== '') {
            $searchCriteria['phuong_thuc_diem_danh'] = $phuong_thuc_diem_danh;
        }
        
        if ($nguoi_dung_id !== null && $nguoi_dung_id !== '') {
            $searchCriteria['nguoi_dung_id'] = $nguoi_dung_id;
        }
        
        if ($su_kien_id !== null && $su_kien_id !== '') {
            $searchCriteria['su_kien_id'] = $su_kien_id;
        }
        
        $searchOptions = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả bản ghi
        ];
        
        // Lấy dữ liệu tham gia sự kiện
        $thamGiaSuKiens = $this->model->search($searchCriteria, $searchOptions);
        
        // Chuẩn bị dữ liệu cho view
        $filters = $this->getFilterDescription($keyword, $status, $phuong_thuc_diem_danh, $nguoi_dung_id, $su_kien_id);
        $data = [
            'title' => 'DANH SÁCH THAM GIA SỰ KIỆN',
            'date' => date('d/m/Y H:i:s'),
            'thamGiaSuKiens' => $thamGiaSuKiens,
            'filters' => $filters,
            'deleted' => false
        ];
        
        // Tạo file PDF với các tùy chọn
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('App\Modules\thamgiasukien\Views\export_pdf', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Xuất file
        $dompdf->stream('danh_sach_tham_gia_su_kien.pdf', ['Attachment' => true]);
        exit();
    }
    
    /**
     * Xuất danh sách tham gia sự kiện đã xóa ra file PDF
     */
    public function exportDeletedPdf()
    {
        // Lấy tất cả tham số từ URL
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $phuong_thuc_diem_danh = $this->request->getGet('phuong_thuc_diem_danh');
        $nguoi_dung_id = $this->request->getGet('nguoi_dung_id');
        $su_kien_id = $this->request->getGet('su_kien_id');
        $sort = $this->request->getGet('sort') ?? 'deleted_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        
        // Chuẩn bị điều kiện tìm kiếm
        $searchCriteria = [
            'keyword' => $keyword,
            'bin' => 1
        ];
        
        // Thêm điều kiện tìm kiếm nếu có giá trị
        if ($status !== null && $status !== '') {
            $searchCriteria['status'] = $status;
        }
        
        if ($phuong_thuc_diem_danh !== null && $phuong_thuc_diem_danh !== '') {
            $searchCriteria['phuong_thuc_diem_danh'] = $phuong_thuc_diem_danh;
        }
        
        if ($nguoi_dung_id !== null && $nguoi_dung_id !== '') {
            $searchCriteria['nguoi_dung_id'] = $nguoi_dung_id;
        }
        
        if ($su_kien_id !== null && $su_kien_id !== '') {
            $searchCriteria['su_kien_id'] = $su_kien_id;
        }
        
        $searchOptions = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả bản ghi
        ];
        
        // Lấy dữ liệu tham gia sự kiện đã xóa
        $thamGiaSuKiens = $this->model->search($searchCriteria, $searchOptions);
        
        // Chuẩn bị dữ liệu cho view
        $filters = $this->getFilterDescription($keyword, $status, $phuong_thuc_diem_danh, $nguoi_dung_id, $su_kien_id);
        $data = [
            'title' => 'DANH SÁCH THAM GIA SỰ KIỆN ĐÃ XÓA',
            'date' => date('d/m/Y H:i:s'),
            'thamGiaSuKiens' => $thamGiaSuKiens,
            'filters' => $filters,
            'deleted' => true
        ];
        
        // Tạo file PDF với các tùy chọn
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('App\Modules\thamgiasukien\Views\export_pdf', $data));
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        // Xuất file
        $dompdf->stream('danh_sach_tham_gia_su_kien_da_xoa.pdf', ['Attachment' => true]);
        exit();
    }
    
    /**
     * Xuất danh sách tham gia sự kiện đã xóa ra file Excel
     */
    public function exportDeletedExcel()
    {
        // Lấy tất cả tham số từ URL
        $keyword = $this->request->getGet('keyword');
        $status = $this->request->getGet('status');
        $phuong_thuc_diem_danh = $this->request->getGet('phuong_thuc_diem_danh');
        $nguoi_dung_id = $this->request->getGet('nguoi_dung_id');
        $su_kien_id = $this->request->getGet('su_kien_id');
        $sort = $this->request->getGet('sort') ?? 'deleted_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        
        // Chuẩn bị điều kiện tìm kiếm
        $searchCriteria = [
            'keyword' => $keyword,
            'bin' => 1
        ];
        
        // Thêm điều kiện tìm kiếm nếu có giá trị
        if ($status !== null && $status !== '') {
            $searchCriteria['status'] = $status;
        }
        
        if ($phuong_thuc_diem_danh !== null && $phuong_thuc_diem_danh !== '') {
            $searchCriteria['phuong_thuc_diem_danh'] = $phuong_thuc_diem_danh;
        }
        
        if ($nguoi_dung_id !== null && $nguoi_dung_id !== '') {
            $searchCriteria['nguoi_dung_id'] = $nguoi_dung_id;
        }
        
        if ($su_kien_id !== null && $su_kien_id !== '') {
            $searchCriteria['su_kien_id'] = $su_kien_id;
        }
        
        $searchOptions = [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Lấy tất cả bản ghi
        ];
        
        // Lấy dữ liệu tham gia sự kiện đã xóa
        $thamGiaSuKiens = $this->model->search($searchCriteria, $searchOptions);
        
        // Tạo file Excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập font chữ và style
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getFont()->setSize(12);
        $sheet->getStyle('A1:I1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('F5F5F5');
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Thiết lập tiêu đề
        $sheet->setCellValue('A1', 'STT');
        $sheet->setCellValue('B1', 'ID');
        $sheet->setCellValue('C1', 'Người dùng ID');
        $sheet->setCellValue('D1', 'Sự kiện ID');
        $sheet->setCellValue('E1', 'Thời gian điểm danh');
        $sheet->setCellValue('F1', 'Phương thức điểm danh');
        $sheet->setCellValue('G1', 'Ghi chú');
        $sheet->setCellValue('H1', 'Trạng thái');
        $sheet->setCellValue('I1', 'Ngày xóa');
        
        // Điền dữ liệu
        $row = 2;
        foreach ($thamGiaSuKiens as $i => $item) {
            $sheet->setCellValue('A' . $row, $i + 1);
            $sheet->setCellValue('B' . $row, $item->tham_gia_su_kien_id);
            $sheet->setCellValue('C' . $row, $item->nguoi_dung_id);
            $sheet->setCellValue('D' . $row, $item->su_kien_id);
            
            // Thời gian điểm danh
            if (!empty($item->thoi_gian_diem_danh)) {
                $thoi_gian = $item->thoi_gian_diem_danh instanceof \CodeIgniter\I18n\Time
                    ? $item->thoi_gian_diem_danh->format('d/m/Y H:i:s')
                    : date('d/m/Y H:i:s', strtotime($item->thoi_gian_diem_danh));
                $sheet->setCellValue('E' . $row, $thoi_gian);
            } else {
                $sheet->setCellValue('E' . $row, 'Chưa điểm danh');
            }
            
            // Phương thức điểm danh
            $phuongThuc = 'Thủ công';
            if ($item->phuong_thuc_diem_danh == 'qr_code') {
                $phuongThuc = 'QR Code';
            } elseif ($item->phuong_thuc_diem_danh == 'face_id') {
                $phuongThuc = 'Face ID';
            }
            $sheet->setCellValue('F' . $row, $phuongThuc);
            
            // Ghi chú
            $sheet->setCellValue('G' . $row, $item->ghi_chu ?? '');
            
            // Trạng thái
            $sheet->setCellValue('H' . $row, $item->status == 1 ? 'Hoạt động' : 'Không hoạt động');
            
            // Ngày xóa
            $deleted_at = !empty($item->deleted_at) 
                ? ($item->deleted_at instanceof \CodeIgniter\I18n\Time 
                    ? $item->deleted_at->format('d/m/Y H:i:s') 
                    : date('d/m/Y H:i:s', strtotime($item->deleted_at)))
                : '';
            $sheet->setCellValue('I' . $row, $deleted_at);
            
            // Căn giữa các cột
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('H' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            
            $row++;
        }
        
        // Tự động điều chỉnh độ rộng cột
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Thêm border cho toàn bộ bảng
        $lastRow = $row - 1;
        $sheet->getStyle('A1:I' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        // Tạo thông tin bộ lọc
        $row = $lastRow + 2;
        $sheet->setCellValue('A' . $row, 'Thông tin bộ lọc:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $row++;
        
        if (!empty($keyword)) {
            $sheet->setCellValue('A' . $row, 'Từ khóa:');
            $sheet->setCellValue('B' . $row, $keyword);
            $row++;
        }
        
        if ($status !== null && $status !== '') {
            $sheet->setCellValue('A' . $row, 'Trạng thái:');
            $sheet->setCellValue('B' . $row, $status == 1 ? 'Hoạt động' : 'Không hoạt động');
            $row++;
        }
        
        if ($phuong_thuc_diem_danh !== null && $phuong_thuc_diem_danh !== '') {
            $sheet->setCellValue('A' . $row, 'Phương thức điểm danh:');
            $phuongThucText = 'Thủ công';
            if ($phuong_thuc_diem_danh == 'qr_code') {
                $phuongThucText = 'QR Code';
            } elseif ($phuong_thuc_diem_danh == 'face_id') {
                $phuongThucText = 'Face ID';
            }
            $sheet->setCellValue('B' . $row, $phuongThucText);
            $row++;
        }
        
        if ($nguoi_dung_id !== null && $nguoi_dung_id !== '') {
            $sheet->setCellValue('A' . $row, 'Người dùng ID:');
            $sheet->setCellValue('B' . $row, $nguoi_dung_id);
            $row++;
        }
        
        if ($su_kien_id !== null && $su_kien_id !== '') {
            $sheet->setCellValue('A' . $row, 'Sự kiện ID:');
            $sheet->setCellValue('B' . $row, $su_kien_id);
            $row++;
        }
        
        $row++;
        $sheet->setCellValue('A' . $row, 'Tổng số bản ghi:');
        $sheet->setCellValue('B' . $row, count($thamGiaSuKiens));
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Ngày xuất:');
        $sheet->setCellValue('B' . $row, date('d/m/Y H:i:s'));
        
        // Thiết lập header để tải xuống
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="danh_sach_tham_gia_su_kien_da_xoa.xlsx"');
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
     * @param string|null $phuong_thuc_diem_danh Phương thức điểm danh
     * @param string|null $nguoi_dung_id ID người dùng
     * @param string|null $su_kien_id ID sự kiện
     * @return string
     */
    private function getFilterDescription($keyword = null, $status = null, $phuong_thuc_diem_danh = null, $nguoi_dung_id = null, $su_kien_id = null)
    {
        $filters = [];
        
        if (!empty($keyword)) {
            $filters[] = "Từ khóa: " . $keyword;
        }
        
        if (isset($status) && $status !== '') {
            $filters[] = "Trạng thái: " . ($status == 1 ? 'Hoạt động' : 'Không hoạt động');
        }
        
        if (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh !== '') {
            $phuongThucText = 'Thủ công';
            if ($phuong_thuc_diem_danh == 'qr_code') {
                $phuongThucText = 'QR Code';
            } elseif ($phuong_thuc_diem_danh == 'face_id') {
                $phuongThucText = 'Face ID';
            }
            $filters[] = "Phương thức điểm danh: " . $phuongThucText;
        }
        
        if (isset($nguoi_dung_id) && $nguoi_dung_id !== '') {
            $filters[] = "Người dùng ID: " . $nguoi_dung_id;
        }
        
        if (isset($su_kien_id) && $su_kien_id !== '') {
            $filters[] = "Sự kiện ID: " . $su_kien_id;
        }
        
        return implode(', ', $filters);
    }

    // Thêm vào phương thức này để hỗ trợ tìm kiếm các bản ghi đã xóa
    public function searchDeleted(array $params, array $options = [])
    {
        // Đảm bảo withDeleted được thiết lập
        $this->model->withDeleted();
        
        // Thêm điều kiện để chỉ lấy các bản ghi đã xóa
        $params['deleted'] = true;
        
        // Sử dụng phương thức search hiện tại với tham số đã sửa đổi
        return $this->model->search($params, $options);
    }
    
    // Đếm số lượng bản ghi đã xóa theo tiêu chí tìm kiếm
    public function countDeletedResults(array $params)
    {
        // Đảm bảo withDeleted được thiết lập
        $this->model->withDeleted();
        
        // Thêm điều kiện để chỉ lấy các bản ghi đã xóa
        $params['deleted'] = true;
        
        // Sử dụng phương thức countSearchResults hiện tại với tham số đã sửa đổi
        return $this->model->countSearchResults($params);
    }
} 