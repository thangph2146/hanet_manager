<?php

namespace App\Modules\diengia\Controllers;

use App\Controllers\BaseController;
use App\Modules\diengia\Models\DienGiaModel;
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
    protected $session;
    protected $data;
    protected $permission;
    
    public function __construct()
    {
        // Khởi tạo session sớm
        $this->session = service('session');
        
        // Khởi tạo các thành phần cần thiết
        $this->model = new DienGiaModel();
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
        $sort = $this->request->getGet('sort') ?? 'thu_tu';
        $order = $this->request->getGet('order') ?? 'ASC';
        $keyword = $this->request->getGet('keyword') ?? '';
        
        // Kiểm tra các tham số không hợp lệ
        if ($page < 1) $page = 1;
        if ($perPage < 1) $perPage = 10;
        
        // Log chi tiết URL và tham số
        log_message('debug', '[Controller] URL đầy đủ: ' . current_url() . '?' . http_build_query($_GET));
        log_message('debug', '[Controller] Tham số request: ' . json_encode($_GET));
        log_message('debug', '[Controller] Đã xử lý: page=' . $page . ', perPage=' . $perPage . ', sort=' . $sort . 
            ', order=' . $order . ', keyword=' . $keyword);
        
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
        
        // Log tham số tìm kiếm cuối cùng
        log_message('debug', '[Controller] Tham số tìm kiếm cuối cùng: ' . json_encode($searchParams));
        
        // Lấy dữ liệu diễn giả và thông tin phân trang
        $diengia = $this->model->search($searchParams, [
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
            $redirectUrl = site_url('diengia') . '?' . http_build_query($redirectParams);
            
            // Chuyển hướng đến trang cuối cùng
            return redirect()->to($redirectUrl);
        }
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath('diengia');
            // Thêm tất cả các tham số cần giữ lại khi chuyển trang
            $pager->setOnly(['keyword', 'perPage', 'sort', 'order']);
            
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
        log_message('debug', '[Controller] Số lượng diễn giả trả về: ' . count($diengia));
        
        // Chuẩn bị dữ liệu cho view
        $this->data['diengias'] = $diengia;
        $this->data['pager'] = $pager;
        $this->data['currentPage'] = $page;
        $this->data['perPage'] = $perPage;
        $this->data['total'] = $total;
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['keyword'] = $keyword;
        
        // Debug thông tin cuối cùng
        log_message('debug', '[Controller] Dữ liệu gửi đến view: ' . json_encode([
            'currentPage' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'pageCount' => $pager ? $pager->getPageCount() : 0,
            'diengia_count' => count($diengia)
        ]));
        
        // Hiển thị view
        return view('App\Modules\diengia\Views\index', $this->data);
    }
    
    /**
     * Hiển thị form tạo mới
     */
    public function new()
    {
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Thêm mới', current_url());
        
        // Chuẩn bị dữ liệu mặc định cho entity mới
        $diengia = new \App\Modules\diengia\Entities\DienGia([
            'bin' => 0,
            'thu_tu' => 0
        ]);
        
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Thêm mới ' . $this->moduleName,
            'validation' => $this->validator,
            'moduleUrl' => $this->moduleUrl,
            'diengia' => $diengia,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
            'is_new' => true
        ];
        
        return view('App\Modules\diengia\Views\new', $viewData);
    }
    
    /**
     * Xử lý tạo mới diễn giả
     */
    public function create()
    {
        // Xóa tất cả quy tắc validation mặc định để tự thiết lập
        $this->validator = \Config\Services::validation();
        
        // Thiết lập quy tắc validation tùy chỉnh, chỉ bắt buộc trường ten_dien_gia
        $rules = [
            'ten_dien_gia' => [
                'rules' => 'required|min_length[3]|max_length[255]|is_unique[dien_gia.ten_dien_gia]',
                'errors' => [
                    'required' => 'Tên diễn giả là bắt buộc',
                    'min_length' => 'Tên diễn giả phải có ít nhất {param} ký tự',
                    'max_length' => 'Tên diễn giả không được vượt quá {param} ký tự',
                    'is_unique' => 'Tên diễn giả đã tồn tại, vui lòng chọn tên khác',
                ]
            ],
            'chuc_danh' => [
                'rules' => 'permit_empty|max_length[255]',
                'errors' => [
                    'max_length' => 'Chức danh không được vượt quá {param} ký tự',
                ]
            ],
            'to_chuc' => [
                'rules' => 'permit_empty|max_length[255]',
                'errors' => [
                    'max_length' => 'Tổ chức không được vượt quá {param} ký tự',
                ]
            ],
            'thu_tu' => [
                'rules' => 'permit_empty|integer',
                'errors' => [
                    'integer' => 'Thứ tự phải là số nguyên',
                ]
            ],
            'status' => [
                'rules' => 'permit_empty|integer|in_list[0,1]',
                'errors' => [
                    'integer' => 'Trạng thái phải là số nguyên',
                    'in_list' => 'Trạng thái phải là 0 hoặc 1',
                ]
            ]
        ];
        
        // Log thông tin request để debug
        log_message('debug', '[CREATE] Form Method: ' . $this->request->getMethod());
        log_message('debug', '[CREATE] POST data: ' . json_encode($_POST));
        log_message('debug', '[CREATE] FILES variable: ' . json_encode($_FILES));
        
        // Xác thực dữ liệu gửi lên với quy tắc tùy chỉnh
        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('error', '[CREATE] Validation errors: ' . json_encode($errors));
            return $this->new();
        }
        
        // Lấy dữ liệu từ request
        $data = [
            'ten_dien_gia' => trim($this->request->getPost('ten_dien_gia')),
            'chuc_danh' => trim($this->request->getPost('chuc_danh') ?? ''),
            'to_chuc' => trim($this->request->getPost('to_chuc') ?? ''),
            'gioi_thieu' => trim($this->request->getPost('gioi_thieu') ?? ''),
            'thu_tu' => (int)($this->request->getPost('thu_tu') ?? 0),
            'status' => (int)($this->request->getPost('status') ?? 1),
            'bin' => 0
        ];

        // Kiểm tra xem tên diễn giả đã tồn tại chưa
        if (!empty($data['ten_dien_gia'])) {
            // Tìm trực tiếp trong database 
            $existingDiengia = $this->model->builder()
                ->where('ten_dien_gia', $data['ten_dien_gia'])
                ->where($this->model->deletedField, null)  // Loại trừ records đã xóa mềm
                ->get()
                ->getRow();
                
            if ($existingDiengia) {
                $this->alert->set('danger', 'Tên diễn giả "' . $data['ten_dien_gia'] . '" đã tồn tại, vui lòng chọn tên khác', true);
                return redirect()->back()->withInput();
            }
        }
        
        // Lấy đối tượng file avatar
        $avatarFile = $this->request->getFile('avatar');
        
        // Log thông tin về file để debug
        if ($avatarFile) {
            log_message('debug', '[CREATE] Avatar File Info: ' . json_encode([
                'name' => $avatarFile->getName(),
                'size' => $avatarFile->getSize(),
                'type' => $avatarFile->getClientMimeType(),
                'error' => $avatarFile->getError(),
                'isValid' => $avatarFile->isValid(),
                'hasMoved' => $avatarFile->hasMoved()
            ]));
        } else {
            log_message('debug', '[CREATE] Không có file avatar được tải lên');
        }
        
        // Xử lý upload file avatar
        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
            log_message('debug', '[CREATE] Processing valid avatar file');
            $avatarPath = $this->uploadAndCompressImage($avatarFile);
            
            if ($avatarPath) {
                log_message('debug', '[CREATE] Upload successful: ' . $avatarPath);
                $data['avatar'] = $avatarPath;
            } else {
                log_message('error', '[CREATE] Avatar upload failed');
                $this->alert->set('warning', 'Không thể tải lên ảnh đại diện, nhưng diễn giả vẫn được tạo', true);
            }
        } else if ($avatarFile) {
            // Log lỗi cụ thể nếu file không hợp lệ
            log_message('error', '[CREATE] Avatar file invalid: ' . 
                ($avatarFile->isValid() ? 'Valid' : 'Invalid') . ', ' . 
                ($avatarFile->hasMoved() ? 'Has moved' : 'Has not moved') . 
                ', Error: ' . $avatarFile->getError());
        }
        
        // Log dữ liệu sẽ lưu
        log_message('debug', '[CREATE] Data to insert: ' . json_encode($data));
        
        // Lưu vào database
        try {
            if ($this->model->insert($data)) {
                $this->alert->set('success', 'Thêm diễn giả thành công', true);
                return redirect()->to($this->moduleUrl);
            } else {
                // Nếu có lỗi validation thì hiển thị lỗi
                $errors = $this->model->errors() ? implode(', ', $this->model->errors()) : 'Unknown error';
                log_message('error', '[CREATE] Insert failed: ' . $errors);
                
                return redirect()->back()->withInput()->with('errors', $this->model->errors());
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
        $diengia = $this->model->findWithRelations($id);
        
        if (empty($diengia)) {
            $this->alert->set('danger', 'Không tìm thấy diễn giả', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Cập nhật breadcrumb
        $this->breadcrumb->add('Chi tiết', current_url());
        
        // Chuẩn bị dữ liệu cho view
        $viewData = [
            'breadcrumb' => $this->breadcrumb->render(),
            'title' => 'Chi tiết ' . $this->moduleName,
            'diengia' => $diengia,
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
        $diengia = $this->model->findWithRelations($id);
        
        if (empty($diengia)) {
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
            'diengia' => $diengia,
            'moduleUrl' => $this->moduleUrl,
            'errors' => session()->getFlashdata('errors') ?? ($this->validator ? $this->validator->getErrors() : []),
        ];
        
        return view('App\Modules\diengia\Views\edit', $viewData);
    }
    
    /**
     * Cập nhật diễn giả
     */
    public function update($id = null)
    {
        if (empty($id)) {
            $this->alert->set('danger', 'ID diễn giả không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy thông tin diễn giả
        $existingDiengia = $this->model->find($id);
        
        if (empty($existingDiengia)) {
            $this->alert->set('danger', 'Không tìm thấy diễn giả', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Xóa tất cả quy tắc validation mặc định để tự thiết lập
        $this->validator = \Config\Services::validation();
        
        // Thiết lập quy tắc validation tùy chỉnh, chỉ bắt buộc trường ten_dien_gia
        $rules = [
            'dien_gia_id' => 'required|integer',
            'ten_dien_gia' => [
                'rules' => 'required|min_length[3]|max_length[255]|is_unique[dien_gia.ten_dien_gia,dien_gia_id,'.$id.']',
                'errors' => [
                    'required' => 'Tên diễn giả là bắt buộc',
                    'min_length' => 'Tên diễn giả phải có ít nhất {param} ký tự',
                    'max_length' => 'Tên diễn giả không được vượt quá {param} ký tự',
                    'is_unique' => 'Tên diễn giả đã tồn tại, vui lòng chọn tên khác',
                ]
            ],
            'chuc_danh' => [
                'rules' => 'permit_empty|max_length[255]',
                'errors' => [
                    'max_length' => 'Chức danh không được vượt quá {param} ký tự',
                ]
            ],
            'to_chuc' => [
                'rules' => 'permit_empty|max_length[255]',
                'errors' => [
                    'max_length' => 'Tổ chức không được vượt quá {param} ký tự',
                ]
            ],
            'thu_tu' => [
                'rules' => 'permit_empty|integer',
                'errors' => [
                    'integer' => 'Thứ tự phải là số nguyên',
                ]
            ],
            'status' => [
                'rules' => 'permit_empty|integer|in_list[0,1]',
                'errors' => [
                    'integer' => 'Trạng thái phải là số nguyên',
                    'in_list' => 'Trạng thái phải là 0 hoặc 1',
                ]
            ]
        ];
        
        // Log thông tin request để debug
        log_message('debug', '[UPDATE] Form Method: ' . $this->request->getMethod());
        log_message('debug', '[UPDATE] POST data: ' . json_encode($_POST));
        log_message('debug', '[UPDATE] FILES variable: ' . json_encode($_FILES));
        
        // Xác thực dữ liệu gửi lên với quy tắc tùy chỉnh
        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();
            log_message('error', '[UPDATE] Validation errors: ' . json_encode($errors));
            return $this->edit($id);
        }
        
        // Lấy dữ liệu từ form
        $data = $this->request->getPost();
        
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
            'status' => $data['status'] ?? 1,
        ];
        
        // Lấy đối tượng file avatar (không sử dụng getPost)
        $avatarFile = $this->request->getFile('avatar');
        
        // Log thông tin về file để debug
        if ($avatarFile) {
            log_message('debug', '[UPDATE] Avatar File Info: ' . json_encode([
                'name' => $avatarFile->getName(),
                'size' => $avatarFile->getSize(),
                'type' => $avatarFile->getClientMimeType(),
                'error' => $avatarFile->getError(),
                'isValid' => $avatarFile->isValid(),
                'hasMoved' => $avatarFile->hasMoved()
            ]));
        } else {
            log_message('debug', '[UPDATE] Không có file avatar được tải lên');
        }
        
        // Xử lý upload file avatar nếu có
        if ($avatarFile && $avatarFile->isValid() && !$avatarFile->hasMoved()) {
            log_message('debug', '[UPDATE] Processing valid avatar file');
            $avatarPath = $this->uploadAndCompressImage($avatarFile);
            
            if ($avatarPath) {
                log_message('debug', '[UPDATE] Upload successful: ' . $avatarPath);
                $updateData['avatar'] = $avatarPath;
                
                // Xóa ảnh cũ nếu có
                if (!empty($existingDiengia->avatar)) {
                    log_message('debug', '[UPDATE] Deleting old avatar: ' . $existingDiengia->avatar);
                    $this->deleteOldImage($existingDiengia->avatar);
                }
            } else {
                log_message('error', '[UPDATE] Avatar upload failed');
                $this->alert->set('warning', 'Không thể tải lên ảnh đại diện mới, giữ nguyên ảnh hiện tại', true);
            }
        } else if ($avatarFile) {
            // Log lỗi cụ thể nếu file không hợp lệ
            log_message('error', '[UPDATE] Avatar file invalid: ' . 
                ($avatarFile->isValid() ? 'Valid' : 'Invalid') . ', ' . 
                ($avatarFile->hasMoved() ? 'Has moved' : 'Has not moved') . 
                ', Error: ' . $avatarFile->getError());
        } else {
            // Giữ nguyên avatar hiện tại
            log_message('debug', '[UPDATE] Keeping existing avatar: ' . $existingDiengia->avatar);
            $updateData['avatar'] = $existingDiengia->avatar;
        }
        
        // Giữ lại các trường thời gian từ dữ liệu hiện có
        $updateData['created_at'] = $existingDiengia->created_at;
        
        // Log dữ liệu sẽ cập nhật
        log_message('debug', '[UPDATE] Data to update: ' . json_encode($updateData));
        
        // Cập nhật dữ liệu vào database
        if ($this->model->update($id, $updateData)) {
            $this->alert->set('success', 'Cập nhật diễn giả thành công', true);
            return redirect()->to($this->moduleUrl);
        } else {
            $errors = $this->model->errors() ? implode(', ', $this->model->errors()) : 'Unknown error';
            log_message('error', '[UPDATE] Update failed: ' . $errors);
            
            $this->alert->set('danger', 'Có lỗi xảy ra khi cập nhật diễn giả: ' . $errors, true);
            return redirect()->back()->withInput();
        }
    }
    
    /**
     * Xóa mềm (sử dụng deleted_at)
     */
    public function delete($id = null, $backToUrl = null)
    {
        // Sử dụng ID từ tham số URL thay vì từ POST
        if (empty($id)) {
            $this->alert->set('danger', 'ID diễn giả không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Đảm bảo ID là số nguyên
        $id = (int)$id;
        
        // Debug lưu lại ID nhận được 
        log_message('debug', '[DELETE] ID nhận được: ' . $id);
        
        // Thực hiện xóa mềm (đặt deleted_at)
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
        $status = $this->request->getGet('status');
        
        // Kiểm tra các tham số không hợp lệ
        if ($page < 1) $page = 1;
        if ($perPage < 1) $perPage = 10;
        
        // Log chi tiết URL và tham số
        log_message('debug', '[Controller:listdeleted] URL đầy đủ: ' . current_url() . '?' . http_build_query($_GET));
        log_message('debug', '[Controller:listdeleted] Tham số request: ' . json_encode($_GET));
        log_message('debug', '[Controller:listdeleted] Đã xử lý: page=' . $page . ', perPage=' . $perPage . ', sort=' . $sort . 
            ', order=' . $order . ', keyword=' . $keyword . ', status=' . $status);
        
        // Thiết lập số liên kết trang hiển thị xung quanh trang hiện tại
        $this->model->setSurroundCount(3);
        
        // Xây dựng tham số tìm kiếm
        $searchParams = [
            'bin' => 1, // Đánh dấu lấy các bản ghi đã xóa mềm
            'sort' => $sort,
            'order' => $order
        ];
        
        // Thêm keyword nếu có
        if (!empty($keyword)) {
            $searchParams['keyword'] = $keyword;
        }
        
        // Đặc biệt, chỉ thêm status vào nếu nó đã được chỉ định (bao gồm status=0)
        if ($status !== null && $status !== '') {
            $searchParams['status'] = (int)$status;
        }
        
        // Log tham số tìm kiếm cuối cùng
        log_message('debug', '[Controller:listdeleted] Tham số tìm kiếm cuối cùng: ' . json_encode($searchParams));
        
        // Lấy dữ liệu diễn giả và thông tin phân trang
        $diengia = $this->model->search($searchParams, [
            'limit' => $perPage,
            'offset' => ($page - 1) * $perPage,
            'sort' => $sort,
            'order' => $order
        ]);
        
        $total = $this->model->countSearchResults($searchParams);
        
        // Lấy pager từ model và thiết lập các tham số
        $pager = $this->model->getPager();
        if ($pager !== null) {
            $pager->setPath('diengia/listdeleted');
            // Không cần thiết lập segment vì chúng ta sử dụng query string
            $pager->setOnly(['keyword', 'perPage', 'sort', 'order', 'status']);
            
            // Đảm bảo perPage được thiết lập đúng trong pager
            $pager->setPerPage($perPage);
            
            // Thiết lập trang hiện tại
            $pager->setCurrentPage($page);
        }
        
        // Chuẩn bị dữ liệu cho view
        $this->data['diengias'] = $diengia;
        $this->data['pager'] = $pager;
        $this->data['currentPage'] = $page;
        $this->data['perPage'] = $perPage;
        $this->data['total'] = $total;
        $this->data['sort'] = $sort;
        $this->data['order'] = $order;
        $this->data['keyword'] = $keyword;
        $this->data['status'] = $status;
        
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
        
        // Lấy thông tin diễn giả để xóa file ảnh
        $diengia = $this->model->find($id);
        if ($diengia && !empty($diengia->avatar)) {
            $this->deleteOldImage($diengia->avatar);
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
            'diengia' => $results,
            'pager' => $this->model->pager,
            'keyword' => $keyword,
            'filters' => $filters,
            'moduleUrl' => $this->moduleUrl
        ];
        
        return view('App\Modules\diengia\Views\search', $viewData);
    }
    
    /**
     * Xóa nhiều diễn giả (xóa mềm)
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
        // Debug trước khi làm bất cứ điều gì
        log_message('debug', '============== STATUS MULTIPLE DEBUG ==============');
        log_message('debug', 'Tất cả POST data: ' . print_r($_POST, true));
        log_message('debug', 'Raw request body: ' . file_get_contents('php://input'));
        log_message('debug', 'Request method: ' . $this->request->getMethod());
        log_message('debug', 'selected_ids từ getPost(): ' . print_r($this->request->getPost('selected_ids'), true));
        log_message('debug', 'selected_ids từ $_POST: ' . print_r($_POST['selected_ids'] ?? 'không có', true));
        log_message('debug', '============== END DEBUG ==============');        
        // Kiểm tra nếu request không phải POST (không phân biệt chữ hoa/thường)
        if (strtolower($this->request->getMethod()) !== 'post') {            
            $this->alert->set('danger', 'Phương thức không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy danh sách ID và trạng thái mới
        $ids = $this->request->getPost('selected_ids');
        $status = $this->request->getPost('status');
        $returnUrl = $this->request->getPost('return_url');
        
        // Debug để kiểm tra dữ liệu nhận được
        log_message('debug', 'POST data received: ' . print_r($this->request->getPost(), true));
        log_message('debug', 'Selected IDs: ' . (is_array($ids) ? json_encode($ids) : $ids));
        
        if (empty($ids)) {
            $this->alert->set('danger', 'Không có diễn giả nào được chọn', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Đảm bảo $ids luôn là array
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        
        // Nếu không có status được chỉ định, đảo ngược trạng thái hiện tại của mỗi item
        if ($status === null) {
            log_message('debug', 'No status specified, toggling current status for each item');
            $success = true;
            $count = 0;
            
            foreach ($ids as $id) {
                $diengia = $this->model->find($id);
                if ($diengia) {
                    $currentStatus = $diengia->getStatus();
                    $newStatus = $currentStatus == 1 ? 0 : 1;
                    log_message('debug', "ID: $id - Changing status from $currentStatus to $newStatus");
                    
                    if ($this->model->update($id, ['status' => $newStatus])) {
                        $count++;
                        log_message('debug', "Successfully changed status for ID $id");
                    } else {
                        $success = false;
                        log_message('error', "Failed to change status for ID: $id");
                    }
                } else {
                    log_message('error', "Diengia with ID: $id not found");
                    $success = false;
                }
            }
        } else {
            // Cập nhật trạng thái cho tất cả các ID với giá trị status được chỉ định
            $success = true;
            $count = 0;
            
            foreach ($ids as $id) {
                if ($this->model->update($id, ['status' => (int)$status])) {
                    $count++;
                } else {
                    $success = false;
                    log_message('error', "Failed to update status for ID: $id");
                }
            }
        }
        
        // Trả về kết quả cho người dùng
        if ($count > 0) {
            $this->alert->set('success', "Đã đổi trạng thái {$count} diễn giả thành công", true);
        } else {
            $this->alert->set('danger', 'Có lỗi xảy ra khi đổi trạng thái diễn giả', true);
        }
        
        // Xử lý URL chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        
        return redirect()->to($redirectUrl);
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
                if ($this->model->restoreFromRecycleBin($id)) {
                    $successCount++;
                    log_message('debug', 'RestoreMultiple - Khôi phục thành công ID: ' . $id);
                } else {
                    $failCount++;
                    $errors = $this->model->errors() ? json_encode($this->model->errors()) : 'Unknown error';
                    log_message('error', 'RestoreMultiple - Lỗi khôi phục ID: ' . $id . ', Errors: ' . $errors);
                    $errorMessages[] = "Lỗi khôi phục ID: {$id}";
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
            // Lấy thông tin diễn giả để xóa file ảnh
            $diengia = $this->model->find($id);
            if ($diengia && !empty($diengia->avatar)) {
                $this->deleteOldImage($diengia->avatar);
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
     * Xuất danh sách camera ra file Excel
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
        
        // Lấy dữ liệu camera theo bộ lọc mà không giới hạn phân trang
        $cameras = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        log_message('debug', '[ExportExcel] Số lượng Diễn giả xuất: ' . count($cameras));
        
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
        $sheet->setCellValue('B' . $headerRow, 'Tên Diễn giả');
        $sheet->setCellValue('C' . $headerRow, 'Chức danh');
        $sheet->setCellValue('D' . $headerRow, 'Tổ chức');
        $sheet->setCellValue('E' . $headerRow, 'Giới thiệu');
        $sheet->setCellValue('F' . $headerRow, 'TRẠNG THÁI');        
        
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
            $sheet->setCellValue('B' . $row, $camera->ten_dien_gia);
            $sheet->setCellValue('C' . $row, $camera->chuc_danh);
            $sheet->setCellValue('D' . $row, $camera->to_chuc);
            $sheet->setCellValue('E' . $row, $camera->gioi_thieu);            
            
            // Xử lý trạng thái
            $status = $camera->status == 1 ? 'Hoạt động' : 'Không hoạt động';
            $sheet->setCellValue('F' . $row, $status);
            
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
        
        // Thêm ngày xuất báo cáo
        $row += 1;
        $sheet->setCellValue('A' . $row, 'Ngày xuất báo cáo: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A' . $row . ':F' . $row);
        
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
     * Xuất danh sách camera ra file PDF
     */
    public function exportPdf()
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
        
        // Lấy dữ liệu camera theo bộ lọc mà không giới hạn phân trang
        $diengias = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        // Chuẩn bị dữ liệu cho view
        $data = [
            'title' => 'DANH SÁCH DIỄN GIẢ',
            'diengias' => $diengias,
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
        if ($status !== null && $status !== '') {
            $filterSuffix .= '_status_' . $status;
        }
        
        $filename = 'danh_sach_dien_gia' . $filterSuffix . '_' . date('dmY_His') . '.pdf';
        
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
        $diengias = $this->model->search($searchParams, [
            'sort' => $sort,
            'order' => $order,
            'limit' => 0 // Không giới hạn số lượng kết quả
        ]);
        
        // Tạo dữ liệu cho PDF
        $pdfData = [
            'title' => 'DANH SÁCH DIỄN GIẢ ĐÃ XÓA',
            'diengias' => $diengias,
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
        if ($status !== null && $status !== '') {
            $filterSuffix .= '_status_' . $status;
        }
        
        $filename = 'danh_sach_dien_gia_da_xoa' . $filterSuffix . '_' . date('dmY_His') . '.pdf';
        
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
        
        log_message('debug', '[ExportDeletedExcel] Số lượng diễn giả xuất: ' . count($cameras));
        
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
        $headers = ['STT', 'Tên Diễn giả', 'Chức Danh', 'Tổ chức', 'Giới thiệu', 'Trạng thái', 'Ngày xóa'];
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
            $sheet->setCellValue($column++ . $row, $camera->ten_dien_gia);
            $sheet->setCellValue($column++ . $row, $camera->chuc_danh);
            $sheet->setCellValue($column++ . $row, $camera->to_chuc);
            $sheet->setCellValue($column++ . $row, $camera->gioi_thieu);
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
    
    /**
     * Upload và nén ảnh, lưu vào thư mục được tổ chức theo ngày
     * 
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file
     * @return string|null Đường dẫn của file ảnh đã upload hoặc null nếu thất bại
     */
    private function uploadAndCompressImage($file)
    {
        // Log file info để debug
        log_message('debug', 'uploadAndCompressImage - File info: ' . json_encode([
            'name' => $file->getName(),
            'type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'error' => $file->getError(),
            'isValid' => $file->isValid(),
            'hasMoved' => $file->hasMoved()
        ]));
        
        if (!$file->isValid() || $file->hasMoved()) {
            log_message('error', 'Upload thất bại: File không hợp lệ hoặc đã được di chuyển. Error code: ' . $file->getError());
            return null;
        }
        
        // Kiểm tra định dạng file
        $validMimeTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!in_array($file->getClientMimeType(), $validMimeTypes)) {
            log_message('error', 'Upload thất bại: Định dạng file ' . $file->getClientMimeType() . ' không hợp lệ. Chỉ chấp nhận: ' . implode(', ', $validMimeTypes));
            return null;
        }
        
        // Tạo thư mục dựa trên ngày
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $uploadDir = "data/images/{$year}/{$month}/{$day}";
        $fullUploadDir = ROOTPATH . 'public/' . $uploadDir;
        
        // Đảm bảo thư mục tồn tại
        if (!is_dir($fullUploadDir)) {
            log_message('debug', 'Đang tạo thư mục upload: ' . $fullUploadDir);
            if (!mkdir($fullUploadDir, 0777, true)) {
                log_message('error', 'Không thể tạo thư mục upload: ' . $fullUploadDir . '. Vui lòng kiểm tra quyền ghi.');
                return null;
            }
            log_message('debug', 'Đã tạo thư mục mới: ' . $fullUploadDir);
        }
        
        try {
            // Tạo tên file ngẫu nhiên để tránh trùng lặp
            $newName = $file->getRandomName();
            $destPath = $uploadDir . '/' . $newName;
            $fullDestPath = ROOTPATH . 'public/' . $destPath;
            
            log_message('debug', 'Đường dẫn đích cho file: ' . $fullDestPath);
            
            // Nén và lưu ảnh
            $image = \Config\Services::image();
            
            // Khởi tạo image với file tạm
            $image->withFile($file->getTempName());
            
            // Lấy kích thước ảnh gốc
            $imgWidth = $image->getWidth();
            $imgHeight = $image->getHeight();
            
            log_message('debug', 'Kích thước ảnh gốc: ' . $imgWidth . 'x' . $imgHeight);
            
            // Tính toán kích thước mới với chiều rộng tối đa 1000px
            $maxWidth = 1000;
            
            if ($imgWidth > $maxWidth) {
                $newHeight = (int)round(($maxWidth / $imgWidth) * $imgHeight);
                
                log_message('debug', 'Resize ảnh thành: ' . $maxWidth . 'x' . $newHeight);
                
                // Resize ảnh với chất lượng 80%
                $result = $image->resize($maxWidth, $newHeight, true)
                      ->save($fullDestPath, 80);
            } else {
                // Nếu ảnh nhỏ hơn chiều rộng tối đa, chỉ nén chất lượng
                log_message('debug', 'Giữ nguyên kích thước, chỉ nén chất lượng');
                $result = $image->save($fullDestPath, 80);
            }
            
            if ($result) {
                log_message('debug', 'Đã lưu ảnh thành công vào: ' . $destPath);
                return $destPath;
            } else {
                log_message('error', 'Lỗi không xác định khi lưu ảnh');
                return null;
            }
        } catch (\Exception $e) {
            log_message('error', 'Lỗi khi xử lý ảnh: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return null;
        }
    }
    
    /**
     * Xóa file ảnh cũ
     * 
     * @param string|null $path Đường dẫn file ảnh cần xóa
     * @return bool True nếu xóa thành công hoặc không cần xóa, False nếu xóa thất bại
     */
    private function deleteOldImage($path)
    {
        if (empty($path)) {
            return true;
        }
        
        // Kiểm tra xem đường dẫn có phải là URL không
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return true; // Không xóa file từ URL bên ngoài
        }
        
        // Nếu có tiền tố "public/", loại bỏ nó
        if (strpos($path, 'public/') === 0) {
            $path = substr($path, 7);
        }
        
        // Đường dẫn đầy đủ đến file
        $fullPath = ROOTPATH . 'public/' . $path;
        
        // Kiểm tra file tồn tại
        if (file_exists($fullPath)) {
            try {
                // Xóa file
                unlink($fullPath);
                log_message('info', 'Đã xóa file ảnh cũ: ' . $fullPath);
                return true;
            } catch (\Exception $e) {
                log_message('error', 'Không thể xóa file ảnh cũ: ' . $e->getMessage());
                return false;
            }
        }
        
        return true; // File không tồn tại, không cần xóa
    }
    
    public function prepareValidationRules(string $scenario = 'insert', array $data = [])
    {
        $entity = new DienGia();
        $this->validationRules = $entity->getValidationRules();
        $this->validationMessages = $entity->getValidationMessages();
        
        // Loại bỏ các quy tắc validate cho trường thời gian (vì chúng được tự động xử lý bởi model)
        unset($this->validationRules['created_at']);
        unset($this->validationRules['updated_at']);
        unset($this->validationRules['deleted_at']);
        
        // Loại bỏ validation cho trường avatar khi đang xử lý file tải lên
        unset($this->validationRules['avatar']);
        
        // Điều chỉnh quy tắc dựa trên tình huống
        if ($scenario === 'update' && isset($data['dien_gia_id'])) {
            // Khi cập nhật, cần loại trừ chính ID hiện tại khi kiểm tra tính duy nhất
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    // Thay thế placeholder {dien_gia_id} bằng ID thực tế
                    $rules = str_replace('{dien_gia_id}', $data['dien_gia_id'], $rules);
                }
            }
        } elseif ($scenario === 'insert') {
            // Khi thêm mới, bỏ loại trừ ID vì không có ID nào cần loại trừ
            foreach ($this->validationRules as $field => &$rules) {
                if (strpos($rules, 'is_unique') !== false) {
                    $rules = str_replace(',dien_gia_id,{dien_gia_id}', '', $rules);
                }
            }
        }
    }

    /**
     * Hàm thay đổi trạng thái hoạt động của diễn giả
     */
    public function changeStatus($id = null, $backToUrl = null)
    {
        // Debug
        log_message('debug', '============== CHANGE STATUS DEBUG ==============');
        log_message('debug', 'Route ID: ' . $id);
        log_message('debug', 'POST data: ' . print_r($_POST, true));
        log_message('debug', 'Request method: ' . $this->request->getMethod());
        
        // Lấy ID từ route hoặc POST data
        $id = $id ?? $this->request->getPost('id');
        
        // Debug ID sau khi xử lý
        log_message('debug', 'Final ID being used: ' . $id);
        
        if (empty($id)) {
            $this->alert->set('danger', 'ID diễn giả không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Kiểm tra nếu request không phải POST (không phân biệt chữ hoa/thường)
        if (strtolower($this->request->getMethod()) !== 'post') {
            $this->alert->set('danger', 'Phương thức không hợp lệ', true);
            return redirect()->to($this->moduleUrl);
        }
        
        $diengia = $this->model->find($id);
        if ($diengia === null) {
            $this->alert->set('danger', 'Diễn giả không tồn tại', true);
            return redirect()->to($this->moduleUrl);
        }
        
        // Lấy trạng thái hiện tại và đảo ngược
        $currentStatus = $diengia->getStatus();
        $newStatus = $currentStatus == 1 ? 0 : 1;
        
        // Debug
        log_message('debug', "Changing status for ID: $id from $currentStatus to $newStatus");
        
        // Cập nhật trạng thái
        if ($this->model->update($id, ['status' => $newStatus])) {
            $this->alert->set('success', 'Cập nhật trạng thái diễn giả thành công', true);
        } else {
            $this->alert->set('danger', 'Không thể cập nhật trạng thái diễn giả', true);
            // Debug lỗi nếu có
            log_message('error', 'Failed to update status. Model errors: ' . print_r($this->model->errors(), true));
        }
        
        // Lấy URL trả về từ tham số truy vấn hoặc từ tham số đường dẫn
        $returnUrl = $this->request->getPost('return_url') ?? $backToUrl;
        
        // Debug URL chuyển hướng
        log_message('debug', 'Return URL: ' . ($returnUrl ?? 'Not set'));
        
        // Xử lý URL chuyển hướng
        $redirectUrl = $this->processReturnUrl($returnUrl);
        log_message('debug', 'Final redirect URL: ' . $redirectUrl);
        log_message('debug', '============== END CHANGE STATUS DEBUG ==============');
        
        return redirect()->to($redirectUrl);
    }
} 