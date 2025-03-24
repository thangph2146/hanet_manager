<?php

namespace App\Modules\diengia\Controllers;

use App\Controllers\BaseController;
use App\Modules\diengia\Models\DiengiaModel;
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

class DienGia extends BaseController
{
    use ResponseTrait;
    
    protected $model;
    protected $breadcrumb;
    protected $alert;
    protected $moduleUrl;
    protected $moduleName;
    protected $data;
    
    public function __construct()
    {
        // Khởi tạo các thành phần cần thiết
        $this->model = new DiengiaModel();
        $this->breadcrumb = new Breadcrumb();
        $this->alert = new Alert();
        
        // Thông tin module
        $this->moduleUrl = base_url('diengia');
        $this->moduleName = 'Diễn giả';
        
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
        $sort = $this->request->getGet('sort') ?? 'ten_dien_gia';
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
        
        // Xây dựng tham số tìm kiếm
        $searchParams = [];
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Đặc biệt, chỉ thêm status vào nếu nó đã được chỉ định (bao gồm status=0)
        if ($status !== null && $status !== '') {
            $searchParams['status'] = $statusFilter;
        }
        
        // Thêm điều kiện bin = 0 để chỉ lấy những diễn giả không nằm trong thùng rác
        $searchParams['filters'] = ['bin' => 0];
        
        // Log tham số tìm kiếm cuối cùng
        log_message('debug', '[Controller] Tham số tìm kiếm cuối cùng: ' . json_encode($searchParams));
        
        // Lấy dữ liệu diễn giả và thông tin phân trang
        $dienGias = $this->model->search($searchParams, [
            'limit' => $perPage,
            'offset' => $offset,
            'sort' => $sort,
            'order' => $order
        ]);
        
        // Lấy tổng số kết quả
        $total = $this->model->pager ? $this->model->pager->getTotal() : 0;
        log_message('debug', '[Controller] Tổng số kết quả từ pager: ' . $total);
        
        // Nếu trang hiện tại lớn hơn tổng số trang, điều hướng về trang cuối cùng
        $pageCount = ceil($total / $perPage);
        if ($total > 0 && $page > $pageCount) {
            log_message('debug', '[Controller] Trang yêu cầu (' . $page . ') vượt quá tổng số trang (' . $pageCount . '), chuyển hướng về trang cuối.');
            
            // Tạo URL mới với trang cuối cùng
            $redirectParams = $_GET;
            $redirectParams['page'] = $pageCount;
            $redirectUrl = site_url('diengia') . '?' . http_build_query($redirectParams);
            
            // Chuyển hướng đến trang cuối cùng
            return redirect()->to($redirectUrl);
        }
        
        // Lấy pager từ model
        $pager = $this->model->pager;
        if ($pager !== null) {
            $pager->setPath('diengia');
            // Thiết lập số liên kết hiển thị xung quanh trang hiện tại
            $pager->setSurroundCount(3);
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
        
        // Kiểm tra số lượng diễn giả trả về
        log_message('debug', '[Controller] Số lượng diễn giả trả về: ' . count($dienGias));
        if (!empty($dienGias)) {
            $firstDienGia = $dienGias[0];
            log_message('debug', '[Controller] Diễn giả đầu tiên: ID=' . $firstDienGia->dien_gia_id . 
                ', Tên=' . $firstDienGia->ten_dien_gia . 
                ', Tổ chức=' . $firstDienGia->to_chuc);
        }
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'dienGias' => $dienGias,
            'pager' => $pager,
            'perPage' => $perPage,
            'currentPage' => $page,
            'total' => $total,
            'keyword' => $keyword,
            'moduleUrl' => $this->moduleUrl,
            'session' => service('session')
        ];
        
        // Debug thông tin cuối cùng
        log_message('debug', '[Controller] Dữ liệu gửi đến view: ' . json_encode([
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'pageCount' => $pager ? $pager->getPageCount() : 0,
            'status' => $status,
            'diengia_count' => count($dienGias)
        ]));
        
        // Hiển thị view
        return view('App\Modules\diengia\Views\index', $viewData);
    }
    
    /**
     * Hiển thị form tạo mới
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Chuẩn bị dữ liệu mặc định cho entity mới
        $dienGia = new \App\Modules\diengia\Entities\DienGia([
            'bin' => 0,
            'thu_tu' => 0
        ]);
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm ' . $this->moduleName,
            'validation' => $this->validator,
            'moduleUrl' => $this->moduleUrl,
            'dienGia' => $dienGia,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
            'is_new' => true
        ];
        
        return view('App\Modules\diengia\Views\new', $viewData);
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
            'ten_dien_gia' => trim($request->getPost('ten_dien_gia')),
            'chuc_danh' => trim($request->getPost('chuc_danh')),
            'to_chuc' => trim($request->getPost('to_chuc')),
            'gioi_thieu' => $request->getPost('gioi_thieu'),
            'thu_tu' => $request->getPost('thu_tu') ?? 0,
            'bin' => 0
        ];

        // Xử lý upload avatar nếu có
        $avatar = $this->request->getFile('avatar');
        if ($avatar && $avatar->isValid() && !$avatar->hasMoved()) {
            $newName = $avatar->getRandomName();
            $uploadPath = ROOTPATH . 'public/data/images/diengia/';
            
            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            if ($avatar->move($uploadPath, $newName)) {
                $data['avatar'] = $newName;
                
                // Tạo thumbnail nếu cần
                $thumbPath = $uploadPath . 'thumbs/';
                if (!is_dir($thumbPath)) {
                    mkdir($thumbPath, 0755, true);
                }
                
                // Có thể thêm code xử lý thumbnail ở đây nếu cần
            }
        }

        // Kiểm tra xem tên diễn giả đã tồn tại chưa
        if (!empty($data['ten_dien_gia'])) {
            if ($this->model->isNameExists($data['ten_dien_gia'])) {
                $this->alert->set('danger', 'Tên diễn giả "' . $data['ten_dien_gia'] . '" đã tồn tại, vui lòng chọn tên khác', true);
                return redirect()->back()->withInput();
            }
        }

        // Lưu dữ liệu vào database
        try {
            if ($this->model->insert($data)) {
                $this->alert->set('success', 'Thêm diễn giả thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                $errors = $this->model->errors();
                if (!empty($errors)) {
                    return redirect()->back()->withInput()->with('errors', $errors);
                }
                
                $this->alert->set('danger', 'Có lỗi xảy ra khi thêm diễn giả', true);
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            // Log lỗi
            log_message('error', 'Lỗi khi thêm diễn giả: ' . $e->getMessage());
            
            // Kiểm tra nếu là lỗi duplicate key
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $this->alert->set('danger', 'Tên diễn giả đã tồn tại trong hệ thống, vui lòng chọn tên khác', true);
            } else {
                $this->alert->set('danger', 'Có lỗi xảy ra khi thêm diễn giả: ' . $e->getMessage(), true);
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
            $this->alert->set('danger', 'ID diễn giả không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin diễn giả với relationship
        $dienGia = $this->model->findWithRelations($id);
        
        if (empty($dienGia)) {
            $this->alert->set('danger', 'Không tìm thấy diễn giả', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chi tiết', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chi tiết ' . $this->moduleName,
            'dienGia' => $dienGia,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\diengia\Views\view', $viewData);
    }
    
    /**
     * Hiển thị form chỉnh sửa
     */
    public function edit($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID diễn giả không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Sử dụng phương thức findWithRelations từ model
        $dienGia = $this->model->findWithRelations($id);
        
        if (empty($dienGia)) {
            $this->alert->set('danger', 'Không tìm thấy diễn giả', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chỉnh sửa', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chỉnh sửa ' . $this->moduleName,
            'validation' => $this->validator,
            'dienGia' => $dienGia,
            'moduleUrl' => $this->moduleUrl,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
        ];
        
        return view('App\Modules\diengia\Views\edit', $viewData);
    }
    
    /**
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID diễn giả không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin diễn giả với relationship
        $existingDienGia = $this->model->findWithRelations($id);
        
        if (empty($existingDienGia)) {
            $this->alert->set('danger', 'Không tìm thấy diễn giả', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xác thực dữ liệu gửi lên
        $data = $this->request->getPost();
        
        // Chuẩn bị quy tắc validation cho cập nhật - cần truyền mảng có chứa dien_gia_id
        $this->model->prepareValidationRules('update', ['dien_gia_id' => $id]);
        
        // Xử lý validation với quy tắc đã được điều chỉnh
        if (!$this->validate($this->model->getValidationRules())) {
            // Nếu validation thất bại, quay lại form với lỗi
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Kiểm tra xem tên diễn giả đã tồn tại chưa (trừ chính nó)
        if (!empty($data['ten_dien_gia']) && $this->model->isNameExists($data['ten_dien_gia'], $id)) {
            $this->alert->set('danger', 'Tên diễn giả đã tồn tại', true);
            return redirect()->back()->withInput();
        }
        
        // Chuẩn bị dữ liệu để cập nhật
        $updateData = [
            'ten_dien_gia' => $data['ten_dien_gia'],
            'chuc_danh' => $data['chuc_danh'] ?? null,
            'to_chuc' => $data['to_chuc'] ?? null,
            'gioi_thieu' => $data['gioi_thieu'] ?? null,
            'thu_tu' => $data['thu_tu'] ?? 0,
            'bin' => $data['bin'] ?? 0
        ];
        
        // Xử lý upload avatar nếu có
        $avatar = $this->request->getFile('avatar');
        if ($avatar && $avatar->isValid() && !$avatar->hasMoved()) {
            $newName = $avatar->getRandomName();
            $uploadPath = ROOTPATH . 'public/data/images/diengia/';
            
            // Tạo thư mục nếu chưa tồn tại
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            if ($avatar->move($uploadPath, $newName)) {
                // Xóa ảnh cũ nếu có
                if (!empty($existingDienGia->avatar)) {
                    $oldImagePath = $uploadPath . $existingDienGia->avatar;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                    
                    // Xóa thumbnail cũ nếu có
                    $oldThumbPath = $uploadPath . 'thumbs/' . $existingDienGia->avatar;
                    if (file_exists($oldThumbPath)) {
                        unlink($oldThumbPath);
                    }
                }
                
                $updateData['avatar'] = $newName;
                
                // Tạo thumbnail nếu cần
                $thumbPath = $uploadPath . 'thumbs/';
                if (!is_dir($thumbPath)) {
                    mkdir($thumbPath, 0755, true);
                }
                
                // Có thể thêm code xử lý thumbnail ở đây nếu cần
            }
        }
        
        // Giữ lại các trường thời gian từ dữ liệu hiện có
        $updateData['created_at'] = $existingDienGia->created_at;
        if ($existingDienGia->deleted_at) {
            $updateData['deleted_at'] = $existingDienGia->deleted_at;
        }
        
        // Cập nhật dữ liệu vào database
        if ($this->model->update($id, $updateData)) {
            $this->alert->set('success', 'Cập nhật diễn giả thành công', true);
            return redirect()->to($this->moduleUrl);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi cập nhật diễn giả: ' . implode(', ', $this->model->errors()), true);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Xóa mềm (chuyển vào thùng rác)
     */
    public function delete($id = null, $backToUrl = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID diễn giả không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Thực hiện xóa mềm (chuyển vào thùng rác)
        if ($this->model->moveToRecycleBin($id)) {
            $this->alert->set('success', 'Đã chuyển diễn giả vào thùng rác', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa diễn giả', true);
        }
        
        // Lấy URL trả về từ tham số truy vấn hoặc từ tham số đường dẫn
        $returnUrl = $this->request->getGet('return_url') ?? $backToUrl;
        
        // Xử lý URL chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
    }
    
    /**
     * Hiển thị danh sách diễn giả trong thùng rác
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
        
        // Kiểm tra các tham số không hợp lệ
        if ($page < 1) $page = 1;
        if ($perPage < 1) $perPage = 10;
        
        // Log chi tiết URL và tham số
        log_message('debug', '[Controller:listdeleted] URL đầy đủ: ' . current_url() . '?' . http_build_query($_GET));
        log_message('debug', '[Controller:listdeleted] Tham số request: ' . json_encode($_GET));
        log_message('debug', '[Controller:listdeleted] Đã xử lý: page=' . $page . ', perPage=' . $perPage . ', sort=' . $sort . 
            ', order=' . $order . ', keyword=' . $keyword);
        
        // Xây dựng tham số tìm kiếm
        $searchParams = [];
        
        // Thêm keyword nếu có
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Luôn lấy các diễn giả trong thùng rác
        $searchParams['filters'] = ['bin' => 1];
        
        // Log tham số tìm kiếm cuối cùng
        log_message('debug', '[Controller:listdeleted] Tham số tìm kiếm cuối cùng: ' . json_encode($searchParams));
        
        // Lấy dữ liệu diễn giả và thông tin phân trang
        $dienGias = $this->model->search($searchParams, [
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage,
            'sort' => $sort,
            'order' => $order
        ]);
        
        $total = $this->model->pager ? $this->model->pager->getTotal() : 0;
        
        // Lấy pager từ model
        $pager = $this->model->pager;
        if ($pager !== null) {
            $pager->setPath('diengia/listdeleted');
            // Không cần thiết lập segment vì chúng ta sử dụng query string
            $pager->setOnly(['keyword', 'perPage', 'sort', 'order']);
            
            // Đảm bảo perPage được thiết lập đúng trong pager
            $pager->setPerPage($perPage);
            
            // Thiết lập trang hiện tại
            $pager->setCurrentPage($page);
        }
        
        // Chuẩn bị dữ liệu cho view
        $this->data['dienGias'] = $dienGias;
        $this->data['pager'] = $pager;
        $this->data['currentPage'] = $page;
        $this->data['perPage'] = $perPage;
        $this->data['total'] = $total;
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['keyword'] = $keyword;
        
        // Hiển thị view
        return view('App\Modules\diengia\Views\listdeleted', $this->data);
    }
    
    /**
     * Khôi phục từ thùng rác
     */
    public function restore($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID diễn giả không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form
        $returnUrl = $this->request->getPost('return_url');
        log_message('debug', 'Restore - Return URL: ' . ($returnUrl ?? 'None'));
        
        if ($this->model->restoreFromRecycleBin($id)) {
            $this->alert->set('success', 'Đã khôi phục diễn giả từ thùng rác', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi khôi phục diễn giả', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xóa vĩnh viễn một diễn giả
     */
    public function permanentDelete($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID diễn giả không hợp lệ', true);
            return redirect()->to($this->moduleUrl . '/listdeleted');
        }
        
        // Lấy URL trả về từ form
        $returnUrl = $this->request->getPost('return_url');
        log_message('debug', 'PermanentDelete - Return URL: ' . ($returnUrl ?? 'None'));
        
        // Lấy thông tin diễn giả để xóa file ảnh nếu có
        $dienGia = $this->model->find($id);
        if ($dienGia && !empty($dienGia->avatar)) {
            $imagePath = ROOTPATH . 'public/data/images/diengia/' . $dienGia->avatar;
            $thumbPath = ROOTPATH . 'public/data/images/diengia/thumbs/' . $dienGia->avatar;
            
            // Xóa file ảnh gốc
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            // Xóa thumbnail
            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }
        }
        
        if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
            $this->alert->set('success', 'Đã xóa vĩnh viễn diễn giả', true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi xóa diễn giả', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Tìm kiếm diễn giả
     */
    public function search()
    {
        // Lấy dữ liệu từ request
        $keyword = $this->request->getGet('keyword');
        
        // Chuẩn bị tiêu chí tìm kiếm
        $criteria = [];
        
        if (!empty($keyword)) {
            $criteria['keyword'] = $keyword;
        }
        
        // Thêm điều kiện lấy diễn giả không nằm trong thùng rác
        $criteria['filters'] = ['bin' => 0];
        
        // Thiết lập tùy chọn
        $options = [
            'sort' => $this->request->getGet('sort') ?? 'thu_tu',
            'order' => $this->request->getGet('order') ?? 'ASC',
            'limit' => (int)($this->request->getGet('length') ?? 10),
            'offset' => (int)($this->request->getGet('start') ?? 0)
        ];
        
        // Thực hiện tìm kiếm
        $results = $this->model->search($criteria, $options);
        
        // Tổng số kết quả
        $totalRecords = $this->model->pager ? $this->model->pager->getTotal() : 0;
        
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
            'dienGias' => $results,
            'pager' => $this->model->pager,
            'keyword' => $keyword,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\diengia\Views\search', $viewData);
    }
    
    /**
     * Xóa nhiều diễn giả (chuyển vào thùng rác)
     */
    public function deleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedIds = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn diễn giả nào để xóa', true);
            
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
            $this->alert->set('success', "Đã chuyển $successCount diễn giả vào thùng rác", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa diễn giả', true);
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
     * Thay đổi trạng thái nhiều diễn giả
     */
    public function statusMultiple()
    {
        $selectedIds = $this->request->getPost('selected_ids');
        $newStatus = $this->request->getPost('status');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn diễn giả nào để thay đổi trạng thái', true);
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
            $this->alert->set('success', "Đã chuyển $successCount diễn giả sang trạng thái $statusText", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể thay đổi trạng thái', true);
        }
        
        return redirect()->to($this->moduleUrl);
    }
    
    /**
     * Khôi phục nhiều diễn giả từ thùng rác
     */
    public function restoreMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedIds = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn diễn giả nào để khôi phục', true);
            
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
                $dienGia = $this->model->find($id);
                if (!$dienGia) {
                    log_message('error', 'RestoreMultiple - Không tìm thấy diễn giả với ID: ' . $id);
                    $failCount++;
                    $errorMessages[] = "Không tìm thấy diễn giả ID: {$id}";
                    continue;
                }
                
                // Kiểm tra xem diễn giả có đang trong thùng rác không
                if ($dienGia->bin != 1) {
                    log_message('warning', 'RestoreMultiple - Diễn giả ID: ' . $id . ' không nằm trong thùng rác (bin = ' . $dienGia->bin . ')');
                    $failCount++;
                    $errorMessages[] = "Diễn giả ID: {$id} không nằm trong thùng rác";
                    continue;
                }
                
                // Đặt lại trạng thái bin và lưu
                $dienGia->bin = 0;
                if ($this->model->save($dienGia)) {
                    $successCount++;
                    log_message('debug', 'RestoreMultiple - Khôi phục thành công ID: ' . $id);
                } else {
                    $failCount++;
                    $errors = $this->model->errors() ? json_encode($this->model->errors()) : 'Unknown error';
                    log_message('error', 'RestoreMultiple - Lỗi lưu diễn giả ID: ' . $id . ', Errors: ' . $errors);
                    $errorMessages[] = "Lỗi lưu diễn giả ID: {$id}";
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
                $this->alert->set('warning', "Đã khôi phục {$successCount} diễn giả, nhưng có {$failCount} diễn giả không thể khôi phục", true);
            } else {
                $this->alert->set('success', "Đã khôi phục {$successCount} diễn giả từ thùng rác", true);
            }
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể khôi phục diễn giả nào', true);
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
     * Xóa vĩnh viễn nhiều diễn giả
     */
    public function permanentDeleteMultiple()
    {
        // Lấy các ID được chọn và URL trả về
        $selectedIds = $this->request->getPost('selected_ids');
        $returnUrl = $this->request->getPost('return_url');
        
        if (empty($selectedIds)) {
            $this->alert->set('warning', 'Chưa chọn diễn giả nào để xóa vĩnh viễn', true);
            
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
            // Lấy thông tin diễn giả để xóa file ảnh nếu có
            $dienGia = $this->model->find($id);
            if ($dienGia && !empty($dienGia->avatar)) {
                $imagePath = ROOTPATH . 'public/data/images/diengia/' . $dienGia->avatar;
                $thumbPath = ROOTPATH . 'public/data/images/diengia/thumbs/' . $dienGia->avatar;
                
                // Xóa file ảnh gốc
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                
                // Xóa file ảnh thumbnail nếu có
                if (file_exists($thumbPath)) {
                    unlink($thumbPath);
                }
            }
            
            if ($this->model->delete($id, true)) { // true = xóa vĩnh viễn
                $successCount++;
            }
        }
        
        if ($successCount > 0) {
            $this->alert->set('success', "Đã xóa vĩnh viễn $successCount diễn giả", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra, không thể xóa diễn giả', true);
        }
        
        // Chuyển hướng đến URL đích đã xử lý
        $redirectUrl = $this->processReturnUrl($returnUrl);
        return redirect()->to($redirectUrl ?: $this->moduleUrl . '/listdeleted');
    }
    
    /**
     * Xuất danh sách diễn giả ra file Excel
     */
    public function exportExcel()
    {
        // Lấy tham số lọc từ URL
        $keyword = $this->request->getGet('keyword') ?? '';
        $status = $this->request->getGet('status');
        $sort = $this->request->getGet('sort') ?? 'ten_dien_gia';
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
        
        // Lấy dữ liệu diễn giả theo bộ lọc mà không giới hạn phân trang
        $dienGias = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        log_message('debug', '[ExportExcel] Số lượng diễn giả xuất: ' . count($dienGias));
        
        // Sử dụng thư viện PhpSpreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập tiêu đề
        $sheet->setCellValue('A1', 'DANH SÁCH DIỄN GIẢ');
        
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
        $sheet->setCellValue('B' . $headerRow, 'MÃ DIỄN GIẢ');
        $sheet->setCellValue('C' . $headerRow, 'TÊN DIỄN GIẢ');
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
        foreach ($dienGias as $dienGia) {
            $sheet->setCellValue('A' . $row, $i);
            $sheet->setCellValue('B' . $row, $dienGia->ma_dien_gia);
            $sheet->setCellValue('C' . $row, $dienGia->ten_dien_gia);
            $sheet->setCellValue('D' . $row, $dienGia->ip_dien_gia);
            $sheet->setCellValue('E' . $row, $dienGia->port);
            $sheet->setCellValue('F' . $row, $dienGia->username);
            
            // Xử lý trạng thái
            $status = $dienGia->status == 1 ? 'Hoạt động' : 'Không hoạt động';
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
        
        $filename = 'Danh_sach_dien_gia' . $filterSuffix . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        // Redirect output to client browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    
    /**
     * Xuất danh sách diễn giả ra file PDF
     */
    public function exportPdf()
    {
        // Lấy tham số lọc từ URL
        $keyword = $this->request->getGet('keyword') ?? '';
        $sort = $this->request->getGet('sort') ?? 'ten_dien_gia';
        $order = $this->request->getGet('order') ?? 'ASC';
        
        // Xây dựng tham số tìm kiếm
        $searchParams = ['filters' => ['bin' => 0]];
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Lấy dữ liệu diễn giả theo bộ lọc mà không giới hạn phân trang
        $dienGias = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'DANH SÁCH DIỄN GIẢ',
            'dienGias' => $dienGias,
            'date' => date('d/m/Y H:i:s')
        ];
        
        // Thêm thông tin bộ lọc vào tiêu đề nếu có
        $filterInfo = [];
        if (!empty($keyword)) {
            $filterInfo[] = "Từ khóa: " . $keyword;
        }
        
        if (!empty($filterInfo)) {
            $data['filters'] = implode(', ', $filterInfo);
        }
        
        // Render view thành HTML
        $html = view('App\Modules\diengia\Views\export_pdf', $data);
        
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
        
        $filename = 'danh_sach_dien_gia' . $filterSuffix . '_' . date('dmY_His') . '.pdf';
        
        // Stream file PDF để tải xuống
        $dompdf->stream($filename, ['Attachment' => true]);
        exit();
    }
    
    /**
     * Xuất danh sách diễn giả đã xóa ra file PDF
     */
    public function exportDeletedPdf()
    {
        // Lấy tham số lọc từ URL
        $keyword = $this->request->getGet('keyword') ?? '';
        $sort = $this->request->getGet('sort') ?? 'updated_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        
        // Xây dựng tham số tìm kiếm
        $searchParams = ['filters' => ['bin' => 1]]; // Luôn lấy các diễn giả trong thùng rác
        
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Lấy dữ liệu diễn giả theo bộ lọc mà không giới hạn phân trang
        $dienGias = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        // Tạo dữ liệu cho PDF
        $pdfData = [
            'title' => 'DANH SÁCH DIỄN GIẢ ĐÃ XÓA',
            'dienGias' => $dienGias,
            'date' => date('d/m/Y H:i:s')
        ];
        
        // Thêm thông tin bộ lọc vào tiêu đề nếu có
        $filterInfo = [];
        if (!empty($keyword)) {
            $filterInfo[] = "Từ khóa: " . $keyword;
        }
        
        if (!empty($filterInfo)) {
            $pdfData['filters'] = implode(', ', $filterInfo);
        }
        
        // Render view thành HTML
        $html = view('App\Modules\diengia\Views\export_pdf', $pdfData);
        
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
        
        $filename = 'danh_sach_dien_gia_da_xoa' . $filterSuffix . '_' . date('dmY_His') . '.pdf';
        
        // Stream file PDF để tải xuống
        $dompdf->stream($filename, ['Attachment' => true]);
        exit();
    }
    
    /**
     * Xuất danh sách diễn giả đã xóa ra file Excel
     */
    public function exportDeletedExcel()
    {
        // Lấy tham số lọc từ URL
        $keyword = $this->request->getGet('keyword') ?? '';
        $sort = $this->request->getGet('sort') ?? 'updated_at';
        $order = $this->request->getGet('order') ?? 'DESC';
        
        // Xây dựng tham số tìm kiếm
        $searchParams = ['filters' => ['bin' => 1]]; // Luôn lấy các diễn giả trong thùng rác
        
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Log tham số tìm kiếm cuối cùng
        log_message('debug', '[ExportDeletedExcel] Tham số tìm kiếm: ' . json_encode($searchParams));
        
        // Lấy dữ liệu diễn giả theo bộ lọc mà không giới hạn phân trang
        $dienGias = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        log_message('debug', '[ExportDeletedExcel] Số lượng diễn giả xuất: ' . count($dienGias));
        
        // Tạo đối tượng spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Thiết lập tiêu đề
        $sheet->setCellValue('A1', 'DANH SÁCH DIỄN GIẢ ĐÃ XÓA');
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Thêm thông tin bộ lọc vào tiêu đề nếu có
        $filterInfo = [];
        if (!empty($keyword)) {
            $filterInfo[] = "Từ khóa: " . $keyword;
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
        $headers = ['STT', 'Tên Diễn Giả', 'Chức Danh', 'Tổ Chức', 'Giới Thiệu', 'Thứ Tự', 'Ngày Xóa'];
        $column = 'A';
        
        foreach ($headers as $header) {
            $sheet->setCellValue($column . $headerRow, $header);
            $sheet->getStyle($column . $headerRow)->getFont()->setBold(true);
            $column++;
        }
        
        // Thêm dữ liệu
        $row = $headerRow + 1;
        $count = 1;
        foreach ($dienGias as $dienGia) {
            $column = 'A';
            $sheet->setCellValue($column++ . $row, $count++);
            $sheet->setCellValue($column++ . $row, $dienGia->ten_dien_gia);
            $sheet->setCellValue($column++ . $row, $dienGia->chuc_danh);
            $sheet->setCellValue($column++ . $row, $dienGia->to_chuc);
            $sheet->setCellValue($column++ . $row, $dienGia->getSummary(30));
            $sheet->setCellValue($column++ . $row, $dienGia->thu_tu);
            $sheet->setCellValue($column++ . $row, $dienGia->deleted_at ? date('d/m/Y H:i', strtotime($dienGia->deleted_at)) : '');
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
        
        $filename = 'Danh_sach_dien_gia_da_xoa' . $filterSuffix . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        
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